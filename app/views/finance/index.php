<?php
$pageTitle = 'Laporan Penjualan - Siblings.co';
$pageStyles = ['transactions.css', 'finance.css'];

$paymentStates = $paymentStates ?? [];
$reportSummary = $reportSummary ?? [];
$filterFrom     = $filterFrom ?? '';
$filterTo       = $filterTo ?? '';
$filterCategory = $filterCategory ?? '';
$categories     = $categories ?? [];

$orderLabels = array_map(fn($s) => $s['label'], Model::ORDER_STATUS_MAP);
$orderClasses = array_map(fn($s) => $s['class'], Model::ORDER_STATUS_MAP);
$orderIcons = array_map(fn($s) => $s['icon'], Model::ORDER_STATUS_MAP);

$totalSales = (float) ($reportSummary['total_revenue'] ?? 0);
$totalQty = 0;
$totalTransactions = (int) ($reportSummary['order_count'] ?? 0);
$outstanding = (float) ($reportSummary['total_receivables'] ?? 0);
$averageOrderValue = (float) ($reportSummary['average_order_value'] ?? 0);
$statusCounts = array_fill_keys(array_keys($orderLabels), 0);
foreach ($paymentStates as $row) {
    $totalQty += (int) ($row['total_qty'] ?? 0);
    $os = $row['order_status'] ?? '';
    if (isset($statusCounts[$os])) {
        $statusCounts[$os]++;
    }
}

