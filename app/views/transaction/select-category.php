<?php
$pageTitle = 'Pilih Kategori Pesanan - Siblings.co';
$pageStyles = ['transactions.css'];

$categories = [
    ['url' => 'tshirt',          'icon' => 'fas fa-tshirt',     'name' => 'T-Shirt / Kaos',  'desc' => 'Kaos oblong, raglan, lengan panjang, atau sablon custom.'],
    ['url' => 'pdh',             'icon' => 'fas fa-user-tie',   'name' => 'PDH / Kemeja',    'desc' => 'Seragam organisasi, kemeja kerja, PDL, atau kemeja formal bordir.'],
    ['url' => 'jersey',          'icon' => 'fas fa-running',    'name' => 'Jersey',          'desc' => 'Pakaian olahraga futsal, basket, atau badminton dengan bahan dry-fit.'],
    ['url' => 'poloshirt',       'icon' => 'fas fa-shirt',      'name' => 'Polo Shirt',      'desc' => 'Kaos berkerah dengan bahan lacoste atau pique untuk kesan semi-formal.'],
    ['url' => 'seragamolahraga', 'icon' => 'fas fa-medal',      'name' => 'Seragam Olahraga','desc' => 'Setelan baju & celana olahraga untuk sekolah, instansi, atau komunitas.'],
    ['url' => 'jackethoodie',    'icon' => 'fas fa-mitten',     'name' => 'Jacket & Hoodie', 'desc' => 'Bomber, coach jacket, jumper, atau zipper hoodie dengan bahan hangat.'],
];
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
$sidebarRole = $sidebarRole ?? 'kasir';
$activeMenu  = $activeMenu  ?? 'orders';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <a href="<?= url('/transactions') ?>" class="btn-back">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                Kembali ke daftar pesanan
            </a>

            <h1>Pilih Kategori Pesanan</h1>
            <p class="text-muted">Pilih jenis pakaian untuk melanjutkan ke form input detail.</p>

            <div class="category-grid">
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= url('/transactions/form/' . $cat['url']) ?>" class="category-card">
                        <span class="category-card__icon" aria-hidden="true">
                            <i class="<?= e($cat['icon']) ?>"></i>
                        </span>
                        <h3><?= e($cat['name']) ?></h3>
                        <p><?= e($cat['desc']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
</body>
</html>
