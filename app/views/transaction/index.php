<?php
$pageTitle = 'Status Pesanan - ' . "Siblings.co";
$pageStyles = ['transactions.css'];

$statusMap = Model::ORDER_STATUS_MAP;


$isOwner = ($sidebarRole ?? Model::ROLE_KASIR) === Model::ROLE_OWNER;
$transactions = $transactions ?? [];
$escape = $escape ?? fn ($value) => e($value);


$countAll = count($transactions);
$countPending = 0; $countConfirmed = 0; $countProses = 0; $countReady = 0; $countSelesai = 0; $countBatal = 0;
foreach ($transactions as $t) {
    $s = $t['order_status'] ?? '';
    if ($s === Model::ORDER_STATUS_PENDING_PAYMENT) $countPending++;
    elseif ($s === Model::ORDER_STATUS_CONFIRMED) $countConfirmed++;
    elseif ($s === Model::ORDER_STATUS_IN_PROGRESS) $countProses++;
    elseif ($s === Model::ORDER_STATUS_READY) $countReady++;
    elseif ($s === Model::ORDER_STATUS_COMPLETED) $countSelesai++;
    elseif ($s === Model::ORDER_STATUS_CANCELLED) $countBatal++;
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
$activeMenu  = $activeMenu  ?? 'status';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <h1>Status Pesanan</h1>
            <p class="text-muted">Pantau seluruh pesanan, dari yang baru masuk sampai selesai diproses.</p>

            <!-- Quick Stats -->
            <div class="status-stats">
                <div class="status-stat">
                    <span class="status-stat__value tabular-nums"><?= $countAll ?></span>
                    <span class="status-stat__label">Total</span>
                </div>
                <div class="status-stat status-stat--pending">
                    <span class="status-stat__value tabular-nums"><?= $countPending ?></span>
                    <span class="status-stat__label">Menunggu DP</span>
                </div>
                <div class="status-stat status-stat--confirm">
                    <span class="status-stat__value tabular-nums"><?= $countConfirmed ?></span>
                    <span class="status-stat__label">Dikonfirmasi</span>
                </div>
                <div class="status-stat status-stat--proses">
                    <span class="status-stat__value tabular-nums"><?= $countProses ?></span>
                    <span class="status-stat__label">Diproses</span>
                </div>
                <div class="status-stat status-stat--ready">
                    <span class="status-stat__value tabular-nums"><?= $countReady ?></span>
                    <span class="status-stat__label">Siap</span>
                </div>
                <div class="status-stat status-stat--selesai">
                    <span class="status-stat__value tabular-nums"><?= $countSelesai ?></span>
                    <span class="status-stat__label">Selesai</span>
                </div>
            </div>

            <!-- Filters -->
            <section class="filter-wrapper" aria-label="Filter pesanan">
                <div class="filter-row">
                    <div class="search-wrapper">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <label class="sr-only" for="searchOrders">Cari pesanan</label>
                        <input id="searchOrders" type="search"
                               placeholder="Cari nama atau ID pesanan…"
                               autocomplete="off"
                               spellcheck="false">
                    </div>
                    <div class="date-filter" role="group" aria-label="Rentang tanggal">
                        <label class="sr-only" for="dateFrom">Dari tanggal</label>
                        <input id="dateFrom" type="date">
                        <span aria-hidden="true">–</span>
                        <label class="sr-only" for="dateTo">Sampai tanggal</label>
                        <input id="dateTo" type="date">
                    </div>
                </div>

                <div class="filter-row filter-row--bottom">
                    <div class="filter-tabs" role="tablist" aria-label="Filter status">
                        <button type="button" class="filter-tab is-active" role="tab" aria-selected="true" data-filter="semua">
                            Semua <span class="filter-tab__count"><?= $countAll ?></span>
                        </button>
                        <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="pending_payment">
                            Menunggu DP <span class="filter-tab__count"><?= $countPending ?></span>
                        </button>
                        <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="confirmed">
                            Dikonfirmasi <span class="filter-tab__count"><?= $countConfirmed ?></span>
                        </button>
                        <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="in_progress">
                            Diproses <span class="filter-tab__count"><?= $countProses ?></span>
                        </button>
                        <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="ready">
                            Siap <span class="filter-tab__count"><?= $countReady ?></span>
                        </button>
                        <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="completed">
                            Selesai <span class="filter-tab__count"><?= $countSelesai ?></span>
                        </button>
                        <button type="button" class="filter-tab" role="tab" aria-selected="false" data-filter="cancelled">
                            Batal <span class="filter-tab__count"><?= $countBatal ?></span>
                        </button>
                    </div>
                    <?php if (!$isOwner): ?>
                        <a href="<?= url('/transactions/create') ?>" class="btn btn--primary btn--sm">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            Buat Pesanan
                        </a>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Order Grid -->
            <?php if (!empty($transactions)): ?>
                <section class="order-grid" id="orderGrid" aria-label="Daftar pesanan">
                    <?php foreach ($transactions as $transaction):
                        $status = $statusMap[$transaction['order_status']] ?? $statusMap[Model::ORDER_STATUS_PENDING_PAYMENT];
                        $catIcon = 'fas fa-box';
                        $phone = preg_replace('/[^0-9]/', '', $transaction['phone_number'] ?? '');
                        $waBaseUrl = "https://wa.me/";
                        $waCountryCode = "62";
                        $waLink = $phone ? $waBaseUrl . (str_starts_with($phone, '0') ? $waCountryCode . substr($phone, 1) : $phone) : '';
                    ?>
                        <article class="order-card"
                                 data-status-group="<?= e($transaction['order_status']) ?>"
                                 data-search="<?= e(strtolower(($transaction['name'] ?? '') . ' ' . ($transaction['order_code'] ?? ''))) ?>"
                                 data-date="<?= e(substr($transaction['order_date'] ?? '', 0, 10)) ?>">
                            <a href="<?= url('/transactions/detail?id=' . urlencode($transaction['order_code'])) ?>"
                               class="order-card__link">
                                <div class="order-card__top">
                                    <span class="order-card__icon"><i class="<?= $catIcon ?>"></i></span>
                                    <span class="order-card__id" translate="no">#<?= e($transaction['order_code']) ?></span>
                                    <span class="status-badge <?= e($status['class']) ?>">
                                        <i class="fas <?= e($status['icon']) ?>" aria-hidden="true"></i>
                                        <?= e($status['label']) ?>
                                    </span>
                                </div>
                                <div class="order-card__body">
                                    <p class="order-card__customer"><?= $escape($transaction['name']) ?></p>
                                    <p class="order-card__meta">
                                        <span><i class="fas fa-calendar" aria-hidden="true"></i> <?= e(format_date_id($transaction['order_date'])) ?></span>
                                        <span><i class="fas fa-box" aria-hidden="true"></i> <?= e($transaction['total_qty']) ?> pcs</span>
                                    </p>
                                </div>
                                <div class="order-card__bottom">
                                    <span class="order-card__price tabular-nums"><?= e(format_currency($transaction['grand_total'])) ?></span>
                                    <?php if ($waLink): ?>
                                        <a href="<?= e($waLink) ?>" class="order-card__wa" target="_blank" rel="noopener"
                                           onclick="event.stopPropagation()"
                                           aria-label="WhatsApp <?= e($transaction['name']) ?>">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </section>

                <!-- Empty filter feedback -->
                <div class="filter-empty" id="filterEmpty" hidden>
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <p>Tidak ada pesanan yang cocok dengan filter.</p>
                </div>
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
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');
        const emptyMsg = document.getElementById('filterEmpty');
        if (!grid) return;

        let activeFilter = 'semua';
        let activeQuery = '';
        let activeDateFrom = '';
        let activeDateTo = '';

        const apply = () => {
            let visible = 0;
            grid.querySelectorAll('.order-card').forEach((card) => {
                const matchesStatus = activeFilter === 'semua' || card.dataset.statusGroup === activeFilter;
                const matchesQuery = !activeQuery || (card.dataset.search || '').includes(activeQuery);
                const matchesDate = (!activeDateFrom || card.dataset.date >= activeDateFrom)
                                 && (!activeDateTo || card.dataset.date <= activeDateTo);
                const show = matchesStatus && matchesQuery && matchesDate;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (emptyMsg) emptyMsg.hidden = visible > 0;
        };

        // Auto-select filter from query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const statusParam = urlParams.get('status');
        if (statusParam) {
            const targetTab = document.querySelector(`.filter-tab[data-filter="${statusParam}"]`);
            if (targetTab) {
                tabs.forEach((t) => { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
                targetTab.classList.add('is-active');
                targetTab.setAttribute('aria-selected', 'true');
                activeFilter = statusParam;
                apply();
            }
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabs.forEach((t) => { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');
                activeFilter = tab.dataset.filter || 'semua';
                apply();
            });
        });

        if (search) search.addEventListener('input', (e) => { activeQuery = (e.target.value || '').toLowerCase().trim(); apply(); });
        if (dateFrom) dateFrom.addEventListener('change', (e) => { activeDateFrom = e.target.value; apply(); });
        if (dateTo) dateTo.addEventListener('change', (e) => { activeDateTo = e.target.value; apply(); });
    })();
</script>
<?php if (!empty($_SESSION['toast_success'])): ?>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        window.SiblingsUI?.toast?.(<?= json_encode($_SESSION['toast_success']) ?>, 'success');
    });
</script>
<?php unset($_SESSION['toast_success']); endif; ?>
</body>
</html>
