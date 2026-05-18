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
<?php
$sidebarRole = 'owner';
$activeMenu = 'products';
include __DIR__ . '/../layouts/sidebar.php';
?>

<!-- MAIN -->
<div class="main-content">

  <div class="header-photo"></div>

<div class="content">
    

<!-- KANAN -->
<div class="right">

  <!-- HEADER -->
  <div class="right-header">

  <div class="header-left">
    <h3><i class="fas fa-box"></i> Stok Barang</h3>
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
      <div class="ukuran-item">

      <!-- Ringkasan ukuran -->
      <div class="ukuran-row"><span>S</span><span>30 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
      <!-- Detail warna  -->
      <div class="warna-list">
        <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
      </div>    
    </div>

      <div class="ukuran-item">
      <div class="ukuran-row"><span>M</span><span>30 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
      <div class="warna-list">
        <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
      </div>    
    </div>

      <div class="ukuran-item">
      <div class="ukuran-row"><span>L</span><span>30 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
      <div class="warna-list">
        <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
      </div>    
    </div>
      
      <div class="ukuran-item">
      <div class="ukuran-row"><span>XL</span><span>30 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
      <div class="warna-list">
        <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
      </div>    
    </div>

      <div class="ukuran-item">
      <div class="ukuran-row"><span>XXL</span><span>30 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
      <div class="warna-list">
        <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
      </div>    
    </div>
  </div>

    <div class="kain">
    <div class="kain-title">Cotton Carded</div>
      <div class="ukuran-item">
      <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
      <div class="warna-list">
          <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        </div>    
      </div>

        <div class="ukuran-item">
        <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
        <div class="warna-list">
          <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        </div>    
      </div>

        <div class="ukuran-item">
        <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
        <div class="warna-list">
          <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        </div>    
      </div>

        <div class="ukuran-item">
        <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
        <div class="warna-list">
          <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        </div>    
      </div>

        <div class="ukuran-item">
        <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
        <div class="warna-list">
          <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
        </div>    
      </div>
    </div>

        <div class="kain">
          <div class="kain-title">Semi Cotton</div>
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
          
          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
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
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
      </div>

        <div class="kain">
          <div class="kain-title">Union</div>
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
        </div>

        <div class="kain">
          <div class="kain-title">Nagata Drill</div>
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
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
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>


          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
      </div>  

        <div class="kain">
          <div class="kain-title">Embos</div>
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
        </div>

        <div class="kain">
          <div class="kain-title">Jaquard</div>
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
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
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
        </div>

        <div class="kain">
          <div class="kain-title">Lacoste 24s</div>
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
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
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
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
          <div class="ukuran-item">
          <div class="ukuran-row"><span>S</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>M</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>L</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>

          <div class="ukuran-item">
          <div class="ukuran-row"><span>XXL</span><span>10 pcs</span><button class="btn-delete" title="Hapus ukuran S beserta semua warna"><i class="fas fa-trash"></i></button></div>
          <div class="warna-list">
            <div class="warna-row"><span>Merah</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Jingga</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Kuning</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hijau</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Biru</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Nila</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Ungu</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Putih</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
            <div class="warna-row"><span>Hitam</span><span>5 pcs</span><div class="aksi"><button class="btn-edit" title="Edit stok warna"><i class="fas fa-pen"></i></button><button class="btn-delete"><i class="fas fa-trash"></i></button></div></div>
          </div>    
        </div>
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
// ACCORDION KATEGORI
document.addEventListener("click", function (e) {
  const header = e.target.closest(".kategori-header");
  if (!header) return;

  const content = header.nextElementSibling;
  if (!content) return;

  // Tutup semua kategori lain
  document.querySelectorAll(".kategori-content").forEach((item) => {
    if (item !== content) {
      item.style.display = "none";

      // Tutup semua kain & warna di kategori lain
      item.querySelectorAll(".ukuran-item").forEach((ukuran) => {
        ukuran.style.display = "none";
      });

      item.querySelectorAll(".warna-list").forEach((warna) => {
        warna.style.display = "none";
      });
    }
  });

  // Toggle kategori yang dipilih
  const isOpen = content.style.display === "block";
  content.style.display = isOpen ? "none" : "block";

  // Jika kategori ditutup, tutup semua kain & warna
  if (isOpen) {
    content.querySelectorAll(".ukuran-item").forEach((ukuran) => {
      ukuran.style.display = "none";
    });

    content.querySelectorAll(".warna-list").forEach((warna) => {
      warna.style.display = "none";
    });
  }
});

