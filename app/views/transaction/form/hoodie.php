<?php
$pageTitle    = 'Konfigurasi Hoodie - Siblings.co';
$formHeading  = 'Konfigurasi Custom Hoodie';

$variants = $variants ?? [];
$sablonTypes = $sablonTypes ?? [];
$variantOptions = [];
$variantIdMap = [];
foreach ($variants as $v) {
    $harga = (int) $v['price'];
    $label = $v['variant_name'] ?: $v['material'];
    $variantOptions[$label] = $harga;
    $variantIdMap[$label] = (int) $v['variant_id'];
}

$editItem = $editItem ?? [];
$isEdit = !empty($editItem);
$editBahan = $editItem['material'] ?? '';
$editVariantId = (int) ($editItem['variant_id'] ?? 0);
$editSablon = $editItem['sablon_type_id'] ?? '';
if ($editSablon === '' && !empty($editItem['sablon'])) {
    foreach ($sablonTypes as $st) {
        if (($st['sablon_name'] ?? '') === $editItem['sablon']) {
            $editSablon = $st['sablon_type_id'];
            break;
        }
    }
}
$editSablonPrice = $editItem['sablon_price'] ?? 0;
$editRincian = $editItem['rincian'] ?? [];
$editQuantityShort = $editItem['quantity_short'] ?? [];
$editQuantityLong = $editItem['quantity_long'] ?? [];
$editWarnaPerSize = $editItem['warna_per_size'] ?? ['short' => [], 'long' => []];
$editCustomColors = $editItem['custom_colors'] ?? [];
$longSleeveSurcharge = (float) ($variants[0]['sleeve_price'] ?? 0);

$sizeSurchargesForForm = [];
$selectedVariantId = $editVariantId ?: ($variants[0]['variant_id'] ?? null);
if ($selectedVariantId && !empty($sizeSurcharges[$selectedVariantId])) {
    $sizeSurchargesForForm = $sizeSurcharges[$selectedVariantId];
}

$uploadHints = ['Ket: Logo Depan', 'Ket: Bordir Belakang', 'Ket: Lengan/Samping'];
$uploadTitle = 'Upload Desain (Bordir / Sablon)';

include __DIR__ . '/../includes/form-layout-start.php';

$hasOldInput = !empty($oldInput ?? []);
if ($hasOldInput) {
    $isEdit = true;
    $editVariantId = (int) ($oldInput['variant_id'] ?? $editVariantId ?? 0);
    $editBahan = $oldInput['jenis_bahan'] ?? $editBahan ?? '';
    $editSablon = $oldInput['sablon_type_id'] ?? $editSablon ?? '';
    $editSablonPrice = $oldInput['sablon_price'] ?? $editSablonPrice ?? 0;
    $editCatatan = $oldInput['order_notes'] ?? $editCatatan ?? '';
}
?>

