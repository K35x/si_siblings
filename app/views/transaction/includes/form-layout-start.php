<?php
/**
 * Partial: layout shell untuk form pesanan custom.
 * Include partial ini DI ATAS view, lalu output content via output buffer.
 *
 * Variables yang harus di-set:
 *   $pageTitle    - judul halaman
 *   $formHeading  - heading utama (mis. "Konfigurasi Custom T-Shirt")
 *
 * Variables opsional:
 *   $pageStyles   - CSS tambahan
 */
$pageTitle = $pageTitle ?? 'Detail Pesanan - Siblings.co';
$pageStyles = array_merge(['transactions.css', 'transaction-form.css'], $pageStyles ?? []);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/../../partials/head.php'; ?>
</head>
<body>
<a href="#main-content" class="skip-to-content">Lewati ke konten utama</a>
<?php include __DIR__ . '/../../partials/sidebar-toggle.php'; ?>

<div class="app-shell">
    <?php
    $sidebarRole = $sidebarRole ?? 'kasir';
    $activeMenu  = $activeMenu  ?? 'orders';
    include __DIR__ . '/../../layouts/sidebar.php';
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <a href="<?= url('/transactions/categories') ?>" class="form-back-link">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                Kembali ke katalog
            </a>

            <?php include __DIR__ . '/validation-errors.php'; ?>

            <h1><?= e($formHeading ?? 'Konfigurasi Pesanan') ?></h1>
