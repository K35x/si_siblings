<?php

class DashboardModel extends Model
{
    public function countOrdersByStatuses(array $statuses): int
    {
        if ($statuses === []) {
            return 0;
        }

        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM orders WHERE order_status IN ($placeholders)",
        );
        $stmt->execute($statuses);

        return (int) $stmt->fetchColumn();
    }

    public function todayRevenue(): float
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) FROM payments
            WHERE payment_status = 'paid' AND DATE(payment_date) = CURDATE()
        ");
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function monthlyCategorySales(): array
    {
        // Monthly category sales — only paid orders, current month.
        // All date/status values are SQL functions or static strings (no user input).
        $stmt = $this->db->prepare("
            SELECT pc.category_name AS label, COALESCE(SUM(CASE WHEN o.order_id IS NULL THEN 0 ELSE oid.quantity END), 0) AS total
            FROM product_categories pc
            LEFT JOIN products p ON p.category_id = pc.category_id AND p.is_active = 1
            LEFT JOIN product_variants pv ON pv.product_id = p.product_id AND pv.is_active = 1
            LEFT JOIN order_items oi ON oi.variant_id = pv.variant_id
            LEFT JOIN order_item_details oid ON oid.order_item_id = oi.order_item_id
            LEFT JOIN orders o ON o.order_id = oi.order_id
                AND o.order_status != 'cancelled'
                AND YEAR(o.order_date) = YEAR(CURDATE()) AND MONTH(o.order_date) = MONTH(CURDATE())
                AND EXISTS (SELECT 1 FROM payments py WHERE py.order_id = o.order_id AND py.payment_status = 'paid')
            WHERE pc.is_active = 1
            GROUP BY pc.category_id, pc.category_name
            ORDER BY total DESC, pc.category_name ASC
        ");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function activeOrderQueue(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT
                o.order_code AS id,
                c.name AS customer,
                o.order_status AS status,
                o.order_date AS tanggal,
                CASE o.order_status
                    WHEN 'in_progress' THEN 'Proses'
                    WHEN 'ready' THEN 'Siap'
                    WHEN 'confirmed' THEN 'Konfirmasi'
                    ELSE 'Baru'
                END AS label
            FROM orders o
            JOIN customers c ON c.customer_id = o.customer_id
            WHERE o.order_status IN ('pending_payment', 'confirmed', 'in_progress', 'ready')
            ORDER BY o.order_date DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function recentOrders(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT
                o.order_code,
                o.order_status,
                o.grand_total,
                c.name AS customer_name,
                o.order_date,
                COALESCE(paid.total_paid, 0) AS total_paid,
                CASE
                    WHEN COALESCE(paid.total_paid, 0) <= 0 THEN 'unpaid'
                    WHEN COALESCE(paid.total_paid, 0) < o.grand_total THEN 'partial'
                    WHEN ABS(COALESCE(paid.total_paid, 0) - o.grand_total) < 0.01 THEN 'paid'
                    ELSE 'overpaid'
                END AS payment_state
            FROM orders o
            JOIN customers c ON c.customer_id = o.customer_id
            LEFT JOIN (
                SELECT order_id, SUM(amount) AS total_paid
                FROM payments
                WHERE payment_status = 'paid'
                GROUP BY order_id
            ) paid ON paid.order_id = o.order_id
            ORDER BY o.order_id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
