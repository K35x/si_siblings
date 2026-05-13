<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siblings.co - Dashboard Owner</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/dashboard-owner.css') ?>">
</head>

<body>

<div class="container">
<?php
$sidebarRole = 'owner';
$activeMenu = 'dashboard';
include __DIR__ . '/sidebar.php';
?>
<!-- MAIN -->
<div class="main-content">

  <div class="header-photo"></div>

  <div class="content-padding">

    <!-- CARDS -->
    <div class="dashboard-cards">
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
        <h2 id="omzet">1.540.000</h2>
        <p>Total Omzet Hari Ini</p>
      </div>
    </div>

    <!-- BAWAH -->
    <div class="dashboard-bottom">

      <div class="box">
        <h6>Deadline Produksi</h6>
        <div class="box-content">
          <p>#102 - Ahmad (Hari ini)</p>
          <p>#103 - Andi (Besok)</p>
          <p>#104 - Nisa (2 hari)</p>
        </div>
      </div>

      <div class="box">
        <h6>Aktivitas Terakhir</h6>
        <div class="box-content">
          <p>10.17 Kasir menambahkan pesanan</p>
          <p>10.50 Pembayaran masuk</p>
          <p>11.07 Tambah pesanan</p>
          <p>11.26 Pembayaran masuk</p>
          <p>12.09 Pesanan baru</p>
        </div>
      </div>

      <div class="box">
        <h6 class="chart-title">Statistik Penjualan</h6>
        <div class="box-content">
          <canvas id="chart"></canvas>
        </div>
      </div>

    </div>

  </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chart'), {
  type: 'bar',
  data: {
    labels: ['Jersey', 'PDH', 'Kaos', 'Kemeja'],
    datasets: [{
      label: 'Penjualan',
      data: [8, 12, 16, 20]
    }]
  }
});
</script>

</body>
</html>