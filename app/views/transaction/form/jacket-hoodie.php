<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Detail Pesanan Jacket & Hoodie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/transactions.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/transaction-form.css') ?>">
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">
            <?php include __DIR__ . '/../includes/header.php'; ?>

            <div class="content-padding">
                <a href="<?= url('/transactions/categories') ?>" class="btn-back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                </a>

                <?php include __DIR__ . '/../includes/validation-errors.php'; ?>

                <h1 class="main-title">Konfigurasi Jacket & Hoodie</h1>
                <div class="form-container-full">
                    <p>Form detail Jacket & Hoodie belum tersedia. Silakan pilih kategori lain terlebih dahulu.</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
