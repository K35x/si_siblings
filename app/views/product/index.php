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
<link rel="stylesheet" href="<?= asset('css/products.css') ?>">
</head>
<body>

<div class="container">
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>

<!-- MAIN -->
<div class="main-content">

  <div class="header-photo"></div>

<div class="content">

<!-- KIRI -->
<div class="left">

  <!-- CARD -->
  <div class="cards">
    <div class="card-custom">
      <p>Total Item Tersedia</p>
      <h2 class="green"><?= (int) $stockSummary['total_qty'] ?></h2>
      <small>pcs</small>
    </div>

    <div class="card-custom">
      <p>Stok Menipis</p>
      <h2 class="red"><?= (int) $stockSummary['low_stock_items'] ?></h2>
      <small>item stok ≤ 10 pcs</small>
    </div>
  </div>

  <!-- AKTIVITAS -->
  <div class="activity-box">

    <div class="activity-header">
      <h3>Aktivitas Terbaru</h3>

      <div class="filter">
        <input type="date" id="filterTanggal">
        <select id="filterJenis">
          <option value="">Semua</option>
          <option value="masuk">Masuk</option>
          <option value="keluar">Keluar</option>
        </select>
      </div>
    </div>

    <div class="activity-list" id="activityList">
      <div class="activity-item">
        <div class="detail">
          <p class="title">Riwayat stok belum tersedia</p>
          <small>Project simple ini hanya menyimpan jumlah stok saat ini, tanpa stock movements.</small>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- KANAN -->
<div class="right">

  <!-- HEADER -->
  <div class="right-header">

    <div class="header-left">
      <h3>📦 Stok Barang</h3>
    </div>

    <div style="display:flex; gap:10px;">
      <select id="filterKategori">
        <option value="">Semua</option>
        <?php foreach ($stockCategories as $category): ?>
          <option value="<?= htmlspecialchars($category['nama_kategori'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($category['nama_kategori'], ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button class="btn" onclick="openModal()">+ Tambah</button>
      <button class="btn" onclick="toggleDeleteMode()">Hapus</button>
    </div>
  </div>

  <!-- WRAPPER -->
  <div class="product-wrapper">
    <?php if ($groupedStocks === []): ?>
      <div class="empty-state">
        <p>Belum ada data stok produk.</p>
      </div>
    <?php endif; ?>

    <?php foreach ($groupedStocks as $categoryName => $variants): ?>
      <?php
        $categoryTotal = 0;
        foreach ($variants as $items) {
            foreach ($items as $item) {
                $categoryTotal += (int) $item['qty'];
            }
        }
      ?>
      <div class="kategori-item" data-kategori="<?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?>">
        <div class="kategori-header">
          <input type="checkbox" class="pilih-hapus" style="display:none;">
          <span><?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?></span>
          <span><?= $categoryTotal ?> pcs</span>
        </div>

        <div class="kategori-content">
          <?php foreach ($variants as $variantName => $items): ?>
            <div class="kain">
              <div class="kain-title"><?= htmlspecialchars($variantName, ENT_QUOTES, 'UTF-8') ?></div>
              <?php foreach ($items as $item): ?>
                <?php
                  $sizeLabel = $item['size_name'] ?? '-';
                  $colorLabel = $item['color_name'] ?? null;
                  $label = $colorLabel ? $sizeLabel . ' / ' . $colorLabel : $sizeLabel;
                ?>
                <div class="ukuran-row" data-stock-id="<?= (int) $item['stock_id'] ?>">
                  <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                  <span><?= (int) $item['qty'] ?> pcs</span>
                  <div class="aksi">
                    <button type="button" title="Edit tampilan saja">✏️</button>
                    <button type="button" title="Hapus tampilan saja">🗑️</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</div>
</div>
</div>

<!-- MODAL -->
<div class="modal" id="modal">
  <div class="modal-content">

    <h3>Tambah Produk</h3>
    <p style="font-size:12px; color:#666; margin-bottom:12px;">
      Form ini menambah stok di tampilan sementara. Penyimpanan ke database bisa dibuat di fitur CRUD berikutnya.
    </p>

    <!-- KATEGORI -->
    <div class="input-group">
      <label>Kategori</label>
      <input list="listKategori" id="kategori" placeholder="Pilih atau ketik kategori">
      <datalist id="listKategori">
        <?php foreach ($stockCategories as $category): ?>
          <option value="<?= htmlspecialchars($category['nama_kategori'], ENT_QUOTES, 'UTF-8') ?>"></option>
        <?php endforeach; ?>
      </datalist>
    </div>

    <!-- KAIN -->
    <div class="input-group">
      <label>Jenis Kain / Varian</label>
      <input id="kain" placeholder="Contoh: Cotton Combed 24s">
    </div>

    <!-- UKURAN -->
    <div class="input-group">
      <label>Ukuran / Warna</label>
      <input id="ukuran" placeholder="Contoh: M / Hitam">
    </div>

    <!-- STOK -->
    <div class="input-group">
      <label>Stok</label>
      <input type="number" id="stok" placeholder="Jumlah stok">
    </div>

    <div class="modal-action">
      <button class="btn" onclick="tambahProduk()">Simpan</button>
      <button class="btn btn-cancel" onclick="closeModal()">Batal</button>
    </div>

  </div>
</div>

<script>
// FILTER AKTIVITAS
const filterTanggal = document.getElementById("filterTanggal");
const filterJenis = document.getElementById("filterJenis");
if (filterTanggal) filterTanggal.addEventListener("change", filterData);
if (filterJenis) filterJenis.addEventListener("change", filterData);

