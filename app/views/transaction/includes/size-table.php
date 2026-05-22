<?php
/**
 * Partial: tabel ukuran compact 2 kolom (lengan pendek | lengan panjang).
 * Param yang bisa di-set:
 *   $sizeShortLabel - default "LENGAN PENDEK"
 *   $sizeLongLabel  - default "LENGAN PANJANG (+10K)"
 *   $sizeShowLong   - default true
 *   $colors         - array dari product_color (wajib untuk dropdown)
 */
$sizeShortLabel = $sizeShortLabel ?? 'LENGAN PENDEK';
$sizeLongLabel  = $sizeLongLabel  ?? 'LENGAN PANJANG (+10K)';
$sizeShowLong   = $sizeShowLong ?? true;
$availableSizes = ['S', 'M', 'L', 'XL', 'XXL'];
$colorOptions   = $colors ?? [];
?>
<table class="size-table size-table--compact">
    <thead>
        <tr>
            <th scope="col" class="size-col-size">Size</th>
            <th scope="col" colspan="2" class="size-col-section"><?= e($sizeShortLabel) ?></th>
            <?php if ($sizeShowLong): ?>
                <th scope="col" colspan="2" class="size-col-section size-col-section--alt"><?= e($sizeLongLabel) ?></th>
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
            $extra = $sz === 'XXL' ? '10000' : '0';
        ?>
            <tr>
                <td class="size-cell-name">
                    <strong><?= e($sz) ?></strong>
                    <?php if ($sz === 'XXL'): ?><span class="size-extra-tag">(+10k)</span><?php endif; ?>
                </td>
                <td>
                    <label class="sr-only" for="qty_short_<?= e($sz) ?>">Qty pendek <?= e($sz) ?></label>
                    <input id="qty_short_<?= e($sz) ?>" type="number"
                           name="qty_short_<?= e($sz) ?>" class="qty-input short tabular-nums"
                           min="0" value="0"
                           inputmode="numeric"
                           data-xxl="<?= $extra ?>"
                           aria-describedby="totalQtyHelper">
                </td>
                <td>
                    <label class="sr-only" for="warna_short_<?= e($sz) ?>">Warna pendek <?= e($sz) ?></label>
                    <select id="warna_short_<?= e($sz) ?>" name="warna_short_<?= e($sz) ?>" data-placeholder="Pilih warna…">
                        <option value="">-</option>
                        <?php foreach ($colorOptions as $c): ?>
                            <option value="<?= e($c['color_name']) ?>"><?= e($c['color_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <?php if ($sizeShowLong): ?>
                    <td>
                        <label class="sr-only" for="qty_long_<?= e($sz) ?>">Qty panjang <?= e($sz) ?></label>
                        <input id="qty_long_<?= e($sz) ?>" type="number"
                               name="qty_long_<?= e($sz) ?>" class="qty-input long tabular-nums"
                               min="0" value="0"
                               inputmode="numeric"
                               data-xxl="<?= $extra ?>"
                               aria-describedby="totalQtyHelper">
                    </td>
                    <td>
                        <label class="sr-only" for="warna_long_<?= e($sz) ?>">Warna panjang <?= e($sz) ?></label>
                        <select id="warna_long_<?= e($sz) ?>" name="warna_long_<?= e($sz) ?>" data-placeholder="Pilih warna…">
                            <option value="">-</option>
                            <?php foreach ($colorOptions as $c): ?>
                                <option value="<?= e($c['color_name']) ?>"><?= e($c['color_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
