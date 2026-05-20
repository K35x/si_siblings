<?php
$stocks = $stocks ?? [];
$stockSummary = $stockSummary ?? ['total_qty' => 0, 'low_stock_qty' => 0, 'low_stock_items' => 0];
$stockCategories = $stockCategories ?? [];
$sidebarRole = $sidebarRole ?? 'owner';
$activeMenu = $activeMenu ?? 'products';

$groupedStocks = [];
foreach ($stocks as $stock) {
    $category = $stock['nama_kategori'] ?? 'Tanpa Kategori';
    $variant = $stock['nama_varian'] ?? 'Tanpa Varian';
    $groupedStocks[$category][$variant][] = $stock;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siblings.co - Stok Produk</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/sidebar.css') ?>">
<link rel="stylesheet" href="<?= asset('css/products.css') ?>">
</head>
<body>

<?php
// ──────────────────────────────────────────────────────────────────────────────
// Susun data: grouping variant → per kategori → per varian → size×warna
// Data dari controller: $categories, $variants, $options, $sizes, $colors
// ──────────────────────────────────────────────────────────────────────────────

// Indeks options per variant_id: [variant_id => [size_id => [color_id => option_id]]]
$optionMap = [];
foreach ($options as $opt) {
  $optionMap[$opt['variant_id']][$opt['size_id']][$opt['color_id']] = [
    'option_id' => $opt['option_id'],
    'qty'       => isset($opt['qty']) ? (int) $opt['qty'] : 1,
  ];
}

// Susun kategori → produk → varian
$grouped = []; // [category_id => ['nama' => ..., 'variants' => [...]]]
foreach ($variants as $v) {
    $cid = $v['category_id'];
    if (!isset($grouped[$cid])) {
        $grouped[$cid] = [
            'nama'     => $v['nama_kategori'],
            'variants' => [],
        ];
    }
    $grouped[$cid]['variants'][] = $v;
}

// Helper: total option per varian
function countOptions(array $optionMap, int $variantId): int {
  if (!isset($optionMap[$variantId])) return 0;
  $sum = 0;
  foreach ($optionMap[$variantId] as $sizeMap) {
    foreach ($sizeMap as $colorData) {
      $sum += isset($colorData['qty']) ? (int) $colorData['qty'] : 1;
    }
  }
  return $sum;
}

// Helper: mapping nama warna (dari DB) → kode hex CSS
function colorNameToHex(string $name): string {
    return match (mb_strtolower(trim($name))) {
        'merah'          => '#ef4444',
        'jingga', 'oranye', 'orange' => '#f97316',
        'kuning'         => '#eab308',
        'hijau'          => '#22c55e',
        'biru'           => '#3b82f6',
        'nila', 'indigo' => '#6366f1',
        'ungu', 'violet' => '#8b5cf6',
        'putih'          => '#ffffff',
        'hitam'          => '#111827',
        'abu', 'abu-abu', 'gray', 'grey' => '#9ca3af',
        'pink', 'merah muda' => '#ec4899',
        'coklat', 'brown'   => '#92400e',
        'maroon'         => '#7f1d1d',
        'tosca', 'teal'  => '#14b8a6',
        'navy', 'biru tua' => '#1e3a5f',
        default          => '#cbd5e1', // fallback abu muda
    };
}
?>

<div class="container">
<?php
$sidebarRole = $sidebarRole ?? 'owner';
$activeMenu  = $activeMenu  ?? 'products';
include __DIR__ . '/../layouts/sidebar.php';
?>

<!-- MAIN -->
<div class="main-content">
  <div class="header-photo"></div>

  <div class="content">
    <div class="right">

      <!-- HEADER -->
      <div class="right-header">
        <div class="header-left">
          <h3><i class="fas fa-box"></i> Stok Barang</h3>
          <p>Manajemen produk & varian bahan</p>
        </div>

        <div class="header-actions">
          <!-- Filter kategori -->
          <select id="filterKategori" onchange="filterByKategori(this.value)">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>">
              <?= htmlspecialchars($cat['nama_kategori']) ?>
            </option>
            <?php endforeach; ?>
          </select>

          <button class="btn" onclick="openModalTambah()">
            <i class="fas fa-plus"></i> Tambah
          </button>
          <button class="btn" id="btnHapusMode" onclick="toggleDeleteMode()">
            <i class="fas fa-trash"></i> Hapus
          </button>
        </div>
      </div>

      <!-- WRAPPER PRODUK -->
      <div class="product-wrapper" id="productWrapper">

        <?php if (empty($grouped)): ?>
          <div style="text-align:center;padding:40px;color:#94a3b8;">
            <i class="fas fa-box-open" style="font-size:40px;margin-bottom:10px;"></i>
            <p>Belum ada data produk</p>
          </div>
        <?php endif; ?>

        <?php foreach ($grouped as $cid => $cat): ?>
        <?php
          // Hitung total option di seluruh varian kategori ini
          $totalCat = 0;
          foreach ($cat['variants'] as $v) {
              $totalCat += countOptions($optionMap, $v['variant_id']);
          }
        ?>
        <div class="kategori-item" data-category-id="<?= $cid ?>">
          <div class="kategori-header" onclick="toggleKategori(this)">
            <div class="left">
              <input type="checkbox" class="pilih-hapus" style="display:none;"
                     data-category-id="<?= $cid ?>"
                     onclick="event.stopPropagation()">
              <div class="icon"><i class="fas fa-tshirt"></i></div>
              <span class="nama"><?= htmlspecialchars($cat['nama']) ?></span>
            </div>
            <div class="right">
              <span class="total"><?= $totalCat ?> opsi</span>
              <span class="arrow"><i class="fas fa-chevron-down"></i></span>
            </div>
          </div>

          <div class="kategori-content">
            <?php foreach ($cat['variants'] as $v): ?>
            <?php
              $vid = $v['variant_id'];
              $sizeGroups = $optionMap[$vid] ?? [];
              $totalVariant = countOptions($optionMap, $vid);
            ?>
            <div class="kain" data-variant-id="<?= $vid ?>">
              <!-- Header varian / bahan -->
              <div class="kain-title" onclick="toggleKain(this)" style="cursor:pointer;display:flex;justify-content:space-between;align-items:center;">
                <span><?= htmlspecialchars($v['nama_varian']) ?>
                  <small style="color:#94a3b8;font-size:12px;margin-left:8px;">(<?= $totalVariant ?> pcs)</small>
                </span>
                <div style="display:flex;gap:6px;" onclick="event.stopPropagation()">
                  <button class="btn" style="padding:4px 10px;font-size:12px;"
                          onclick="openModalEdit(<?= htmlspecialchars(json_encode($v)) ?>, <?= htmlspecialchars(json_encode($optionMap[$vid] ?? [])) ?>)">
                    <i class="fas fa-pen"></i>
                  </button>
                  <button class="btn btn-cancel" style="padding:4px 10px;font-size:12px;"
                          onclick="hapusVarian(<?= $vid ?>)">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>

              <!-- Info harga -->
              <div style="font-size:12px;color:#94a3b8;margin-bottom:8px;">
                Mulai Rp <?= number_format($v['harga_start_from'], 0, ',', '.') ?> &nbsp;|&nbsp;
                Bahan: <?= htmlspecialchars($v['bahan'] ?? '-') ?>
              </div>

              <?php if (empty($sizeGroups)): ?>
              <div style="font-size:13px;color:#cbd5e1;padding:8px 0;">
                Belum ada ukuran & warna
              </div>
              <?php else: ?>

              <?php foreach ($sizeGroups as $sid => $colorMap): ?>
              <?php
                $sizeName  = '';
                foreach ($sizes as $sz) {
                    if ($sz['size_id'] == $sid) { $sizeName = $sz['size_name']; break; }
                }
                $totalColors = count($colorMap);
                $totalSizePcs = 0;
                foreach ($colorMap as $cdata) { $totalSizePcs += isset($cdata['qty']) ? (int)$cdata['qty'] : 1; }
              ?>
              <div class="ukuran-item" data-size-id="<?= $sid ?>">
                <div class="ukuran-row" onclick="toggleUkuran(this)" style="cursor:pointer;">
                  <span><?= htmlspecialchars($sizeName) ?></span>
                  <span><?= $totalColors ?> warna &bull; <?= $totalSizePcs ?> pcs</span>
                  <div class="aksi">
                    <button class="btn-delete" title="Hapus semua warna ukuran ini"
                            onclick="event.stopPropagation(); hapusSemuaWarnaUkuran(<?= $vid ?>, <?= $sid ?>)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>

                <div class="warna-list" style="display:none;">
                  <?php foreach ($colorMap as $cid2 => $optData): ?>
                  <?php
                    $colorName = '';
                    foreach ($colors as $cl) {
                        if ($cl['color_id'] == $cid2) { $colorName = $cl['color_name']; break; }
                    }
                    $dotHex    = colorNameToHex($colorName);
                    $dotBorder = (mb_strtolower(trim($colorName)) === 'putih')
                                 ? 'border:1px solid #d1d5db;' : '';
                    $optId = is_array($optData) ? $optData['option_id'] : $optData;
                    $qty   = is_array($optData) ? (int)($optData['qty'] ?? 1) : 1;
                  ?>
                  <div class="warna-row" data-option-id="<?= $optId ?>">
                    <span style="--dot-color:<?= $dotHex ?>; --dot-border:<?= $dotBorder ?>;">
                      <?= htmlspecialchars($colorName) ?>
                    </span>
                    <span style="color:#94a3b8;font-size:13px;"><?= $qty ?> pcs</span>
                    <div class="aksi">
                      <button class="btn-delete" title="Hapus warna ini"
                              onclick="hapusOption(<?= $optId ?>, this)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endforeach; ?>
              <?php endif; ?>

            </div><!-- .kain -->
            <?php endforeach; ?>
          </div><!-- .kategori-content -->
        </div><!-- .kategori-item -->
        <?php endforeach; ?>

      </div><!-- .product-wrapper -->
    </div><!-- .right -->
  </div><!-- .content -->
</div><!-- .main-content -->
</div><!-- .container -->


<!-- ══════════════════════════════════════════════════════════════
     MODAL TAMBAH VARIAN
══════════════════════════════════════════════════════════════ -->
<div class="modal" id="modalTambah">
  <div class="modal-content" style="max-width:420px;">
    <h3><i class="fas fa-plus-circle" style="color:#4A3328;margin-right:8px;"></i>Tambah Varian</h3>

    <div class="input-group">
      <label>Kategori</label>
      <select id="t_category_id" style="padding:10px 12px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;outline:none;">
        <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['nama_kategori']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="input-group">
      <label>Nama Produk</label>
      <input type="text" id="t_nama_produk" placeholder="Contoh: Tshirt Basic">
    </div>

    <div class="input-group">
      <label>Nama Varian / Bahan</label>
      <input type="text" id="t_nama_varian" placeholder="Contoh: Cotton Combed 24s">
    </div>

    <div class="input-group">
      <label>Bahan</label>
      <input type="text" id="t_bahan" placeholder="Contoh: Cotton Combed 24s">
    </div>

    <div class="input-group">
      <label>Tipe Sablon / Bordir</label>
      <input type="text" id="t_tipe_sablon" placeholder="Contoh: Plastisol / DTF">
    </div>

    <div class="input-group">
      <label>Harga Mulai Dari (Rp)</label>
      <input type="number" id="t_harga" placeholder="Contoh: 62000" min="0">
    </div>

    <!-- Pilih ukuran & warna -->
    <div class="input-group">
      <label>Ukuran & Warna <small style="color:#94a3b8;">(boleh pilih lebih dari satu kombinasi)</small></label>
      <div id="t_options_list" style="display:flex;flex-direction:column;gap:6px;margin-top:4px;"></div>
      <button type="button" onclick="tambahBarisPilihan('t_options_list')"
              style="margin-top:8px;padding:6px 12px;border:1px dashed #4A3328;border-radius:8px;background:transparent;color:#4A3328;cursor:pointer;font-size:13px;">
        <i class="fas fa-plus"></i> Tambah Kombinasi
      </button>
    </div>

    <div class="modal-action">
      <button class="btn btn-cancel" onclick="closeModal('modalTambah')">Batal</button>
      <button class="btn" onclick="submitTambah()">Simpan</button>
    </div>
  </div>
</div>
</div>
</div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     MODAL EDIT VARIAN
══════════════════════════════════════════════════════════════ -->
<div class="modal" id="modalEdit">
  <div class="modal-content" style="max-width:420px;">
    <h3><i class="fas fa-pen" style="color:#4A3328;margin-right:8px;"></i>Edit Varian</h3>
    <input type="hidden" id="e_variant_id">

    <div class="input-group">
      <label>Nama Varian / Bahan</label>
      <input type="text" id="e_nama_varian">
    </div>

    <div class="input-group">
      <label>Bahan</label>
      <input type="text" id="e_bahan">
    </div>

    <div class="input-group">
      <label>Tipe Sablon / Bordir</label>
      <input type="text" id="e_tipe_sablon">
    </div>

    <div class="input-group">
      <label>Harga Mulai Dari (Rp)</label>
      <input type="number" id="e_harga" min="0">
    </div>

    <!-- Pilih ukuran & warna -->
    <div class="input-group">
      <label>Ukuran & Warna</label>
      <div id="e_options_list" style="display:flex;flex-direction:column;gap:6px;margin-top:4px;"></div>
      <button type="button" onclick="tambahBarisPilihan('e_options_list')"
              style="margin-top:8px;padding:6px 12px;border:1px dashed #4A3328;border-radius:8px;background:transparent;color:#4A3328;cursor:pointer;font-size:13px;">
        <i class="fas fa-plus"></i> Tambah Kombinasi
      </button>
    </div>

    <div class="modal-action">
      <button class="btn btn-cancel" onclick="closeModal('modalEdit')">Batal</button>
      <button class="btn" onclick="submitEdit()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     DATA UKURAN & WARNA (untuk JS dropdown)
══════════════════════════════════════════════════════════════ -->
<script>
const SIZES  = <?= json_encode(array_values($sizes)) ?>;
const COLORS = <?= json_encode(array_values($colors)) ?>;
const ENDPOINTS = {
  store: '<?= url('/products/store') ?>',
  update: '<?= url('/products/update') ?>',
  destroy: '<?= url('/products/destroy') ?>',
  destroyOption: '<?= url('/products/destroy-option') ?>',
};

// ── TOGGLE ACCORDION ──────────────────────────────────────────
function toggleKategori(header) {
    const item = header.closest('.kategori-item');
    item.classList.toggle('active');
}

function toggleKain(title) {
    const kain    = title.closest('.kain');
    const content = kain.querySelectorAll('.ukuran-item');
    const isOpen  = kain.classList.contains('active');
    kain.classList.toggle('active');
    content.forEach(el => el.style.display = isOpen ? 'none' : 'block');
}

function toggleUkuran(row) {
    const item   = row.closest('.ukuran-item');
    const list   = item.querySelector('.warna-list');
    if (!list) return;
    list.style.display = list.style.display === 'none' ? 'flex' : 'none';
}

// ── FILTER KATEGORI ───────────────────────────────────────────
function filterByKategori(cid) {
    document.querySelectorAll('.kategori-item').forEach(el => {
        el.style.display = (!cid || el.dataset.categoryId == cid) ? '' : 'none';
    });
}

// ── MODAL ─────────────────────────────────────────────────────
function openModalTambah() {
    document.getElementById('t_nama_produk').value = '';
    document.getElementById('t_nama_varian').value = '';
    document.getElementById('t_bahan').value       = '';
    document.getElementById('t_tipe_sablon').value = '';
    document.getElementById('t_harga').value       = '';
    document.getElementById('t_options_list').innerHTML = '';
    tambahBarisPilihan('t_options_list');
    showModal('modalTambah');
}

function openModalEdit(varian, optionMap) {
    document.getElementById('e_variant_id').value   = varian.variant_id;
    document.getElementById('e_nama_varian').value  = varian.nama_varian;
    document.getElementById('e_bahan').value        = varian.bahan ?? '';
    document.getElementById('e_tipe_sablon').value  = varian.tipe_sablon_bordir ?? '';
    document.getElementById('e_harga').value        = varian.harga_start_from;

    const list = document.getElementById('e_options_list');
    list.innerHTML = '';

    // Isi baris pilihan dari optionMap {size_id: {color_id: {option_id, qty}}}
    let hasPilihan = false;
    for (const [sizeId, colorMap] of Object.entries(optionMap)) {
      for (const [colorId, optData] of Object.entries(colorMap)) {
        const qty = (optData && optData.qty) ? parseInt(optData.qty) : 1;
        tambahBarisPilihan('e_options_list', parseInt(sizeId), parseInt(colorId), qty);
        hasPilihan = true;
      }
    }
    if (!hasPilihan) tambahBarisPilihan('e_options_list');
    showModal('modalEdit');
}

function showModal(id) {
    const m = document.getElementById(id);
    m.style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Tutup modal saat klik backdrop
window.addEventListener('click', e => {
    ['modalTambah', 'modalEdit'].forEach(id => {
        if (e.target.id === id) closeModal(id);
    });
});

// ── BARIS PILIHAN UKURAN × WARNA ─────────────────────────────
function tambahBarisPilihan(containerId, selectedSize = null, selectedColor = null, selectedQty = 1) {
  const container = document.getElementById(containerId);
  const row = document.createElement('div');
  row.style.cssText = 'display:flex;gap:6px;align-items:center;';

  const sizeSelect = document.createElement('select');
  sizeSelect.style.cssText = 'flex:1;padding:8px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;';
  SIZES.forEach(s => {
    const opt = document.createElement('option');
    opt.value = s.size_id;
    opt.textContent = s.size_name;
    if (selectedSize && s.size_id == selectedSize) opt.selected = true;
    sizeSelect.appendChild(opt);
  });

  const colorSelect = document.createElement('select');
  colorSelect.style.cssText = 'flex:1;padding:8px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;';
  COLORS.forEach(c => {
    const opt = document.createElement('option');
    opt.value = c.color_id;
    opt.textContent = c.color_name;
    if (selectedColor && c.color_id == selectedColor) opt.selected = true;
    colorSelect.appendChild(opt);
  });

  const qtyInput = document.createElement('input');
  qtyInput.type = 'number';
  qtyInput.min = 1;
  qtyInput.value = selectedQty || 1;
  qtyInput.style.cssText = 'width:80px;padding:8px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;';

  const btnHapus = document.createElement('button');
  btnHapus.type = 'button';
  btnHapus.innerHTML = '<i class="fas fa-times"></i>';
  btnHapus.style.cssText = 'padding:6px 10px;border:none;background:#fee2e2;color:#dc2626;border-radius:8px;cursor:pointer;';
  btnHapus.onclick = () => row.remove();

  row.appendChild(sizeSelect);
  row.appendChild(colorSelect);
  row.appendChild(qtyInput);
  row.appendChild(btnHapus);
  container.appendChild(row);
}

function collectOptions(containerId) {
    const rows = document.getElementById(containerId).querySelectorAll('div');
    const opts = [];
    rows.forEach(row => {
        const selects = row.querySelectorAll('select');
    if (selects.length >= 2) {
      const qtyInput = row.querySelector('input[type="number"]');
      const qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
      opts.push({ size_id: parseInt(selects[0].value), color_id: parseInt(selects[1].value), qty });
    }
    });
    return opts;
}

// ── SUBMIT TAMBAH ─────────────────────────────────────────────
async function submitTambah() {
    const payload = {
        category_id : parseInt(document.getElementById('t_category_id').value),
        nama_produk : document.getElementById('t_nama_produk').value.trim(),
        nama_varian : document.getElementById('t_nama_varian').value.trim(),
        bahan       : document.getElementById('t_bahan').value.trim(),
        tipe_sablon : document.getElementById('t_tipe_sablon').value.trim(),
        harga       : parseFloat(document.getElementById('t_harga').value) || 0,
        options     : collectOptions('t_options_list'),
    };

    if (!payload.nama_produk || !payload.nama_varian) {
        alert('Nama produk dan nama varian wajib diisi!'); return;
    }

    const res  = await fetch(ENDPOINTS.store, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
    const json = await res.json();

    if (json.success) {
        closeModal('modalTambah');
        location.reload();
    } else {
        alert('Gagal menyimpan: ' + json.message);
    }
}

// ── SUBMIT EDIT ───────────────────────────────────────────────
async function submitEdit() {
    const payload = {
        variant_id  : parseInt(document.getElementById('e_variant_id').value),
        nama_varian : document.getElementById('e_nama_varian').value.trim(),
        bahan       : document.getElementById('e_bahan').value.trim(),
        tipe_sablon : document.getElementById('e_tipe_sablon').value.trim(),
        harga       : parseFloat(document.getElementById('e_harga').value) || 0,
        options     : collectOptions('e_options_list'),
    };

    const res  = await fetch(ENDPOINTS.update, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
    const json = await res.json();

    if (json.success) {
        closeModal('modalEdit');
        location.reload();
    } else {
        alert('Gagal menyimpan: ' + json.message);
    }
}

// ── HAPUS VARIAN (soft delete) ────────────────────────────────
async function hapusVarian(variantId) {
    if (!confirm('Yakin ingin menonaktifkan varian ini?')) return;

    const res  = await fetch(ENDPOINTS.destroy, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ variant_id: variantId }),
    });
    const json = await res.json();

    if (json.success) {
        location.reload();
    } else {
        alert('Gagal menghapus: ' + json.message);
    }
}

// ── HAPUS SATU WARNA (option_id) ─────────────────────────────
async function hapusOption(optionId, btn) {
    if (!confirm('Hapus warna ini?')) return;

    const res  = await fetch(ENDPOINTS.destroyOption, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ option_id: optionId }),
    });
    const json = await res.json();

    if (json.success) {
        btn.closest('.warna-row').remove();
    } else {
        alert('Gagal menghapus: ' + json.message);
    }
}

