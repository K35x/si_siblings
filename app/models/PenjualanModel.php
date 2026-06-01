<?php

class PenjualanModel extends Model
{
    public function calculatePaymentState(float $grandTotal, float $totalPaid): string
    {
        if ($totalPaid <= 0) {
            return self::PAYMENT_STATE_UNPAID;
        }

        if ($totalPaid < $grandTotal) {
            return self::PAYMENT_STATE_PARTIAL;
        }

        if (abs($totalPaid - $grandTotal) < 0.01) {
            return self::PAYMENT_STATE_PAID;
        }

        return self::PAYMENT_STATE_OVERPAID;
    }

    public function allCategories(): array
    {
        return $this->db->query('SELECT category_name FROM product_categories WHERE is_active = 1 ORDER BY category_name')->fetchAll();
    }

    public function validatePaymentInput(array $payload): array
    {
        $errors = [];

        if (! isset($payload['amount']) || ! is_numeric($payload['amount']) || (float) $payload['amount'] <= 0) {
            $errors['amount'] = 'Jumlah pembayaran harus lebih besar dari nol.';
        }

        if (isset($payload['payment_method']) && ! isset(self::PAYMENT_METHODS[$payload['payment_method']])) {
            $errors['payment_method'] = 'Metode pembayaran tidak valid. Pilih salah satu: ' . implode(', ', self::PAYMENT_METHODS) . '.';
        }

        if (isset($payload['payment_status']) && ! in_array((string) $payload['payment_status'], self::ALLOWED_PAYMENT_STATUSES, true)) {
            $errors['payment_status'] = 'Status pembayaran tidak valid. Pilih salah satu: ' . implode(', ', self::ALLOWED_PAYMENT_STATUSES) . '.';
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
                COALESCE(SUM(CASE WHEN p.payment_status = '{$paidStatus}' THEN p.amount ELSE 0 END), 0) AS total_paid
             FROM orders o
             LEFT JOIN payments p ON p.order_id = o.order_id
             WHERE o.order_id = :order_id
             GROUP BY o.order_id, o.order_code, o.grand_total
             LIMIT 1"
        );
        $statement->execute(['order_id' => $orderId]);
        $row = $statement->fetch();

        if (! $row) {
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

    public function salesReportSummary(?string $from = null, ?string $to = null, ?string $category = null): array
    {
        [$start, $end] = $this->resolveReportRange($from, $to);
        $paidStatus = self::PAYMENT_STATUS_PAID;
        $categoryClause = '';
        $categoryParams = [];

        if ($category !== null && $category !== '') {
            $categoryClause = ' AND o.order_id IN (
                SELECT oi.order_id FROM order_items oi
                JOIN product_variants pv ON pv.variant_id = oi.variant_id
                JOIN products p ON p.product_id = pv.product_id
                JOIN product_categories pc ON pc.category_id = p.category_id
                WHERE pc.category_name = :category
            )';
            $categoryParams['category'] = $category;
        }

        $revenueStatement = $this->db->prepare(
            "SELECT COALESCE(SUM(p.amount), 0)
             FROM payments p
             JOIN orders o ON o.order_id = p.order_id
             WHERE p.payment_status = :paid_status
               AND p.payment_date BETWEEN :start_date AND :end_date
               {$categoryClause}"
        );
        $revenueStatement->execute(array_merge([
            'paid_status' => $paidStatus,
            'start_date' => $start,
            'end_date' => $end,
        ], $categoryParams));
        $totalRevenue = (float) $revenueStatement->fetchColumn();

        $orderStatement = $this->db->prepare(
            "SELECT COUNT(*) AS order_count, COALESCE(AVG(o.grand_total), 0) AS average_order_value
             FROM orders o
             WHERE o.order_status != :cancelled_status
               AND o.order_date BETWEEN :start_date AND :end_date
               {$categoryClause}"
        );
        $orderStatement->execute(array_merge([
            'cancelled_status' => self::ORDER_STATUS_CANCELLED,
            'start_date' => $start,
            'end_date' => $end,
        ], $categoryParams));
        $orderRow = $orderStatement->fetch() ?: [];

        return [
            'start_date' => $start,
            'end_date' => $end,
            'total_revenue' => $totalRevenue,
            'total_receivables' => $this->salesReportReceivables($start, $end, $category),
            'order_count' => (int) ($orderRow['order_count'] ?? 0),
            'average_order_value' => (float) ($orderRow['average_order_value'] ?? 0),
        ];
    }

    private function resolveReportRange(?string $from, ?string $to): array
    {
        if ($from !== null && $from !== '') {
            $start = $from . ' 00:00:00';
            $end = ($to !== null && $to !== '') ? $to . ' 23:59:59' : '9999-12-31 23:59:59';
            return [$start, $end];
        }

        if ($to !== null && $to !== '') {
            return ['1000-01-01 00:00:00', $to . ' 23:59:59'];
        }

        // Default: bulan ini
        $year = date('Y');
        $month = date('m');
        $start = "{$year}-{$month}-01 00:00:00";
        $end = date('Y-m-t 23:59:59', strtotime($start));
        return [$start, $end];
    }

    private function salesReportReceivables(string $start, string $end, ?string $category): float
    {
        $categoryClause = '';
        $params = [
            'cancelled_status' => self::ORDER_STATUS_CANCELLED,
            'start_date' => $start,
            'end_date' => $end,
        ];

        if ($category !== null && $category !== '') {
            $categoryClause = ' AND o.order_id IN (
                SELECT oi.order_id FROM order_items oi
                JOIN product_variants pv ON pv.variant_id = oi.variant_id
                JOIN products p ON p.product_id = pv.product_id
                JOIN product_categories pc ON pc.category_id = p.category_id
                WHERE pc.category_name = :category
            )';
            $params['category'] = $category;
        }

        $paidStatus = self::PAYMENT_STATUS_PAID;
        $statement = $this->db->prepare(
            "SELECT COALESCE(SUM(GREATEST(o.grand_total - COALESCE(paid.total_paid, 0), 0)), 0)
             FROM orders o
             LEFT JOIN (
                SELECT order_id, SUM(amount) AS total_paid
                FROM payments
                WHERE payment_status = :paid_status
                GROUP BY order_id
             ) paid ON paid.order_id = o.order_id
             WHERE o.order_status != :cancelled_status
               AND o.order_date BETWEEN :start_date AND :end_date
               {$categoryClause}"
        );
        $params['paid_status'] = $paidStatus;
        $statement->execute($params);

        return (float) $statement->fetchColumn();
    }

