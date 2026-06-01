<?php
$pageTitle    = 'Konfigurasi PDH / Kemeja - Siblings.co';
$formHeading  = 'Konfigurasi Custom PDH / Kemeja';

$variants = $variants ?? [];
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

$sizeShortLabel = 'LENGAN PENDEK';
$sizeLongLabel  = 'LENGAN PANJANG (+12K)';
$uploadHints = ['Ket: Bordir Depan', 'Ket: Bordir Belakang', 'Ket: Bordir Samping'];
$uploadTitle = 'Upload Referensi Desain';

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
    <input type="hidden" name="kategori" value="PDH">
    <input type="hidden" id="variantId" name="variant_id" value="<?= e($editItem['variant_id'] ?? '') ?>">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'pdh') ?>">
    <input type="hidden" id="paketBahan" name="paket_bahan" value="">
    <input type="hidden" name="sleeve_price" value="<?= $longSleeveSurcharge ?>">
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
                        <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>— Pilih bahan —</option>
                        <?php foreach ($variantOptions as $label => $harga): ?>
                            <option value="<?= e($label) ?>" data-price="<?= e($harga) ?>"
                                data-variant-id="<?= e($variantIdMap[$label] ?? '') ?>"
                                <?= $isEdit && (($editVariantId > 0 && $editVariantId === (int) ($variantIdMap[$label] ?? 0)) || ($editVariantId === 0 && $editBahan === $label)) ? 'selected' : '' ?>>
                                <?= e($label) ?> — Rp <?= e(number_format($harga, 0, ',', '.')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="hargaSablon">Harga Sablon/Bordir per pcs</label>
                    <div class="input-group">
                        <span class="input-group__prefix">Rp</span>
                        <input id="hargaSablon" class="form-control" type="number" name="sablon_price"
                               min="0" step="1" value="<?= e($editSablonPrice ?: 0) ?>" placeholder="0" inputmode="numeric">
                    </div>
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
                    <p class="summary-helper" id="totalQtyHelper">Minimal pemesanan <?= e($minimumOrder) ?>&nbsp;pcs.</p>
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
        const select = document.getElementById('bahanKain');
        const hargaSablon = document.getElementById('hargaSablon');
        const variantIdInput = document.getElementById('variantId');
        const paketBahanInput = document.getElementById('paketBahan');

        function updateSelectedVariant() {
            const opt = select.options[select.selectedIndex];
            if (variantIdInput) variantIdInput.value = opt?.getAttribute('data-variant-id') || '';
            if (paketBahanInput) paketBahanInput.value = opt?.getAttribute('data-price') || '';
        }

        const calc = TransactionCalc.init({
            basePrice: () => {
                const opt = select.options[select.selectedIndex];
                return (parseInt(opt?.getAttribute('data-price'), 10) || 0) + (parseInt(hargaSablon.value, 10) || 0);
            },
            longSleeveSurcharge: <?= $longSleeveSurcharge ?>,
            minQty: <?= e($minimumOrder) ?>,
        });
        select.addEventListener('change', () => { updateSelectedVariant(); calc(); });
        hargaSablon.addEventListener('input', calc);
        document.querySelectorAll('.qty-input').forEach(el => el.addEventListener('input', calc));
        updateSelectedVariant();
        calc();
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