// TOGGLE JENIS KAIN
// Klik satu kain -> kain lain tertutup
document.addEventListener("click", function (e) {
  // Abaikan jika klik elemen lain
  if (
    e.target.closest(".kategori-header") ||
    e.target.closest(".ukuran-row") ||
    e.target.closest(".btn-edit") ||
    e.target.closest(".btn-delete")
  ) {
    return;
  }

  const kainTitle = e.target.closest(".kain-title");
  if (!kainTitle) return;

  const kain = kainTitle.closest(".kain");
  const kategoriContent = kain.closest(".kategori-content");
  if (!kain || !kategoriContent) return;

  const ukuranItems = kain.querySelectorAll(".ukuran-item");

  // Apakah kain ini sedang terbuka?
  const isOpen = [...ukuranItems].some(
    (item) => item.style.display !== "none"
  );

  // Tutup semua kain lain
  kategoriContent.querySelectorAll(".kain").forEach((kainLain) => {
    if (kainLain !== kain) {
      kainLain.querySelectorAll(".ukuran-item").forEach((item) => {
        item.style.display = "none";
      });

      kainLain.querySelectorAll(".warna-list").forEach((warna) => {
        warna.style.display = "none";
      });
    }
  });

  // Toggle kain yang dipilih
  ukuranItems.forEach((item) => {
    item.style.display = isOpen ? "none" : "block";
  });

  // Saat membuka kain, pastikan semua warna tetap tertutup
  kain.querySelectorAll(".warna-list").forEach((warna) => {
    warna.style.display = "none";
  });
});

// FILTER KATEGORI
document
  .getElementById("filterKategori")
  .addEventListener("change", filterProduk);

function filterProduk() {
  const kategori = document.getElementById("filterKategori").value;

  document.querySelectorAll(".kategori-item").forEach((item) => {
    const cocok =
      kategori === "" || item.dataset.kategori === kategori;

    item.style.display = cocok ? "block" : "none";
  });
}

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

  // Cari kategori
  let kategoriEl = document.querySelector(
    `.kategori-item[data-kategori="${kategori}"]`
  );

  // Jika kategori belum ada
  if (!kategoriEl) {
    wrapper.insertAdjacentHTML(
      "beforeend",
      `
      <div class="kategori-item" data-kategori="${kategori}">
        <div class="kategori-header">
          <input type="checkbox" class="pilih-hapus" style="display:none;">
          <span>${kategori}</span>
          <span>0 pcs</span>
        </div>
        <div class="kategori-content" style="display:none;"></div>
      </div>
      `
    );

    kategoriEl = document.querySelector(
      `.kategori-item[data-kategori="${kategori}"]`
    );
  }

  const content = kategoriEl.querySelector(".kategori-content");

  // Cari kain
  let kainEl = [...content.querySelectorAll(".kain")].find(
    (item) =>
      item.querySelector(".kain-title").textContent.trim() === kain
  );

  // Jika kain belum ada
  if (!kainEl) {
    content.insertAdjacentHTML(
      "beforeend",
      `
      <div class="kain">
        <div class="kain-title">${kain}</div>
      </div>
      `
    );

    kainEl = [...content.querySelectorAll(".kain")].find(
      (item) =>
        item.querySelector(".kain-title").textContent.trim() === kain
    );
  }

  // Tambah ukuran
  kainEl.insertAdjacentHTML(
    "beforeend",
    `
    <div class="ukuran-item" style="display:none;">
      <div class="ukuran-row">
        <span>${ukuran}</span>
        <span>${stok} pcs</span>
        <div class="aksi">
          <button class="btn-delete" title="Hapus ukuran">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>

      <div class="warna-list" style="display:none;">
        <div class="warna-row">
          <span>Default</span>
          <span>${stok} pcs</span>
          <div class="aksi">
            <button class="btn-edit" title="Edit stok warna">
              <i class="fas fa-pen"></i>
            </button>
            <button class="btn-delete" title="Hapus warna">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    `
  );

  updateAllUkuranTotals();
  updateAllKategoriTotals();

  // Reset form
  document.getElementById("kategori").value = "";
  document.getElementById("kain").value = "";
  document.getElementById("ukuran").value = "";
  document.getElementById("stok").value = "";

  closeModal();
  filterProduk();
}