// ── HAPUS SEMUA WARNA DALAM SATU UKURAN ──────────────────────
async function hapusSemuaWarnaUkuran(variantId, sizeId) {
    if (!confirm('Hapus semua warna pada ukuran ini?')) return;

    // Kumpulkan semua option_id di ukuran ini
    // Kita fetch dari DOM (sudah ada data-option-id)
    const item    = event.target.closest('.ukuran-item') ||
                    document.querySelector(`[data-variant-id="${variantId}"] [data-size-id="${sizeId}"]`);
    const rows    = item ? item.querySelectorAll('.warna-row') : [];
    const optIds  = [...rows].map(r => parseInt(r.dataset.optionId)).filter(Boolean);

    for (const oid of optIds) {
        await fetch(ENDPOINTS.destroyOption, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ option_id: oid }),
        });
    }
    location.reload();
}

// ── MODE HAPUS KATEGORI (checkbox) ───────────────────────────
let deleteMode = false;
function toggleDeleteMode() {
    deleteMode = !deleteMode;
    const btn  = document.getElementById('btnHapusMode');

    document.querySelectorAll('.pilih-hapus').forEach(cb => {
        cb.style.display = deleteMode ? 'inline-block' : 'none';
        cb.checked = false;
    });

    if (deleteMode) {
        btn.style.background = '#fee2e2';
        btn.style.color = '#dc2626';
        if (!document.getElementById('hapusTerpilih')) {
            const b = document.createElement('button');
            b.id = 'hapusTerpilih';
            b.className = 'btn';
            b.style.background = '#dc2626';
            b.style.color = '#fff';
            b.innerHTML = '<i class="fas fa-trash"></i> Hapus Terpilih';
            b.onclick = hapusTerpilih;
            document.querySelector('.header-actions').appendChild(b);
        }
    } else {
        btn.style.background = '';
        btn.style.color = '';
        document.getElementById('hapusTerpilih')?.remove();
    }
}

async function hapusTerpilih() {
    const checked = [...document.querySelectorAll('.pilih-hapus:checked')];
    if (!checked.length) { alert('Pilih kategori terlebih dahulu!'); return; }
    if (!confirm(`Nonaktifkan ${checked.length} varian dalam kategori terpilih?`)) return;

    // Kumpulkan semua variant_id dalam kategori yang di-check
    for (const cb of checked) {
        const kategoriEl = cb.closest('.kategori-item');
        const variantEls = kategoriEl.querySelectorAll('[data-variant-id]');
        for (const el of variantEls) {
            await fetch(ENDPOINTS.destroy, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ variant_id: parseInt(el.dataset.variantId) }),
            });
        }
    }
    location.reload();
}
</script>

</body>
</html>