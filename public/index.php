<?php

require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/models/TransactionModel.php';
require_once __DIR__ . '/../app/models/ProductModel.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/TransactionController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/FinanceController.php';

$routes = require __DIR__ . '/../app/config/routes.php';

App::run($routes);
