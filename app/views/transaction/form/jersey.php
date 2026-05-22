<?php
$pageTitle    = 'Konfigurasi Jersey - Siblings.co';
$formHeading  = 'Konfigurasi Custom Jersey';

$uploadHints = ['Ket: Bordir Depan', 'Ket: Bordir Belakang', 'Ket: Bordir Samping'];
$uploadTitle = 'Upload Referensi Desain';

include __DIR__ . '/../includes/form-layout-start.php';
?>

<form action="<?= url('/transactions/cart') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="kategori" value="Jersey">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'jersey') ?>">
    <input type="hidden" name="index_edit" value="<?= e($editIndex ?? '') ?>">
    <div class="sr-only" data-form-errors aria-live="polite"></div>

    <div class="form-grid">
        <section class="form-card" aria-labelledby="jerseySpec">
            <div class="form-card__header" id="jerseySpec">
                <i class="fas fa-tshirt" aria-hidden="true"></i>
                Spesifikasi Produk
            </div>
            <div class="form-card__body">
                <div class="form-field">
                    <label class="form-field__label" for="paketBahan">Pilih Paket Jersey</label>
                    <select id="paketBahan" class="form-control" name="paket_bahan" required aria-required="true">
                        <option value="" disabled selected>— Pilih paket —</option>
                        <option value="110000">Print Depan (Polyflex) — Rp&nbsp;110.000</option>
                        <option value="120000">Full Print (Milano) — Rp&nbsp;120.000</option>
                        <option value="135000">Premium (Embose/Airwalk) — Rp&nbsp;135.000</option>
                        <option value="150000">Bahan Jaquard — Rp&nbsp;150.000</option>
                        <option value="150000">Full Print Baju &amp; Celana (Embose) — Rp&nbsp;150.000</option>
                        <option value="165000">Full Print Baju &amp; Celana (Jaquard) — Rp&nbsp;165.000</option>
                        <option value="95000">Baju Atasan Saja — Rp&nbsp;95.000</option>
                        <option value="100000">Jersey Non-Print (DTF/Polyflex) — Rp&nbsp;100.000</option>
                    </select>
                </div>

                <label class="option-toggle" for="plusLogo3D">
                    <input type="checkbox" id="plusLogo3D" name="add_logo3d" value="30000">
                    <span>Gunakan Logo 3D Rubber/Bordir <strong>(+30k/pcs)</strong></span>
                </label>

                <?php include __DIR__ . '/../includes/upload-section.php'; ?>
            </div>
        </section>

        <section class="form-card" aria-labelledby="jerseySize">
            <div class="form-card__header" id="jerseySize">
                <i class="fas fa-th-list" aria-hidden="true"></i>
                Rincian Ukuran &amp; Add-on
            </div>
            <div class="form-card__body">
                <?php include __DIR__ . '/../includes/size-table.php'; ?>

                <div class="addon-box" role="group" aria-labelledby="kaosKakiLabel">
                    <span class="addon-box__label" id="kaosKakiLabel">
                        <i class="fas fa-socks" aria-hidden="true"></i>
                        Tambahan Kaos Kaki <span class="text-muted">(+45k/psg)</span>
                    </span>
                    <label class="sr-only" for="qtyKaosKaki">Jumlah pasang kaos kaki</label>
                    <input id="qtyKaosKaki" type="number" name="qty_kaoskaki"
                           min="0" value="0"
                           inputmode="numeric"
                           class="tabular-nums">
                    <small>Jumlah kaos kaki bisa berbeda dengan jumlah baju.</small>
                </div>

                <div class="order-summary-box" role="region" aria-label="Ringkasan pesanan">
                    <div class="summary-row">
                        <span>Total Baju</span>
                        <span aria-live="polite"><strong id="totalQty">0</strong> pcs</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Kaos Kaki</span>
                        <span aria-live="polite"><strong id="valKausKaki">0</strong> psg</span>
                    </div>
                    <div class="summary-row summary-row--grand">
                        <span>Estimasi</span>
                        <span id="totalHarga" aria-live="polite">Rp&nbsp;0</span>
                    </div>
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
        const base = () => parseInt(document.getElementById('paketBahan').value, 10) || 0;
        const logo3D = () => document.getElementById('plusLogo3D').checked ? 30000 : 0;

        function calc() {
            const b = base();
            const logo = logo3D();
            const kaki = parseInt(document.getElementById('qtyKaosKaki').value, 10) || 0;
            let qty = 0, total = 0;

            document.querySelectorAll('.qty-input.short').forEach((i) => {
                const q = parseInt(i.value, 10) || 0;
                const extra = parseInt(i.dataset.xxl, 10) || 0;
                qty += q;
                total += q * (b + extra + logo);
            });
            document.querySelectorAll('.qty-input.long').forEach((i) => {
                const q = parseInt(i.value, 10) || 0;
                const extra = parseInt(i.dataset.xxl, 10) || 0;
                qty += q;
                total += q * (b + extra + 10000 + logo);
            });

            total += kaki * 45000;

            document.getElementById('totalQty').textContent = qty.toLocaleString('id-ID');
            document.getElementById('valKausKaki').textContent = kaki.toLocaleString('id-ID');
            document.getElementById('totalHarga').textContent =
                'Rp\u00a0' + total.toLocaleString('id-ID');

            const submit = document.getElementById('btnSubmit');
            const valid = (qty > 0 && b > 0) || kaki > 0;
            submit.disabled = !valid;
            submit.setAttribute('aria-disabled', String(!valid));
        }

        document.querySelectorAll('#paketBahan, #plusLogo3D, #qtyKaosKaki, .qty-input').forEach((el) => {
            el.addEventListener('input', calc);
            el.addEventListener('change', calc);
        });
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
