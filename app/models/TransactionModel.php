<?php

class TransactionModel extends Model
{
    public function all(): array
    {
        $statement = $this->db->query(
            'SELECT
                o.order_id,
                o.order_code,
                o.tanggal_order,
                o.status_order,
                o.total_qty,
                o.grand_total,
                COALESCE(NULLIF(TRIM(c.nama), \'\'), \'Pelanggan Umum\') AS customer_name
            FROM orders o
            LEFT JOIN (
                SELECT customer_id, MAX(nama) AS nama
                FROM customers
                GROUP BY customer_id
            ) c ON c.customer_id = o.customer_id
            ORDER BY o.tanggal_order DESC, o.order_id DESC'
        );

        return $statement->fetchAll();
    }

    public function getOrderProtectionState(int $orderId): array
    {
        $statement = $this->db->prepare(
            'SELECT
                o.order_id,
                o.status_order,
                COALESCE(SUM(CASE WHEN p.status_bayar = :paid_status_amount THEN p.jumlah_bayar ELSE 0 END), 0) AS total_paid,
                COALESCE(SUM(CASE WHEN p.status_bayar = :paid_status_count THEN 1 ELSE 0 END), 0) AS paid_payment_rows,
                COUNT(p.payment_id) AS payment_rows
             FROM orders o
             LEFT JOIN payments p ON p.order_id = o.order_id
             WHERE o.order_id = :order_id
             GROUP BY o.order_id, o.status_order
             LIMIT 1'
        );
        $statement->execute([
            'order_id' => $orderId,
            'paid_status_amount' => self::PAYMENT_STATUS_PAID,
            'paid_status_count' => self::PAYMENT_STATUS_PAID,
        ]);
        $row = $statement->fetch();

        if (!$row) {
            throw new InvalidArgumentException('Order tidak ditemukan.');
        }

        $status = (string) $row['status_order'];
        $paidPaymentRows = (int) $row['paid_payment_rows'];
        $paymentRows = (int) $row['payment_rows'];

        return [
            'order_id' => (int) $row['order_id'],
            'status_order' => $status,
            'total_paid' => (float) $row['total_paid'],
            'paid_payment_rows' => $paidPaymentRows,
            'payment_rows' => $paymentRows,
            'has_paid_payments' => $paidPaymentRows > 0,
            'has_any_payments' => $paymentRows > 0,
            'is_completed' => $status === self::ORDER_STATUS_DONE,
            'is_financially_protected' => $paidPaymentRows > 0 || $status === self::ORDER_STATUS_DONE,
        ];
    }

    public function canDeleteOrder(int $orderId): array
    {
        $state = $this->getOrderProtectionState($orderId);

        if ($state['has_paid_payments']) {
            return [
                'allowed' => false,
                'message' => 'Pesanan tidak dapat dihapus karena sudah memiliki pembayaran paid.',
                'state' => $state,
            ];
        }

        if ($state['is_completed']) {
            return [
                'allowed' => false,
                'message' => 'Pesanan selesai tidak dapat dihapus. Gunakan koreksi/catatan pembatalan bila diperlukan.',
                'state' => $state,
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'state' => $state,
        ];
    }

    public function deleteOrderSafely(int $orderId): array
    {
        try {
            $guard = $this->canDeleteOrder($orderId);
            if (!$guard['allowed']) {
                return [
                    'success' => false,
                    'message' => $guard['message'],
                ];
            }

            $this->db->beginTransaction();
            $this->deleteOrderDetails($orderId);
            $deleteOrder = $this->db->prepare('DELETE FROM orders WHERE order_id = :order_id');
            $deleteOrder->execute(['order_id' => $orderId]);
            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Pesanan berhasil dihapus.',
            ];
        } catch (Throwable) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Pesanan gagal dihapus. Silakan periksa status pesanan dan coba lagi.',
            ];
        }
    }

