<?php
$validationErrors = $validationErrors ?? [];
$oldInput = $oldInput ?? [];
?>

<?php if (!empty($validationErrors)): ?>
    <div class="alert alert-danger" style="background:#fdecea;color:#b71c1c;border:1px solid #f5c2c7;border-radius:6px;padding:12px;margin:12px 0;">
        <strong>Input pesanan belum valid.</strong>
        <ul style="margin:8px 0 0 18px;">
            <?php foreach ($validationErrors as $message): ?>
                <li><?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($oldInput)): ?>
    <div class="alert alert-warning" style="background:#fff8e1;color:#6d4c41;border:1px solid #ffe082;border-radius:6px;padding:12px;margin:12px 0;">
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
