<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siblings.co - Pilih Kategori</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styling khusus untuk menu kategori agar seimbang */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .category-card {
            background: white;
            border: 2px solid #E6D5B8;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .category-card:hover {
            border-color: #4A3328;
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(74, 51, 40, 0.1);
        }

        .category-icon {
            width: 80px;
            height: 80px;
            background-color: #F8F3E9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #4A3328;
            transition: 0.3s;
        }

        .category-card:hover .category-icon {
            background-color: #4A3328;
            color: white;
        }

        .category-card h3 {
            color: #4A3328;
            font-size: 20px;
            font-weight: 800;
        }

        .category-card p {
            color: #A39382;
            font-size: 14px;
            line-height: 1.5;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4A3328;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .btn-back:hover {
            color: #79B473;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Panggil Sidebar yang sama -->
        <?php include('includes/sidebar.php'); ?>

        <main class="main-content">
            <!-- Panggil Header yang sama -->
            <?php include('includes/header.php'); ?>

            <div class="content-padding">
                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke List Pesanan
                </a>
                
                <h1>Pilih Kategori Pesanan</h1>
                <p style="color: #4A3328; margin-bottom: 30px;">Silahkan pilih jenis pakaian untuk melanjutkan ke form input detail.</p>

                <div class="category-grid">
    <!-- Kategori T-Shirt -->
    <a href="form/input_tshirt.php" class="category-card">
        <div class="category-icon">
            <i class="fas fa-tshirt"></i>
        </div>
        <h3>T-Shirt / Kaos</h3>
        <p>Pesanan kaos oblong, raglan, lengan panjang, atau sablon custom.</p>
    </a>

    <!-- Kategori PDH -->
    <a href="form/input_pdh.php" class="category-card">
        <div class="category-icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <h3>PDH / Kemeja</h3>
        <p>Seragam organisasi, kemeja kerja, PDL, atau kemeja formal bordir.</p>
    </a>

    <!-- Kategori Jersey -->
    <a href="form/input_jersey.php" class="category-card">
        <div class="category-icon">
            <i class="fas fa-running"></i>
        </div>
        <h3>Jersey</h3>
        <p>Pakaian olahraga, futsal, basket, atau badminton dengan bahan dry-fit.</p>
    </a>

    <!-- Kategori Polo -->
    <a href="form/input_poloshirt.php" class="category-card">
        <div class="category-icon">
            <i class="fas fa-vest"></i>
        </div>
        <h3>Polo Shirt</h3>
        <p>Kaos berkerah dengan bahan lacoste atau pique untuk kesan semi-formal.</p>
    </a>

    <!-- Kategori Seragam Olahraga (NEW) -->
    <a href="form/input_seragamolahraga.php" class="category-card">
        <div class="category-icon">
            <i class="fas fa-swimmer"></i>
        </div>
        <h3>Seragam Olahraga</h3>
        <p>Setelan baju dan celana olahraga untuk sekolah, instansi, atau komunitas.</p>
    </a>

    <!-- Kategori Jacket & Hoodie (NEW) -->
    <a href="#" class="category-card">
        <div class="category-icon">
            <i class="fas fa-user-ninja"></i>
        </div>
        <h3>Jacket & Hoodie</h3>
        <p>Jaket bomber, coach jacket, jumper, atau zipper hoodie dengan bahan hangat.</p>
    </a>
</div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>