$payLabels = [
    Model::PAYMENT_STATE_UNPAID   => 'Belum Bayar',
    Model::PAYMENT_STATE_PARTIAL  => 'Sebagian',
    Model::PAYMENT_STATE_PAID    => 'Lunas',
    Model::PAYMENT_STATE_OVERPAID => 'Lebih Bayar',
];
$payClasses = [
    Model::PAYMENT_STATE_UNPAID   => 'status-pending',
    Model::PAYMENT_STATE_PARTIAL  => 'status-partial',
    Model::PAYMENT_STATE_PAID    => 'status-selesai',
    Model::PAYMENT_STATE_OVERPAID => 'status-overpaid',
];
$payIcons = [
    Model::PAYMENT_STATE_UNPAID   => 'fas fa-clock',
    Model::PAYMENT_STATE_PARTIAL  => 'fas fa-hourglass-half',
    Model::PAYMENT_STATE_PAID    => 'fas fa-check',
    Model::PAYMENT_STATE_OVERPAID => 'fas fa-exclamation-triangle',
];
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
    $sidebarRole = Model::ROLE_OWNER;
    $activeMenu = 'finance';
    include __DIR__ . '/../layouts/sidebar.php';
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo header-photo--finance" aria-hidden="true"></div>

        <div class="app-content finance-page">
            <h1>Laporan Penjualan</h1>

            <section class="finance-stats" aria-label="Ringkasan penjualan">
                <div class="finance-stat">
                    <span class="finance-stat__icon"><i class="fas fa-shopping-cart" aria-hidden="true"></i></span>
                    <div class="finance-stat__content">
                        <span class="finance-stat__label" title="Uang masuk berdasarkan tanggal pembayaran pada periode filter.">Pendapatan Diterima</span>
                        <strong class="finance-stat__value"><?= e(format_currency($totalSales)) ?></strong>
                    </div>
                </div>
                <div class="finance-stat">
                    <span class="finance-stat__icon"><i class="fas fa-receipt" aria-hidden="true"></i></span>
                    <div class="finance-stat__content">
                        <span class="finance-stat__label" title="Jumlah pesanan berdasarkan tanggal order pada periode filter.">Pesanan Dibuat</span>
                        <strong class="finance-stat__value"><?= (int) $totalTransactions ?></strong>
                    </div>
                </div>
                <div class="finance-stat">
                    <span class="finance-stat__icon"><i class="fas fa-chart-line" aria-hidden="true"></i></span>
                    <div class="finance-stat__content">
                        <span class="finance-stat__label" title="Rata-rata nilai order berdasarkan tanggal order pada periode filter.">Rata-rata Nilai Order</span>
                        <strong class="finance-stat__value"><?= e(format_currency($averageOrderValue)) ?></strong>
                    </div>
                </div>
                <div class="finance-stat finance-stat--danger">
                    <span class="finance-stat__icon"><i class="fas fa-exclamation-circle" aria-hidden="true"></i></span>
                    <div class="finance-stat__content">
                        <span class="finance-stat__label" title="Sisa tagihan dari pesanan yang dibuat pada periode filter.">Tagihan Belum Lunas</span>
                        <strong class="finance-stat__value"><?= e(format_currency($outstanding)) ?></strong>
                    </div>
                </div>
            </section>

            <div class="finance-controls">
                <div class="finance-controls__top">
                    <form class="finance-controls__filters" method="get" action="<?= url('/finance') ?>">
                        <input type="date" name="from" value="<?= e($filterFrom) ?>" aria-label="Dari tanggal">
                        <input type="date" name="to" value="<?= e($filterTo) ?>" aria-label="Sampai tanggal">
                        <select name="category" aria-label="Kategori">
                            <option value="">Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat['category_name']) ?>" <?= $filterCategory === $cat['category_name'] ? 'selected' : '' ?>><?= e($cat['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn--primary btn--sm"><i class="fas fa-filter"></i></button>
                    </form>
                    <div class="finance-controls__search">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <label class="sr-only" for="financeSearch">Cari pesanan</label>
                        <input id="financeSearch" type="search" placeholder="Cari…"
                               autocomplete="off" spellcheck="false">
                    </div>
                </div>
                <div class="finance-controls__tabs" role="tablist" aria-label="Filter status order">
                    <button type="button" class="filter-tab is-active" role="tab" aria-selected="true" data-filter="semua">
                        Semua <span class="filter-tab__count"><?= $totalTransactions ?></span>
                    </button>
                    <?php foreach ($orderLabels as $key => $label): ?>
                        <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="<?= e($key) ?>">
                            <?= e($label) ?> <span class="filter-tab__count"><?= $statusCounts[$key] ?? 0 ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Desktop: Table -->
            <div class="finance-table-panel">
                <div class="finance-table-wrap">
                    <table class="finance-table">
                        <caption class="sr-only">Detail Transaksi Penjualan</caption>
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Pesanan</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="col-right">Qty</th>
                                <th scope="col" class="col-right">Total</th>
                                <th scope="col" class="col-right">Dibayar</th>
                                <th scope="col" class="col-right">Sisa</th>
                                <th scope="col">Bayar</th>
                            </tr>
                        </thead>
                        <tbody id="financeBody">
                            <?php if (!empty($paymentStates)): ?>
                                <?php foreach ($paymentStates as $index => $row):
                                    $state = (string) ($row['payment_state'] ?? Model::PAYMENT_STATE_UNPAID);
                                    $remaining = (float) ($row['remaining_balance'] ?? 0);
                                    $balanceText = $state === Model::PAYMENT_STATE_OVERPAID
                                        ? 'Lebih ' . format_currency(abs($remaining))
                                        : format_currency(max(0, $remaining));
                                ?>
                                    <tr data-search="<?= e(strtolower(($row['order_code'] ?? '') . ' ' . ($row['customer_name'] ?? ''))) ?>"
                                        data-status-order="<?= e($row['order_status'] ?? '') ?>"
                                        onclick="window.location='<?= url('/transactions/detail?id=' . urlencode($row['order_code'])) ?>'">
                                        <td><span class="date-pill"><?= e(format_date_id($row['order_date'] ?? null)) ?></span></td>
                                        <td translate="no"><a href="<?= url('/transactions/detail?id=' . urlencode($row['order_code'])) ?>"><?= e($row['order_code']) ?></a></td>
                                        <td><?= e($row['customer_name']) ?></td>
                                        <td>
                                            <?php $oStatus = $row['order_status'] ?? ''; ?>
                                            <span class="order-status-pill <?= e($orderClasses[$oStatus] ?? 'status-pending') ?>">
                                                <?= e($orderLabels[$oStatus] ?? ucfirst($oStatus)) ?>
                                            </span>
                                        </td>
                                        <td class="col-right tabular-nums"><?= (int) ($row['total_qty'] ?? 0) ?></td>
                                        <td class="col-right tabular-nums"><?= e(format_currency($row['grand_total'])) ?></td>
                                        <td class="col-right tabular-nums"><?= e(format_currency($row['total_paid'])) ?></td>
                                        <td class="col-right tabular-nums <?= $state === Model::PAYMENT_STATE_OVERPAID ? 'amount-danger' : '' ?>"><?= e($balanceText) ?></td>
                                        <td>
                                            <span class="payment-status-pill <?= e($payClasses[$state] ?? 'status-pending') ?>">
                                                <?= e($payLabels[$state] ?? ucfirst($state)) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">
                                        <div class="finance-empty">
                                            <i class="fas fa-chart-line" aria-hidden="true"></i>
                                            <p>Belum ada data transaksi</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile: Card list -->
            <div class="finance-card-list" id="financeCardList">
                <?php if (!empty($paymentStates)): ?>
                    <?php foreach ($paymentStates as $index => $row):
                        $state = (string) ($row['payment_state'] ?? Model::PAYMENT_STATE_UNPAID);
                        $remaining = (float) ($row['remaining_balance'] ?? 0);
                        $balanceText = $state === Model::PAYMENT_STATE_OVERPAID
                            ? 'Lebih ' . format_currency(abs($remaining))
                            : format_currency(max(0, $remaining));
                    ?>
                        <?php $oStatus = $row['order_status'] ?? ''; ?>
                        <a class="finance-card" data-search="<?= e(strtolower(($row['order_code'] ?? '') . ' ' . ($row['customer_name'] ?? ''))) ?>"
                           data-status-order="<?= e($oStatus) ?>"
                           href="<?= url('/transactions/detail?id=' . urlencode($row['order_code'])) ?>">
                            <div class="finance-card__header">
                                <div>
                                    <span class="finance-card__code" translate="no"><?= e($row['order_code']) ?></span>
                                    <span class="finance-card__customer"><?= e($row['customer_name']) ?></span>
                                </div>
                                <div class="finance-card__pills">
                                    <span class="order-status-pill <?= e($orderClasses[$oStatus] ?? 'status-pending') ?>">
                                        <?= e($orderLabels[$oStatus] ?? ucfirst($oStatus)) ?>
                                    </span>
                                    <span class="payment-status-pill <?= e($payClasses[$state] ?? 'status-pending') ?>">
                                        <?= e($payLabels[$state] ?? ucfirst($state)) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="finance-card__footer">
                                <span class="finance-card__date"><?= e(format_date_id($row['order_date'] ?? null)) ?></span>
                                <div class="finance-card__amounts">
                                    <span class="finance-card__total tabular-nums"><?= e(format_currency($row['grand_total'])) ?></span>
                                    <?php if ($state !== Model::PAYMENT_STATE_PAID): ?>
                                        <span class="finance-card__balance <?= $state === Model::PAYMENT_STATE_OVERPAID ? 'amount-danger' : '' ?> tabular-nums"><?= e($balanceText) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state empty-state--inline">
                        <i class="fas fa-chart-line empty-state__icon" aria-hidden="true"></i>
                        <p class="empty-state__title">Belum ada data transaksi</p>
                        <p class="empty-state__desc">Transaksi yang sudah selesai akan muncul di sini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        const search = document.getElementById('financeSearch');
        const tbody = document.getElementById('financeBody');
        const cardList = document.getElementById('financeCardList');
        const tabs = document.querySelectorAll('.filter-tab');
        let activeFilter = 'semua';
        let activeQuery = '';

        function apply() {
            const show = (el) => {
                const matchesStatus = activeFilter === 'semua' || el.dataset.statusOrder === activeFilter;
                const matchesQuery = !activeQuery || (el.dataset.search || '').includes(activeQuery);
                el.style.display = matchesStatus && matchesQuery ? '' : 'none';
            };
            if (tbody) tbody.querySelectorAll('tr[data-search]').forEach(show);
            if (cardList) cardList.querySelectorAll('.finance-card[data-search]').forEach(show);
        }

        // Status tab filter
        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabs.forEach((t) => { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');
                activeFilter = tab.dataset.filter || 'semua';
                apply();
            });
        });

        // Search filter
        if (search) {
            search.addEventListener('input', (e) => {
                activeQuery = (e.target.value || '').toLowerCase().trim();
                apply();
            });
        }

        // Fade gradient hint — show when table is scrollable
        const tableWrap = document.querySelector('.finance-table-wrap');
        if (tableWrap) {
            const checkScroll = () => {
                const isScrollable = tableWrap.scrollWidth > tableWrap.clientWidth;
                tableWrap.classList.toggle('is-scrollable', isScrollable);
            };
            checkScroll();
            window.addEventListener('resize', checkScroll);
        }
    })();
</script>
</body>
</html>
