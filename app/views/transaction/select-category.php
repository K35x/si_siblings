<?php
$pageTitle = 'Pilih Kategori Pesanan - Siblings.co';
$pageStyles = ['transactions.css'];




$formKeyMap = [];
foreach ($categories as $cat) {
    $formKeyMap[strtolower($cat['category_name'])] = strtolower(str_replace(' ', '-', $cat['category_name']));
}

$customerName  = $_SESSION['customer_name'] ?? '';
$customerPhone = $_SESSION['customer_phone'] ?? '';
$namaProject   = $_SESSION['project_name'] ?? '';
$tglPemesanan  = $_SESSION['order_date'] ?? '';
$hasBiodata    = $customerName !== '';

$iconMap = [
    't-shirt'         => 'fa-shirt',
    'jersey'          => 'fa-vest',
    'polo shirt'      => 'fa-vest',
    'seragam olahraga'=> 'fa-dumbbell',
    'jacket'          => 'fa-vest-patches',
    'hoodie'          => 'fa-hat-cowboy',
    'pdh'             => 'fa-user-tie',
];
$defaultIcon = 'fa-shirt';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<a href="#main-content" class="skip-to-content">Lewati ke konten utama</a>
<?php include __DIR__ . '/../partials/sidebar-toggle.php'; ?>

<div class="app-shell">
    <?php
$sidebarRole = $sidebarRole ?? Model::ROLE_KASIR;
$activeMenu  = $activeMenu  ?? 'orders';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <a href="<?= url('/transactions') ?>" class="btn-back">
                <i class="fas fa-arrow-left" aria-hidden="true"></i> Kembali
            </a>
            <h1>Pilih Kategori</h1>
            <p class="text-muted">Pilih jenis produk yang ingin dipesan oleh pelanggan.</p>

            <div class="category-grid">
                <?php foreach ($categories as $cat):
                    $catLower = strtolower($cat['category_name']);
                    $formKey = $formKeyMap[$catLower] ?? str_replace(' ', '-', $catLower);
                    $icon = $iconMap[$catLower] ?? $defaultIcon;
                ?>
                    <a href="<?= url('/transactions/form/' . $formKey) ?>"
                       class="category-card">
                        <div class="category-card__icon">
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="category-card__info">
                            <h3 class="category-card__name"><?= e($cat['category_name']) ?></h3>
                        </div>
                        <i class="fas fa-chevron-right category-card__arrow" aria-hidden="true"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
</body>
</html>
