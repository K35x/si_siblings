<?php
$id_pesanan     = $id_pesanan ?? ($_GET['id'] ?? 'ORD001');
$kategori       = $kategori ?? '-';
$nama_pelanggan = $nama_pelanggan ?? '-';
$telepon        = $telepon ?? '-';
$tanggal        = $tanggal ?? '-';
$user_role      = $user_role ?? 'kasir';
$order          = $order ?? [];
$items          = $items ?? [];

// Extract specs from first item
$firstItem   = $items[0] ?? [];
$bahan       = $firstItem['bahan'] ?? '-';
$warna       = $firstItem['warna'] ?? '-';
$sablon_opsi = $firstItem['sablon'] ?? '-';
$catatan     = $order['catatan'] ?? '';

// Aggregate sizes from all items
$sizeData = ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0];
$totalProduksi = 0;
foreach ($items as $item) {
    $rincian = $item['rincian'] ?? [];
    if (is_string($rincian)) {
        $rincian = json_decode($rincian, true) ?? [];
    }
    foreach ($rincian as $sz => $qty) {
        $qty = (int) $qty;
        $sizeData[$sz] = ($sizeData[$sz] ?? 0) + $qty;
        $totalProduksi += $qty;
    }
}

// Determine status
$statusOrder = $order['status_order'] ?? 'processing';
$statusLabels = [
    'pending'    => ['label' => 'Belum Diproses', 'class' => 'status-pending'],
    'processing' => ['label' => 'Proses',         'class' => 'status-proses'],
    'done'       => ['label' => 'Selesai',        'class' => 'status-selesai'],
    'cancelled'  => ['label' => 'Batal',          'class' => 'status-batal'],
];
$statusInfo = $statusLabels[$statusOrder] ?? $statusLabels['processing'];

