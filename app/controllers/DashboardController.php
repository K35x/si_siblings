<?php

class DashboardController extends Controller
{
    private ?DashboardModel $dashboard = null;

    public function kasir(): void
    {
        $stats = [
            [
                'label' => 'Pesanan Baru',
                'value' => $this->dashboard()->countOrdersByStatuses([
                    Model::ORDER_STATUS_PENDING_PAYMENT,
                    Model::ORDER_STATUS_CONFIRMED,
                ]),
            ],
            [
                'label' => 'Sedang Diproses',
                'value' => $this->dashboard()->countOrdersByStatuses([
                    Model::ORDER_STATUS_IN_PROGRESS,
                ]),
            ],
            [
                'label' => 'Siap Diambil',
                'value' => $this->dashboard()->countOrdersByStatuses([Model::ORDER_STATUS_READY]),
            ],
            [
                'label' => 'Omzet Hari Ini',
                'value' => format_currency($this->dashboard()->todayRevenue()),
            ],
        ];

        $queue = $this->dashboard()->activeOrderQueue();

        $this->view('layouts.cashier', [
            'sidebarRole' => Model::ROLE_KASIR,
            'activeMenu' => 'dashboard',
            'stats' => $stats,
            'queue' => $queue,
        ]);
    }

    public function owner(): void
    {
        $stats = [
            [
                'label' => 'Pesanan Baru',
                'value' => $this->dashboard()->countOrdersByStatuses([
                    Model::ORDER_STATUS_PENDING_PAYMENT,
                    Model::ORDER_STATUS_CONFIRMED,
                ]),
            ],
            [
                'label' => 'Sedang Diproses',
                'value' => $this->dashboard()->countOrdersByStatuses([
                    Model::ORDER_STATUS_IN_PROGRESS,
                ]),
            ],
            [
                'label' => 'Siap Diambil',
                'value' => $this->dashboard()->countOrdersByStatuses([Model::ORDER_STATUS_READY]),
            ],
            [
                'label' => 'Omzet Hari Ini',
                'value' => format_currency($this->dashboard()->todayRevenue()),
            ],
        ];

        $salesByCategory = $this->dashboard()->monthlyCategorySales();
        $salesPeriodTotal = array_sum(array_map(fn ($row) => (int) ($row['total'] ?? 0), $salesByCategory));
        $recentOrders = $this->dashboard()->recentOrders();

        $this->view('layouts.owner', [
            'sidebarRole' => Model::ROLE_OWNER,
            'activeMenu' => 'dashboard',
            'stats' => $stats,
            'salesByCategory' => $salesByCategory,
            'salesPeriodTotal' => $salesPeriodTotal,
            'recentOrders' => $recentOrders,
        ]);
    }

    private function dashboard(): DashboardModel
    {
        if ($this->dashboard === null) {
            $this->dashboard = new DashboardModel();
        }

        return $this->dashboard;
    }
}
