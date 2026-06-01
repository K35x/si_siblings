<?php

// ── Error Configuration ──────────────────────────────────────────
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// ── Security Headers ─────────────────────────────────────────────
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

require_once __DIR__ . '/../app/core/helpers.php';

// ── Bootstrap ──────────────────────────────────────────────────────
require_once __DIR__ . '/../app/core/Model.php';

// ── Session Configuration ──────────────────────────────────────────
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 0) == 443;
session_set_cookie_params([
    'lifetime' => 0,
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => $isSecure,
]);
session_start();

// Session timeout: 2 hours inactivity
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 7200) {
    session_unset();
    session_destroy();
    header('Location: ' . url('/login'));
    exit;
}
$_SESSION['last_activity'] = time();

// Periodic session regeneration every 30 minutes
if (!isset($_SESSION['created_at'])) {
    $_SESSION['created_at'] = time();
} elseif (time() - $_SESSION['created_at'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created_at'] = time();
}

// ── Bootstrap (remaining models & controllers) ─────────────────────
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/models/DashboardModel.php';
require_once __DIR__ . '/../app/models/TransactionModel.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/ProductModel.php';
require_once __DIR__ . '/../app/models/PenjualanModel.php';
require_once __DIR__ . '/../app/models/StockModel.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/TransactionController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/PenjualanController.php';

$config = require __DIR__ . '/../app/config/routes.php';
App::run($config['routes'], $config['public'] ?? [], $config['roles'] ?? []);