$pageTitle = 'Detail Pesanan #' . e($id_pesanan) . ' - Siblings.co';
$pageStyles = ['transactions.css', 'detail-pesanan.css'];
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
$sidebarRole = $sidebarRole ?? $user_role ?? 'kasir';
$activeMenu  = $activeMenu  ?? 'status';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <div class="info-meta">
                <div class="meta-box">
                    <h3>
                        Pesanan #<?= e($id_pesanan) ?>
                        <span class="badge <?= e($statusInfo['class']) ?> detail-meta-status"><?= e($kategori) ?></span>

                        <?php if ($user_role === 'owner'): ?>
                            <label class="sr-only" for="statusSelect">Ubah status pesanan</label>
                            <select id="statusSelect" class="status-select">
                                <option value="pending" <?= $statusOrder === 'pending' ? 'selected' : '' ?>>Belum Diproses</option>
                                <option value="processing" <?= $statusOrder === 'processing' ? 'selected' : '' ?>>Proses</option>
                                <option value="done" <?= $statusOrder === 'done' ? 'selected' : '' ?>>Selesai</option>
                                <option value="cancelled" <?= $statusOrder === 'cancelled' ? 'selected' : '' ?>>Batal</option>
                            </select>
                            <button type="button" id="saveStatusBtn" class="btn btn--icon btn--primary" aria-label="Simpan status">
                                <i class="fas fa-check" aria-hidden="true"></i>
                            </button>
                        <?php else: ?>
                            <span class="status-badge <?= e($statusInfo['class']) ?>"><?= e($statusInfo['label']) ?></span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-muted"><i class="far fa-calendar-alt" aria-hidden="true"></i> Tanggal Masuk: <strong><?= e($tanggal) ?></strong></p>
                </div>

                <div class="meta-box text-right">
                    <p class="text-muted">Pelanggan: <strong><?= e($nama_pelanggan) ?></strong></p>
                    <p class="text-muted" translate="no"><i class="fas fa-phone" aria-hidden="true"></i> <?= e($telepon) ?></p>
                </div>
            </div>

            <div class="detail-grid">
                <article class="detail-card">
                    <div class="detail-card__header"><i class="fas fa-cogs" aria-hidden="true"></i> Spesifikasi Produksi</div>
                    <div class="detail-card__body">
                        <ul class="spec-list">
                            <li class="spec-item"><span>Pilihan Paket / Bahan</span><span><?= e($bahan) ?></span></li>
                            <li class="spec-item"><span>Warna / Motif Kain</span><span><?= e($warna) ?></span></li>
                            <li class="spec-item"><span>Opsi Tambahan</span><span><?= e($sablon_opsi) ?></span></li>
                        </ul>

                        <h4 class="detail-section-label">File Master Desain</h4>
                        <div class="image-preview-box">
                            <i class="far fa-file-image" aria-hidden="true"></i>
                            <?php if (!empty($firstItem['design_url'])): ?>
                                <a href="<?= e($firstItem['design_url']) ?>" download>
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                    Download <?= e($firstItem['design_filename'] ?? 'desain') ?>
                                </a>
                            <?php else: ?>
                                <span class="image-preview-box__empty">File desain belum diunggah.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>

                <article class="detail-card">
                    <div class="detail-card__header"><i class="fas fa-th-list" aria-hidden="true"></i> Rincian Ukuran yang Dipesan</div>
                    <div class="detail-card__body">
                        <table class="data-table detail-data-table">
                            <thead>
                                <tr>
                                    <th scope="col">Ukuran</th>
                                    <th scope="col" class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sizeData as $sz => $qty): ?>
                                    <?php if ($qty > 0): ?>
                                        <tr>
                                            <td><strong><?= e($sz) ?></strong></td>
                                            <td class="text-center tabular-nums"><?= e($qty) ?>&nbsp;pcs</td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($totalProduksi === 0): ?>
                                    <tr><td colspan="2" class="text-center text-muted">Belum ada data ukuran.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="total-production">
                            <span>Total Produksi Baju</span>
                            <span><?= e($totalProduksi) ?>&nbsp;pcs</span>
                        </div>
                    </div>
                </article>
            </div>

            <article class="detail-card mt-6">
                <div class="detail-card__header"><i class="fas fa-list-ol" aria-hidden="true"></i> List Nameset / Keterangan Tambahan</div>
                <div class="detail-card__body">
                    <?php if (!empty($catatan)): ?>
                        <pre class="nameset-area"><?= e($catatan) ?></pre>
                    <?php else: ?>
                        <p class="text-muted">Tidak ada catatan tambahan.</p>
                    <?php endif; ?>
                </div>
            </article>

            <div class="action-footer">
                <a href="<?= url('/transactions') ?>" class="btn btn--ghost">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Kembali ke daftar
                </a>
                <a href="<?= url('/transactions/invoice?id=' . urlencode($id_pesanan)) ?>" class="btn btn--accent">
                    Lihat &amp; Cetak Invoice
                    <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        const statusSelect = document.getElementById('statusSelect');
        const saveBtn = document.getElementById('saveStatusBtn');
        if (!statusSelect) return;

        const palette = {
            pending:    { bg: 'var(--color-pending-bg)',  fg: 'var(--color-pending)' },
            processing: { bg: 'var(--color-pending-bg)',  fg: 'var(--color-pending)' },
            done:       { bg: 'var(--color-success-bg)',  fg: 'var(--color-success)' },
            cancelled:  { bg: 'var(--color-danger-bg)',   fg: 'var(--color-danger)' },
        };

        function apply(value) {
            const c = palette[value] || palette.proses;
            statusSelect.style.backgroundColor = c.bg;
            statusSelect.style.color = c.fg;
        }

        apply(statusSelect.value);
        statusSelect.addEventListener('change', () => apply(statusSelect.value));

        saveBtn?.addEventListener('click', () => {
            const text = statusSelect.options[statusSelect.selectedIndex].text;
            window.SiblingsUI?.toast?.(`Status pesanan diubah ke ${text}.`, 'success');
        });
    })();
</script>
</body>
</html>
