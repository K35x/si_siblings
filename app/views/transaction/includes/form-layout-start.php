<?php

$pageTitle = $pageTitle ?? 'Detail Pesanan - Siblings.co';
$pageStyles = array_merge(['transactions.css', 'transaction-form.css'], $pageStyles ?? []);


$editItem = $editItem ?? [];
$isEdit = !empty($editItem);
$editBahan = $editItem['material'] ?? '';
$editSablon = $editItem['sablon'] ?? '';
$editHargaSablon = $editItem['sablon_price'] ?? 0;
$editCatatan = $editItem['catatan'] ?? '';
$editRincian = $editItem['rincian'] ?? [];
$editWarnaPerSize = $editItem['warna_per_size'] ?? ['short' => [], 'long' => []];
$editCustomColors = $editItem['custom_colors'] ?? [];
$validationErrors = $_SESSION['validation_errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['validation_errors'], $_SESSION['old_input']);
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
    $sidebarRole = $sidebarRole ?? Model::ROLE_KASIR;
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

            <?php $currentStep = 3; include __DIR__ . '/step-indicator.php'; ?>

            <?php include __DIR__ . '/validation-errors.php'; ?>

            <h1><?= e($formHeading ?? 'Konfigurasi Pesanan') ?></h1>