<form action="<?= url('/transactions/cart') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="kategori" value="Hoodie">
    <input type="hidden" id="variantId" name="variant_id" value="<?= e($editItem['variant_id'] ?? '') ?>">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'hoodie') ?>">
    <input type="hidden" name="sleeve_price" value="<?= $longSleeveSurcharge ?>">
    <input type="hidden" name="index_edit" value="<?= e($editIndex ?? '') ?>">
    <div class="sr-only" data-form-errors aria-live="polite"></div>

    <div class="form-grid">
        <section class="form-card" aria-labelledby="hoodieSpec">
            <div class="form-card__header" id="hoodieSpec">
                <i class="fas fa-tshirt" aria-hidden="true"></i>
                Spesifikasi Produk
            </div>
            <div class="form-card__body">
                <div class="form-field">
                    <label class="form-field__label" for="paketBahan">Pilih Jenis Bahan Hoodie</label>
                    <select id="paketBahan" class="form-control" name="paket_bahan" required aria-required="true">
                        <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>— Pilih bahan —</option>
                        <?php if (empty($variantOptions)): ?>
                            <option value="" disabled>Belum ada varian produk. Tambahkan di Master Data.</option>
                        <?php else: ?>
                            <?php foreach ($variantOptions as $label => $harga): ?>
                                <option value="<?= e($harga) ?>" data-label="<?= e($label) ?>"
                                    data-variant-id="<?= e($variantIdMap[$label] ?? '') ?>"
                                    <?= $isEdit && (($editVariantId > 0 && $editVariantId === (int) ($variantIdMap[$label] ?? 0)) || ($editVariantId === 0 && $editBahan === $label)) ? 'selected' : '' ?>>
                                    <?= e($label) ?> — Rp <?= e(number_format($harga, 0, ',', '.')) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisBahan">Detail Jenis Bahan</label>
                    <input id="jenisBahan" class="form-control" type="text" name="jenis_bahan" readonly>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisSablon">Jenis Sablon / Bordir</label>
                    <select id="jenisSablon" class="form-control" name="sablon_type_id">
                        <option value="">— Polosan (Tanpa Sablon) —</option>
                        <?php foreach ($sablonTypes as $st): ?>
                            <option value="<?= e($st['sablon_type_id']) ?>"
                                <?= $isEdit && $editSablon == $st['sablon_type_id'] ? 'selected' : '' ?>>
                                <?= e($st['sablon_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-field__label" for="sablonPrice">Harga Sablon / Bordir (Rp)</label>
                    <input type="number" name="sablon_price" id="sablonPrice" class="form-control"
                        value="<?= e($editSablonPrice) ?>"
                        min="0" step="1000" placeholder="0">
                </div>

                <?php include __DIR__ . '/../includes/upload-section.php'; ?>
            </div>
        </section>

        <section class="form-card" aria-labelledby="hoodieSize">
            <div class="form-card__header" id="hoodieSize">
                <i class="fas fa-th-list" aria-hidden="true"></i>
                Rincian Ukuran &amp; Warna
            </div>
            <div class="form-card__body">
                <?php
                $editRincian = $editRincian;
                $editQuantityShort = $editQuantityShort;
                $editQuantityLong = $editQuantityLong;
                $editWarnaPerSize = $editWarnaPerSize;
                $editCustomColors = $editCustomColors;
                $sizeSurcharges = $sizeSurchargesForForm;
                include __DIR__ . '/../includes/size-table.php';
                ?>

                <div class="order-summary-box" role="region" aria-label="Ringkasan pesanan">
                    <div class="summary-row">
                        <span>Total Qty</span>
                        <span aria-live="polite"><strong id="totalQty">0</strong>&nbsp;pcs</span>
                    </div>
                    <div class="summary-row summary-row--grand">
                        <span>Estimasi</span>
                        <span id="totalHarga" aria-live="polite">Rp&nbsp;0</span>
                    </div>
                    <p class="summary-helper" id="totalQtyHelper">Minimal pemesanan <?= e($minimumOrder) ?>&nbsp;pcs untuk kategori ini.</p>
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

<script src="<?= asset('js/transaction-calc.js') ?>"></script>
<script src="<?= asset('js/transaction-form-validation.js') ?>"></script>
<script>
    (function () {
        const paketBahan = document.getElementById('paketBahan');
        const jenisBahan = document.getElementById('jenisBahan');
        const jenisSablon = document.getElementById('jenisSablon');
        const sablonPriceInput = document.getElementById('sablonPrice');
        const variantIdInput = document.getElementById('variantId');

        function updateBahan() {
            const opt = paketBahan.options[paketBahan.selectedIndex];
            jenisBahan.value = opt?.getAttribute('data-label') || '';
            if (variantIdInput) variantIdInput.value = opt?.getAttribute('data-variant-id') || '';
        }

        if (jenisSablon && sablonPriceInput) {
            jenisSablon.addEventListener('change', function() {
                if (!jenisSablon.value) {
                    sablonPriceInput.value = 0;
                }
                if (typeof calc === 'function') calc();
            });
        }

        const calc = TransactionCalc.init({
            basePrice: () => (parseInt(paketBahan.value, 10) || 0) + (parseInt(sablonPriceInput?.value, 10) || 0),
            longSleeveSurcharge: <?= $longSleeveSurcharge ?>,
            minQty: <?= e($minimumOrder) ?>,
        });

        paketBahan.addEventListener('change', () => { updateBahan(); calc(); });
        if (sablonPriceInput) sablonPriceInput.addEventListener('input', calc);
        document.querySelectorAll('.qty-input').forEach(el => el.addEventListener('input', calc));
        updateBahan();
        calc();
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
