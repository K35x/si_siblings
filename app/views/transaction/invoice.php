<?php
$pageTitle = 'Invoice - Siblings.co';
$pageStyles = ['transactions.css', 'invoice.css'];

date_default_timezone_set('Asia/Jakarta');

$invoiceNumber = $invoice_number ?? ('INV-' . date('Ymd') . '-0001');
$customerName  = $customer_name ?? '-';
$customerPhone = $customer_phone ?? '-';
$items         = $items ?? ($_SESSION['keranjang'] ?? []);
$grandTotal    = (float) ($grand_total ?? 0);
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
$activeMenu  = $activeMenu  ?? '';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <article class="invoice-card" aria-label="Invoice <?= e($invoiceNumber) ?>">
                <header class="invoice-header">
                    <div>
                        <h2>Siblings.co</h2>
                        <p>Konveksi & Sablon Profesional</p>
                    </div>
                    <div class="text-right">
                        <h3>INVOICE</h3>
                        <p>#<?= e($invoiceNumber) ?></p>
                    </div>
                </header>

                <div class="invoice-body">
                    <div class="info-grid">
                        <div>
                            <p class="info-label">Dipesan Oleh</p>
                            <p class="info-value"><?= e($customerName) ?></p>
                            <p class="text-muted" translate="no"><?= e($customerPhone) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="info-label">Tanggal Pesanan</p>
                            <p class="info-value--sm"><?= e(format_date_id(date('Y-m-d'))) ?></p>
                        </div>
                    </div>

                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th scope="col">Item Deskripsi</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-right">Harga Satuan</th>
                                <th scope="col" class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item):
                                    $qty = max(1, (int) ($item['qty'] ?? 1));
                                    $price = (float) ($item['harga'] ?? $item['subtotal'] ?? 0);
                                    $unit = $price / $qty;
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?= e($item['kategori'] ?? $item['name'] ?? 'Item') ?></strong>
                                            <?php if (!empty($item['bahan'])): ?>
                                                <br><small>Bahan: <?= e($item['bahan']) ?></small>
                                            <?php endif; ?>
                                            <?php if (!empty($item['sablon'])): ?>
                                                <br><small>Sablon: <?= e($item['sablon']) ?></small>
                                            <?php endif; ?>
                                            <?php if (!empty($item['warna_summary']['short'])): ?>
                                                <br><small>Pendek: <?= e($item['warna_summary']['short']) ?></small>
                                            <?php endif; ?>
                                            <?php if (!empty($item['warna_summary']['long'])): ?>
                                                <br><small>Panjang: <?= e($item['warna_summary']['long']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= e($qty) ?>&nbsp;pcs</td>
                                        <td class="text-right"><?= e(format_currency($unit)) ?></td>
                                        <td class="text-right"><?= e(format_currency($price)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center invoice-empty-cell">
                                        Keranjang kosong.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <tr class="row-summary">
                                <td colspan="3" class="text-right row-summary__label">
                                    <label for="displayDP">Nominal DP</label>
                                </td>
                                <td class="text-right">
                                    <input type="text" id="displayDP" class="input-dp-box" placeholder="0"
                                           inputmode="numeric"
                                           autocomplete="off"
                                           aria-describedby="sisaTagihan">
                                </td>
                            </tr>

                            <tr class="total-row">
                                <td colspan="3" class="text-right">TOTAL TAGIHAN</td>
                                <td class="text-right"><?= e(format_currency($grandTotal)) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-right invoice-summary-block">
                        <p class="info-label">Sisa yang harus dibayar</p>
                        <h2 class="sisa-tagihan-text" id="sisaTagihan" aria-live="polite"><?= e(format_currency($grandTotal)) ?></h2>
                    </div>
                </div>
            </article>

            <div class="action-btns">
                <a href="<?= url('/transactions') ?>" class="btn btn--ghost">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Selesai
                </a>
                <button type="button" class="btn btn--primary" data-action="print-invoice">
                    <i class="fas fa-print" aria-hidden="true"></i>
                    Simpan PDF
                </button>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        const total = <?= json_encode($grandTotal) ?>;
        const dpInput = document.getElementById('displayDP');
        const sisaEl = document.getElementById('sisaTagihan');
        const fmt = new Intl.NumberFormat('id-ID');

        function formatRp(num) {
            return 'Rp\u00a0' + fmt.format(Math.max(0, num));
        }

        if (dpInput) {
            dpInput.addEventListener('input', () => {
                const raw = dpInput.value.replace(/\D/g, '');
                dpInput.value = raw === '' ? '' : fmt.format(raw);
                const dp = parseInt(raw || '0', 10);
                sisaEl.textContent = formatRp(total - dp);
            });
        }

        document.querySelector('[data-action="print-invoice"]')?.addEventListener('click', () => {
            window.print();
        });
    })();
</script>
</body>
</html>
