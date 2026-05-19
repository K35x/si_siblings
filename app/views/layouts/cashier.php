<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Dashboard Kasir</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/dashboard-cashier.css') ?>">
</head>
<body>

<div class="container">

    <?php
    $sidebarRole = 'kasir';
    $activeMenu = 'dashboard';
    include __DIR__ . '/sidebar.php';
    ?>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- HEADER -->
        <div class="header-photo"></div>

        <!-- CONTENT -->
        <div class="content">

            <!--CARD STATISTIK -->
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

            <!--BAGIAN BAWAH-->
            <div class="bottom">

                <!-- TAMBAH PESANAN -->
                <div class="box add-order-card">
                <div class="add-icon">
                  <i class="fas fa-plus"></i>
                </div>

                <h3>Tambah Pesanan</h3>

                <button class="btn-add-order">
                + Buat Pesanan
                </button>
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
                            <span class="activity-text">Menambahkan pesanan</span>
                        </div>

                    </div>
                </div>

                <!-- ANTRIAN -->
                <div class="box order-card">
                  <div class="order-header">
                   <h3>Antrian Pesanan</h3>
                
                <select id="filterStatus">
                  <option value="">Semua Status</option>
                  <option value="Proses">Proses</option>
                  <option value="Selesai">Selesai</option>
                </select>
            </div>

                <div class="order-list">
                <div class="order-item" data-status="Proses">
                   <div>
                   <b>#INV001</b>
                   <p>Ahmad</p>
                  </div>
                  <span class="status process">Proses</span>
                </div>

                <div class="order-item" data-status= "Proses">
                   <div>
                   <b>#INV002</b>
                   <p>Andi</p>
                  </div>
                  <span class="status process">Proses</span>
                </div>

                <div class="order-item" data-status="Selesai">
                  <div>
                  <b>#INV003</b>
                  <p>Nisa</p>
                  </div>
                  <span class="status done">Selesai</span>
                </div>
                
            </div>
         </div>

        </div>
    </div>
</div>

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
                        <td>Menambahkan pesanan</td>
                        <td>Kasir</td>
                    </tr> 
                    <tr>
                        <td>12.09</td>
                        <td>Menambahkan pesanan</td>
                        <td>Kasir</td>
                    </tr>
                    <tr>
                        <td>12.09</td>
                        <td>Menambahkan pesanan</td>
                        <td>Kasir</td>
                    </tr>
                    <tr>
                        <td>12.09</td>
                        <td>Menambahkan pesanan</td>
                        <td>Kasir</td>
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

<!-- JAVASCRIPT -->
<script>
  // ===============================
  // MODAL AKTIVITAS
  // ===============================
  function openActivityModal() {
    document
      .getElementById("activityModal")
      .classList.add("show");
  }

  function closeActivityModal() {
    document
      .getElementById("activityModal")
      .classList.remove("show");
  }

  // Klik area gelap untuk menutup modal
  window.addEventListener("click", function (e) {
    const modal = document.getElementById("activityModal");

    if (e.target === modal) {
      closeActivityModal();
    }
  });

  // FILTER STATUS ANTRIAN INVOICE
document.addEventListener("DOMContentLoaded", function () {
  const filter = document.getElementById("filterStatus");
  if (!filter) return;

  filter.addEventListener("change", filterInvoice);

  function filterInvoice() {
    const status = filter.value;

    document.querySelectorAll(".order-item").forEach((item) => {
      const itemStatus = item.dataset.status;

      const cocok =
        status === "" || itemStatus === status;

      item.style.display = cocok ? "flex" : "none";
    });
  }
});
</script>
</script>

</body>
</html>