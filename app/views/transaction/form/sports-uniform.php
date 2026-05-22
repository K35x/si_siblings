<?php
$pageTitle    = 'Konfigurasi Seragam Olahraga - Siblings.co';
$formHeading  = 'Konfigurasi Seragam Olahraga';

$uploadHints = ['Ket: Bordir Depan', 'Ket: Bordir Belakang', 'Ket: Bordir Samping'];
$uploadTitle = 'Upload Referensi Desain';

include __DIR__ . '/../includes/form-layout-start.php';
?>

<form action="<?= url('/transactions/cart') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="kategori" value="Seragam Olahraga">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'sports-uniform') ?>">
    <input type="hidden" name="index_edit" value="<?= e($editIndex ?? '') ?>">
    <input type="hidden" id="paketBahan" name="paket_bahan" value="95000">
    <div class="sr-only" data-form-errors aria-live="polite"></div>

    <div class="form-grid">
        <section class="form-card" aria-labelledby="sportsSpec">
            <div class="form-card__header" id="sportsSpec">
                <i class="fas fa-running" aria-hidden="true"></i>
                Spesifikasi Produk
            </div>
            <div class="form-card__body">
                <div class="form-field">
                    <label class="form-field__label" for="bahanBaju">Bahan Baju</label>
                    <select id="bahanBaju" class="form-control" name="jenis_bahan" required aria-required="true">
                        <option value="" disabled selected>— Pilih bahan —</option>
                        <option value="Semi Cotton">Semi Cotton</option>
                        <option value="TC">TC (Teteron Cotton)</option>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisSablon">Jenis Sablon</label>
                    <select id="jenisSablon" class="form-control" name="jenis_sablon" required aria-required="true">
                        <option value="" disabled selected>— Pilih sablon —</option>
                        <option value="Rubber">Sablon Rubber</option>
                        <option value="DTF">Sablon DTF</option>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="bahanTraining">Bahan Training (otomatis)</label>
                    <input id="bahanTraining" class="form-control" type="text" name="bahan_training" value="Bahan Diadora" readonly>
                </div>

                <?php include __DIR__ . '/../includes/upload-section.php'; ?>
            </div>
        </section>

        <section class="form-card" aria-labelledby="sportsSize">
            <div class="form-card__header" id="sportsSize">
                <i class="fas fa-list-ol" aria-hidden="true"></i>
                Rincian Ukuran
            </div>
            <div class="form-card__body">
                <?php include __DIR__ . '/../includes/size-table.php'; ?>

                <div class="order-summary-box" role="region" aria-label="Ringkasan pesanan">
                    <div class="summary-row">
                        <span>Total Pesanan</span>
                        <span aria-live="polite"><strong id="totalQty">0</strong> stel</span>
                    </div>
                    <div class="summary-row summary-row--grand">
                        <span>Estimasi</span>
                        <span id="totalHarga" aria-live="polite">Rp&nbsp;0</span>
                    </div>
                    <p class="summary-helper" id="totalQtyHelper" role="status" hidden>
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        Minimal pemesanan 24&nbsp;pcs.
                    </p>
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
        const base = 95000;

        function calc() {
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
                total += q * (base + extra + 5000);
            });

            document.getElementById('totalQty').textContent = qty.toLocaleString('id-ID');
            document.getElementById('totalHarga').textContent =
                'Rp\u00a0' + total.toLocaleString('id-ID');

            const warning = document.getElementById('totalQtyHelper');
            const btn = document.getElementById('btnSubmit');
            const valid = qty >= 24;
            btn.disabled = !valid;
            btn.setAttribute('aria-disabled', String(!valid));
            if (valid) {
                warning.hidden = true;
            } else {
                warning.hidden = qty === 0;
            }
        }

        document.querySelectorAll('.qty-input').forEach((i) => i.addEventListener('input', calc));
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
