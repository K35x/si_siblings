<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keuangan - Siblings.co</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/finance.css') ?>">
</head>
<body>
<div class="container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/header.php'; ?>
        <div class="content-padding">
    <h1 class="finance-title">Dashboard Keuangan</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <p>Total Pemasukan</p>
            <h3>Rp 25.000.000</h3>
        </div>
        <div class="stat-card">
            <p>Total Pengeluaran</p>
            <h3>Rp 15.500.000</h3>
        </div>
        <div class="stat-card highlight">
            <p>Laba Bersih</p>
            <h3>Rp 9.000.000</h3>
        </div>
        <div class="stat-card">
            <p>Produk Terjual</p>
            <h3>320 pcs</h3>
        </div>
    </div>

    <div class="finance-overview">
        <div class="finance-chart-card">
            <h3>Pemasukan vs Pengeluaran</h3>
            <div class="finance-bars">
                <div class="finance-bar income finance-bar-60"></div>
                <div class="finance-bar expense finance-bar-30"></div>
                <div class="finance-bar income finance-bar-80"></div>
                <div class="finance-bar expense finance-bar-40"></div>
            </div>
            <div class="finance-chart-labels">
                <span>Januari</span><span>Februari</span>
            </div>
        </div>

        <div class="finance-summary-list">
            <div class="finance-summary-card">
                <span class="finance-summary-label">Pemasukan <i class="fas fa-chevron-circle-right"></i></span>
                <span class="finance-summary-value">3.500.000</span>
            </div>
            <div class="finance-summary-card">
                <span class="finance-summary-label">Pengeluaran <i class="fas fa-chevron-circle-right"></i></span>
                <span class="finance-summary-value">Rp 1.500.000</span>
            </div>
        </div>
    </div>

    <div class="finance-toolbar">
        <h2>Daftar Transaksi Terbaru</h2>
        <div class="finance-tools">
             <div class="finance-filter">
                <i class="fas fa-list"></i> <span>Kategori</span>
             </div>
             <div class="finance-search">
                <input type="text" placeholder="Cari">
                <i class="fas fa-search"></i>
             </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
            <tr class="transaction-row">
                <td><span class="date-pill">1/2/2026</span></td>
                <td>Penjualan Kaos</td>
                <td>Pemasukan</td>
                <td>5.000.000</td>
                <td class="status-badge">Selesai <i class="fas fa-check"></i></td>
            </tr>
        </table>
    </div>
        </div>
    </main>
</div>
</body>
</html>
