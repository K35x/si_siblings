<?php
session_start();

date_default_timezone_set('Asia/Jakarta');

$keranjang = $_SESSION['keranjang'] ?? [];

// =========================
// GENERATE INVOICE NUMBER
// =========================
$invoice_number = 'INV-' . date('Ymd') . '-' . rand(100,999);

// =========================
// CUSTOMER
// =========================
$customer_name  = $_SESSION['nama'] ?? '-';
$customer_phone = $_SESSION['customer_phone'] ?? '-';

// =========================
// GRAND TOTAL
// =========================
$grand_total = 0;

foreach ($keranjang as $item) {
    $grand_total += ($item['harga'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Siblings.co</title>

    <link rel="stylesheet" href="<?= asset('css/transactions.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>

        /* =========================
        INVOICE CARD
        ========================= */
        .invoice-card {
            background: white;
            border-radius: 15px;
            border: 1.5px solid #4A3328;
            overflow: hidden;
            max-width: 850px;
            margin: 0 auto;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }

        .invoice-header {
            background-color: #4A3328;
            color: white;
            padding: 30px 45px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-body {
            padding: 45px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 35px;
        }

        .info-label {
            font-size: 11px;
            color: #A39382;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        /* =========================
        TABLE
        ========================= */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #E6D5B8;
            color: #A39382;
            font-size: 13px;
        }

        .invoice-table td {
            padding: 15px;
            border-bottom: 1px solid #F8F3E9;
            color: #4A3328;
            font-size: 14px;
            vertical-align: top;
        }

        .row-summary td {
            border-bottom: none;
            padding: 8px 15px;
        }

        .input-dp-box {
            border: 1.5px solid #79B473;
            border-radius: 6px;
            padding: 5px 10px;
            width: 160px;
            font-weight: bold;
            color: #4A3328;
            text-align: right;
            outline: none;
        }

        .total-row {
            background-color: #fafafa;
            font-weight: bold;
            border-top: 2px solid #4A3328 !important;
        }

        .sisa-tagihan-text {
            color: #79B473;
            font-size: 32px;
            font-weight: 900;
        }

        .action-btns {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            max-width: 850px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-print {
            background: #4A3328;
            color: white;
            padding: 12px 35px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-print:hover {
            background: #2d1f18;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* =========================
        PRINT
        ========================= */
        @media print {

            .sidebar,
            .header-photo,
            .action-btns {
                display: none !important;
            }

            .container {
                display: block;
            }

            body {
                background: white;
            }

            .content-padding {
                padding: 0;
            }

            .invoice-card {
                border: 1px solid #4A3328;
                box-shadow: none;
                width: 100%;
                border-radius: 0;
            }

            .invoice-header {
                background-color: #4A3328 !important;
                -webkit-print-color-adjust: exact;
            }

            .input-dp-box {
                border: none;
                text-align: right;
                padding: 0;
            }

            @page {
                size: A4;
                margin: 15mm;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="main-content">

        <?php include __DIR__ . '/includes/header.php'; ?>

        <div class="content-padding">

            <div class="invoice-card">

                <!-- =========================
                HEADER
                ========================= -->
                <div class="invoice-header">

                    <div>
                        <h2 style="font-style: italic; letter-spacing: 1px;">
                            Siblings.co
                        </h2>

                        <p style="font-size: 11px; opacity: 0.8;">
                            Konveksi & Sablon Profesional
                        </p>
                    </div>

                    <div style="text-align: right;">

                        <h3 style="font-size: 20px; letter-spacing: 2px;">
                            INVOICE
                        </h3>

                        <p style="font-size: 12px; opacity: 0.8;">
                            #<?= $invoice_number; ?>
                        </p>

                    </div>

                </div>

                <!-- =========================
                BODY
                ========================= -->
                <div class="invoice-body">

                    <div class="info-grid">

                        <div class="info-group">

                            <p class="info-label">
                                Dipesan Oleh:
                            </p>

                            <p style="font-weight: bold; font-size: 16px;">
                                <?= $customer_name; ?>
                            </p>

                            <p style="color: #666; font-size: 13px;">
                                <?= $customer_phone; ?>
                            </p>

                        </div>

                        <div class="info-group" style="text-align: right;">

                            <p class="info-label"> Tanggal Pesanan:</p>
                            <p style="font-weight: bold;">
                                <?= date('d F Y'); ?>
                            </p>

                        </div>

                    </div>

                    <!-- =========================
                         TABLE
                    ========================= -->
                    <table class="invoice-table">

                        <thead>

                        <tr>
                            <th>Item Deskripsi</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Harga Satuan</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>

                        </thead>

                        <tbody>

                        <?php if(!empty($keranjang)): ?>

                            <?php foreach($keranjang as $item): ?>

                                <tr>

                                    <td>

                                        <strong>
                                            <?= $item['kategori']; ?>
                                        </strong>

                                        <br>

                                        <small>
                                            <?= $item['bahan']; ?>
                                            (<?= $item['warna']; ?>)
                                        </small>

                                        <br>

                                        <small>
                                            Sablon :
                                            <?= $item['sablon']; ?>
                                        </small>

                                    </td>

                                    <td style="text-align:center;">

                                        <?= $item['qty']; ?> pcs

                                    </td>

                                    <td style="text-align:right;">

                                        Rp <?= number_format(($item['harga'] / max($item['qty'],1)),0,',','.'); ?>

                                    </td>

                                    <td style="text-align:right;">

                                        Rp <?= number_format($item['harga'],0,',','.'); ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="4" style="text-align:center; padding:40px;">
                                    Keranjang kosong
                                </td>
                            </tr>

                        <?php endif; ?>


                        <!-- =========================
                             INPUT DP
                        ========================= -->
                        <tr class="row-summary" style="height: 60px;">

                            <td colspan="3"
                                style="text-align:right; vertical-align:middle; color:#A39382; font-weight:bold;">

                                NOMINAL DP:

                            </td>

                            <td style="text-align:right; vertical-align:middle;">

                                <input
                                        type="text"
                                        id="displayDP"
                                        class="input-dp-box"
                                        placeholder="0"
                                        oninput="formatRupiah(this)"
                                >

                            </td>

                        </tr>


                        <!-- =========================
                             TOTAL
                        ========================= -->
                        <tr class="total-row">

                            <td colspan="3"
                                style="text-align:right; padding:20px;">

                                TOTAL TAGIHAN

                            </td>

                            <td style="text-align:right; padding:20px;">

                                Rp <?= number_format($grand_total,0,',','.'); ?>

                            </td>

                        </tr>

                        </tbody>

                    </table>


                    <!-- =========================
                         SISA TAGIHAN
                    ========================= -->
                    <div style="text-align:right; margin-top:10px;">

                        <p style="font-size:12px; color:#A39382; font-weight:bold;">
                            SISA YANG HARUS DIBAYAR:
                        </p>

                        <h2 class="sisa-tagihan-text" id="sisaTagihan">

                            Rp <?= number_format($grand_total,0,',','.'); ?>

                        </h2>

                    </div>

                </div>

            </div>


            <!-- =========================
                 BUTTON
            ========================= -->
            <div class="action-btns">

                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Simpan PDF
                </button>

                <a href="<?= url('/transactions') ?>"
                   style="text-decoration:none; color:#4A3328; font-weight:bold; font-size:14px; align-self:center;">

                    Selesai

                </a>

            </div>

        </div>

    </main>

</div>


<!-- =========================
     JAVASCRIPT
========================= -->
<script>

    function formatRupiah(input)
    {
        let value = input.value.replace(/[^0-9]/g, '');

        let formatted = new Intl.NumberFormat('id-ID').format(value);

        if (value === "") {

            input.value = "";

            updateSisa(0);

        } else {

            input.value = formatted;

            updateSisa(parseInt(value));
        }
    }


    function updateSisa(dpValue)
    {
        const total = <?= $grand_total; ?>;

        const sisa = total - dpValue;

        document.getElementById('sisaTagihan').innerText =
            "Rp " + new Intl.NumberFormat('id-ID').format(sisa);
    }

</script>

</body>
</html>