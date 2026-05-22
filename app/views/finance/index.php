<?php
$pageTitle = 'Laporan Penjualan - Siblings.co';
$pageStyles = ['transactions.css', 'finance.css'];

$paymentStates = $paymentStates ?? [];
$financialErrors = $financialErrors ?? [];
$financialNotice = $financialNotice ?? null;
$oldInput = $oldInput ?? [];

$totalSales = 0.0;
$totalQty = 0;
$totalTransactions = 0;
foreach ($paymentStates as $row) {
    $totalSales += (float) ($row['grand_total'] ?? 0);
    $totalQty += (int) ($row['total_qty'] ?? 0);
    $totalTransactions++;
}

$statusLabels = [
    'unpaid'   => 'Belum Bayar',
    'partial'  => 'Sebagian',
    'paid'     => 'Lunas',
    'overpaid' => 'Lebih Bayar',
];
$statusClasses = [
    'unpaid'   => 'status-pending',
    'partial'  => 'status-partial',
    'paid'     => 'status-selesai',
    'overpaid' => 'status-overpaid',
];
$statusIcons = [
    'unpaid'   => 'fas fa-clock',
    'partial'  => 'fas fa-hourglass-half',
    'paid'     => 'fas fa-check',
    'overpaid' => 'fas fa-exclamation-triangle',
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
    $sidebarRole = 'owner';
    $activeMenu = 'finance';
    include __DIR__ . '/../layouts/sidebar.php';
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo header-photo--finance" aria-hidden="true"></div>

        <div class="app-content">
            <h1>Laporan Penjualan</h1>

            <?php if (!empty($financialErrors)): ?>
                <div class="alert alert--danger" role="alert">
                    <strong>Aksi keuangan gagal.</strong>
                    <ul>
                        <?php foreach ($financialErrors as $message): ?>
                            <li><?= e($message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($financialNotice)): ?>
                <div class="alert alert--success" role="status">
                    <?= e($financialNotice) ?>
                </div>
            <?php endif; ?>

            <section class="finance-stats" aria-label="Ringkasan penjualan">
                <div class="stat-card">
                    <span class="stat-card__icon"><i class="fas fa-shopping-cart" aria-hidden="true"></i></span>
                    <div>
                        <p class="stat-card__label">Total Penjualan</p>
                        <h3 class="stat-card__value tabular-nums"><?= e(format_currency($totalSales)) ?></h3>
                    </div>
                </div>
                <div class="stat-card stat-card--highlight">
                    <span class="stat-card__icon"><i class="fas fa-box" aria-hidden="true"></i></span>
                    <div>
                        <p class="stat-card__label">Total Produk Terjual</p>
                        <h3 class="stat-card__value tabular-nums"><?= (int) $totalQty ?>&nbsp;pcs</h3>
                    </div>
                </div>
                <div class="stat-card">
                    <span class="stat-card__icon"><i class="fas fa-receipt" aria-hidden="true"></i></span>
                    <div>
                        <p class="stat-card__label">Jumlah Transaksi</p>
                        <h3 class="stat-card__value tabular-nums"><?= (int) $totalTransactions ?>&nbsp;Pesanan</h3>
                    </div>
                </div>
            </section>

            <form class="filter-section" method="get" action="<?= url('/finance') ?>">
                <label for="financeMonth"><i class="fas fa-filter" aria-hidden="true"></i> Filter:</label>
                <select id="financeMonth" name="month">
                    <option value="">Semua Bulan</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                <label class="sr-only" for="financeFrom">Dari tanggal</label>
                <input id="financeFrom" type="date" name="from">

                <label class="sr-only" for="financeTo">Sampai tanggal</label>
                <input id="financeTo" type="date" name="to">

                <button type="submit" class="btn btn--primary">Terapkan</button>
            </form>

            <div class="finance-toolbar">
                <h2>Detail Transaksi Penjualan</h2>
                <div class="finance-tools">
                    <div class="finance-search">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <label class="sr-only" for="financeSearch">Cari pesanan</label>
                        <input id="financeSearch" type="search"
                               placeholder="Cari pesanan…"
                               autocomplete="off"
                               spellcheck="false">
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Nomor Pesanan</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Total Penjualan</th>
                            <th scope="col">Total Dibayar</th>
                            <th scope="col">Sisa / Lebih Bayar</th>
                            <th scope="col">Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody id="financeBody">
                        <?php if (!empty($paymentStates)): ?>
                            <?php foreach ($paymentStates as $index => $row):
                                $state = (string) ($row['payment_state'] ?? 'unpaid');
                                $remaining = (float) ($row['remaining_balance'] ?? 0);
                                $balanceText = $state === 'overpaid'
                                    ? 'Lebih ' . format_currency(abs($remaining))
                                    : format_currency(max(0, $remaining));
                            ?>
                                <tr data-search="<?= e(strtolower(($row['order_code'] ?? '') . ' ' . ($row['customer_name'] ?? ''))) ?>">
                                    <td class="tabular-nums"><?= $index + 1 ?></td>
                                    <td><span class="date-pill"><?= e(format_date_id($row['tanggal_order'] ?? null)) ?></span></td>
                                    <td translate="no"><?= e($row['order_code']) ?></td>
                                    <td><?= e($row['customer_name']) ?></td>
                                    <td class="tabular-nums"><?= (int) ($row['total_qty'] ?? 0) ?>&nbsp;pcs</td>
                                    <td class="tabular-nums"><?= e(format_currency($row['grand_total'])) ?></td>
                                    <td class="tabular-nums"><?= e(format_currency($row['total_paid'])) ?></td>
                                    <td class="tabular-nums <?= $state === 'overpaid' ? 'amount-danger' : '' ?>"><?= e($balanceText) ?></td>
                                    <td>
                                        <span class="payment-status-pill <?= e($statusClasses[$state] ?? 'status-pending') ?>">
                                            <i class="<?= e($statusIcons[$state] ?? 'fas fa-clock') ?>" aria-hidden="true"></i>
                                            <?= e($statusLabels[$state] ?? ucfirst($state)) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state empty-state--inline">
                                        <i class="fas fa-chart-line empty-state__icon" aria-hidden="true"></i>
                                        <h2 class="empty-state__title">Belum ada data transaksi</h2>
                                        <p class="empty-state__desc">Transaksi yang sudah selesai akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        const search = document.getElementById('financeSearch');
        const tbody = document.getElementById('financeBody');
        if (!search || !tbody) return;

        search.addEventListener('input', (e) => {
            const q = (e.target.value || '').toLowerCase().trim();
            tbody.querySelectorAll('tr[data-search]').forEach((row) => {
                row.style.display = !q || row.dataset.search.includes(q) ? '' : 'none';
            });
        });
    })();
</script>
</body>
</html>
