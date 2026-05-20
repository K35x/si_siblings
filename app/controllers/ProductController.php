<?php

class ProductController extends Controller
{
    private ?ProductModel $products = null;

    public function index(): void
    {
        $this->view('product.index', [
            'sidebarRole' => 'owner',
            'activeMenu' => 'products',
            'stocks' => $this->products()->stockList(),
            'stockSummary' => $this->products()->stockSummary(),
            'stockCategories' => $this->products()->categoriesWithStock(),
        ]);
    }

    private function products(): ProductModel
    {
        if ($this->products === null) {
            $this->products = new ProductModel();
        }

        return $this->products;
    }
}
