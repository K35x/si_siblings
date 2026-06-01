<?php
$pageTitle = 'Opsi Varian - Siblings.co';
$pageStyles = ['products.css'];
$sidebarRole = $sidebarRole ?? Model::ROLE_OWNER;
$activeMenu = $activeMenu ?? 'products';

$variantId = (int) $variant['variant_id'];
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
                    <h1><i class="fas fa-layer-group" aria-hidden="true"></i> Opsi Varian</h1>
                    <p><?= e($variant['category_name']) ?> &middot; <?= e($variant['product_name']) ?> &middot; <?= e($variant['variant_name']) ?></p>
                </div>
                <div class="product-page__actions">
                    <a class="btn btn--ghost" href="<?= url('/products') ?>">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Kembali
                    </a>
                    <button type="button" class="btn btn--primary" id="btnTambahOpsi">
                        <i class="fas fa-plus" aria-hidden="true"></i> Tambah Opsi
                    </button>
                </div>
            </div>

            <div class="product-wrapper scroll-thin">
                <?php if (empty($options)): ?>
                    <div class="empty-state empty-state--bare">
                        <i class="fas fa-layer-group empty-state__icon" aria-hidden="true"></i>
                        <h2 class="empty-state__title">Belum ada opsi varian</h2>
                        <p class="empty-state__desc">Tambahkan kombinasi ukuran, warna, dan tipe lengan untuk varian ini.</p>
                    </div>
                <?php else: ?>
                    <div class="stok-table-wrap">
                        <table class="stok-table variant-options-table">
                            <thead>
                                <tr>
                                    <th>Ukuran</th>
                                    <th>Warna</th>
                                    <th>Tipe Lengan</th>
                                    <th>Qty</th>
                                    <th>+Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($options as $option): ?>
                                    <?php $active = (int) ($option['is_active'] ?? 1) === 1; ?>
                                    <tr class="<?= $active ? '' : 'variant-options-table__row--inactive' ?>">
                                        <td><?= e($option['size_name']) ?></td>
                                        <td><?= e($option['color_name']) ?></td>
                                        <td><?= e($option['sleeve_type'] === Model::SLEEVE_LONG ? 'Lengan Panjang' : 'Lengan Pendek') ?></td>
                                        <td><?= e((string) (int) $option['quantity']) ?></td>
                                        <td><?= e(format_currency((float) $option['price_surcharge'])) ?></td>
                                        <td>
                                            <span class="status-pill <?= $active ? 'status-pill--success' : 'status-pill--muted' ?>">
                                                <?= $active ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="master-list__actions">
                                                <button type="button"
                                                        class="btn btn--ghost btn--sm"
                                                        data-action="edit-option"
                                                        data-option='<?= e(json_encode($option)) ?>'>
                                                    <i class="fas fa-pen" aria-hidden="true"></i> Edit
                                                </button>
                                                <button type="button"
                                                        class="btn <?= $active ? 'btn--danger' : 'btn--primary' ?> btn--sm"
                                                        data-action="toggle-option"
                                                        data-option-id="<?= e($option['option_id']) ?>"
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

