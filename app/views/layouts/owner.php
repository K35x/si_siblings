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

  <!-- MAIN CONTENT -->
  <div class="main-content">

    <!-- HEADER -->
    <div class="header-photo"></div>

    <!-- CONTENT -->
    <div class="content-padding">

      <!-- CARD STATISTIK -->
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

      <!-- BAGIAN BAWAH -->
      <div class="dashboard-bottom">

<!-- DEADLINE PRODUKSI -->
<div class="box deadline-card">
  <div class="deadline-header">
    <div class="deadline-icon">
      <i class="fas fa-calendar-alt"></i>
    </div>
    <h6>Deadline Produksi</h6>
  </div>

  <div class="deadline-list">

    <!-- Item 1 -->
    <div class="deadline-item">
      <div class="deadline-item-icon">
        <i class="fas fa-calendar-check"></i>
      </div>

      <div class="deadline-info">
        <h4>#102 - Ahmad</h4>
        <p>Deadline: Hari ini</p>
      </div>

      <span class="deadline-badge">
        <i class="far fa-clock"></i>
        Hari ini
      </span>
    </div>

    <!-- Item 2 -->
    <div class="deadline-item">
      <div class="deadline-item-icon">
        <i class="fas fa-calendar-day"></i>
      </div>

      <div class="deadline-info">
        <h4>#103 - Andi</h4>
        <p>Deadline: Besok</p>
      </div>

      <span class="deadline-badge">
        <i class="far fa-clock"></i>
        Besok
      </span>
    </div>

    <!-- Item 3 -->
    <div class="deadline-item">
      <div class="deadline-item-icon">
        <i class="fas fa-calendar"></i>
      </div>

      <div class="deadline-info">
        <h4>#104 - Nisa</h4>
        <p>Deadline: 2 hari lagi</p>
      </div>

      <span class="deadline-badge">
        <i class="far fa-clock"></i>
        2 hari lagi
      </span>
    </div>

    <!-- Item 4 -->
    <div class="deadline-item">
      <div class="deadline-item-icon">
        <i class="fas fa-calendar"></i>
      </div>

      <div class="deadline-info">
        <h4>#105 - Alin</h4>
        <p>Deadline: 2 hari lagi</p>
      </div>

      <span class="deadline-badge">
        <i class="far fa-clock"></i>
        2 hari lagi
      </span>
    </div>

     <div class="deadline-item">
      <div class="deadline-item-icon">
        <i class="fas fa-calendar"></i>
      </div>

      <div class="deadline-info">
        <h4>#106 - A</h4>
        <p>Deadline: 2 hari lagi</p>
      </div>

      <span class="deadline-badge">
        <i class="far fa-clock"></i>
        2 hari lagi
      </span>
    </div>

  </div>
</div>

        <!-- AKTIVITAS TERAKHIR -->
        <div class="box activity-card">

          <div class="activity-header">
            <h3>Aktivitas Terakhir</h3>
            <button class="btn-view-all" onclick="openActivityModal()">
              Lihat Semua
            </button>
          </div>

          <div class="activity-list">
            <div class="activity-item">
              <span class="activity-time">10.17</span>
              <span class="activity-text">Menambahkan pesanan</span>
            </div>

            <div class="activity-item">
              <span class="activity-time">10.50</span>
              <span class="activity-text">Menerima pembayaran dari Ahmad</span>
            </div>

            <div class="activity-item">
              <span class="activity-time">11.07</span>
              <span class="activity-text">Menambahkan pesanan</span>
            </div>

            <div class="activity-item">
              <span class="activity-time">11.26</span>
              <span class="activity-text">Menerima pembayaran dari SMA 2</span>
            </div>

            <div class="activity-item">
              <span class="activity-time">12.09</span>
              <span class="activity-text">Pesanan baru masuk</span>
            </div>
          </div>

        </div>

        <!-- STATISTIK PENJUALAN -->
        <div class="box chart-box">
          <h6 class="chart-title">Statistik Penjualan</h6>
          <div class="box-content">
            <canvas id="chart"></canvas>
          </div>
        </div>

      </div>
      <!-- END dashboard-bottom -->

    </div>
    <!-- END content-padding -->

  </div>
  <!-- END main-content -->

</div>
<!-- END container -->

<!-- MODAL AKTIVITAS -->
<div class="modal-overlay" id="activityModal">
  <div class="activity-modal">

    <div class="modal-header">
      <h3>Aktivitas Terakhir (Semua)</h3>
      <button class="modal-close" onclick="closeActivityModal()">
        &times;
      </button>
    </div>

    <div class="modal-body">
      <table class="activity-table">
        <thead>
          <tr>
            <th>Waktu</th>
            <th>Aktivitas</th>
            <th>Oleh</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>10.17</td>
            <td>Menambahkan pesanan</td>
            <td>Kasir</td>
          </tr>
          <tr>
            <td>10.50</td>
            <td>Menerima pembayaran dari Ahmad</td>
            <td>Kasir</td>
          </tr>
          <tr>
            <td>11.07</td>
            <td>Menambahkan pesanan</td>
            <td>Kasir</td>
          </tr>
          <tr>
            <td>11.26</td>
            <td>Menerima pembayaran dari SMA 2</td>
            <td>Kasir</td>
          </tr>
          <tr>
            <td>12.09</td>
            <td>Pesanan baru masuk</td>
            <td>Kasir</td>
          </tr>
          <tr>
            <td>13.20</td>
            <td>Status pesanan diperbarui</td>
            <td>Owner</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="modal-footer">
      <button class="btn-close" onclick="closeActivityModal()">
        Tutup
      </button>
    </div>

  </div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JAVASCRIPT -->
<script>
  // Grafik Penjualan
  new Chart(document.getElementById('chart'), {
    type: 'bar',
    data: {
      labels: ['Jersey', 'PDH', 'Kaos', 'Kemeja'],
      datasets: [{
        label: 'Penjualan',
        data: [8, 12, 16, 20]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });

  // Modal Aktivitas
  function openActivityModal() {
    document.getElementById('activityModal').classList.add('show');
  }

  function closeActivityModal() {
    document.getElementById('activityModal').classList.remove('show');
  }

  window.addEventListener('click', function(e) {
    const modal = document.getElementById('activityModal');
    if (e.target === modal) {
      closeActivityModal();
    }
  });
</script>

</body>
</html>