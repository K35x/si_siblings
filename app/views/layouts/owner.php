<?php
$pageTitle = 'Dashboard Owner - ' . "Siblings.co";
$pageStyles = ['dashboard.css'];

$stats = $stats ?? [];
$salesByCategory = $salesByCategory ?? [];
$salesPeriodTotal = (int) ($salesPeriodTotal ?? 0);
$recentOrders = $recentOrders ?? [];

$statIcons = [
    'Pesanan Baru'    => 'fa-bag-shopping',
    'Sedang Diproses' => 'fa-shirt',
    'Siap Diambil'    => 'fa-box-open',
    'Omzet Hari Ini'  => 'fa-wallet',
];

$statusLabels = Model::ORDER_STATUS_MAP;

$chartLabels = array_column($salesByCategory, 'label');
$chartData = array_map('intval', array_column($salesByCategory, 'total'));
$paymentLabels = [
    Model::PAYMENT_STATE_UNPAID => ['label' => 'Belum Bayar', 'class' => 'badge--danger'],
    Model::PAYMENT_STATE_PARTIAL => ['label' => 'Sebagian', 'class' => 'badge--warning'],
    Model::PAYMENT_STATE_PAID => ['label' => 'Lunas', 'class' => 'badge--success'],
    Model::PAYMENT_STATE_OVERPAID => ['label' => 'Lebih Bayar', 'class' => 'badge--info'],
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
    $activeMenu = 'dashboard';
    include __DIR__ . '/sidebar.php';
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content app-content--fit dashboard-owner-page">
            <h1 class="dashboard-owner-page__title">Dashboard Owner</h1>

            <section class="dashboard-stats" aria-label="Statistik">
                <?php foreach ($stats as $index => $stat): ?>
                    <?php $icon = $statIcons[$stat['label']] ?? 'fa-chart-line'; ?>
                    <article class="dashboard-stat dashboard-stat--<?= e((string) (($index % 4) + 1)) ?>">
                        <span class="dashboard-stat__icon" aria-hidden="true"><i class="fas <?= e($icon) ?>"></i></span>
                        <span class="dashboard-stat__content">
                            <span class="dashboard-stat__label"><?= e($stat['label']) ?></span>
                            <strong class="dashboard-stat__value"><?= e($stat['value']) ?></strong>
                        </span>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="dashboard-grid dashboard-grid--owner">
                <!-- RECENT ORDERS -->
                <div class="panel">
                    <div class="panel__header">
                        <h3 class="panel__title">
                            <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                            Pesanan Terbaru
                        </h3>
                        <a href="<?= url('/transactions?role=owner') ?>" class="panel__link">Lihat Semua</a>
                    </div>
                    <div class="panel__body scroll-thin">
                        <?php if (!empty($recentOrders)): ?>
                            <ul class="activity-list">
                                <?php foreach ($recentOrders as $o): ?>

                                    <li class="deadline-item">
                                        <span class="deadline-item__icon" aria-hidden="true">
                                            <i class="fas fa-receipt"></i>
                                        </span>
                                        <?php $paymentInfo = $paymentLabels[$o['payment_state'] ?? Model::PAYMENT_STATE_UNPAID] ?? $paymentLabels[Model::PAYMENT_STATE_UNPAID]; ?>
                                        <a class="deadline-item__info deadline-item__info--link" href="<?= url('/transactions/detail?id=' . urlencode($o['order_code'])) ?>">
                                            <h4><?= e($o['customer_name']) ?></h4>
                                            <p>#<?= e($o['order_code']) ?> &middot; <?= e(format_currency($o['grand_total'])) ?></p>
                                            <small>Status Pembayaran: <span class="badge <?= e($paymentInfo['class']) ?>"><?= e($paymentInfo['label']) ?></span></small>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p style="text-align:center;color:var(--color-text-muted);padding:var(--space-4);">Belum ada pesanan.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CHART -->
                <div class="panel chart-panel">
                    <div class="panel__header">
                        <h3 class="panel__title">
                            <i class="fas fa-chart-line" aria-hidden="true"></i>
                            Grafik Penjualan per Kategori (Bulanan)
                        </h3>
                    </div>
                    <div class="panel__body">
                        <div class="dashboard-sales-total">
                            <span>Total Item Terjual Bulan Ini</span>
                            <strong class="tabular-nums"><?= e(number_format($salesPeriodTotal, 0, ',', '.')) ?> pcs</strong>
                        </div>
                        <?php if (!empty($chartLabels)): ?>
                            <div class="dashboard-chart-wrap">
                                <canvas id="chart" aria-label="Grafik penjualan per kategori bulanan" role="img"></canvas>
                            </div>
                        <?php else: ?>
                            <p style="text-align:center;color:var(--color-text-muted);padding:var(--space-8);">Belum ada data penjualan per kategori.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<?php if (!empty($chartLabels)): ?>
<script src="<?= asset('js/vendor/chart.umd.min.js') ?>"></script>
<script>
    (function () {
        const ctx = document.getElementById('chart');
        if (!ctx || typeof Chart === 'undefined') return;

        const styles = getComputedStyle(document.documentElement);
        const brand = styles.getPropertyValue('--color-brand-700').trim() || '#4a3328';
        const grid = styles.getPropertyValue('--color-border-soft').trim() || '#f1e6db';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartLabels, JSON_HEX_TAG | JSON_HEX_AMP) ?>,
                datasets: [{
                    label: 'Item Terjual',
                    data: <?= json_encode($chartData, JSON_HEX_TAG | JSON_HEX_AMP) ?>,
                    backgroundColor: brand,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                    ? false
                    : { duration: 350 },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: grid }, ticks: { precision: 0, callback: value => new Intl.NumberFormat('id-ID').format(value) + ' pcs' } },
                    x: { grid: { display: false } }
                }
            }
        });
    })();
</script>
<?php endif; ?>
<script src="<?= asset('js/ui.js') ?>"></script>
</body>
</html>
