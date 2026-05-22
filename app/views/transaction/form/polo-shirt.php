<?php
$pageTitle    = 'Konfigurasi Polo Shirt - Siblings.co';
$formHeading  = 'Konfigurasi Custom Polo Shirt';

$uploadHints = ['Ket: Logo Depan', 'Ket: Bordir Belakang', 'Ket: Lengan/Samping'];
$uploadTitle = 'Upload Desain (Bordir / Sablon)';

include __DIR__ . '/../includes/form-layout-start.php';
?>

<form action="<?= url('/transactions/cart') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="kategori" value="Polo Shirt">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'polo-shirt') ?>">
    <input type="hidden" name="index_edit" value="<?= e($editIndex ?? '') ?>">
    <div class="sr-only" data-form-errors aria-live="polite"></div>

    <div class="form-grid">
        <section class="form-card" aria-labelledby="poloSpec">
            <div class="form-card__header" id="poloSpec">
                <i class="fas fa-cut" aria-hidden="true"></i>
                Spesifikasi Produk
            </div>
            <div class="form-card__body">
                <div class="form-field">
                    <label class="form-field__label" for="paketBahan">Pilih Jenis Bahan Polo</label>
                    <select id="paketBahan" class="form-control" name="paket_bahan" required aria-required="true">
                        <option value="" disabled selected>— Pilih bahan —</option>
                        <option value="85000" data-label="Full Premium Cotton 24s">Full Premium Cotton 24s — Rp&nbsp;85.000</option>
                        <option value="85000" data-label="Lacos 24s">Lacos 24s — Rp&nbsp;85.000</option>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisBahan">Detail Jenis Bahan</label>
                    <input id="jenisBahan" class="form-control" type="text" name="jenis_bahan" readonly>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisAplikasi">Detail Sablon</label>
                    <select id="jenisAplikasi" class="form-control" name="jenis_aplikasi" required aria-required="true">
                        <option value="">Pilih bahan dahulu…</option>
                    </select>
                </div>

                <?php include __DIR__ . '/../includes/upload-section.php'; ?>
            </div>
        </section>

        <section class="form-card" aria-labelledby="poloSize">
            <div class="form-card__header" id="poloSize">
                <i class="fas fa-th-list" aria-hidden="true"></i>
                Rincian Ukuran &amp; Add-on
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
                    <p class="summary-helper" id="totalQtyHelper">Minimal pemesanan 24&nbsp;pcs untuk kategori ini.</p>
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
        const paketBahan = document.getElementById('paketBahan');
        const jenisBahan = document.getElementById('jenisBahan');
        const jenisAplikasi = document.getElementById('jenisAplikasi');

        const aplikasiByBahan = {
            'Full Premium Cotton 24s': ['Sablon Plastisol', 'Sablon DTF'],
            'Lacos 24s': ['Sablon DTF'],
        };

        function updateBahan() {
            const label = paketBahan.options[paketBahan.selectedIndex]?.getAttribute('data-label') || '';
            jenisBahan.value = label;
            jenisAplikasi.innerHTML = '';
            (aplikasiByBahan[label] || []).forEach((val) => {
                const o = document.createElement('option');
                o.value = val; o.textContent = val;
                jenisAplikasi.appendChild(o);
            });
        }

        function calc() {
            const base = parseInt(paketBahan.value, 10) || 0;
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

            const submit = document.getElementById('btnSubmit');
            const valid = qty >= 24 && base > 0;
            submit.disabled = !valid;
            submit.setAttribute('aria-disabled', String(!valid));
        }

        paketBahan.addEventListener('change', () => { updateBahan(); calc(); });
        document.querySelectorAll('.qty-input').forEach((i) => i.addEventListener('input', calc));
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
