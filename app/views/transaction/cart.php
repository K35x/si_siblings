<?php
$pageTitle = 'Keranjang Pesanan - Siblings.co';
$pageStyles = ['transactions.css', 'transaction-cart.css'];

$keranjang = $_SESSION['keranjang'] ?? [];
$grandTotal = 0;
foreach ($keranjang as $item) {
    $grandTotal += (float) ($item['harga'] ?? 0);
}

$validationErrors = $validationErrors ?? [];
$oldInput = $oldInput ?? [];
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
$activeMenu  = $activeMenu  ?? 'cart';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <h1>Keranjang Pesanan</h1>
            <p class="text-muted">Berikut daftar item yang akan dipesan.</p>

            <?php include __DIR__ . '/includes/validation-errors.php'; ?>

            <div class="cart-card">
                <div class="data-table cart-table-wrap">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th scope="col">Kategori</th>
                                <th scope="col">Detail Spesifikasi</th>
                                <th scope="col">Rincian Size</th>
                                <th scope="col">Total Qty</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($keranjang)): ?>
                                <?php foreach ($keranjang as $index => $item): ?>
                                    <tr>
                                        <td><strong><?= e($item['kategori']) ?></strong></td>
                                        <td>
                                            <div class="spec-info">
                                                <span><strong>Bahan:</strong> <?= e($item['bahan']) ?></span>
                                                <span><strong>Sablon:</strong> <?= e($item['sablon']) ?></span>
                                                <?php if (!empty($item['warna_summary']['short'])): ?>
                                                    <span><strong>Pendek:</strong> <?= e($item['warna_summary']['short']) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item['warna_summary']['long'])): ?>
                                                    <span><strong>Panjang:</strong> <?= e($item['warna_summary']['long']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="size-pills">
                                                <?php foreach ($item['rincian'] as $sz => $jml): if ($jml > 0): ?>
                                                    <span><?= e($sz) ?>: <?= e($jml) ?></span>
                                                <?php endif; endforeach; ?>
                                            </div>
                                        </td>
                                        <td><span class="total-qty tabular-nums"><?= e($item['qty']) ?>&nbsp;pcs</span></td>
                                        <td>
                                            <span class="price-text tabular-nums"><?= e(format_currency($item['harga'] ?? 0)) ?></span>
                                        </td>
                                        <td class="text-right">
                                            <div class="action-buttons">
                                                <a href="<?= url('/transactions/cart?edit=' . $index) ?>"
                                                   class="btn btn--icon btn--soft" aria-label="Edit item <?= e($item['kategori']) ?>">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn--icon btn--danger-soft"
                                                        data-cart-delete
                                                        data-href="<?= url('/transactions/cart?hapus=' . $index) ?>"
                                                        data-name="<?= e($item['kategori']) ?>"
                                                        aria-label="Hapus item <?= e($item['kategori']) ?>">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state empty-state--cart">
                                            <i class="fas fa-shopping-basket empty-state__icon" aria-hidden="true"></i>
                                            <h2 class="empty-state__title">Keranjang masih kosong</h2>
                                            <p class="empty-state__desc">Tambahkan kategori produk untuk memulai pesanan.</p>
                                            <a href="<?= url('/transactions/categories') ?>" class="btn btn--primary">
                                                <i class="fas fa-plus" aria-hidden="true"></i>
                                                Tambah Kategori
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($keranjang)): ?>
                    <div class="cart-summary-section">
                        <div class="summary-box">
                            <div class="summary-row">
                                <span>Subtotal Barang</span>
                                <span class="tabular-nums"><?= e(format_currency($grandTotal)) ?></span>
                            </div>
                            <div class="summary-row grand-total-row">
                                <span>Grand Total</span>
                                <span class="final-price tabular-nums"><?= e(format_currency($grandTotal)) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($keranjang)): ?>
                <div class="cart-actions-bottom">
                    <a href="<?= url('/transactions/categories') ?>" class="btn btn--ghost">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Tambah Kategori Lain
                    </a>
                    <a href="<?= url('/transactions/invoice') ?>" class="btn btn--primary btn--lg">
                        Proses Invoice
                        <i class="fas fa-file-invoice" aria-hidden="true"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    document.addEventListener('click', async (e) => {
        const trigger = e.target.closest('[data-cart-delete]');
        if (!trigger) return;
        e.preventDefault();

        const ok = await window.SiblingsUI.confirm({
            title: 'Hapus item?',
            message: `Item ${trigger.dataset.name || ''} akan dihapus dari keranjang.`,
            confirmText: 'Hapus',
            cancelText: 'Batal',
            variant: 'danger',
        });
        if (ok) {
            window.location.href = trigger.dataset.href;
        }
    });
</script>
</body>
</html>
