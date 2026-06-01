<?php

$sizeShortLabel = $sizeShortLabel ?? "LENGAN PENDEK";
$sizeLongLabel = $sizeLongLabel ?? "LENGAN PANJANG";
$sizeShowLong = $sizeShowLong ?? true;
$availableSizes = array_column($sizes ?? [], 'size_name');
$colorOptions = $colors ?? [];
$sizeSurcharges = $sizeSurcharges ?? [];
$sizeIdByName = [];
foreach ($sizes ?? [] as $sizeRow) {
    $sizeIdByName[$sizeRow['size_name']] = (int) $sizeRow['size_id'];
}

$editRincian = $editRincian ?? [];
$editQuantityShort = $editQuantityShort ?? [];
$editQuantityLong = $editQuantityLong ?? [];
$editWarnaPerSize = $editWarnaPerSize ?? ["short" => [], "long" => []];
$editCustomColors = $editCustomColors ?? [];
$oldInput = $oldInput ?? [];
if (!empty($oldInput)) {
    foreach ($availableSizes as $sz) {
        $shortQty = (int) ($oldInput['quantity_short_' . $sz] ?? 0);
        $longQty = (int) ($oldInput['quantity_long_' . $sz] ?? 0);
        $editQuantityShort[$sz] = $shortQty;
        $editQuantityLong[$sz] = $longQty;
        $editRincian[$sz] = $shortQty + $longQty;

        $shortColor = (string) ($oldInput['warna_short_' . $sz] ?? '');
        $longColor = (string) ($oldInput['warna_long_' . $sz] ?? '');
        $editWarnaPerSize['short'][$sz] = $shortColor === '__custom'
            ? (string) ($oldInput['custom_warna_short_' . $sz] ?? '')
            : $shortColor;
        $editWarnaPerSize['long'][$sz] = $longColor === '__custom'
            ? (string) ($oldInput['custom_warna_long_' . $sz] ?? '')
            : $longColor;
    }
}
$isEdit = !empty($editRincian);
?>
<div class="size-table-wrap">
<table class="size-table size-table--compact">
    <thead>
        <tr>
            <th scope="col" class="size-col-size">Size</th>
            <th scope="col" colspan="2" class="size-col-section"><?= e(
                $sizeShortLabel,
            ) ?></th>
            <?php if ($sizeShowLong): ?>
                <th scope="col" colspan="2" class="size-col-section size-col-section--alt"><?= e(
                    $sizeLongLabel,
                ) ?></th>
            <?php endif; ?>
        </tr>
        <tr class="size-subheader">
            <th></th>
            <th>Qty</th>
            <th>Warna</th>
            <?php if ($sizeShowLong): ?>
                <th>Qty</th>
                <th>Warna</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($availableSizes as $sz):
            $extra = (string) ($sizeSurcharges[$sz] ?? 0); ?>
            <tr data-size-name="<?= e($sz) ?>" data-size-id="<?= e($sizeIdByName[$sz] ?? '') ?>">
                <td class="size-cell-name">
                    <strong><?= e($sz) ?></strong>
                    <?php if (
                        (float) $extra > 0
                    ): ?><span class="size-extra-tag">(+<?= e(
    number_format((float) $extra / 1000, 0),
) ?>k)</span><?php endif; ?>
                </td>
                <?php
                $qtyShortVal = (int) ($editQuantityShort[$sz] ?? $editRincian[$sz] ?? 0);
                $qtyLongVal = (int) ($editQuantityLong[$sz] ?? 0);
                $wsVal = $editWarnaPerSize["short"][$sz] ?? "";
                $wlVal = $editWarnaPerSize["long"][$sz] ?? "";
                $isCustomShort =
                    $wsVal !== "" &&
                    !in_array(
                        $wsVal,
                        array_column($colorOptions, "color_name"),
                    );
                $isCustomLong =
                    $wlVal !== "" &&
                    !in_array(
                        $wlVal,
                        array_column($colorOptions, "color_name"),
                    );
                ?>
                <td>
                    <label class="sr-only" for="quantity_short_<?= e(
                        $sz,
                    ) ?>">Qty pendek <?= e($sz) ?></label>
                    <input id="quantity_short_<?= e($sz) ?>" type="number"
                           name="quantity_short_<?= e(
                               $sz,
                           ) ?>" class="qty-input short tabular-nums"
                           min="0" placeholder="0"
                           inputmode="numeric"
                           data-surcharge="<?= $extra ?>"
                           value="<?= e($qtyShortVal) ?>"
                           aria-describedby="totalQtyHelper">
                </td>
                <td>
                    <label class="sr-only" for="warna_short_<?= e(
                        $sz,
                    ) ?>">Warna pendek <?= e($sz) ?></label>
                    <select id="warna_short_<?= e(
                        $sz,
                    ) ?>" name="warna_short_<?= e(
    $sz,
) ?>" data-sleeve-type="short" data-placeholder="Pilih warna…">
                        <option value="">-</option>
                        <?php foreach ($colorOptions as $c): ?>
                            <option value="<?= e($c["color_name"]) ?>"
                                <?= $wsVal === $c["color_name"]
                                    ? "selected"
                                    : "" ?>><?= e($c["color_name"]) ?></option>
                        <?php endforeach; ?>
                        <option value="__custom" <?= $isCustomShort
                            ? "selected"
                            : "" ?>>Custom</option>
                    </select>
                    <input type="text" class="custom-color-input" name="custom_warna_short_<?= e(
                        $sz,
                    ) ?>"
                           placeholder="Contoh: Merah Putih"
                           value="<?= $isCustomShort ? e($wsVal) : "" ?>"
                           style="<?= $isCustomShort
                               ? ""
                               : "display:none;" ?> margin-top:4px;">
                </td>
                <?php if ($sizeShowLong): ?>
                    <td>
                        <label class="sr-only" for="quantity_long_<?= e(
                            $sz,
                        ) ?>">Qty panjang <?= e($sz) ?></label>
                        <input id="quantity_long_<?= e($sz) ?>" type="number"
                               name="quantity_long_<?= e(
                                   $sz,
                               ) ?>" class="qty-input long tabular-nums"
                               min="0" placeholder="0"
                               inputmode="numeric"
                               data-surcharge="<?= $extra ?>"
                               value="<?= e($qtyLongVal) ?>"
                               aria-describedby="totalQtyHelper">
                    </td>
                    <td>
                        <label class="sr-only" for="warna_long_<?= e(
                            $sz,
                        ) ?>">Warna panjang <?= e($sz) ?></label>
                        <select id="warna_long_<?= e(
                            $sz,
                        ) ?>" name="warna_long_<?= e(
    $sz,
) ?>" data-sleeve-type="long" data-placeholder="Pilih warna…">
                            <option value="">-</option>
                            <?php foreach ($colorOptions as $c): ?>
                                <option value="<?= e($c["color_name"]) ?>"
                                    <?= $wlVal === $c["color_name"]
                                        ? "selected"
                                        : "" ?>><?= e(
    $c["color_name"],
) ?></option>
                            <?php endforeach; ?>
                            <option value="__custom" <?= $isCustomLong
                                ? "selected"
                                : "" ?>>Custom</option>
                        </select>
                        <input type="text" class="custom-color-input" name="custom_warna_long_<?= e(
                            $sz,
                        ) ?>"
                               placeholder="Contoh: Merah Putih"
                               value="<?= $isCustomLong ? e($wlVal) : "" ?>"
                               style="<?= $isCustomLong
                                   ? ""
                                   : "display:none;" ?> margin-top:4px;">
                    </td>
                <?php endif; ?>
            </tr>
        <?php
        endforeach; ?>
    </tbody>
</table>
</div>
<script>
document.querySelectorAll('select[name^="warna_short_"], select[name^="warna_long_"]').forEach(sel => {
    const toggleCustomInput = () => {
        const customInput = sel.parentElement.querySelector('.custom-color-input');
        if (!customInput) return;
        customInput.style.display = sel.value === '__custom' ? 'block' : 'none';
        if (sel.value !== '__custom') customInput.value = '';
    };

    toggleCustomInput();
    sel.addEventListener('change', toggleCustomInput);
});
</script>
