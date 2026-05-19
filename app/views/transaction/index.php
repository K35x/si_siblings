<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Management Pesanan</title>
    <!-- Icon & Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS Utama -->
    <link rel="stylesheet" href="<?= asset("css/transactions.css") ?>">
</head>
<body>

    <?php
    $escape = static fn($value): string => htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8",
    );
    $formatCurrency = static fn($amount): string => "Rp " .
        number_format((float) $amount, 0, ",", ".");
    $formatDate = static function ($value): string {
        if (empty($value)) {
            return "-";
        }

        try {
            $date = new DateTime((string) $value);
        } catch (Exception) {
            return "-";
        }
        $months = [
            1 => "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        return $date->format("d") .
            " " .
            $months[(int) $date->format("n")] .
            " " .
            $date->format("Y H:i");
    };
    $statusMap = [
        "selesai_pembayaran" => [
            "label" => "Selesai Pembayaran",
            "class" => "status-selesai",
            "group" => "selesai",
        ],
        "proses_pesanan" => [
            "label" => "Proses Pesanan",
            "class" => "status-proses",
            "group" => "proses",
        ],
        "selesai_proses" => [
            "label" => "Selesai Proses",
            "class" => "status-selesai",
            "group" => "selesai",
        ],
        "sudah_diambil_lunas" => [
            "label" => "Sudah Diambil & Lunas",
            "class" => "status-selesai",
            "group" => "selesai",
        ],
        "selesai_pembayaran_dp" => [
            "label" => "DP 50% Selesai",
            "class" => "status-proses",
            "group" => "proses",
        ],
        "dp_50_selesai" => [
            "label" => "DP 50% Selesai",
            "class" => "status-proses",
            "group" => "proses",
        ],
        "sedang_diproses" => [
            "label" => "Proses Pesanan",
            "class" => "status-proses",
            "group" => "proses",
        ],
        "siap_diambil" => [
            "label" => "Selesai Proses",
            "class" => "status-selesai",
            "group" => "selesai",
        ],
        "lunas_belum_diambil" => [
            "label" => "Selesai Pembayaran",
            "class" => "status-selesai",
            "group" => "selesai",
        ],
        "lunas_sudah_diambil" => [
            "label" => "Sudah Diambil & Lunas",
            "class" => "status-selesai",
            "group" => "selesai",
        ],
    ];
    $isOwner = ($sidebarRole ?? "kasir") === "owner";
    ?>

    <div class="container">
        <?php include __DIR__ . "/includes/sidebar.php"; ?>

        <main class="main-content">
            <?php include __DIR__ . "/includes/header.php"; ?>

            <div class="content-padding">
                <h1>Status Pesanan</h1>

                <!-- SECTION FILTER -->
                <div class="filter-wrapper">
                    <!-- Baris 1: Search & Tambah -->
                    <div class="filter-row-top">
                        <div class="search-wrapper" style="flex: 1;">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Cari status pelanggan atau ID pesanan...">
                        </div>

                        <?php if (!$isOwner): ?>
                            <a href="<?= url(
                                "/transactions/create",
                            ) ?>" class="btn-tambah" style="border-radius: 10px; padding: 12px 20px;">
                                Buat Pesanan Baru <i class="fas fa-plus"></i>
                            </a>
                        <?php endif; ?>
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
                         <div class="tab">Belum Diproses</div>
                        
                    </div>
                </div>

                <!-- SECTION GRID PESANAN -->
                <div class="order-grid">
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <?php $status = $statusMap[
                                $transaction["status_order"]
                            ] ?? [
                                "label" => "Status Tidak Diketahui",
                                "class" => "status-proses",
                                "group" => "proses",
                            ]; ?>
                            <div class="order-card" data-status-group="<?= $escape(
                                $status["group"],
                            ) ?>">
                                <div class="order-img-container">
                                    <img src="https://www.dummyimage.com/100x100/000/fff" class="order-img" alt="Pesanan <?= $escape(
                                        $transaction["order_code"],
                                    ) ?>">
                                </div>
                                <div class="order-info">
                                    <p>ID: <strong>#<?= $escape(
                                        $transaction["order_code"],
                                    ) ?></strong></p>
                                    <p>Nama Pelanggan: <strong><?= $escape(
                                        $transaction["nama"],
                                    ) ?></strong></p>
                                    <p>Tanggal Order: <strong><?= $escape(
                                        $formatDate(
                                            $transaction["tanggal_order"],
                                        ),
                                    ) ?></strong></p>
                                    <p>Jumlah: <strong><?= $escape(
                                        $transaction["total_qty"],
                                    ) ?> pcs</strong></p>
                                    <p>Total Harga: <span class="price-tag"><?= $escape(
                                        $formatCurrency(
                                            $transaction["grand_total"],
                                        ),
                                    ) ?></span></p>
                                    <div class="status-badge <?= $escape(
                                        $status["class"],
                                    ) ?>"><?= $escape($status["label"]) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="order-card" style="grid-column: 1 / -1; justify-content: center; text-align: center;">
                            <div class="order-info">
                                <p><strong>Belum ada pesanan</strong></p>
                                <p>Data order dari database belum tersedia.</p>
                            </div>
                        </div>
                    <?php endif; ?>

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
            const statusGroup = card.dataset.statusGroup || 'semua';

            if (filterValue === 'semua') {
                card.style.display = 'flex'; // Tampilkan semua
            } else if (statusGroup === filterValue) {
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