<div class="modal-overlay" id="modalOpsi" role="dialog" aria-modal="true" aria-labelledby="modalOpsiTitle" aria-hidden="true">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title" id="modalOpsiTitle">Tambah Opsi Varian</h3>
            <button type="button" class="modal__close" data-modal-close aria-label="Tutup dialog">&times;</button>
        </div>
        <div class="modal__body">
            <input type="hidden" id="f_option_id" value="">

            <div class="form-field">
                <label class="form-field__label" for="f_size_id">Ukuran</label>
                <select id="f_size_id" class="form-control">
                    <?php foreach ($sizes as $size): ?>
                        <option value="<?= e($size['size_id']) ?>"><?= e($size['size_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_color_id">Warna</label>
                <select id="f_color_id" class="form-control">
                    <?php foreach ($colors as $color): ?>
                        <option value="<?= e($color['color_id']) ?>"><?= e($color['color_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_sleeve_type">Tipe Lengan</label>
                <select id="f_sleeve_type" class="form-control">
                    <option value="<?= e(Model::SLEEVE_SHORT) ?>">Lengan Pendek</option>
                    <option value="<?= e(Model::SLEEVE_LONG) ?>">Lengan Panjang</option>
                </select>
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_quantity">Quantity</label>
                <input id="f_quantity" class="form-control" type="number" min="1" step="1" value="1">
            </div>

            <div class="form-field">
                <label class="form-field__label" for="f_price_surcharge">Price Surcharge (Rp)</label>
                <input id="f_price_surcharge" class="form-control" type="number" min="0" step="1000" value="0">
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--ghost" data-modal-close>Batal</button>
            <button type="button" class="btn btn--primary" id="btnSimpanOpsi">Simpan</button>
        </div>
    </div>
</div>

<script>
const VARIANT_ID = <?= json_encode($variantId) ?>;
const ENDPOINTS = window.ENDPOINTS = {
    store: '<?= url('/products/variant-options/store') ?>',
    update: '<?= url('/products/variant-options/update') ?>',
    toggle: '<?= url('/products/variant-options/toggle') ?>',
};
</script>
<script src="<?= asset('js/ui.js') ?>"></script>
<script>
(function () {
    const ui = window.SiblingsUI;
    const modal = document.getElementById('modalOpsi');
    const title = document.getElementById('modalOpsiTitle');
    const optionId = document.getElementById('f_option_id');
    const sizeId = document.getElementById('f_size_id');
    const colorId = document.getElementById('f_color_id');
    const sleeveType = document.getElementById('f_sleeve_type');
    const quantity = document.getElementById('f_quantity');
    const priceSurcharge = document.getElementById('f_price_surcharge');
    const saveBtn = document.getElementById('btnSimpanOpsi');

    document.getElementById('btnTambahOpsi')?.addEventListener('click', () => {
        title.textContent = 'Tambah Opsi Varian';
        modal.dataset.mode = 'create';
        optionId.value = '';
        quantity.value = '1';
        priceSurcharge.value = '0';
        ui.openModal('modalOpsi');
    });

    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest('[data-action]');
        if (!trigger) return;

        if (trigger.dataset.action === 'edit-option') {
            const option = JSON.parse(trigger.dataset.option || '{}');
            title.textContent = 'Edit Opsi Varian';
            modal.dataset.mode = 'edit';
            optionId.value = option.option_id || '';
            sizeId.value = option.size_id || '';
            colorId.value = option.color_id || '';
            sleeveType.value = option.sleeve_type || 'short';
            quantity.value = option.quantity || 0;
            priceSurcharge.value = option.price_surcharge || 0;
            ui.openModal('modalOpsi');
        }

        if (trigger.dataset.action === 'toggle-option') {
            const active = trigger.dataset.active === '1';
            const ok = await ui.confirm({
                title: active ? 'Nonaktifkan opsi?' : 'Aktifkan opsi?',
                message: active ? 'Opsi ini tidak akan muncul di form pemesanan kasir.' : 'Opsi ini akan tersedia kembali untuk pemesanan.',
                confirmText: active ? 'Nonaktifkan' : 'Aktifkan',
                variant: active ? 'danger' : 'primary',
            });
            if (!ok) return;

            const response = await postJson(ENDPOINTS.toggle, {
                option_id: parseInt(trigger.dataset.optionId, 10),
                active: !active,
            });
            if (response?.success) {
                ui.toast(active ? 'Opsi dinonaktifkan.' : 'Opsi diaktifkan.', 'success');
                location.reload();
            } else {
                ui.toast(response?.message || 'Gagal mengubah status.', 'danger');
            }
        }
    });

    saveBtn?.addEventListener('click', async () => {
        const payload = {
            variant_id: VARIANT_ID,
            size_id: parseInt(sizeId.value, 10),
            color_id: parseInt(colorId.value, 10),
            sleeve_type: sleeveType.value,
            quantity: parseInt(quantity.value || '0', 10),
            price_surcharge: parseFloat(priceSurcharge.value || '0'),
        };
        if (!Number.isInteger(payload.quantity) || payload.quantity < 1 || payload.price_surcharge < 0) {
            ui.toast('Quantity harus minimal 1 dan surcharge tidak boleh negatif.', 'warning');
            return;
        }

        const endpoint = modal.dataset.mode === 'edit' ? ENDPOINTS.update : ENDPOINTS.store;
        if (modal.dataset.mode === 'edit') payload.option_id = parseInt(optionId.value, 10);

        await ui.withLoading(saveBtn, async () => {
            const response = await postJson(endpoint, payload);
            if (response?.success) {
                ui.toast('Opsi varian tersimpan.', 'success');
                ui.closeModal('modalOpsi');
                location.reload();
            } else {
                ui.toast(response?.message || 'Gagal menyimpan opsi.', 'danger');
            }
        });
    });

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
