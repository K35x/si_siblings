<?php
$validationErrors = $validationErrors ?? [];
$oldInput = $oldInput ?? [];
?>

<?php if (!empty($validationErrors)): ?>
    <div class="alert alert--danger" role="alert" aria-live="assertive" tabindex="-1" data-validation-banner>
        <strong>Input pesanan belum valid.</strong>
        <ul>
            <?php foreach ($validationErrors as $message): ?>
                <li><?= e((string) $message) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const banner = document.querySelector('[data-validation-banner]');
            if (banner && typeof banner.focus === 'function') {
                banner.focus();
            }
        });
    </script>
<?php endif; ?>

<?php if (!empty($oldInput)): ?>
    <div class="alert alert--warning" role="status" aria-live="polite">
        Data yang sudah Anda isi dipertahankan. Perbaiki field yang ditandai lalu submit ulang.
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const oldInput = <?= json_encode($oldInput, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

            Object.entries(oldInput).forEach(([name, value]) => {
                document.querySelectorAll(`[name="${CSS.escape(name)}"]`).forEach((field) => {
                    if (field.type === 'checkbox' || field.type === 'radio') {
                        field.checked = field.value === String(value) || value === '1' || value === true;
                    } else if (field.type !== 'file') {
                        field.value = value;
                    }

                    field.dispatchEvent(new Event('change', { bubbles: true }));
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                });
            });
        });
    </script>
<?php endif; ?>
