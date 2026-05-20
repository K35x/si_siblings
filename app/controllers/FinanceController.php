<?php

class FinanceController extends Controller
{
    private ?FinanceModel $finance = null;
    private ?TransactionModel $transactions = null;

    public function index(): void
    {
        $financialErrors = [];
        $financialNotice = null;
        $oldInput = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldInput = $_POST;
            $action = $_POST['finance_action'] ?? 'validate-payment';

            if ($action === 'validate-payment') {
                $validation = $this->finance()->validatePaymentInput($_POST);
                $financialErrors = $validation['errors'];
                $financialNotice = $validation['valid'] ? 'Data pembayaran valid.' : null;
            } elseif ($action === 'delete-order') {
                $result = $this->transactions()->deleteOrderSafely((int) ($_POST['order_id'] ?? 0));
                $financialErrors = $result['success'] ? [] : ['action' => $result['message']];
                $financialNotice = $result['success'] ? $result['message'] : null;
            } elseif ($action === 'delete-payment') {
                $result = $this->transactions()->deletePaymentSafely((int) ($_POST['payment_id'] ?? 0));
                $financialErrors = $result['success'] ? [] : ['action' => $result['message']];
                $financialNotice = $result['success'] ? $result['message'] : null;
            } elseif ($action === 'update-payment') {
                $result = $this->transactions()->updatePaymentSafely((int) ($_POST['payment_id'] ?? 0), $_POST);
                $financialErrors = $result['success'] ? [] : ['action' => $result['message']];
                $financialNotice = $result['success'] ? $result['message'] : null;
            } else {
                $financialErrors = ['action' => 'Aksi keuangan tidak valid.'];
            }
        }

        $this->view('finance.index', [
            'sidebarRole' => 'owner',
            'activeMenu' => 'finance',
            'paymentStates' => $this->finance()->paymentStates(),
            'financialErrors' => $financialErrors,
            'financialNotice' => $financialNotice,
            'oldInput' => $oldInput,
        ]);
    }

    private function finance(): FinanceModel
    {
        if ($this->finance === null) {
            $this->finance = new FinanceModel();
        }

        return $this->finance;
    }

    private function transactions(): TransactionModel
    {
        if ($this->transactions === null) {
            $this->transactions = new TransactionModel();
        }

        return $this->transactions;
    }
}
