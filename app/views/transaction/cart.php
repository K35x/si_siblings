<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$validationErrors = $validationErrors ?? [];
$oldInput = $oldInput ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Siblings.co - Keranjang</title>
    <link rel="stylesheet" href="<?= asset('css/transactions.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/transaction-cart.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <main class="main-content">
            <?php include __DIR__ . '/includes/header.php'; ?>
            
            <div class="content-padding">
                <h1 class="main-title">Keranjang Pesanan</h1>
                <p class="subtitle">Berikut adalah daftar item yang akan dipesan.</p>

                <?php if (!empty($validationErrors)): ?>
                    <div class="alert alert-danger" style="background:#fdecea;color:#b71c1c;border:1px solid #f5c2c7;border-radius:6px;padding:12px;margin:12px 0;">
                        <strong>Input pesanan belum valid.</strong>
                        <ul style="margin:8px 0 0 18px;">
                            <?php foreach ($validationErrors as $message): ?>
                                <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($oldInput)): ?>
                    <div class="alert alert-warning" style="background:#fff8e1;color:#6d4c41;border:1px solid #ffe082;border-radius:6px;padding:12px;margin:12px 0;">
                        Data input lama masih tersedia untuk diperbaiki. Silakan kembali ke form produk dan submit ulang setelah koreksi.
                    </div>
                <?php endif; ?>

                <div class="cart-card">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Detail Spesifikasi</th>
                                <th>Rincian Size</th>
                                <th>Total Qty</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($_SESSION['keranjang'])): ?>
                                <?php foreach($_SESSION['keranjang'] as $index => $item): ?>
                                <tr>
                                    <td><strong><?php echo $item['kategori']; ?></strong></td>
                                    <td>
                                        <div class="spec-info">
                                            <span><strong>Bahan:</strong> <?php echo $item['bahan']; ?> (<?php echo $item['warna']; ?>)</span>
                                            <span><strong>Sablon:</strong> <?php echo $item['sablon']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="size-pills">
                                            <?php foreach($item['rincian'] as $sz => $jml): if($jml > 0): ?>
                                                <span><?php echo $sz; ?>: <?php echo $jml; ?></span>
                                            <?php endif; endforeach; ?>
                                        </div>
                                    </td>
                                    <td><span class="total-qty"><?php echo $item['qty']; ?> pcs</span></td>
                                    <td> <span class="price-text"> Rp <?php echo number_format($item['harga'] ?? 0, 0, ',', '.'); ?>
                                    </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= url('/transactions/form/tshirt') ?>?edit=<?php echo $index; ?>" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                            <a href="?hapus=<?php echo $index; ?>" class="btn-icon delete" onclick="return confirm('Hapus item?')"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center; padding: 50px;">Keranjang kosong.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="cart-summary-section">
                        <div class="summary-box">
                            <div class="summary-row">
                                <span>Subtotal Barang:</span>
                                <span>Rp <?php 
                                $grand_total = 0;
                                if(!empty($_SESSION['keranjang'])) {
                                   foreach($_SESSION['keranjang'] as $item) {
                                     $grand_total += ($item['harga'] ?? 0);
                                }
                             }
                             echo number_format($grand_total, 0, ',', '.'); 
                        ?></span>
                        </div>
                        <div class="summary-row grand-total-row">
                            <span>Grand Total:</span>
                            <span class="final-price">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>

                    <div class="cart-actions-bottom">
                        <a href="<?= url('/transactions/categories') ?>" class="btn-tambah">+ Tambah Kategori Lain</a>
                        <a href="<?= url('/transactions/invoice') ?>" class="btn-invoice">Proses Invoice <i class="fas fa-file-invoice"></i></a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>