function filterData(){
  const tanggal = document.getElementById("filterTanggal").value;
  const jenis = document.getElementById("filterJenis").value;

  document.querySelectorAll(".activity-item").forEach(item => {
    const cocokTanggal = !tanggal || item.dataset.date === tanggal;
    const cocokJenis = !jenis || item.classList.contains(jenis);

    item.style.display = (cocokTanggal && cocokJenis) ? "flex" : "none";
  });
}

// ACCORDION
document.addEventListener("click", function (e) {
  const header = e.target.closest(".kategori-header");
  if (!header) return;

  const content = header.nextElementSibling;
  content.style.display = content.style.display === "block" ? "none" : "block";
});

// FILTER KATEGORI
document.getElementById("filterKategori").addEventListener("change", function () {
  const value = this.value;

  document.querySelectorAll(".kategori-item").forEach(item => {
    item.style.display = !value || item.dataset.kategori === value ? "block" : "none";
  });
});

// MODAL
function openModal() {
  document.getElementById("modal").style.display = "flex";
}

function closeModal() {
  document.getElementById("modal").style.display = "none";
}

// TAMBAH PRODUK CLIENT-SIDE ONLY
function tambahProduk() {
  const kategori = document.getElementById("kategori").value.trim();
  const kain = document.getElementById("kain").value.trim();
  const ukuran = document.getElementById("ukuran").value.trim();
  const stok = document.getElementById("stok").value.trim();

  if (!kategori || !kain || !ukuran || !stok) {
    alert("Isi semua data!");
    return;
  }

  const wrapper = document.querySelector(".product-wrapper");
  let kategoriEl = document.querySelector(`.kategori-item[data-kategori="${kategori}"]`);

  if (!kategoriEl) {
    wrapper.insertAdjacentHTML("beforeend", `
      <div class="kategori-item" data-kategori="${kategori}">
        <div class="kategori-header">
          <input type="checkbox" class="pilih-hapus" style="display:none;">
          <span>${kategori}</span>
          <span>0 pcs</span>
        </div>
        <div class="kategori-content"></div>
      </div>
    `);

    kategoriEl = document.querySelector(`.kategori-item[data-kategori="${kategori}"]`);
  }

  const content = kategoriEl.querySelector(".kategori-content");
  let kainEl = [...content.querySelectorAll(".kain")]
    .find(k => k.querySelector(".kain-title").innerText === kain);

  if (!kainEl) {
    content.insertAdjacentHTML("beforeend", `
      <div class="kain">
        <div class="kain-title">${kain}</div>
      </div>
    `);

    kainEl = [...content.querySelectorAll(".kain")]
      .find(k => k.querySelector(".kain-title").innerText === kain);
  }

  kainEl.insertAdjacentHTML("beforeend", `
    <div class="ukuran-row">
      <span>${ukuran}</span>
      <span>${stok} pcs</span>
      <div class="aksi">
        <button type="button">✏️</button>
        <button type="button">🗑️</button>
      </div>
    </div>
  `);

  document.getElementById("kategori").value = "";
  document.getElementById("kain").value = "";
  document.getElementById("ukuran").value = "";
  document.getElementById("stok").value = "";

  closeModal();
}

// EDIT INLINE CLIENT-SIDE ONLY
document.addEventListener("click", function (e) {
  if (e.target.textContent === "✏️") {
    const row = e.target.closest(".ukuran-row");
    const stokEl = row.children[1];
    const oldValue = stokEl.textContent.replace(" pcs", "");

    stokEl.innerHTML = `<input type="number" value="${oldValue}" style="width:60px;">`;

    const input = stokEl.querySelector("input");
    input.focus();

    input.addEventListener("blur", save);
    input.addEventListener("keypress", e => {
      if (e.key === "Enter") save();
    });

    function save(){
      let val = input.value;
      if (!val || val < 0) val = 0;
      stokEl.textContent = val + " pcs";
    }
  }
});

// HAPUS INLINE CLIENT-SIDE ONLY
document.addEventListener("click", function (e) {
  if (e.target.textContent === "🗑️") {
    const row = e.target.closest(".ukuran-row");
    const kain = row.closest(".kain");
    const kategori = row.closest(".kategori-content");

    row.remove();

    if (!kain.querySelector(".ukuran-row")) kain.remove();
    if (!kategori.querySelector(".kain")) kategori.parentElement.remove();
  }
});

// MODE HAPUS KATEGORI CLIENT-SIDE ONLY
let deleteMode = false;

function toggleDeleteMode() {
  deleteMode = !deleteMode;

  document.querySelectorAll(".pilih-hapus").forEach(cb => {
    cb.style.display = deleteMode ? "inline-block" : "none";
    cb.checked = false;
  });

  deleteMode ? showDeleteBtn() : removeDeleteBtn();
}

function showDeleteBtn() {
  if (document.getElementById("hapusTerpilih")) return;

  const btn = document.createElement("button");
  btn.id = "hapusTerpilih";
  btn.className = "btn";
  btn.textContent = "Hapus Terpilih";
  btn.onclick = hapusTerpilih;

  document.querySelector(".right-header").appendChild(btn);
}

function removeDeleteBtn() {
  document.getElementById("hapusTerpilih")?.remove();
}

function hapusTerpilih() {
  const checked = document.querySelectorAll(".pilih-hapus:checked");

  if (!checked.length) return alert("Pilih dulu!");

  if (confirm("Yakin hapus?")) {
    checked.forEach(cb => cb.closest(".kategori-item").remove());
    toggleDeleteMode();
  }
}

// KLIK LUAR MODAL
window.onclick = e => {
  if (e.target.id === "modal") closeModal();
};
</script>

</body>
</html>
