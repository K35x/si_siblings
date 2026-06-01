<?php
$pageTitle = 'Invoice - ' . "Siblings.co";
$pageStyles = ['transactions.css', 'detail-pesanan.css', 'invoice.css'];

date_default_timezone_set("Asia/Jakarta");

$mode          = $mode ?? 'session';
$order         = $order ?? null;
$invoiceNumber = $invoice_number ?? ("ORD-" . date('Ymd') . '-0001');
$customerName  = $customer_name ?? $_SESSION['customer_name'] ?? '-';
$customerPhone = $customer_phone ?? $_SESSION['customer_phone'] ?? '-';
$namaProject   = $project_name ?? $_SESSION['project_name'] ?? '';
$tglPemesanan  = $order_date ?? $_SESSION['order_date'] ?? date('Y-m-d');
$items         = $items ?? ($_SESSION['keranjang'] ?? []);
$grandTotal    = (float) ($grand_total ?? 0);
$detailsByItem = $detailsByItem ?? [];
$designs       = $designs ?? [];
$paymentSummary = $payment_summary ?? ['total_paid' => 0, 'total_void' => 0, 'total_refunded' => 0];

$totalPaid   = (float) ($paymentSummary['total_paid'] ?? 0);
$sisaTagihan = (float) ($paymentSummary['remaining_balance'] ?? max(0, $grandTotal - $totalPaid));
$suggestedInitialPaymentAmount = $grandTotal > 0 ? ceil($grandTotal * Model::DP_THRESHOLD) : 0;

$badgeClassMap = [
    Model::ORDER_STATUS_PENDING_PAYMENT => 'warning',
    Model::ORDER_STATUS_CONFIRMED       => 'info',
    Model::ORDER_STATUS_IN_PROGRESS     => 'info',
    Model::ORDER_STATUS_READY           => 'success',
    Model::ORDER_STATUS_COMPLETED       => 'success',
    Model::ORDER_STATUS_CANCELLED       => 'danger',
];

$totalQtyAll = 0;
foreach ($items as $it) {
    $totalQtyAll += (int) ($it['quantity'] ?? 0);
}

