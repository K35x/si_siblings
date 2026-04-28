<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Input Pesanan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* CSS tetap sama seperti sebelumnya */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #F8F3E9; min-height: 100vh; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-image: linear-gradient(rgba(15, 6, 1, 0.9), rgba(74, 51, 40, 0.9)), url('header.jpeg'); background-size: cover; background-position: center; color: white; flex-shrink: 0; padding-top: 30px; }
        .logo-container { padding: 0 25px; margin-bottom: 40px; }
        .logo-container h2 { font-style: italic; font-size: 28px; }
        nav { display: flex; flex-direction: column; gap: 15px; padding-left: 20px; }
        .nav-item { text-decoration: none; color: #4A3328; background: white; padding: 12px 20px; border-radius: 30px 0 0 30px; font-weight: bold; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; transform-origin: left; }
        .nav-item:hover { background-color: #79B473; color: white; transform: scale(1.08); padding-left: 30px; box-shadow: 5px 5px 15px rgba(0,0,0,0.2); }
        .nav-item.active { background-color: #E6D5B8; color: #4A3328; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .header-photo { width: 100%; height: 107px; background-image: url('header.jpeg'); background-size: cover; background-position: center; }
        .content-padding { padding: 30px 40px; }
        .back-nav { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; text-decoration: none; color: #4A3328; width: fit-content; }
        .btn-back-circle { background-color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #4A3328; transition: 0.3s; color: #4A3328; }
        .back-nav:hover .btn-back-circle { background-color: #4A3328; color: white; }
        .back-nav h1 { font-size: 28px; margin: 0; font-weight: 800; }
        .content-wrapper { display: flex; gap: 30px; align-items: stretch; }
        .form-section { flex: 2; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .input-group { display: flex; flex-direction: column; gap: 5px; }
        .input-group label { font-weight: bold; font-size: 15px; color: #4A3328; }
        .input-group input { padding: 12px; border: 2px solid #000; border-radius: 12px; font-size: 14px; outline: none; }
        .design-container { background-color: #A39382; padding: 20px; border-radius: 15px; display: flex; gap: 20px; border: 1.5px solid #4A3328; }
        .upload-box { flex: 1.2; border: 2px dashed #4A3328; border-radius: 10px; background: rgba(255, 255, 255, 0.1); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; text-align: center; cursor: pointer; position: relative; }
        .upload-box input[type="file"] { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .design-inputs { flex: 1; display: flex; flex-direction: column; gap: 10px; }
        .design-inputs input { background: white; padding: 10px; border-radius: 8px; border: 1.5px solid #4A3328; }
        .summary-box { flex: 1; background-color: #E6D5B8; padding: 25px; border-radius: 20px; border: 1.5px solid #A39382; display: flex; flex-direction: column; }
        .summary-header { border-bottom: 1px solid #A39382; padding-bottom: 15px; margin-bottom: 20px; }
        .summary-header h3 { color: #4A3328; font-size: 18px; }
        .summary-details { flex-grow: 1; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 12px; color: #4A3328; font-size: 14px; }
        .note-area { margin-top: 20px; }
        .note-area label { display: block; font-weight: bold; margin-bottom: 8px; font-size: 13px; }
        .note-area textarea { width: 100%; height: 120px; border-radius: 10px; border: 1.5px solid #4A3328; padding: 10px; resize: none; background: rgba(255,255,255,0.4); outline: none; }
        .total-section { padding-top: 20px; margin-top: auto; }
        .total-amount { font-size: 32px; color: #4A3328; font-weight: 900; margin-top: 5px; }
        .footer-action { display: flex; justify-content: flex-end; margin-top: 40px; gap: 15px; }
        .btn-cancel { background: transparent; color: #D9534F; border: 2px solid #D9534F; padding: 10px 30px; border-radius: 10px; font-size: 16px; font-weight: bold; text-decoration: none; transition: 0.3s; }
        .btn-cancel:hover { background: #D9534F; color: white; }
        
        /* Ubah tombol NEXT jadi anchor tag agar bisa link ke invoice */
        .btn-next { background-color: #79B473; color: white; border: none; padding: 10px 45px; border-radius: 10px; font-size: 20px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.3s; text-decoration: none; }
        .btn-next:hover { background-color: #618f5c; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo-container"><h2>Siblings.co</h2></div>
            <nav>
                <a href="beranda.php" class="nav-item"><i class="fas fa-home"></i> Beranda</a>
                <a href="index.php" class="nav-item active"><i class="fas fa-shopping-basket"></i> Pesanan</a>
                <a href="#" class="nav-item"><i class="fas fa-box"></i> Stok Barang</a>
                <a href="#" class="nav-item"><i class="fas fa-money-bill"></i> Keuangan</a>
                <a href="#" class="nav-item"><i class="fas fa-tshirt"></i> Katalog produk</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header-photo"></div>
            <div class="content-padding">
                <header>
                    <a href="index.php" class="back-nav">
                        <div class="btn-back-circle"><i class="fas fa-arrow-left"></i></div>
                        <h1>Input Pesanan & Desain</h1>
                    </a>
                </header>

                <form action="invoice.php" method="POST">
                    <div class="content-wrapper">
                        <div class="form-section">
                            <div class="form-grid">
                                <div class="input-group"><label>Nama Customer</label><input type="text" name="nama" placeholder="Masukkan nama..." required></div>
                                <div class="input-group"><label>Nomor Customer</label><input type="tel" name="nomor" placeholder="08xxxxxxxxxx" required></div>
                                <div class="input-group"><label>Nama Produk</label><input type="text" name="produk" placeholder="Nama baju/sablon..." required></div>
                                <div class="input-group"><label>Qty (Pcs)</label><input type="number" name="qty" value="0" min="1" required></div>
                                <div class="input-group" style="grid-column: span 2;"><label>Harga Dasar/Pcs</label><input type="text" name="harga" placeholder="Rp 0" required></div>
                            </div>

                            <div class="design-container">
                                <div class="upload-box">
                                    <input type="file" name="file_desain">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 35px; color: #4A3328; margin-bottom: 10px;"></i>
                                    <p style="font-weight: bold; font-size: 14px;">Upload File Desain</p>
                                    <span style="font-size: 10px;">.CDR / .AI / .PNG / .PDF</span>
                                </div>
                                <div class="design-inputs">
                                    <div class="input-group"><label>Teknik Cetak</label><input type="text" name="teknik" placeholder="DTF / Plastisol"></div>
                                    <div class="input-group"><label>Jumlah Titik</label><input type="number" name="titik" value="0"></div>
                                </div>
                            </div>
                        </div>

                        <aside class="summary-box">
                            <div class="summary-header"><h3>Estimasi Biaya Produksi</h3></div>
                            <div class="summary-details">
                                <div class="summary-item"><span>Harga Dasar (Polos)</span><strong>Rp100.000,00</strong></div>
                                <div class="summary-item"><span>Biaya Bordir/Sablon</span><strong>Rp20.000,00</strong></div>
                                <div class="note-area">
                                    <label><i class="fas fa-edit"></i> Catatan Produksi (Ukuran/Warna)</label>
                                    <textarea name="catatan" placeholder="Tulis rincian detail pesanan di sini..."></textarea>
                                </div>
                            </div>
                            <div class="total-section">
                                <p style="font-size: 14px; font-weight: bold;">Total Tagihan:</p>
                                <div class="total-amount">Rp12.000.000,00</div>
                            </div>
                        </aside>
                    </div>

                    <div class="footer-action">
                        <a href="index.php" class="btn-cancel">BATAL</a>
                        <button type="submit" class="btn-next">NEXT <i class="fas fa-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>