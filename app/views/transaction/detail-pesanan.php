<?php
$id_pesanan = $id_pesanan ?? ($_GET['id'] ?? '');
$nama_pelanggan = $nama_pelanggan ?? '-';
$telepon = $telepon ?? '-';
$tanggal = $tanggal ?? '-';
$user_role = $user_role ?? Model::ROLE_KASIR;
$current_user_id = $current_user_id ?? 0;
$order = $order ?? [];
$items = $items ?? [];
$detailsByItem = $detailsByItem ?? [];
$payments = $payments ?? [];
$designs = $designs ?? [];
$history = $history ?? [];

$catatan = $order['project_name'] ?? '';
$statusOrder = $order['order_status'] ?? Model::ORDER_STATUS_PENDING_PAYMENT;
$grandTotal = (float) ($order['grand_total'] ?? 0);
$orderSubtotal = (float) ($order['subtotal'] ?? 0);

$payments = $payments ?? [];
$paymentSummary = $payment_summary ?? [];
$totalPaid = (float) ($paymentSummary['total_paid'] ?? 0);
$totalRefunded = (float) ($paymentSummary['total_refunded'] ?? 0);
if (empty($paymentSummary)) {
    foreach ($payments as $p) {
        if (($p['payment_status'] ?? '') === Model::PAYMENT_STATUS_PAID) {
            $totalPaid += (float) $p['amount'];
        } elseif (($p['payment_status'] ?? '') === Model::PAYMENT_STATUS_REFUNDED) {
            $totalRefunded += (float) $p['amount'];
        }
    }
}
$sisaTagihan = (float) ($paymentSummary['remaining_balance'] ?? max(0, $grandTotal - $totalPaid));
$paymentState = 'unpaid';
$paymentInfo = ['label' => 'Belum Bayar', 'class' => 'payment-status-badge--unpaid', 'icon' => 'fa-times-circle'];
if ($grandTotal > 0 && $totalPaid >= $grandTotal) {
    $paymentState = 'paid';
    $paymentInfo = ['label' => 'Lunas', 'class' => 'payment-status-badge--paid', 'icon' => 'fa-check-circle'];
} elseif ($totalPaid > 0) {
    $paymentState = 'partial';
    $paymentInfo = ['label' => 'Sebagian', 'class' => 'payment-status-badge--partial', 'icon' => 'fa-clock'];
}
$isOrderOwner = $user_role === Model::ROLE_OWNER || ($current_user_id > 0 && (int) ($order['user_id'] ?? 0) === $current_user_id);
$canRecordPayment = ($user_role === Model::ROLE_OWNER || ($user_role === Model::ROLE_KASIR && $isOrderOwner)) && $sisaTagihan > 0 && in_array($statusOrder, [Model::ORDER_STATUS_PENDING_PAYMENT, Model::ORDER_STATUS_CONFIRMED, Model::ORDER_STATUS_IN_PROGRESS, Model::ORDER_STATUS_READY], true);

$statusLabels = Model::ORDER_STATUS_MAP;
$statusInfo = $statusLabels[$statusOrder] ?? $statusLabels[Model::ORDER_STATUS_PENDING_PAYMENT];
$nextStatusActions = [
    Model::ORDER_STATUS_PENDING_PAYMENT => [Model::ORDER_STATUS_CONFIRMED, 'Konfirmasi', 'fa-check', [Model::ROLE_KASIR]],
    Model::ORDER_STATUS_CONFIRMED => [Model::ORDER_STATUS_IN_PROGRESS, 'Mulai Proses', 'fa-play', [Model::ROLE_OWNER]],
    Model::ORDER_STATUS_IN_PROGRESS => [Model::ORDER_STATUS_READY, 'Siap Diambil', 'fa-box-open', [Model::ROLE_OWNER]],
    Model::ORDER_STATUS_READY => [Model::ORDER_STATUS_COMPLETED, 'Selesai', 'fa-check-circle', [Model::ROLE_KASIR, Model::ROLE_OWNER]],
];
$currentStatusAction = $nextStatusActions[$statusOrder] ?? null;
$allowedStatusRoles = $currentStatusAction[3] ?? [];
$allowedStatusRoles = is_array($allowedStatusRoles) ? $allowedStatusRoles : [$allowedStatusRoles];
$canUpdateStatus = $currentStatusAction !== null && in_array($user_role, $allowedStatusRoles, true) && $isOrderOwner;

