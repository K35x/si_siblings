<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Detail Seragam Olahraga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../style_tshirt.css">
    <style>
        .status-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .status-values { display: flex; gap: 10px; min-width: 80px; justify-content: flex-end; }
        .readonly-input { background-color: #f0f0f0; cursor: not-allowed; }
    </style>
</head>
<body>

    <div class="container">
        <?php include('../includes/sidebar.php'); ?>

        <main class="main-content">
            <?php include('../includes/header.php'); ?>

            <div class="content-padding">
                <a href="../pilih_kategori.php" class="btn-back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                </a>

                <h1 class="main-title">Konfigurasi Seragam Olahraga</h1>
            
                <form action="../keranjang.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="kategori" value="Seragam Olahraga">
                    <!-- Harga dikunci 95k sesuai spek -->
                    <input type="hidden" id="paketBahan" value="95000">
                    
                    <div class="form-grid">
                        <!-- Section Kiri: Pilihan Spesifikasi -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-running"></i> Spesifikasi Produk</div>
                            <div class="card-body">
                                
                                <div class="input-group">
                                    <label>BAHAN BAJU</label>
                                    <select name="jenis_bahan" required>
                                        <option value="" disabled selected>-- Pilih Bahan --</option>
                                        <option value="Semi Cotton">Semi Cotton</option>
                                        <option value="TC">TC (Teteron Cotton)</option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label>JENIS SABLON</label>
                                    <select name="jenis_sablon" required>
                                        <option value="" disabled selected>-- Pilih Sablon --</option>
                                        <option value="Rubber">Sablon Rubber</option>
                                        <option value="DTF">Sablon DTF</option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label>BAHAN TRAINING (OTOMATIS)</label>
                                    <input type="text" name="bahan_training" value="Bahan Diadora" class="readonly-input" readonly>
                                </div>

                                <div class="input-group">
                                    <label>WARNA SERAGAM & TRAINING</label>
                                    <input type="text" name="warna_kain" required placeholder="Contoh: Baju Biru, Training Hitam">
                                </div>

                               <div class="design-upload-section">
                                    <div class="card-header" style="background: #6d4c41; color: white; margin: 0 -20px 15px -20px; padding: 8px 20px; font-size: 0.9em;">
                                        <i class="fas fa-image"></i> Upload Referensi Desain
                                    </div>
                                    <div class="upload-container">
                                        <div class="upload-item">
                                            <input type="file" name="desain_1" class="file-input">
                                            <input type="text" name="note_desain_1" placeholder="Ket: Bordir Depan" class="note-input">
                                        </div>
                                        <div class="upload-item">
                                            <input type="file" name="desain_2" class="file-input">
                                            <input type="text" name="note_desain_2" placeholder="Ket: Bordir Belakang" class="note-input">
                                        </div>
                                        <div class="upload-item">
                                            <input type="file" name="desain_3" class="file-input">
                                            <input type="text" name="note_desain_3" placeholder="Ket: Bordir Samping" class="note-input">
                                        </div>
                                        <div class="input-group" style="margin-top: 15px;">
                                            <label><i class="fas fa-sticky-note"></i> Catatan Tambahan Pesanan</label>
                                            <textarea name="catatan_pesanan" rows="4" placeholder="Contoh: Nama di dada kanan, logo di lengan kiri, pakai furing hero, dll..." style="width: 100%; border: 1px solid #ddd; border-radius: 5px; padding: 10px; font-family: inherit; resize: none;"></textarea>
</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Kanan: Rincian Ukuran -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-list-ol"></i> Rincian Ukuran</div>
                            <div class="card-body">
                                <table class="size-table">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Pendek</th>
                                            <th>Panjang (+5k)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                                        foreach($sizes as $sz): 
                                            // Tambahan 5k untuk size di atas XXL (XXXL) sesuai gambar
                                            $isBigSize = ($sz == 'XXXL') ? 'data-bigsize="5000"' : 'data-bigsize="0"';
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $sz; ?></strong> <?php echo ($sz == 'XXXL') ? '<br><small>(+5k)</small>' : ''; ?></td>
                                            <td><input type="number" name="qty_short_<?php echo $sz; ?>" class="qty-input short" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                            <td><input type="number" name="qty_long_<?php echo $sz; ?>" class="qty-input long" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <div class="order-summary-box" style="margin-top: 20px; border: 2px dashed #ccc; padding: 15px; border-radius: 10px;">
                                    <div class="status-row">
                                        <span style="font-weight: bold;">Total Pesanan:</span> 
                                        <div class="status-values">
                                            <span id="totalQty" style="font-weight: bold;">0</span>
                                            <span style="width: 30px; text-align: right;">Stel</span>
                                        </div>
                                    </div>
                                    <div class="status-row" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                                        <span style="font-weight: bold; font-size: 1.1em;">Estimasi:</span> 
                                        <span id="totalHarga" style="color: #2e7d32; font-weight: 800; font-size: 1.3em;">Rp 0</span>
                                    </div>
                                    <!-- Warning Minimal Order 24 Pcs sesuai gambar -->
                                    <div id="minOrderWarning" style="color: #d32f2f; font-size: 0.85em; margin-top: 10px; display: none; font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> Minimal pemesanan adalah 24 pcs.
                                    </div>
                                    <button type="submit" id="btnSubmit" class="btn-primary-order" disabled style="width: 100%; margin-top: 15px; padding: 12px; font-weight: bold;">
                                        TAMBAHKAN KE KERANJANG <i class="fas fa-shopping-bag"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function calculateTotal() {
            // Harga dasar 95k dari hidden input
            const basePrice = parseInt(document.getElementById('paketBahan').value) || 0;
            
            let totalQty = 0;
            let totalPrice = 0;

            // Hitung Pendek
            document.querySelectorAll('.qty-input.short').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraSize = parseInt(input.getAttribute('data-bigsize')) || 0;
                totalQty += qty;
                totalPrice += qty * (basePrice + extraSize);
            });

            // Hitung Panjang (+5k) sesuai gambar
            document.querySelectorAll('.qty-input.long').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraSize = parseInt(input.getAttribute('data-bigsize')) || 0;
                totalQty += qty;
                totalPrice += qty * (basePrice + extraSize + 5000); 
            });

            document.getElementById('totalQty').innerText = totalQty;
            document.getElementById('totalHarga').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

            const btn = document.getElementById('btnSubmit');
            const warning = document.getElementById('minOrderWarning');

            // Validasi: Minimal 24 pcs sesuai NB di gambar
            if (totalQty >= 24) {
                btn.disabled = false;
                warning.style.display = 'none';
            } else {
                btn.disabled = true;
                warning.style.display = totalQty > 0 ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>