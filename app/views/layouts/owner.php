<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siblings.co - Dashboard Owner</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', sans-serif;
}

body {
  background-color: #F8F3E9;
}

/* LAYOUT */
.container {
  display: flex;
  height: 100vh;
}

/* SIDEBAR */
.sidebar {
  width: 250px;
  background: linear-gradient(rgba(74,51,40,0.9), rgba(74,51,40,0.9)), url('header.jpg');
  background-size: cover;
  color: white;
  padding-top: 30px;
}

.logo-container {
  padding: 0 25px;
  margin-bottom: 40px;
}

.logo-container h2 {
  font-style: italic;
  font-size: 28px;
}

nav {
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding-left: 20px;
}

.nav-item {
  text-decoration: none;
  color: #4A3328;
  background: white;
  padding: 12px 20px;
  border-radius: 30px 0 0 30px;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: 0.3s;
}

.nav-item:hover {
  background-color: #79B473;
  color: white;
  transform: scale(1.05);
}

.nav-item.active {
  background-color: #E6D5B8;
}

/* MAIN */
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* HEADER */
.header-photo {
  height: 110px;
  background: url('header.jpg') center/cover;
  box-shadow: inset 0 0 100px rgba(0,0,0,0.2);
}

/* CONTENT */
.content-padding {
  flex: 1;
  padding: 30px 50px;
  display: flex;
  flex-direction: column;
}

/* CARDS ATAS */
.dashboard-cards {
  display: flex;
  gap: 25px;
  margin-bottom: 25px;
}

.card-custom {
  flex: 1;
  height: 140px;
  background: #fff;
  border: 2px solid #4A3328;
  border-radius: 15px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transition: 0.3s;
}

.card-custom:hover {
  transform: scale(1.05);
}

.card-custom h2 {
  font-size: 30px;
}

.card-custom p {
  font-size: 16px;
}

/* BAGIAN BAWAH */
.dashboard-bottom {
  flex: 1;
  display: flex;
  gap: 25px;
}

/* BOX */
.box {
  flex: 1;
  background: #fff;
  border: 2px solid #4A3328;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  flex-direction: column;
}

.box h6 {
  margin-bottom: 10px;
  font-size: 16px;
  color: #4A3328;
}

.box-content {
  flex: 1;
}


.box p {
  font-size: 14px;
  margin-bottom: 8px;
}


canvas {
  height: 75%;
}
</style>
</head>

<body>

<?php include 'sidebar.php'; ?>
<?php include 'header.php' ; ?>


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
        <h6 style="text-align:center;">Statistik Penjualan</h6>
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