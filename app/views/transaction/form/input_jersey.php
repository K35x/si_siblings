<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Detail Pesanan Jersey</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../style_tshirt.css">
    <style>
        /* Perbaikan styling kecil untuk merapikan summary */
        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .status-values {
            display: flex;
            gap: 10px;
            min-width: 80px;
            justify-content: flex-end;
        }
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

                <h1 class="main-title">Konfigurasi Custom Jersey</h1>
            
                <form action="../keranjang.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="kategori" value="Jersey">
                    
                    <div class="form-grid">
                        <!-- Section Kiri -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-tshirt"></i> Spesifikasi Produk</div>
                            <div class="card-body">
                                <div class="input-group">
                                    <label>PILIH PAKET JERSEY</label>
                                    <select name="paket_bahan" id="paketBahan" onchange="calculateTotal();" required>
                                        <option value="" disabled selected>-- Pilih Paket --</option>
                                        <option value="110000">Print Depan (Polyflex) - Rp 110.000</option>
                                        <option value="120000">Full Print (Milano) - Rp 120.000</option>
                                        <option value="135000">Premium (Embose/Airwalk) - Rp 135.000</option>
                                        <option value="150000">Bahan Jaquard - Rp 150.000</option>
                                        <option value="150000">Full Print Baju & Celana (Embose) - Rp 150.000</option>
                                        <option value="165000">Full Print Baju & Celana (Jaquard) - Rp 165.000</option>
                                        <option value="95000">Baju Atasan Saja - Rp 95.000</option>
                                        <option value="100000">Jersey Non-Print (DTF/Polyflex) - Rp 100.000</option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label>WARNA/MOTIF JERSEY</label>
                                    <input type="text" name="warna_kain" required placeholder="Contoh: Biru Strip Putih">
                                </div>

                                <!-- Logo 3D dipindah ke bawah Warna dan dirapikan -->
                                <div class="input-group">
                                    <label style="display: flex; align-items: center; cursor: pointer; background: #fff8e1; padding: 12px; border-radius: 5px; border: 1px solid #ffe082; justify-content: flex-start;">
                                        <input type="checkbox" id="plusLogo3D" name="add_logo3d" value="30000" onchange="calculateTotal()" style="margin-right: 12px; transform: scale(1.2);"> 
                                        <span style="font-size: 0.85em; font-weight: bold; color: #6d4c41;">GUNAKAN LOGO 3D RUBBER/BORDIR (+30K/PCS)</span>
                                    </label>
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

                        <!-- Section Kanan -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-th-list"></i> Rincian Ukuran & Add-ons</div>
                            <div class="card-body">
                                <table class="size-table">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Pendek</th>
                                            <th>Panjang (+10k)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                                        foreach($sizes as $sz): 
                                            $isBigSize = ($sz == 'XXL') ? 'data-xxl="10000"' : 'data-xxl="0"';
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $sz; ?></strong> <?php echo ($sz == 'XXL') ? '<br><small>(+10k)</small>' : ''; ?></td>
                                            <td><input type="number" name="qty_short_<?php echo $sz; ?>" class="qty-input short" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                            <td><input type="number" name="qty_long_<?php echo $sz; ?>" class="qty-input long" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- TAMBAHAN KAOS KAKI -->
                                <div class="input-group" style="margin-top: 20px; padding: 15px; background: #e8f5e9; border-radius: 8px; border: 1px solid #c8e6c9;">
                                    <label style="font-weight: bold; color: #2e7d32; font-size: 0.85em;"><i class="fas fa-socks"></i> TAMBAHAN KAOS KAKI (+45K/PSG)</label>
                                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                                        <input type="number" name="qty_kaoskaki" id="qtyKaosKaki" min="0" value="0" onchange="calculateTotal()" style="width: 70px; padding: 8px; border: 1px solid #a5d6a7; border-radius: 4px; text-align: center;">
                                        <span style="font-size: 0.9em; font-weight: 500;">Pasang</span>
                                    </div>
                                    <small style="color: #666; display: block; margin-top: 5px; font-size: 0.75em;">*Jumlah kaos kaki bisa berbeda dengan jumlah baju.</small>
                                </div>

                                <!-- SUMMARY BOX YANG SUDAH DILURUSKAN -->
                                <div class="order-summary-box" style="margin-top: 20px; border: 2px dashed #ccc; padding: 15px; border-radius: 10px;">
                                    <div class="status-row">
                                        <span style="font-weight: bold;">Total Baju:</span> 
                                        <div class="status-values">
                                            <span id="totalQty" style="font-weight: bold;">0</span>
                                            <span style="width: 30px; text-align: right;">Pcs</span>
                                        </div>
                                    </div>
                                    <div class="status-row">
                                        <span style="font-weight: bold;">Total Kaos Kaki:</span> 
                                        <div class="status-values">
                                            <span id="valKausKaki" style="font-weight: bold;">0</span>
                                            <span style="width: 30px; text-align: right;">Psg</span>
                                        </div>
                                    </div>
                                    <div class="status-row" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                                        <span style="font-weight: bold; font-size: 1.1em;">Estimasi:</span> 
                                        <span id="totalHarga" style="color: #2e7d32; font-weight: 800; font-size: 1.3em;">Rp 0</span>
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
            const basePrice = parseInt(document.getElementById('paketBahan').value) || 0;
            const priceLogo3D = document.getElementById('plusLogo3D').checked ? 30000 : 0;
            const qtyKaosKaki = parseInt(document.getElementById('qtyKaosKaki').value) || 0;
            const totalHargaKaosKaki = qtyKaosKaki * 45000;
            
            let totalBaju = 0;
            let totalHargaBaju = 0;

            document.querySelectorAll('.qty-input.short').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraXXL = parseInt(input.getAttribute('data-xxl')) || 0;
                totalBaju += qty;
                totalHargaBaju += qty * (basePrice + extraXXL + priceLogo3D);
            });

            document.querySelectorAll('.qty-input.long').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraXXL = parseInt(input.getAttribute('data-xxl')) || 0;
                totalBaju += qty;
                totalHargaBaju += qty * (basePrice + extraXXL + 10000 + priceLogo3D); 
            });

            const grandTotal = totalHargaBaju + totalHargaKaosKaki;

            document.getElementById('totalQty').innerText = totalBaju;
            document.getElementById('valKausKaki').innerText = qtyKaosKaki;
            document.getElementById('totalHarga').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

            const btn = document.getElementById('btnSubmit');
            if ((totalBaju > 0 && basePrice > 0) || (qtyKaosKaki > 0)) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }
    </script>
</body>
</html>