<?php
$pageTitle = 'Stok Produk - Siblings.co';
$pageStyles = ['products.css'];

$sidebarRole = $sidebarRole ?? Model::ROLE_OWNER;
$activeMenu = $activeMenu ?? 'products';


$optionMap = [];
foreach ($options as $opt) {
    $tipe = $opt['sleeve_type'] ?? 'short';
    $optionMap[$opt['variant_id']][$opt['size_id']][$opt['color_id']][$tipe] = [
        'option_id' => $opt['option_id'],
        'quantity'  => isset($opt['quantity']) ? (int) $opt['quantity'] : 1,
        'price_surcharge' => isset($opt['price_surcharge']) ? (float) $opt['price_surcharge'] : 0,
    ];
}


$grouped = [];
foreach ($variants as $v) {
    $cid = $v['category_id'];
    if (!isset($grouped[$cid])) {
        $grouped[$cid] = ['category_name' => $v['category_name'], 'variants' => []];
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
$sidebarRole = $sidebarRole ?? Model::ROLE_OWNER;
$activeMenu  = $activeMenu  ?? 'products';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <div class="product-page__header">
                <div>
                    <h1><i class="fas fa-box" aria-hidden="true"></i> Stok Barang</h1>
                    <p>Kelola produk dan varian bahan dari sini.</p>
                </div>

                <div class="product-page__actions">
                    <label class="sr-only" for="filterKategori">Filter kategori</label>
                    <select id="filterKategori">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat['category_id']) ?>"><?= e($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <span class="product-page__spacer"></span>

                    <button type="button" class="btn btn--primary" id="btnTambah">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Tambah
                    </button>
                    <button type="button" class="btn btn--accent" id="btnMasterData" aria-haspopup="true">
                        <i class="fas fa-database" aria-hidden="true"></i> Master Data
                    </button>
                    <button type="button" class="btn btn--soft" id="btnHapusMode">
                        <i class="fas fa-power-off" aria-hidden="true"></i>
                        Nonaktifkan
                    </button>
                    <!-- Master Data Dropdown -->
                    <div id="masterDataDropdown" class="master-dropdown" hidden>
                        <button type="button" class="master-dropdown__item" data-master="products">
                            <i class="fas fa-box" aria-hidden="true"></i> Produk
                        </button>
                        <button type="button" class="master-dropdown__item" data-master="sizes">
                            <i class="fas fa-ruler" aria-hidden="true"></i> Ukuran
                        </button>
                        <button type="button" class="master-dropdown__item" data-master="colors">
                            <i class="fas fa-palette" aria-hidden="true"></i> Warna
                        </button>
                        <a class="master-dropdown__item" href="<?= url('/products/sablon-types') ?>">
                            <i class="fas fa-print" aria-hidden="true"></i> Tipe Sablon
                        </a>
                    </div>
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
                                <span class="nama"><?= e($cat['category_name']) ?></span>
                            </span>
                            <span class="right">
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
                                <div class="kain<?= ($v['varian_aktif'] ?? 1) ? '' : ' kain--inactive' ?>" data-variant-id="<?= e($vid) ?>">
                                    <div class="kain__info">
                                        <button type="button"
                                                class="kain__toggle"
                                                data-toggle="kain"
                                                aria-expanded="false"
                                                aria-controls="kain-options-<?= e($vid) ?>">
                                            <span class="kain__caret" aria-hidden="true"></span>
                                            <span class="kain__name"><?= e($v['variant_name']) ?></span>
                                        </button>
                                        <span class="kain__sep" aria-hidden="true">&middot;</span>
                                        <span class="kain__info-item kain__harga"><?= e(format_currency($v['price'])) ?></span>
                                        <span class="kain__actions-inline">
                                            <button type="button" class="btn btn--icon btn--ghost btn--xs kain__action-btn kain__action-btn--edit"
                                                    data-action="edit-variant"
                                                    data-variant='<?= e(json_encode($v)) ?>'
                                                    data-options='<?= e(json_encode($optionMap[$vid] ?? [])) ?>'
                                                    aria-label="Edit varian <?= e($v['variant_name']) ?>">
                                                <i class="fas fa-pen" aria-hidden="true"></i>
                                            </button>
                                            <a class="btn btn--icon btn--ghost btn--xs kain__action-btn"
                                               href="<?= url('/products/variant-options') . '?variant_id=' . e($vid) ?>"
                                               aria-label="Kelola opsi <?= e($v['variant_name']) ?>">
                                                <i class="fas fa-layer-group" aria-hidden="true"></i>
                                            </a>
                                            <button type="button" class="btn btn--icon btn--ghost btn--xs kain__action-btn"
                                                    data-action="toggle-variant"
                                                    data-variant-id="<?= e($vid) ?>"
                                                    data-variant-name="<?= e($v['variant_name']) ?>"
                                                    data-active="<?= ($v['varian_aktif'] ?? 1) ? '1' : '0' ?>"
                                                    aria-label="<?= ($v['varian_aktif'] ?? 1) ? 'Nonaktifkan' : 'Aktifkan' ?> varian <?= e($v['variant_name']) ?>">
                                                <i class="fas fa-power-off" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                    </div>

                                    <div class="kain__options" id="kain-options-<?= e($vid) ?>" hidden>
                                        <?php if (empty($sizeGroups)): ?>
                                            <p class="text-muted kain__empty">Belum ada ukuran &amp; warna.</p>
                                        <?php else: ?>
                                            <?php
                                            
                                            $allColorIds = [];
                                            $allSizeIds = [];
                                            foreach ($sizeGroups as $sid => $colorMap) {
                                                $allSizeIds[$sid] = true;
                                                foreach ($colorMap as $cid2 => $sleeveData) {
                                                    $allColorIds[$cid2] = true;
                                                }
                                            }
                                            
                                            $colorNames = [];
                                            foreach ($colors as $cl) {
                                                if (isset($allColorIds[$cl['color_id']])) {
                                                    $colorNames[$cl['color_id']] = $cl['color_name'];
                                                }
                                            }
                                            $sizeNames = [];
                                            foreach ($sizes as $sz) {
                                                $sizeNames[$sz['size_id']] = $sz['size_name'];
                                            }
                                            
                                            $colorMapByColor = [];
                                            foreach ($sizeGroups as $sid => $colorMap) {
                                                foreach ($colorMap as $cid2 => $sleeveData) {
                                                    $colorMapByColor[$cid2][$sid] = $sleeveData;
                                                }
                                            }
                                            ?>
                                            <div class="stok-table-wrap">
                                                <table class="stok-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="stok-table__color-row-col">Warna</th>
                                                            <?php foreach ($sizeNames as $sid => $sname): ?>
                                                                <th class="stok-table__size-col"><?= e($sname) ?></th>
                                                            <?php endforeach; ?>
                                                            <th class="stok-table__total-col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($colorMapByColor as $cid2 => $sizeMap): ?>
                                                            <?php
                                                            $colorName = $colorNames[$cid2] ?? '?';
                                                            $totalColorPcs = 0;
                                                            foreach ($sizeMap as $sleeveData) {
                                                                foreach ($sleeveData as $optData) {
                                                                    $totalColorPcs += isset($optData['quantity']) ? (int) $optData['quantity'] : 1;
                                                                }
                                                            }
                                                            ?>
                                                            <tr data-color-id="<?= e($cid2) ?>">
                                                                <td class="stok-table__color-cell"><?= e($colorName) ?></td>
                                                                <?php foreach ($sizeNames as $sid => $sname): ?>
                                                                    <?php $sleeveData = $sizeMap[$sid] ?? []; ?>
                                                                    <td class="stok-table__qty-cell" <?= !empty($sleeveData) ? 'data-option-ids' : '' ?>>
                                                                        <?php if (!empty($sleeveData)): ?>
                                                                            <span class="stok-table__qty-compact">
                                                                                <?php
                                                                                $parts = [];
                                                                                foreach (['short' => 'S', 'long' => 'L'] as $tipe => $label) {
                                                                                    if (isset($sleeveData[$tipe])) {
                                                                                        $optData = $sleeveData[$tipe];
                                                                                        $optId = $optData['option_id'];
                                                                                        $qty = (int) ($optData['quantity'] ?? 1);
                                                                                        $parts[] = '<span class="stok-table__sleeve-chip" data-option-id="' . e($optId) . '"><span class="stok-table__sleeve-label">' . e($label) . ':</span><span class="stok-table__qty">' . $qty . '</span></span>';
                                                                                    }
                                                                                }
                                                                                echo implode('<span class="stok-table__sleeve-sep">/</span>', $parts);
                                                                                ?>
                                                                            </span>
                                                                        <?php else: ?>
                                                                            <span class="stok-table__empty">-</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                <?php endforeach; ?>
                                                                <td class="stok-table__total-cell"><?= $totalColorPcs ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
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
                <select id="f_category_id" class="form-control" data-no-custom>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= e($cat['category_id']) ?>"><?= e($cat['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field" id="f_nama_produk_field">
                <label class="form-field__label" for="f_nama_produk">Nama Produk</label>
                <select id="f_nama_produk" class="form-control" data-no-custom>
                    <option value="" disabled selected>-- Pilih Produk --</option>
                    <option value="__new__">+ Tambah Produk Baru</option>
                </select>
                <input id="f_nama_produk_new" class="form-control mt-2" type="text" placeholder="Nama produk baru" style="display:none;">
            </div>

            <div class="form-field" id="f_minimum_order_field">
                <label class="form-field__label" for="f_minimum_order">Minimal Pemesanan (pcs)</label>
                <input id="f_minimum_order" class="form-control" type="number" min="1" step="1" value="24" placeholder="24">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_nama_varian">Nama Varian / Bahan</label>
                <input id="f_nama_varian" class="form-control" type="text" placeholder="Contoh: Cotton Combed 24s">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_material">Bahan</label>
                <input id="f_material" class="form-control" type="text">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_harga">Harga Mulai Dari (Rp)</label>
                <input id="f_harga" class="form-control" type="number" min="0" placeholder="62000">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_sleeve_price">Biaya Lengan Panjang (Rp)</label>
                <input id="f_sleeve_price" class="form-control" type="number" min="0" placeholder="5000">
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

<!-- Master Data Modal -->
<div class="modal-overlay" id="masterDataModal" aria-hidden="true">
    <div class="modal" role="dialog" aria-labelledby="masterDataTitle">
        <div class="modal__header">
            <h3 id="masterDataTitle">Master Data</h3>
            <button type="button" class="modal__close" data-modal-close aria-label="Tutup">&times;</button>
        </div>
        <div class="modal__body">
            <!-- List -->
            <div id="masterDataList" class="master-list"></div>

            <!-- Add Form -->
            <div class="master-add-form">
                <div id="masterCategoryField" style="display:none; margin-bottom: var(--space-2);">
                    <label class="form-field__label" for="masterCategorySelect">Kategori *</label>
                    <select id="masterCategorySelect" class="form-control">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat['category_id']) ?>"><?= e($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="text" id="masterDataInput" class="form-control" placeholder="Tambah baru..." maxlength="100">
                <button type="button" id="masterDataAddBtn" class="btn btn--primary btn--sm">
                    <i class="fas fa-plus" aria-hidden="true"></i> Tambah
                </button>
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--ghost" data-modal-close>Tutup</button>
        </div>
    </div>
