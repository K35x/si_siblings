<?php
$pageTitle = 'Dashboard Kasir - Siblings.co';
$pageStyles = ['dashboard.css'];

$queue = $queue ?? [
    ['id' => 'INV001', 'customer' => 'Ahmad', 'status' => 'process', 'label' => 'Proses'],
    ['id' => 'INV002', 'customer' => 'Andi',  'status' => 'process', 'label' => 'Proses'],
    ['id' => 'INV003', 'customer' => 'Nisa',  'status' => 'done',    'label' => 'Selesai'],
];

$stats = $stats ?? [
    ['label' => 'Pesanan Baru',      'value' => 12],
    ['label' => 'Sedang Diproses',   'value' => 4],
    ['label' => 'Siap Diambil',      'value' => 8],
    ['label' => 'Belum Lunas',       'value' => 15],
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
    $sidebarRole = 'kasir';
    $activeMenu = 'dashboard';
    include __DIR__ . '/sidebar.php';
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <h1>Dashboard Kasir</h1>

            <section class="dashboard-stats" aria-label="Statistik pesanan">
                <?php foreach ($stats as $stat): ?>
                    <div class="dashboard-stat">
                        <span class="dashboard-stat__value"><?= e($stat['value']) ?></span>
                        <span class="dashboard-stat__label"><?= e($stat['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </section>

            <section class="dashboard-grid dashboard-grid--cashier" aria-label="Pesanan dan antrian">
                <!-- TAMBAH PESANAN -->
                <div class="panel add-order-card">
                    <div class="add-icon" aria-hidden="true">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h3>Tambah Pesanan</h3>
                    <p>Buat pesanan baru untuk pelanggan dengan cepat.</p>
                    <a href="<?= url('/transactions/create') ?>" class="btn btn--primary mt-4">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Buat Pesanan
                    </a>
                </div>

                <!-- ANTRIAN PESANAN -->
                <div class="panel">
                    <div class="panel__header">
                        <h3 class="panel__title">Antrian Pesanan</h3>
                        <label class="sr-only" for="filterStatus">Filter status pesanan</label>
                        <select id="filterStatus" class="queue-filter">
                            <option value="">Semua status</option>
                            <option value="process">Proses</option>
                            <option value="done">Selesai</option>
                        </select>
                    </div>
                    <div class="panel__body scroll-thin">
                        <ul class="queue-list" id="queueList">
                            <?php foreach ($queue as $order): ?>
                                <li class="queue-item" data-status="<?= e($order['status']) ?>">
                                    <div>
                                        <span class="queue-item__id">#<?= e($order['id']) ?></span>
                                        <span class="queue-item__customer"><?= e($order['customer']) ?></span>
                                    </div>
                                    <span class="badge <?= $order['status'] === 'done' ? 'badge--success' : 'badge--warning' ?>">
                                        <?= e($order['label']) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        const filter = document.getElementById('filterStatus');
        const list = document.getElementById('queueList');
        if (!filter || !list) return;

        filter.addEventListener('change', function () {
            const value = this.value;
            list.querySelectorAll('.queue-item').forEach((item) => {
                const matches = !value || item.dataset.status === value;
                item.style.display = matches ? '' : 'none';
            });
        });
    })();
</script>
</body>
</html>
