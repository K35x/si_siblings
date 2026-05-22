<?php

class TransactionController extends Controller
{
    private const ORDER_FORM_VIEWS = [
        't-shirt'        => 'transaction.form.t-shirt',
        'work-uniform'   => 'transaction.form.work-uniform',
        'jersey'         => 'transaction.form.jersey',
        'polo-shirt'     => 'transaction.form.polo-shirt',
        'sports-uniform' => 'transaction.form.sports-uniform',
        'jacket-hoodie'  => 'transaction.form.jacket-hoodie',
    ];

    private const HARGA_PER_PCS = 60000;

    private const KATEGORI_TO_FORM_ROUTE = [
        'T-Shirt / Kaos'    => '/transactions/form/tshirt',
        'PDH / Kemeja'      => '/transactions/form/pdh',
        'Jersey'            => '/transactions/form/jersey',
        'Polo Shirt'        => '/transactions/form/poloshirt',
        'Seragam Olahraga'  => '/transactions/form/seragamolahraga',
        'Jacket & Hoodie'   => '/transactions/form/jackethoodie',
    ];

    private ?TransactionModel $transactions = null;

    // =========================
    // HALAMAN LIST TRANSACTION
    // =========================
    public function index(): void
    {
        $sidebarRole = $this->resolveSidebarRole();

        $this->view('transaction.index', [
            'sidebarRole'  => $sidebarRole,
            'activeMenu'   => 'status',
            'transactions' => $this->transactions()->all(),
            'escape'       => fn ($value) => e($value),
        ]);
    }

    // =========================
    // HALAMAN CREATE ORDER (biodata)
    // =========================
    public function create(): void
    {
        $this->view('transaction.create-order', [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'orders',
            'products'    => $this->transactions()->allProducts(),
        ]);
    }

