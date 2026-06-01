<?php

class TransactionModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->prepare('
            SELECT
                o.*,
                c.name,
                c.phone_number,
                c.project_name,
                COALESCE(SUM(oid.quantity), 0) AS total_qty,
                COALESCE(SUM(oid.quantity * (oi.unit_price + oi.sablon_price)), 0) AS subtotal
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            LEFT JOIN order_items oi ON oi.order_id = o.order_id
            LEFT JOIN order_item_details oid ON oid.order_item_id = oi.order_item_id
            GROUP BY o.order_id, c.customer_id, c.name, c.phone_number, c.project_name
            ORDER BY o.order_id DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createCustomer(array $data): int
    {
        $this->db
            ->prepare('INSERT INTO customers (name, phone_number, project_name) VALUES (?, ?, ?)')
            ->execute([
                $data['name'],
                $data['phone_number'],
                $data['project_name'] ?? null,
            ]);
        return (int) $this->db->lastInsertId();
    }

    public function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . date('Ymd') . '-' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $stmt = $this->db->prepare('SELECT 1 FROM orders WHERE order_code = ? LIMIT 1');
            $stmt->execute([$code]);
        } while ($stmt->fetchColumn());

        return $code;
    }

    public function createOrder(array $data): array
    {
        $items = $data['items'] ?? [];
        if (empty($items)) {
            throw new \InvalidArgumentException('Keranjang kosong.');
        }

        $grandTotal = 0;
        foreach ($items as $item) {
            $grandTotal += (float) ($item['price'] ?? 0);
        }

        $this->db->beginTransaction();
        try {
            $customerId = $this->findOrCreateCustomer([
                'name' => $data['customer_name'] ?? '',
                'phone_number' => $data['customer_phone'] ?? '',
                'project_name' => $data['project_name'] ?? null,
            ]);

            $orderCode = null;
            for ($attempt = 0; $attempt < 5; $attempt++) {
                $orderCode = $this->generateOrderCode();
                $stmt = $this->db->prepare('
                    INSERT INTO orders (order_code, customer_id, order_date, order_status, grand_total, user_id)
                    VALUES (?, ?, ?, ?, ?, ?)
                ');

                try {
                    $stmt->execute([
                        $orderCode,
                        $customerId,
                        $this->normalizeOrderDate($data['order_date'] ?? null) . ' ' . date('H:i:s'),
                        self::ORDER_STATUS_PENDING_PAYMENT,
                        $grandTotal,
                        $data['user_id'] ?? null,
                    ]);
                    break;
                } catch (PDOException $e) {
                    if (($e->errorInfo[1] ?? null) !== 1062 || $attempt === 4) {
                        throw $e;
                    }
                }
            }
            $orderId = (int) $this->db->lastInsertId();

            $this->createOrderItems($orderId, $items);

            $this->db->prepare('
                INSERT INTO order_status_history (order_id, from_status, to_status, changed_by_user_id, changed_at, notes)
                VALUES (?, NULL, ?, ?, NOW(), ?)
            ')->execute([$orderId, self::ORDER_STATUS_PENDING_PAYMENT, $data['user_id'] ?? null, 'Order dibuat']);

            $this->db->commit();

            return [
                'order_id' => $orderId,
                'order_code' => $orderCode,
                'grand_total' => $grandTotal,
                'customer_name' => $data['customer_name'] ?? '',
                'customer_phone' => $data['customer_phone'] ?? '',
                'project_name' => $data['project_name'] ?? '',
                'order_date' => $this->normalizeOrderDate($data['order_date'] ?? null),
            ];
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function normalizeOrderDate(?string $value): string
    {
        $fallback = date('Y-m-d');
        $value = trim((string) $value);
        if ($value === '') {
            return $fallback;
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);
        $errors = DateTime::getLastErrors();
        if (! $date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) || $date->format('Y-m-d') !== $value) {
            return $fallback;
        }

        $min = new DateTime('2020-01-01');
        $max = new DateTime('+1 day');
        if ($date < $min || $date > $max) {
            return $fallback;
        }

        return $value;
    }

    private function findCustomerIdByNameAndPhone(string $name, string $phoneNumber): ?int
    {
        $stmt = $this->db->prepare('SELECT customer_id FROM customers WHERE name = ? AND phone_number = ? LIMIT 1');
        $stmt->execute([$name, $phoneNumber]);
        $existing = $stmt->fetch();

        return $existing ? (int) $existing['customer_id'] : null;
    }

    private function findOrCreateCustomer(array $data): int
    {
        $name = $data['name'] ?? '';
        $phoneNumber = $data['phone_number'] ?? '';

        $existingId = $this->findCustomerIdByNameAndPhone($name, $phoneNumber);
        if ($existingId !== null) {
            return $existingId;
        }

        try {
            return $this->createCustomer($data);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) !== 1062) {
                throw $e;
            }

            $existingId = $this->findCustomerIdByNameAndPhone($name, $phoneNumber);
            if ($existingId !== null) {
                return $existingId;
            }

            throw $e;
        }
    }

    public function createOrderItems(int $orderId, array $items): void
    {
        $stock = new StockModel($this->db);
        $sizeMap = $this->activeSizeMap();
        $colorMap = $this->activeColorMap();

        $stmt = $this->db->prepare('
            INSERT INTO order_items (order_id, variant_id, product_name_snapshot, variant_name_snapshot, sablon_type_id, sablon_price, unit_price, item_notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        foreach ($items as $item) {
            $quantity = (int) ($item['quantity'] ?? 0);
            if ($quantity < 1) {
                throw new \InvalidArgumentException('Jumlah item harus lebih dari 0.');
            }

            $variantId = isset($item['variant_id']) ? (int) $item['variant_id'] : null;
            $variantId = $variantId > 0 ? $variantId : null;

            $unitPrice = $item['unit_price'] ?? ($quantity > 0 ? round(((float) ($item['price'] ?? 0)) / $quantity) : 0);
            $sablonPrice = (int) ($item['sablon_price'] ?? 0);

            $stmt->execute([
                $orderId,
                $variantId,
                $item['category'] ?? '-',
                $item['material'] ?? '-',
                $item['sablon_type_id'] ?? null,
                $sablonPrice,
                max(0, $unitPrice - $sablonPrice),
                $item['catatan'] ?? '',
            ]);

            $orderItemId = (int) $this->db->lastInsertId();
            $this->saveDesignFiles($orderItemId, $item['desain'] ?? []);
            $this->createOrderItemDetails($orderItemId, $item, $sizeMap, $colorMap, $stock);
        }
    }

    public function createOrderItemDetails(int $orderItemId, array $item, array $sizeMap = [], array $colorMap = [], ?StockModel $stock = null): void
    {
        if (empty($item['rincian'])) {
            return;
        }

        $stock ??= new StockModel($this->db);
        $sizeMap = $sizeMap ?: $this->activeSizeMap();
        $colorMap = $colorMap ?: $this->activeColorMap();
        $variantId = isset($item['variant_id']) ? (int) $item['variant_id'] : null;
        $variantId = $variantId > 0 ? $variantId : null;
        $warnaPerSize = $item['warna_per_size'] ?? ['short' => [], 'long' => []];
        $qtyShort = $item['quantity_short'] ?? [];
        $qtyLong = $item['quantity_long'] ?? [];

        $detailStmt = $this->db->prepare('
            INSERT INTO order_item_details
                (order_item_id, option_id, size_id, color_id, quantity, sleeve_type, fulfillment_type, custom_color)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        foreach ($item['rincian'] as $size => $totalQty) {
            if ((int) $totalQty <= 0 || empty($sizeMap[$size])) {
                continue;
            }

            $sizeId = $sizeMap[$size];
            $shortQty = (int) ($qtyShort[$size] ?? 0);
            $longQty = (int) ($qtyLong[$size] ?? 0);
            if ($shortQty === 0 && $longQty === 0) {
                $shortQty = (int) $totalQty;
            }

            $this->insertAllocatedDetails($detailStmt, $stock, $orderItemId, $variantId, $sizeId, $shortQty, $warnaPerSize['short'][$size] ?? '', $item['custom_colors'][$size . '_short'] ?? '', $colorMap, self::SLEEVE_SHORT);
            $this->insertAllocatedDetails($detailStmt, $stock, $orderItemId, $variantId, $sizeId, $longQty, $warnaPerSize['long'][$size] ?? '', $item['custom_colors'][$size . '_long'] ?? '', $colorMap, self::SLEEVE_LONG);
        }
    }

    private function insertAllocatedDetails(PDOStatement $detailStmt, StockModel $stock, int $orderItemId, ?int $variantId, int $sizeId, int $quantity, string $colorName, string $customColor, array $colorMap, string $sleeveType): void
    {
        if ($quantity < 1) {
            return;
        }

        if ($customColor !== '') {
            $detailStmt->execute([
                $orderItemId,
                null,
                $sizeId,
                null,
                $quantity,
                $sleeveType,
                self::FULFILLMENT_CUSTOM,
                $customColor,
            ]);
            return;
        }

        $colorKey = strtolower(trim($colorName));
        if ($colorKey === '' || ! isset($colorMap[$colorKey])) {
            throw new \RuntimeException('Warna wajib dipilih untuk setiap ukuran yang memiliki kuantitas.');
        }

        $colorId = $colorMap[$colorKey];
        $allocations = $stock->allocateFulfillment($variantId, $sizeId, $colorId, $quantity, $sleeveType);
        foreach ($allocations as $alloc) {
            $detailStmt->execute([
                $orderItemId,
                $alloc['option_id'],
                $sizeId,
                $colorId,
                $alloc['qty'],
                $sleeveType,
                $alloc['fulfillment_type'],
                $customColor !== '' ? $customColor : null,
            ]);
        }
    }

    private function activeSizeMap(): array
    {
        $stmt = $this->db->query('SELECT size_id, size_name FROM sizes WHERE is_active = 1');
        $map = [];
        foreach ($stmt->fetchAll() as $size) {
            $map[$size['size_name']] = (int) $size['size_id'];
        }
        return $map;
    }

    private function activeColorMap(): array
    {
        $stmt = $this->db->query('SELECT color_id, color_name FROM colors WHERE is_active = 1');
        $map = [];
        foreach ($stmt->fetchAll() as $color) {
            $map[strtolower($color['color_name'])] = (int) $color['color_id'];
        }
        return $map;
    }

    public function getCustomerById(int $customerId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM customers WHERE customer_id = ? LIMIT 1');
        $stmt->execute([$customerId]);
        return $stmt->fetch() ?: null;
    }

    public function getCategoriesWithPrices(): array
    {
        $stmt = $this->db->prepare('
            SELECT
                pc.category_id,
                pc.category_name,
                MIN(pv.price) AS min_price
            FROM product_categories pc
            LEFT JOIN products p ON p.category_id = pc.category_id AND p.is_active = 1
            LEFT JOIN product_variants pv ON pv.product_id = p.product_id AND pv.is_active = 1
            WHERE pc.is_active = 1
            GROUP BY pc.category_id, pc.category_name
            ORDER BY pc.category_id ASC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrderById(int $orderId): ?array
    {
        $stmt = $this->db->prepare('SELECT order_id, user_id, order_status, grand_total FROM orders WHERE order_id = ?');
        $stmt->execute([$orderId]);
        return $stmt->fetch() ?: null;
    }

    public function getOrderByPaymentId(int $paymentId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT o.order_id, o.user_id, o.order_status
            FROM payments p
            JOIN orders o ON o.order_id = p.order_id
            WHERE p.payment_id = ?
        ');
        $stmt->execute([$paymentId]);
        return $stmt->fetch() ?: null;
    }

    public function findOrder(string $orderCode): ?array
    {
        $stmt = $this->db->prepare('
            SELECT
                o.*,
                c.name,
                c.phone_number,
                c.project_name,
                COALESCE(SUM(oid.quantity), 0) AS total_qty,
                COALESCE(SUM(oid.quantity * (oi.unit_price + oi.sablon_price)), 0) AS subtotal
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            LEFT JOIN order_items oi ON oi.order_id = o.order_id
            LEFT JOIN order_item_details oid ON oid.order_item_id = oi.order_item_id
            WHERE o.order_code = ? OR o.order_id = ?
            GROUP BY o.order_id, c.customer_id, c.name, c.phone_number, c.project_name
            LIMIT 1
        ');
        $stmt->execute([$orderCode, (int) $orderCode]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                oi.*,
                pv.variant_name,
                pv.material,
                pv.sleeve_price,
                p.product_name,
                pc.category_name,
                st.sablon_name,
                COALESCE(SUM(oid.quantity), 0) AS quantity,
                COALESCE(SUM(oid.quantity * (oi.unit_price + oi.sablon_price)), 0) AS subtotal
            FROM order_items oi
            LEFT JOIN product_variants pv ON pv.variant_id = oi.variant_id
            LEFT JOIN products p ON p.product_id = pv.product_id
            LEFT JOIN product_categories pc ON pc.category_id = p.category_id
            LEFT JOIN sablon_types st ON st.sablon_type_id = oi.sablon_type_id
            LEFT JOIN order_item_details oid ON oid.order_item_id = oi.order_item_id
            WHERE oi.order_id = ?
            GROUP BY oi.order_item_id
        ');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function findOrderDetails(int $orderId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                oid.*,
                s.size_name,
                COALESCE(c.color_name, oid.custom_color) AS color_name,
                COALESCE(vo.price_surcharge, 0) AS price_surcharge
            FROM order_item_details oid
            JOIN order_items oi ON oi.order_item_id = oid.order_item_id
            JOIN sizes s ON s.size_id = oid.size_id
            LEFT JOIN colors c ON c.color_id = oid.color_id
            LEFT JOIN variant_options vo ON vo.option_id = oid.option_id
            WHERE oi.order_id = ?
            ORDER BY oid.order_item_detail_id
        ');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public static function mergeFulfillmentDetails(array $details): array
    {
        $merged = [];
        foreach ($details as $d) {
            $key = ($d['size_id'] ?? 0) . "\x00"
            . ($d['color_id'] ?? 'null') . "\x00"
            . str_replace("\x00", '', $d['custom_color'] ?? '') . "\x00"
                . ($d['sleeve_type'] ?? 'short');

            if (! isset($merged[$key])) {
                $merged[$key] = $d;
                $merged[$key]['_ready_qty'] = 0;
                $merged[$key]['_total_qty'] = 0;
            }

            $qty = (int) ($d['quantity'] ?? 0);
            $merged[$key]['_total_qty'] += $qty;

            if (($d['fulfillment_type'] ?? '') === Model::FULFILLMENT_READY_STOCK) {
                $merged[$key]['_ready_qty'] += $qty;
            }
        }

        foreach ($merged as &$row) {
            $row['_custom_qty'] = $row['_total_qty'] - $row['_ready_qty'];
            $row['quantity'] = $row['_total_qty'];
        }

        return array_values($merged);
    }

    public function findUserName(int $userId): ?string
    {
        $stmt = $this->db->prepare('SELECT username FROM users WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $name = $stmt->fetchColumn();
        return $name !== false ? (string) $name : null;
    }

    public function findOrderPayments(int $orderId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                p.*,
                u.username AS received_by_name
            FROM payments p
            LEFT JOIN users u ON u.user_id = p.received_by_user_id
            WHERE p.order_id = ?
            ORDER BY p.payment_date DESC, p.payment_id DESC
        ');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function findOrderHistory(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                osh.*,
                u.username AS changed_by_name
            FROM order_status_history osh
            LEFT JOIN users u ON u.user_id = osh.changed_by_user_id
            WHERE osh.order_id = ?
              AND osh.notes IS NOT NULL
              AND osh.notes != ''
            ORDER BY osh.changed_at DESC, osh.history_id DESC
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function saveDesignFiles(int $orderItemId, array $files): void
    {
        if (empty($files)) {
            return;
        }

        $stmt = $this->db->prepare('
            INSERT INTO order_item_designs (order_item_id, filename, notes)
            VALUES (?, ?, ?)
        ');

        foreach ($files as $f) {
            if (empty($f['filename'])) {
                continue;
            }

            $stmt->execute([
                $orderItemId,
                $f['filename'],
                $f['notes'] ?? $f['note'] ?? null,
            ]);
        }
    }

    public function findOrderDesigns(int $orderId): array
    {
        $stmt = $this->db->prepare('
            SELECT d.*, oi.order_id
            FROM order_item_designs d
            JOIN order_items oi ON oi.order_item_id = d.order_item_id
            WHERE oi.order_id = ?
            ORDER BY d.design_id
        ');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    private function designUploadPath(string $filename): ?string
    {
        if ($filename === '' || basename($filename) !== $filename) {
            return null;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/desain/';
        $base = realpath($uploadDir);
        if ($base === false) {
            return null;
        }

        $path = $base . DIRECTORY_SEPARATOR . $filename;
        $real = realpath($path);
        if ($real !== false && str_starts_with($real, $base . DIRECTORY_SEPARATOR)) {
            return $real;
        }

        return $path;
    }

    public function canAccessDesign(int $designId, int $userId, string $role): bool
    {
        if ($role === self::ROLE_OWNER) {
            return true;
        }

        $stmt = $this->db->prepare('
            SELECT o.user_id
            FROM order_item_designs d
            JOIN order_items oi ON oi.order_item_id = d.order_item_id
            JOIN orders o ON o.order_id = oi.order_id
            WHERE d.design_id = ?
            LIMIT 1
        ');
        $stmt->execute([$designId]);
        $ownerId = $stmt->fetchColumn();

        return $ownerId !== false && (int) $ownerId === $userId;
    }

    public function updateDesign(int $designId, ?string $filename, ?string $notes): bool
    {
        $stmt = $this->db->prepare('SELECT filename FROM order_item_designs WHERE design_id = ?');
        $stmt->execute([$designId]);
        $row = $stmt->fetch();
        if (! $row) {
            return false;
        }

        if ($filename !== null && $filename !== '') {
            $oldPath = $this->designUploadPath((string) $row['filename']);
            if ($oldPath !== null && file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $updates = [];
        $params = [];
        if ($filename !== null && $filename !== '') {
            $updates[] = 'filename = ?';
            $params[] = $filename;
        }
        if ($notes !== null) {
            $updates[] = 'notes = ?';
            $params[] = $notes !== '' ? $notes : null;
        }

        if (empty($updates)) {
            return true;
        }

        $params[] = $designId;
        $this->db->prepare('UPDATE order_item_designs SET ' . implode(', ', $updates) . ' WHERE design_id = ?')
            ->execute($params);

        return true;
    }

    public function deleteDesign(int $designId): bool
    {
        $stmt = $this->db->prepare('SELECT filename FROM order_item_designs WHERE design_id = ?');
        $stmt->execute([$designId]);
        $row = $stmt->fetch();
        if (! $row) {
            return false;
        }

        $filePath = $this->designUploadPath((string) $row['filename']);
        if ($filePath !== null && file_exists($filePath)) {
            @unlink($filePath);
        }

        $this->db->prepare('DELETE FROM order_item_designs WHERE design_id = ?')->execute([$designId]);
        return true;
    }

    public function getPriceSurcharge(int $optionId): float
    {
        $stmt = $this->db->prepare('SELECT price_surcharge FROM variant_options WHERE option_id = ? AND is_active = 1');
        $stmt->execute([$optionId]);
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    public function getMinimumOrder(int $productId): int
    {
        $stmt = $this->db->prepare('SELECT minimum_order FROM products WHERE product_id = ? AND is_active = 1');
        $stmt->execute([$productId]);
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function getVariantPrice(int $variantId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT pv.price, pv.sleeve_price
            FROM product_variants pv
            JOIN products p ON p.product_id = pv.product_id
            JOIN product_categories pc ON pc.category_id = p.category_id
            WHERE pv.variant_id = ?
              AND pv.is_active = 1
              AND p.is_active = 1
              AND pc.is_active = 1
            LIMIT 1
        ');
        $stmt->execute([$variantId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function inactiveVariantOptionSelections(?int $variantId, array $rincian, array $warnaPerSize, array $qtyShort, array $qtyLong): array
    {
        if ($variantId === null) {
            return [];
        }

        $sizeMap = $this->activeSizeMap();
        $colorMap = $this->activeColorMap();
        $stmt = $this->db->prepare('
            SELECT vo.is_active
            FROM variant_options vo
            WHERE vo.variant_id = ?
              AND vo.size_id = ?
              AND vo.color_id = ?
              AND vo.sleeve_type = ?
            LIMIT 1
        ');

        $errors = [];
        foreach ($rincian as $sizeName => $totalQty) {
            if ((int) $totalQty < 1 || empty($sizeMap[$sizeName])) {
                continue;
            }

            foreach ([self::SLEEVE_SHORT => $qtyShort, self::SLEEVE_LONG => $qtyLong] as $sleeveType => $qtyMap) {
                if ((int) ($qtyMap[$sizeName] ?? 0) < 1) {
                    continue;
                }

                $colorName = trim((string) ($warnaPerSize[$sleeveType][$sizeName] ?? ''));
                $colorKey = strtolower($colorName);
                if ($colorKey === '' || !isset($colorMap[$colorKey])) {
                    continue;
                }

                $stmt->execute([$variantId, $sizeMap[$sizeName], $colorMap[$colorKey], $sleeveType]);
                $row = $stmt->fetch();
                if ($row && (int) ($row['is_active'] ?? 0) === 0) {
                    $label = $sleeveType === self::SLEEVE_LONG ? 'lengan panjang' : 'lengan pendek';
                    $errors[] = "Opsi {$sizeName} / {$colorName} / {$label} sudah nonaktif.";
                }
            }
        }

        return $errors;
    }

    public function getVariantMinimumOrder(int $variantId): int
    {
        $stmt = $this->db->prepare('
            SELECT p.minimum_order
            FROM products p
            JOIN product_variants pv ON pv.product_id = p.product_id
            WHERE pv.variant_id = ? AND p.is_active = 1 AND pv.is_active = 1
        ');
        $stmt->execute([$variantId]);
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    public function getActiveCategories(): array
    {
        return $this->db
            ->query('SELECT category_id, category_name FROM product_categories WHERE is_active = 1 ORDER BY category_name')
            ->fetchAll();
    }

    public function getVariantsByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                p.product_id,
                p.product_name,
                p.minimum_order,
                pv.variant_id,
                pv.variant_name,
                pv.material,
                pv.price,
                pv.sleeve_price,
                COALESCE(vos.total_qty, 0) AS stok
            FROM products p
            JOIN product_variants pv ON pv.product_id = p.product_id
            LEFT JOIN (
                SELECT variant_id, SUM(quantity) AS total_qty
                FROM variant_options
                WHERE is_active = 1
                GROUP BY variant_id
            ) vos ON vos.variant_id = pv.variant_id
            WHERE p.category_id = ? AND p.is_active = 1 AND pv.is_active = 1
            ORDER BY p.product_name, pv.variant_name
        ');
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getVariantOptions(int $variantId): array
    {
        $stmt = $this->db->prepare('
            SELECT vo.*, s.size_name, c.color_name
            FROM variant_options vo
            JOIN sizes s ON vo.size_id = s.size_id
            JOIN colors c ON vo.color_id = c.color_id
            WHERE vo.variant_id = ? AND vo.is_active = 1
            ORDER BY s.size_id, c.color_name, vo.sleeve_type
        ');
        $stmt->execute([$variantId]);
        return $stmt->fetchAll();
    }

    public function getSablonTypesByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('
            SELECT st.sablon_type_id, st.sablon_name, st.notes
            FROM sablon_types st
            JOIN category_sablon_types cst ON st.sablon_type_id = cst.sablon_type_id
            WHERE cst.category_id = ? AND st.is_active = 1 AND cst.is_active = 1
            ORDER BY st.sablon_name
        ');
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function allProducts(): array
    {
        $stmt = $this->db->prepare('
            SELECT
                p.product_id,
                p.product_name,
                p.minimum_order,
                pv.variant_id,
                pv.variant_name,
                pv.material,
                pv.price,
                COALESCE(vos.total_qty, 0) AS stok
            FROM products p
            JOIN product_variants pv ON pv.product_id = p.product_id
            LEFT JOIN (
                SELECT variant_id, SUM(quantity) AS total_qty
                FROM variant_options
                WHERE is_active = 1
                GROUP BY variant_id
            ) vos ON vos.variant_id = pv.variant_id
            WHERE p.is_active = 1 AND pv.is_active = 1
            ORDER BY p.product_name, pv.variant_name
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function recordPayment(int $orderId, array $data, int $userId): int
    {
        $this->db->beginTransaction();
        try {
            $lockStmt = $this->db->prepare('SELECT grand_total FROM orders WHERE order_id = ? FOR UPDATE');
            $lockStmt->execute([$orderId]);
            $order = $lockStmt->fetch();
            if (!$order) {
                $this->db->rollBack();
                throw new \RuntimeException('Order tidak ditemukan.');
            }

            $grandTotal = (float) $order['grand_total'];
            $sumStmt = $this->db->prepare("
                SELECT COALESCE(SUM(amount), 0) AS total_paid
                FROM payments
                WHERE order_id = ? AND payment_status = 'paid'
            ");
            $sumStmt->execute([$orderId]);
            $totalPaid = (float) ($sumStmt->fetchColumn() ?: 0);
            $newAmount = (float) $data['amount'];

            if ($totalPaid + $newAmount > $grandTotal) {
                $this->db->rollBack();
                throw new \RuntimeException('Jumlah pembayaran melebihi sisa tagihan.');
            }

            $paymentDate = $data['payment_date'] ?? date('Y-m-d H:i:s');
            $stmt = $this->db->prepare("
                INSERT INTO payments (order_id, payment_date, payment_method, amount, payment_status, received_by_user_id, paid_at)
                VALUES (?, ?, ?, ?, 'paid', ?, NOW())
            ");
            $stmt->execute([
                $orderId,
                $paymentDate,
                $data['payment_method'],
                $newAmount,
                $userId,
            ]);

            $this->autoUpdateStatusFromPayment($orderId, $userId);

            $this->db->commit();
            return (int) $this->db->lastInsertId();
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function autoUpdateStatusFromPayment(int $orderId, int $userId): void
    {
        $stmt = $this->db->prepare("
            SELECT o.order_status, o.grand_total,
                   COALESCE(SUM(CASE WHEN p.payment_status = 'paid' THEN p.amount ELSE 0 END), 0) AS total_paid
            FROM orders o
            LEFT JOIN payments p ON p.order_id = o.order_id
            WHERE o.order_id = ?
            GROUP BY o.order_id
        ");
        $stmt->execute([$orderId]);
        $row = $stmt->fetch();
        if (! $row) {
            return;
        }

        $status = $row['order_status'];
        $grandTotal = (float) $row['grand_total'];
        $totalPaid = (float) $row['total_paid'];
        $pct = $grandTotal > 0 ? ($totalPaid / $grandTotal) * 100 : 0;

        $newStatus = null;

        if ($status === self::ORDER_STATUS_PENDING_PAYMENT && $pct >= 50) {
            $newStatus = self::ORDER_STATUS_CONFIRMED;
        } elseif ($status === self::ORDER_STATUS_READY && $totalPaid >= $grandTotal) {
            $newStatus = self::ORDER_STATUS_COMPLETED;
        }

        if ($newStatus !== null) {
            $this->db->prepare('UPDATE orders SET order_status = ? WHERE order_id = ?')
                ->execute([$newStatus, $orderId]);

            $this->db->prepare("
                INSERT INTO order_status_history (order_id, from_status, to_status, changed_by_user_id, changed_at, notes)
                VALUES (?, ?, ?, ?, NOW(), 'Auto: pembayaran tercatat')
            ")->execute([$orderId, $status, $newStatus, $userId]);
        }
    }

    public function voidPayment(int $paymentId, int $userId, string $userRole, string $reason): array
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("SELECT * FROM payments WHERE payment_id = ? AND payment_status = 'paid' FOR UPDATE");
            $stmt->execute([$paymentId]);
            $payment = $stmt->fetch();
            if (! $payment) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Payment tidak ditemukan atau sudah dibatalkan'];
            }

            $orderId = (int) $payment['order_id'];

            $this->db->prepare("UPDATE payments SET payment_status = 'void', voided_by_user_id = ?, voided_at = NOW() WHERE payment_id = ?")
                ->execute([$userId, $paymentId]);

            $auditNotes = 'Void: ' . $reason;

            $this->db->prepare('
                INSERT INTO order_status_history (order_id, from_status, to_status, changed_by_user_id, changed_at, notes)
                VALUES (?, (SELECT order_status FROM orders WHERE order_id = ?), (SELECT order_status FROM orders WHERE order_id = ?), ?, NOW(), ?)
            ')->execute([$orderId, $orderId, $orderId, $userId, $auditNotes]);

            $this->db->commit();
            return ['success' => true, 'message' => 'Payment berhasil di-void'];
        } catch (\Throwable $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Gagal void payment'];
        }
    }

    public function refundPayment(int $paymentId, int $userId, float $amount, string $reason): array
    {
        $this->db->beginTransaction();
        try {
            // Lock payment + order row di awal transaksi untuk mencegah race condition (I1 fix).
            // Refund yang concurrent ke order yang sama harus serialize di sini.
            $stmt = $this->db->prepare("SELECT * FROM payments WHERE payment_id = ? AND payment_status = 'paid' FOR UPDATE");
            $stmt->execute([$paymentId]);
            $original = $stmt->fetch();
            if (! $original) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Payment tidak ditemukan atau sudah dibatalkan'];
            }

            $orderId = (int) $original['order_id'];
            $orderLockStmt = $this->db->prepare('SELECT order_status FROM orders WHERE order_id = ? FOR UPDATE');
            $orderLockStmt->execute([$orderId]);
            $currentStatus = (string) ($orderLockStmt->fetchColumn() ?: '');

            if ($amount <= 0) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Jumlah refund tidak valid'];
            }

            $originalAmount = (float) $original['amount'];
            $existingRefunds = $this->db->prepare("
                SELECT COALESCE(SUM(amount), 0) FROM payments
                WHERE order_id = ? AND payment_status = 'refunded' AND reference_payment_id = ?
            ");
            $existingRefunds->execute([(int) $original['order_id'], $paymentId]);
            $totalRefunded = (float) $existingRefunds->fetchColumn();

            if ($totalRefunded + $amount > $originalAmount) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Total refund melebihi jumlah payment asli. Sudah di-refund: Rp ' . number_format($totalRefunded, 0, ',', '.')];
            }

            $remaining = $originalAmount - $totalRefunded - $amount;
            $this->db->prepare("UPDATE payments SET payment_status = 'void', voided_by_user_id = ?, voided_at = NOW() WHERE payment_id = ?")
                ->execute([$userId, $paymentId]);

            if ($remaining > 0) {
                $this->db->prepare("
                    INSERT INTO payments (order_id, payment_date, payment_method, amount, payment_status, received_by_user_id, paid_at, reference_payment_id)
                    VALUES (?, ?, ?, ?, 'paid', ?, ?, ?)
                ")->execute([
                    $original['order_id'],
                    $original['payment_date'],
                    $original['payment_method'],
                    $remaining,
                    $original['received_by_user_id'] ?? $userId,
                    $original['paid_at'] ?? date('Y-m-d H:i:s'),
                    $paymentId,
                ]);
            }

            $this->db->prepare("
                INSERT INTO payments (order_id, payment_date, payment_method, amount, payment_status, refunded_by_user_id, refunded_at, reference_payment_id)
                VALUES (?, NOW(), ?, ?, 'refunded', ?, NOW(), ?)
            ")->execute([
                $original['order_id'],
                $original['payment_method'],
                $amount,
                $userId,
                $paymentId,
            ]);

            $auditNotes = 'Refund: ' . $reason;

            $this->db->prepare('
                INSERT INTO order_status_history (order_id, from_status, to_status, changed_by_user_id, changed_at, notes)
                VALUES (?, (SELECT order_status FROM orders WHERE order_id = ?), (SELECT order_status FROM orders WHERE order_id = ?), ?, NOW(), ?)
            ')->execute([(int) $original['order_id'], (int) $original['order_id'], (int) $original['order_id'], $userId, $auditNotes]);

            // Opsi B: auto-cancel saat refund full di status pre-production.
            // Refund full → total_paid drop ke 0 → order tidak akan bisa diproduksi (CAP-9 guard).
            // Sekalian cancel + restore stock supaya tidak nyangkut menunggu Owner cancel manual.
            // Catatan: $orderId & $currentStatus sudah di-lock di awal transaksi (I1 fix).

            $isFullRefund = $remaining <= 0;
            $isPreProduction = in_array($currentStatus, [
                self::ORDER_STATUS_PENDING_PAYMENT,
                self::ORDER_STATUS_CONFIRMED,
            ], true);

            if ($isFullRefund && $isPreProduction) {
                $autoCancelReason = 'Auto-cancel refund: ' . $reason;
                $this->db->prepare('
                    UPDATE orders
                    SET order_status = ?, cancelled_at = NOW(), cancellation_reason = ?, cancelled_by_user_id = ?
                    WHERE order_id = ?
                ')->execute([
                    self::ORDER_STATUS_CANCELLED,
                    $autoCancelReason,
                    $userId,
                    $orderId,
                ]);

                $detailStmt = $this->db->prepare("
                    SELECT oid.option_id, oid.quantity
                    FROM order_item_details oid
                    JOIN order_items oi ON oi.order_item_id = oid.order_item_id
                    WHERE oi.order_id = ? AND oid.fulfillment_type = 'ready_stock' AND oid.option_id IS NOT NULL
                ");
                $detailStmt->execute([$orderId]);
                $updStmt = $this->db->prepare('UPDATE variant_options SET quantity = quantity + ? WHERE option_id = ?');
                foreach ($detailStmt->fetchAll() as $d) {
                    $updStmt->execute([(int) $d['quantity'], (int) $d['option_id']]);
                    if ($updStmt->rowCount() === 0) {
                        error_log("[refundPayment auto-cancel] option_id {$d['option_id']} not found during stock restore for order {$orderId}");
                    }
                }

                $this->db->prepare('
                    INSERT INTO order_status_history (order_id, from_status, to_status, changed_by_user_id, changed_at, notes)
                    VALUES (?, ?, ?, ?, NOW(), ?)
                ')->execute([
                    $orderId,
                    $currentStatus,
                    self::ORDER_STATUS_CANCELLED,
                    $userId,
                    $autoCancelReason,
                ]);
            }

            $this->db->commit();

            $message = ($isFullRefund && $isPreProduction)
                ? 'Refund berhasil diproses. Pesanan otomatis dibatalkan dan stok ready_stock dikembalikan.'
                : 'Refund berhasil diproses';

            return ['success' => true, 'message' => $message];
        } catch (\Throwable $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Gagal proses refund'];
        }
    }

    public function getPaymentSummary(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                o.grand_total,
                COALESCE(SUM(CASE WHEN p.payment_status = 'paid' THEN p.amount ELSE 0 END), 0) AS total_paid,
                COALESCE(SUM(CASE WHEN p.payment_status = 'void' THEN p.amount ELSE 0 END), 0) AS total_void,
                COALESCE(SUM(CASE WHEN p.payment_status = 'refunded' THEN p.amount ELSE 0 END), 0) AS total_refunded
            FROM orders o
            LEFT JOIN payments p ON p.order_id = o.order_id
            WHERE o.order_id = ?
            GROUP BY o.order_id
        ");
        $stmt->execute([$orderId]);
        $row = $stmt->fetch();

        $grandTotal = (float) ($row['grand_total'] ?? 0);
        $totalPaid = (float) ($row['total_paid'] ?? 0);

        return [
            'grand_total' => $grandTotal,
            'total_paid' => $totalPaid,
            'total_void' => (float) ($row['total_void'] ?? 0),
            'total_refunded' => (float) ($row['total_refunded'] ?? 0),
            'remaining_balance' => max(0, $grandTotal - $totalPaid),
        ];
    }

    public function sizeSurchargesByName(int $variantId): array
    {
        $stmt = $this->db->prepare('
            SELECT s.size_name, MAX(COALESCE(vo.price_surcharge, 0)) AS price_surcharge
            FROM variant_options vo
            JOIN sizes s ON s.size_id = vo.size_id
            WHERE vo.variant_id = ? AND vo.is_active = 1 AND s.is_active = 1
            GROUP BY s.size_name
        ');
        $stmt->execute([$variantId]);

        $surcharges = [];
        foreach ($stmt->fetchAll() as $row) {
            $surcharges[$row['size_name']] = (float) $row['price_surcharge'];
        }

        return $surcharges;
    }

    public function updateOrderStatus(int $orderId, string $newStatus, int $userId, string $userRole, string $reason = '', ?string $notes = null): array
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('SELECT order_status, grand_total, user_id FROM orders WHERE order_id = ? FOR UPDATE');
            $stmt->execute([$orderId]);
            $order = $stmt->fetch();
            if (!$order) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Order tidak ditemukan'];
            }

            $oldStatus = $order['order_status'];
            $grandTotal = (float) $order['grand_total'];
            $orderUserId = (int) ($order['user_id'] ?? 0);

            if ($userRole !== self::ROLE_OWNER && $orderUserId !== $userId) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengubah pesanan ini', 'http_code' => 403];
            }

            if (!in_array($newStatus, self::VALID_ORDER_TRANSITIONS[$oldStatus] ?? [], true)) {
                $this->db->rollBack();
                return ['success' => false, 'message' => "Transisi dari $oldStatus ke $newStatus tidak valid"];
            }

            if ($newStatus !== self::ORDER_STATUS_CANCELLED) {
                $allowedRoles = self::ORDER_TRANSITION_ROLES[$oldStatus][$newStatus] ?? [];
                $allowedRoles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
                if (!empty($allowedRoles) && !in_array($userRole, $allowedRoles, true)) {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => 'Hanya ' . implode(' atau ', $allowedRoles) . ' yang bisa melakukan transisi ini', 'http_code' => 403];
                }
            } else {
                if ($userRole !== self::ROLE_OWNER) {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => 'Hanya owner yang bisa membatalkan order', 'http_code' => 403];
                }
                if (trim($reason) === '') {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => 'Alasan pembatalan wajib diisi'];
                }
            }

            $paymentSummary = $this->getPaymentSummary($orderId);
            $totalPaid = $paymentSummary['total_paid'];

            if ($oldStatus === self::ORDER_STATUS_PENDING_PAYMENT && $newStatus === self::ORDER_STATUS_CONFIRMED && $totalPaid < $grandTotal * self::DP_THRESHOLD) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'DP belum mencukupi (minimal 50%). Sudah bayar: ' . format_currency($totalPaid) . ' dari ' . format_currency($grandTotal),
                ];
            }

            if ($oldStatus === self::ORDER_STATUS_CONFIRMED && $newStatus === self::ORDER_STATUS_IN_PROGRESS && $totalPaid < $grandTotal * self::DP_THRESHOLD) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'DP belum mencukupi (minimal 50%). Pesanan tidak bisa diproses. Sudah bayar: ' . format_currency($totalPaid) . ' dari ' . format_currency($grandTotal),
                ];
            }

            if ($oldStatus === self::ORDER_STATUS_IN_PROGRESS && $newStatus === self::ORDER_STATUS_READY && $totalPaid < $grandTotal * self::DP_THRESHOLD) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'DP belum mencukupi (minimal 50%). Pesanan tidak bisa dilanjutkan ke siap diambil. Sudah bayar: ' . format_currency($totalPaid) . ' dari ' . format_currency($grandTotal),
                ];
            }

            if ($oldStatus === self::ORDER_STATUS_READY && $newStatus === self::ORDER_STATUS_COMPLETED && $totalPaid < $grandTotal) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Pembayaran belum lunas. Kurang: ' . format_currency($grandTotal - $totalPaid),
                ];
            }

            $stmt = $this->db->prepare('UPDATE orders SET order_status = ? WHERE order_id = ?');
            $stmt->execute([$newStatus, $orderId]);

            if ($newStatus === self::ORDER_STATUS_CANCELLED) {
                $this->db->prepare('UPDATE orders SET cancelled_at = NOW(), cancellation_reason = ?, cancelled_by_user_id = ? WHERE order_id = ?')
                    ->execute([trim($reason), $userId, $orderId]);

                $this->restoreReadyStockForOrder($orderId, '[cancelOrder]');
            }

            $historyNotes = $notes ?? "Status diubah dari $oldStatus ke $newStatus";
            $this->db->prepare('
                INSERT INTO order_status_history (order_id, from_status, to_status, changed_by_user_id, changed_at, notes)
                VALUES (?, ?, ?, ?, NOW(), ?)
            ')->execute([$orderId, $oldStatus, $newStatus, $userId, $historyNotes]);

            $this->db->commit();
            return ['success' => true];
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function restoreReadyStockForOrder(int $orderId, string $logPrefix): void
    {
        $detailStmt = $this->db->prepare("
            SELECT oid.option_id, oid.quantity
            FROM order_item_details oid
            JOIN order_items oi ON oi.order_item_id = oid.order_item_id
            WHERE oi.order_id = ? AND oid.fulfillment_type = 'ready_stock' AND oid.option_id IS NOT NULL
        ");
        $detailStmt->execute([$orderId]);
        $updStmt = $this->db->prepare('UPDATE variant_options SET quantity = quantity + ? WHERE option_id = ?');
        foreach ($detailStmt->fetchAll() as $d) {
            $updStmt->execute([(int) $d['quantity'], (int) $d['option_id']]);
            if ($updStmt->rowCount() === 0) {
                error_log("{$logPrefix} option_id {$d['option_id']} not found during stock restore for order {$orderId}");
            }
        }
    }
}
