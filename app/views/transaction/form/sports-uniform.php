<?php
$pageTitle    = 'Konfigurasi Seragam Olahraga - Siblings.co';
$formHeading  = 'Konfigurasi Seragam Olahraga';

$variants = $variants ?? [];
$sablonTypes = $sablonTypes ?? [];
$materialOptions = [];
$materialVariantIdMap = [];
foreach ($variants as $v) {
    $bahan = trim((string) ($v['material'] ?? ''));
    if ($bahan !== '' && !in_array($bahan, $materialOptions, true)) {
        $materialOptions[] = $bahan;
    }
    if ($bahan !== '' && !isset($materialVariantIdMap[$bahan])) {
        $materialVariantIdMap[$bahan] = (int) $v['variant_id'];
    }
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
    <input type="hidden" name="kategori" value="Seragam Olahraga">
    <input type="hidden" id="variantId" name="variant_id" value="<?= e($editItem['variant_id'] ?? '') ?>">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'seragam-olahraga') ?>">
    <input type="hidden" name="sleeve_price" value="<?= $longSleeveSurcharge ?>">
    <input type="hidden" name="index_edit" value="<?= e($editIndex ?? '') ?>">
    <input type="hidden" id="paketBahan" name="paket_bahan" value="<?= e($variants[0]['price'] ?? 95000) ?>">
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
                        <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>— Pilih bahan —</option>
                        <?php foreach ($materialOptions as $b): ?>
                            <option value="<?= e($b) ?>"
                                data-variant-id="<?= e($materialVariantIdMap[$b] ?? '') ?>"
                                <?= $isEdit && (($editVariantId > 0 && $editVariantId === (int) ($materialVariantIdMap[$b] ?? 0)) || ($editVariantId === 0 && $editBahan === $b)) ? 'selected' : '' ?>><?= e($b) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="jenisSablon">Jenis Sablon</label>
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
                    <label class="form-field__label" for="sablonPrice">Harga Sablon (Rp)</label>
                    <input type="number" name="sablon_price" id="sablonPrice" class="form-control"
                        value="<?= e($editSablonPrice) ?>"
                        min="0" step="1000" placeholder="0">
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="bahanTraining">Bahan Training (otomatis)</label>
                    <input id="bahanTraining" class="form-control" type="text" name="material_training" value="Bahan Diadora" readonly>
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
                        <span>Total Pesanan</span>
                        <span aria-live="polite"><strong id="totalQty">0</strong> stel</span>
                    </div>
                    <div class="summary-row summary-row--grand">
                        <span>Estimasi</span>
                        <span id="totalHarga" aria-live="polite">Rp&nbsp;0</span>
                    </div>
                    <p class="summary-helper" id="totalQtyHelper" role="status" hidden>
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        Minimal pemesanan <?= e($minimumOrder) ?>&nbsp;pcs.
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

<script src="<?= asset('js/transaction-calc.js') ?>"></script>
<script src="<?= asset('js/transaction-form-validation.js') ?>"></script>
<script>
    (function () {
        const hargaSablon = document.getElementById('hargaSablon');
        const sablonPriceInput = document.getElementById('sablonPrice');
        const bahanBaju = document.getElementById('bahanBaju');
        const variantIdInput = document.getElementById('variantId');

        function updateSelectedVariant() {
            const opt = bahanBaju.options[bahanBaju.selectedIndex];
            if (variantIdInput) variantIdInput.value = opt?.getAttribute('data-variant-id') || '';
        }

        const jenisSablon = document.getElementById('jenisSablon');
        if (jenisSablon && sablonPriceInput) {
            jenisSablon.addEventListener('change', function() {
                if (!jenisSablon.value) {
                    sablonPriceInput.value = 0;
                }
                if (typeof calc === 'function') calc();
            });
        }

        const DEFAULT_PRICE = <?= e($variants[0]['price'] ?? 95000) ?>;

        const calc = TransactionCalc.init({
            basePrice: () => DEFAULT_PRICE + (parseInt(sablonPriceInput?.value, 10) || 0),
            longSleeveSurcharge: <?= $longSleeveSurcharge ?>,
            minQty: <?= e($minimumOrder) ?>,
            onCalc: (result) => {
                const warning = document.getElementById('totalQtyHelper');
                if (warning) {
                    warning.hidden = result.qty >= <?= e($minimumOrder) ?> || result.qty === 0;
                }
            },
        });
        if (sablonPriceInput) sablonPriceInput.addEventListener('input', calc);
        bahanBaju.addEventListener('change', () => { updateSelectedVariant(); calc(); });
        document.querySelectorAll('.qty-input').forEach(el => el.addEventListener('input', calc));
        updateSelectedVariant();
        calc();
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