    // =========================
    // PROSES CHECKOUT
    // =========================
    public function checkout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('/transactions/create'));
            exit;
        }

        $this->ensureSession();

        $data = [
            'product_id' => $_POST['product_id'] ?? null,
            'qty'        => (int) ($_POST['qty'] ?? 0),
            'user_id'    => $_SESSION['user']['user_id'] ?? 1,
        ];

        $orderId = $this->transactions()->createOrder($data);

        if (!$orderId) {
            $_SESSION['error'] = 'Checkout gagal atau stok tidak cukup';
            header('Location: ' . url('/transactions/create'));
            exit;
        }

        header('Location: ' . url('/transactions/invoice?id=' . $orderId));
        exit;
    }

    // =========================
    // HALAMAN INVOICE
    // =========================
    public function invoice(): void
    {
        $this->ensureSession();
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            $keranjang  = $_SESSION['keranjang'] ?? [];
            $grandTotal = 0;
            foreach ($keranjang as $item) {
                $grandTotal += (float) ($item['harga'] ?? 0);
            }

            // Persist invoice number selama session berjalan
            if (empty($_SESSION['invoice_number'])) {
                $_SESSION['invoice_number'] = 'INV-' . date('Ymd') . '-' . str_pad((string) random_int(100, 999), 3, '0', STR_PAD_LEFT);
            }

            $this->view('transaction.invoice', [
                'sidebarRole'    => 'kasir',
                'activeMenu'     => 'invoice',
                'mode'           => 'session',
                'invoice_number' => $_SESSION['invoice_number'],
                'customer_name'  => $_SESSION['customer_name'] ?? 'Walk In Customer',
                'customer_phone' => $_SESSION['customer_phone'] ?? '-',
                'items'          => $keranjang,
                'grand_total'    => $grandTotal,
            ]);
            return;
        }

        $order = $this->transactions()->findOrder((string) $orderId);
        if (!$order) {
            header('Location: ' . url('/transactions'));
            exit;
        }

        $items      = $this->transactions()->findOrderItems((int) $order['order_id']);
        $grandTotal = 0;
        foreach ($items as $item) {
            $grandTotal += (float) ($item['subtotal'] ?? 0);
        }

        $this->view('transaction.invoice', [
            'sidebarRole'    => 'kasir',
            'activeMenu'     => 'invoice',
            'mode'           => 'database',
            'invoice_number' => $order['invoice_number'] ?? ('INV-' . $order['order_id']),
            'customer_name'  => $order['customer_name'] ?? 'Customer',
            'customer_phone' => $order['customer_phone'] ?? '-',
            'order'          => $order,
            'items'          => $items,
            'grand_total'    => $grandTotal,
        ]);
    }

    // =========================
    // HALAMAN CATEGORY
    // =========================
    public function categories(): void
    {
        $this->ensureSession();

        // Simpan biodata customer dari form create-order ke session bila ada
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['customer_name']  = trim($_POST['nama_customer'] ?? '');
            $_SESSION['customer_phone'] = trim($_POST['no_hp'] ?? '');
            $_SESSION['nama_project']   = trim($_POST['nama_project'] ?? '');
            $_SESSION['tgl_pemesanan']  = $_POST['tgl_pemesanan'] ?? null;
        }

        $this->view('transaction.select-category', [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'orders',
        ]);
    }

    // =========================
    // HALAMAN DETAIL PESANAN
    // =========================
    public function detail(): void
    {
        $this->ensureSession();
        $orderCode = $_GET['id'] ?? null;

        if (!$orderCode) {
            header('Location: ' . url('/transactions'));
            exit;
        }

        $order = $this->transactions()->findOrder($orderCode);

        if (!$order) {
            http_response_code(404);
            echo 'Pesanan tidak ditemukan.';
            return;
        }

        $items = $this->transactions()->findOrderItems((int) ($order['order_id'] ?? 0));
        $sidebarRole = $this->resolveSidebarRole();

        $this->view('transaction.detail-pesanan', [
            'sidebarRole'    => $sidebarRole,
            'activeMenu'     => 'status',
            'id_pesanan'     => $order['order_code'],
            'kategori'       => $items[0]['kategori'] ?? '-',
            'nama_pelanggan' => $order['nama'] ?? '-',
            'telepon'        => $order['no_hp'] ?? '-',
            'tanggal'        => format_date_id($order['tanggal_order'] ?? null, true),
            'order'          => $order,
            'items'          => $items,
            'user_role'      => $sidebarRole,
        ]);
    }

    // =========================
    // HALAMAN CART
    // =========================
    public function cart(): void
    {
        $this->ensureSession();

        // GET hapus item
        if (isset($_GET['hapus'])) {
            $id = (int) $_GET['hapus'];
            if (isset($_SESSION['keranjang'][$id])) {
                unset($_SESSION['keranjang'][$id]);
                $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
            }
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        // GET edit item -> redirect ke form sesuai kategori
        if (isset($_GET['edit'])) {
            $id = (int) $_GET['edit'];
            if (isset($_SESSION['keranjang'][$id])) {
                $item = $_SESSION['keranjang'][$id];
                $_SESSION['edit_item']  = $item;
                $_SESSION['edit_index'] = $id;

                $route = self::KATEGORI_TO_FORM_ROUTE[$item['kategori']] ?? '/transactions/categories';
                header('Location: ' . url($route));
                exit;
            }
        }

        // POST simpan/update item
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveCartItem();
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        $this->view('transaction.cart', [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'cart',
        ]);
    }

    // =========================
    // FORM PRODUCT
    // =========================
    public function tshirt(): void          { $this->productForm('t-shirt'); }
    public function pdh(): void             { $this->productForm('work-uniform'); }
    public function jersey(): void          { $this->productForm('jersey'); }
    public function poloshirt(): void       { $this->productForm('polo-shirt'); }
    public function seragamolahraga(): void { $this->productForm('sports-uniform'); }
    public function jackethoodie(): void    { $this->productForm('jacket-hoodie'); }

    // =========================
    // PRIVATE PRODUCT FORM
    // =========================
    private function productForm(string $formSource): void
    {
        $this->ensureSession();

        $view = self::ORDER_FORM_VIEWS[$formSource] ?? null;
        if ($view === null) {
            header('Location: ' . url('/transactions/categories'));
            exit;
        }

        $productModel = new ProductModel();

        $this->view($view, [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'orders',
            'formSource'  => $formSource,
            'colors'      => $productModel->allColors(),
            'editIndex'   => $_SESSION['edit_index'] ?? null,
            'editItem'    => $_SESSION['edit_item'] ?? null,
        ]);
    }

    // =========================
    // CART HELPERS
    // =========================
    private function saveCartItem(): void
    {
        $kategori = $_POST['kategori'] ?? '';
        $availableSizes = ['S', 'M', 'L', 'XL', 'XXL'];

        // Parse qty
        if ($kategori === 'PDH / Kemeja') {
            $sizes = [
                'S'   => (int) ($_POST['qty_std_S'] ?? 0)   + (int) ($_POST['qty_cpt_S'] ?? 0),
                'M'   => (int) ($_POST['qty_std_M'] ?? 0)   + (int) ($_POST['qty_cpt_M'] ?? 0),
                'L'   => (int) ($_POST['qty_std_L'] ?? 0)   + (int) ($_POST['qty_cpt_L'] ?? 0),
                'XL'  => (int) ($_POST['qty_std_XL'] ?? 0)  + (int) ($_POST['qty_cpt_XL'] ?? 0),
                'XXL' => (int) ($_POST['qty_std_XXL'] ?? 0) + (int) ($_POST['qty_cpt_XXL'] ?? 0),
            ];
        } else {
            $sizes = [];
            foreach ($availableSizes as $sz) {
                $sizes[$sz] = (int) ($_POST['qty_short_' . $sz] ?? 0) + (int) ($_POST['qty_long_' . $sz] ?? 0);
            }
        }

        // Parse warna per size
        $warnaPerSize = [
            'short' => [],
            'long'  => [],
        ];
        foreach ($availableSizes as $sz) {
            $warnaPerSize['short'][$sz] = trim($_POST['warna_short_' . $sz] ?? '');
            $warnaPerSize['long'][$sz]  = trim($_POST['warna_long_' . $sz] ?? '');
        }

        // Generate ringkasan warna untuk display
        $warnaShortSummary = $this->summarizeColors($warnaPerSize['short']);
        $warnaLongSummary  = $this->summarizeColors($warnaPerSize['long']);

        $totalQty = array_sum($sizes);

        // Validate required fields
        if ($kategori === '' || $totalQty < 1) {
            $_SESSION['validation_errors'] = [];
            if ($kategori === '') {
                $_SESSION['validation_errors'][] = 'Kategori wajib diisi.';
            }
            if ($totalQty < 1) {
                $_SESSION['validation_errors'][] = 'Minimal 1 ukuran harus diisi.';
            }
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        $hargaPerPcs = (int) ($_POST['paket_bahan'] ?? self::HARGA_PER_PCS);
        $totalHarga = $totalQty * $hargaPerPcs;

        $itemBaru = [
            'kategori'       => $kategori ?: '-',
            'bahan'          => trim($_POST['jenis_bahan'] ?? '-'),
            'warna'          => $warnaShortSummary ?: '-',
            'warna_per_size' => $warnaPerSize,
            'warna_summary'  => [
                'short' => $warnaShortSummary,
                'long'  => $warnaLongSummary,
            ],
            'sablon'   => trim($_POST['jenis_sablon'] ?? '-'),
            'rincian'  => $sizes,
            'qty'      => $totalQty,
            'harga'    => $totalHarga,
        ];

        if (isset($_POST['index_edit']) && $_POST['index_edit'] !== '') {
            $idx = (int) $_POST['index_edit'];
            if (!isset($_SESSION['keranjang'][$idx])) {
                $_SESSION['validation_errors'] = ['Item yang ingin diubah tidak ditemukan.'];
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . url('/transactions/cart'));
                exit;
            }
            $_SESSION['keranjang'][$idx] = $itemBaru;
            unset($_SESSION['edit_item'], $_SESSION['edit_index']);
        } else {
            $_SESSION['keranjang'][] = $itemBaru;
        }

        // Reset invoice number agar regenerate untuk sesi pesanan baru
        unset($_SESSION['invoice_number']);
    }

    /**
     * Ringkas warna per size: "Merah (S,M,L), Biru (XL,XXL)"
     */
    private function summarizeColors(array $sizeColors): string
    {
        $grouped = [];
        foreach ($sizeColors as $size => $color) {
            if ($color !== '') {
                $grouped[$color][] = $size;
            }
        }

        if (empty($grouped)) {
            return '';
        }

        $parts = [];
        foreach ($grouped as $color => $sizes) {
            $parts[] = $color . ' (' . implode(',', $sizes) . ')';
        }

        return implode(', ', $parts);
    }

    // =========================
    // LOAD MODEL
    // =========================
    private function transactions(): TransactionModel
    {
        if ($this->transactions === null) {
            $this->transactions = new TransactionModel();
        }

        return $this->transactions;
    }

    // =========================
    // SESSION HELPER
    // =========================
    private function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    // =========================
    // SIDEBAR ROLE
    // =========================
    private function resolveSidebarRole(): string
    {
        $sessionRole = (
            session_status() === PHP_SESSION_ACTIVE
            && isset($_SESSION['user']['role'])
        )
            ? $_SESSION['user']['role']
            : null;

        $role = $_GET['role'] ?? $sessionRole ?? 'kasir';

        return in_array($role, ['kasir', 'owner'], true) ? $role : 'kasir';
    }
}
