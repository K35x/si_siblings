<?php

return [
    '/'                               => [AuthController::class,        'login'],
    '/login'                          => [AuthController::class,        'login'],
    '/kasir'                          => [DashboardController::class,   'kasir'],
    '/owner'                          => [DashboardController::class,   'owner'],
    '/transactions'                   => [TransactionController::class, 'index'],
    '/transactions/create'            => [TransactionController::class, 'create'],
    '/transactions/categories'        => [TransactionController::class, 'categories'],
    '/transactions/cart'              => [TransactionController::class, 'cart'],
    '/transactions/invoice'           => [TransactionController::class, 'invoice'],
    '/transactions/form/tshirt'       => [TransactionController::class, 'tshirt'],
    '/transactions/form/pdh'          => [TransactionController::class, 'pdh'],
    '/transactions/form/jersey'       => [TransactionController::class, 'jersey'],
    '/transactions/form/poloshirt'    => [TransactionController::class, 'poloshirt'],
    '/transactions/form/seragamolahraga' => [TransactionController::class, 'seragamolahraga'],
    '/transactions/form/jackethoodie' => [TransactionController::class, 'jackethoodie'],
    '/products' => [ProductController::class, 'index'],
    '/finance' => [FinanceController::class, 'index'],
    '/transactions/invoice' => [TransactionController::class, 'invoice'],
    '/products'                       => [ProductController::class, 'index'],
    '/products/store'                 => [ProductController::class, 'store'],         // POST
    '/products/update'                => [ProductController::class, 'update'],        // POST
    '/products/destroy'               => [ProductController::class, 'destroy'],       // POST
    '/products/destroy-option'        => [ProductController::class, 'destroyOption'], // POST
    '/finance'                        => [FinanceController::class, 'index'],
];
