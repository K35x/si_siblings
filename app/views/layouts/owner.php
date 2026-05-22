<?php
$pageTitle = 'Dashboard Owner - Siblings.co';
$pageStyles = ['dashboard.css'];

$stats = $stats ?? [
    ['label' => 'Pesanan Baru',         'value' => 12],
    ['label' => 'Sedang Diproses',      'value' => 4],
    ['label' => 'Siap Diambil',         'value' => 8],
    ['label' => 'Total Omzet Hari Ini', 'value' => 'Rp 1.540.000'],
];

$deadlines = $deadlines ?? [
    ['order' => '#102 - Ahmad', 'label' => 'Hari ini',     'badge' => 'badge--danger'],
    ['order' => '#103 - Andi',  'label' => 'Besok',        'badge' => 'badge--warning'],
    ['order' => '#104 - Nisa',  'label' => '2 hari lagi',  'badge' => 'badge--neutral'],
    ['order' => '#105 - Alin',  'label' => '2 hari lagi',  'badge' => 'badge--neutral'],
    ['order' => '#106 - Anto',  'label' => '3 hari lagi',  'badge' => 'badge--neutral'],
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
    $activeMenu = 'dashboard';
    include __DIR__ . '/sidebar.php';
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content app-content--fit dashboard-owner-page">
            <h1 class="dashboard-owner-page__title">Dashboard Owner</h1>

            <section class="dashboard-stats" aria-label="Statistik harian">
                <?php foreach ($stats as $stat): ?>
                    <div class="dashboard-stat">
                        <span class="dashboard-stat__value"><?= e($stat['value']) ?></span>
                        <span class="dashboard-stat__label"><?= e($stat['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </section>

            <section class="dashboard-grid dashboard-grid--owner">
                <!-- DEADLINE PRODUKSI -->
                <div class="panel">
                    <div class="panel__header">
                        <h3 class="panel__title">
                            <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                            Deadline Produksi
                        </h3>
                    </div>
                    <div class="panel__body scroll-thin">
                        <ul class="activity-list">
                            <?php foreach ($deadlines as $item): ?>
                                <li class="deadline-item">
                                    <span class="deadline-item__icon" aria-hidden="true">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <div class="deadline-item__info">
                                        <h4><?= e($item['order']) ?></h4>
                                        <p>Deadline: <?= e($item['label']) ?></p>
                                    </div>
                                    <span class="badge <?= e($item['badge']) ?>">
                                        <i class="far fa-clock" aria-hidden="true"></i>
                                        <?= e($item['label']) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- STATISTIK PENJUALAN -->
                <div class="panel chart-panel">
                    <div class="panel__header">
                        <h3 class="panel__title">Statistik Penjualan</h3>
                    </div>
                    <div class="panel__body">
                        <canvas id="chart" aria-label="Grafik penjualan per kategori produk" role="img"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script src="<?= asset('js/vendor/chart.umd.min.js') ?>"></script>
<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        const ctx = document.getElementById('chart');
        if (!ctx || typeof Chart === 'undefined') return;

        const styles = getComputedStyle(document.documentElement);
        const brand = styles.getPropertyValue('--color-brand-700').trim() || '#4a3328';
        const grid = styles.getPropertyValue('--color-divider').trim()
            || styles.getPropertyValue('--color-border-soft').trim()
            || '#f1e6db';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jersey', 'PDH', 'Kaos', 'Kemeja'],
                datasets: [{
                    label: 'Penjualan (pcs)',
                    data: [8, 12, 16, 20],
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
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: grid }, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    })();
</script>
</body>
</html>
