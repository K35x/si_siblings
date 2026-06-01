<?php
$pageTitle = "Keranjang Pesanan - Siblings.co";
$pageStyles = ["transactions.css", "transaction-cart.css"];

$keranjang = $_SESSION["keranjang"] ?? [];
$grandTotal = 0;
$totalQty = 0;
foreach ($keranjang as $item) {
    $grandTotal += (float) ($item["price"] ?? $item["harga"] ?? 0);
    $totalQty += (int) ($item["quantity"] ?? $item["qty"] ?? 0);
}

$customerName = $_SESSION["customer_name"] ?? "";
$namaProject = $_SESSION["project_name"] ?? "";
$hasCustomer = $customerName !== "";

$validationErrors = $validationErrors ?? [];

$categoryIcons = [
    "Tshirt" => "fas fa-tshirt",
    "Seragam Kerja" => "fas fa-user-tie",
    "Seragam Olahraga" => "fas fa-running",
    "Polo Shirt" => "fas fa-tshirt",
    "Jersey" => "fas fa-tshirt",
];
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
    $sidebarRole = $sidebarRole ?? Model::ROLE_KASIR;
    $activeMenu = $activeMenu ?? "cart";
    include __DIR__ . "/../layouts/sidebar.php";
    ?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <?php
            $currentStep = 4;
            include __DIR__ . "/includes/step-indicator.php";
            ?>

            <h1>Keranjang Pesanan</h1>
            <p class="text-muted"><?= count($keranjang) ?> item &middot; <?= e(
     $totalQty,
 ) ?> pcs</p>

            <?php include __DIR__ . "/includes/validation-errors.php"; ?>

            <?php if (!empty($_SESSION["error"])): ?>
                <div class="alert alert--danger" role="alert"><?= e(
                    $_SESSION["error"],
                ) ?></div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>

            <?php if ($hasCustomer): ?>
                <div class="cart-customer-bar">
                    <span><i class="fas fa-user" aria-hidden="true"></i> <?= e(
                        $customerName,
                    ) ?></span>
                    <?php if (!empty($_SESSION["customer_phone"])): ?>
                        <span><i class="fas fa-phone" aria-hidden="true"></i> <?= e(
                            $_SESSION["customer_phone"],
                        ) ?></span>
                    <?php endif; ?>
                    <?php if ($namaProject): ?>
                        <span><i class="fas fa-clipboard" aria-hidden="true"></i> <?= e(
                            $namaProject,
                        ) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION["order_date"])): ?>
                        <span><i class="fas fa-calendar" aria-hidden="true"></i> <?= e(
                            format_date_id($_SESSION["order_date"]),
                        ) ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($keranjang)): ?>
                <div class="cart-items">
                    <?php foreach ($keranjang as $index => $item):

                        $icon =
                            $categoryIcons[$item["category"]] ?? "fas fa-box";
                        $quantity = (int) ($item["quantity"] ?? $item["qty"] ?? 0);
                        $itemTotal = (float) ($item["price"] ?? $item["harga"] ?? 0);
                        $basePrice = (float) ($item["unit_price"] ?? $item["harga_satuan"] ?? 0);
                        $material = $item["material"] ?? $item["bahan"] ?? "-";
                        $sablon = $item["sablon"] ?? "-";
                        $sizes = $item["rincian"] ?? [];
                        $surcharge = $item["surcharge"] ?? [
                            "xxl_qty" => 0,
                            "xxl_total" => 0,
                            "long_qty" => 0,
                            "long_rate" => 0,
                            "long_total" => 0,
                        ];
                        $sablonPrice = (float) ($item["sablon_price"] ?? 0);
                        $productBasePrice = max(0, $basePrice - $sablonPrice);
                        $productBaseTotal = $quantity * $productBasePrice;
                        $sablonTotal = $quantity * $sablonPrice;
                        $sizeSurcharges = $surcharge["size"] ?? [];
                        $qtyShort = $item["quantity_short"] ?? [];
                        $qtyLong = $item["quantity_long"] ?? [];
                        $warnaPerSize = $item["warna_per_size"] ?? ["short" => [], "long" => []];
                        $productionRows = [];
                        foreach ($sizes as $sz => $jml) {
                            $shortQty = (int) ($qtyShort[$sz] ?? 0);
                            $longQty = (int) ($qtyLong[$sz] ?? 0);
                            if ($shortQty === 0 && $longQty === 0 && (int) $jml > 0) {
                                $shortQty = (int) $jml;
                            }
                            if ($shortQty > 0) {
                                $productionRows[] = [
                                    "size" => $sz,
                                    "sleeve" => "Pendek",
                                    "color" => $warnaPerSize["short"][$sz] ?? "-",
                                    "qty" => $shortQty,
                                ];
                            }
                            if ($longQty > 0) {
                                $productionRows[] = [
                                    "size" => $sz,
                                    "sleeve" => "Panjang",
                                    "color" => $warnaPerSize["long"][$sz] ?? "-",
                                    "qty" => $longQty,
                                ];
                            }
                        }
                        ?>
                        <div class="cart-item">
                            <div class="cart-item__header">
                                <span class="cart-item__num"><?= $index +
                                    1 ?></span>
                                <span class="cart-item__icon"><i class="<?= $icon ?>"></i></span>
                                <span class="cart-item__kategori"><?= e(
                                    $item["category"],
                                ) ?></span>
                                <span class="cart-item__qty"><?= e(
                                    $quantity,
                                ) ?> pcs</span>
                                <span class="cart-item__price tabular-nums"><?= e(
                                    format_currency($itemTotal),
                                ) ?></span>
                            </div>
                            <div class="cart-item__body">
                                <div class="cart-review-grid">
                                    <section class="cart-review-panel" aria-label="Spesifikasi item">
                                        <div class="cart-review-panel__title">
                                            <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                                            Spesifikasi
                                        </div>
                                        <dl class="cart-spec-list">
                                            <div>
                                                <dt>Bahan</dt>
                                                <dd><?= e($material) ?></dd>
                                            </div>
                                            <div>
                                                <dt>Jenis Sablon</dt>
                                                <dd><?= e($sablon ?: '-') ?></dd>
                                            </div>
                                            <div>
                                                <dt>Harga Sablon</dt>
                                                <dd><?= e(format_currency($sablonPrice)) ?>/pcs</dd>
                                            </div>
                                            <?php if (!empty($item["catatan"])): ?>
                                                <div>
                                                    <dt>Catatan</dt>
                                                    <dd><?= e($item["catatan"]) ?></dd>
                                                </div>
                                            <?php endif; ?>
                                        </dl>
                                    </section>

                                    <section class="cart-review-panel" aria-label="Rincian produksi">
                                        <div class="cart-review-panel__title">
                                            <i class="fas fa-ruler-combined" aria-hidden="true"></i>
                                            Rincian Produksi
                                        </div>
                                        <?php if (!empty($productionRows)): ?>
                                            <div class="cart-production-table-wrap">
                                                <table class="cart-production-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Ukuran</th>
                                                            <th>Lengan</th>
                                                            <th>Warna</th>
                                                            <th class="text-right">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($productionRows as $row): ?>
                                                            <tr>
                                                                <td><span class="size-pill size-pill--table"><?= e($row["size"]) ?></span></td>
                                                                <td><?= e($row["sleeve"]) ?></td>
                                                                <td><?= e($row["color"] ?: '-') ?></td>
                                                                <td class="text-right tabular-nums"><?= e($row["qty"]) ?> pcs</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="cart-muted">Belum ada rincian ukuran.</p>
                                        <?php endif; ?>
                                    </section>
                                </div>

                                <?php if (!empty($item["desain"])): ?>
                                    <section class="cart-review-panel cart-review-panel--wide" aria-label="File desain">
                                        <div class="cart-review-panel__title">
                                            <i class="fas fa-image" aria-hidden="true"></i>
                                            File Desain
                                        </div>
                                        <div class="cart-design-list">
                                            <?php foreach ($item["desain"] as $designIndex => $design): ?>
                                                <div class="cart-design-item">
                                                    <span class="cart-design-item__badge"><?= e($designIndex + 1) ?></span>
                                                    <div>
                                                        <?php if (!empty($design["filename"])): ?>
                                                            <a href="<?= e(url('/' . ltrim($design['url'] ?? '', '/'))) ?>" target="_blank" rel="noopener">
                                                                <?= e($design["filename"]) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <strong>Catatan desain</strong>
                                                        <?php endif; ?>
                                                        <?php if (!empty($design["note"])): ?>
                                                            <p><?= e($design["note"]) ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </section>
                                <?php endif; ?>

                                <section class="cart-item__formula" aria-label="Rincian biaya">
                                    <div class="cart-review-panel__title">
                                        <i class="fas fa-receipt" aria-hidden="true"></i>
                                        Rincian Biaya
                                    </div>
                                    <div class="formula-line">
                                        <span>Harga produk: <?= e($quantity) ?> pcs × <?= e(format_currency($productBasePrice)) ?></span>
                                        <span class="tabular-nums"><?= e(format_currency($productBaseTotal)) ?></span>
                                    </div>
                                    <?php if ($sablonPrice > 0): ?>
                                        <div class="formula-line formula-line--sub">
                                            <span>Sablon: <?= e($quantity) ?> pcs × <?= e(format_currency($sablonPrice)) ?></span>
                                            <span class="tabular-nums">+<?= e(format_currency($sablonTotal)) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php foreach ($sizeSurcharges as $sizeName => $sizeData): ?>
                                        <?php if (($sizeData["qty"] ?? 0) > 0): ?>
                                            <div class="formula-line formula-line--sub">
                                                <span>Ukuran <?= e($sizeName) ?>: <?= e($sizeData["qty"]) ?> pcs × <?= e(format_currency($sizeData["rate"] ?? 0)) ?></span>
                                                <span class="tabular-nums">+<?= e(format_currency($sizeData["total"] ?? 0)) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if (($surcharge["long_qty"] ?? 0) > 0): ?>
                                        <div class="formula-line formula-line--sub">
                                            <span>Lengan panjang: <?= e($surcharge["long_qty"]) ?> pcs × <?= e(format_currency($surcharge["long_rate"] ?? 0)) ?></span>
                                            <span class="tabular-nums">+<?= e(format_currency($surcharge["long_total"] ?? 0)) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="formula-line formula-line--total">
                                        <span>Subtotal</span>
                                        <span class="tabular-nums"><?= e(format_currency($itemTotal)) ?></span>
                                    </div>
                                </section>
                            </div>
                            <div class="cart-item__actions">
                                <a href="<?= url(
                                    "/transactions/cart?edit=" . $index,
                                ) ?>"
                                   class="btn btn--soft btn--sm">
                                    <i class="fas fa-edit" aria-hidden="true"></i> Edit
                                </a>
                                <form method="POST" action="<?= url(
                                    "/transactions/cart",
                                ) ?>" class="display-inline"
                                      data-cart-delete data-name="<?= e(
                                          $item["category"],
                                      ) ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="hapus" value="<?= e(
                                        $index,
                                    ) ?>">
                                    <button type="submit" class="btn btn--danger-soft btn--sm">
                                        <i class="fas fa-trash" aria-hidden="true"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php
                    endforeach; ?>
                </div>

                <div class="cart-summary-section">
                    <div class="summary-box">
                        <?php
                        $totalSizeSurcharge = [];
                        $totalLong = 0;
                        $totalSurcharge = 0;
                        foreach ($keranjang as $it) {
                            $sc = $it["surcharge"] ?? [];
                            foreach ($sc["size"] ?? [] as $sizeName => $sizeSurcharge) {
                                $totalSizeSurcharge[$sizeName] = ($totalSizeSurcharge[$sizeName] ?? 0) + (int) ($sizeSurcharge["qty"] ?? 0);
                                $totalSurcharge += (float) ($sizeSurcharge["total"] ?? 0);
                            }
                            if (empty($sc["size"]) && !empty($sc["xxl_qty"])) {
                                $totalSizeSurcharge["XXL"] = ($totalSizeSurcharge["XXL"] ?? 0) + (int) $sc["xxl_qty"];
                                $totalSurcharge += (float) ($sc["xxl_total"] ?? 0);
                            }
                            $totalLong += $sc["long_qty"] ?? 0;
                            $totalSurcharge += $sc["long_total"] ?? 0;
                        }
                        ?>
                        <div class="cart-summary-row">
                            <span>Total Item</span>
                            <span class="tabular-nums"><?= count(
                                $keranjang,
                            ) ?> kategori</span>
                        </div>
                        <div class="cart-summary-row">
                            <span>Total Qty</span>
                            <span class="tabular-nums"><?= e(
                                $totalQty,
                            ) ?> pcs</span>
                        </div>
                        <?php if (!empty($totalSizeSurcharge) || $totalLong > 0): ?>
                            <div class="cart-summary-row cart-summary-row--sub">
                                <span>&nbsp;&nbsp;Lengan Panjang</span>
                                <span class="tabular-nums"><?= e(
                                    $totalLong,
                                ) ?> pcs</span>
                            </div>
                            <?php foreach ($totalSizeSurcharge as $sizeName => $sizeQty): ?>
                                <div class="cart-summary-row cart-summary-row--sub">
                                    <span>&nbsp;&nbsp;<?= e($sizeName) ?></span>
                                    <span class="tabular-nums"><?= e($sizeQty) ?> pcs</span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($totalSurcharge > 0): ?>
                            <div class="cart-summary-row cart-summary-row--sub">
                                <span>Biaya Tambahan</span>
                                <span class="tabular-nums">+<?= e(
                                    format_currency($totalSurcharge),
                                ) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="cart-summary-row grand-total-row">
                            <span>Grand Total</span>
                            <span class="final-price tabular-nums"><?= e(
                                format_currency($grandTotal),
                            ) ?></span>
                        </div>
                    </div>
                </div>

                <div class="cart-actions-bottom">
                    <div class="cart-actions-bottom__left">
                        <a href="<?= url(
                            "/transactions/categories",
                        ) ?>" class="btn btn--ghost btn--sm">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            Tambah Kategori
                        </a>
                        <form method="POST" action="<?= url(
                            "/transactions/cart",
                        ) ?>" class="display-inline"
                              data-cart-clear>
                            <?= csrf_field() ?>
                            <input type="hidden" name="clear" value="1">
                            <button type="submit" class="btn btn--danger-soft btn--sm">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                                Kosongkan
                            </button>
                        </form>
                    </div>
                    <a href="<?= url(
                        "/transactions/invoice",
                    ) ?>" class="btn btn--primary btn--lg">
                        Proses Invoice
                        <i class="fas fa-file-invoice" aria-hidden="true"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="empty-state empty-state--cart">
                    <i class="fas fa-shopping-basket empty-state__icon" aria-hidden="true"></i>
                    <h2 class="empty-state__title">Keranjang masih kosong</h2>
                    <p class="empty-state__desc">Tambahkan kategori produk untuk memulai pesanan.</p>
                    <a href="<?= url(
                        "/transactions/categories",
                    ) ?>" class="btn btn--primary">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Tambah Kategori
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="<?= asset("js/ui.js") ?>"></script>
<script>
    document.addEventListener('click', async (e) => {
        const del = e.target.closest('[data-cart-delete]');
        if (del) {
            e.preventDefault();
            const ok = await window.SiblingsUI.confirm({
                title: 'Hapus item?',
                message: `Item ${del.dataset.name || ''} akan dihapus dari keranjang.`,
                confirmText: 'Hapus',
                cancelText: 'Batal',
                variant: 'danger',
            });
            if (ok) del.closest('form').submit();
            return;
        }

        const clear = e.target.closest('[data-cart-clear]');
        if (clear) {
            e.preventDefault();
            const ok = await window.SiblingsUI.confirm({
                title: 'Kosongkan keranjang?',
                message: 'Semua item akan dihapus. Tindakan ini tidak bisa dibatalkan.',
                confirmText: 'Kosongkan',
                cancelText: 'Batal',
                variant: 'danger',
            });
            if (ok) clear.closest('form').submit();
        }
    });
</script>
</body>
</html>
