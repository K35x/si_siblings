<?php

class PenjualanController extends Controller
{
    private ?PenjualanModel $finance = null;

    public function index(): void
    {
        $from = trim($_GET['from'] ?? '');
        $to = trim($_GET['to'] ?? '');
        $category = trim($_GET['category'] ?? '');

        $model = $this->finance();

        $this->view('finance.index', [
            'sidebarRole' => Model::ROLE_OWNER,
            'activeMenu' => 'finance',
            'paymentStates' => $model->paymentStates($from ?: null, $to ?: null, null, $category ?: null),
            'reportSummary' => $model->salesReportSummary($from ?: null, $to ?: null, $category ?: null),
            'filterFrom' => $from,
            'filterTo' => $to,
            'filterCategory' => $category,
            'categories' => $model->allCategories(),
        ]);
    }

    private function finance(): PenjualanModel
    {
        if ($this->finance === null) {
            $this->finance = new PenjualanModel();
        }

        return $this->finance;
    }
}
