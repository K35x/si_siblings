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
            LEFT JOIN customers c ON c.customer_id = o.customer_id
            ORDER BY o.tanggal_order DESC, o.order_id DESC'
        );

        return $statement->fetchAll();
    }
}
