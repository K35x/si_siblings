<?php
$baseUrl = rtrim(url('/'), '/');
$sessionRole = (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user']['role']))
    ? $_SESSION['user']['role']
    : null;
$role = resolve_sidebar_role($sidebarRole ?? null, $sessionRole ?? ($_GET['role'] ?? null));

$menus = [
    'kasir' => [
        [
            'key' => 'dashboard',
            'label' => 'Beranda',
            'icon' => 'fas fa-home',
            'url' => ($baseUrl ?: '') . '/kasir',
        ],
        [
            'key' => 'orders',
            'label' => 'Pesanan',
            'icon' => 'fas fa-shopping-basket',
            'url' => ($baseUrl ?: '') . '/transactions/create',
        ],
        [
            'key' => 'cart',
            'label' => 'Keranjang',
            'icon' => 'fas fa-shopping-cart',
            'url' => ($baseUrl ?: '') . '/transactions/cart',
        ],
        [
            'key' => 'status',
            'label' => 'Status Pesanan',
            'icon' => 'fas fa-tasks',
            'url' => ($baseUrl ?: '') . '/transactions',
        ],
        [
            'key' => 'logout',
            'label' => 'Logout',
            'icon' => 'fas fa-sign-out-alt',
            'url' => ($baseUrl ?: '') . '/logout',
        ],
    ],
    'owner' => [
        [
            'key' => 'dashboard',
            'label' => 'Beranda',
            'icon' => 'fas fa-home',
            'url' => ($baseUrl ?: '') . '/owner',
        ],
        [
            'key' => 'status',
            'label' => 'Status Pesanan',
            'icon' => 'fas fa-shopping-basket',
            'url' => ($baseUrl ?: '') . '/transactions?role=owner',
        ],
        [
            'key' => 'products',
            'label' => 'Produk',
            'icon' => 'fas fa-box',
            'url' => ($baseUrl ?: '') . '/products',
        ],
        [
            'key' => 'finance',
            'label' => 'Keuangan',
            'icon' => 'fas fa-money-bill',
            'url' => ($baseUrl ?: '') . '/finance',
        ],
        [
            'key' => 'logout',
            'label' => 'Logout',
            'icon' => 'fas fa-sign-out-alt',
            'url' => ($baseUrl ?: '') . '/logout',
        ],
    ],
];

if (!array_key_exists($role, $menus)) {
    $role = 'kasir';
}

$currentMenu = $activeMenu ?? '';

if ($currentMenu === '') {
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    $basePath = app_base_url();
    if ($basePath !== '' && str_starts_with($currentPath, $basePath)) {
        $currentPath = substr($currentPath, strlen($basePath));
    }
    $currentPath = '/' . trim($currentPath, '/');
    if ($currentPath === '//') {
        $currentPath = '/';
    }

    $currentMenu = match ($currentPath) {
        '/kasir', '/owner' => 'dashboard',
        '/transactions/create', '/transactions/categories' => 'orders',
        '/transactions/cart' => 'cart',
        '/transactions/invoice' => 'invoice',
        '/products' => 'products',
        '/finance' => 'finance',
        default => str_starts_with($currentPath, '/transactions') ? 'status' : '',
    };
}
?>
<aside class="sidebar">
    <div class="logo-container"><h2>Siblings.co</h2></div>
    <nav>
        <?php foreach ($menus[$role] as $menu): ?>
            <?php $isActive = $currentMenu === $menu['key']; ?>
            <a href="<?= htmlspecialchars($menu['url'], ENT_QUOTES, 'UTF-8') ?>" class="nav-item<?= $isActive ? ' active' : '' ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
                <i class="<?= htmlspecialchars($menu['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                <?= htmlspecialchars($menu['label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
