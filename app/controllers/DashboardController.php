<?php

class DashboardController extends Controller
{
    public function kasir(): void
    {
        $this->view('layouts.cashier', [
            'sidebarRole' => 'kasir',
            'activeMenu' => 'dashboard',
        ]);
    }

    public function owner(): void
    {
        $this->view('layouts.owner', [
            'sidebarRole' => 'owner',
            'activeMenu' => 'dashboard',
        ]);
    }
}
