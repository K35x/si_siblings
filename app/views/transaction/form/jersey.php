<?php
$pageTitle    = 'Konfigurasi Jersey - Siblings.co';
$formHeading  = 'Konfigurasi Custom Jersey';

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
$editCatatan = $editItem['catatan'] ?? '';
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
    <input type="hidden" name="kategori" value="Jersey">
    <input type="hidden" id="variantId" name="variant_id" value="<?= e($editItem['variant_id'] ?? '') ?>">
    <input type="hidden" name="form_source" value="<?= e($formSource ?? 'jersey') ?>">
    <input type="hidden" name="sleeve_price" value="<?= $longSleeveSurcharge ?>">
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
                        <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>— Pilih paket —</option>
                        <?php foreach ($variantOptions as $label => $harga): ?>
                            <option value="<?= e($harga) ?>"
                                data-variant-id="<?= e($variantIdMap[$label] ?? '') ?>"
                                <?= $isEdit && (($editVariantId > 0 && $editVariantId === (int) ($variantIdMap[$label] ?? 0)) || ($editVariantId === 0 && $editBahan === $label)) ? 'selected' : '' ?> >
                                <?= e($label) ?> — Rp <?= e(number_format($harga, 0, ',', '.')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-field__label" for="hargaSablon">Harga Bordir per pcs</label>
                    <div class="input-group">
                        <span class="input-group__prefix">Rp</span>
                        <input id="hargaSablon" class="form-control" type="number" name="sablon_price"
                               min="0" step="1" value="<?= e($editSablonPrice ?: 0) ?>" placeholder="0" inputmode="numeric">
                    </div>
                </div>

                <?php include __DIR__ . '/../includes/upload-section.php'; ?>
            </div>
        </section>

        <section class="form-card" aria-labelledby="jerseySize">
            <div class="form-card__header" id="jerseySize">
                <i class="fas fa-th-list" aria-hidden="true"></i>
                Rincian Ukuran & Warna
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

<script src="<?= asset('js/transaction-calc.js') ?>"></script>
<script src="<?= asset('js/transaction-form-validation.js') ?>"></script>
<script>
    (function () {
        const hargaSablon = document.getElementById('hargaSablon');
        const paketBahan = document.getElementById('paketBahan');
        const variantIdInput = document.getElementById('variantId');

        function updateSelectedVariant() {
            const opt = paketBahan.options[paketBahan.selectedIndex];
            if (variantIdInput) variantIdInput.value = opt?.getAttribute('data-variant-id') || '';
        }

        const calc = TransactionCalc.init({
            basePrice: () => (parseInt(paketBahan.value, 10) || 0) + (parseInt(hargaSablon.value, 10) || 0),
            longSleeveSurcharge: <?= $longSleeveSurcharge ?>,
            minQty: 0,
        });

        document.querySelectorAll('#paketBahan, #hargaSablon').forEach((el) => {
            el.addEventListener('input', calc);
            el.addEventListener('change', () => { updateSelectedVariant(); calc(); });
        });
        document.querySelectorAll('.qty-input').forEach(el => el.addEventListener('input', calc));
        updateSelectedVariant();
        calc();
    })();
</script>

<?php include __DIR__ . '/../includes/form-layout-end.php'; ?>
