<?php
$pageTitle = 'Tambah Pesanan Baru - Siblings.co';
$pageStyles = ['transactions.css'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<a href="#main-content" class="skip-to-content">Lewati ke konten utama</a>
<?php include __DIR__ . '/../partials/sidebar-toggle.php'; ?>

<div class="app-shell">
    <?php
$sidebarRole = $sidebarRole ?? 'kasir';
$activeMenu  = $activeMenu  ?? 'orders';
include __DIR__ . '/../layouts/sidebar.php';
?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <a href="<?= url('/transactions') ?>" class="btn-back">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                Kembali ke daftar pesanan
            </a>

            <h1>Tambah Pesanan Baru</h1>
            <p class="text-muted">Lengkapi biodata pelanggan terlebih dahulu.</p>

            <div class="form-container-full">
                <form action="<?= url('/transactions/categories') ?>" method="POST" novalidate>
                    <div class="sr-only" data-form-errors aria-live="polite"></div>
                    <div class="form-grid-modern">
                        <div class="form-field">
                            <label class="form-field__label" for="namaCustomer">Nama Customer</label>
                            <input id="namaCustomer" class="form-control" type="text" name="nama_customer"
                                   placeholder="Masukkan nama pelanggan"
                                   autocomplete="name"
                                   spellcheck="false"
                                   required aria-required="true">
                            <span class="form-field__error" data-error-for="nama_customer">Nama customer wajib diisi.</span>
                        </div>

                        <div class="form-field">
                            <label class="form-field__label" for="noHp">No.&nbsp;HP / WhatsApp</label>
                            <input id="noHp" class="form-control" type="tel" name="no_hp"
                                   placeholder="Contoh: 081234567890"
                                   pattern="[0-9]{10,13}"
                                   inputmode="numeric"
                                   autocomplete="tel"
                                   spellcheck="false"
                                   title="Nomor HP harus berupa angka dan berjumlah 10-13 digit"
                                   aria-describedby="noHpHint"
                                   required aria-required="true">
                            <span id="noHpHint" class="form-field__hint">Gunakan angka 10–13 digit, tanpa spasi atau tanda hubung.</span>
                            <span class="form-field__error" data-error-for="no_hp">Periksa format nomor HP (10–13 digit angka).</span>
                        </div>

                        <div class="form-field">
                            <label class="form-field__label" for="namaProject">Nama Project</label>
                            <input id="namaProject" class="form-control" type="text" name="nama_project"
                                   placeholder="Contoh: Kaos Kelas 12 IPA"
                                   autocomplete="off"
                                   required aria-required="true">
                            <span class="form-field__error" data-error-for="nama_project">Nama project wajib diisi.</span>
                        </div>

                        <div class="form-field">
                            <label class="form-field__label" for="tglPemesanan">Tanggal Pemesanan</label>
                            <input id="tglPemesanan" class="form-control tabular-nums" type="date" name="tgl_pemesanan"
                                   value="<?= date('Y-m-d') ?>"
                                   min="<?= date('Y-m-d') ?>"
                                   required aria-required="true">
                            <span class="form-field__error" data-error-for="tgl_pemesanan">Tanggal pemesanan wajib diisi.</span>
                        </div>
                    </div>

                    <div class="btn-footer">
                        <button type="submit" class="btn btn--primary btn--lg" data-loading-label="Memuat kategori…">
                            Pilih Kategori
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
</body>
</html>
