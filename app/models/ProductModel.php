<?php

class ProductModel extends Model
{
    // ──────────────────────────────────────────────────────────────
    // READ
    // ──────────────────────────────────────────────────────────────

    public function allCategories(): array
    {
        return $this->db
            ->query('SELECT * FROM product_categories WHERE aktif = 1 ORDER BY category_id')
            ->fetchAll();
    }

    public function allVariants(): array
    {
        return $this->db->query('
            SELECT
                pv.variant_id,
                pv.product_id,
                pv.nama_varian,
                pv.bahan,
                pv.tipe_sablon_bordir,
                pv.harga_start_from,
                pv.aktif          AS varian_aktif,
                p.nama_produk,
                p.minimal_order,
                p.aktif           AS produk_aktif,
                pc.category_id,
                pc.nama_kategori
            FROM product_variants pv
            JOIN products           p  ON pv.product_id  = p.product_id
            JOIN product_categories pc ON p.category_id  = pc.category_id
            WHERE pv.aktif = 1
              AND p.aktif  = 1
              AND pc.aktif = 1
            ORDER BY pc.category_id, p.product_id, pv.variant_id
        ')->fetchAll();
    }

    public function allOptions(): array
    {
        try {
            return $this->db->query(
                'SELECT
                    pvo.option_id,
                    pvo.variant_id,
                    pvo.size_id,
                    pvo.color_id,
                    COALESCE(pvo.qty, 1) AS qty,
                    ps.size_name,
                    pc.color_name
                FROM product_variant_options pvo
                JOIN product_size  ps ON pvo.size_id  = ps.size_id
                JOIN product_color pc ON pvo.color_id = pc.color_id
                ORDER BY pvo.variant_id, ps.size_id, pc.color_id'
            )->fetchAll();
        } catch (PDOException $e) {
            // Fallback for databases without `qty` column — return same structure with default qty=1
            $rows = $this->db->query(
                'SELECT
                    pvo.option_id,
                    pvo.variant_id,
                    pvo.size_id,
                    pvo.color_id,
                    ps.size_name,
                    pc.color_name
                FROM product_variant_options pvo
                JOIN product_size  ps ON pvo.size_id  = ps.size_id
                JOIN product_color pc ON pvo.color_id = pc.color_id
                ORDER BY pvo.variant_id, ps.size_id, pc.color_id'
            )->fetchAll();

            // Normalize to include qty key with default 1
            foreach ($rows as &$r) {
                $r['qty'] = 1;
            }

            return $rows;
        }
    }

    public function allSizes(): array
    {
        return $this->db
            ->query('SELECT * FROM product_size ORDER BY size_id')
            ->fetchAll();
    }

    public function allColors(): array
    {
        return $this->db
            ->query('SELECT * FROM product_color ORDER BY color_id')
            ->fetchAll();
    }

    // ──────────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────────

    /**
     * Cari product_id yang sudah ada (category + nama), atau buat baru.
     */
    public function findOrCreateProduct(int $categoryId, string $namaProduk): int
    {
        $stmt = $this->db->prepare('
            SELECT product_id FROM products
            WHERE category_id = ? AND nama_produk = ?
            LIMIT 1
        ');
        $stmt->execute([$categoryId, $namaProduk]);
        $row = $stmt->fetch();

        if ($row) {
            return (int) $row['product_id'];
        }

        $this->db->prepare('
            INSERT INTO products (category_id, nama_produk, deskripsi, minimal_order, aktif)
            VALUES (?, ?, ?, 24, 1)
        ')->execute([$categoryId, $namaProduk, $namaProduk]);

        return (int) $this->db->lastInsertId();
    }

    public function createVariant(int $productId, array $data): int
    {
        $this->db->prepare('
            INSERT INTO product_variants
                (product_id, nama_varian, bahan, tipe_sablon_bordir, harga_start_from, aktif)
            VALUES (?, ?, ?, ?, ?, 1)
        ')->execute([
            $productId,
            $data['nama_varian'],
            $data['bahan']       ?? null,
            $data['tipe_sablon'] ?? null,
            $data['harga']       ?? 0,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function createOption(int $variantId, int $sizeId, int $colorId, int $qty = 1): void
    {
        $this->db->prepare('
            INSERT INTO product_variant_options (variant_id, size_id, color_id, qty)
            VALUES (?, ?, ?, ?)
        ')->execute([$variantId, $sizeId, $colorId, $qty]);
    }

    // ──────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────

    public function updateVariant(int $variantId, array $data): void
    {
        $this->db->prepare('
            UPDATE product_variants
            SET nama_varian        = ?,
                bahan              = ?,
                tipe_sablon_bordir = ?,
                harga_start_from   = ?
            WHERE variant_id = ?
        ')->execute([
            $data['nama_varian'],
            $data['bahan']       ?? null,
            $data['tipe_sablon'] ?? null,
            $data['harga']       ?? 0,
            $variantId,
        ]);
    }

    public function deleteOptionsByVariant(int $variantId): void
    {
        $this->db->prepare('
            DELETE FROM product_variant_options WHERE variant_id = ?
        ')->execute([$variantId]);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────────────────────────

    /** Soft-delete: set aktif = 0 */
    public function softDeleteVariant(int $variantId): void
    {
        $this->db->prepare('
            UPDATE product_variants SET aktif = 0 WHERE variant_id = ?
        ')->execute([$variantId]);
    }

    public function deleteOption(int $optionId): void
    {
        $this->db->prepare('
            DELETE FROM product_variant_options WHERE option_id = ?
        ')->execute([$optionId]);
    }
}
