<?php

class FinanceModel extends Model
{
    public function calculatePaymentState(float $grandTotal, float $totalPaid): string
    {
        if ($totalPaid <= 0) {
            return 'unpaid';
        }

        if ($totalPaid < $grandTotal) {
            return 'partial';
        }

        if (abs($totalPaid - $grandTotal) < 0.01) {
            return self::PAYMENT_STATUS_PAID;
        }

        return 'overpaid';
    }

    public function validatePaymentInput(array $payload): array
    {
        $errors = [];

        if (!isset($payload['jumlah_bayar']) || !is_numeric($payload['jumlah_bayar']) || (float) $payload['jumlah_bayar'] <= 0) {
            $errors['jumlah_bayar'] = 'Jumlah pembayaran harus lebih besar dari nol.';
        }

        if (isset($payload['metode_bayar']) && !in_array((string) $payload['metode_bayar'], self::ALLOWED_PAYMENT_METHODS, true)) {
            $errors['metode_bayar'] = 'Metode pembayaran tidak valid. Pilih salah satu: ' . implode(', ', self::ALLOWED_PAYMENT_METHODS) . '.';
        }

        if (isset($payload['status_bayar']) && !in_array((string) $payload['status_bayar'], self::ALLOWED_PAYMENT_STATUSES, true)) {
            $errors['status_bayar'] = 'Status pembayaran tidak valid. Pilih salah satu: ' . implode(', ', self::ALLOWED_PAYMENT_STATUSES) . '.';
        }

        return [
            'valid' => $errors === [],
            'errors' => $errors,
            'oldInput' => $payload,
        ];
    }

    public function getPaymentStateForOrder(int $orderId): array
    {
        $paidStatus = self::PAYMENT_STATUS_PAID;
        $statement = $this->db->prepare(
            "SELECT
                o.order_id,
                o.order_code,
                o.grand_total,
                COALESCE(SUM(CASE WHEN p.status_bayar = '{$paidStatus}' THEN p.jumlah_bayar ELSE 0 END), 0) AS total_paid
             FROM orders o
             LEFT JOIN payments p ON p.order_id = o.order_id
             WHERE o.order_id = :order_id
             GROUP BY o.order_id, o.order_code, o.grand_total
             LIMIT 1"
        );
        $statement->execute(['order_id' => $orderId]);
        $row = $statement->fetch();

        if (!$row) {
            throw new InvalidArgumentException('Order tidak ditemukan.');
        }

        $grandTotal = (float) $row['grand_total'];
        $totalPaid = (float) $row['total_paid'];

        return [
            'order_id' => (int) $row['order_id'],
            'order_code' => $row['order_code'],
            'grand_total' => $grandTotal,
            'total_paid' => $totalPaid,
            'remaining_balance' => $grandTotal - $totalPaid,
            'payment_state' => $this->calculatePaymentState($grandTotal, $totalPaid),
        ];
    }

    public function paymentStates(): array
    {
        if ($this->paymentStatusViewExists()) {
            $statement = $this->db->query(
                'SELECT
                    o.order_id,
                    o.order_code,
                    o.tanggal_order,
                    o.total_qty,
                    o.grand_total,
                    COALESCE(NULLIF(TRIM(c.nama), \'\'), \'Pelanggan Umum\') AS customer_name,
                    v.total_paid,
                    v.remaining_balance,
                    v.calculated_payment_status AS payment_state
                 FROM v_order_payment_status v
                 INNER JOIN orders o ON o.order_id = v.order_id
                 LEFT JOIN customers c ON c.customer_id = o.customer_id
                 ORDER BY o.tanggal_order DESC, o.order_id DESC'
            );

            return array_map([$this, 'normalizePaymentStateRow'], $statement->fetchAll());
        }

        $paidStatus = self::PAYMENT_STATUS_PAID;
        $statement = $this->db->query(
            "SELECT
                o.order_id,
                o.order_code,
                o.tanggal_order,
                o.total_qty,
                o.grand_total,
                COALESCE(NULLIF(TRIM(c.nama), ''), 'Pelanggan Umum') AS customer_name,
                COALESCE(SUM(CASE WHEN p.status_bayar = '{$paidStatus}' THEN p.jumlah_bayar ELSE 0 END), 0) AS total_paid
             FROM orders o
             LEFT JOIN customers c ON c.customer_id = o.customer_id
             LEFT JOIN payments p ON p.order_id = o.order_id
             GROUP BY o.order_id, o.order_code, o.tanggal_order, o.total_qty, o.grand_total, customer_name
             ORDER BY o.tanggal_order DESC, o.order_id DESC"
        );

        return array_map(function (array $row): array {
            $grandTotal = (float) $row['grand_total'];
            $totalPaid = (float) $row['total_paid'];

            $row['remaining_balance'] = $grandTotal - $totalPaid;
            $row['payment_state'] = $this->calculatePaymentState($grandTotal, $totalPaid);

            return $this->normalizePaymentStateRow($row);
        }, $statement->fetchAll());
    }

    private function paymentStatusViewExists(): bool
    {
        $statement = $this->db->query(
            "SELECT COUNT(*)
             FROM information_schema.VIEWS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'v_order_payment_status'"
        );

        return (int) $statement->fetchColumn() > 0;
    }

    private function normalizePaymentStateRow(array $row): array
    {
        $grandTotal = (float) ($row['grand_total'] ?? 0);
        $totalPaid = (float) ($row['total_paid'] ?? 0);
        $remainingBalance = (float) ($row['remaining_balance'] ?? ($grandTotal - $totalPaid));
        $paymentState = (string) ($row['payment_state'] ?? $this->calculatePaymentState($grandTotal, $totalPaid));

        $row['total_qty'] = (int) ($row['total_qty'] ?? 0);
        $row['grand_total'] = $grandTotal;
        $row['total_paid'] = $totalPaid;
        $row['remaining_balance'] = $remainingBalance;
        $row['payment_state'] = $paymentState;

        return $row;
    }
}