$categoryIcons = [];

$sizeData = [];
$totalProduksi = 0;
foreach ($items as $item) {
    $rincian = $item['rincian'] ?? [];
    if (is_string($rincian)) {
        $rincian = json_decode($rincian, true) ?? [];
    }
    foreach ($rincian as $sz => $qty) {
        $qty = (int) $qty;
        if ($qty > 0) {
            $sizeData[$sz] = ($sizeData[$sz] ?? 0) + $qty;
            $totalProduksi += $qty;
        }
    }
}

$phone = preg_replace('/[^0-9]/', '', $telepon);
$waBaseUrl = 'https://wa.me/';
$waCountryCode = '62';
$waLink = $phone ? $waBaseUrl . (str_starts_with($phone, '0') ? $waCountryCode . substr($phone, 1) : $phone) : '';

$pageTitle = 'Detail Pesanan #' . e($id_pesanan) . ' - ' . 'Siblings.co';
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
$sidebarRole = $sidebarRole ?? $user_role ?? Model::ROLE_KASIR;
$activeMenu = $activeMenu ?? 'status';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <!-- Header -->
            <div class="detail-header">
                <div class="detail-header__left">
                    <a href="<?= url('/transactions') ?>" class="btn-back">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Kembali
                    </a>
                    <h1 class="detail-header__title">
                        Pesanan #<?= e($id_pesanan) ?>
                    </h1>
                    <p class="detail-header__meta">
                        <span><i class="far fa-calendar-alt" aria-hidden="true"></i> <?= e($tanggal) ?></span>
                        <span><i class="fas fa-box" aria-hidden="true"></i> <?= count($items) ?> item &middot; <?= e($totalProduksi) ?> pcs</span>
                    </p>
                </div>
            </div>

            <!-- Info Bar -->
            <div class="detail-info-bar">
                <span class="detail-info-bar__item">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <?= e($nama_pelanggan) ?>
                    <?php if ($telepon !== '-'): ?>
                        <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="detail-info-bar__link" translate="no"><?= e($telepon) ?></a>
                    <?php endif; ?>
                </span>
                <span class="detail-info-bar__sep"></span>
                <span class="detail-info-bar__item">
                    <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
                    <strong class="tabular-nums"><?= e(format_currency($grandTotal)) ?></strong>
                </span>
                <span class="detail-info-bar__sep"></span>
                <span class="detail-info-bar__item detail-info-bar__item--status">
                    <span class="status-badge <?= e($statusInfo['class']) ?>">
                        <i class="fas <?= e($statusInfo['icon']) ?>" aria-hidden="true"></i>
                        <?= e($statusInfo['label']) ?>
                    </span>
                    <?php if ($canUpdateStatus): ?>
                        <button type="button" class="btn btn--primary btn--xs btn-update-status" data-status="<?= e($currentStatusAction[0]) ?>" data-label="<?= e($currentStatusAction[1]) ?>">
                            <i class="fas <?= e($currentStatusAction[2]) ?>"></i> <?= e($currentStatusAction[1]) ?>
                        </button>
                    <?php endif; ?>
                    <?php if ($user_role === Model::ROLE_OWNER && !in_array($statusOrder, [Model::ORDER_STATUS_COMPLETED, Model::ORDER_STATUS_CANCELLED], true)): ?>
                        <button type="button" id="cancelOrderBtn" class="btn btn--danger-soft btn--xs">
                            <i class="fas fa-ban"></i> Batalkan
                        </button>
                    <?php endif; ?>
                </span>
            </div>

            <!-- Index designs by order_item_id -->
            <?php
            $designsByItem = [];