</div>

<!-- Sablon Type Master Modal -->
<div class="modal-overlay" id="sablonTypeMasterModal" aria-hidden="true">
    <div class="modal" role="dialog" aria-labelledby="sablonTypeMasterTitle">
        <div class="modal__header">
            <h3 id="sablonTypeMasterTitle">Kelola Tipe Sablon</h3>
            <button type="button" class="modal__close" data-modal-close aria-label="Tutup">&times;</button>
        </div>
        <div class="modal__body">
            <div id="sablonTypeMasterList" class="master-list"></div>
            <div id="sablonTypeMasterForm" style="display:none; margin-top: var(--space-3); padding: var(--space-3); background: var(--color-bg-soft); border-radius: var(--radius-sm); border: 1px solid var(--color-border-soft);">
                <input type="hidden" id="sablonTypeFormId">
                <div class="form-field" style="margin-bottom: var(--space-2);">
                    <label class="form-field__label">Nama Tipe Sablon *</label>
                    <input type="text" id="sablonTypeFormNama" class="form-control" maxlength="100" required>
                </div>
                <div class="form-field" style="margin-bottom: var(--space-2);">
                    <label class="form-field__label">Keterangan</label>
                    <input type="text" id="sablonTypeFormKeterangan" class="form-control" maxlength="255">
                </div>
                <div class="form-field" style="margin-bottom: var(--space-2);">
                    <label class="form-field__label">Kategori Produk</label>
                    <div id="sablonTypeFormCategories" style="display:flex; flex-wrap:wrap; gap:var(--space-2);">
                        <?php foreach ($categories as $cat): ?>
                            <label style="display:inline-flex; align-items:center; gap:4px; font-size:var(--fs-xs); cursor:pointer;">
                                <input type="checkbox" name="sablon_category_ids" value="<?= e($cat['category_id']) ?>">
                                <?= e($cat['category_name']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display:flex; gap:var(--space-2); justify-content:flex-end;">
                    <button type="button" class="btn btn--ghost btn--sm" id="sablonTypeFormCancel">Batal</button>
                    <button type="button" class="btn btn--primary btn--sm" id="sablonTypeFormSave">
                        <i class="fas fa-save" aria-hidden="true"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--primary btn--sm" id="sablonTypeMasterAddBtn">
                <i class="fas fa-plus" aria-hidden="true"></i> Tambah Tipe Sablon
            </button>
            <button type="button" class="btn btn--ghost" data-modal-close>Tutup</button>
        </div>
    </div>
</div>

<script>
const SIZES  = <?= json_encode(array_values($sizes)) ?>;
const COLORS = <?= json_encode(array_values($colors)) ?>;
const ALL_SABLON_TYPES = <?= json_encode(array_values($allSablonTypes ?? []), JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const SABLON_TYPE_CATEGORIES = <?= json_encode($sablonTypeCategories ?? [], JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const ENDPOINTS = window.ENDPOINTS = {
    store: '<?= url('/products/store') ?>',
    update: '<?= url('/products/update') ?>',
    destroyBatch: '<?= url('/products/destroy-batch') ?>',
    destroyOption: '<?= url('/products/destroy-option') ?>',
    toggleOptionStatus: '<?= url('/products/toggle-option-status') ?>',
    updateQuantity: '<?= url('/products/update-quantity') ?>',
    manageCategory: '<?= url('/products/manage-category') ?>',
    manageProduct: '<?= url('/products/manage-product') ?>',
    manageSize: '<?= url('/products/manage-size') ?>',
    manageColor: '<?= url('/products/manage-color') ?>',
    manageSablonType: '<?= url('/products/manage-sablon-type') ?>',
    byCategory: '<?= url('/products/by-category') ?>',
    toggleActive: '<?= url('/products/toggle-active') ?>',
    sablonTypes: '<?= url('/products/sablon-types') ?>',
};

// Master data
const CATEGORIES = <?= json_encode(array_values($categories), JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const PRODUCTS = window.PRODUCTS = <?= json_encode($products ?? [], JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const MASTER_CATEGORIES = <?= json_encode(array_values($managementCategories ?? $categories), JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const MASTER_PRODUCTS = <?= json_encode(array_values($managementProducts ?? $products ?? []), JSON_HEX_TAG | JSON_HEX_AMP) ?>;
</script>
<script src="<?= asset('js/ui.js') ?>"></script>
<script src="<?= asset('js/products.js') ?>"></script>
<script>
// ============================================================
// Master Data CRUD
// ============================================================
(function() {
    const dropdown = document.getElementById('masterDataDropdown');
    const modal = document.getElementById('masterDataModal');
    const list = document.getElementById('masterDataList');
    const input = document.getElementById('masterDataInput');
    const addBtn = document.getElementById('masterDataAddBtn');
    const title = document.getElementById('masterDataTitle');
    let currentType = '';
    let currentData = [];

    const CONFIG = {
        categories: { title: 'Kategori', endpoint: ENDPOINTS.manageCategory, nameField: 'category_name', idField: 'category_id', toggleType: 'category' },
        products:   { title: 'Produk',   endpoint: ENDPOINTS.manageProduct,  nameField: 'product_name',   idField: 'product_id', toggleType: 'product' },
        sizes:      { title: 'Ukuran',   endpoint: ENDPOINTS.manageSize,     nameField: 'size_name',     idField: 'size_id' },
        colors:     { title: 'Warna',    endpoint: ENDPOINTS.manageColor,    nameField: 'color_name',    idField: 'color_id' },
    };

    // Toggle dropdown
    document.getElementById('btnMasterData')?.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.hidden = !dropdown.hidden;
    });
    document.addEventListener('click', () => dropdown.hidden = true);

    const categoryField = document.getElementById('masterCategoryField');
    const categorySelect = document.getElementById('masterCategorySelect');

    // Open modal for each type
    dropdown.querySelectorAll('[data-master]').forEach(btn => {
        btn.addEventListener('click', () => {
            currentType = btn.dataset.master;
            dropdown.hidden = true;
            if (currentType === 'sablontypes') {
                window.openSablonTypeMasterModal();
                return;
            }
            const cfg = CONFIG[currentType];
            title.textContent = 'Kelola ' + cfg.title;
            input.placeholder = 'Tambah ' + cfg.title.toLowerCase() + ' baru...';
            // Show category selector only for products
            if (categoryField) {
                categoryField.style.display = currentType === 'products' ? '' : 'none';
            }
            loadList();
            window.SiblingsUI?.openModal('masterDataModal');
        });
    });

    // BUG 3 fix: XSS escape utility
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // BUG 2 fix: get fresh data from local arrays (mutated after CRUD)
    function getLocalArray() {
        if (currentType === 'categories') return MASTER_CATEGORIES;
        if (currentType === 'products') return MASTER_PRODUCTS;
        if (currentType === 'sizes') return SIZES;
        if (currentType === 'colors') return COLORS;
        return [];
    }

    function loadList() {
        currentData = [...getLocalArray()];
        renderList();
    }

    function renderList() {
        const cfg = CONFIG[currentType];
        if (currentData.length === 0) {
            list.innerHTML = '<p class="master-list__empty">Belum ada data.</p>';
            return;
        }
        list.innerHTML = currentData.map(item => {
            const isActive = String(item.is_active ?? '1') === '1';
            const canToggle = Boolean(cfg.toggleType);
            return `
            <div class="master-list__item${isActive ? '' : ' master-list__item--inactive'}" data-id="${item[cfg.idField]}">
                <span class="master-list__name">${escapeHtml(item[cfg.nameField])}${isActive ? '' : ' <small>(Nonaktif)</small>'}</span>
                <div class="master-list__actions">
                    <button type="button" class="btn btn--ghost btn--sm master-list__edit" data-id="${item[cfg.idField]}" data-name="${escapeHtml(item[cfg.nameField])}" title="Edit">
                        <i class="fas fa-pen" aria-hidden="true"></i>
                    </button>
                    ${canToggle ? `
                    <button type="button" class="btn ${isActive ? 'btn--danger' : 'btn--primary'} btn--sm master-list__toggle" data-id="${item[cfg.idField]}" data-active="${isActive ? '1' : '0'}" title="${isActive ? 'Nonaktifkan' : 'Aktifkan'}">
                        <i class="fas fa-power-off" aria-hidden="true"></i>
                    </button>` : `
                    <button type="button" class="btn btn--danger btn--sm master-list__delete" data-id="${item[cfg.idField]}" title="Hapus">
                        <i class="fas fa-trash" aria-hidden="true"></i>
                    </button>`}
                </div>
            </div>`;
        }).join('');

        // Edit handlers
        list.querySelectorAll('.master-list__edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const oldName = btn.dataset.name;
                const newName = prompt('Edit nama:', oldName);
                if (!newName || newName === oldName) return;
                updateItem(id, newName);
            });
        });

        // Toggle active handlers for no-hard-delete data
        list.querySelectorAll('.master-list__toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const isActive = btn.dataset.active === '1';
                if (!confirm(`Yakin ingin ${isActive ? 'menonaktifkan' : 'mengaktifkan'}?`)) return;
                toggleMasterItem(id, !isActive);
            });
        });

        // Delete handlers for master data without restore UI yet
        list.querySelectorAll('.master-list__delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                if (!confirm('Yakin ingin menghapus?')) return;
                deleteItem(id);
            });
        });
    }

    // Add
    addBtn?.addEventListener('click', async () => {
        const cfg = CONFIG[currentType];
        const name = input.value.trim();
        if (!name) return;

        // Validate category for products
        if (currentType === 'products' && !categorySelect?.value) {
            window.SiblingsUI?.toast?.('Pilih kategori terlebih dahulu.', 'warning');
            categorySelect?.focus();
            return;
        }

        addBtn.disabled = true;
        try {
            const body = { action: 'create', [cfg.nameField]: name };
            // Add category_id for products
            if (currentType === 'products' && categorySelect?.value) {
                body.category_id = parseInt(categorySelect.value, 10);
            }
            const resp = await fetch(cfg.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.SiblingsUI?.getCsrfToken() || '' },
                body: JSON.stringify(body),
            });
            const data = await resp.json();
            if (data.success) {
                input.value = '';
                window.SiblingsUI?.toast?.(cfg.title + ' ditambahkan.', 'success');
                location.reload();
            } else {
                window.SiblingsUI?.toast?.(data.message || 'Gagal.', 'danger');
            }
        } catch {
            window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
        }
        addBtn.disabled = false;
    });

    async function updateItem(id, newName) {
        const cfg = CONFIG[currentType];
        try {
            const body = { action: 'update', [cfg.idField]: parseInt(id), [cfg.nameField]: newName };
            const resp = await fetch(cfg.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.SiblingsUI?.getCsrfToken() || '' },
                body: JSON.stringify(body),
            });
            const data = await resp.json();
            if (data.success) {
                // BUG 2 fix: update local array
                const arr = getLocalArray();
                const item = arr.find(i => String(i[cfg.idField]) === String(id));
                if (item) item[cfg.nameField] = newName;
                loadList();
                window.SiblingsUI?.toast?.(cfg.title + ' diperbarui.', 'success');
            } else {
                window.SiblingsUI?.toast?.(data.message || 'Gagal.', 'danger');
            }
        } catch {
            window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
        }
    }

    async function toggleMasterItem(id, active) {
        const cfg = CONFIG[currentType];
        try {
            const resp = await fetch(ENDPOINTS.toggleActive, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.SiblingsUI?.getCsrfToken() || '' },
                body: JSON.stringify({ type: cfg.toggleType, id: parseInt(id), active }),
            });
            const data = await resp.json();
            if (data.success) {
                const arr = getLocalArray();
                const item = arr.find(i => String(i[cfg.idField]) === String(id));
                if (item) item.is_active = active ? 1 : 0;
                loadList();
                window.SiblingsUI?.toast?.(`${cfg.title} ${active ? 'diaktifkan' : 'dinonaktifkan'}.`, 'success');
            } else {
                window.SiblingsUI?.toast?.(data.message || 'Gagal.', 'danger');
            }
        } catch {
            window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
        }
    }

    async function deleteItem(id) {
        const cfg = CONFIG[currentType];
        try {
            const body = { action: 'delete', [cfg.idField]: parseInt(id) };
            const resp = await fetch(cfg.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.SiblingsUI?.getCsrfToken() || '' },
                body: JSON.stringify(body),
            });
            const data = await resp.json();
            if (data.success) {
                // BUG 2 fix: remove from local array
                const arr = getLocalArray();
                const idx = arr.findIndex(i => String(i[cfg.idField]) === String(id));
                if (idx !== -1) arr.splice(idx, 1);
                loadList();
                window.SiblingsUI?.toast?.(cfg.title + ' dihapus.', 'success');
            } else {
                window.SiblingsUI?.toast?.(data.message || 'Gagal.', 'danger');
            }
        } catch {
            window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
        }
    }

    // Enter key to add
    input?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') addBtn.click();
    });
})();

