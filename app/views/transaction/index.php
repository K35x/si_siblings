<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Management Pesanan</title>
    <!-- Icon & Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS Utama -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <?php include('includes/sidebar.php'); ?>

        <main class="main-content">
            <?php include('includes/header.php'); ?>

            <div class="content-padding">
                <h1>List Pesanan</h1>

                <!-- SECTION FILTER -->
                <div class="filter-wrapper">
                    <!-- Baris 1: Search & Tambah -->
                    <div class="filter-row-top">
                        <div class="search-wrapper" style="flex: 1;">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Cari Nama Pelanggan atau ID Pesanan...">
                        </div>
                        
                        <a href="input_pesanan.php" class="btn-tambah" style="border-radius: 10px; padding: 12px 20px;">
                            Tambah Pesanan Baru <i class="fas fa-plus"></i>
                        </a>
                    </div>

                    <!-- Baris 2: Tanggal & Kategori -->
                    <div class="filter-row-bottom">
                        <div class="date-filter-simple">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" title="Mulai Tanggal">
                            <span class="date-separator">-</span>
                            <input type="date" title="Sampai Tanggal">
                        </div>

                        <select class="category-filter">
                            <option value="">Semua Kategori</option>
                            <option value="tshirt">T-Shirt</option>
                            <option value="pdh">PDH / Kemeja</option>
                            <option value="jersey">Jersey</option>
                            <option value="polo">Polo Shirt</option>
                        </select>
                    </div>

                    <!-- Baris 3: Tabs Status -->
                    <div class="filter-tabs">
                        <div class="tab active">Semua</div>
                        <div class="tab">Proses</div>
                        <div class="tab">Selesai</div>
                    </div>
                </div>

                <!-- SECTION GRID PESANAN -->
                <div class="order-grid">
                    
                    <!-- Card 1: Contoh T-Shirt (Proses) -->
                    <div class="order-card">
                        <div class="order-img-container">
                            <img src="https://via.placeholder.com/100" class="order-img" alt="T-Shirt">
                        </div>
                        <div class="order-info">
                            <p>ID: <strong>#ORD001</strong></p>
                            <p>Nama Pelanggan: <strong>Alin Aktaviani</strong></p>
                            <p>Jumlah: <strong>4pcs</strong></p>
                            <p>Total Harga: <span class="price-tag">Rp. 400.000</span></p>
                            <div class="status-badge status-proses">Proses</div>
                        </div>
                    </div>

                    <!-- Card 2: Contoh PDH (Selesai) -->
                    <div class="order-card">
                        <div class="order-img-container">
                            <img src="https://via.placeholder.com/100" class="order-img" alt="PDH">
                        </div>
                        <div class="order-info">
                            <p>ID: <strong>#ORD002</strong></p>
                            <p>Nama Pelanggan: <strong>Kalyca Ashila</strong></p>
                            <p>Jumlah: <strong>5pcs</strong></p>
                            <p>Total Harga: <span class="price-tag">Rp. 750.000</span></p>
                            <div class="status-badge status-selesai">Selesai</div>
                        </div>
                    </div>

                    <!-- Card 3: Contoh Jersey (Proses) -->
                    <div class="order-card">
                        <div class="order-img-container">
                            <img src="https://via.placeholder.com/100" class="order-img" alt="Jersey">
                        </div>
                        <div class="order-info">
                            <p>ID: <strong>#ORD003</strong></p>
                            <p>Nama Pelanggan: <strong>Enami Asa</strong></p>
                            <p>Jumlah: <strong>5pcs</strong></p>
                            <p>Total Harga: <span class="price-tag">Rp. 625.000</span></p>
                            <div class="status-badge status-proses">Proses</div>
                        </div>
                    </div>

                </div> <!-- End Order Grid -->
            </div> <!-- End Content Padding -->
        </main>
    </div>
    <script>
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // 1. Ubah tampilan tombol yang aktif
        document.querySelector('.tab.active').classList.remove('active');
        this.classList.add('active');

        const filterValue = this.textContent.toLowerCase().trim();
        const cards = document.querySelectorAll('.order-card');

        // 2. Filter kartu pesanan
        cards.forEach(card => {
            const statusBadge = card.querySelector('.status-badge').textContent.toLowerCase().trim();
            
            if (filterValue === 'semua') {
                card.style.display = 'flex'; // Tampilkan semua
            } else if (statusBadge === filterValue) {
                card.style.display = 'flex'; // Tampilkan yang cocok
            } else {
                card.style.display = 'none'; // Sembunyikan yang beda
            }
        });
    });
});
</script>

</body>
</html>