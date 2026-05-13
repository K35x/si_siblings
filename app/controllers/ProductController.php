<?php

class ProductController extends Controller
{
    public function index(): void
    {
        $this->view('product.index', [
            'sidebarRole' => 'owner',
            'activeMenu' => 'products',
        ]);
    }
}