// ============================================================
// Sablon Type Master Data CRUD
// ============================================================
(function() {
    const modal = document.getElementById('sablonTypeMasterModal');
    const list = document.getElementById('sablonTypeMasterList');
    const form = document.getElementById('sablonTypeMasterForm');
    const formId = document.getElementById('sablonTypeFormId');
    const formNama = document.getElementById('sablonTypeFormNama');
    const formKeterangan = document.getElementById('sablonTypeFormKeterangan');
    const addBtn = document.getElementById('sablonTypeMasterAddBtn');
    const saveBtn = document.getElementById('sablonTypeFormSave');
    const cancelBtn = document.getElementById('sablonTypeFormCancel');

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    const categoryCheckboxes = document.querySelectorAll('#sablonTypeFormCategories input[name="sablon_category_ids"]');

    function renderList() {
        if (ALL_SABLON_TYPES.length === 0) {
            list.innerHTML = '<p class="master-list__empty">Belum ada tipe sablon.</p>';
            return;
        }
        list.innerHTML = ALL_SABLON_TYPES.map(s => {
            const cats = (SABLON_TYPE_CATEGORIES[s.sablon_type_id] || [])
                .map(cid => { const c = CATEGORIES.find(x => x.category_id == cid); return c ? c.category_name : ''; })
                .filter(Boolean).join(', ');
            return `
            <div class="master-list__item" data-id="${s.sablon_type_id}">
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:var(--fw-semibold); font-size:var(--fs-sm);">${escapeHtml(s.sablon_name)}</div>
                    <div style="font-size:var(--fs-xs); color:var(--color-text-muted);">
                        ${cats || 'Semua kategori'}${s.notes ? ' · ' + escapeHtml(s.notes) : ''}
                    </div>
                </div>
                <div class="master-list__actions">
                    <button type="button" class="btn btn--ghost btn--sm master-list__edit" data-id="${s.sablon_type_id}" title="Edit">
                        <i class="fas fa-pen" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn--danger btn--sm master-list__delete" data-id="${s.sablon_type_id}" title="Hapus">
                        <i class="fas fa-trash" aria-hidden="true"></i>
                    </button>
                </div>
            </div>`;
        }).join('');

        list.querySelectorAll('.master-list__edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const s = ALL_SABLON_TYPES.find(x => String(x.sablon_type_id) === btn.dataset.id);
                if (s) openForm(s);
            });
        });

        list.querySelectorAll('.master-list__delete').forEach(btn => {
            btn.addEventListener('click', async () => {
                if (!confirm('Yakin ingin menghapus tipe sablon ini?')) return;
                try {
                    const resp = await fetch(ENDPOINTS.manageSablonType, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.SiblingsUI?.getCsrfToken() || '' },
                        body: JSON.stringify({ action: 'delete', sablon_type_id: parseInt(btn.dataset.id) }),
                    });
                    const data = await resp.json();
                    if (data.success) {
                        const idx = ALL_SABLON_TYPES.findIndex(x => String(x.sablon_type_id) === btn.dataset.id);
                        if (idx !== -1) ALL_SABLON_TYPES.splice(idx, 1);
                        delete SABLON_TYPE_CATEGORIES[parseInt(btn.dataset.id)];
                        renderList();
                        window.SiblingsUI?.toast?.('Tipe sablon dihapus.', 'success');
                    } else {
                        window.SiblingsUI?.toast?.(data.message || 'Gagal.', 'danger');
                    }
                } catch {
                    window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
                }
            });
        });
    }

    function openForm(sablonType) {
        formId.value = sablonType ? sablonType.sablon_type_id : '';
        formNama.value = sablonType ? sablonType.sablon_name : '';
        formKeterangan.value = sablonType ? (sablonType.notes || '') : '';
        const linkedCats = sablonType ? (SABLON_TYPE_CATEGORIES[sablonType.sablon_type_id] || []) : [];
        categoryCheckboxes.forEach(cb => {
            cb.checked = linkedCats.includes(parseInt(cb.value));
        });
        form.style.display = 'block';
        formNama.focus();
    }

    function closeForm() {
        form.style.display = 'none';
        formId.value = '';
        formNama.value = '';
        formKeterangan.value = '';
        categoryCheckboxes.forEach(cb => { cb.checked = false; });
    }

    function getSelectedCategoryIds() {
        return [...categoryCheckboxes].filter(cb => cb.checked).map(cb => parseInt(cb.value));
    }

    window.openSablonTypeMasterModal = function() {
        closeForm();
        renderList();
        window.SiblingsUI?.openModal('sablonTypeMasterModal');
    };

    addBtn?.addEventListener('click', () => openForm(null));
    cancelBtn?.addEventListener('click', closeForm);

    saveBtn?.addEventListener('click', async () => {
        const nama = formNama.value.trim();
        if (!nama) { formNama.focus(); return; }

        const isEdit = !!formId.value;
        const body = {
            action: isEdit ? 'update' : 'create',
            sablon_name: nama,
            notes: formKeterangan.value.trim() || null,
            category_ids: getSelectedCategoryIds(),
        };
        if (isEdit) body.sablon_type_id = parseInt(formId.value);

        saveBtn.disabled = true;
        try {
            const resp = await fetch(ENDPOINTS.manageSablonType, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.SiblingsUI?.getCsrfToken() || '' },
                body: JSON.stringify(body),
            });
            const data = await resp.json();
            if (data.success) {
                if (isEdit) {
                    const idx = ALL_SABLON_TYPES.findIndex(x => String(x.sablon_type_id) === formId.value);
                    if (idx !== -1) {
                        ALL_SABLON_TYPES[idx].sablon_name = nama;
                        ALL_SABLON_TYPES[idx].notes = body.notes;
                    }
                } else {
                    ALL_SABLON_TYPES.push({ sablon_type_id: data.sablon_type_id, sablon_name: nama, notes: body.notes, is_active: 1 });
                }
                SABLON_TYPE_CATEGORIES[isEdit ? parseInt(formId.value) : data.sablon_type_id] = body.category_ids;
                closeForm();
                renderList();
                window.SiblingsUI?.toast?.('Tipe sablon ' + (isEdit ? 'diperbarui' : 'ditambahkan') + '.', 'success');
            } else {
                window.SiblingsUI?.toast?.(data.message || 'Gagal.', 'danger');
            }
        } catch {
            window.SiblingsUI?.toast?.('Terjadi kesalahan.', 'danger');
        }
        saveBtn.disabled = false;
    });

    modal?.querySelectorAll('[data-modal-close]').forEach(el => {
        el.addEventListener('click', closeForm);
    });
})();
</script>
</body>
</html>
