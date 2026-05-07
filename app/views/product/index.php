<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siblings.co - Stok Produk</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body>


<div class="container">
        <aside class="sidebar">
            <div class="logo-container"><h2>Siblings.co</h2></div>
            <nav>
                <a href="owner.php" class="nav-item"><i class="fas fa-home"></i> Beranda</a>
                <a href="index.php" class="nav-item active"><i class="fas fa-shopping-basket"></i> Pesanan</a>
                <a href="index.php" class="nav-item"><i class="fas fa-box"></i> Stok produk</a>
                <a href="#" class="nav-item"><i class="fas fa-money-bill"></i> Penjualan</a>
            </nav>
        </aside>

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
      <h2 class="green">250</h2>
      <small>pcs</small>
    </div>

    <div class="card-custom">
      <p>Stok Menipis</p>
      <h2 class="red">15</h2>
      <small>pcs</small>
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

      <div class="activity-item keluar" data-date="2026-05-01">
        <div class="time">01/05/2026<br><small>10:15</small></div>
        <div class="detail">
          <p class="title">Stok Keluar</p>
          <small>25pcs Kaos Hitam Uk.L</small>
        </div>
      </div>

      <div class="activity-item masuk" data-date="2026-04-30">
        <div class="time">30/04/2026<br><small>13:00</small></div>
        <div class="detail">
          <p class="title">Stok Masuk</p>
          <small>50pcs Kaos Putih Uk.M</small>
        </div>
      </div>

      <div class="activity-item masuk" data-date="2026-04-02">
        <div class="time">02/04/2026<br><small>14:00</small></div>
        <div class="detail">
          <p class="title">Stok Masuk</p>
          <small>100pcs Kaos Putih Uk.s</small>
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

  <div class="header-actions">
</div>

    <div style="display:flex; gap:10px;">
      <select id="filterKategori">
        <option value="">Semua</option>
        <option value="T-shirt">T-shirt</option>
        <option value="PDH">PDH</option>
        <option value="Jersey">Jersey</option>
        <option value="Polo Shirt">Polo Shirt</option>
        <option value="Seragam Olahraga">Seragam Olahraga</option>
        <option value="Jacket">Jacket</option>
      </select>

      <button class="btn" onclick="openModal()">+ Tambah</button>
      <button class="btn" onclick="toggleDeleteMode()">Hapus</button>
    </div>
  </div>

  <!-- WRAPPER -->
  <div class="product-wrapper">
    <!-- T-SHIRT -->
    <div class="kategori-item" data-kategori="T-shirt">
      <div class="kategori-header">
        <input type="checkbox" class="pilih-hapus" style="display:none;">
        <span> T-shirt</span>
        <span>160 pcs</span>
      </div>

      <div class="kategori-content">

        <div class="kain">
          <div class="kain-title">Cotton Combed 24s</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

        <div class="kain">
          <div class="kain-title">Cotton Carded</div>
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

        <div class="kain">
          <div class="kain-title">Semi Cotton</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>


      </div>
    </div>

    <!-- PDH -->
    <div class="kategori-item" data-kategori="PDH">
      <div class="kategori-header">
        <input type="checkbox" class="pilih-hapus" style="display:none;">
        <span>PDH</span>
        <span>60 pcs</span>
      </div>

      <div class="kategori-content">

        <div class="kain">
          <div class="kain-title">American Drill</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

        <div class="kain">
          <div class="kain-title">Union</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

        <div class="kain">
          <div class="kain-title">Nagata Drill</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

      </div>
    </div>

    <!-- JERSEY -->
    <div class="kategori-item" data-kategori="Jersey">
      <div class="kategori-header">
        <input type="checkbox" class="pilih-hapus" style="display:none;">
        <span>Jersey</span>
        <span>80 pcs</span>
      </div>

      <div class="kategori-content">

        <div class="kain">
          <div class="kain-title">Dryfit</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

        <div class="kain">
          <div class="kain-title">Embos</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

        <div class="kain">
          <div class="kain-title">Jaquard</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

      </div>
    </div>

    <!-- POLO -->
    <div class="kategori-item" data-kategori="Polo Shirt">
      <div class="kategori-header">
        <input type="checkbox" class="pilih-hapus" style="display:none;">
        <span>Polo Shirt</span>
        <span>80 pcs</span>
      </div>

      <div class="kategori-content">

        <div class="kain">
          <div class="kain-title">Premium Cotton 24s</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

        <div class="kain">
          <div class="kain-title">Lacoste 24s</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

      </div>
    </div>

    <!-- SERAGAM -->
    <div class="kategori-item" data-kategori="Seragam Olahraga">
      <div class="kategori-header">
        <input type="checkbox" class="pilih-hapus" style="display:none;">
        <span>Seragam Olahraga</span>
        <span>80 pcs</span>
      </div>

      <div class="kategori-content">

        <div class="kain">
          <div class="kain-title">Semi Cotton</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>

      </div>
    </div>

    <!-- JACKET -->
    <div class="kategori-item" data-kategori="Jacket">
      <div class="kategori-header">
        <input type="checkbox" class="pilih-hapus" style="display:none;">
        <span>Jacket</span>
        <span>80 pcs</span>
      </div>

      <div class="kategori-content">

        <div class="kain">
          <div class="kain-title">Custom</div>
          <div class="ukuran-row"><span>S</span><span>20 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>M</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>L</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
          <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><div class="aksi"><button>✏️</button><button>🗑️</button></div></div>
        </div>
      </div>

    </div>

  </div>
