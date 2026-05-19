<?php

class TransactionController extends Controller
{
    private ?TransactionModel $transactions = null;

    // =========================
    // HALAMAN LIST TRANSACTION
    // =========================
    public function index(): void
    {
        $sidebarRole = $this->resolveSidebarRole();

        $this->view('transaction.index', [
            'sidebarRole' => $sidebarRole,
            'activeMenu'  => 'status',
            'transactions'=> $this->transactions()->all(),
        ]);
    }

    // =========================
    // HALAMAN CREATE ORDER
    // =========================
    public function create(): void
    {
        $this->view('transaction.create-order', [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'orders',

            // KIRIM DATA PRODUK KE VIEW
            'products'    => $this->transactions()->allProducts(),
        ]);
    }

    // =========================
    // PROSES CHECKOUT
    // =========================
    public function checkout(): void
    {
        // VALIDASI METHOD
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /transaction/create');
            exit;
        }

        // AMBIL DATA FORM
        $data = [
            'product_id' => $_POST['product_id'] ?? null,
            'qty'        => $_POST['qty'] ?? 0,
            'user_id'    => $_SESSION['user_id'] ?? 1,
        ];

        // SIMPAN ORDER
        $orderId = $this->transactions()->createOrder($data);

        // VALIDASI GAGAL
        if (!$orderId) {

            $_SESSION['error'] = 'Checkout gagal atau stok tidak cukup';
            header('Location: /transaction/create');
            exit;
        }

        // REDIRECT KE INVOICE
        header('Location: /transaction/invoice?id=' . $orderId);
        exit;
    }

     // =========================
     // PROSES INVOICE
     // =========================
    public function invoice(): void
{
    // =========================
    // AMBIL ORDER ID
    // =========================
    $orderId = $_GET['id'] ?? null;


    // =========================
    // JIKA BELUM ADA ORDER
    // PAKAI SESSION CART
    // =========================
    if (!$orderId) {

        $keranjang = $_SESSION['keranjang'] ?? [];
        $grandTotal = 0;
        foreach ($keranjang as $item) {
            $grandTotal += ($item['harga'] ?? 0);
        }

        $this->view('transaction.invoice', [

            'sidebarRole' => 'kasir',
            'activeMenu'  => 'invoice',

            // =========================
            // MODE SESSION
            // =========================
            'mode'        => 'session',
            'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(100,999),
            'customer_name'  => $_SESSION['customer_name'] ?? 'Walk In Customer',
            'customer_phone' => $_SESSION['customer_phone'] ?? '-',
            'items'       => $keranjang,
            'grand_total' => $grandTotal,
        ]);

        return;
    }


    // =========================
    // MODE DATABASE
    // =========================
    $order = $this->transactions()->findOrder($orderId);

    if (!$order) {
        header('Location: ' . url('/transactions'));
        exit;
    }


    // =========================
    // AMBIL ITEM ORDER
    // =========================
    $items = $this->transactions()->findOrderItems($orderId);


    // =========================
    // HITUNG TOTAL
    // =========================
    $grandTotal = 0;

    foreach ($items as $item) {
        $grandTotal += ($item['subtotal'] ?? 0);
    }


    // =========================
    // VIEW
    // =========================
    $this->view('transaction.invoice', [

        'sidebarRole' => 'kasir',
        'activeMenu'  => 'invoice',

        // =========================
        // MODE DATABASE
        // =========================
        'mode'        => 'database',
        'invoice_number' => $order['invoice_number'] ?? ('INV-' . $order['id']),
        'customer_name'  => $order['customer_name'] ?? 'Customer',
        'customer_phone' => $order['customer_phone'] ?? '-',
        'order'       => $order,
        'items'       => $items,
        'grand_total' => $grandTotal,
    ]);
}

    // =========================
    // HALAMAN CATEGORY
    // =========================
    public function categories(): void
    {
        $this->view('transaction.select-category', [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'orders',
        ]);
    }

    // =========================
    // HALAMAN CART
    // =========================
    public function cart(): void
    {
        $this->view('transaction.cart', [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'cart',
        ]);
    }

    // =========================
    // FORM PRODUCT
    // =========================
    public function tshirt(): void
    {
        $this->productForm('t-shirt');
    }

    public function pdh(): void
    {
        $this->productForm('work-uniform');
    }

    public function jersey(): void
    {
        $this->productForm('jersey');
    }

    public function poloshirt(): void
    {
        $this->productForm('polo-shirt');
    }

    public function seragamolahraga(): void
    {
        $this->productForm('sports-uniform');
    }

    public function jackethoodie(): void
    {
        $this->productForm('jacket-hoodie');
    }

    // =========================
    // PRIVATE PRODUCT FORM
    // =========================
    private function productForm(string $view): void
    {
        $this->view('transaction.form.' . $view, [
            'sidebarRole' => 'kasir',
            'activeMenu'  => 'orders',
        ]);
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
    // SIDEBAR ROLE
    // =========================
    private function resolveSidebarRole(): string
    {
        $sessionRole = (
            session_status() === PHP_SESSION_ACTIVE
            && isset($_SESSION['role'])
        )
            ? $_SESSION['role']
            : null;

        $role = $_GET['role'] ?? $sessionRole ?? 'kasir';

        return in_array($role, ['kasir', 'owner'], true)
            ? $role
            : 'kasir';
    }
}