$designsByItem = [];
foreach ($designs as $d) {
    $designsByItem[(int) $d['order_item_id']][] = $d;
}
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
$sidebarRole = $sidebarRole ?? Model::ROLE_KASIR;
$activeMenu  = $activeMenu  ?? '';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <?php if ($mode === 'session'): ?>
                <?php $currentStep = 5; include __DIR__ . '/includes/step-indicator.php'; ?>
            <?php endif; ?>

            <article class="invoice-card" aria-label="Invoice <?= e($invoiceNumber) ?>">
                <!-- Header -->
                <header class="invoice-header">
                    <div class="invoice-header__brand">
                        <h2><?= e("Siblings.co") ?></h2>
                        <p><?= e("Konveksi & Sablon Profesional") ?></p>
                    </div>
                    <div class="invoice-header__meta">
                        <span class="invoice-badge">INVOICE</span>
                        <span class="invoice-number">#<?= e($invoiceNumber) ?></span>
                        <?php if ($order): ?>
                            <?php $statusInfo = Model::ORDER_STATUS_MAP[$order['order_status']] ?? null; ?>
                            <?php if ($statusInfo): ?>
                                <span class="invoice-status-badge invoice-status-badge--<?= e($badgeClassMap[$order['order_status']] ?? 'info') ?>">
                                    <i class="fas <?= e($statusInfo['icon']) ?>" aria-hidden="true"></i>
                                    <?= e($statusInfo['label']) ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- Customer & Order Info -->
                <div class="invoice-info">
                    <div class="invoice-info__block">
                        <p class="info-label">Dipesan Oleh</p>
                        <p class="info-value"><?= e($customerName) ?></p>
                        <?php if ($customerPhone !== '-'): ?>
                            <p class="info-sub" translate="no"><?= e($customerPhone) ?></p>
                        <?php endif; ?>
                        <?php if ($namaProject): ?>
                            <p class="info-sub"><i class="fas fa-clipboard" aria-hidden="true"></i> <?= e($namaProject) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="invoice-info__block invoice-info__block--right">
                        <p class="info-label">Tanggal Pesanan</p>
                        <p class="info-value--sm"><?= e(format_date_id($tglPemesanan)) ?></p>
                        <p class="info-sub"><?= e($totalQtyAll) ?> pcs &middot; <?= count($items) ?> item</p>
                    </div>
                </div>

                <!-- Items -->
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $idx => $item):
                        $kategori = $item['product_name_snapshot'] ?? ($item['category_name'] ?? ($item['category'] ?? 'Item'));
                        $bahan = $item['variant_name_snapshot'] ?? ($item['material'] ?? '-');
                        $sablon = $item['sablon_name'] ?? ($item['sablon'] ?? '-');
                        $itemDesigns = $designsByItem[(int) ($item['order_item_id'] ?? 0)] ?? [];
                        $itemDetails = $detailsByItem[(int) ($item['order_item_id'] ?? 0)] ?? [];
                        $itemSubtotal = (float) ($item['subtotal'] ?? ($item['price'] ?? 0));
                        $itemQty = (int) ($item['quantity'] ?? 0);
                        $computedItemSubtotal = 0;
                        foreach ($itemDetails as $d) {
                            $qty = (int) ($d['_total_qty'] ?? $d['quantity'] ?? 0);
                            $isLong = ($d['sleeve_type'] ?? 'short') === 'long';
                            $hargaBase = (float) ($item['unit_price'] ?? 0);
                            $xxlSurcharge = (float) ($d['price_surcharge'] ?? 0);
                            $longSurcharge = $isLong ? (float) ($item['sleeve_price'] ?? 0) : 0;
                            $sablonPrice = (float) ($item['sablon_price'] ?? 0);
                            $computedItemSubtotal += ($hargaBase + $xxlSurcharge + $longSurcharge + $sablonPrice) * $qty;
                        }
                        $displayItemSubtotal = !empty($itemDetails) ? $computedItemSubtotal : $itemSubtotal;
                    ?>
                        <div class="invoice-item">
                            <div class="invoice-item__header">
                                <strong class="invoice-item__name"><?= e($kategori) ?></strong>
                                <span class="tabular-nums invoice-item__subtotal"><?= e(format_currency($displayItemSubtotal)) ?></span>
                            </div>
                            <div class="invoice-item__specs">
                                <?php if ($bahan !== '-'): ?>
                                    <span><strong>Bahan:</strong> <?= e($bahan) ?></span>
                                <?php endif; ?>
                                <?php if ($sablon !== '-'): ?>
                                    <span><strong>Sablon:</strong> <?= e($sablon) ?>
                                        <?php if (!empty($item['sablon_price'])): ?>
                                            (<?= e(format_currency($item['sablon_price'])) ?>/pcs)
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($item['warna_summary']['short'])): ?>
                                <div class="invoice-item__specs">
                                    <span><strong>Pendek:</strong> <?= e($item['warna_summary']['short']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['warna_summary']['long'])): ?>
                                <div class="invoice-item__specs">
                                    <span><strong>Panjang:</strong> <?= e($item['warna_summary']['long']) ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Rincian table (size/color/sleeve breakdown) -->
                            <?php if (!empty($itemDetails)): ?>
                                <div class="detail-item-rincian">
                                    <div class="detail-item-rincian__table-wrap">
                                        <table class="detail-item-rincian__table">
                                            <thead>
                                                <tr>
                                                    <th>Size</th>
                                                    <th>Qty</th>
                                                    <th>Warna</th>
                                                    <th>Lengan</th>
                                                    <th>Stok</th>
                                                    <th>Harga &times; Qty</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($itemDetails as $d):
                                                    $sz = $d['size_name'] ?? '?';
                                                    $color = $d['color_name'] ?? '-';
                                                    $qty = (int) ($d['_total_qty'] ?? $d['quantity'] ?? 0);
                                                    $readyQty = (int) ($d['_ready_qty'] ?? 0);
                                                    $customQty = (int) ($d['_custom_qty'] ?? 0);
                                                    $isLong = ($d['sleeve_type'] ?? 'short') === 'long';
                                                    $hargaBase = (float) ($item['unit_price'] ?? 0);
                                                    $xxlSurcharge = (float) ($d['price_surcharge'] ?? 0);
                                                    $longSurcharge = $isLong ? (float) ($item['sleeve_price'] ?? 0) : 0;
                                                    $sablonPrice = (float) ($item['sablon_price'] ?? 0);
                                                    $hargaFinal = $hargaBase + $xxlSurcharge + $longSurcharge + $sablonPrice;
                                                ?>
                                                    <tr>
                                                        <td><?= e($sz) ?></td>
                                                        <td class="tabular-nums"><?= e($qty) ?></td>
                                                        <td><?= e($color) ?></td>
                                                        <td><?= $isLong ? 'Panjang' : 'Pendek' ?></td>
                                                        <td>
                                                            <?php if ($readyQty > 0): ?>
                                                                <span class="badge badge--success"><?= e($readyQty) ?> Ready</span>
                                                            <?php endif; ?>
                                                            <?php if ($customQty > 0): ?>
                                                                <span class="badge badge--warning"><?= e($customQty) ?> Buat</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="tabular-nums"><?= e(format_currency($hargaFinal)) ?> &times; <?= e($qty) ?></td>
                                                        <td class="tabular-nums"><?= e(format_currency($hargaFinal * $qty)) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6">Total (<?= e($itemQty) ?> pcs)</td>
                                                    <td class="tabular-nums"><?= e(format_currency($displayItemSubtotal)) ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Surcharges (session mode) -->
                            <?php if ($mode === 'session'): ?>
                                <?php
                                $sc = $item['surcharge'] ?? [];
                                ?>
                                <?php if (($sc['xxl_qty'] ?? 0) > 0 || ($sc['long_qty'] ?? 0) > 0): ?>
                                    <div class="item-surcharges">
                                        <?php if (($sc['long_qty'] ?? 0) > 0): ?>
                                            <span>+Lengan Panjang (<?= e($sc['long_qty']) ?>×<?= e(format_currency($sc['long_rate'] ?? 5000)) ?>)</span>
                                        <?php endif; ?>
                                        <?php if (($sc['xxl_qty'] ?? 0) > 0): ?>
                                            <span>+XXL (<?= e($sc['xxl_qty']) ?>×<?= e(format_currency($sc['xxl_qty'] > 0 ? $sc['xxl_total'] / $sc['xxl_qty'] : 0)) ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Design gallery -->
                            <?php if (!empty($itemDesigns)): ?>
                                <div class="detail-item-designs">
                                    <span class="detail-item-designs__label"><i class="fas fa-image" aria-hidden="true"></i> Desain:</span>
                                    <div class="design-gallery design-gallery--compact">
                                        <?php foreach ($itemDesigns as $d):
                                            $fn = $d['filename'];
                                            $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);
                                            $fileUrl = url('uploads/desain/' . $fn);
                                        ?>
                                            <div class="design-gallery__item-wrap">
                                                <?php if ($isImage): ?>
                                                    <a class="design-gallery__item" href="<?= e($fileUrl) ?>" target="_blank" rel="noopener">
                                                        <img src="<?= e($fileUrl) ?>" alt="<?= e($d['notes'] ?: 'Desain') ?>" loading="lazy">
                                                    </a>
                                                <?php else: ?>
                                                    <a class="design-gallery__item design-gallery__item--file" href="<?= e($fileUrl) ?>" target="_blank" rel="noopener">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <span class="design-gallery__label"><?= e($d['notes'] ?: $fn) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($item['item_notes']) || !empty($item['catatan'])): ?>
                                <div class="invoice-item__catatan"><i class="fas fa-sticky-note" aria-hidden="true"></i> <?= e($item['item_notes'] ?? $item['catatan']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="invoice-empty">Tidak ada item.</div>
                <?php endif; ?>

                <!-- Summary -->
                <div class="invoice-summary">
                    <div class="invoice-summary__rows">
                        <div class="summary-row summary-row--total">
                            <span>TOTAL TAGIHAN</span>
                            <span class="tabular-nums"><?= e(format_currency($grandTotal)) ?></span>
                        </div>

                        <?php if ($mode === 'database'): ?>
                            <div class="summary-row summary-row--paid">
                                <span><i class="fas fa-check-circle" aria-hidden="true"></i> Sudah Dibayar</span>
                                <span class="tabular-nums"><?= e(format_currency($totalPaid)) ?></span>
                            </div>
                            <?php if ($sisaTagihan > 0): ?>
                                <div class="summary-row summary-row--sisa">
                                    <span>Sisa Tagihan</span>
                                    <span class="tabular-nums"><?= e(format_currency($sisaTagihan)) ?></span>
                                </div>
                            <?php else: ?>
                                <div class="summary-row summary-row--lunas">
                                    <span class="lunas-text">LUNAS</span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer (visible in print) -->
                <footer class="invoice-footer">
                    <p>Terima kasih atas pesanan Anda. Pembayaran dapat dilakukan via Transfer Bank atau Tunai.</p>
                    <p class="invoice-footer__contact"><?= e("Siblings.co") ?> &middot; <?= e("Konveksi & Sablon Profesional") ?> &middot; Telp/WA: <?= e("0812-XXXX-XXXX") ?></p>
                </footer>
            </article>

            <?php if ($mode === 'session' && !empty($_SESSION['error'])): ?>
                <div class="invoice-flash no-print">
                    <div class="alert alert--danger" role="alert"><?= e($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if ($mode === 'session'): ?>
                <article class="detail-card detail-card--payment-form invoice-payment-card no-print">
                    <div class="detail-card__header">
                        <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
                        Pembayaran / DP
                    </div>
                    <div class="detail-card__body">
                        <div class="payment-form payment-form--initial">
                            <div class="payment-form__row">
                                <div class="payment-form__field">
                                    <label for="initial_payment_method">Metode Bayar</label>
                                    <select name="initial_payment_method" id="initial_payment_method" form="storeOrderForm">
                                        <?php foreach (Model::PAYMENT_METHODS as $val => $label): ?>
                                            <option value="<?= e($val) ?>"><?= e($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="payment-form__field">
                                    <label for="initial_payment_amount">Jumlah DP (Rp)</label>
                                    <input type="number" name="initial_payment_amount" id="initial_payment_amount" min="0" step="any"
                                           value="<?= e($suggestedInitialPaymentAmount > 0 ? $suggestedInitialPaymentAmount : '') ?>"
                                           max="<?= e($grandTotal) ?>"
                                           step="1"
                                           placeholder="0 = tanpa pembayaran sekarang" form="storeOrderForm">
                                </div>
                                <div class="payment-form__field">
                                    <label for="initial_payment_date">Tanggal</label>
                                    <input type="date" name="initial_payment_date" id="initial_payment_date" value="<?= e(date('Y-m-d')) ?>" form="storeOrderForm">
                                </div>
                            </div>
                            <p class="payment-form__hint">
                                Kosongkan atau isi 0 jika belum ada pembayaran. Nominal default <?= e((int) (Model::DP_THRESHOLD * 100)) ?>% dari total.
                            </p>
                        </div>
                    </div>
                </article>
            <?php endif; ?>

            <!-- Actions -->
            <div class="action-btns">
                <a href="<?= url('/transactions') ?>" class="btn btn--ghost">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Kembali
                </a>
                <div class="action-btns__right">
                    <button type="button" class="btn btn--outline" data-action="print-invoice">
                        <i class="fas fa-print" aria-hidden="true"></i>
                        Cetak
                    </button>
                    <?php if ($mode === 'session'): ?>
                        <form id="storeOrderForm" action="<?= url('/transactions/store') ?>" method="POST" class="display-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="invoice_number" value="<?= e($invoiceNumber) ?>">
                            <button type="submit" class="btn btn--accent btn--lg" data-loading-label="Menyimpan…">
                                <i class="fas fa-check-circle" aria-hidden="true"></i>
                                Simpan Pesanan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        document.querySelector('[data-action="print-invoice"]')?.addEventListener('click', () => {
            window.print();
        });

    })();
</script>
</body>
</html>