    public function canEditOrder(int $orderId): array
    {
        $state = $this->getOrderProtectionState($orderId);

        if ($state['is_financially_protected']) {
            return [
                'allowed' => false,
                'message' => 'Pesanan sudah dibayar atau selesai sehingga tidak dapat diedit langsung. Gunakan koreksi eksplisit.',
                'state' => $state,
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'state' => $state,
        ];
    }

    public function updateOrderSafely(int $orderId, array $data): array
    {
        try {
            $guard = $this->canEditOrder($orderId);
            if (!$guard['allowed']) {
                return [
                    'success' => false,
                    'message' => $guard['message'],
                ];
            }

            $status = $data['status_order'] ?? self::ORDER_STATUS_PENDING;
            if (!in_array((string) $status, self::ALLOWED_ORDER_STATUSES, true)) {
                return [
                    'success' => false,
                    'message' => 'Status order tidak valid. Pilih salah satu: ' . implode(', ', self::ALLOWED_ORDER_STATUSES) . '.',
                ];
            }

            $statement = $this->db->prepare(
                'UPDATE orders
                 SET status_order = :status_order,
                     catatan = :catatan
                 WHERE order_id = :order_id'
            );
            $statement->execute([
                'order_id' => $orderId,
                'status_order' => (string) $status,
                'catatan' => $data['catatan'] ?? null,
            ]);

            return [
                'success' => true,
                'message' => 'Pesanan berhasil diperbarui.',
            ];
        } catch (Throwable) {
            return [
                'success' => false,
                'message' => 'Pesanan gagal diperbarui. Silakan periksa status pesanan dan coba lagi.',
            ];
        }
    }

    public function deletePaymentSafely(int $paymentId): array
    {
        if ($this->paymentExists($paymentId)) {
            return [
                'success' => false,
                'message' => 'Pembayaran tidak dapat dihapus langsung. Buat transaksi koreksi untuk menjaga riwayat keuangan.',
            ];
        }

        return [
            'success' => false,
            'message' => 'Pembayaran tidak ditemukan.',
        ];
    }

    public function updatePaymentSafely(int $paymentId, array $data): array
    {
        if ($this->paymentExists($paymentId)) {
            return [
                'success' => false,
                'message' => 'Pembayaran tidak dapat diedit langsung. Buat transaksi koreksi untuk menjaga riwayat keuangan.',
            ];
        }

        return [
            'success' => false,
            'message' => 'Pembayaran tidak ditemukan.',
        ];
    }

    public function generateOrderCode(?DateTimeInterface $date = null): string
    {
        $date ??= new DateTimeImmutable();
        $year = $date->format('Y');
        $ownsTransaction = !$this->db->inTransaction();

        if ($ownsTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $orderCode = $this->generateOrderCodeLocked($year);

            if ($ownsTransaction) {
                $this->db->commit();
            }

            return $orderCode;
        } catch (Throwable $e) {
            if ($ownsTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function createOrder(array $data): array
    {
        $tanggalOrder = $this->normalizeOrderDate($data['tanggal_order'] ?? null);
        $ownsTransaction = !$this->db->inTransaction();

        if ($ownsTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $orderCode = $this->generateOrderCodeLocked($tanggalOrder->format('Y'));

            $statement = $this->db->prepare(
                'INSERT INTO orders (
                    order_code,
                    customer_id,
                    tanggal_order,
                    status_order,
                    catatan,
                    total_qty,
                    subtotal,
                    total_addon,
                    grand_total,
                    user_id
                ) VALUES (
                    :order_code,
                    :customer_id,
                    :tanggal_order,
                    :status_order,
                    :catatan,
                    :total_qty,
                    :subtotal,
                    :total_addon,
                    :grand_total,
                    :user_id
                )'
            );

            $statement->execute([
                'order_code' => $orderCode,
                'customer_id' => $data['customer_id'] ?? null,
                'tanggal_order' => $tanggalOrder->format('Y-m-d H:i:s'),
                'status_order' => $data['status_order'] ?? self::ORDER_STATUS_PENDING,
                'catatan' => $data['catatan'] ?? null,
                'total_qty' => (int) ($data['total_qty'] ?? 0),
                'subtotal' => (float) ($data['subtotal'] ?? 0),
                'total_addon' => (float) ($data['total_addon'] ?? 0),
                'grand_total' => (float) ($data['grand_total'] ?? 0),
                'user_id' => $data['user_id'] ?? null,
            ]);

            $orderId = (int) $this->db->lastInsertId();

            if ($ownsTransaction) {
                $this->db->commit();
            }

            return [
                'order_id' => $orderId,
                'order_code' => $orderCode,
            ];
        } catch (Throwable $e) {
            if ($ownsTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function saveOrderAtomically(array $payload): array
    {
        $ownsTransaction = !$this->db->inTransaction();

        if ($ownsTransaction) {
            $this->db->beginTransaction();
        }

        try {
            $items = $payload['items'] ?? [];
            if (!is_array($items) || $items === []) {
                throw new InvalidArgumentException('Minimal satu item pesanan harus diisi.');
            }

            $order = $this->createOrder($payload['order'] ?? []);
            $orderId = (int) $order['order_id'];
            $totalQty = 0;
            $subtotal = 0.0;
            $totalAddon = 0.0;

            foreach ($items as $item) {
                if (!is_array($item)) {
                    throw new InvalidArgumentException('Format item pesanan tidak valid.');
                }

                $sizes = $item['sizes'] ?? [];
                $qty = (int) ($item['qty'] ?? 0);
                $sizeValidation = $this->validateSizeBreakdownAgainstItemQuantity($qty, is_array($sizes) ? $sizes : []);
                if (!$sizeValidation['valid']) {
                    throw new InvalidArgumentException((string) $sizeValidation['message']);
                }

                if (!empty($item['variant_id'])) {
                    $minimumValidation = $this->validateMinimumOrderQuantity((int) $item['variant_id'], $qty);
                    if (!$minimumValidation['valid']) {
                        throw new InvalidArgumentException((string) $minimumValidation['message']);
                    }
                }

                $savedItem = $this->saveOrderItem([
                    'order_id' => $orderId,
                    'variant_id' => $item['variant_id'] ?? null,
                    'desain_referensi' => $item['desain_referensi'] ?? null,
                    'qty' => $qty,
                    'harga_satuan' => $item['harga_satuan'] ?? 0,
                    'catatan_item' => $item['catatan_item'] ?? null,
                ]);
                $totalQty += $qty;
                $subtotal += (float) $savedItem['subtotal'];

                foreach ($sizes as $sizeName => $sizeQty) {
                    if ((int) $sizeQty <= 0) {
                        continue;
                    }

                    $this->saveOrderItemSize([
                        'order_item_id' => $savedItem['order_item_id'],
                        'ukuran' => (string) $sizeName,
                        'qty' => (int) $sizeQty,
                    ]);
                }

                foreach (($item['addons'] ?? []) as $addon) {
                    if (!is_array($addon)) {
                        throw new InvalidArgumentException('Format addon pesanan tidak valid.');
                    }

                    $savedAddon = $this->saveOrderItemAddon([
                        'order_item_id' => $savedItem['order_item_id'],
                        'addon_id' => $addon['addon_id'] ?? null,
                        'qty' => $addon['qty'] ?? 0,
                        'biaya_satuan' => $addon['biaya_satuan'] ?? 0,
                    ]);
                    $totalAddon += (float) $savedAddon['subtotal'];
                }
            }

            $totals = $this->updateOrderTotals($orderId, $totalQty, $subtotal, $totalAddon);

            foreach (($payload['payments'] ?? []) as $payment) {
                if (!is_array($payment)) {
                    throw new InvalidArgumentException('Format pembayaran tidak valid.');
                }

                $this->savePayment($orderId, $payment);
            }

            if ($ownsTransaction) {
                $this->db->commit();
            }

            return [
                'success' => true,
                'order_id' => $orderId,
                'order_code' => $order['order_code'],
                'totals' => $totals,
                'error' => null,
            ];
        } catch (Throwable) {
            if ($ownsTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return [
                'success' => false,
                'order_id' => null,
                'order_code' => null,
                'totals' => null,
                'error' => 'Pesanan gagal disimpan. Silakan periksa data pesanan dan coba lagi.',
            ];
        }
    }

    public function saveOrderItem(array $data): array
    {
        $qty = (int) ($data['qty'] ?? 0);
        $hargaSatuan = (float) ($data['harga_satuan'] ?? 0);
        $subtotal = $this->calculateLineSubtotal($qty, $hargaSatuan);

        $statement = $this->db->prepare(
            'INSERT INTO order_items (
                order_id,
                variant_id,
                desain_referensi,
                qty,
                harga_satuan,
                subtotal,
                catatan_item
            ) VALUES (
                :order_id,
                :variant_id,
                :desain_referensi,
                :qty,
                :harga_satuan,
                :subtotal,
                :catatan_item
            )'
        );

        $statement->execute([
            'order_id' => (int) $data['order_id'],
            'variant_id' => (int) $data['variant_id'],
            'desain_referensi' => $data['desain_referensi'] ?? null,
            'qty' => $qty,
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
            'catatan_item' => $data['catatan_item'] ?? null,
        ]);

        return [
            'order_item_id' => (int) $this->db->lastInsertId(),
            'subtotal' => $subtotal,
        ];
    }

    public function saveOrderItemSize(array $data): array
    {
        $statement = $this->db->prepare(
            'INSERT INTO order_item_sizes (
                order_item_id,
                ukuran,
                qty
            ) VALUES (
                :order_item_id,
                :ukuran,
                :qty
            )'
        );

        $statement->execute([
            'order_item_id' => (int) $data['order_item_id'],
            'ukuran' => (string) $data['ukuran'],
            'qty' => (int) $data['qty'],
        ]);

        return [
            'order_item_size_id' => (int) $this->db->lastInsertId(),
        ];
    }

    public function saveOrderItemAddon(array $data): array
    {
        $qty = (int) ($data['qty'] ?? 0);
        $biayaSatuan = (float) ($data['biaya_satuan'] ?? 0);
        $subtotal = $this->calculateLineSubtotal($qty, $biayaSatuan);

        $statement = $this->db->prepare(
            'INSERT INTO order_item_addons (
                order_item_id,
                addon_id,
                qty,
                biaya_satuan,
                subtotal
            ) VALUES (
                :order_item_id,
                :addon_id,
                :qty,
                :biaya_satuan,
                :subtotal
            )'
        );

        $statement->execute([
            'order_item_id' => (int) $data['order_item_id'],
            'addon_id' => (int) $data['addon_id'],
            'qty' => $qty,
            'biaya_satuan' => $biayaSatuan,
            'subtotal' => $subtotal,
        ]);

        return [
            'order_item_addon_id' => (int) $this->db->lastInsertId(),
            'subtotal' => $subtotal,
        ];
    }

    public function savePayment(int $orderId, array $data): array
    {
        $statement = $this->db->prepare(
            'INSERT INTO payments (
                order_id,
                tanggal_bayar,
                metode_bayar,
                jumlah_bayar,
                status_bayar,
                keterangan
            ) VALUES (
                :order_id,
                :tanggal_bayar,
                :metode_bayar,
                :jumlah_bayar,
                :status_bayar,
                :keterangan
            )'
        );

        $statement->execute([
            'order_id' => $orderId,
            'tanggal_bayar' => $this->normalizeOrderDate($data['tanggal_bayar'] ?? null)->format('Y-m-d H:i:s'),
            'metode_bayar' => $data['metode_bayar'] ?? self::PAYMENT_METHOD_CASH,
            'jumlah_bayar' => (float) ($data['jumlah_bayar'] ?? 0),
            'status_bayar' => $data['status_bayar'] ?? self::PAYMENT_STATUS_PAID,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        return [
            'payment_id' => (int) $this->db->lastInsertId(),
        ];
    }

    public function calculateLineSubtotal(int $qty, float $unitPrice): float
    {
        return $qty * $unitPrice;
    }

    public function recalculateOrderTotals(int $orderId): array
    {
        $statement = $this->db->prepare(
            'SELECT
                COALESCE(SUM(oi.qty), 0) AS total_qty,
                COALESCE(SUM(oi.subtotal), 0) AS subtotal,
                COALESCE((
                    SELECT SUM(oia.subtotal)
                    FROM order_item_addons oia
                    INNER JOIN order_items oi2 ON oi2.order_item_id = oia.order_item_id
                    WHERE oi2.order_id = :addon_order_id
                ), 0) AS total_addon
             FROM order_items oi
             WHERE oi.order_id = :order_id'
        );
        $statement->execute([
            'order_id' => $orderId,
            'addon_order_id' => $orderId,
        ]);
        $totals = $statement->fetch() ?: [
            'total_qty' => 0,
            'subtotal' => 0,
            'total_addon' => 0,
        ];

        return $this->updateOrderTotals(
            $orderId,
            (int) $totals['total_qty'],
            (float) $totals['subtotal'],
            (float) $totals['total_addon']
        );
    }

    private function updateOrderTotals(int $orderId, int $totalQty, float $subtotal, float $totalAddon): array
    {
        $grandTotal = $subtotal + $totalAddon;
        $update = $this->db->prepare(
            'UPDATE orders
             SET total_qty = :total_qty,
                 subtotal = :subtotal,
                 total_addon = :total_addon,
                 grand_total = :grand_total
             WHERE order_id = :order_id'
        );
        $update->execute([
            'order_id' => $orderId,
            'total_qty' => $totalQty,
            'subtotal' => $subtotal,
            'total_addon' => $totalAddon,
            'grand_total' => $grandTotal,
        ]);

        return [
            'total_qty' => $totalQty,
            'subtotal' => $subtotal,
            'total_addon' => $totalAddon,
            'grand_total' => $grandTotal,
        ];
    }

    public function validateOrderPayloadBeforeSave(array $payload): array
    {
        $errors = [];
        $oldInput = $payload;
        $quantities = $this->extractQuantities($payload);
        $totalQty = array_sum($quantities);

        foreach ($quantities as $field => $qty) {
            if ($qty < 0) {
                $errors[$field] = 'Quantity tidak boleh bernilai negatif.';
            }
        }

        if ($totalQty <= 0) {
            $errors['qty'] = 'Minimal satu item quantity harus diisi.';
        }

        $declaredItemQty = $this->resolveDeclaredItemQuantity($payload, $totalQty);
        $sizeValidation = $this->validateSizeBreakdownAgainstItemQuantity($declaredItemQty, $quantities);
        if (!$sizeValidation['valid']) {
            $errors['size_breakdown'] = $sizeValidation['message'];
        }

        if (!empty($payload['variant_id']) && is_numeric($payload['variant_id'])) {
            try {
                $minimumValidation = $this->validateMinimumOrderQuantity((int) $payload['variant_id'], $declaredItemQty);
                if (!$minimumValidation['valid']) {
                    $errors['minimal_order'] = $minimumValidation['message'];
                }
            } catch (InvalidArgumentException) {
                $errors['variant_id'] = 'Varian produk tidak valid.';
            }
        }

        if (isset($payload['status_order']) && !in_array((string) $payload['status_order'], self::ALLOWED_ORDER_STATUSES, true)) {
            $errors['status_order'] = 'Status order tidak valid. Pilih salah satu: ' . implode(', ', self::ALLOWED_ORDER_STATUSES) . '.';
        }

        if (isset($payload['metode_bayar']) && !in_array((string) $payload['metode_bayar'], self::ALLOWED_PAYMENT_METHODS, true)) {
            $errors['metode_bayar'] = 'Metode pembayaran tidak valid. Pilih salah satu: ' . implode(', ', self::ALLOWED_PAYMENT_METHODS) . '.';
        }

        if (isset($payload['status_bayar']) && !in_array((string) $payload['status_bayar'], self::ALLOWED_PAYMENT_STATUSES, true)) {
            $errors['status_bayar'] = 'Status pembayaran tidak valid. Pilih salah satu: ' . implode(', ', self::ALLOWED_PAYMENT_STATUSES) . '.';
        }

        foreach (['harga_satuan', 'subtotal', 'total_addon', 'grand_total', 'jumlah_bayar', 'paket_bahan'] as $moneyField) {
            if (!array_key_exists($moneyField, $payload) || $payload[$moneyField] === '' || $payload[$moneyField] === null) {
                continue;
            }

            if (!is_numeric($payload[$moneyField]) || (float) $payload[$moneyField] < 0) {
                $errors[$moneyField] = 'Nilai harga atau pembayaran tidak boleh negatif.';
            }
        }

        return [
            'valid' => $errors === [],
            'errors' => $errors,
            'oldInput' => $oldInput,
            'normalized' => [
                'quantities' => $quantities,
                'total_qty' => $totalQty,
            ],
        ];
    }

    public function validateSizeBreakdownAgainstItemQuantity(int $itemQty, array $sizeQuantities): array
    {
        $sizeTotal = 0;

        foreach ($sizeQuantities as $qty) {
            $sizeTotal += max(0, (int) $qty);
        }

        $difference = $sizeTotal - $itemQty;

        if ($difference === 0) {
            return [
                'valid' => true,
                'item_qty' => $itemQty,
                'size_total' => $sizeTotal,
                'difference' => $difference,
                'message' => null,
            ];
        }

        return [
            'valid' => false,
            'item_qty' => $itemQty,
            'size_total' => $sizeTotal,
            'difference' => $difference,
            'message' => sprintf(
                'Total rincian size tidak sesuai. Qty item: %d, total size: %d, selisih: %+d.',
                $itemQty,
                $sizeTotal,
                $difference
            ),
        ];
    }

    public function validateMinimumOrderQuantity(int $variantId, int $itemQty): array
    {
        $minimum = $this->getApplicableMinimumOrder($variantId);

        if ($minimum === null) {
            return [
                'valid' => true,
                'variant_id' => $variantId,
                'minimum' => null,
                'submitted_qty' => $itemQty,
                'source' => null,
                'message' => null,
            ];
        }

        if ($itemQty >= $minimum['minimal_order']) {
            return [
                'valid' => true,
                'variant_id' => $variantId,
                'minimum' => $minimum['minimal_order'],
                'submitted_qty' => $itemQty,
                'source' => $minimum['source'],
                'message' => null,
            ];
        }

        return [
            'valid' => false,
            'variant_id' => $variantId,
            'minimum' => $minimum['minimal_order'],
            'submitted_qty' => $itemQty,
            'source' => $minimum['source'],
            'message' => sprintf(
                'Minimal order untuk varian ini adalah %d pcs, tetapi quantity yang dikirim %d pcs.',
                $minimum['minimal_order'],
                $itemQty
            ),
        ];
    }

    public function getApplicableMinimumOrder(int $variantId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT
                v.variant_id,
                v.minimal_order AS variant_minimal_order,
                p.minimal_order AS product_minimal_order
            FROM product_variants v
            INNER JOIN products p ON p.product_id = v.product_id
            WHERE v.variant_id = :variant_id
            LIMIT 1'
        );
        $statement->execute(['variant_id' => $variantId]);
        $row = $statement->fetch();

        if (!$row) {
            throw new InvalidArgumentException('Varian produk tidak ditemukan.');
        }

        if ($row['variant_minimal_order'] !== null) {
            return [
                'minimal_order' => (int) $row['variant_minimal_order'],
                'source' => 'variant',
            ];
        }

        if ($row['product_minimal_order'] !== null) {
            return [
                'minimal_order' => (int) $row['product_minimal_order'],
                'source' => 'product',
            ];
        }

        return null;
    }

    public function buildCartItemFromPayload(array $payload, array $validation): array
    {
        $quantities = $validation['normalized']['quantities'] ?? $this->extractQuantities($payload);
        $totalQty = (int) ($validation['normalized']['total_qty'] ?? array_sum($quantities));
        $unitPrice = $this->resolveUnitPrice($payload);
        $category = trim((string) ($payload['kategori'] ?? 'T-Shirt'));

        if ($category === '') {
            $category = 'T-Shirt';
        }

        return [
            'kategori' => $category,
            'bahan' => $payload['jenis_bahan'] ?? '-',
            'warna' => $payload['warna_kain'] ?? '-',
            'sablon' => $payload['jenis_sablon'] ?? '-',
            'rincian' => $this->collapseQuantitiesBySize($quantities),
            'qty' => $totalQty,
            'harga' => $totalQty * $unitPrice,
            'old_input' => $payload,
        ];
    }

    private function deleteOrderDetails(int $orderId): void
    {
        $deleteAddons = $this->db->prepare(
            'DELETE oia FROM order_item_addons oia
             INNER JOIN order_items oi ON oi.order_item_id = oia.order_item_id
             WHERE oi.order_id = :order_id'
        );
        $deleteAddons->execute(['order_id' => $orderId]);

        $deleteSizes = $this->db->prepare(
            'DELETE ois FROM order_item_sizes ois
             INNER JOIN order_items oi ON oi.order_item_id = ois.order_item_id
             WHERE oi.order_id = :order_id'
        );
        $deleteSizes->execute(['order_id' => $orderId]);

        $deleteItems = $this->db->prepare('DELETE FROM order_items WHERE order_id = :order_id');
        $deleteItems->execute(['order_id' => $orderId]);
    }

    private function paymentExists(int $paymentId): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM payments WHERE payment_id = :payment_id');
        $statement->execute(['payment_id' => $paymentId]);

        return (int) $statement->fetchColumn() > 0;
    }

    private function resolveDeclaredItemQuantity(array $payload, int $fallback): int
    {
        foreach (['item_qty', 'order_item_qty', 'qty'] as $field) {
            if (array_key_exists($field, $payload) && $payload[$field] !== '' && $payload[$field] !== null) {
                return is_numeric($payload[$field]) ? max(0, (int) $payload[$field]) : 0;
            }
        }

        return $fallback;
    }

    private function extractQuantities(array $payload): array
    {
        $quantities = [];

        foreach ($payload as $field => $value) {
            if (!is_string($field) || !str_starts_with($field, 'qty_')) {
                continue;
            }

            if ($value === '' || $value === null) {
                $value = 0;
            }

            $quantities[$field] = is_numeric($value) ? (int) $value : -1;
        }

        return $quantities;
    }

    private function collapseQuantitiesBySize(array $quantities): array
    {
        $sizes = ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0];

        foreach ($quantities as $field => $qty) {
            foreach (array_keys($sizes) as $size) {
                if (str_ends_with($field, '_' . $size)) {
                    $sizes[$size] += max(0, (int) $qty);
                    break;
                }
            }
        }

        return $sizes;
    }

    private function resolveUnitPrice(array $payload): int
    {
        if (isset($payload['paket_bahan']) && is_numeric($payload['paket_bahan'])) {
            return max(0, (int) $payload['paket_bahan']);
        }

        $materialPrices = [
            'Unione' => 130000,
            'American Drill' => 135000,
            'Nagata Drill' => 140000,
        ];

        $material = (string) ($payload['jenis_bahan'] ?? '');

        return $materialPrices[$material] ?? 60000;
    }

    private function generateOrderCodeLocked(string $year): string
    {
        $select = $this->db->prepare('SELECT last_number FROM invoice_sequences WHERE tahun = :tahun FOR UPDATE');
        $select->execute(['tahun' => $year]);
        $lastNumber = $select->fetchColumn();

        if ($lastNumber === false) {
            $this->seedInvoiceSequenceIfMissing($year);
            $select->execute(['tahun' => $year]);
            $lastNumber = $select->fetchColumn();
        }

        if ($lastNumber === false) {
            throw new RuntimeException('Invoice sequence row not available for year ' . $year);
        }

        $nextNumber = ((int) $lastNumber) + 1;

        $update = $this->db->prepare('UPDATE invoice_sequences SET last_number = :last_number WHERE tahun = :tahun');
        $update->execute([
            'tahun' => $year,
            'last_number' => $nextNumber,
        ]);

        return sprintf('INV-%s-%04d', $year, $nextNumber);
    }

    private function seedInvoiceSequenceIfMissing(string $year): void
    {
        $insert = $this->db->prepare(
            'INSERT INTO invoice_sequences (tahun, last_number)
             SELECT :tahun, COALESCE(MAX(CAST(SUBSTRING(order_code, 10) AS UNSIGNED)), 0)
             FROM orders
             WHERE SUBSTRING(order_code, 5, 4) = :tahun_for_orders
             ON DUPLICATE KEY UPDATE last_number = last_number'
        );

        $insert->execute([
            'tahun' => $year,
            'tahun_for_orders' => $year,
        ]);
    }

    private function normalizeOrderDate(mixed $value): DateTimeImmutable
    {
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return new DateTimeImmutable($value->format('Y-m-d H:i:s'));
        }

        if (is_string($value) && trim($value) !== '') {
            return new DateTimeImmutable($value);
        }

        return new DateTimeImmutable();
    }
}
