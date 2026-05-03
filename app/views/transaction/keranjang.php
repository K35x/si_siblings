<?php
session_start();

// Simulasi harga per pcs
$harga_per_pcs = 60000; 

// 1. Logika Hapus Item (Taruh di paling atas sebelum HTML)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (isset($_SESSION['keranjang'][$id])) {
        unset($_SESSION['keranjang'][$id]);
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Reset nomor index
    }
    header("Location: keranjang.php");
    exit();
}

// 2. Logika Simpan / Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s   = (int)($_POST['qty_short_S'] ?? 0) + (int)($_POST['qty_long_S'] ?? 0);
    $m   = (int)($_POST['qty_short_M'] ?? 0) + (int)($_POST['qty_long_M'] ?? 0);
    $l   = (int)($_POST['qty_short_L'] ?? 0) + (int)($_POST['qty_long_L'] ?? 0);
    $xl  = (int)($_POST['qty_short_XL'] ?? 0) + (int)($_POST['qty_long_XL'] ?? 0);
    $xxl = (int)($_POST['qty_short_XXL'] ?? 0) + (int)($_POST['qty_long_XXL'] ?? 0);

    $total_qty = $s + $m + $l + $xl + $xxl;
    $total_harga = $total_qty * $harga_per_pcs;

    $item_baru = [
        'kategori' => 'T-Shirt',
        'bahan'    => $_POST['jenis_bahan'] ?? '-',
        'warna'    => $_POST['warna_kain'] ?? '-',
        'sablon'   => $_POST['jenis_sablon'] ?? '-',
        'rincian'  => ['S' => $s, 'M' => $m, 'L' => $l, 'XL' => $xl, 'XXL' => $xxl],
        'qty'      => $total_qty,
        'harga'    => $total_harga
    ];

    if (isset($_POST['index_edit']) && $_POST['index_edit'] !== "") {
        $idx = $_POST['index_edit'];
        $_SESSION['keranjang'][$idx] = $item_baru;
    } else {
        $_SESSION['keranjang'][] = $item_baru;
    }
    header("Location: keranjang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Siblings.co - Keranjang</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include('includes/sidebar.php'); ?>
        <main class="main-content">
            <?php include('includes/header.php'); ?>
            
            <div class="content-padding">
                <h1 class="main-title">Keranjang Pesanan</h1>
                <p class="subtitle">Berikut adalah daftar item yang akan dipesan.</p>

                <div class="cart-card">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Detail Spesifikasi</th>
                                <th>Rincian Size</th>
                                <th>Total Qty</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($_SESSION['keranjang'])): ?>
                                <?php foreach($_SESSION['keranjang'] as $index => $item): ?>
                                <tr>
                                    <td><strong><?php echo $item['kategori']; ?></strong></td>
                                    <td>
                                        <div class="spec-info">
                                            <span><strong>Bahan:</strong> <?php echo $item['bahan']; ?> (<?php echo $item['warna']; ?>)</span>
                                            <span><strong>Sablon:</strong> <?php echo $item['sablon']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="size-pills">
                                            <?php foreach($item['rincian'] as $sz => $jml): if($jml > 0): ?>
                                                <span><?php echo $sz; ?>: <?php echo $jml; ?></span>
                                            <?php endif; endforeach; ?>
                                        </div>
                                    </td>
                                    <td><span class="total-qty"><?php echo $item['qty']; ?> pcs</span></td>
                                    <td> <span class="price-text"> Rp <?php echo number_format($item['harga'] ?? 0, 0, ',', '.'); ?>
                                    </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="form/input_tshirt.php?edit=<?php echo $index; ?>" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                            <a href="?hapus=<?php echo $index; ?>" class="btn-icon delete" onclick="return confirm('Hapus item?')"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center; padding: 50px;">Keranjang kosong.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="cart-summary-section">
                        <div class="summary-box">
                            <div class="summary-row">
                                <span>Subtotal Barang:</span>
                                <span>Rp <?php 
                                $grand_total = 0;
                                if(!empty($_SESSION['keranjang'])) {
                                   foreach($_SESSION['keranjang'] as $item) {
                                     $grand_total += ($item['harga'] ?? 0);
                                }
                             }
                             echo number_format($grand_total, 0, ',', '.'); 
                        ?></span>
                        </div>
                        <div class="summary-row grand-total-row">
                            <span>Grand Total:</span>
                            <span class="final-price">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>

                    <div class="cart-actions-bottom">
                        <a href="pilih_kategori.php" class="btn-tambah">+ Tambah Kategori Lain</a>
                        <a href="invoice.php" class="btn-invoice">Proses Invoice <i class="fas fa-file-invoice"></i></a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>