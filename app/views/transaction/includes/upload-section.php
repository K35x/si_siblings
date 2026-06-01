<?php

$uploadHints = $uploadHints ?? [
    'Ket: Lokasi Depan',
    'Ket: Lokasi Belakang',
    'Ket: Lokasi Samping',
];
$uploadTitle = $uploadTitle ?? 'Upload Desain Sablon';
$editCatatan = $editCatatan ?? '';
$editDesigns = $editItem['desain'] ?? [];
$oldInput = $oldInput ?? [];
if (!empty($oldInput)) {
    $editCatatan = $oldInput['order_notes'] ?? $editCatatan;
}
?>
<div class="design-upload-section">
    <div class="design-upload-section__title">
        <i class="fas fa-image" aria-hidden="true"></i>
        <span><?= e($uploadTitle) ?></span>
    </div>

    <?php for ($i = 1; $i <= 3; $i++): ?>
        <?php
        $existingDesign = $editDesigns[$i - 1] ?? null;
        $noteValue = $oldInput['note_desain_' . $i] ?? ($existingDesign['note'] ?? '');
        ?>
        <div class="upload-item">
            <label class="sr-only" for="desain_<?= $i ?>">Upload desain <?= $i ?></label>
            <input id="desain_<?= $i ?>" type="file" name="desain_<?= $i ?>"
                   accept="image/png,image/jpeg,image/webp,application/pdf">

            <?php if (!empty($existingDesign['filename'])): ?>
                <p class="text-muted mt-2">
                    File saat ini:
                    <a href="<?= e(url('/' . ltrim($existingDesign['url'] ?? '', '/'))) ?>" target="_blank" rel="noopener">
                        <?= e($existingDesign['filename']) ?>
                    </a>
                    <span>— pilih file baru jika ingin mengganti.</span>
                </p>
            <?php endif; ?>

            <label class="sr-only" for="note_desain_<?= $i ?>">Keterangan desain <?= $i ?></label>
            <input id="note_desain_<?= $i ?>" type="text" name="note_desain_<?= $i ?>"
                   placeholder="<?= e($uploadHints[$i - 1] ?? '') ?>"
                   value="<?= e($noteValue) ?>"
                   autocomplete="off"
                   class="note-input">
        </div>
    <?php endfor; ?>

    <div class="form-field upload-section__notes">
        <label class="form-field__label" for="order_notes">
            <i class="fas fa-sticky-note" aria-hidden="true"></i>
            Catatan Tambahan Pesanan
        </label>
        <textarea id="order_notes" class="form-control" name="order_notes" rows="4"
                  placeholder="Contoh: nama di dada kanan, logo di lengan kiri, pakai furing, dll."><?= e($editCatatan) ?></textarea>
    </div>
</div>
