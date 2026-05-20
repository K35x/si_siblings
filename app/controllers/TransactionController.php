<?php

class TransactionController extends Controller
{
    private const ORDER_FORM_VIEWS = [
        't-shirt' => 'transaction.form.t-shirt',
        'work-uniform' => 'transaction.form.work-uniform',
        'jersey' => 'transaction.form.jersey',
        'polo-shirt' => 'transaction.form.polo-shirt',
        'sports-uniform' => 'transaction.form.sports-uniform',
        'jacket-hoodie' => 'transaction.form.jacket-hoodie',
    ];

    private ?TransactionModel $transactions = null;

    public function index(): void
    {
        $sidebarRole = $this->resolveSidebarRole();

        $this->view('transaction.index', [
            'sidebarRole' => $sidebarRole,
            'activeMenu' => 'status',
            'transactions' => $this->transactions()->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('transaction.create-order', [
            'sidebarRole' => 'kasir',
            'activeMenu' => 'orders',
        ]);
    }

    public function categories(): void
    {
        $this->view('transaction.select-category', [
            'sidebarRole' => 'kasir',
            'activeMenu' => 'orders',
        ]);
    }

    public function cart(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_GET['hapus'])) {
            $id = (int) $_GET['hapus'];
            if (isset($_SESSION['keranjang'][$id])) {
                unset($_SESSION['keranjang'][$id]);
                $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
            }

            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validation = $this->transactions()->validateOrderPayloadBeforeSave($_POST);

            if (!$validation['valid']) {
                $formView = $this->resolveOrderFormView($_POST['form_source'] ?? null);
                $this->view($formView, [
                    'sidebarRole' => 'kasir',
                    'activeMenu' => 'orders',
                    'validationErrors' => $validation['errors'],
                    'oldInput' => $validation['oldInput'],
                ]);
                return;
            }

            $item = $this->transactions()->buildCartItemFromPayload($_POST, $validation);

            if (isset($_POST['index_edit']) && $_POST['index_edit'] !== '') {
                $_SESSION['keranjang'][(int) $_POST['index_edit']] = $item;
            } else {
                $_SESSION['keranjang'][] = $item;
            }

            header('Location: ' . url('/transactions/cart'));
            exit;
        }

        $this->view('transaction.cart', [
            'sidebarRole' => 'kasir',
            'activeMenu' => 'cart',
        ]);
    }

    public function invoice(): void
    {
        $this->view('transaction.invoice', [
            'sidebarRole' => 'kasir',
            'activeMenu' => 'invoice',
        ]);
    }

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

    private function productForm(string $formSource): void
    {
        $this->view(self::ORDER_FORM_VIEWS[$formSource], [
            'sidebarRole' => 'kasir',
            'activeMenu' => 'orders',
            'formSource' => $formSource,
        ]);
    }

    private function resolveOrderFormView(?string $formSource): string
    {
        return self::ORDER_FORM_VIEWS[$formSource ?? ''] ?? 'transaction.cart';
    }

    private function transactions(): TransactionModel
    {
        if ($this->transactions === null) {
            $this->transactions = new TransactionModel();
        }

        return $this->transactions;
    }

    private function resolveSidebarRole(): string
    {
        $sessionRole = (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user']['role']))
            ? $_SESSION['user']['role']
            : null;

        return resolve_sidebar_role($_GET['role'] ?? null, $sessionRole);
    }
}