// TOGGLE DETAIL WARNA
// Klik satu ukuran -> ukuran lain tertutup
document.addEventListener("click", function (e) {
  if (
    e.target.closest(".btn-edit") ||
    e.target.closest(".btn-delete")
  ) {
    return;
  }

  const ukuranRow = e.target.closest(".ukuran-row");
  if (!ukuranRow) return;

  const ukuranItem = ukuranRow.closest(".ukuran-item");
  const warnaList = ukuranItem?.querySelector(".warna-list");
  const kain = ukuranItem?.closest(".kain");

  if (!ukuranItem || !warnaList || !kain) return;

  const isOpen =
    warnaList.style.display === "flex" ||
    warnaList.style.display === "block";

  // Tutup semua warna lain
  kain.querySelectorAll(".warna-list").forEach((item) => {
    item.style.display = "none";
  });

  // Toggle warna yang dipilih
  warnaList.style.display = isOpen ? "none" : "flex";
});

// EDIT STOK WARNA
document.addEventListener("click", function (e) {
  const editBtn = e.target.closest(".btn-edit");
  if (!editBtn) return;

  const row = editBtn.closest(".warna-row");
  if (!row) return;

  const stokEl = row.children[1];
  const oldValue = stokEl.textContent.replace(" pcs", "").trim();

  stokEl.innerHTML = `
    <input
      type="number"
      value="${oldValue}"
      min="0"
      style="width:70px;"
    >
  `;

  const input = stokEl.querySelector("input");
  input.focus();
  input.select();

  function save() {
    let value = parseInt(input.value);

    if (isNaN(value) || value < 0) {
      value = 0;
    }

    stokEl.textContent = value + " pcs";

    const ukuranItem = row.closest(".ukuran-item");
    if (ukuranItem) {
      updateUkuranTotal(ukuranItem);
    }

    updateAllKategoriTotals();
  }

  input.addEventListener("blur", save);

  input.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      save();
    }
  });
});

// HAPUS DATA
document.addEventListener("click", function (e) {
  const deleteBtn = e.target.closest(".btn-delete");
  if (!deleteBtn) return;

  if (!confirm("Yakin ingin menghapus data ini?")) return;

  // Hapus warna
  const warnaRow = deleteBtn.closest(".warna-row");
  if (warnaRow) {
    const ukuranItem = warnaRow.closest(".ukuran-item");
    warnaRow.remove();

    if (ukuranItem.querySelectorAll(".warna-row").length === 0) {
      ukuranItem.remove();
    } else {
      updateUkuranTotal(ukuranItem);
    }

    cleanupEmptyParents();
    updateAllKategoriTotals();
    return;
  }

  // Hapus ukuran
  const ukuranRow = deleteBtn.closest(".ukuran-row");
  if (ukuranRow) {
    ukuranRow.closest(".ukuran-item")?.remove();

    cleanupEmptyParents();
    updateAllKategoriTotals();
  }
});

