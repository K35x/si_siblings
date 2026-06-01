<?php
$pageTitle = 'Tambah Pesanan Baru - Siblings.co';
$pageStyles = ['transactions.css'];
$customerName = $_SESSION['customer_name'] ?? '';
$customerPhone = $_SESSION['customer_phone'] ?? '';
$projectName = $_SESSION['project_name'] ?? '';
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
$sidebarRole = $sidebarRole ?? Model::ROLE_KASIR;
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

            <?php $currentStep = 1; include __DIR__ . '/includes/step-indicator.php'; ?>

            <h1>Tambah Pesanan Baru</h1>
            <p class="text-muted">Lengkapi biodata pelanggan sebelum memilih kategori produk.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert--danger" role="alert"><?= e($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="form-container-full">
                <form action="<?= url('/transactions/categories') ?>" method="POST" novalidate>
                    <?= csrf_field() ?>
                    <div class="sr-only" data-form-errors aria-live="polite"></div>
                    <div class="form-grid-modern">
                        <div class="form-field">
                            <label class="form-field__label" for="namaCustomer">Nama Customer</label>
                            <input id="namaCustomer" class="form-control" type="text" name="customer_name"
                                   value="<?= e($customerName) ?>"
                                   placeholder="Nama lengkap atau nama komunitas"
                                   autocomplete="name"
                                   spellcheck="false"
                                   required aria-required="true">
                            <span class="form-field__error" data-error-for="customer_name">Nama customer wajib diisi.</span>
                        </div>

                        <div class="form-field">
                            <label class="form-field__label" for="noHp">No.&nbsp;HP / WhatsApp</label>
                            <input id="noHp" class="form-control" type="tel" name="phone_number"
                                   value="<?= e($customerPhone) ?>"
                                   placeholder="08xxxxxxxxxx"
                                   pattern="[0-9]{10,13}"
                                   inputmode="numeric"
                                   autocomplete="tel"
                                   spellcheck="false"
                                   title="Nomor HP harus berupa angka dan berjumlah 10-13 digit"
                                   required aria-required="true"
                                   aria-describedby="noHpHint">
                            <span id="noHpHint" class="form-field__hint">Wajib diisi. Gunakan angka 10–13 digit, tanpa spasi atau tanda hubung.</span>
                            <span class="form-field__error" data-error-for="phone_number">Periksa format nomor HP (10–13 digit angka).</span>
                        </div>

                        <div class="form-field">
                            <label class="form-field__label" for="namaProject">Nama Project</label>
                            <input id="namaProject" class="form-control" type="text" name="project_name"
                                   value="<?= e($projectName) ?>"
                                   placeholder="Kaos Angkatan 2026"
                                   autocomplete="off"
                                   required aria-required="true">
                            <span class="form-field__error" data-error-for="project_name">Nama project wajib diisi.</span>
                        </div>

                        <div class="form-field">
                            <label class="form-field__label" for="tglPemesanan">Tanggal Pemesanan</label>
                            <input id="tglPemesanan" class="form-control tabular-nums" type="date" name="order_date"
                                   value="<?= date('Y-m-d') ?>"
                                   min="<?= date('Y-m-d') ?>"
                                   required aria-required="true">
                            <span class="form-field__error" data-error-for="order_date">Tanggal pemesanan wajib diisi.</span>
                        </div>
                    </div>

                    <div class="btn-footer">
                        <button type="submit" class="btn btn--primary btn--lg" data-loading-label="Memuat kategori…">
                            Lanjut Pilih Kategori
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="<?= asset('js/ui.js') ?>"></script>
<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const customerFields = ['customer_name', 'phone_number', 'project_name']
        .map(name => document.querySelector(`[name="${name}"]`))
        .filter(Boolean);
    let saveTimer;

    customerFields.forEach(field => {
        field.addEventListener('input', () => {
            window.clearTimeout(saveTimer);
            saveTimer = window.setTimeout(saveCustomerData, 350);
        });
        field.addEventListener('change', saveCustomerData);
    });

    async function saveCustomerData() {
        if (customerFields.some(field => !field.value.trim())) return;
        try {
            const resp = await fetch('<?= url('/transactions/customer/save') ?>', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    customer_name: document.querySelector('[name="customer_name"]').value,
                    phone_number: document.querySelector('[name="phone_number"]').value,
                    project_name: document.querySelector('[name="project_name"]').value,
                }),
            });
            if (!resp.ok) {
                window.SiblingsUI?.toast?.('Gagal menyimpan data customer.', 'danger');
            }
        } catch (e) {
            window.SiblingsUI?.toast?.('Gagal menyimpan data customer.', 'danger');
        }
    }
})();
</script>
</body>
</html>
