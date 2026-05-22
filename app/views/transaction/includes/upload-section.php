<?php
/**
 * Partial: bagian upload desain & catatan tambahan.
 * Bisa di-override placeholder via $uploadHints (array 3 elemen).
 */
$uploadHints = $uploadHints ?? [
    'Ket: Lokasi Depan',
    'Ket: Lokasi Belakang',
    'Ket: Lokasi Samping',
];
$uploadTitle = $uploadTitle ?? 'Upload Desain Sablon';
?>
<div class="design-upload-section">
    <div class="design-upload-section__title">
        <i class="fas fa-image" aria-hidden="true"></i>
        <span><?= e($uploadTitle) ?></span>
    </div>

    <?php for ($i = 1; $i <= 3; $i++): ?>
        <div class="upload-item">
            <label class="sr-only" for="desain_<?= $i ?>">Upload desain <?= $i ?></label>
            <input id="desain_<?= $i ?>" type="file" name="desain_<?= $i ?>"
                   accept="image/png,image/jpeg,image/webp,application/pdf">

            <label class="sr-only" for="note_desain_<?= $i ?>">Keterangan desain <?= $i ?></label>
            <input id="note_desain_<?= $i ?>" type="text" name="note_desain_<?= $i ?>"
                   placeholder="<?= e($uploadHints[$i - 1] ?? '') ?>"
                   autocomplete="off"
                   class="note-input">
        </div>
    <?php endfor; ?>

    <div class="form-field upload-section__notes">
        <label class="form-field__label" for="catatan_pesanan">
            <i class="fas fa-sticky-note" aria-hidden="true"></i>
            Catatan Tambahan Pesanan
        </label>
        <textarea id="catatan_pesanan" class="form-control" name="catatan_pesanan" rows="4"
                  placeholder="Contoh: nama di dada kanan, logo di lengan kiri, pakai furing, dll."></textarea>
    </div>
</div>
