<?php
$pageTitle = "Dashboard Kasir - Siblings.co";
$pageStyles = ["dashboard.css"];

$stats = $stats ?? [];
$queue = $queue ?? [];

$statIcons = [
    "Pesanan Baru" => "fa-bag-shopping",
    "Sedang Diproses" => "fa-shirt",
    "Siap Diambil" => "fa-box-open",
    "Omzet Hari Ini" => "fa-wallet",
];

$statusBadges = Model::BADGE_STATUS_MAP;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../partials/head.php"; ?>
</head>
<body>
<a href="#main-content" class="skip-to-content">Lewati ke konten utama</a>
<?php include __DIR__ . "/../partials/sidebar-toggle.php"; ?>

<div class="app-shell">
    <?php
    $sidebarRole = Model::ROLE_KASIR;
    $activeMenu = "dashboard";
    include __DIR__ . "/sidebar.php";
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content dashboard-page dashboard-page--cashier">
            <h1 id="dashboard-title">Dashboard Kasir</h1>

            <section class="dashboard-stats" aria-label="Statistik pesanan hari ini">
                <?php foreach ($stats as $index => $stat): ?>
                    <?php $icon =
                        $statIcons[$stat["label"]] ?? "fa-chart-line"; ?>
                    <article class="dashboard-stat dashboard-stat--<?= e(
                        (string) (($index % 4) + 1),
                    ) ?>">
                        <span class="dashboard-stat__icon" aria-hidden="true"><i class="fas <?= e(
                            $icon,
                        ) ?>"></i></span>
                        <span class="dashboard-stat__content">
                            <span class="dashboard-stat__label"><?= e(
                                $stat["label"],
                            ) ?></span>
                            <strong class="dashboard-stat__value"><?= e(
                                $stat["value"],
                            ) ?></strong>
                        </span>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="dashboard-grid dashboard-grid--cashier">
                <aside class="panel quick-actions" aria-label="Aksi cepat">
                    <div class="panel__header">
                        <div>
                            <h3 class="panel__title">
                                <i class="fas fa-bolt" aria-hidden="true"></i>
                                Menu Kasir
                            </h3>
                        </div>
                    </div>
                    <div class="panel__body">
                        <a href="<?= url(
                            "/transactions/create",
                        ) ?>" class="quick-actions__cta">
                            <span class="quick-actions__cta-icon"><i class="fas fa-plus" aria-hidden="true"></i></span>
                            <span>
                                <strong>Buat Pesanan Baru</strong>
                                <small>Input customer, produk, ukuran, dan pembayaran.</small>
                            </span>
                        </a>
                        <div class="quick-actions__shortcuts">
                            <a href="<?= url(
                                "/transactions?status=pending_payment",
                            ) ?>" class="quick-action-link">
                                <span class="quick-action-link__icon"><i class="fas fa-clock"></i></span>
                                <span class="quick-action-link__label">Belum Dibayar</span>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                            <a href="<?= url(
                                "/transactions?status=ready",
                            ) ?>" class="quick-action-link">
                                <span class="quick-action-link__icon"><i class="fas fa-check-double"></i></span>
                                <span class="quick-action-link__label">Siap Diambil</span>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                            <a href="<?= url(
                                "/transactions",
                            ) ?>" class="quick-action-link">
                                <span class="quick-action-link__icon"><i class="fas fa-list-check"></i></span>
                                <span class="quick-action-link__label">Semua Pesanan</span>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </aside>

                <div class="panel queue-panel">
                    <div class="panel__header">
                        <div>
                            <h3 class="panel__title">
                                <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                                Antrian Pesanan
                            </h3>
                        </div>
                        <a href="<?= url(
                            "/transactions",
                        ) ?>" class="panel__link">Kelola</a>
                    </div>
                    <div class="panel__body scroll-thin">
                        <?php if (!empty($queue)): ?>
                            <ul class="queue-list">
                                <?php foreach ($queue as $order): ?>
                                    <?php
                                    $status =
                                        $order["status"] ?? "pending_payment";
                                    $label =
                                        $order["label"] ?? ucfirst($status);
                                    $custom = $order["customer"] ?? "-";
                                    $badgeClass =
                                        $statusBadges[$status] ??
                                        "badge--neutral";
                                    ?>
                                    <li class="queue-item" data-status="<?= e(
                                        $status,
                                    ) ?>">
                                        <a class="queue-item__main" href="<?= url(
                                            "/transactions/detail?id=" .
                                                urlencode(
                                                    (string) $order["id"],
                                                ),
                                        ) ?>">
                                            <span class="queue-item__marker" aria-hidden="true"></span>
                                            <span>
                                                <strong class="queue-item__id">#<?= e(
                                                    $order["id"],
                                                ) ?></strong>
                                                <small class="queue-item__customer"><?= e(
                                                    $custom,
                                                ) ?> &middot; <?= e(
     format_date_id($order["tanggal"] ?? null),
 ) ?></small>
                                            </span>
                                        </a>
                                        <span class="badge <?= e(
                                            $badgeClass,
                                        ) ?>">
                                            <i class="fas <?= $status ===
                                            "ready"
                                                ? "fa-check"
                                                : "fa-circle-notch" ?>" aria-hidden="true"></i>
                                            <?= e($label) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="dashboard-empty">
                                <i class="fas fa-inbox" aria-hidden="true"></i>
                                <p>Antrian kosong. Saatnya siap menerima order baru.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script src="<?= asset("js/ui.js") ?>"></script>
</body>
</html>
