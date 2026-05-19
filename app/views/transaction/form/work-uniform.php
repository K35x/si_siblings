<?php
session_start();

$edit_index = $_GET['edit'] ?? "";
$data_lama = ($edit_index !== "") ? $_SESSION['keranjang'][$edit_index] : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Detail Pesanan PDH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/transactions.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/transaction-form.css') ?>">
</head>
<body>

    <div class="container">
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">
            <?php include __DIR__ . '/../includes/header.php'; ?>

            <div class="content-padding">
                <a href="<?= url('/transactions/categories') ?>" class="btn-back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                </a>

                <h1 class="main-title">Konfigurasi Custom PDH</h1>
            
                <form action="<?= url('/transactions/cart') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="kategori" value="PDH">
                    <input type="hidden" name="index_edit" value="<?php echo $edit_index; ?>">

                    <div class="form-grid">
    
                        <!-- Section Kiri: Produksi & Desain -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-cut"></i> Spesifikasi Produk</div>
                            <div class="card-body">
                                <div class="input-group">
                                    <label>Pilih Jenis Bahan Kain</label>
                                    <select name="jenis_bahan" id="paketBahan" onchange="calculateTotalPDH()" required>
                                        <option value="" disabled <?php echo !$data_lama ? 'selected' : ''; ?>>-- Pilih Bahan --</option>
                                        <option value="Unione" data-harga="130000" <?php echo ($data_lama && $data_lama['bahan'] == 'Unione') ? 'selected' : ''; ?>>Unione (Rp 130.000)</option>
                                        <option value="American Drill" data-harga="135000" <?php echo ($data_lama && $data_lama['bahan'] == 'American Drill') ? 'selected' : ''; ?>>American Drill (Rp 135.000)</option>
                                        <option value="Nagata Drill" data-harga="140000" <?php echo ($data_lama && $data_lama['bahan'] == 'Nagata Drill') ? 'selected' : ''; ?>>Nagata Drill (Rp 140.000)</option>
                                    </select>
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

                        <!-- Section Kanan: Size & Model Lengan -->
                        <div class="form-card">
                            <div class="card-header"><i class="fas fa-table"></i> Rincian Ukuran & Lengan</div>
                            <div class="card-body">
                                <table class="size-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">Size</th>
                                            <th style="width: 20%;">Jumlah Qty</th>
                                            <th style="width: 65%;">Keterangan Warna Berbeda</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="text-left" style="background: #fafafa; padding: 6px 10px;">
                                                <span class="section-label-lengan">LENGAN PENDEK</span>
                                            </td>
                                        </tr>
                                        <?php 
                                        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                                        foreach($sizes as $sz): 
                                            $isBigSize = ($sz == 'XXL') ? 'data-xxl="10000"' : 'data-xxl="0"';
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $sz; ?></strong> <?php echo ($sz == 'XXL') ? '<br><small style="color:red; font-weight:bold;">(+10k)</small>' : ''; ?></td>
                                            <td><input type="number" name="qty_short_<?php echo $sz; ?>" class="qty-input short" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                            <td><input type="text" name="warna_short_<?php echo $sz; ?>" class="warna-input-inline" placeholder="Warna khusus size <?php echo $sz; ?> pendek..."></td>
                                        </tr>
                                        <?php endforeach; ?>

                                        <tr>
                                            <td colspan="3" class="text-left" style="background: #fafafa; padding: 15px 10px 6px 10px;">
                                                <span class="section-label-lengan" style="background: #a39382;"> LENGAN PANJANG (+10K)</span>
                                            </td>
                                        </tr>
                                        <?php 
                                        foreach($sizes as $sz): 
                                            $isBigSize = ($sz == 'XXL') ? 'data-xxl="10000"' : 'data-xxl="0"';
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $sz; ?></strong> <?php echo ($sz == 'XXL') ? '<br><small style="color:red; font-weight:bold;">(+10k)</small>' : ''; ?></td>
                                            <td><input type="number" name="qty_long_<?php echo $sz; ?>" class="qty-input long" min="0" value="0" <?php echo $isBigSize; ?> onchange="calculateTotal()"></td>
                                            <td><input type="text" name="warna_long_<?php echo $sz; ?>" class="warna-input-inline" placeholder="Warna khusus size <?php echo $sz; ?> panjang..."></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <div class="order-summary-box">
                                    <div class="status-row"><span>Total Qty:</span> <span id="totalQty">0</span> / 24 Pcs</div>
                                    <div class="status-row"><span>Estimasi:</span> <span id="totalHarga" style="color: #2e7d32; font-weight: bold; font-size: 1.2em;">Rp 0</span></div>
                                    <button type="submit" id="btnSubmit" class="btn-primary-order" disabled style="width: 100%; margin-top: 15px;">
                                        <?php echo ($edit_index !== "") ? "SIMPAN PERUBAHAN" : "TAMBAHKAN KE KERANJANG"; ?> <i class="fas fa-shopping-bag"></i>
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
        function calculateTotalPDH() {
            const selectBahan = document.getElementById('paketBahan');
            const basePrice = parseInt(selectBahan.options[selectBahan.selectedIndex].getAttribute('data-harga')) || 0;
            
            let totalQty = 0;
            let totalPrice = 0;

            // Hitung Lengan Standar
            document.querySelectorAll('.pdh-qty.std').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraBig = parseInt(input.getAttribute('data-extra')) || 0;
                totalQty += qty;
                totalPrice += qty * (basePrice + extraBig);
            });

            // Hitung Lengan Copotan (+12k)
            document.querySelectorAll('.pdh-qty.cpt').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const extraBig = parseInt(input.getAttribute('data-extra')) || 0;
                totalQty += qty;
                totalPrice += qty * (basePrice + extraBig + 12000);
            });

            document.getElementById('totalQty').innerText = totalQty;
            document.getElementById('totalHarga').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

            const btn = document.getElementById('btnSubmit');
            btn.disabled = !(totalQty >= 24 && basePrice > 0);
        }
        window.onload = calculateTotalPDH;
    </script>
</body>
</html>