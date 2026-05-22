<?php
$pageTitle = 'Status Pesanan - Siblings.co';
$pageStyles = ['transactions.css'];

$statusMap = [
    'pending'    => ['label' => 'Menunggu',        'class' => 'status-pending', 'group' => 'pending'],
    'processing' => ['label' => 'Proses Pesanan',  'class' => 'status-proses',  'group' => 'proses'],
    'done'       => ['label' => 'Selesai',         'class' => 'status-selesai', 'group' => 'selesai'],
    'cancelled'  => ['label' => 'Dibatalkan',      'class' => 'status-batal',   'group' => 'batal'],
];

$isOwner = ($sidebarRole ?? 'kasir') === 'owner';
$transactions = $transactions ?? [];
$escape = $escape ?? fn ($value) => e($value);
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
$activeMenu  = $activeMenu  ?? 'status';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <h1>Status Pesanan</h1>
            <p class="text-muted">Pantau seluruh pesanan, dari yang baru masuk sampai selesai diproses.</p>

            <section class="filter-wrapper" aria-label="Filter pesanan">
                <div class="filter-row filter-row--top">
                    <div class="search-wrapper">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <label class="sr-only" for="searchOrders">Cari pesanan</label>
                        <input id="searchOrders" type="search"
                               placeholder="Cari nama pelanggan atau ID pesanan…"
                               autocomplete="off"
                               spellcheck="false">
                    </div>

                    <?php if (!$isOwner): ?>
                        <a href="<?= url('/transactions/create') ?>" class="btn btn--primary">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            Buat Pesanan Baru
                        </a>
                    <?php endif; ?>
                </div>

                <div class="filter-row">
                    <div class="date-filter" role="group" aria-label="Rentang tanggal">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                        <label class="sr-only" for="dateFrom">Dari tanggal</label>
                        <input id="dateFrom" type="date">
                        <span aria-hidden="true">—</span>
                        <label class="sr-only" for="dateTo">Sampai tanggal</label>
                        <input id="dateTo" type="date">
                    </div>

                    <label class="sr-only" for="categoryFilter">Filter kategori</label>
                    <select id="categoryFilter" class="category-filter">
                        <option value="">Semua Kategori</option>
                        <option value="tshirt">T-Shirt</option>
                        <option value="pdh">PDH / Kemeja</option>
                        <option value="jersey">Jersey</option>
                        <option value="polo">Polo Shirt</option>
                    </select>
                </div>

                <div class="filter-tabs" role="tablist" aria-label="Filter status">
                    <button type="button" class="filter-tab is-active" role="tab" aria-selected="true" data-filter="semua">Semua</button>
                    <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="pending">Belum Diproses</button>
                    <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="proses">Proses</button>
                    <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="selesai">Selesai</button>
                </div>
            </section>

            <?php if (!empty($transactions)): ?>
                <section class="order-grid" id="orderGrid" aria-label="Daftar pesanan">
                    <?php foreach ($transactions as $transaction): ?>
                        <?php $status = $statusMap[$transaction['status_order']] ?? ['label' => 'Status Tidak Diketahui', 'class' => 'status-pending', 'group' => 'pending']; ?>
                            <article class="order-card"
                                     data-status-group="<?= e($status['group']) ?>"
                                     data-search="<?= e(strtolower(($transaction['nama'] ?? '') . ' ' . ($transaction['order_code'] ?? ''))) ?>">
                                <a href="<?= url('/transactions/detail?id=' . urlencode($transaction['order_code'])) ?>"
                                   class="order-card__link"
                                   aria-label="Lihat detail pesanan #<?= e($transaction['order_code']) ?>">
                                    <p>ID: <strong translate="no">#<?= e($transaction['order_code']) ?></strong></p>
                                    <p>Nama Pelanggan: <strong><?= $escape($transaction['nama']) ?></strong></p>
                                    <p>Tanggal Order: <strong><?= e(format_date_id($transaction['tanggal_order'], true)) ?></strong></p>
                                    <p>Jumlah: <strong class="tabular-nums"><?= e($transaction['total_qty']) ?>&nbsp;pcs</strong></p>
                                    <p>Total Harga: <span class="price-tag tabular-nums"><?= e(format_currency($transaction['grand_total'])) ?></span></p>
                                    <span class="status-badge <?= e($status['class']) ?>"><?= e($status['label']) ?></span>
                                </a>
                            </article>
                    <?php endforeach; ?>
                </section>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox empty-state__icon" aria-hidden="true"></i>
                    <h2 class="empty-state__title">Belum ada pesanan</h2>
                    <p class="empty-state__desc">Pesanan baru akan tampil di sini ketika kasir membuatnya.</p>
                    <?php if (!$isOwner): ?>
                        <a href="<?= url('/transactions/create') ?>" class="btn btn--primary">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            Buat Pesanan Baru
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
    (function () {
        const tabs = document.querySelectorAll('.filter-tab');
        const grid = document.getElementById('orderGrid');
        const search = document.getElementById('searchOrders');
        if (!grid) return;

        let activeFilter = 'semua';
        let activeQuery = '';

        const apply = () => {
            grid.querySelectorAll('.order-card').forEach((card) => {
                const matchesFilter = activeFilter === 'semua' || card.dataset.statusGroup === activeFilter;
                const matchesQuery = !activeQuery || (card.dataset.search || '').includes(activeQuery);
                card.style.display = matchesFilter && matchesQuery ? '' : 'none';
            });
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabs.forEach((t) => {
                    t.classList.remove('is-active');
                    t.setAttribute('aria-selected', 'false');
                });
                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');
                activeFilter = tab.dataset.filter || 'semua';
                apply();
            });
        });

        if (search) {
            search.addEventListener('input', (e) => {
                activeQuery = (e.target.value || '').toLowerCase().trim();
                apply();
            });
        }
    })();
</script>
</body>
</html>
