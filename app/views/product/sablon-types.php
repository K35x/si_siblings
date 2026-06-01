<?php
$pageTitle = 'Tipe Sablon - Siblings.co';
$pageStyles = ['products.css'];
$sidebarRole = $sidebarRole ?? Model::ROLE_OWNER;
$activeMenu = $activeMenu ?? 'products';
$totalSablonTypes = count($sablonTypes ?? []);
$activeSablonTypes = 0;
$unmappedSablonTypes = 0;
foreach ($sablonTypes ?? [] as $row) {
    $rowId = (int) ($row['sablon_type_id'] ?? 0);
    if ((int) ($row['is_active'] ?? 1) === 1) {
        $activeSablonTypes++;
    }
    if (empty($associations[$rowId] ?? [])) {
        $unmappedSablonTypes++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<a href="#main-content" class="skip-to-content">Lewati ke konten utama</a>
<?php include __DIR__ . '/../partials/sidebar-toggle.php'; ?>

<div class="app-shell">
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="app-main" id="main-content">
        <div class="header-photo" aria-hidden="true"></div>

        <div class="app-content">
            <div class="product-page__header">
                <div>
                    <h1><i class="fas fa-print" aria-hidden="true"></i> Tipe Sablon</h1>
                    <p>Kelola tipe sablon dan kategori produk yang tersedia untuk kasir.</p>
                </div>
                <div class="product-page__actions">
                    <a class="btn btn--ghost product-back-btn" href="<?= url('/products') ?>">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Kembali ke Produk
                    </a>
                    <button type="button" class="btn btn--primary" id="btnTambahSablon">
                        <i class="fas fa-plus" aria-hidden="true"></i> Tambah Tipe Sablon
                    </button>
                </div>
            </div>

            <?php if (!empty($sablonTypes)): ?>
                <div class="sablon-overview" aria-label="Ringkasan tipe sablon">
                    <div class="sablon-overview__item">
                        <span>Total Tipe</span>
                        <strong><?= e($totalSablonTypes) ?></strong>
                    </div>
                    <div class="sablon-overview__item sablon-overview__item--success">
                        <span>Aktif</span>
                        <strong><?= e($activeSablonTypes) ?></strong>
                    </div>
                    <div class="sablon-overview__item <?= $unmappedSablonTypes > 0 ? 'sablon-overview__item--warning' : '' ?>">
                        <span>Belum Terkait Kategori</span>
                        <strong><?= e($unmappedSablonTypes) ?></strong>
                    </div>
                </div>
            <?php endif; ?>

            <div class="product-wrapper scroll-thin product-wrapper--sablon">
                <?php if (empty($sablonTypes)): ?>
                    <div class="empty-state empty-state--bare">
                        <i class="fas fa-print empty-state__icon" aria-hidden="true"></i>
                        <h2 class="empty-state__title">Belum ada tipe sablon</h2>
                        <p class="empty-state__desc">Tambahkan tipe sablon agar bisa dipilih di form pemesanan kasir.</p>
                    </div>
                <?php else: ?>
                    <div class="stok-table-wrap">
                        <table class="stok-table variant-options-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Catatan</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sablonTypes as $sablonType): ?>
                                    <?php
                                    $id = (int) $sablonType['sablon_type_id'];
                                    $active = (int) ($sablonType['is_active'] ?? 1) === 1;
                                    $linkedCategories = $associations[$id] ?? [];
                                    $linkedCategoryIds = array_map('intval', array_column($linkedCategories, 'category_id'));
                                    ?>
                                    <tr class="<?= $active ? '' : 'variant-options-table__row--inactive' ?>">
                                        <td class="stok-table__color-cell sablon-name-cell">
                                            <span><?= e($sablonType['sablon_name']) ?></span>
                                            <?php if (!$active): ?>
                                                <small>Disembunyikan dari pesanan baru</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="sablon-notes-cell"><?= e($sablonType['notes'] ?: '-') ?></td>
                                        <td>
                                            <div class="sablon-category-list sablon-category-list--readonly">
                                                <?php if (empty($linkedCategories)): ?>
                                                    <span class="sablon-category-chip sablon-category-chip--warning">
                                                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                                        Belum muncul di kasir
                                                    </span>
                                                <?php else: ?>
                                                    <?php foreach ($linkedCategories as $category): ?>
                                                        <span class="sablon-category-chip"><?= e($category['category_name']) ?></span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-pill <?= $active ? 'status-pill--success' : 'status-pill--muted' ?>">
                                                <?= $active ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="master-list__actions">
                                                <button type="button"
                                                        class="btn btn--ghost btn--sm"
                                                        data-action="edit-sablon"
                                                        data-sablon='<?= e(json_encode($sablonType)) ?>'
                                                        data-category-ids='<?= e(json_encode($linkedCategoryIds)) ?>'>
                                                    <i class="fas fa-pen" aria-hidden="true"></i> Edit
                                                </button>
                                                <button type="button"
                                                        class="btn <?= $active ? 'btn--danger' : 'btn--primary' ?> btn--sm"
                                                        data-action="toggle-sablon"
                                                        data-sablon-type-id="<?= e($id) ?>"
                                                        data-active="<?= $active ? '1' : '0' ?>">
                                                    <i class="fas fa-power-off" aria-hidden="true"></i>
                                                    <?= $active ? 'Nonaktifkan' : 'Aktifkan' ?>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<div class="modal-overlay" id="modalSablon" role="dialog" aria-modal="true" aria-labelledby="modalSablonTitle" aria-hidden="true">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title" id="modalSablonTitle">Tambah Tipe Sablon</h3>
            <button type="button" class="modal__close" data-modal-close aria-label="Tutup dialog">&times;</button>
        </div>
        <div class="modal__body">
            <input type="hidden" id="f_sablon_type_id" value="">
            <div class="form-field">
                <label class="form-field__label" for="f_sablon_name">Nama Tipe Sablon</label>
                <input id="f_sablon_name" class="form-control" type="text" maxlength="100">
            </div>
            <div class="form-field">
                <label class="form-field__label" for="f_sablon_notes">Catatan</label>
                <input id="f_sablon_notes" class="form-control" type="text" maxlength="255">
            </div>
            <div class="form-field">
                <span class="form-field__label">Kategori Produk</span>
                <p class="form-help">Centang kategori agar tipe sablon tersedia di form pesanan kasir.</p>
                <div class="sablon-category-picker">
                    <?php foreach ($categories as $category): ?>
                        <label class="sablon-category-option">
                            <input type="checkbox" value="<?= e($category['category_id']) ?>" data-category-checkbox>
                            <span><?= e($category['category_name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--ghost" data-modal-close>Batal</button>
            <button type="button" class="btn btn--primary" id="btnSimpanSablon">Simpan</button>
        </div>
    </div>
</div>

<script>
const ENDPOINTS = window.ENDPOINTS = {
    store: '<?= url('/products/sablon-types/store') ?>',
    update: '<?= url('/products/sablon-types/update') ?>',
    toggle: '<?= url('/products/sablon-types/toggle') ?>',
};
</script>
<script src="<?= asset('js/ui.js') ?>"></script>
<script>
(function () {
    const ui = window.SiblingsUI;
    const modal = document.getElementById('modalSablon');
    const title = document.getElementById('modalSablonTitle');
    const sablonTypeId = document.getElementById('f_sablon_type_id');
    const sablonName = document.getElementById('f_sablon_name');
    const sablonNotes = document.getElementById('f_sablon_notes');
    const saveBtn = document.getElementById('btnSimpanSablon');
    const categoryInputs = Array.from(modal.querySelectorAll('[data-category-checkbox]'));

    document.getElementById('btnTambahSablon')?.addEventListener('click', () => {
        title.textContent = 'Tambah Tipe Sablon';
        modal.dataset.mode = 'create';
        sablonTypeId.value = '';
        sablonName.value = '';
        sablonNotes.value = '';
        setCategoryChecks([]);
        ui.openModal('modalSablon');
        sablonName.focus();
    });

    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest('[data-action]');
        if (!trigger) return;

        if (trigger.dataset.action === 'edit-sablon') {
            const sablon = JSON.parse(trigger.dataset.sablon || '{}');
            title.textContent = 'Edit Tipe Sablon';
            modal.dataset.mode = 'edit';
            sablonTypeId.value = sablon.sablon_type_id || '';
            sablonName.value = sablon.sablon_name || '';
            sablonNotes.value = sablon.notes || '';
            setCategoryChecks(JSON.parse(trigger.dataset.categoryIds || '[]'));
            ui.openModal('modalSablon');
            sablonName.focus();
        }

        if (trigger.dataset.action === 'toggle-sablon') {
            const active = trigger.dataset.active === '1';
            const ok = await ui.confirm({
                title: active ? 'Nonaktifkan tipe sablon?' : 'Aktifkan tipe sablon?',
                message: active ? 'Tipe sablon ini tidak akan tersedia untuk pesanan baru.' : 'Tipe sablon ini akan tersedia kembali sesuai kategori terkait.',
                confirmText: active ? 'Nonaktifkan' : 'Aktifkan',
                variant: active ? 'danger' : 'primary',
            });
            if (!ok) return;

            const response = await postJson(ENDPOINTS.toggle, {
                sablon_type_id: parseInt(trigger.dataset.sablonTypeId, 10),
                active: !active,
            });
            if (response?.success) {
                ui.toast(active ? 'Tipe sablon dinonaktifkan.' : 'Tipe sablon diaktifkan.', 'success');
                location.reload();
            } else {
                ui.toast(response?.message || 'Gagal mengubah status.', 'danger');
            }
        }

    });

    saveBtn?.addEventListener('click', async () => {
        const payload = {
            sablon_name: sablonName.value.trim(),
            notes: sablonNotes.value.trim() || null,
            category_ids: selectedCategoryIds(),
        };
        if (!payload.sablon_name) {
            ui.toast('Nama tipe sablon wajib diisi.', 'warning');
            sablonName.focus();
            return;
        }

        const endpoint = modal.dataset.mode === 'edit' ? ENDPOINTS.update : ENDPOINTS.store;
        if (modal.dataset.mode === 'edit') payload.sablon_type_id = parseInt(sablonTypeId.value, 10);

        await ui.withLoading(saveBtn, async () => {
            const response = await postJson(endpoint, payload);
            if (response?.success) {
                ui.toast('Tipe sablon tersimpan.', 'success');
                ui.closeModal('modalSablon');
                location.reload();
            } else {
                ui.toast(response?.message || 'Gagal menyimpan tipe sablon.', 'danger');
            }
        });
    });

    function setCategoryChecks(selectedIds) {
        const selected = new Set((selectedIds || []).map((id) => parseInt(id, 10)));
        categoryInputs.forEach((input) => {
            input.checked = selected.has(parseInt(input.value, 10));
        });
    }

    function selectedCategoryIds() {
        return categoryInputs
            .filter((input) => input.checked)
            .map((input) => parseInt(input.value, 10));
    }

    async function postJson(url, body) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': window.SiblingsUI?.getCsrfToken() || '',
                },
                body: JSON.stringify(body),
                credentials: 'same-origin',
            });
            if (!response.ok) return { success: false, message: `Server merespons ${response.status}.` };
            return await response.json();
        } catch (error) {
            return { success: false, message: 'Gagal menghubungi server.' };
        }
    }
})();
</script>
</body>
</html>
