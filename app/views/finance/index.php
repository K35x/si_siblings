<?php
$paymentStates = $paymentStates ?? [];
$financialErrors = $financialErrors ?? [];
$financialNotice = $financialNotice ?? null;
$oldInput = $oldInput ?? [];
$totalSales = 0.0;
$totalQty = 0;
$totalTransactions = 0;

foreach ($paymentStates as $row) {
    $totalSales += (float) ($row['grand_total'] ?? 0);
    $totalQty += (int) ($row['total_qty'] ?? 0);
    $totalTransactions++;
}

$statusLabels = [
    'unpaid' => 'Belum Bayar',
    'partial' => 'Sebagian',
    'paid' => 'Lunas',
    'overpaid' => 'Lebih Bayar',
];
$statusClasses = [
    'unpaid' => 'status-pending',
    'partial' => 'status-partial',
    'paid' => 'status-selesai',
    'overpaid' => 'status-overpaid',
];
$statusIcons = [
    'unpaid' => 'fas fa-clock',
    'partial' => 'fas fa-hourglass-half',
    'paid' => 'fas fa-check',
    'overpaid' => 'fas fa-exclamation-triangle',
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Siblings.co</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/finance.css') ?>">
    <style>
        .payment-status-pill { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-weight:700; font-size:.85rem; }
        .status-partial { color:#9a5b00; background:#fff4d6; }
        .status-overpaid { color:#8a1c1c; background:#ffe0e0; }
        .status-pending { color:#6d4c41; background:#fff8e1; }
        .status-selesai { color:#1b5e20; background:#e8f5e9; }
        .amount-muted { color:#777; font-size:.85rem; margin-top:4px; }
        .amount-danger { color:#b71c1c; font-weight:700; }
    </style>
</head>
<body>
<div class="container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/header.php'; ?>
        <div class="content-padding">
            <h1 class="finance-title">Laporan Penjualan</h1>

            <?php if (!empty($financialErrors)): ?>
                <div class="alert alert-danger" style="background:#fdecea;color:#b71c1c;border:1px solid #f5c2c7;border-radius:6px;padding:12px;margin:12px 0;">
                    <strong>Aksi keuangan gagal.</strong>
                    <ul style="margin:8px 0 0 18px;">
                        <?php foreach ($financialErrors as $message): ?>
                            <li><?= e($message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($financialNotice)): ?>
                <div class="alert alert-success" style="background:#e8f5e9;color:#1b5e20;border:1px solid #a5d6a7;border-radius:6px;padding:12px;margin:12px 0;">
                    <?= e($financialNotice) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($oldInput) && !empty($financialErrors)): ?>
                <div class="alert alert-warning" style="background:#fff8e1;color:#6d4c41;border:1px solid #ffe082;border-radius:6px;padding:12px;margin:12px 0;">
                    Data input lama masih tersedia untuk diperbaiki.
                </div>
            <?php endif; ?>

            <div class="stats-grid sales-stats-grid">
                <div class="stat-card sales-stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="stat-info">
                        <p>Total Penjualan</p>
                        <h3><?= e(format_currency($totalSales)) ?></h3>
                    </div>
                </div>
                <div class="stat-card sales-stat-card highlight">
                    <i class="fas fa-box"></i>
                    <div class="stat-info">
                        <p>Total Produk Terjual</p>
                        <h3><?= (int) $totalQty ?> pcs</h3>
                    </div>
                </div>
                <div class="stat-card sales-stat-card">
                    <i class="fas fa-receipt"></i>
                    <div class="stat-info">
                        <p>Jumlah Transaksi</p>
                        <h3><?= (int) $totalTransactions ?> Pesanan</h3>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <label><i class="fas fa-filter"></i> Filter:</label>
                <select>
                    <option>Semua Bulan</option>
                    <option>Januari 2026</option>
                    <option>Februari 2026</option>
                    <option>Maret 2026</option>
                </select>
                <input type="date" value="2026-01-01">
                <input type="date" value="2026-12-31">
                <button class="btn-filter">Terapkan</button>
            </div>

            <div class="finance-toolbar">
                <h2>Detail Transaksi Penjualan</h2>
                <div class="finance-tools">
                    <div class="finance-search">
                        <input type="text" placeholder="Cari pesanan...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor Pesanan</th>
                            <th>Customer</th>
                            <th>Jumlah</th>
                            <th>Total Penjualan</th>
                            <th>Total Dibayar</th>
                            <th>Sisa / Lebih Bayar</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($paymentStates)): ?>
                            <?php foreach ($paymentStates as $index => $row): ?>
                                <?php
                                $state = (string) ($row['payment_state'] ?? 'unpaid');
                                $remaining = (float) ($row['remaining_balance'] ?? 0);
                                $balanceText = $state === 'overpaid'
                                    ? 'Lebih ' . format_currency(abs($remaining))
                                    : format_currency(max(0, $remaining));
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="date-pill"><?= e(format_date_id($row['tanggal_order'] ?? null)) ?></span></td>
                                    <td><?= e($row['order_code']) ?></td>
                                    <td><?= e($row['customer_name']) ?></td>
                                    <td><?= (int) ($row['total_qty'] ?? 0) ?> pcs</td>
                                    <td><?= e(format_currency($row['grand_total'])) ?></td>
                                    <td><?= e(format_currency($row['total_paid'])) ?></td>
                                    <td class="<?= $state === 'overpaid' ? 'amount-danger' : '' ?>"><?= e($balanceText) ?></td>
                                    <td>
                                        <span class="payment-status-pill <?= e($statusClasses[$state] ?? 'status-pending') ?>">
                                            <?= e($statusLabels[$state] ?? ucfirst($state)) ?>
                                            <i class="<?= e($statusIcons[$state] ?? 'fas fa-clock') ?>"></i>
                                        </span>
                                        <div class="amount-muted">status: <?= e($state) ?></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align:center; padding: 50px;">Belum ada data transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
