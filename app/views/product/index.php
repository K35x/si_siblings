<?php
$pageTitle = 'Stok Produk - Siblings.co';
$pageStyles = ['products.css'];

$stocks = $stocks ?? [];
$sidebarRole = $sidebarRole ?? 'owner';
$activeMenu = $activeMenu ?? 'products';

// Indeks options per variant_id: [variant_id => [size_id => [color_id => option_data]]]
$optionMap = [];
foreach ($options as $opt) {
    $optionMap[$opt['variant_id']][$opt['size_id']][$opt['color_id']] = [
        'option_id' => $opt['option_id'],
        'qty'       => isset($opt['qty']) ? (int) $opt['qty'] : 1,
    ];
}

// Susun kategori → varian
$grouped = [];
foreach ($variants as $v) {
    $cid = $v['category_id'];
    if (!isset($grouped[$cid])) {
        $grouped[$cid] = ['nama' => $v['nama_kategori'], 'variants' => []];
    }
    $grouped[$cid]['variants'][] = $v;
}

if (!function_exists('colorNameToHex')) {
    function colorNameToHex(string $name): string
    {
        return match (mb_strtolower(trim($name))) {
            'merah'                       => '#ef4444',
            'jingga', 'oranye', 'orange'  => '#f97316',
            'kuning'                      => '#eab308',
            'hijau'                       => '#22c55e',
            'biru'                        => '#3b82f6',
            'nila', 'indigo'              => '#6366f1',
            'ungu', 'violet'              => '#8b5cf6',
            'putih'                       => '#ffffff',
            'hitam'                       => '#111827',
            'abu', 'abu-abu', 'gray', 'grey' => '#9ca3af',
            'pink', 'merah muda'          => '#ec4899',
            'coklat', 'brown'             => '#92400e',
            'maroon'                      => '#7f1d1d',
            'tosca', 'teal'               => '#14b8a6',
            'navy', 'biru tua'            => '#1e3a5f',
            default                       => '#cbd5e1',
        };
    }
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
$sidebarRole = $sidebarRole ?? 'owner';
$activeMenu  = $activeMenu  ?? 'products';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <div class="product-page__header">
                <div>
                    <h3><i class="fas fa-box" aria-hidden="true"></i> Stok Barang</h3>
                    <p>Kelola produk dan varian bahan dari sini.</p>
                </div>

                <div class="product-page__actions">
                    <label class="sr-only" for="filterKategori">Filter kategori</label>
                    <select id="filterKategori">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat['category_id']) ?>"><?= e($cat['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="button" class="btn btn--soft" id="btnTambah">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Tambah
                    </button>
                    <button type="button" class="btn btn--soft" id="btnHapusMode">
                        <i class="fas fa-trash" aria-hidden="true"></i>
                        Hapus
                    </button>
                </div>
            </div>

            <div class="product-wrapper scroll-thin" id="productWrapper">
                <?php if (empty($grouped)): ?>
                    <div class="empty-state empty-state--bare">
                        <i class="fas fa-box-open empty-state__icon" aria-hidden="true"></i>
                        <h2 class="empty-state__title">Belum ada produk</h2>
                        <p class="empty-state__desc">Tambahkan varian baru untuk mulai mengelola stok.</p>
                        <button type="button" class="btn btn--primary" id="btnTambahEmpty">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            Tambah Varian
                        </button>
                    </div>
                <?php endif; ?>

                <?php foreach ($grouped as $cid => $cat): ?>
                    <?php
                    $totalCat = 0;
                    foreach ($cat['variants'] as $v) {
                        $totalCat += countOptions($optionMap, $v['variant_id']);
                    }
                    ?>
                    <div class="kategori-item" data-category-id="<?= e($cid) ?>">
                        <button type="button"
                                class="kategori-header"
                                data-toggle="kategori"
                                aria-expanded="false"
                                aria-controls="kategori-content-<?= e($cid) ?>">
                            <span class="left">
                                <input type="checkbox" class="pilih-hapus" hidden
                                       data-category-id="<?= e($cid) ?>"
                                       aria-label="Pilih kategori untuk dihapus">
                                <span class="icon" aria-hidden="true"><i class="fas fa-tshirt"></i></span>
                                <span class="nama"><?= e($cat['nama']) ?></span>
                            </span>
                            <span class="right">
                                <span class="total"><?= $totalCat ?> opsi</span>
                                <span class="arrow" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                            </span>
                        </button>

                        <div class="kategori-content" id="kategori-content-<?= e($cid) ?>">
                            <?php foreach ($cat['variants'] as $v): ?>
                                <?php
                                $vid = $v['variant_id'];
                                $sizeGroups = $optionMap[$vid] ?? [];
                                $totalVariant = countOptions($optionMap, $vid);
                                ?>
                                <div class="kain" data-variant-id="<?= e($vid) ?>">
                                    <div class="kain-title">
                                        <button type="button"
                                                class="kain-title__btn"
                                                data-toggle="kain"
                                                aria-expanded="false"
                                                aria-controls="kain-options-<?= e($vid) ?>">
                                            <span class="kain-title__caret" aria-hidden="true"></span>
                                            <span class="kain-title__label">
                                                <?= e($v['nama_varian']) ?>
                                                <small class="text-muted kain-title__count">(<?= $totalVariant ?>&nbsp;pcs)</small>
                                            </span>
                                        </button>
                                        <div class="aksi">
                                            <button type="button" class="btn btn--soft btn--sm"
                                                    data-action="edit-variant"
                                                    data-variant='<?= e(json_encode($v)) ?>'
                                                    data-options='<?= e(json_encode($optionMap[$vid] ?? [])) ?>'
                                                    aria-label="Edit varian <?= e($v['nama_varian']) ?>">
                                                <i class="fas fa-pen" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" class="btn btn--icon btn--danger-soft"
                                                    data-action="delete-variant"
                                                    data-variant-id="<?= e($vid) ?>"
                                                    data-variant-name="<?= e($v['nama_varian']) ?>"
                                                    aria-label="Hapus varian <?= e($v['nama_varian']) ?>">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="kain__meta">
                                        Mulai <?= e(format_currency($v['harga_start_from'])) ?>
                                        &middot; Bahan: <?= e($v['bahan'] ?? '-') ?>
                                    </div>

                                    <div class="kain__options" id="kain-options-<?= e($vid) ?>" hidden>
                                        <?php if (empty($sizeGroups)): ?>
                                            <p class="text-muted kain__empty">Belum ada ukuran &amp; warna.</p>
                                        <?php else: ?>
                                            <?php foreach ($sizeGroups as $sid => $colorMap): ?>
                                                <?php
                                                $sizeName  = '';
                                                foreach ($sizes as $sz) {
                                                    if ($sz['size_id'] == $sid) { $sizeName = $sz['size_name']; break; }
                                                }
                                                $totalColors = count($colorMap);
                                                $totalSizePcs = 0;
                                                foreach ($colorMap as $cdata) {
                                                    $totalSizePcs += isset($cdata['qty']) ? (int) $cdata['qty'] : 1;
                                                }
                                                ?>
                                                <div class="ukuran-item" data-size-id="<?= e($sid) ?>">
                                                    <div class="ukuran-row">
                                                        <button type="button"
                                                                class="ukuran-row__toggle"
                                                                data-toggle="ukuran"
                                                                aria-expanded="false"
                                                                aria-controls="warna-list-<?= e($vid) ?>-<?= e($sid) ?>">
                                                            <span class="ukuran-row__size"><?= e($sizeName) ?></span>
                                                            <span class="ukuran-row__count"><?= $totalColors ?>&nbsp;warna &bull; <?= $totalSizePcs ?>&nbsp;pcs</span>
                                                        </button>
                                                        <button type="button" class="btn-delete"
                                                                data-action="delete-size"
                                                                data-variant-id="<?= e($vid) ?>"
                                                                data-size-id="<?= e($sid) ?>"
                                                                aria-label="Hapus semua warna ukuran <?= e($sizeName) ?>">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </div>

                                                    <div class="warna-list" id="warna-list-<?= e($vid) ?>-<?= e($sid) ?>" hidden>
                                                        <?php foreach ($colorMap as $cid2 => $optData):
                                                            $colorName = '';
                                                            foreach ($colors as $cl) {
                                                                if ($cl['color_id'] == $cid2) { $colorName = $cl['color_name']; break; }
                                                            }
                                                            $dotHex    = colorNameToHex($colorName);
                                                            $dotBorder = (mb_strtolower(trim($colorName)) === 'putih') ? '1px solid #d1d5db' : 'none';
                                                            $optId = is_array($optData) ? $optData['option_id'] : $optData;
                                                            $qty   = is_array($optData) ? (int) ($optData['qty'] ?? 1) : 1;
                                                        ?>
                                                            <div class="warna-row" data-option-id="<?= e($optId) ?>">
                                                                <span class="warna-row__name" style="--dot-color:<?= e($dotHex) ?>; --dot-border:<?= e($dotBorder) ?>;">
                                                                    <?= e($colorName) ?>
                                                                </span>
                                                                <span class="warna-row__qty"><?= $qty ?>&nbsp;pcs</span>
                                                                <button type="button" class="btn-delete"
                                                                        data-action="delete-option"
                                                                        data-option-id="<?= e($optId) ?>"
                                                                        aria-label="Hapus warna <?= e($colorName) ?>">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>

