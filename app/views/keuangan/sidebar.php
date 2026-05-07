<style>
    /* Gunakan font yang sama persis dengan input pesanan */
    .sidebar { 
        width: 250px; 
        background-image: linear-gradient(rgba(15, 6, 1, 0.9), rgba(74, 51, 40, 0.9)), url('header.jpeg'); 
        background-size: cover; 
        background-position: center; 
        color: white; 
        flex-shrink: 0; 
        padding-top: 30px; 
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .logo-container { padding: 0 25px; margin-bottom: 40px; }
    .logo-container h2 { 
        font-style: italic; 
        font-size: 28px; 
        font-weight: 800; /* Dibuat tebal agar font Segoe UI lebih tegas */
    }
    nav { display: flex; flex-direction: column; gap: 15px; padding-left: 20px; }
    .nav-item { 
        text-decoration: none; 
        color: #4A3328; 
        background: white; 
        padding: 12px 20px; 
        border-radius: 30px 0 0 30px; 
        font-weight: 800; /* Font weight disamakan dengan header h1 kamu */
        display: flex; 
        align-items: center; 
        gap: 12px; 
        transition: all 0.3s ease; 
        transform-origin: left; 
    }
    .nav-item:hover { 
        background-color: #79B473; 
        color: white; 
        transform: scale(1.08); 
        padding-left: 30px; 
        box-shadow: 5px 5px 15px rgba(0,0,0,0.2); 
    }
    .nav-item.active { 
        background-color: #E6D5B8; 
        color: #4A3328; 
    }
</style>

<aside class="sidebar">
    <div class="logo-container"><h2>Siblings.co</h2></div>
  <nav>
    <a href="beranda.php" class="nav-item"><i class="fas fa-home"></i> Beranda</a>
    <a href="index.php" class="nav-item"><i class="fas fa-shopping-basket"></i> Pesanan</a>
    <a href="product/index.php" class="nav-item"><i class="fas fa-box"></i> Stok Barang</a>
    <a href="keuangan.php" class="nav-item active"><i class="fas fa-money-bill"></i> Keuangan</a>
</nav>
</aside>