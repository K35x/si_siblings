<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Siblings.co</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/finance.css') ?>">
</head>
<body>
<div class="container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/header.php'; ?>
        <div class="content-padding">
            <h1 class="finance-title">Laporan Penjualan</h1>

            <div class="stats-grid sales-stats-grid">
                <div class="stat-card sales-stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="stat-info">
                        <p>Total Penjualan</p>
                        <h3>Rp 25.000.000</h3>
                    </div>
                </div>
                <div class="stat-card sales-stat-card highlight">
                    <i class="fas fa-box"></i>
                    <div class="stat-info">
                        <p>Total Produk Terjual</p>
                        <h3>320 pcs</h3>
                    </div>
                </div>
                <div class="stat-card sales-stat-card">
                    <i class="fas fa-receipt"></i>
                    <div class="stat-info">
                        <p>Jumlah Transaksi</p>
                        <h3>45 Pesanan</h3>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <label><i class="fas fa-filter"></i> Filter:</label>
                <select>
                    <option>Semua Bulan</option>
                    <option>Januari 2026</option>
                    <option>Februari 2026</option>
                    <option>Maret 2026</option>
                </select>
                <input type="date" value="2026-01-01">
                <input type="date" value="2026-12-31">
                <button class="btn-filter">Terapkan</button>
            </div>

            <div class="finance-toolbar">
                <h2>Detail Transaksi Penjualan</h2>
                <div class="finance-tools">
                    <div class="finance-search">
                        <input type="text" placeholder="Cari pesanan...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor Pesanan</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total Penjualan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><span class="date-pill">07/05/2026</span></td>
                            <td>ORD-001</td>
                            <td>Ahmad</td>
                            <td>Kaos Polos</td>
                            <td>10 pcs</td>
                            <td>Rp 500.000</td>
                            <td class="status-selesai">Selesai <i class="fas fa-check"></i></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><span class="date-pill">06/05/2026</span></td>
                            <td>ORD-002</td>
                            <td>Siti</td>
                            <td>Kemeja</td>
                            <td>5 pcs</td>
                            <td>Rp 750.000</td>
                            <td class="status-selesai">Selesai <i class="fas fa-check"></i></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><span class="date-pill">05/05/2026</span></td>
                            <td>ORD-003</td>
                            <td>Budi</td>
                            <td>Jaket</td>
                            <td>3 pcs</td>
                            <td>Rp 900.000</td>
                            <td class="status-pending">Pending <i class="fas fa-clock"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