<!-- Modal Tambah / Edit -->
<div class="modal-overlay" id="modalForm" role="dialog" aria-modal="true" aria-labelledby="modalFormTitle" aria-hidden="true">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title" id="modalFormTitle">Tambah Varian</h3>
            <button type="button" class="modal__close" data-modal-close aria-label="Tutup dialog">&times;</button>
        </div>
        <div class="modal__body">
            <input type="hidden" id="f_variant_id" value="">

            <div class="form-field" id="f_category_field">
                <label class="form-field__label" for="f_category_id">Kategori</label>
                <select id="f_category_id" class="form-control">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= e($cat['category_id']) ?>"><?= e($cat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field" id="f_nama_produk_field">
                <label class="form-field__label" for="f_nama_produk">Nama Produk</label>
                <input id="f_nama_produk" class="form-control" type="text" placeholder="Contoh: Tshirt Basic">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_nama_varian">Nama Varian / Bahan</label>
                <input id="f_nama_varian" class="form-control" type="text" placeholder="Contoh: Cotton Combed 24s">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_bahan">Bahan</label>
                <input id="f_bahan" class="form-control" type="text">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_tipe_sablon">Tipe Sablon / Bordir</label>
                <input id="f_tipe_sablon" class="form-control" type="text" placeholder="Contoh: Plastisol / DTF">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_harga">Harga Mulai Dari (Rp)</label>
                <input id="f_harga" class="form-control" type="number" min="0" placeholder="62000">
            </div>

            <div class="form-field">
                <label class="form-field__label">Ukuran &amp; Warna</label>
                <div id="f_options_list" class="f-options-list"></div>
                <button type="button" class="btn btn--ghost btn--sm mt-4" id="btnAddCombination">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Tambah Kombinasi
                </button>
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--ghost" data-modal-close>Batal</button>
            <button type="button" class="btn btn--primary" id="btnSubmitForm">Simpan</button>
        </div>
    </div>
</div>

<script>
const SIZES  = <?= json_encode(array_values($sizes)) ?>;
const COLORS = <?= json_encode(array_values($colors)) ?>;
const ENDPOINTS = {
    store: '<?= url('/products/store') ?>',
    update: '<?= url('/products/update') ?>',
    destroy: '<?= url('/products/destroy') ?>',
    destroyOption: '<?= url('/products/destroy-option') ?>',
};
</script>
<script src="<?= asset('js/ui.js') ?>"></script>
<script src="<?= asset('js/products.js') ?>"></script>
</body>
</html>
