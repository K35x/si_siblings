<?php
$pageTitle    = 'Konfigurasi PDH / Kemeja - Siblings.co';
$formHeading  = 'Konfigurasi Custom PDH / Kemeja';

$sizeShortLabel = 'LENGAN PENDEK';
$sizeLongLabel  = 'LENGAN PANJANG (+10K)';
$uploadHints = ['Ket: Bordir Depan', 'Ket: Bordir Belakang', 'Ket: Bordir Samping'];
$uploadTitle = 'Upload Referensi Desain';

include __DIR__ . '/../includes/form-layout-start.php';
?>

<form action="<?= url('/transactions/cart') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="kategori" value="PDH / Kemeja">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'work-uniform') ?>">
    <input type="hidden" name="index_edit" value="<?= e($editIndex ?? '') ?>">
    <div class="sr-only" data-form-errors aria-live="polite"></div>

    <div class="form-grid">
        <section class="form-card" aria-labelledby="pdhSpec">
            <div class="form-card__header" id="pdhSpec">
                <i class="fas fa-cut" aria-hidden="true"></i>
                Spesifikasi Produk
            </div>
            <div class="form-card__body">
                <div class="form-field">
                    <label class="form-field__label" for="bahanKain">Pilih Jenis Bahan Kain</label>
                    <select id="bahanKain" class="form-control" name="jenis_bahan" required aria-required="true">
                        <option value="" disabled selected>— Pilih bahan —</option>
                        <option value="Unione" data-harga="130000">Unione — Rp&nbsp;130.000</option>
                        <option value="American Drill" data-harga="135000">American Drill — Rp&nbsp;135.000</option>
                        <option value="Nagata Drill" data-harga="140000">Nagata Drill — Rp&nbsp;140.000</option>
                    </select>
                </div>

                <?php include __DIR__ . '/../includes/upload-section.php'; ?>
            </div>
        </section>

        <section class="form-card" aria-labelledby="pdhSize">
            <div class="form-card__header" id="pdhSize">
                <i class="fas fa-table" aria-hidden="true"></i>
                Rincian Ukuran &amp; Lengan
            </div>
            <div class="form-card__body">
                <?php include __DIR__ . '/../includes/size-table.php'; ?>

                <div class="order-summary-box" role="region" aria-label="Ringkasan pesanan">
                    <div class="summary-row">
                        <span>Total Qty</span>
                        <span aria-live="polite"><strong id="totalQty">0</strong> / 24&nbsp;pcs</span>
                    </div>
                    <div class="summary-row summary-row--grand">
                        <span>Estimasi</span>
                        <span id="totalHarga" aria-live="polite">Rp&nbsp;0</span>
                    </div>
                    <p class="summary-helper" id="totalQtyHelper">Minimal pemesanan 24&nbsp;pcs.</p>
                    <button type="submit" id="btnSubmit" class="btn btn--accent btn-submit-order"
                            data-loading-label="Menambahkan…" disabled>
                        Tambahkan ke Keranjang
                        <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </section>
    </div>
</form>

<script>
    (function () {
        const select = document.getElementById('bahanKain');

        function getBase() {
            const opt = select.options[select.selectedIndex];
            return parseInt(opt?.getAttribute('data-harga'), 10) || 0;
        }

        function calc() {
            const base = getBase();
            let qty = 0, total = 0;

            document.querySelectorAll('.qty-input.short').forEach((i) => {
                const q = parseInt(i.value, 10) || 0;
                const extra = parseInt(i.dataset.xxl, 10) || 0;
                qty += q;
                total += q * (base + extra);
            });
            document.querySelectorAll('.qty-input.long').forEach((i) => {
                const q = parseInt(i.value, 10) || 0;
                const extra = parseInt(i.dataset.xxl, 10) || 0;
                qty += q;
                total += q * (base + extra + 12000);
            });

            document.getElementById('totalQty').textContent = qty.toLocaleString('id-ID');
            document.getElementById('totalHarga').textContent =
                'Rp\u00a0' + total.toLocaleString('id-ID');

            const submit = document.getElementById('btnSubmit');
            const valid = qty >= 24 && base > 0;
            submit.disabled = !valid;
            submit.setAttribute('aria-disabled', String(!valid));
        }

        select.addEventListener('change', calc);
        document.querySelectorAll('.qty-input').forEach((i) => i.addEventListener('input', calc));
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
