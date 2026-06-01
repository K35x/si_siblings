(function () {
    'use strict';

    const endpoint = window.TRANSACTION_DYNAMIC_OPTIONS_ENDPOINT;
    if (!endpoint) return;

    const paketBahan = document.getElementById('paketBahan');
    const variantIdInput = document.getElementById('variantId');
    if (!paketBahan || !variantIdInput) return;

    async function loadOptions(variantId) {
        if (!variantId) {
            applyOptions([]);
            return;
        }

        try {
            const response = await fetch(endpoint + '?variant_id=' + encodeURIComponent(variantId), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            const data = await response.json();
            applyOptions(data.options || []);
        } catch (error) {
            applyOptions([]);
        }
    }

    function applyOptions(options) {
        const surcharges = new Map();

        options.forEach((option) => {
            surcharges.set(String(option.size_id), Math.max(surcharges.get(String(option.size_id)) || 0, parseFloat(option.price_surcharge || 0)));
        });

        document.querySelectorAll('.size-table tbody tr[data-size-id]').forEach((row) => {
            const sizeId = row.dataset.sizeId;
            const surcharge = surcharges.get(String(sizeId)) || 0;
            row.querySelectorAll('.qty-input').forEach((input) => {
                input.dataset.surcharge = String(surcharge);
            });
            row.hidden = false;
        });

        document.querySelectorAll('.size-table select[data-sleeve-type]').forEach((select) => {
            Array.from(select.options).forEach((option) => {
                option.hidden = false;
            });
        });
    }

    function selectedVariantId() {
        const option = paketBahan.options[paketBahan.selectedIndex];
        return option?.getAttribute('data-variant-id') || variantIdInput.value || '';
    }

    paketBahan.addEventListener('change', () => loadOptions(selectedVariantId()));
    loadOptions(selectedVariantId());
})();
