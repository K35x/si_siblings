<?php

class StockModel extends Model
{
    private const MAX_STOCK_QTY = 999999;
    public function __construct(?PDO $db = null)
    {
        if ($db !== null) {
            $this->db = $db;
            return;
        }

        parent::__construct();
    }

    public function allStock(): array
    {
        return $this->db
            ->query(
                '
            SELECT
                vo.option_id,
                vo.variant_id,
                vo.quantity,
                vo.sleeve_type,
                s.size_name,
                c.color_name,
                pv.variant_name,
                pv.material,
                pv.price,
                p.product_name,
                pc.category_name,
                CASE
                    WHEN vo.quantity <= 0 THEN "OUT_OF_STOCK"
                    WHEN vo.quantity < 5 THEN "LOW_STOCK"
                    ELSE "IN_STOCK"
                END AS stock_status
            FROM variant_options vo
            JOIN sizes s ON s.size_id = vo.size_id
            JOIN colors c ON c.color_id = vo.color_id
            JOIN product_variants pv ON pv.variant_id = vo.variant_id
            JOIN products p ON p.product_id = pv.product_id
            JOIN product_categories pc ON pc.category_id = p.category_id
            WHERE vo.is_active = 1 AND pv.is_active = 1 AND p.is_active = 1
            ORDER BY
                CASE WHEN vo.quantity <= 0 THEN 0 WHEN vo.quantity < 5 THEN 1 ELSE 2 END,
                pc.category_name, p.product_name, pv.variant_name, s.size_id, c.color_id, vo.sleeve_type
        ',
            )
            ->fetchAll();
    }

    public function getLowStock(int $threshold): array
    {
        return array_values(array_filter($this->allStock(), static fn (array $row): bool => (int) ($row['quantity'] ?? 0) < $threshold));
    }

    public function adjustStock(int $optionId, int $adjustment): array
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                'SELECT quantity FROM variant_options WHERE option_id = ? FOR UPDATE',
            );
            $stmt->execute([$optionId]);
            $row = $stmt->fetch();

            if (!$row) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Stok tidak ditemukan.',
                ];
            }

            $oldQty = (int) $row['quantity'];
            $newQty = $oldQty + $adjustment;
            if ($newQty < 0) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Stok tidak bisa negatif.',
                ];
            }

            if ($newQty > self::MAX_STOCK_QTY) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Batas maksimal stok adalah 999.999.',
                ];
            }

            $this->db
                ->prepare(
                    'UPDATE variant_options SET quantity = quantity + ? WHERE option_id = ?',
                )
                ->execute([$adjustment, $optionId]);

            $this->db->commit();

            return [
                'success' => true,
                'message' =>
                    'Stok berhasil di' .
                    ($adjustment > 0 ? 'tambah' : 'kurangi') .
                    ". Qty: {$oldQty} → {$newQty}",
                'new_qty' => $newQty,
            ];
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function allocateFulfillment(?int $variantId, int $sizeId, ?int $colorId, int $requestedQty, string $tipeLengan = Model::SLEEVE_SHORT): array
    {
        if ($requestedQty < 1) {
            return [];
        }

        if ($variantId === null) {
            return [
                [
                    'fulfillment_type' => Model::FULFILLMENT_CUSTOM,
                    'option_id' => null,
                    'qty' => $requestedQty,
                ],
            ];
        }

        $lookupColorId = $colorId ?? 1;

        $stmt = $this->db->prepare('
            SELECT option_id, quantity, is_active
            FROM variant_options
            WHERE variant_id = ? AND size_id = ? AND color_id = ? AND sleeve_type = ?
            FOR UPDATE
        ');
        $stmt->execute([$variantId, $sizeId, $lookupColorId, $tipeLengan]);
        $option = $stmt->fetch();

        if (!$option) {
            return [
                [
                    'fulfillment_type' => Model::FULFILLMENT_CUSTOM,
                    'option_id' => null,
                    'qty' => $requestedQty,
                ],
            ];
        }

        if ((int) ($option['is_active'] ?? 0) !== 1) {
            throw new \RuntimeException('Opsi varian sudah nonaktif dan tidak bisa dipesan.');
        }

        $readyQty = min($requestedQty, max(0, (int) $option['quantity']));
        $customQty = $requestedQty - $readyQty;
        $rows = [];

        if ($readyQty > 0) {
            $update = $this->db->prepare('
                UPDATE variant_options
                SET quantity = quantity - ?
                WHERE option_id = ? AND quantity >= ?
            ');
            $update->execute([$readyQty, (int) $option['option_id'], $readyQty]);

            if ($update->rowCount() !== 1) {
                throw new \RuntimeException('Stok ready berubah saat order diproses. Silakan coba lagi.');
            }

            $rows[] = [
                'fulfillment_type' => Model::FULFILLMENT_READY_STOCK,
                'option_id' => (int) $option['option_id'],
                'qty' => $readyQty,
            ];
        }

        if ($customQty > 0) {
            $rows[] = [
                'fulfillment_type' => Model::FULFILLMENT_CUSTOM,
                'option_id' => (int) $option['option_id'],
                'qty' => $customQty,
            ];
        }

        return $rows;
    }

    public function getStockForVariant(int $variantId): array
    {
        $stmt = $this->db->prepare('
            SELECT vo.option_id, vo.quantity, s.size_name, c.color_name, vo.sleeve_type
            FROM variant_options vo
            JOIN sizes s ON s.size_id = vo.size_id
            JOIN colors c ON c.color_id = vo.color_id
            WHERE vo.variant_id = ? AND vo.is_active = 1
            ORDER BY s.size_id, c.color_id, vo.sleeve_type
        ');
        $stmt->execute([$variantId]);
        return $stmt->fetchAll();
    }
}
