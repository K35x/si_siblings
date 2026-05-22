<?php
$pageTitle    = 'Konfigurasi Jacket & Hoodie - Siblings.co';
$formHeading  = 'Konfigurasi Jacket & Hoodie';

include __DIR__ . '/../includes/form-layout-start.php';
?>

<div class="empty-state empty-state--mt">
    <i class="fas fa-tools empty-state__icon" aria-hidden="true"></i>
    <h2 class="empty-state__title">Form belum tersedia</h2>
    <p class="empty-state__desc">
        Konfigurasi untuk Jacket &amp; Hoodie sedang disiapkan. Silakan pilih kategori lain
        terlebih dahulu, atau hubungi admin untuk pemesanan kustom.
    </p>
    <a href="<?= url('/transactions/categories') ?>" class="btn btn--primary">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        Pilih Kategori Lain
    </a>
</div>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