foreach (($designs ?? []) as $d) {
    $designsByItem[(int) $d['order_item_id']][] = $d;
}
?>

            <!-- Items -->
            <?php foreach ($items as $idx => $item):
                $kategori = $item['product_name_snapshot'] ?: ($item['category_name'] ?? '-');
                $catIcon = $categoryIcons[$kategori] ?? 'fas fa-box';
                $bahan = $item['variant_name_snapshot'] ?: ($item['material'] ?? '-');
                $sablon = $item['sablon_name'] ?? '-';
                $itemQty = (int) ($item['quantity'] ?? 0);
                $itemHarga = (float) ($item['subtotal'] ?? 0);
                $itemCatatan = $item['item_notes'] ?? '';

                $itemDesigns = $designsByItem[(int) $item['order_item_id']] ?? [];

                $itemDetails = $detailsByItem[(int) $item['order_item_id']] ?? [];
                $uniqueColors = [];
                foreach ($itemDetails as $d) {
                    $c = $d['color_name'] ?? '-';
                    if ($c !== '-' && !in_array($c, $uniqueColors, true)) {
                        $uniqueColors[] = $c;
                    }
                }
                $warnaDisplay = !empty($uniqueColors) ? implode(', ', $uniqueColors) : '-';
                ?>
                <article class="detail-item-card">
                    <div class="detail-item-card__header">
                        <span class="detail-item-card__icon"><i class="<?= $catIcon ?>"></i></span>
                        <span class="detail-item-card__kategori"><?= e($kategori) ?></span>
                    </div>
                    <div class="detail-item-card__body">
                        <!-- Specs -->
                        <div class="detail-item-specs">
                            <div class="detail-item-spec">
                                <span class="detail-item-spec__label">Bahan</span>
                                <span class="detail-item-spec__value"><?= e($bahan) ?></span>
                            </div>
                            <div class="detail-item-spec">
                                <span class="detail-item-spec__label">Sablon</span>
                                <span class="detail-item-spec__value">
                                    <?php if ($sablon !== '-' && count($itemDesigns) > 0): ?>
                                        <?= e($sablon) ?> <small class="detail-item-spec__count">(<?= count($itemDesigns) ?> desain)</small>
                                    <?php else: ?>
                                        <?= e($sablon) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="detail-item-spec">
                                <span class="detail-item-spec__label">Warna</span>
                                <span class="detail-item-spec__value"><?= e($warnaDisplay) ?></span>
                            </div>
                        </div>

                        <!-- Rincian Harga (per-row table) -->
                        <?php if (!empty($itemDetails)): ?>
                            <div class="detail-item-rincian">
                                <span class="detail-item-rincian__label">Rincian Harga:</span>
                                <div class="detail-item-rincian__table-wrap">
                                    <table class="detail-item-rincian__table">
                                        <thead>
                                            <tr>
                                                <th>Size</th>
                                                <th>Qty</th>
                                                <th>Warna</th>
                                                <th>Tipe Lengan</th>
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
                                                $subtotal = $hargaFinal * $qty;
                                                ?>
                                                <tr>
                                                    <td class="detail-item-rincian__cell--size"><?= e($sz) ?></td>
                                                    <td class="detail-item-rincian__cell--qty tabular-nums"><?= e($qty) ?></td>
                                                    <td class="detail-item-rincian__cell--color"><?= e($color) ?></td>
                                                    <td class="detail-item-rincian__cell--lengan">
                                                        <?php if ($isLong): ?>
                                                            <span class="detail-item-rincian__badge">Lengan Panjang</span>
                                                        <?php else: ?>
                                                            <span class="detail-item-rincian__badge detail-item-rincian__badge--short">Lengan Pendek</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="detail-item-rincian__cell--stok">
                                                        <?php if ($readyQty > 0): ?>
                                                            <span class="detail-item-rincian__badge detail-item-rincian__badge--ready"><?= e($readyQty) ?> Ready</span>
                                                        <?php endif; ?>
                                                        <?php if ($customQty > 0): ?>
                                                            <span class="detail-item-rincian__badge detail-item-rincian__badge--buat"><?= e($customQty) ?> Buat</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="detail-item-rincian__cell--harga tabular-nums">
                                                        <?= e(format_currency($hargaFinal)) ?> &times; <?= e($qty) ?>
                                                        <?php if ($sablonPrice > 0): ?>
                                                            <small class="detail-item-rincian__sablon-breakdown">(<?= e(format_currency($hargaBase + $xxlSurcharge + $longSurcharge)) ?> + <?= e(format_currency($sablonPrice)) ?> sablon)</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="detail-item-rincian__cell--subtotal tabular-nums">
                                                        <?= e(format_currency($subtotal)) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="detail-item-rincian__footer-label">Total (<?= e($itemQty) ?> pcs)</td>
                                                <td class="detail-item-rincian__footer-total tabular-nums"><?= e(format_currency($itemHarga)) ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Design Gallery (per-item) -->
                        <?php if (!empty($itemDesigns)): ?>
                            <div class="detail-item-designs">
                                <span class="detail-item-designs__label"><i class="fas fa-image" aria-hidden="true"></i> Referensi Desain:</span>
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
                                                    <img src="<?= e($fileUrl) ?>" alt="Desain" loading="lazy">
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

                        <!-- Item note -->
                        <?php if ($itemCatatan): ?>
                            <div class="detail-item-catatan">
                                <i class="fas fa-sticky-note" aria-hidden="true"></i> <?= e($itemCatatan) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>

            <?php if ($statusOrder === Model::ORDER_STATUS_CANCELLED): ?>
                <article class="detail-card detail-card--danger">
                    <div class="detail-card__header"><i class="fas fa-ban" aria-hidden="true"></i> Informasi Pembatalan</div>
                    <div class="detail-card__body detail-cancel-info">
                        <div>
                            <span class="detail-cancel-info__label">Alasan Pembatalan</span>
                            <strong><?= e($order['cancellation_reason'] ?? '-') ?></strong>
                        </div>
                        <div>
                            <span class="detail-cancel-info__label">Dibatalkan Oleh</span>
                            <strong><?= e($order['cancelled_by_name'] ?? '-') ?></strong>
                        </div>
                        <?php if (!empty($order['cancelled_at'])): ?>
                            <div>
                                <span class="detail-cancel-info__label">Waktu Pembatalan</span>
                                <strong><?= e(format_date_id($order['cancelled_at'], true)) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endif; ?>

            <!-- Size Summary -->
            <?php if (!empty($sizeData)): ?>
                <div class="detail-size-summary">
                    <h3><i class="fas fa-th-list" aria-hidden="true"></i> Rincian Ukuran Total</h3>
                    <div class="detail-size-summary__grid">
                        <?php foreach ($sizeData as $sz => $qty): ?>
                            <div class="detail-size-summary__item">
                                <span class="detail-size-summary__size"><?= e($sz) ?></span>
                                <span class="detail-size-summary__qty tabular-nums"><?= e($qty) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="detail-size-summary__total">
                        <span>Total Produksi</span>
                        <span class="tabular-nums"><?= e($totalProduksi) ?> pcs</span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Payment Summary -->
            <div class="detail-payment-summary">
                <div class="detail-payment-summary__header">
                    <span><i class="fas fa-wallet" aria-hidden="true"></i> Status Pembayaran</span>
                    <span class="payment-status-badge <?= e($paymentInfo['class']) ?>">
                        <i class="fas <?= e($paymentInfo['icon']) ?>" aria-hidden="true"></i>
                        <?= e($paymentInfo['label']) ?>
                    </span>
                </div>
                <?php if ($orderSubtotal > 0): ?>
                <div class="detail-payment-summary__row">
                    <span>Subtotal Pesanan</span>
                    <span class="tabular-nums"><?= e(format_currency($orderSubtotal)) ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-payment-summary__row">
                    <span>Total Pesanan</span>
                    <span class="tabular-nums detail-payment-summary__total"><?= e(format_currency($grandTotal)) ?></span>
                </div>
                <div class="detail-payment-summary__row detail-payment-summary__row--paid">
                    <span><i class="fas fa-check-circle" aria-hidden="true"></i> Sudah Dibayar</span>
                    <span class="tabular-nums"><?= e(format_currency($totalPaid)) ?></span>
                </div>
                <?php if ($sisaTagihan > 0): ?>
                    <div class="detail-payment-summary__row detail-payment-summary__row--remaining">
                        <span><?= $paymentState === 'unpaid' ? 'Total yang Harus Dibayar' : 'Sisa Pembayaran' ?></span>
                        <span class="tabular-nums detail-payment-summary__total"><?= e(format_currency($sisaTagihan)) ?></span>
                    </div>
                <?php else: ?>
                    <div class="detail-payment-summary__row detail-payment-summary__row--lunas">
                        <span><i class="fas fa-check-double" aria-hidden="true"></i> Tagihan sudah lunas</span>
                        <span class="tabular-nums">Rp 0</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Status History -->
            <?php $history = $history ?? []; ?>
            <?php if (!empty($history)): ?>
                <article class="detail-card">
                    <div class="detail-card__header"><i class="fas fa-history" aria-hidden="true"></i> Riwayat Aktivitas Pesanan</div>
                    <div class="detail-card__body detail-card__body--flush">
                        <table class="detail-payment-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Dari</th>
                                    <th>Ke</th>
                                    <th>Oleh</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($history as $h):
                                $fromStatus = $h['from_status'] ?? '';
                                $toStatus = $h['to_status'] ?? '';
                                $isTransition = $fromStatus !== '' && $fromStatus !== $toStatus;
                                $isCreation = $fromStatus === '' && $toStatus !== '';
                                $rawNotes = (string) ($h['notes'] ?? '');
                                $cleanNotes = trim($rawNotes);
                                ?>
                                <tr>
                                    <td><?= e(format_date_id($h['changed_at'], true)) ?></td>
                                    <td><?= e($isTransition || $isCreation ? ($fromStatus !== '' ? ($statusLabels[$fromStatus]['label'] ?? $fromStatus) : '—') : '—') ?></td>
                                    <td><?= e($isTransition || $isCreation ? ($statusLabels[$toStatus]['label'] ?? $toStatus) : '—') ?></td>
                                    <td><?= e($h['changed_by_name'] ?? 'System') ?></td>
                                    <td><?= e($cleanNotes) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            <?php endif; ?>

            <!-- Payment History -->
            <?php if (!empty($payments)): ?>
                <article class="detail-card">
                    <div class="detail-card__header"><i class="fas fa-history" aria-hidden="true"></i> Riwayat Pembayaran</div>
                    <div class="detail-card__body detail-card__body--flush">
                        <table class="detail-payment-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Metode</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Diterima Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $p):
                                    $pStatus = $p['payment_status'] ?? Model::PAYMENT_STATUS_PAID;
                                    $paymentStatusMap = [
                                        Model::PAYMENT_STATUS_PAID => ['label' => 'Diterima', 'class' => 'badge--success', 'row' => ''],
                                        Model::PAYMENT_STATUS_VOID => ['label' => 'Dibatalkan', 'class' => 'badge--danger', 'row' => 'detail-payment-table__row--void'],
                                        Model::PAYMENT_STATUS_REFUNDED => ['label' => 'Dikembalikan', 'class' => 'badge--warning', 'row' => 'detail-payment-table__row--refunded'],
                                        Model::PAYMENT_STATUS_PENDING => ['label' => 'Menunggu', 'class' => 'badge--warning', 'row' => 'detail-payment-table__row--pending'],
                                    ];
                                    $pStatusInfo = $paymentStatusMap[$pStatus] ?? ['label' => ucfirst($pStatus), 'class' => 'badge--warning', 'row' => ''];
                                    $metodeLabel = Model::PAYMENT_METHODS[$p['payment_method'] ?? ''] ?? ($p['payment_method'] ?? '-');
                                    ?>
                                    <tr class="<?= e($pStatusInfo['row']) ?>">
                                        <td><?= e(format_date_id($p['payment_date'] ?? '', true)) ?></td>
                                        <td><?= e($metodeLabel) ?></td>
                                        <td class="tabular-nums"><?= e(format_currency($p['amount'] ?? 0)) ?></td>
                                        <td><span class="badge <?= e($pStatusInfo['class']) ?>"><?= e($pStatusInfo['label']) ?></span></td>
                                        <td><?= e($p['received_by_name'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $orderStatus = $order['order_status'] ?? '';
                                            $orderActive = $orderStatus !== Model::ORDER_STATUS_CANCELLED && $orderStatus !== Model::ORDER_STATUS_COMPLETED;
                                            ?>
                                            <?php if ($pStatus === Model::PAYMENT_STATUS_PAID && $orderActive && ($user_role === Model::ROLE_OWNER || ($user_role === Model::ROLE_KASIR && $isOrderOwner))): ?>
                                                <button type="button" class="btn btn--danger btn--sm btn-void-payment" data-payment-id="<?= (int) $p['payment_id'] ?>" data-amount="<?= e(format_currency($p['amount'] ?? 0)) ?>">
                                                    <i class="fas fa-ban" aria-hidden="true"></i> Void
                                                </button>
                                                <?php if ($user_role === Model::ROLE_OWNER): ?>
                                                <button type="button" class="btn btn--warning btn--sm btn-refund-payment" data-payment-id="<?= (int) $p['payment_id'] ?>" data-amount="<?= (float) ($p['amount'] ?? 0) ?>">
                                                    <i class="fas fa-undo" aria-hidden="true"></i> Refund
                                                </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            <?php endif; ?>

            <!-- Record Payment Form -->
            <?php if ($canRecordPayment): ?>
                <article class="detail-card detail-card--payment-form">
                    <div class="detail-card__header"><i class="fas fa-plus-circle" aria-hidden="true"></i> Catat Pembayaran</div>
                    <div class="detail-card__body">
                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert--danger"><?= e($_SESSION['error']) ?></div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        <?php if (!empty($_SESSION['success'])): ?>
                            <div class="alert alert--success"><?= e($_SESSION['success']) ?></div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        <form method="POST" action="<?= url('/transactions/record-payment') ?>" class="payment-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="order_id" value="<?= e($order['order_id'] ?? 0) ?>">
                            <div class="payment-form__row">
                                <div class="payment-form__field">
                                    <label for="payment_method">Metode Bayar</label>
                                    <select name="payment_method" id="payment_method" required data-no-custom>
                                        <?php foreach (Model::PAYMENT_METHODS as $val => $label): ?>
                                        <option value="<?= e($val) ?>"><?= e($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="payment-form__field">
                                    <label for="amount">Jumlah (Rp)</label>
                                    <input type="number" name="amount" id="amount" min="1" step="any"
                                           value="<?= e($sisaTagihan > 0 ? $sisaTagihan : '') ?>"
                                           placeholder="Masukkan jumlah" required>
                                </div>
                                <div class="payment-form__field">
                                    <label for="payment_date">Tanggal</label>
                                    <input type="date" name="payment_date" id="payment_date" value="<?= e(date('Y-m-d')) ?>" required>
                                </div>
                                <div class="payment-form__field payment-form__field--btn">
                                    <button type="submit" class="btn btn--primary">
                                        <i class="fas fa-save" aria-hidden="true"></i> Simpan
                                    </button>
                                </div>
                            </div>
                            <p class="payment-form__hint">
                                Sisa tagihan: <strong class="tabular-nums"><?= e(format_currency($sisaTagihan)) ?></strong>
                            </p>
                        </form>
                    </div>
                </article>
            <?php elseif ($sisaTagihan <= 0): ?>
                <article class="detail-card detail-card--payment-paid">
                    <div class="detail-card__header"><i class="fas fa-check-circle" aria-hidden="true"></i> Pembayaran Lunas</div>
                    <div class="detail-card__body">
                        <p class="payment-paid-note">Tagihan sudah lunas. Tidak ada pembayaran tambahan yang perlu dicatat.</p>
                    </div>
                </article>
            <?php endif; ?>

            <!-- Notes -->
            <?php if (!empty($catatan)): ?>
                <article class="detail-card">
                    <div class="detail-card__header"><i class="fas fa-sticky-note" aria-hidden="true"></i> Catatan Pesanan</div>
                    <div class="detail-card__body">
                        <pre class="nameset-area"><?= e($catatan) ?></pre>
                    </div>
                </article>
            <?php endif; ?>

            <!-- Actions -->
            <div class="action-footer">
                <a href="<?= url('/transactions') ?>" class="btn btn--ghost">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Kembali
                </a>
                <a href="<?= url('/transactions/invoice?id=' . urlencode($id_pesanan)) ?>" class="btn btn--accent">
                    <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
                    Lihat Invoice
                </a>
            </div>
        </div>
    </main>
</div>

<!-- Edit Design Modal -->
<div id="editDesignModal" class="modal-overlay" style="display:none;">
    <div class="modal-panel" style="max-width:400px;">
        <div class="modal-panel__header">
            <h3>Edit Desain</h3>
            <button type="button" class="btn btn--ghost btn--sm modal-close-btn" aria-label="Tutup">&times;</button>
        </div>
        <form id="editDesignForm" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="design_id" id="editDesignId">
            <div class="modal-panel__body">
                <div class="form-field">
                    <label class="form-field__label" for="editDesignNote">Keterangan</label>
                    <input type="text" id="editDesignNote" name="notes" class="form-control" placeholder="cth: Logo depan" autocomplete="off">
                </div>
                <div class="form-field">
                    <label class="form-field__label" for="editDesignFile">Ganti File (opsional)</label>
                    <input type="file" id="editDesignFile" name="file" accept="image/png,image/jpeg,image/webp,application/pdf">
                    <small class="form-field__hint" id="editDesignCurrent"></small>
                </div>
            </div>
            <div class="modal-panel__footer">
                <button type="button" class="btn btn--ghost modal-close-btn">Batal</button>
                <button type="submit" class="btn btn--primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    document.querySelectorAll('.btn-update-status').forEach(btn => {
        btn.addEventListener('click', async () => {
            const status = btn.dataset.status;
            const label = btn.dataset.label || 'ubah status';
            if (!confirm(`${label} pesanan ini?`)) return;

            btn.disabled = true;
            try {
                const resp = await fetch('<?= url('/transactions/update-status') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: `order_id=<?= e($order['order_id'] ?? 0) ?>&order_status=${encodeURIComponent(status)}`
                });
                const data = await resp.json();
                if (data.success) {
                    window.SiblingsUI?.toast?.('Status pesanan diperbarui.', 'success');
                    location.reload();
                } else {
                    window.SiblingsUI?.toast?.(data.message || 'Gagal mengubah status.', 'danger');
                    btn.disabled = false;
                }
            } catch {
                window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
                btn.disabled = false;
            }
        });
    });

    // Cancel order (owner only)
    document.getElementById('cancelOrderBtn')?.addEventListener('click', async () => {
        const reason = prompt('Alasan pembatalan:');
        if (!reason || !reason.trim()) return;
        if (!confirm('Batalkan pesanan ini?')) return;

        const btn = document.getElementById('cancelOrderBtn');
        btn.disabled = true;
        try {
            const resp = await fetch('<?= url('/transactions/update-status') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: `order_id=<?= e($order['order_id'] ?? 0) ?>&order_status=cancelled&reason=${encodeURIComponent(reason.trim())}`
            });
            const data = await resp.json();
            if (data.success) {
                window.SiblingsUI?.toast?.('Pesanan dibatalkan.', 'success');
                location.reload();
            } else {
                window.SiblingsUI?.toast?.(data.message || 'Gagal membatalkan.', 'danger');
                btn.disabled = false;
            }
        } catch {
            window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
            btn.disabled = false;
        }
    });

    // Void payment
    document.querySelectorAll('.btn-void-payment').forEach(btn => {
        btn.addEventListener('click', async () => {
            const paymentId = btn.dataset.paymentId;
            const amount = btn.dataset.amount;
            if (!confirm(`Void pembayaran ${amount}? Tindakan ini tidak bisa dibatalkan.`)) return;

            const reason = prompt('Alasan void (minimal 5 karakter):');
            if (reason === null) return;
            if (reason.trim().length < 5) {
                window.SiblingsUI?.toast?.('Alasan void wajib diisi minimal 5 karakter.', 'danger');
                return;
            }

            btn.disabled = true;
            try {
                const resp = await fetch('<?= url('/transactions/void-payment') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: `payment_id=${paymentId}&reason=${encodeURIComponent(reason)}`
                });
                const data = await resp.json();
                if (data.success) {
                    window.SiblingsUI?.toast?.('Payment berhasil di-void.', 'success');
                    location.reload();
                } else {
                    window.SiblingsUI?.toast?.(data.message || 'Gagal void payment.', 'danger');
                    btn.disabled = false;
                }
            } catch {
                window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
                btn.disabled = false;
            }
        });
    });

    // Refund payment
    document.querySelectorAll('.btn-refund-payment').forEach(btn => {
        btn.addEventListener('click', () => {
            const paymentId = btn.dataset.paymentId;
            const maxAmount = parseFloat(btn.dataset.amount);
            const reason = prompt('Alasan refund (minimal 10 karakter):');
            if (reason === null) return;
            if (reason.trim().length < 10) {
                window.SiblingsUI?.toast?.('Alasan refund wajib diisi minimal 10 karakter.', 'danger');
                return;
            }
            const amountStr = prompt(`Jumlah refund (maks ${btn.dataset.amount}):`, maxAmount);
            if (!amountStr) return;
            const amount = parseFloat(amountStr);
            if (isNaN(amount) || amount <= 0 || amount > maxAmount) {
                window.SiblingsUI?.toast?.('Jumlah refund tidak valid.', 'danger');
                return;
            }

            btn.disabled = true;
            fetch('<?= url('/transactions/refund-payment') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: `payment_id=${paymentId}&amount=${amount}&reason=${encodeURIComponent(reason)}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.SiblingsUI?.toast?.('Refund berhasil diproses.', 'success');
                    location.reload();
                } else {
                    window.SiblingsUI?.toast?.(data.message || 'Gagal proses refund.', 'danger');
                    btn.disabled = false;
                }
            })
            .catch(() => {
                window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });

    // Edit design file
    const editModal = document.getElementById('editDesignModal');
    const editForm = document.getElementById('editDesignForm');

    document.querySelectorAll('.edit-design-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('editDesignId').value = btn.dataset.designId;
            document.getElementById('editDesignNote').value = btn.dataset.note || '';
            document.getElementById('editDesignCurrent').textContent = 'File saat ini: ' + (btn.dataset.filename || '-');
            document.getElementById('editDesignFile').value = '';
            editModal.style.display = 'flex';
        });
    });

    editModal.querySelectorAll('.modal-close-btn').forEach(b => {
        b.addEventListener('click', () => { editModal.style.display = 'none'; });
    });
    editModal.addEventListener('click', (e) => {
        if (e.target === editModal) editModal.style.display = 'none';
    });

    editForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = editForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        const fd = new FormData(editForm);
        try {
            const resp = await fetch('<?= url('/transactions/update-design') ?>', {
                method: 'POST',
                headers: { 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                body: fd
            });
            const data = await resp.json();
            if (data.success) {
                window.SiblingsUI?.toast?.('Desain berhasil diupdate.', 'success');
                location.reload();
            } else {
                window.SiblingsUI?.toast?.(data.message || 'Gagal update.', 'danger');
                submitBtn.disabled = false;
            }
        } catch {
            window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
            submitBtn.disabled = false;
        }
    });

    // Delete design file
    document.querySelectorAll('.delete-design-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Hapus file desain ini?')) return;
            const id = btn.dataset.designId;
            try {
                const resp = await fetch('<?= url('/transactions/delete-design') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: `design_id=${id}`
                });
                const data = await resp.json();
                if (data.success) {
                    btn.closest('.design-gallery__item-wrap').remove();
                    window.SiblingsUI?.toast?.('File desain berhasil dihapus.', 'success');
                } else {
                    window.SiblingsUI?.toast?.(data.message || 'Gagal menghapus.', 'danger');
                }
            } catch {
                window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
            }
        });
    });

</script>
</body>
</html>
