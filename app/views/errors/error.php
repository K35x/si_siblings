<?php
$statusCode = (int) ($statusCode ?? 404);
$pageTitle = ($statusCode === 403 ? 'Akses Ditolak' : 'Halaman Tidak Ditemukan') . ' - Siblings.co';
$pageStyles = ['error.css'];
$title = $title ?? ($statusCode === 403 ? 'Akses ditolak' : 'Halaman tidak ditemukan');
$message = $message ?? ($statusCode === 403 ? 'Akun kamu tidak punya izin untuk membuka halaman ini.' : 'Alamat yang kamu tuju tidak tersedia atau sudah dipindahkan.');
$primaryUrl = $primaryUrl ?? url('/');
$primaryLabel = $primaryLabel ?? 'Kembali ke Beranda';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body class="error-page">
    <a href="#main-content" class="skip-to-content">Lewati ke konten utama</a>

    <main class="error-shell" id="main-content">
        <section class="error-card" aria-labelledby="error-title">
            <h1 class="error-card__code"><?= e((string) $statusCode) ?></h1>
            <h2 class="error-card__title" id="error-title"><?= e($title) ?></h2>
            <p class="error-card__message"><?= e($message) ?></p>

            <div class="error-card__actions">
                <a class="btn btn--primary" href="<?= e($primaryUrl) ?>">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <?= e($primaryLabel) ?>
                </a>
            </div>
        </section>
    </main>
</body>
</html>
