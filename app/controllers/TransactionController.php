<?php

class TransactionController extends Controller
{
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

    private function productForm(string $view): void
    {
        $this->view('transaction.form.' . $view, [
            'sidebarRole' => 'kasir',
            'activeMenu' => 'orders',
        ]);
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
        $sessionRole = (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['role'])) ? $_SESSION['role'] : null;
        $role = $_GET['role'] ?? $sessionRole ?? 'kasir';

        return in_array($role, ['kasir', 'owner'], true) ? $role : 'kasir';
    }
}
