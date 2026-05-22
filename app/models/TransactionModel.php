<?php

class TransactionModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->prepare("
        SELECT 
    o.*,
    c.nama
FROM orders o
JOIN customers c
    ON o.customer_id = c.customer_id
ORDER BY o.order_id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // =========================
    // FIND ORDER (detail)
    // =========================
    public function findOrder(string $orderCode): ?array
    {
        $stmt = $this->db->prepare("
            SELECT o.*, c.nama, c.no_hp
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            WHERE o.order_code = ?
            LIMIT 1
        ");
        $stmt->execute([$orderCode]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM order_items
            WHERE order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    // =========================
    // PRODUK AKTIF
    // =========================
    public function allProducts(): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM products
            WHERE aktif = 1
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // =========================
    // CREATE ORDER
    // =========================
    public function createOrder(array $data)
    {
        $product_id = (int)$data['product_id'];
        $qty        = (int)$data['qty'];
        $user_id    = (int)$data['user_id'];

        // =========================
        // AMBIL PRODUK
        // =========================
        $stmt = $this->db->prepare("
            SELECT *
            FROM products
            WHERE product_id = ?
        ");

        $stmt->execute([$product_id]);

        $product = $stmt->fetch();

        // VALIDASI PRODUK
        if (!$product) {
            return false;
        }

        // VALIDASI STOK
        if ($qty > $product['stok']) {
            return false;
        }

        $harga = (float)$product['harga'];
        $subtotal = $harga * $qty;

        // =========================
        // INSERT ORDER
        // =========================
        $orderCode = 'ORD' . time();

        $stmt = $this->db->prepare("
            INSERT INTO orders (
                order_code,
                tanggal_order,
                status_order,
                total_qty,
                subtotal,
                grand_total,
                user_id
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $orderCode,
            date('Y-m-d H:i:s'),
            'diproses',
            $qty,
            $subtotal,
            $subtotal,
            $user_id
        ]);

        $orderId = $this->db->lastInsertId();

        // =========================
        // INSERT ORDER ITEM
        // =========================
        $stmt = $this->db->prepare("
            INSERT INTO order_items (
                order_id,
                product_id,
                qty,
                harga_satuan,
                subtotal
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $orderId,
            $product_id,
            $qty,
            $harga,
            $subtotal
        ]);

        // =========================
        // UPDATE STOK
        // =========================
        $stmt = $this->db->prepare("
            UPDATE products
            SET stok = stok - ?
            WHERE product_id = ?
        ");

        $stmt->execute([
            $qty,
            $product_id
        ]);

        return $orderId;
    }
}
