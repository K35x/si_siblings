<?php
/**
 * Sidebar partial.
 *
 * Variables yang dapat di-set sebelum include:
 *   $sidebarRole - 'kasir' | 'owner' (auto-resolve dari session jika null)
 *   $activeMenu  - key menu yang aktif. Jika kosong, akan ditebak dari URL.
 */
$baseUrl = rtrim(url('/'), '/');
$sessionRole = (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user']['role']))
    ? $_SESSION['user']['role']
    : null;
$role = resolve_sidebar_role($sidebarRole ?? null, $sessionRole ?? ($_GET['role'] ?? null));

$menus = [
    'kasir' => [
        ['key' => 'dashboard', 'label' => 'Beranda',        'icon' => 'fas fa-home',           'url' => ($baseUrl ?: '') . '/kasir'],
        ['key' => 'orders',    'label' => 'Pesanan',        'icon' => 'fas fa-shopping-basket','url' => ($baseUrl ?: '') . '/transactions/create'],
        ['key' => 'cart',      'label' => 'Keranjang',      'icon' => 'fas fa-shopping-cart',  'url' => ($baseUrl ?: '') . '/transactions/cart'],
        ['key' => 'status',    'label' => 'Status Pesanan', 'icon' => 'fas fa-tasks',          'url' => ($baseUrl ?: '') . '/transactions'],
        ['key' => 'logout',    'label' => 'Logout',         'icon' => 'fas fa-sign-out-alt',   'url' => ($baseUrl ?: '') . '/logout'],
    ],
    'owner' => [
        ['key' => 'dashboard', 'label' => 'Beranda',        'icon' => 'fas fa-home',            'url' => ($baseUrl ?: '') . '/owner'],
        ['key' => 'status',    'label' => 'Status Pesanan', 'icon' => 'fas fa-shopping-basket', 'url' => ($baseUrl ?: '') . '/transactions?role=owner'],
        ['key' => 'products',  'label' => 'Produk',         'icon' => 'fas fa-box',             'url' => ($baseUrl ?: '') . '/products'],
        ['key' => 'finance',   'label' => 'Keuangan',       'icon' => 'fas fa-money-bill',      'url' => ($baseUrl ?: '') . '/finance'],
        ['key' => 'logout',    'label' => 'Logout',         'icon' => 'fas fa-sign-out-alt',    'url' => ($baseUrl ?: '') . '/logout'],
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
<aside id="sidebar" class="sidebar" aria-label="Navigasi utama">
    <div class="sidebar__logo logo-container">
        <h2>Siblings.co</h2>
    </div>
    <nav class="sidebar__nav" aria-label="Menu utama">
        <?php foreach ($menus[$role] as $menu): ?>
            <?php $isActive = $currentMenu === $menu['key']; ?>
            <a
                href="<?= e($menu['url']) ?>"
                class="nav-item<?= $isActive ? ' active' : '' ?>"
                <?= $isActive ? 'aria-current="page"' : '' ?>>
                <i class="<?= e($menu['icon']) ?>" aria-hidden="true"></i>
                <span><?= e($menu['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
