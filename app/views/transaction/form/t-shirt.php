<?php
$pageTitle    = 'Konfigurasi T-Shirt - Siblings.co';
$formHeading  = 'Konfigurasi Custom T-Shirt';

include __DIR__ . '/../includes/form-layout-start.php';
?>

<form action="<?= url('/transactions/cart') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="kategori" value="T-Shirt / Kaos">
    <input type="hidden" name="index_edit" value="<?= e($editIndex ?? '') ?>">
    <div class="sr-only" data-form-errors aria-live="polite"></div>

    <div class="form-grid">
        <section class="form-card" aria-labelledby="cardSpecTitle">
            <div class="form-card__header" id="cardSpecTitle">
                <i class="fas fa-cut" aria-hidden="true"></i>
                Spesifikasi Produk
            </div>
            <div class="form-card__body">
                <div class="form-field">
                    <label class="form-field__label" for="paketBahan">Pilih Paket Harga (Bahan Kain)</label>
                    <select id="paketBahan" class="form-control" name="paket_bahan" required aria-required="true">
                        <option value="" disabled selected>— Pilih harga —</option>
                        <option value="55000" data-label="Semi Cotton">Rp&nbsp;55.000 — Semi Cotton</option>
                        <option value="55000" data-label="Polyester">Rp&nbsp;55.000 — Polyester</option>
                        <option value="60000" data-label="Cotton Carded">Rp&nbsp;60.000 — Cotton Carded</option>
                        <option value="62000" data-label="Cotton Combed 24s">Rp&nbsp;62.000 — Cotton Combed 24s</option>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisBahan">Detail Jenis Bahan</label>
                    <input id="jenisBahan" class="form-control" type="text" name="jenis_bahan" readonly>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisSablon">Detail Jenis Sablon</label>
                    <select id="jenisSablon" class="form-control" name="jenis_sablon" required aria-required="true">
                        <option value="">Pilih paket harga dulu…</option>
                    </select>
                </div>

                <?php include __DIR__ . '/../includes/upload-section.php'; ?>
            </div>
        </section>

        <section class="form-card" aria-labelledby="cardSizeTitle">
            <div class="form-card__header" id="cardSizeTitle">
                <i class="fas fa-table" aria-hidden="true"></i>
                Rincian Ukuran
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
        const jenisSablon = document.getElementById('jenisSablon');

        const sablonRules = {
            'Semi Cotton': ['Rubber'],
            'Polyester': ['Rubber'],
            'Cotton Carded': ['Plastisol'],
            'Cotton Combed 24s': ['Plastisol', 'Rubber', 'DTF'],
        };

        function updateBahan() {
            const opt = paketBahan.options[paketBahan.selectedIndex];
            const label = opt?.getAttribute('data-label') || '';
            jenisBahan.value = label;

            jenisSablon.innerHTML = '';
            (sablonRules[label] || []).forEach((val) => {
                const o = document.createElement('option');
                o.value = val; o.textContent = val;
                jenisSablon.appendChild(o);
            });
        }

        function calculateTotal() {
            const base = parseInt(paketBahan.value, 10) || 0;
            let qty = 0;
            let total = 0;

            document.querySelectorAll('.qty-input.short').forEach((input) => {
                const q = parseInt(input.value, 10) || 0;
                const extra = parseInt(input.dataset.xxl, 10) || 0;
                qty += q;
                total += q * (base + extra);
            });
            document.querySelectorAll('.qty-input.long').forEach((input) => {
                const q = parseInt(input.value, 10) || 0;
                const extra = parseInt(input.dataset.xxl, 10) || 0;
                qty += q;
                total += q * (base + extra + 5000);
            });

            document.getElementById('totalQty').textContent = qty.toLocaleString('id-ID');
            document.getElementById('totalHarga').textContent =
                'Rp\u00a0' + total.toLocaleString('id-ID');

            const submitBtn = document.getElementById('btnSubmit');
            const valid = qty >= 24 && base > 0;
            submitBtn.disabled = !valid;
            submitBtn.setAttribute('aria-disabled', String(!valid));
        }

        paketBahan.addEventListener('change', () => {
            updateBahan();
            calculateTotal();
        });
        document.querySelectorAll('.qty-input').forEach((i) => i.addEventListener('input', calculateTotal));
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