</div>

<!-- MODAL -->
<div class="modal" id="modal">
  <div class="modal-content">

    <h3>Tambah Produk</h3>

      <!-- KATEGORI -->
    <div class="input-group">
      <label>Kategori</label>
      <input list="listKategori" id="kategori" placeholder="Pilih atau ketik kategori">
      <datalist id="listKategori">
        <option value="Tshirt">
        <option value="Jersey">
        <option value="PDH">
        <option value="Polo Shirt">
        <option value="Seragam Olahraga">
        <option value="Jacket">
      </datalist>
    </div>

    <!-- KAIN -->
    <div class="input-group">
      <label>Jenis Kain</label>
      <input list="listKain" id="kain" placeholder="Pilih atau ketik kain">
      <datalist id="listKain">
        <option value="Semi Cotton">
        <option value="Cotton Carded">
        <option value="Cotton Combed 24s">
        <option value="Unione">
        <option value="American Drill">
        <option value="Nagata Drill">
        <option value="Embos">
        <option value="Jaquard">
        <option value="Dryfit">
        <option value="Premium Cotton 24s">
        <option value="Lacoste 24s">
      </datalist>
    </div>

    <!-- UKURAN -->
    <div class="input-group">
      <label>Ukuran</label>
      <input list="listUkuran" id="ukuran" placeholder="S, M, L, XL...">
      <datalist id="listUkuran">
        <option value="S">
        <option value="M">
        <option value="L">
        <option value="XL">
        <option value="XXL">
      </datalist>
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
document.getElementById("filterTanggal").addEventListener("change", filterData);
document.getElementById("filterJenis").addEventListener("change", filterData);

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

// TAMBAH PRODUK
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

  // CEK KATEGORI 
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

  // CEK KAIN
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

  // === TAMBAH UKURAN ===
  kainEl.insertAdjacentHTML("beforeend", `
    <div class="ukuran-row">
      <span>${ukuran}</span>
      <span>${stok} pcs</span>
      <div class="aksi">
        <button>✏️</button>
        <button>🗑️</button>
      </div>
    </div>
  `);

  // reset form
  document.getElementById("kategori").value = "";
  document.getElementById("kain").value = "";
  document.getElementById("ukuran").value = "";
  document.getElementById("stok").value = "";

  closeModal();
}

// EDIT INLINE
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

// HAPUS INLINE
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

// MODE HAPUS KATEGORI
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
</script>

</body>
</html>
