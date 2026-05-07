<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Detail Pesanan Polo Shirt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../style_tshirt.css">
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

                <h1 class="main-title">Konfigurasi Custom Polo Shirt</h1>
            
                <form action="../keranjang.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="kategori" value="Polo Shirt">
                    
                    <div class="form-grid">
    
                        <!-- Section Kiri: Produksi & Desain -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-cut"></i> Spesifikasi Produk
                        </div>
                            <div class="card-body">
                                <div class="input-group">
                                    <label>Pilih Jenis Bahan Polo</label>
                                    <select name="paket_bahan" id="paketBahan" onchange="updateDetailPolo(); calculateTotal();" required>
                                        <option value="" disabled selected>-- Pilih Bahan --</option>
                                        <option value="85000" data-label="Full Premium Cotton 24s">Full Premium Cotton 24s (Rp 85.000)</option>
                                        <option value="85000" data-label="Lacos 24s">Lacos 24s (Rp 85.000)</option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label>Detail Jenis Bahan</label>
                                    <input type="text" name="jenis_bahan" id="jenisBahan" readonly style="background-color: #f9f9f9;">
                                </div>

                                <div class="input-group">
                                    <label>Detail Sablon</label>
                                    <select name="jenis_aplikasi" id="jenisAplikasi" required>
                                        <option value="">Pilih bahan dahulu...</option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label>Warna Kain Polo</label>
                                    <input type="text" name="warna_kain" required placeholder="Contoh: Merah Cabai / Biru Navy">
                                </div>

                                <!-- Bagian Upload -->
                                <div class="design-upload-section">
                                    <div class="card-header" style="background: #6d4c41; color: white; margin: 0 -20px 15px -20px; padding: 8px 20px; font-size: 0.9em;">
                                        <i class="fas fa-image"></i> Upload Desain (Bordir/Sablon)
                                    </div>
                                    
                                    <div class="upload-container">
                                        <div class="upload-item">
                                            <input type="file" name="desain_1" class="file-input">
                                            <input type="text" name="note_desain_1" placeholder="Ket: Lokasi Depan (Logo)" class="note-input">
                                        </div>
                                        <div class="upload-item">
                                            <input type="file" name="desain_2" class="file-input">
                                            <input type="text" name="note_desain_2" placeholder="Ket: Lokasi Belakang" class="note-input">
                                        </div>
                                        <div class="upload-item">
                                            <input type="file" name="desain_3" class="file-input">
                                            <input type="text" name="note_desain_3" placeholder="Ket: Lokasi Samping/Lengan" class="note-input">
                                        </div>
                                        <div class="input-group" style="margin-top: 15px;">
                                            <label><i class="fas fa-sticky-note"></i> Catatan Tambahan Pesanan</label>
                                            <textarea name="catatan_pesanan" rows="4" placeholder="Contoh: Bordir standar 2 titik, pakai kancing warna hitam, dll..." style="width: 100%; border: 1px solid #ddd; border-radius: 5px; padding: 10px; font-family: inherit; resize: none;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Kanan: Size & Summary -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-table"></i> Rincian Ukuran</div>
                            <div class="card-body">
                                <table class="size-table">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Lengan Pendek</th>
                                            <th>Lengan Panjang (+5k)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                                        foreach($sizes as $sz): 
                                            // Diatas XL (XXL) kena biaya tambahan 5k
                                            $isBigSize = ($sz == 'XXL') ? 'data-xxl="5000"' : 'data-xxl="0"';
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo $sz; ?></strong>
                                                <?php if($sz == 'XXL'): ?>
                                                    <br><small style="color: #d32f2f;">(+5k Big Size)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td><input type="number" name="qty_short_<?php echo $sz; ?>" class="qty-input short" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                            <td><input type="number" name="qty_long_<?php echo $sz; ?>" class="qty-input long" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <div class="order-summary-box">
                                    <div class="status-row"><span>Total Qty:</span> <span id="totalQty">0</span> / 24 Pcs</div>
                                    <div class="status-row"><span>Estimasi:</span> <span id="totalHarga" style="color: #2e7d32; font-weight: bold; font-size: 1.2em;">Rp 0</span></div>
                                    <button type="submit" id="btnSubmit" class="btn-primary-order" disabled style="width: 100%; margin-top: 15px;">
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
        function updateDetailPolo() {
            const selectPaket = document.getElementById('paketBahan');
            const labelBahan = selectPaket.options[selectPaket.selectedIndex].getAttribute('data-label');
            const detailBahan = document.getElementById('jenisBahan');
            const selectAplikasi = document.getElementById('jenisAplikasi');

            detailBahan.value = labelBahan;
            selectAplikasi.innerHTML = ""; 
            
            if (labelBahan === "Full Premium Cotton 24s") {
                const options = ["Sablon Plastisol", "Sablon DTF"];
                options.forEach(val => {
                    let opt = document.createElement('option');
                    opt.value = val;
                    opt.text = val;
                    selectAplikasi.appendChild(opt);
                });
            } 
            else if (labelBahan === "Lacos 24s") {
                const options = ["Sablon DTF"];
                options.forEach(val => {
                    let opt = document.createElement('option');
                    opt.value = val;
                    opt.text = val;
                    selectAplikasi.appendChild(opt);
                });
            }
        }

        function calculateTotal() {
            const basePrice = parseInt(document.getElementById('paketBahan').value) || 0;
            let totalQty = 0;
            let totalPrice = 0;

            // Hitung Lengan Pendek
            document.querySelectorAll('.qty-input.short').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraXXL = parseInt(input.getAttribute('data-xxl')) || 0;
                totalQty += qty;
                totalPrice += qty * (basePrice + extraXXL);
            });

            // Hitung Lengan Panjang (+5k)
            document.querySelectorAll('.qty-input.long').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraXXL = parseInt(input.getAttribute('data-xxl')) || 0;
                totalQty += qty;
                totalPrice += qty * (basePrice + extraXXL + 5000); 
            });

            document.getElementById('totalQty').innerText = totalQty;
            document.getElementById('totalHarga').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

            const btn = document.getElementById('btnSubmit');

            // Minimal order 24 pcs sesuai NB di gambar
            if (totalQty >= 24 && basePrice > 0) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }
    </script>
</body>
</html>