<?php
// Ambil ID Pesanan dari URL (Parameter untuk kebutuhan front-end/back-end nantinya)
$id_pesanan = isset($_GET['id']) ? $_GET['id'] : 'ORD001';

// Data Dummy / Tiruan untuk keperluan mockup tampilan front-end
$kategori = "Jersey"; 
$nama_pelanggan = "Alin Altaviani";
$telepon = "081234567890";
$tanggal = "16 Mei 2026";
$warna = "Biru Langit - Strip Putih";
$bahan = "Full Print (Milano)";
$sablon_opsi = "Gunakan Logo 3D Rubber";
$catatan = "1. Messi (No. 10) - Size L (Pendek)\n2. Ronaldo (No. 7) - Size M (Panjang)\n3. Neymar (No. 11) - Size XL (Pendek)";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?php echo $id_pesanan; ?> - Siblings.co</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_tshirt.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #F8F3E9; }
        .container { display: flex; min-height: 100vh; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .content-padding { padding: 40px; }
        
        /* Grid Layout */
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px; }
        
        /* Top Meta Info */
        .info-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 20px; border-radius: 10px; border: 1px solid #ddd; }
        .meta-box h3 { color: #4A3328; font-size: 1.3em; }
        .meta-box p { color: #666; font-size: 0.9em; margin-top: 5px; }
        
        /* Card Styling */
        .detail-card { background: white; border-radius: 12px; border: 1.5px solid #4A3328; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        .detail-card .card-header { background: #4A3328; color: white; padding: 15px 20px; font-weight: bold; display: flex; align-items: center; gap: 10px; }
        .detail-card .card-body { padding: 25px; }

        /* Spec List Styling */
        .spec-list { display: flex; flex-direction: column; gap: 15px; }
        .spec-item { display: flex; justify-content: space-between; border-bottom: 1px dashed #eee; padding-bottom: 10px; }
        .spec-item span:first-child { font-weight: bold; color: #888; font-size: 0.9em; }
        .spec-item span:last-child { font-weight: 600; color: #4A3328; }

        /* Design File Box */
        .image-preview-box { border: 2px dashed #4A3328; padding: 20px; text-align: center; border-radius: 8px; background: #fafafa; margin-top: 15px; }
        .image-preview-box i { font-size: 3em; color: #6d4c41; margin-bottom: 10px; display: block; }
        .image-preview-box a { color: #2e7d32; font-weight: bold; text-decoration: none; font-size: 0.9em; }

        /* Textarea Output Style */
        .nameset-area { background: #fdf5e6; border: 1px solid #e6d5b8; padding: 15px; border-radius: 8px; font-family: 'Courier New', Courier, monospace; font-size: 0.95em; color: #4A3328; white-space: pre-line; line-height: 1.5; }
        
        /* Action Buttons */
        .action-footer { display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1.5px solid #4A3328; }
        .btn-invoice { background: #79B473; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
        .btn-invoice:hover { background: #5a9354; box-shadow: 0 4px 12px rgba(121,180,115,0.3); }
        .btn-back { background: #666; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>

    <div class="container">
        <?php include('includes/sidebar.php'); ?>

        <main class="main-content">
            <?php include('includes/header.php'); ?>

            <div class="content-padding">
                <div class="info-meta">
                    <div class="meta-box">
                        <h3 style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            Pesanan #<?php echo htmlspecialchars($id_pesanan); ?> 
                            <span style="font-size: 0.7em; background: #e8f5e9; color: #2e7d32; padding: 3px 10px; border-radius: 20px; font-weight: bold;"><?php echo $kategori; ?></span>
                            
                            <?php 
                            $user_role = "owner"; 
                            ?>
                            <?php if ($user_role === 'owner') : ?>
                            <div style="display: inline-flex; align-items: center; gap: 6px; margin-left: 5px;">
                                <select id="statusSelect" style="padding: 5px 12px; border: 1.5px solid #4A3328; border-radius: 20px; font-size: 0.65em; font-weight: bold; color: white; background-color: #79B473; cursor: pointer; outline: none; text-align: center; transition: background-color 0.3s;">
                                    <option value="proses" style="background: white; color: #4A3328;" selected>Proses</option>
                                    <option value="selesai" style="background: white; color: #4A3328;">Selesai</option>
                                    <option value="belum" style="background: white; color: #4A3328;">Belum Diproses</option>
                                    <option value="batal" style="background: white; color: #D32F2F;">Batal</option>
                                </select>
                                
                                <button type="button" id="saveStatusBtn" title="Simpan Status" style="background: #4A3328; color: white; border: none; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.7em; transition: 0.2s;">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <?php else : ?>
                                 <span class="status-badge status-proses" style="font-size: 0.65em; padding: 5px 12px; border-radius: 20px; background-color: #79B473; color: white; font-weight: bold; margin-left: 5px;">Proses</span>
                                 <?php endif; ?>
                        </h3>
                        <p><i class="far fa-calendar-alt"></i> Tanggal Masuk: <strong><?php echo $tanggal; ?></strong></p>
                    </div>
                    <div class="meta-box" style="text-align: right;">
                        <p>Pelanggan: <strong style="font-size: 1.1em; color: #4A3328;"><?php echo $nama_pelanggan; ?></strong></p>
                        <p><i class="fas fa-phone"></i> <?php echo $telepon; ?></p>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-card">
                        <div class="card-header"><i class="fas fa-cogs"></i> Spesifikasi Produksi</div>
                        <div class="card-body">
                            <div class="spec-list">
                                <div class="spec-item">
                                    <span>Pilihan Paket / Bahan:</span>
                                    <span><?php echo $bahan; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span>Warna / Motif Kain:</span>
                                    <span><?php echo $warna; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span>Opsi Tambahan (Add-ons):</span>
                                    <span><?php echo $sablon_opsi; ?></span>
                                </div>
                            </div>

                            <h4 style="margin-top: 25px; color: #4A3328; font-size: 0.9em; font-weight: bold; text-transform: uppercase;">File Master Desain:</h4>
                            <div class="image-preview-box">
                                <i class="far fa-file-image"></i>
                                <a href="#" target="_blank"><i class="fas fa-download"></i> Lihat / Download Desain_Jersey.pdf</a>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="card-header"><i class="fas fa-th-list"></i> Rincian Ukuran yang Dipesan</div>
                        <div class="card-body">
                            <table class="size-table" style="margin-top: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Ukuran (Size)</th>
                                        <th style="text-align: center;">Lengan Pendek</th>
                                        <th style="text-align: center;">Lengan Panjang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td><strong>S</strong></td><td style="text-align: center;">5 pcs</td><td style="text-align: center;">0 pcs</td></tr>
                                    <tr><td><strong>M</strong></td><td style="text-align: center;">12 pcs</td><td style="text-align: center;">2 pcs</td></tr>
                                    <tr><td><strong>L</strong></td><td style="text-align: center;">20 pcs</td><td style="text-align: center;">5 pcs</td></tr>
                                    <tr><td><strong>XL</strong></td><td style="text-align: center;">10 pcs</td><td style="text-align: center;">1 pcs</td></tr>
                                    <tr><td><strong>XXL</strong> <small style="color:red;">(+10k)</small></td><td style="text-align: center;">3 pcs</td><td style="text-align: center;">0 pcs</td></tr>
                                </tbody>
                            </table>
                            
                            <div style="margin-top: 20px; display: flex; justify-content: space-between; font-weight: bold; background: #f9f9f9; padding: 12px; border-radius: 6px; border: 1px solid #eee;">
                                <span>TOTAL PRODUKSI BAJU:</span>
                                <span style="color: #4A3328;">58 Pcs</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-card" style="margin-top: 30px;">
                    <div class="card-header"><i class="fas fa-list-ol"></i> List Nameset / Keterangan Tambahan</div>
                    <div class="card-body">
                        <div class="nameset-area"><?php echo htmlspecialchars($catatan); ?></div>
                    </div>
                </div>

                <div class="action-footer">
                    <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke List</a>
                    <a href="invoice.php?id=<?php echo $id_pesanan; ?>" class="btn-invoice">Lihat & Cetak Invoice <i class="fas fa-file-invoice-dollar"></i></a>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const statusSelect = document.getElementById('statusSelect');
        const saveStatusBtn = document.getElementById('saveStatusBtn');

        // Fungsi mencocokkan warna background dengan status terpilih secara real-time
        function applyStatusStyle(value) {
            if (value === 'proses') {
                statusSelect.style.backgroundColor = '#79B473'; 
                statusSelect.style.borderColor = '#4A3328';
            } else if (value === 'selesai') {
                statusSelect.style.backgroundColor = '#2E7D32'; 
                statusSelect.style.borderColor = '#4A3328';
            } else if (value === 'batal') {
                statusSelect.style.backgroundColor = '#D32F2F'; 
                statusSelect.style.borderColor = '#D32F2F';
            } else {
                statusSelect.style.backgroundColor = '#A39382'; 
                statusSelect.style.borderColor = '#4A3328';
            }
        }

        // Inisialisasi awal saat load halaman
        applyStatusStyle(statusSelect.value);

        // Ubah warna seketika saat dropdown diganti oleh owner
        statusSelect.addEventListener('change', function() {
            applyStatusStyle(this.value);
        });

        // Feedback klik konfirmasi simpan
        saveStatusBtn.addEventListener('click', function() {
            const currentStatusText = statusSelect.options[statusSelect.selectedIndex].text;
            alert('Status pesanan berhasil diubah ke: ' + currentStatusText);
        });
    });
    </script>
</body>
</html>