    public function paymentStates(?string $from = null, ?string $to = null, ?string $statusOrder = null, ?string $category = null): array
    {
        $where = [];
        $params = [];

        if ($from !== null && $from !== '') {
            $where[] = 'o.order_date >= :from';
            $params['from'] = $from . ' 00:00:00';
        }
        if ($to !== null && $to !== '') {
            $where[] = 'o.order_date <= :to';
            $params['to'] = $to . ' 23:59:59';
        }
        if ($statusOrder !== null && $statusOrder !== '') {
            $where[] = 'o.order_status = :order_status';
            $params['order_status'] = $statusOrder;
        } else {
            $where[] = 'o.order_status != :cancelled_status';
            $params['cancelled_status'] = self::ORDER_STATUS_CANCELLED;
        }
        if ($category !== null && $category !== '') {
            $where[] = 'o.order_id IN (
                SELECT oi.order_id FROM order_items oi
                JOIN product_variants pv ON pv.variant_id = oi.variant_id
                JOIN products p ON p.product_id = pv.product_id
                JOIN product_categories pc ON pc.category_id = p.category_id
                WHERE pc.category_name = :category
            )';
            $params['category'] = $category;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $paidStatus = self::PAYMENT_STATUS_PAID;
        $sql = "SELECT
                o.order_id,
                o.order_code,
                o.order_date,
                o.order_status,
                COALESCE(qty.total_qty, 0) AS total_qty,
                o.grand_total,
                COALESCE(NULLIF(TRIM(c.name), ''), 'Pelanggan Umum') AS customer_name,
                COALESCE(paid.total_paid, 0) AS total_paid,
                GREATEST(o.grand_total - COALESCE(paid.total_paid, 0), 0) AS remaining_balance,
                CASE
                    WHEN COALESCE(paid.total_paid, 0) <= 0 THEN 'unpaid'
                    WHEN COALESCE(paid.total_paid, 0) < o.grand_total THEN 'partial'
                    WHEN ABS(COALESCE(paid.total_paid, 0) - o.grand_total) < 0.01 THEN 'paid'
                    ELSE 'overpaid'
                END AS payment_state
             FROM orders o
             LEFT JOIN customers c ON c.customer_id = o.customer_id
             LEFT JOIN (
                SELECT oi.order_id, COALESCE(SUM(oid.quantity), 0) AS total_qty
                FROM order_items oi
                LEFT JOIN order_item_details oid ON oid.order_item_id = oi.order_item_id
                GROUP BY oi.order_id
             ) qty ON qty.order_id = o.order_id
             LEFT JOIN (
                SELECT order_id, SUM(amount) AS total_paid
                FROM payments
                WHERE payment_status = :paid_status
                GROUP BY order_id
             ) paid ON paid.order_id = o.order_id
             {$whereClause}
             ORDER BY o.order_date DESC, o.order_id DESC";

        $params['paid_status'] = $paidStatus;
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return array_map([$this, 'normalizePaymentStateRow'], $statement->fetchAll());
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
