<?php
session_start();

// =========================
// HARGA PER PCS
// =========================
$harga_per_pcs = 60000;


// =========================
// ROUTE CATEGORY
// =========================
function getCategoryRoute($kategori)
{
    return match ($kategori) {

        'T-Shirt / Kaos'        => '/transactions/form/tshirt',
        'Jersey'         => '/transactions/form/jersey',
        'Polo Shirt'     => '/transactions/form/poloshirt',
        'Seragam Olahraga' => '/transactions/form/seragamolahraga',
        'PDH / Kemeja'   => '/transactions/form/pdh',
        'Jacket & Hoodie'         => '/transactions/form/jackethoodie',

        default          => '/transactions/categories',
    };
}


// =========================
// HAPUS ITEM
// =========================
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    if (isset($_SESSION['keranjang'][$id])) {
        unset($_SESSION['keranjang'][$id]);

        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    }

    header('Location: ' . url('/transactions/cart'));
    exit();
}


// =========================
// EDIT ITEM
// =========================
if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    if (isset($_SESSION['keranjang'][$id])) {

        $item = $_SESSION['keranjang'][$id];

        $_SESSION['edit_item'] = $item;
        $_SESSION['edit_index'] = $id;

        $redirect = getCategoryRoute($item['kategori']);

        header('Location: ' . url($redirect));
        exit();
    }
}


// =========================
// SIMPAN / UPDATE ITEM
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kategori = $_POST['kategori'] ?? '';

    // =========================================
    // KHUSUS PDH / KEMEJA
    // =========================================
    if ($kategori == 'PDH / Kemeja') {

        $s   = (int)($_POST['qty_std_S'] ?? 0) + (int)($_POST['qty_cpt_S'] ?? 0);

        $m   = (int)($_POST['qty_std_M'] ?? 0) + (int)($_POST['qty_cpt_M'] ?? 0);

        $l   = (int)($_POST['qty_std_L'] ?? 0) + (int)($_POST['qty_cpt_L'] ?? 0);

        $xl  = (int)($_POST['qty_std_XL'] ?? 0) + (int)($_POST['qty_cpt_XL'] ?? 0);

        $xxl = (int)($_POST['qty_std_XXL'] ?? 0) + (int)($_POST['qty_cpt_XXL'] ?? 0);

    } else {

        // =========================================
        // CATEGORY NORMAL
        // =========================================
        $s   = (int)($_POST['qty_short_S'] ?? 0) + (int)($_POST['qty_long_S'] ?? 0);

        $m   = (int)($_POST['qty_short_M'] ?? 0) + (int)($_POST['qty_long_M'] ?? 0);

        $l   = (int)($_POST['qty_short_L'] ?? 0) + (int)($_POST['qty_long_L'] ?? 0);

        $xl  = (int)($_POST['qty_short_XL'] ?? 0) + (int)($_POST['qty_long_XL'] ?? 0);

        $xxl = (int)($_POST['qty_short_XXL'] ?? 0) + (int)($_POST['qty_long_XXL'] ?? 0);
    }


    // =========================================
    // TOTAL
    // =========================================
    $total_qty = $s + $m + $l + $xl + $xxl;

    $total_harga = $total_qty * $harga_per_pcs;


    // =========================================
    // DATA ITEM
    // =========================================
    $item_baru = [

        'kategori' => $_POST['kategori'] ?? '-',

        'bahan'    => trim($_POST['jenis_bahan'] ?? '-'),

        'warna'    => trim($_POST['warna_kain'] ?? '-'),

        'sablon'   => trim($_POST['jenis_sablon'] ?? '-'),

        'rincian'  => [

            'S'   => $s,

            'M'   => $m,

            'L'   => $l,

            'XL'  => $xl,

            'XXL' => $xxl
        ],

        // =========================
        // KHUSUS PDH
        // =========================
        'rincian_std' => [

            'S'   => (int)($_POST['qty_std_S'] ?? 0),

            'M'   => (int)($_POST['qty_std_M'] ?? 0),

            'L'   => (int)($_POST['qty_std_L'] ?? 0),

            'XL'  => (int)($_POST['qty_std_XL'] ?? 0),

            'XXL' => (int)($_POST['qty_std_XXL'] ?? 0),
        ],

        'rincian_cpt' => [

            'S'   => (int)($_POST['qty_cpt_S'] ?? 0),

            'M'   => (int)($_POST['qty_cpt_M'] ?? 0),

            'L'   => (int)($_POST['qty_cpt_L'] ?? 0),

            'XL'  => (int)($_POST['qty_cpt_XL'] ?? 0),

            'XXL' => (int)($_POST['qty_cpt_XXL'] ?? 0),
        ],

        'qty'   => $total_qty,

        'harga' => $total_harga
    ];


    // =========================
    // UPDATE ITEM
    // =========================
    if (isset($_POST['index_edit']) && $_POST['index_edit'] !== "") {

        $idx = $_POST['index_edit'];
        $_SESSION['keranjang'][$idx] = $item_baru;

        unset($_SESSION['edit_item']);
        unset($_SESSION['edit_index']);

    } else {

        // =========================
        // TAMBAH ITEM BARU
        // =========================
        $_SESSION['keranjang'][] = $item_baru;
    }

    header('Location: ' . url('/transactions/cart'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Siblings.co - Keranjang</title>
    <link rel="stylesheet" href="<?= asset('css/transactions.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/transaction-cart.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <main class="main-content">
            <?php include __DIR__ . '/includes/header.php'; ?>
            
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
                                            <a href="<?= url('/transactions/cart?edit=' . $index) ?>" class="btn-icon edit"><i class="fas fa-edit"></i></a>
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
                        <a href="<?= url('/transactions/categories') ?>" class="btn-tambah">+ Tambah Kategori Lain</a>
                        <a href="<?= url('/transactions/invoice') ?>" class="btn-invoice">Proses Invoice <i class="fas fa-file-invoice"></i></a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>