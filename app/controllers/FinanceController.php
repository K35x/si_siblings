<?php

class FinanceController extends Controller
{
    public function index(): void
    {
        $this->view('finance.index', [
            'sidebarRole' => 'owner',
            'activeMenu' => 'finance',
        ]);
    }
}
