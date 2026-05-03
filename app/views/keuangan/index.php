<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keuangan - Siblings.co</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #F8F3E9; overflow-x: hidden; }

        /* Struktur Pembungkus Utama */
        .container { display: flex; min-height: 100vh; }
        
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .content-padding { padding: 30px 40px; }

        /* Styling Statistik Card */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { 
            background-color: #E6D5B8; padding: 20px; border-radius: 20px; 
            display: flex; align-items: center; gap: 15px; border: 1px solid #A39382;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .stat-card i { font-size: 25px; color: #4A3328; }
        .stat-info p { font-size: 13px; font-weight: bold; color: #4A3328; opacity: 0.8; }
        .stat-info h3 { font-size: 18px; font-weight: 900; color: #4A3328; }

        /* Styling Tabel */
        .table-container { 
            background: white; border-radius: 15px; overflow: hidden; 
            border: 2px solid #E6D5B8; margin-top: 20px;
        }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #F8F3E9; padding: 15px; text-align: left; color: #A39382; font-weight: 800; }
        td { padding: 15px; border-bottom: 1px solid #F8F3E9; color: #4A3328; font-weight: 600; }
        
        .status-badge { color: #79B473; font-weight: 800; display: flex; align-items: center; gap: 5px; }
    </style>
</head>
<body>
<div class="content-padding">
    <h1 style="color: #4A3328; font-weight: 900; margin-bottom: 25px;">Finance Dashboard</h1>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px;">
        <div class="stat-card" style="background:#E6D5B8; padding:15px; border-radius:15px; border:1px solid #A39382;">
            <p style="font-size:12px; font-weight:bold;">Total Pemasukan</p>
            <h3 style="font-weight:900;">Rp 25.000.000</h3>
        </div>
        <div class="stat-card" style="background:#E6D5B8; padding:15px; border-radius:15px; border:1px solid #A39382;">
            <p style="font-size:12px; font-weight:bold;">Total Pengeluaran</p>
            <h3 style="font-weight:900;">Rp 15.500.000</h3>
        </div>
        <div class="stat-card" style="background:#E6D5B8; padding:15px; border-radius:15px; border:2px solid #4A3328;">
            <p style="font-size:12px; font-weight:bold;">Labah Bersih</p>
            <h3 style="font-weight:900;">Rp 9.000.000</h3>
        </div>
        <div class="stat-card" style="background:#E6D5B8; padding:15px; border-radius:15px; border:1px solid #A39382;">
            <p style="font-size:12px; font-weight:bold;">Produk Terjual</p>
            <h3 style="font-weight:900;">320 pcs</h3>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
        <div style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('header.jpeg'); background-size: cover; padding: 20px; border-radius: 15px; color: white;">
            <h3 style="font-weight: 800;">Pemasukan vs Pengeluaran</h3>
            <div style="display: flex; align-items: flex-end; gap: 10px; height: 150px; margin-top: 20px;">
                <div style="width: 30px; height: 60%; background: #E6D5B8; border-radius: 5px;"></div>
                <div style="width: 30px; height: 30%; background: #4A3328; border-radius: 5px;"></div>
                <div style="width: 30px; height: 80%; background: #E6D5B8; border-radius: 5px;"></div>
                <div style="width: 30px; height: 40%; background: #4A3328; border-radius: 5px;"></div>
            </div>
            <div style="display: flex; gap: 40px; margin-top: 10px; font-size: 12px; font-weight: bold;">
                <span>Januari</span><span>Februari</span>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div style="background: white; padding: 20px; border-radius: 15px; border: 2.5px solid #4A3328; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 800;">Pemasukan <i class="fas fa-chevron-circle-right"></i></span>
                <span style="font-weight: 900; font-size: 20px;">3.500.000</span>
            </div>
            <div style="background: white; padding: 20px; border-radius: 15px; border: 2.5px solid #4A3328; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 800;">Pengeluaran <i class="fas fa-chevron-circle-right"></i></span>
                <span style="font-weight: 900; font-size: 20px;">Rp 1.500.000</span>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 style="font-weight: 900; color: #4A3328;">Daftar Transaksi Terbaru</h2>
        <div style="display: flex; gap: 10px;">
             <div style="background: white; padding: 5px 15px; border-radius: 20px; border: 1px solid #000; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-list"></i> <span style="font-weight: bold;">Kategori</span>
             </div>
             <div style="background: white; padding: 5px 15px; border-radius: 20px; border: 1px solid #000;">
                <input type="text" placeholder="Search" style="border:none; outline:none; font-weight:bold;">
                <i class="fas fa-search"></i>
             </div>
        </div>
    </div>

    <div style="background: white; border-radius: 10px; overflow: hidden; border: 1px solid #E6D5B8;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #F8F3E9; color: #A39382; font-weight: 800;">
                <th style="padding: 15px; text-align: center;">Tanggal</th>
                <th style="padding: 15px; text-align: center;">Keterangan</th>
                <th style="padding: 15px; text-align: center;">Kategori</th>
                <th style="padding: 15px; text-align: center;">Nominal</th>
                <th style="padding: 15px; text-align: center;">Status</th>
            </tr>
            <tr style="text-align: center; font-weight: bold; color: #4A3328;">
                <td style="padding: 15px;"><span style="background:#F1F1F1; padding:5px 10px; border-radius:15px;">1/2/2026</span></td>
                <td>Penjualan Kaos</td>
                <td>Pemasukan</td>
                <td>5.000.000</td>
                <td style="color: #79B473;">Selesai <i class="fas fa-check"></i></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>