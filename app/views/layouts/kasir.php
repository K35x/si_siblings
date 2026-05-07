<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siblings.co - Dashboard Kasir</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

/* RESET */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', sans-serif;
}

body {
  background-color: #F8F3E9;
}

/* LAYOUT  */
.container {
  display: flex;
  min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    background-image: linear-gradient(rgba(15, 6, 1, 0.9), rgba(74, 51, 40, 0.9)), url('/si_siblings/public/assets/img/background.jpeg'); 
    background-size: cover; background-position: center;
    color: white; flex-shrink: 0; padding-top: 30px;
}

.logo-container {
  padding: 0 25px;
  margin-bottom: 40px;
}

.logo-container h2 {
  font-size: 28px;
  font-style: italic;
}

nav {
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding-left: 20px;
}

.nav-item {
  display: flex;
  gap: 12px;
  padding: 12px 20px;
  border-radius: 30px 0 0 30px;
  background: white;
  color: #4A3328;
  font-weight: bold;
  text-decoration: none;
  transition: 0.3s;
}

.nav-item:hover {
  background-color: #79B473;
  color: white;
  transform: scale(1.05);
}

.nav-item.active {
  background-color: #E6D5B8;
}

/*  MAIN  */
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.header-photo {
  height: 110px;
  background: url('/si_siblings/public/assets/img/background.jpeg');
  width: 100%; height: 107px;
  background-size: cover; background-position: center;
}

.content {
  flex: 1;
  padding: 30px 50px;
  display: flex;
  flex-direction: column;
}

/*  CARD ATAS */
.cards {
  display: flex;
  gap: 25px;
  margin-bottom: 30px;
}

.card-custom {
  flex: 1;
  padding: 30px;
  text-align: center;
  background: white;
  border: 2px solid #4A3328;
  border-radius: 15px;
  transition: 0.3s;
}

.card-custom:hover {
  transform: scale(1.05);
}

.card-custom h2 {
  font-size: 32px;
}

.card-custom p {
  font-size: 16px;
}

/* GRID BAWAH */
.bottom {
  flex: 1;
  display: grid;
  grid-template-columns: 1fr 1.5fr 1.5fr;
  gap: 25px;
}

/*  BOX  */
.box {
  display: flex;
  flex-direction: column;
  height: 100%;
  padding: 20px;
  background: white;
  border: 2px solid #4A3328;
  border-radius: 15px;
}

/* TAMBAH PESANAN */
.add-box {
  justify-content: center;
  align-items: center;
  text-align: center;
  font-size: 18px;
}

.add-box i {
  font-size: 60px;
  margin-bottom: 10px;
  color: #999;
}

/* AKTIVITAS */
.activity h3 {
  margin-bottom: 15px;
}

.activity p {
  font-size: 15px;
  margin-bottom: 8px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ANTRIAN */
.big-box {
  justify-content: center;
  align-items: center;
  text-align: center;
  font-size: 20px;
  color: #555;
}

</style>
</head>

<body>

<div class="container">
        <aside class="sidebar">
            <div class="logo-container"><h2>Siblings.co</h2></div>
            <nav>
                <a href="kasir.php" class="nav-item"><i class="fas fa-home"></i> Beranda</a>
                <a href="si_siblings/app/views/transaction/index.php" class="nav-item"><i class="fas fa-shopping-basket"></i> Pesanan</a>
            </nav>
        </aside>

<!-- MAIN -->
<div class="main-content">

  <div class="header-photo"></div>

  <div class="content">

    <!-- CARD ATAS -->
    <div class="cards">

      <div class="card-custom">
        <h2 id="pesananBaru">12</h2>
        <p>Pesanan Baru</p>
      </div>

      <div class="card-custom">
        <h2 id="diproses">4</h2>
        <p>Sedang Diproses</p>
      </div>

      <div class="card-custom">
        <h2 id="siap">8</h2>
        <p>Siap Diambil</p>
      </div>

      <div class="card-custom">
        <h2 id="belumlunas">15</h2>
        <p>Belum Lunas</p>
      </div>

    </div>

    <!-- BAGIAN BAWAH -->
    <div class="bottom">

      <div class="box add-box">
        <i class="fas fa-plus"></i>
        Tambah Pesanan
      </div>

      <div class="box activity">
        <h3>Aktivitas Terakhir</h3>
        <p><strong>10.17</strong> Menambahkan pesanan</p>
        <p><strong>10.50</strong> Menerima pembayaran dari Ahmad</p>
        <p><strong>11.07</strong> Menambahkan pesanan</p>
        <p><strong>11.26</strong> Menerima pembayaran dari SMA 2</p>
        <p><strong>12.09</strong> Menambahkan pesanan</p>
      </div>

      <div class="box big-box">
        Antrian Invoice dan Pesanan
      </div>

    </div>

  </div>

</div>

</div>

</body>
</html>