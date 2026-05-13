<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siblings.co - Dashboard Kasir</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/dashboard-cashier.css') ?>">
</head>

<body>

<div class="container">
<?php
$sidebarRole = 'kasir';
$activeMenu = 'dashboard';
include __DIR__ . '/sidebar.php';
?>

<!-- MAIN -->
<div class="main-content">

  <div class="header-photo"></div>

  <div class="content">

    <!-- CARD ATAS -->
    <div class="cards">

      <div class="card-custom">
        <h2 id="pesananBaru">12</h2>
        <p>Pesanan Baru</p>
      </div>

      <div class="card-custom">
        <h2 id="diproses">4</h2>
        <p>Sedang Diproses</p>
      </div>

      <div class="card-custom">
        <h2 id="siap">8</h2>
        <p>Siap Diambil</p>
      </div>

      <div class="card-custom">
        <h2 id="belumlunas">15</h2>
        <p>Belum Lunas</p>
      </div>

    </div>

    <!-- BAGIAN BAWAH -->
    <div class="bottom">

      <div class="box add-box">
        <i class="fas fa-plus"></i>
        Tambah Pesanan
      </div>

      <div class="box activity">
        <h3>Aktivitas Terakhir</h3>
        <p><strong>10.17</strong> Menambahkan pesanan</p>
        <p><strong>10.50</strong> Menerima pembayaran dari Ahmad</p>
        <p><strong>11.07</strong> Menambahkan pesanan</p>
        <p><strong>11.26</strong> Menerima pembayaran dari SMA 2</p>
        <p><strong>12.09</strong> Menambahkan pesanan</p>
      </div>

      <div class="box big-box">
        Antrian Invoice dan Pesanan
      </div>

    </div>

  </div>

</div>

</div>

</body>
</html>