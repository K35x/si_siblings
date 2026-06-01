<?php

class ProductController extends Controller
{
    private ?ProductModel $products = null;

    private function jsonInput(): ?array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Payload JSON tidak valid']);
            return null;
        }

        return $data;
    }

    public function index(): void
    {
        $model = $this->products();

        $this->view('product.index', [
            'sidebarRole' => Model::ROLE_OWNER,
            'activeMenu' => 'products',
            'categories' => $model->allCategories(),
            'products' => $model->allProducts(),
            'managementCategories' => $model->allCategoriesForManagement(),
            'managementProducts' => $model->allProductsForManagement(),
            'variants' => $model->allVariants(),
            'options' => $model->allOptions(),
            'sizes' => $model->allSizes(),
            'colors' => $model->allColors(),
            'allSablonTypes' => $model->allSablonTypes(),
            'sablonTypeCategories' => $model->sablonTypeCategoryMap(),
        ]);
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }

        try {
            $model = $this->products();

            $productId = $model->findOrCreateProduct(
                (int) $data['category_id'],
                trim($data['product_name']),
                (int) ($data['minimum_order'] ?? 24)
            );

            $variantId = $model->createVariant($productId, $data);

            foreach ($this->normalizeVariantOptionSurcharges($data['options'] ?? []) as $opt) {
                $model->createOption(
                    $variantId,
                    (int) $opt['size_id'],
                    (int) $opt['color_id'],
                    (int) ($opt['quantity'] ?? 1),
                    $opt['sleeve_type'] ?? Model::SLEEVE_SHORT,
                    (float) ($opt['price_surcharge'] ?? 0)
                );
            }

            echo json_encode(['success' => true, 'variant_id' => $variantId]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[ProductController] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function update(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }

        try {
            $this->products()->updateVariantWithOptions(
                (int) $data['variant_id'],
                $data,
                $this->normalizeVariantOptionSurcharges($data['options'] ?? [])
            );

            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[ProductController] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function destroy(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }

        try {
            $this->products()->softDeleteVariant((int) $data['variant_id']);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[ProductController] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function toggleActive(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $type = $data['type'] ?? '';
        $id = (int) ($data['id'] ?? 0);
        $active = (bool) ($data['active'] ?? true);

        if (!$id || !in_array($type, ['category', 'product', 'variant'], true)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
            return;
        }

        try {
            $model = $this->products();
            $rows = match ($type) {
                'category' => $model->toggleCategory($id, $active),
                'product' => $model->toggleProduct($id, $active),
                'variant' => $model->toggleVariant($id, $active),
            };
            if ($rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
                return;
            }
            echo json_encode(['success' => true]);
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('[toggleActive] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function destroyOption(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }

        try {
            $this->products()->deleteOption((int) $data['option_id']);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[ProductController] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function toggleOptionStatus(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $optionId = (int) ($data['option_id'] ?? 0);
        $active = (bool) ($data['active'] ?? true);

        if (!$optionId) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
            return;
        }

        try {
            $rows = $this->products()->toggleOption($optionId, $active);
            if ($rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
                return;
            }
            echo json_encode(['success' => true]);
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('[toggleOptionStatus] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function updateQuantity(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $updates = $data['updates'] ?? [];

        if (empty($updates)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada data.']);
            return;
        }

        try {
            $this->products()->updateOptionQty($updates);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[ProductController] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function destroyBatch(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }

        try {
            $model = $this->products();
            $type = $data['type'] ?? 'variants';
            $ids = array_map('intval', $data['ids'] ?? []);

            if (empty($ids)) {
                echo json_encode(['success' => false, 'message' => 'Tidak ada item yang dipilih.']);
                return;
            }

            $result = $type === 'options'
                ? $model->deleteOptionsBatch($ids)
                : $model->softDeleteVariantsBatch($ids);

            $success = $result['failed'] === 0;
            echo json_encode([
                'success' => $success,
                'succeeded' => $result['succeeded'],
                'failed' => $result['failed'],
                'message' => $success
                    ? "Berhasil menghapus {$result['succeeded']} item."
                    : "{$result['succeeded']} berhasil, {$result['failed']} gagal.",
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[ProductController] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function variantOptions(): void
    {
        $variantId = (int) ($_GET['variant_id'] ?? 0);
        $model = $this->products();
        $variant = $model->findVariant($variantId);

        if (!$variant) {
            http_response_code(404);
            echo 'Varian tidak ditemukan.';
            return;
        }

        $this->view('product.variant-options', [
            'sidebarRole' => Model::ROLE_OWNER,
            'activeMenu' => 'products',
            'variant' => $variant,
            'options' => $model->getVariantOptions($variantId),
            'sizes' => $model->allSizes(),
            'colors' => $model->allColors(),
        ]);
    }

    public function storeVariantOption(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }

        if (empty($data['variant_id']) || empty($data['size_id']) || empty($data['color_id'])) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }

        if (!$this->validVariantOptionPayload($data)) {
            echo json_encode(['success' => false, 'message' => 'Quantity harus minimal 1 dan surcharge tidak boleh negatif.']);
            return;
        }

        try {
            $optionId = $this->products()->createVariantOption($data);
            echo json_encode(['success' => true, 'option_id' => $optionId]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[storeVariantOption] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function updateVariantOption(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $optionId = (int) ($data['option_id'] ?? 0);

        if (!$optionId || empty($data['size_id']) || empty($data['color_id'])) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }

        if (!$this->validVariantOptionPayload($data)) {
            echo json_encode(['success' => false, 'message' => 'Quantity harus minimal 1 dan surcharge tidak boleh negatif.']);
            return;
        }

        try {
            $this->products()->updateVariantOption($optionId, $data);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[updateVariantOption] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function toggleVariantOptionStatus(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $optionId = (int) ($data['option_id'] ?? 0);
        $active = (bool) ($data['active'] ?? true);

        if (!$optionId) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
            return;
        }

        try {
            $rows = $active
                ? $this->products()->restoreVariantOption($optionId)
                : $this->products()->softDeleteVariantOption($optionId);
            if ($rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
                return;
            }
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[toggleVariantOptionStatus] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    private function normalizeVariantOptionSurcharges(array $options): array
    {
        $surchargesBySize = [];
        foreach ($options as $option) {
            $sizeId = (int) ($option['size_id'] ?? 0);
            if ($sizeId <= 0) {
                continue;
            }
            $surcharge = (float) ($option['price_surcharge'] ?? 0);
            if ($surcharge > ($surchargesBySize[$sizeId] ?? 0)) {
                $surchargesBySize[$sizeId] = $surcharge;
            }
        }

        foreach ($options as &$option) {
            $sizeId = (int) ($option['size_id'] ?? 0);
            $option['price_surcharge'] = $surchargesBySize[$sizeId] ?? 0;
        }
        unset($option);

        return $options;
    }

    private function validVariantOptionPayload(array $data): bool
    {
        $quantity = $data['quantity'] ?? null;
        $surcharge = $data['price_surcharge'] ?? 0;

        return filter_var($quantity, FILTER_VALIDATE_INT) !== false
            && (int) $quantity >= 1
            && is_numeric($surcharge)
            && (float) $surcharge >= 0;
    }

    public function manageCategory(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $action = $data['action'] ?? '';

        try {
            if ($action === 'create') {
                if (empty($data['category_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Nama kategori wajib diisi.']);
                    return;
                }
                $id = $this->products()->createCategory($data);
                echo json_encode(['success' => true, 'category_id' => $id]);
            } elseif ($action === 'update') {
                if (empty($data['category_id']) || empty($data['category_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
                    return;
                }
                $this->products()->updateCategory((int) $data['category_id'], $data);
                echo json_encode(['success' => true]);
            } elseif ($action === 'delete') {
                $this->products()->softDeleteCategory((int) $data['category_id']);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('[manageCategory] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function manageProduct(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $action = $data['action'] ?? '';

        try {
            if ($action === 'create') {
                if (empty($data['product_name']) || empty($data['category_id'])) {
                    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
                    return;
                }

                $id = $this->products()->createProduct((int) $data['category_id'], $data);
                echo json_encode(['success' => true, 'product_id' => $id]);
            } elseif ($action === 'update') {
                if (empty($data['product_id']) || empty($data['product_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
                    return;
                }
                $this->products()->updateProduct((int) $data['product_id'], $data);
                echo json_encode(['success' => true]);
            } elseif ($action === 'delete') {
                $this->products()->softDeleteProduct((int) $data['product_id']);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('[manageProduct] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function byCategory(): void
    {
        header('Content-Type: application/json');

        $categoryId = (int) ($_GET['category_id'] ?? 0);
        if (!$categoryId) {
            echo json_encode(['products' => []]);
            return;
        }

        $products = $this->products()->productsByCategory($categoryId);
        echo json_encode(['products' => $products]);
    }

    public function manageSize(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $action = $data['action'] ?? '';

        try {
            if ($action === 'create') {
                if (empty($data['size_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Nama ukuran wajib diisi.']);
                    return;
                }

                $existing = $this->products()->findSizeByName($data['size_name']);
                if ($existing) {
                    echo json_encode(['success' => false, 'message' => 'Ukuran "' . $data['size_name'] . '" sudah ada.']);
                    return;
                }
                $id = $this->products()->createSize($data['size_name']);
                echo json_encode(['success' => true, 'size_id' => $id]);
            } elseif ($action === 'update') {
                if (empty($data['size_id']) || empty($data['size_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
                    return;
                }
                $this->products()->updateSize((int) $data['size_id'], $data['size_name']);
                echo json_encode(['success' => true]);
            } elseif ($action === 'delete') {
                $this->products()->softDeleteSize((int) $data['size_id']);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('[manageSize] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function manageColor(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $action = $data['action'] ?? '';

        try {
            if ($action === 'create') {
                if (empty($data['color_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Nama warna wajib diisi.']);
                    return;
                }

                $existing = $this->products()->findColorByName($data['color_name']);
                if ($existing) {
                    echo json_encode(['success' => false, 'message' => 'Warna "' . $data['color_name'] . '" sudah ada.']);
                    return;
                }
                $id = $this->products()->createColor($data['color_name']);
                echo json_encode(['success' => true, 'color_id' => $id]);
            } elseif ($action === 'update') {
                if (empty($data['color_id']) || empty($data['color_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
                    return;
                }
                $this->products()->updateColor((int) $data['color_id'], $data['color_name']);
                echo json_encode(['success' => true]);
            } elseif ($action === 'delete') {
                $this->products()->softDeleteColor((int) $data['color_id']);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('[manageColor] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function sablonTypes(): void
    {
        $model = $this->products();

        $this->view('product.sablon-types', [
            'sidebarRole' => Model::ROLE_OWNER,
            'activeMenu' => 'products',
            'sablonTypes' => $model->getSablonTypes(),
            'categories' => $model->allCategories(),
            'associations' => $model->sablonTypeAssociations(),
        ]);
    }

    public function storeSablonType(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }

        if (empty($data['sablon_name'])) {
            echo json_encode(['success' => false, 'message' => 'Nama sablon wajib diisi.']);
            return;
        }

        try {
            $id = $this->products()->createSablonType($data);
            $this->products()->syncSablonTypeCategories($id, $data['category_ids'] ?? []);
            echo json_encode(['success' => true, 'sablon_type_id' => $id]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[storeSablonType] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function updateSablonType(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $sablonTypeId = (int) ($data['sablon_type_id'] ?? 0);

        if (!$sablonTypeId || empty($data['sablon_name'])) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }

        try {
            $this->products()->updateSablonType($sablonTypeId, $data);
            $this->products()->syncSablonTypeCategories($sablonTypeId, $data['category_ids'] ?? []);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[updateSablonType] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function toggleSablonTypeStatus(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $sablonTypeId = (int) ($data['sablon_type_id'] ?? 0);
        $active = (bool) ($data['active'] ?? true);

        if (!$sablonTypeId) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
            return;
        }

        try {
            $rows = $active
                ? $this->products()->restoreSablonType($sablonTypeId)
                : $this->products()->softDeleteSablonType($sablonTypeId);
            if ($rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
                return;
            }
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[toggleSablonTypeStatus] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function addSablonCategory(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $sablonTypeId = (int) ($data['sablon_type_id'] ?? 0);
        $categoryId = (int) ($data['category_id'] ?? 0);

        if (!$sablonTypeId || !$categoryId) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }

        try {
            $this->products()->addSablonCategory($sablonTypeId, $categoryId);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[addSablonCategory] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function removeSablonCategory(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $sablonTypeId = (int) ($data['sablon_type_id'] ?? 0);
        $categoryId = (int) ($data['category_id'] ?? 0);

        if (!$sablonTypeId || !$categoryId) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            return;
        }

        try {
            $this->products()->removeSablonCategory($sablonTypeId, $categoryId);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('[removeSablonCategory] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function manageSablonType(): void
    {
        header('Content-Type: application/json');

        if (!csrf_verify_unified()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token tidak valid']);
            return;
        }

        $data = $this->jsonInput();
        if ($data === null) {
            return;
        }
        $action = $data['action'] ?? '';

        try {
            if ($action === 'create') {
                if (empty($data['sablon_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Nama sablon wajib diisi.']);
                    return;
                }
                $id = $this->products()->createSablonType($data);
                $this->products()->syncSablonTypeCategories($id, $data['category_ids'] ?? []);
                echo json_encode(['success' => true, 'sablon_type_id' => $id]);
            } elseif ($action === 'update') {
                if (empty($data['sablon_type_id']) || empty($data['sablon_name'])) {
                    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
                    return;
                }
                $this->products()->updateSablonType((int) $data['sablon_type_id'], $data);
                $this->products()->syncSablonTypeCategories((int) $data['sablon_type_id'], $data['category_ids'] ?? []);
                echo json_encode(['success' => true]);
            } elseif ($action === 'delete') {
                $this->products()->softDeleteSablonType((int) $data['sablon_type_id']);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('[manageSablonType] ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    private function products(): ProductModel
    {
        if ($this->products === null) {
            $this->products = new ProductModel();
        }

        return $this->products;
    }
}
