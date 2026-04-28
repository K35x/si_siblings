<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - List Pesanan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body { background-color: #F8F3E9; min-height: 100vh; }
        .container { display: flex; min-height: 100vh; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 250px;
            background-image: linear-gradient(rgba(15, 6, 1, 0.9), rgba(74, 51, 40, 0.9)), url('header.jpeg'); 
            background-size: cover; background-position: center;
            color: white; flex-shrink: 0; padding-top: 30px;
        }

        .logo-container { padding: 0 25px; margin-bottom: 40px; }
        .logo-container h2 { font-style: italic; font-size: 28px; }

        nav { display: flex; flex-direction: column; gap: 15px; padding-left: 20px; }

        .nav-item {
            text-decoration: none; color: #4A3328; background: white;
            padding: 12px 20px; border-radius: 30px 0 0 30px;
            font-weight: bold; display: flex; align-items: center; gap: 12px;
            transition: all 0.3s ease; transform-origin: left;
        }

        .nav-item:hover {
            background-color: #79B473; color: white;
            transform: scale(1.08); padding-left: 30px;
        }

        .nav-item.active { background-color: #E6D5B8; color: #4A3328; }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .header-photo { width: 100%; height: 107px; background-image: url('header.jpeg'); background-size: cover; background-position: center; }
        .content-padding { padding: 30px 40px; }

        h1 { font-size: 32px; margin-bottom: 25px; color: #4A3328; }

        /* --- COMPACT ACTION BAR --- */
        .action-bar { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        /* Search Box */
        .search-wrapper { position: relative; width: 280px; }
        .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #A39382; }
        .search-wrapper input {
            width: 100%; padding: 10px 12px 10px 40px;
            border-radius: 25px; border: 1.5px solid #4A3328;
            font-size: 14px; outline: none;
        }

        /* Simple Date Filter */
        .date-filter-simple {
            display: flex;
            align-items: center;
            background: white;
            padding: 6px 15px;
            border-radius: 25px;
            border: 1.5px solid #4A3328;
            gap: 8px;
        }

        .date-filter-simple i { color: #4A3328; font-size: 14px; }
        .date-filter-simple input {
            border: none;
            outline: none;
            font-size: 13px;
            color: #4A3328;
            width: 115px;
            background: transparent;
        }

        .date-separator { color: #A39382; font-weight: bold; }

        /* Button Tambah (Pill Style) */
        .btn-tambah {
            background-color: #4A3328; color: white; padding: 10px 20px;
            border-radius: 25px; border: none; font-weight: bold;
            cursor: pointer; display: flex; align-items: center; gap: 8px;
            text-decoration: none; font-size: 13px;
            margin-left: auto; /* Push to right */
        }

        /* --- FILTER TABS --- */
        .filter-tabs { display: flex; gap: 8px; margin-bottom: 25px; }
        .tab {
            padding: 5px 15px; background: white; border: 1.5px solid #4A3328;
            border-radius: 20px; font-weight: bold; cursor: pointer; color: #4A3328; font-size: 12px;
        }
        .tab.active { background: #4A3328; color: white; }

        /* --- ORDER CARD --- */
        .order-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 20px;
        }

        .order-card {
            background: #FFF9F1; border-radius: 15px; padding: 18px;
            display: flex; gap: 15px; align-items: center;
            border: 1px solid rgba(74, 51, 40, 0.1);
        }

        .order-img { width: 100px; height: 100px; background: #E6D5B8; border-radius: 12px; object-fit: cover; }
        .order-info { flex: 1; }
        .order-info p { font-size: 13px; color: #4A3328; margin-bottom: 2px; }
        .price-tag { color: #D9534F; font-weight: bold; }

        .status-badge {
            display: inline-block; padding: 4px 15px; border-radius: 20px;
            font-size: 11px; font-weight: bold; margin-top: 5px;
        }
        .status-proses { background: #FFD966; color: #7F6000; }
    </style>
</head>
<body>

    <div class="container">
        <aside class="sidebar">
            <div class="logo-container"><h2>Siblings.co</h2></div>
            <nav>
                <a href="#" class="nav-item"><i class="fas fa-home"></i> Beranda</a>
                <a href="index.php" class="nav-item active"><i class="fas fa-shopping-basket"></i> Pesanan</a>
                <a href="#" class="nav-item"><i class="fas fa-box"></i> Stok Barang</a>
                <a href="#" class="nav-item"><i class="fas fa-money-bill"></i> Keuangan</a>
                <a href="#" class="nav-item"><i class="fas fa-tshirt"></i> Katalog produk</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header-photo"></div>

            <div class="content-padding">
                <h1>List Pesanan</h1>

                <div class="action-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari pesanan...">
                    </div>

                    <div class="date-filter-simple">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" title="Mulai">
                        <span class="date-separator">-</span>
                        <input type="date" title="Selesai">
                    </div>

                    <a href="input_pesanan.php" class="btn-tambah">
                        Tambah Pesanan <i class="fas fa-plus"></i>
                    </a>
                </div>

                <div class="filter-tabs">
                    <div class="tab active">Semua</div>
                    <div class="tab">Dikemas</div>
                    <div class="tab">Dikirim</div>
                    <div class="tab">Dibatalkan</div>
                </div>

                <div class="order-grid">
                    <div class="order-card">
                        <img src="https://via.placeholder.com/100" class="order-img" alt="Produk">
                        <div class="order-info">
                            <p>ID: <strong>#OR0001</strong></p>
                            <p>Tgl: 26/04/2026</p>
                            <p>Customer: <strong>Alin Alviani</strong></p>
                            <p>Total: <span class="price-tag">Rp. 400.000</span></p>
                            <div class="status-badge status-proses">Proses</div>
                        </div>
                    </div>
                    </div>
            </div>
        </main>
    </div>

</body>
</html>