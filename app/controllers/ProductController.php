<?php

class ProductController extends Controller
{
    /** @var ProductModel|null */
    private $products = null;

    // ──────────────────────────────────────────────────────────────
    // READ — halaman utama
    // ──────────────────────────────────────────────────────────────

    public function index(): void
    {
        $model = $this->products();

        $this->view('product.index', [
            'sidebarRole' => 'owner',
            'activeMenu'  => 'products',
            'categories'  => $model->allCategories(),
            'variants'    => $model->allVariants(),
            'options'     => $model->allOptions(),
            'sizes'       => $model->allSizes(),
            'colors'      => $model->allColors(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // CREATE — tambah varian baru
    // ──────────────────────────────────────────────────────────────

    public function store(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $model = $this->products();

            // Cari atau buat produk induk
            $productId = $model->findOrCreateProduct(
                (int) $data['category_id'],
                trim($data['nama_produk'])
            );

            // Buat varian
            $variantId = $model->createVariant($productId, $data);

            // Buat opsi ukuran × warna (dengan qty per warna)
            foreach ($data['options'] ?? [] as $opt) {
                $model->createOption(
                    $variantId,
                    (int) $opt['size_id'],
                    (int) $opt['color_id'],
                    (int) ($opt['qty'] ?? 1)
                );
            }

            echo json_encode(['success' => true, 'variant_id' => $variantId]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // UPDATE — edit varian
    // ──────────────────────────────────────────────────────────────

    public function update(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $model     = $this->products();
            $variantId = (int) $data['variant_id'];

            // Update info varian
            $model->updateVariant($variantId, $data);

            // Hapus opsi lama, insert ulang (dengan qty)
            $model->deleteOptionsByVariant($variantId);
            foreach ($data['options'] ?? [] as $opt) {
                $model->createOption(
                    $variantId,
                    (int) $opt['size_id'],
                    (int) $opt['color_id'],
                    (int) ($opt['qty'] ?? 1)
                );
            }

            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE — nonaktifkan varian (soft-delete)
    // ──────────────────────────────────────────────────────────────

    public function destroy(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $this->products()->softDeleteVariant((int) $data['variant_id']);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE OPTION — hapus satu baris ukuran × warna
    // ──────────────────────────────────────────────────────────────

    public function destroyOption(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $this->products()->deleteOption((int) $data['option_id']);
            echo json_encode(['success' => true]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // LAZY-LOAD MODEL — sama persis dengan pola TransactionController
    // ──────────────────────────────────────────────────────────────

    private function products(): ProductModel
    {
        if ($this->products === null) {
            $this->products = new ProductModel();
        }

        return $this->products;
    }
}