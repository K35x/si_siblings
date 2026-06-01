<?php

class ProductModel extends Model
{
    public function allCategories(): array
    {
        return $this->db
            ->query(
                'SELECT category_id, category_name, is_active
             FROM product_categories WHERE is_active = 1 ORDER BY category_id ASC',
            )
            ->fetchAll();
    }

    public function allCategoriesForManagement(): array
    {
        return $this->db
            ->query(
                'SELECT category_id, category_name, is_active
             FROM product_categories ORDER BY is_active DESC, category_id ASC',
            )
            ->fetchAll();
    }

    public function findByFormKey(string $formKey): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT category_id, category_name
             FROM product_categories
             WHERE LOWER(REPLACE(category_name, " ", "-")) = ? AND is_active = 1',
        );
        $stmt->execute([strtolower($formKey)]);
        return $stmt->fetch() ?: null;
    }

    public function categoryFormRouteMap(): array
    {
        $categories = $this->allCategories();
        $map = [];
        foreach ($categories as $cat) {
            $formKey = strtolower(str_replace(' ', '-', $cat['category_name']));
            $map[$cat['category_name']] = '/transactions/form/' . $formKey;
        }
        return $map;
    }

    public function allVariants(): array
    {
        return $this->db
            ->query(
                '
            SELECT
                pv.variant_id,
                pv.product_id,
                pv.variant_name,
                pv.material,
                pv.price,
                pv.sleeve_price,
                pv.is_active      AS varian_aktif,
                p.product_name,
                p.minimum_order,
                p.is_active       AS produk_aktif,
                pc.category_id,
                pc.category_name,
                COALESCE(vos.total_qty, 0) AS stok,
                10 AS stok_min
            FROM product_variants pv
            JOIN products           p  ON pv.product_id  = p.product_id
            JOIN product_categories pc ON p.category_id  = pc.category_id
            LEFT JOIN (
                SELECT variant_id, SUM(quantity) AS total_qty
                FROM variant_options
                WHERE is_active = 1
                GROUP BY variant_id
            ) vos ON vos.variant_id = pv.variant_id
            WHERE p.is_active  = 1
              AND pc.is_active = 1
            ORDER BY pc.category_id, p.product_id, pv.is_active DESC, pv.variant_id
        ',
            )
            ->fetchAll();
    }

    public function variantsByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('
            SELECT
                pv.variant_id,
                pv.variant_name,
                pv.material,
                pv.price,
                p.product_name,
                p.minimum_order
            FROM product_variants pv
            JOIN products p  ON pv.product_id = p.product_id
            WHERE p.category_id = :cat
              AND pv.is_active = 1
              AND p.is_active  = 1
            ORDER BY pv.price, pv.variant_id
        ');
        $stmt->execute(['cat' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function sizeSurchargesByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('
            SELECT vo.variant_id, s.size_name, MAX(vo.price_surcharge) AS surcharge
            FROM variant_options vo
            JOIN sizes s ON vo.size_id = s.size_id
            JOIN product_variants pv ON vo.variant_id = pv.variant_id
            JOIN products p ON pv.product_id = p.product_id
            WHERE p.category_id = :cat
              AND vo.is_active = 1
              AND vo.price_surcharge > 0
            GROUP BY vo.variant_id, s.size_name
        ');
        $stmt->execute(['cat' => $categoryId]);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[(int) $row['variant_id']][$row['size_name']] =
                (float) $row['surcharge'];
        }
        return $result;
    }

    public function sablonTypesByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('
            SELECT st.sablon_type_id, st.sablon_name
            FROM sablon_types st
            JOIN category_sablon_types cst ON cst.sablon_type_id = st.sablon_type_id
            WHERE cst.category_id = :cat AND cst.is_active = 1 AND st.is_active = 1
            ORDER BY st.sablon_name
        ');
        $stmt->execute(['cat' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function findSablonType(int $sablonTypeId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT sablon_type_id, sablon_name FROM sablon_types WHERE sablon_type_id = ? AND is_active = 1',
        );
        $stmt->execute([$sablonTypeId]);
        return $stmt->fetch() ?: null;
    }

    public function allOptions(): array
    {
        try {
            return $this->db
                ->query(
                    'SELECT
                    pvo.option_id,
                    pvo.variant_id,
                    pvo.size_id,
                    pvo.color_id,
                    pvo.sleeve_type,
                    COALESCE(pvo.quantity, 1) AS quantity,
                    pvo.price_surcharge,
                    ps.size_name,
                    pc.color_name
                FROM variant_options pvo
                JOIN sizes  ps ON pvo.size_id  = ps.size_id
                JOIN colors pc ON pvo.color_id = pc.color_id
                WHERE pvo.is_active = 1
                ORDER BY pvo.variant_id, ps.size_id, pc.color_id, pvo.sleeve_type',
                )
                ->fetchAll();
        } catch (PDOException $e) {
            $rows = $this->db
                ->query(
                    'SELECT
                    pvo.option_id,
                    pvo.variant_id,
                    pvo.size_id,
                    pvo.color_id,
                    pvo.sleeve_type,
                    ps.size_name,
                    pc.color_name
                FROM variant_options pvo
                JOIN sizes  ps ON pvo.size_id  = ps.size_id
                JOIN colors pc ON pvo.color_id = pc.color_id
                WHERE pvo.is_active = 1
                ORDER BY pvo.variant_id, ps.size_id, pc.color_id, pvo.sleeve_type',
                )
                ->fetchAll();

            foreach ($rows as &$r) {
                $r['quantity'] = 1;
            }

            return $rows;
        }
    }

    public function allSizes(): array
    {
        return $this->db
            ->query('SELECT * FROM sizes WHERE is_active = 1 ORDER BY size_id')
            ->fetchAll();
    }

    public function allColors(): array
    {
        return $this->db
            ->query(
                'SELECT * FROM colors WHERE is_active = 1 ORDER BY color_id',
            )
            ->fetchAll();
    }

    public function findOrCreateProduct(
        int $categoryId,
        string $namaProduk,
        int $minimumOrder = 24,
    ): int {
        $stmt = $this->db->prepare('
            SELECT product_id FROM products
            WHERE category_id = ? AND product_name = ? AND is_active = 1
            LIMIT 1
        ');
        $stmt->execute([$categoryId, $namaProduk]);
        $row = $stmt->fetch();

        if ($row) {
            return (int) $row['product_id'];
        }

        $this->db
            ->prepare(
                '
            INSERT INTO products (category_id, product_name, minimum_order, is_active)
            VALUES (?, ?, ?, 1)
        ',
            )
            ->execute([$categoryId, $namaProduk, $minimumOrder]);

        return (int) $this->db->lastInsertId();
    }

    public function createVariant(int $productId, array $data): int
    {
        $this->db
            ->prepare(
                '
            INSERT INTO product_variants
                (product_id, variant_name, material, price, sleeve_price, is_active)
            VALUES (?, ?, ?, ?, ?, 1)
        ',
            )
            ->execute([
                $productId,
                $data['variant_name'],
                $data['material'] ?? null,
                $data['price'] ?? 0,
                $data['sleeve_price'] ?? 5000,
            ]);

        return (int) $this->db->lastInsertId();
    }

    public function getVariantOptions(int $variantId): array
    {
        $stmt = $this->db->prepare('
            SELECT vo.*, s.size_name, c.color_name
            FROM variant_options vo
            JOIN sizes s ON vo.size_id = s.size_id
            JOIN colors c ON vo.color_id = c.color_id
            WHERE vo.variant_id = ?
            ORDER BY vo.is_active DESC, s.size_id, c.color_id, vo.sleeve_type
        ');
        $stmt->execute([$variantId]);
        return $stmt->fetchAll();
    }

    public function findVariant(int $variantId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT pv.*, p.product_name, pc.category_name
            FROM product_variants pv
            JOIN products p ON p.product_id = pv.product_id
            JOIN product_categories pc ON pc.category_id = p.category_id
            WHERE pv.variant_id = ?
            LIMIT 1
        ');
        $stmt->execute([$variantId]);
        return $stmt->fetch() ?: null;
    }

    public function createVariantOption(array $data): int
    {
        $this->db
            ->prepare(
                '
            INSERT INTO variant_options (variant_id, size_id, color_id, sleeve_type, quantity, price_surcharge, is_active)
            VALUES (?, ?, ?, ?, ?, ?, 1)
        ',
            )
            ->execute([
                (int) $data['variant_id'],
                (int) $data['size_id'],
                (int) $data['color_id'],
                $data['sleeve_type'] ?? Model::SLEEVE_SHORT,
                (int) ($data['quantity'] ?? 1),
                (float) ($data['price_surcharge'] ?? 0),
            ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateVariantOption(int $optionId, array $data): int
    {
        $stmt = $this->db->prepare('
            UPDATE variant_options
            SET size_id = ?, color_id = ?, sleeve_type = ?, quantity = ?, price_surcharge = ?
            WHERE option_id = ?
        ');
        $stmt->execute([
            (int) $data['size_id'],
            (int) $data['color_id'],
            $data['sleeve_type'] ?? Model::SLEEVE_SHORT,
            (int) ($data['quantity'] ?? 1),
            (float) ($data['price_surcharge'] ?? 0),
            $optionId,
        ]);
        return $stmt->rowCount();
    }

    public function softDeleteVariantOption(int $optionId): int
    {
        $stmt = $this->db->prepare(
            'UPDATE variant_options SET is_active = 0 WHERE option_id = ?',
        );
        $stmt->execute([$optionId]);
        return $stmt->rowCount();
    }

    public function restoreVariantOption(int $optionId): int
    {
        $stmt = $this->db->prepare(
            'UPDATE variant_options SET is_active = 1 WHERE option_id = ?',
        );
        $stmt->execute([$optionId]);
        return $stmt->rowCount();
    }

    public function createOption(
        int $variantId,
        int $sizeId,
        int $colorId,
        int $qty = 1,
        string $tipeLengan = 'short',
        float $priceSurcharge = 0,
    ): void {
        $this->db
            ->prepare(
                '
            INSERT INTO variant_options (variant_id, size_id, color_id, sleeve_type, quantity, price_surcharge, is_active)
            VALUES (?, ?, ?, ?, ?, ?, 1)
        ',
            )
            ->execute([
                $variantId,
                $sizeId,
                $colorId,
                $tipeLengan,
                $qty,
                $priceSurcharge,
            ]);
    }

    public function updateVariant(int $variantId, array $data): void
    {
        $this->db
            ->prepare(
                '
            UPDATE product_variants
            SET variant_name       = ?,
                material           = ?,
                price     = ?,
                sleeve_price = ?
            WHERE variant_id = ?
        ',
            )
            ->execute([
                $data['variant_name'],
                $data['material'] ?? null,
                $data['price'] ?? 0,
                $data['sleeve_price'] ?? 5000,
                $variantId,
            ]);
    }

    public function updateVariantWithOptions(int $variantId, array $data, array $options): void
    {
        $this->db->beginTransaction();
        try {
            $this->updateVariant($variantId, $data);
            $this->syncVariantOptions($variantId, $options);
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function deleteOptionsByVariant(int $variantId): void
    {
        $this->db
            ->prepare(
                'UPDATE variant_options SET is_active = 0 WHERE variant_id = ?',
            )
            ->execute([$variantId]);
    }

    public function syncVariantOptions(int $variantId, array $options): void
    {
        $stmt = $this->db->prepare('
            SELECT option_id, size_id, color_id, sleeve_type, is_active
            FROM variant_options
            WHERE variant_id = ?
        ');
        $stmt->execute([$variantId]);

        $existing = [];
        foreach ($stmt->fetchAll() as $row) {
            $key =
                $row['size_id'] .
                '|' .
                $row['color_id'] .
                '|' .
                $row['sleeve_type'];
            $existing[$key] = [
                'option_id' => (int) $row['option_id'],
                'is_active' => (int) $row['is_active'] === 1,
            ];
        }

        $updateStmt = $this->db->prepare('
            UPDATE variant_options
            SET quantity = ?, price_surcharge = ?, is_active = 1
            WHERE option_id = ?
        ');
        $insertStmt = $this->db->prepare('
            INSERT INTO variant_options (variant_id, size_id, color_id, sleeve_type, quantity, price_surcharge, is_active)
            VALUES (?, ?, ?, ?, ?, ?, 1)
        ');

        $seen = [];
        foreach ($options as $option) {
            $sizeId = (int) $option['size_id'];
            $colorId = (int) $option['color_id'];
            $sleeveType = $option['sleeve_type'] ?? Model::SLEEVE_SHORT;
            $quantity = (int) ($option['quantity'] ?? 1);
            if ($quantity < 0 || $quantity > 999999) {
                throw new \InvalidArgumentException(
                    'Quantity harus antara 0 dan 999.999.',
                );
            }
            $priceSurcharge = (float) ($option['price_surcharge'] ?? 0);
            $key = $sizeId . '|' . $colorId . '|' . $sleeveType;
            $seen[$key] = true;

            if (isset($existing[$key])) {
                $updateStmt->execute([
                    $quantity,
                    $priceSurcharge,
                    $existing[$key]['option_id'],
                ]);
            } else {
                $insertStmt->execute([
                    $variantId,
                    $sizeId,
                    $colorId,
                    $sleeveType,
                    $quantity,
                    $priceSurcharge,
                ]);
            }
        }

        $deleteStmt = $this->db->prepare(
            'UPDATE variant_options SET is_active = 0 WHERE option_id = ?',
        );
        foreach ($existing as $key => $option) {
            if ($option['is_active'] && !isset($seen[$key])) {
                $deleteStmt->execute([$option['option_id']]);
            }
        }
    }

    public function softDeleteVariant(int $variantId): void
    {
        $this->db
            ->prepare(
                '
            UPDATE product_variants SET is_active = 0 WHERE variant_id = ?
        ',
            )
            ->execute([$variantId]);
    }

    public function toggleVariant(int $variantId, bool $active): int
    {
        $stmt = $this->db->prepare(
            'UPDATE product_variants SET is_active = ? WHERE variant_id = ?',
        );
        $stmt->execute([$active ? 1 : 0, $variantId]);
        return $stmt->rowCount();
    }

    public function deleteOption(int $optionId): void
    {
        $this->db
            ->prepare(
                'UPDATE variant_options SET is_active = 0 WHERE option_id = ?',
            )
            ->execute([$optionId]);
    }

    public function toggleOption(int $optionId, bool $active): int
    {
        $stmt = $this->db->prepare(
            'UPDATE variant_options SET is_active = ? WHERE option_id = ?',
        );
        $stmt->execute([$active ? 1 : 0, $optionId]);
        return $stmt->rowCount();
    }

    public function updateOptionQty(array $updates): void
    {
        $stmt = $this->db->prepare(
            'UPDATE variant_options SET quantity = ? WHERE option_id = ?',
        );
        $this->db->beginTransaction();
        try {
            foreach ($updates as $u) {
                $qty = (int) $u['qty'];
                $optionId = (int) $u['option_id'];
                if ($optionId < 1 || $qty < 0 || $qty > 999999) {
                    throw new \InvalidArgumentException(
                        'Quantity harus antara 0 dan 999.999.',
                    );
                }
                $stmt->execute([$qty, $optionId]);
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function softDeleteVariantsBatch(array $variantIds): array
    {
        $succeeded = 0;
        $failed = 0;
        $details = [];
        $variantIds = array_values(array_unique(array_filter(array_map('intval', $variantIds), static fn (int $id): bool => $id > 0)));

        $updateStmt = $this->db->prepare(
            'UPDATE product_variants SET is_active = 0 WHERE variant_id = ? AND is_active = 1',
        );
        $existsStmt = $this->db->prepare(
            'SELECT is_active FROM product_variants WHERE variant_id = ? LIMIT 1',
        );

        $this->db->beginTransaction();
        try {
            foreach ($variantIds as $variantId) {
                try {
                    $updateStmt->execute([$variantId]);
                    if ($updateStmt->rowCount() > 0) {
                        $succeeded++;
                        $details[] = ['id' => $variantId, 'success' => true];
                        continue;
                    }

                    $existsStmt->execute([$variantId]);
                    $existing = $existsStmt->fetch();
                    if ($existing) {
                        $succeeded++;
                        $details[] = [
                            'id' => $variantId,
                            'success' => true,
                            'message' => 'Data sudah nonaktif',
                        ];
                        continue;
                    }

                    $failed++;
                    $details[] = [
                        'id' => $variantId,
                        'success' => false,
                        'message' => 'Data tidak ditemukan',
                    ];
                } catch (\Throwable $e) {
                    $failed++;
                    $details[] = [
                        'id' => $variantId,
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                }
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return [
            'succeeded' => $succeeded,
            'failed' => $failed,
            'details' => $details,
        ];
    }

    public function getSablonTypes(): array
    {
        return $this->db
            ->query(
                'SELECT * FROM sablon_types ORDER BY is_active DESC, sablon_name',
            )
            ->fetchAll();
    }

    public function sablonTypeAssociations(): array
    {
        $rows = $this->db
            ->query(
                '
                SELECT cst.sablon_type_id, cst.category_id, pc.category_name
                FROM category_sablon_types cst
                JOIN product_categories pc ON pc.category_id = cst.category_id
                WHERE cst.is_active = 1 AND pc.is_active = 1
                ORDER BY pc.category_name
            ',
            )
            ->fetchAll();
        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['sablon_type_id']][] = $row;
        }
        return $map;
    }

    public function toggleSablonType(int $sablonTypeId, bool $active): int
    {
        $stmt = $this->db->prepare(
            'UPDATE sablon_types SET is_active = ? WHERE sablon_type_id = ?',
        );
        $stmt->execute([$active ? 1 : 0, $sablonTypeId]);
        return $stmt->rowCount();
    }

    public function restoreSablonType(int $sablonTypeId): int
    {
        return $this->toggleSablonType($sablonTypeId, true);
    }

    public function addSablonCategory(int $sablonTypeId, int $categoryId): void
    {
        $stmt = $this->db->prepare('
            SELECT category_sablon_id
            FROM category_sablon_types
            WHERE sablon_type_id = ? AND category_id = ?
            LIMIT 1
        ');
        $stmt->execute([$sablonTypeId, $categoryId]);
        $existingId = $stmt->fetchColumn();

        if ($existingId) {
            $this->db
                ->prepare(
                    'UPDATE category_sablon_types SET is_active = 1 WHERE category_sablon_id = ?',
                )
                ->execute([(int) $existingId]);
            return;
        }

        $this->db
            ->prepare(
                'INSERT INTO category_sablon_types (category_id, sablon_type_id, is_active) VALUES (?, ?, 1)',
            )
            ->execute([$categoryId, $sablonTypeId]);
    }

    public function removeSablonCategory(
        int $sablonTypeId,
        int $categoryId,
    ): int {
        $stmt = $this->db->prepare('
            UPDATE category_sablon_types
            SET is_active = 0
            WHERE sablon_type_id = ? AND category_id = ?
        ');
        $stmt->execute([$sablonTypeId, $categoryId]);
        return $stmt->rowCount();
    }

    public function allSablonTypes(): array
    {
        return $this->db
            ->query(
                'SELECT * FROM sablon_types WHERE is_active = 1 ORDER BY sablon_name',
            )
            ->fetchAll();
    }

    public function sablonTypeCategoryMap(): array
    {
        $rows = $this->db
            ->query(
                'SELECT sablon_type_id, category_id FROM category_sablon_types WHERE is_active = 1',
            )
            ->fetchAll();
        $map = [];
        foreach ($rows as $r) {
            $map[(int) $r['sablon_type_id']][] = (int) $r['category_id'];
        }
        return $map;
    }

    public function syncSablonTypeCategories(
        int $sablonTypeId,
        array $categoryIds,
    ): void {
        $categoryIds = array_values(
            array_unique(array_map('intval', $categoryIds)),
        );

        $this->db->beginTransaction();
        try {
            $this->db
                ->prepare(
                    'UPDATE category_sablon_types SET is_active = 0 WHERE sablon_type_id = ?',
                )
                ->execute([$sablonTypeId]);

            foreach ($categoryIds as $cid) {
                if ($cid > 0) {
                    $this->addSablonCategory($sablonTypeId, $cid);
                }
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function createSablonType(array $data): int
    {
        $this->db
            ->prepare(
                'INSERT INTO sablon_types (sablon_name, notes) VALUES (?, ?)',
            )
            ->execute([$data['sablon_name'], $data['notes'] ?? null]);
        return (int) $this->db->lastInsertId();
    }

    public function updateSablonType(int $sablonTypeId, array $data): void
    {
        $this->db
            ->prepare(
                'UPDATE sablon_types SET sablon_name = ?, notes = ? WHERE sablon_type_id = ?',
            )
            ->execute([
                $data['sablon_name'],
                $data['notes'] ?? null,
                $sablonTypeId,
            ]);
    }

    public function softDeleteSablonType(int $sablonTypeId): int
    {
        $stmt = $this->db->prepare(
            'UPDATE sablon_types SET is_active = 0 WHERE sablon_type_id = ?',
        );
        $stmt->execute([$sablonTypeId]);
        return $stmt->rowCount();
    }

    public function deleteOptionsBatch(array $optionIds): array
    {
        $succeeded = 0;
        $failed = 0;
        $details = [];
        $optionIds = array_values(array_unique(array_filter(array_map('intval', $optionIds), static fn (int $id): bool => $id > 0)));

        $updateStmt = $this->db->prepare(
            'UPDATE variant_options SET is_active = 0 WHERE option_id = ? AND is_active = 1',
        );
        $existsStmt = $this->db->prepare(
            'SELECT is_active FROM variant_options WHERE option_id = ? LIMIT 1',
        );

        $this->db->beginTransaction();
        try {
            foreach ($optionIds as $optionId) {
                try {
                    $updateStmt->execute([$optionId]);
                    if ($updateStmt->rowCount() > 0) {
                        $succeeded++;
                        $details[] = ['id' => $optionId, 'success' => true];
                        continue;
                    }

                    $existsStmt->execute([$optionId]);
                    $existing = $existsStmt->fetch();
                    if ($existing) {
                        $succeeded++;
                        $details[] = [
                            'id' => $optionId,
                            'success' => true,
                            'message' => 'Data sudah nonaktif',
                        ];
                        continue;
                    }

                    $failed++;
                    $details[] = [
                        'id' => $optionId,
                        'success' => false,
                        'message' => 'Data tidak ditemukan',
                    ];
                } catch (\Throwable $e) {
                    $failed++;
                    $details[] = [
                        'id' => $optionId,
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                }
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return [
            'succeeded' => $succeeded,
            'failed' => $failed,
            'details' => $details,
        ];
    }

    public function findSizeByName(string $name): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM sizes WHERE size_name = ? AND is_active = 1 LIMIT 1',
        );
        $stmt->execute([$name]);
        return $stmt->fetch() ?: null;
    }

    public function findColorByName(string $name): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM colors WHERE color_name = ? AND is_active = 1 LIMIT 1',
        );
        $stmt->execute([$name]);
        return $stmt->fetch() ?: null;
    }

    public function createCategory(array $data): int
    {
        $this->db
            ->prepare(
                'INSERT INTO product_categories (category_name, is_active) VALUES (?, 1)',
            )
            ->execute([$data['category_name']]);
        return (int) $this->db->lastInsertId();
    }

    public function updateCategory(int $categoryId, array $data): void
    {
        $this->db
            ->prepare(
                'UPDATE product_categories SET category_name = ? WHERE category_id = ?',
            )
            ->execute([$data['category_name'], $categoryId]);
    }

    public function softDeleteCategory(int $categoryId): void
    {
        $this->db
            ->prepare(
                'UPDATE product_categories SET is_active = 0 WHERE category_id = ?',
            )
            ->execute([$categoryId]);
    }

    public function toggleCategory(int $categoryId, bool $active): int
    {
        $stmt = $this->db->prepare(
            'UPDATE product_categories SET is_active = ? WHERE category_id = ?',
        );
        $stmt->execute([$active ? 1 : 0, $categoryId]);
        return $stmt->rowCount();
    }

    public function allProducts(): array
    {
        return $this->db
            ->query(
                '
            SELECT p.*, pc.category_name
            FROM products p
            JOIN product_categories pc ON pc.category_id = p.category_id
            WHERE p.is_active = 1
            AND pc.is_active = 1
            ORDER BY pc.category_name, p.product_name
        ',
            )
            ->fetchAll();
    }

    public function allProductsForManagement(): array
    {
        return $this->db
            ->query(
                '
            SELECT p.*, pc.category_name, pc.is_active AS category_active
            FROM products p
            JOIN product_categories pc ON pc.category_id = p.category_id
            ORDER BY pc.category_name, p.is_active DESC, p.product_name
        ',
            )
            ->fetchAll();
    }

    public function productsByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('
            SELECT product_id, product_name, category_id
            FROM products
            WHERE category_id = ? AND is_active = 1
            ORDER BY product_name
        ');
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function createProduct(int $categoryId, array $data): int
    {
        $this->db
            ->prepare(
                'INSERT INTO products (category_id, product_name, minimum_order, is_active) VALUES (?, ?, ?, 1)',
            )
            ->execute([
                $categoryId,
                $data['product_name'],
                (int) ($data['minimum_order'] ?? 1),
            ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateProduct(int $productId, array $data): void
    {
        $this->db
            ->prepare(
                'UPDATE products SET product_name = ?, minimum_order = ? WHERE product_id = ?',
            )
            ->execute([
                $data['product_name'],
                (int) ($data['minimum_order'] ?? 1),
                $productId,
            ]);
    }

    public function softDeleteProduct(int $productId): void
    {
        $this->db
            ->prepare('UPDATE products SET is_active = 0 WHERE product_id = ?')
            ->execute([$productId]);
    }

    public function toggleProduct(int $productId, bool $active): int
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET is_active = ? WHERE product_id = ?',
        );
        $stmt->execute([$active ? 1 : 0, $productId]);
        return $stmt->rowCount();
    }

    public function createSize(string $sizeName): int
    {
        $this->db
            ->prepare('INSERT INTO sizes (size_name, is_active) VALUES (?, 1)')
            ->execute([$sizeName]);
        return (int) $this->db->lastInsertId();
    }

    public function updateSize(int $sizeId, string $sizeName): void
    {
        $this->db
            ->prepare('UPDATE sizes SET size_name = ? WHERE size_id = ?')
            ->execute([$sizeName, $sizeId]);
    }

    public function softDeleteSize(int $sizeId): void
    {
        $this->db
            ->prepare('UPDATE sizes SET is_active = 0 WHERE size_id = ?')
            ->execute([$sizeId]);
    }

    public function createColor(string $colorName): int
    {
        $this->db
            ->prepare(
                'INSERT INTO colors (color_name, is_active) VALUES (?, 1)',
            )
            ->execute([$colorName]);
        return (int) $this->db->lastInsertId();
    }

    public function updateColor(int $colorId, string $colorName): void
    {
        $this->db
            ->prepare('UPDATE colors SET color_name = ? WHERE color_id = ?')
            ->execute([$colorName, $colorId]);
    }

    public function softDeleteColor(int $colorId): void
    {
        $this->db
            ->prepare('UPDATE colors SET is_active = 0 WHERE color_id = ?')
            ->execute([$colorId]);
    }
}
