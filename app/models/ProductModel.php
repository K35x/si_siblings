<?php

class ProductModel extends Model
{
    public function stockList(): array
    {
        $statement = $this->db->query(
            'SELECT
                ps.stock_id,
                pc.category_id,
                pc.nama_kategori,
                p.product_id,
                p.nama_produk,
                pv.variant_id,
                pv.nama_varian,
                pv.bahan,
                s.size_id,
                s.size_name,
                c.color_id,
                c.color_name,
                ps.qty
            FROM product_stock ps
            JOIN product_variants pv ON pv.variant_id = ps.variant_id
            JOIN products p ON p.product_id = pv.product_id
            JOIN product_categories pc ON pc.category_id = p.category_id
            JOIN product_size s ON s.size_id = ps.size_id
            LEFT JOIN product_color c ON c.color_id = ps.color_id
            WHERE p.aktif = 1 AND pv.aktif = 1 AND pc.aktif = 1
            ORDER BY pc.nama_kategori, p.nama_produk, pv.nama_varian, s.size_id, c.color_name'
        );

        return $statement->fetchAll();
    }

    public function stockSummary(): array
    {
        $statement = $this->db->query(
            'SELECT
                COALESCE(SUM(qty), 0) AS total_qty,
                COALESCE(SUM(CASE WHEN qty <= 10 THEN qty ELSE 0 END), 0) AS low_stock_qty,
                COALESCE(SUM(CASE WHEN qty <= 10 THEN 1 ELSE 0 END), 0) AS low_stock_items
            FROM product_stock'
        );

        $summary = $statement->fetch();

        return [
            'total_qty' => (int) ($summary['total_qty'] ?? 0),
            'low_stock_qty' => (int) ($summary['low_stock_qty'] ?? 0),
            'low_stock_items' => (int) ($summary['low_stock_items'] ?? 0),
        ];
    }

    public function categoriesWithStock(): array
    {
        $statement = $this->db->query(
            'SELECT
                pc.nama_kategori,
                COALESCE(SUM(ps.qty), 0) AS total_qty
            FROM product_categories pc
            JOIN products p ON p.category_id = pc.category_id
            JOIN product_variants pv ON pv.product_id = p.product_id
            JOIN product_stock ps ON ps.variant_id = pv.variant_id
            WHERE pc.aktif = 1 AND p.aktif = 1 AND pv.aktif = 1
            GROUP BY pc.category_id, pc.nama_kategori
            ORDER BY pc.nama_kategori'
        );

        return $statement->fetchAll();
    }
}