// UPDATE TOTAL SATU UKURAN
function updateUkuranTotal(ukuranItem) {
  let total = 0;

  ukuranItem.querySelectorAll(".warna-row").forEach((row) => {
    const stok =
      parseInt(
        row.children[1].textContent.replace(" pcs", "").trim()
      ) || 0;

    total += stok;
  });

  const stokUkuranEl = ukuranItem.querySelector(
    ".ukuran-row span:nth-child(2)"
  );

  if (stokUkuranEl) {
    stokUkuranEl.textContent = total + " pcs";
  }
}

// UPDATE SEMUA UKURAN
function updateAllUkuranTotals() {
  document.querySelectorAll(".ukuran-item").forEach(
    updateUkuranTotal
  );
}

// UPDATE TOTAL KATEGORI
function updateAllKategoriTotals() {
  document.querySelectorAll(".kategori-item").forEach(
    (kategori) => {
      let total = 0;

      kategori
        .querySelectorAll(".ukuran-row span:nth-child(2)")
        .forEach((stokEl) => {
          total +=
            parseInt(
              stokEl.textContent.replace(" pcs", "").trim()
            ) || 0;
        });

      const totalEl = kategori.querySelector(
        ".kategori-header span:last-child"
      );

      if (totalEl) {
        totalEl.textContent = total + " pcs";
      }
    }
  );
}

// BERSIHKAN KAIN / KATEGORI KOSONG
function cleanupEmptyParents() {
  document.querySelectorAll(".kain").forEach((kain) => {
    if (!kain.querySelector(".ukuran-item")) {
      kain.remove();
    }
  });

  document.querySelectorAll(".kategori-item").forEach(
    (kategori) => {
      if (!kategori.querySelector(".kain")) {
        kategori.remove();
      }
    }
  );
}

// MODE HAPUS KATEGORI
let deleteMode = false;

function toggleDeleteMode() {
  deleteMode = !deleteMode;

  document.querySelectorAll(".pilih-hapus").forEach(
    (checkbox) => {
      checkbox.style.display =
        deleteMode ? "inline-block" : "none";
      checkbox.checked = false;
    }
  );

  deleteMode ? showDeleteBtn() : removeDeleteBtn();
}

// TAMPILKAN TOMBOL HAPUS TERPILIH
function showDeleteBtn() {
  if (document.getElementById("hapusTerpilih")) return;

  const btn = document.createElement("button");
  btn.id = "hapusTerpilih";
  btn.className = "btn";
  btn.textContent = "Hapus Terpilih";
  btn.onclick = hapusTerpilih;

  document
    .querySelector(".header-actions")
    .appendChild(btn);
}

// HAPUS TOMBOL HAPUS TERPILIH
function removeDeleteBtn() {
  document.getElementById("hapusTerpilih")?.remove();
}

// HAPUS KATEGORI TERPILIH
function hapusTerpilih() {
  const checked = document.querySelectorAll(
    ".pilih-hapus:checked"
  );

  if (checked.length === 0) {
    alert("Pilih kategori terlebih dahulu!");
    return;
  }

  if (!confirm("Yakin ingin menghapus kategori terpilih?")) {
    return;
  }

  checked.forEach((checkbox) => {
    checkbox.closest(".kategori-item").remove();
  });

  toggleDeleteMode();
}

// KLIK DI LUAR MODAL
window.addEventListener("click", function (e) {
  if (e.target.id === "modal") {
    closeModal();
  }
});

// INISIALISASI
document.addEventListener("DOMContentLoaded", function () {
  // Sembunyikan semua ukuran saat awal
  document.querySelectorAll(".ukuran-item").forEach((item) => {
    item.style.display = "none";
  });

  // Sembunyikan semua warna saat awal
  document.querySelectorAll(".warna-list").forEach((item) => {
    item.style.display = "none";
  });

  // Hitung ulang total
  updateAllUkuranTotals();
  updateAllKategoriTotals();
});
</script>
</scrpt>

</body>
</html>
