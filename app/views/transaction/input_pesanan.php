<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Data Pelanggan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=1.3">
</head>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include('includes/sidebar.php'); ?>

        <main class="main-content">
            <!-- Header -->
            <?php include('includes/header.php'); ?>

            <div class="content-padding">
                <a href="index.php" class="btn-back" style="text-decoration: none; color: #4A3328; font-weight: bold; display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <h1 style="margin-bottom: 5px;">Tambah Pesanan Baru</h1>
                <p style="color: #888; margin-bottom: 25px;">Lengkapi biodata pelanggan terlebih dahulu.</p>
                
                <div class="form-container-full">
                    <form action="pilih_kategori.php" method="POST">
                        <div class="form-grid-modern">
                            <!-- Kolom Kiri -->
                            <div class="form-group-custom">
                                <label>Nama Customer</label>
                                <input type="text" name="nama_customer" placeholder="Masukkan nama pelanggan..." required>
                            </div>
                            

                            <!-- Kolom Kanan -->
                            <div class="form-group-custom">
                                <label>No. HP / WhatsApp</label>
                                <input type="tel"
                                 name="no_hp" 
                                 placeholder="Contoh: 081234567890" 
                                 pattern="[0-9]{10,13}"
                                 title="Nomor HP harus berupa angka dan berjumlah 10-13 digit"
                                 required>
                            </div>

                            <!-- Kolom Kiri -->
                            <div class="form-group-custom">
                                <label>Nama Project</label>
                                <input type="text" name="nama_project" placeholder="Contoh: Kaos Kelas 12 IPA" required>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="form-group-custom">
                                <label>Tanggal Pemesanan</label>
                                <input type="date" name="tgl_pemesanan" 
                                value="<?php echo date('Y-m-d'); ?>" 
                                min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="btn-footer">
                            <button type="submit" class="btn-lanjut">
                                PILIH KATEGORI <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>