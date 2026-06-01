(function () {
    'use strict';

    var MAX_DESIGN_SIZE = 5 * 1024 * 1024;
    var ALLOWED_DESIGN_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

    function toInt(value) {
        if (value === null || value === undefined || value === '') return 0;
        var parsed = parseInt(value, 10);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function escapeSelector(value) {
        if (window.CSS && typeof window.CSS.escape === 'function') {
            return window.CSS.escape(value);
        }
        return String(value).replace(/[^a-zA-Z0-9_-]/g, '\\$&');
    }

    function fieldWrapper(field) {
        if (!field) return null;
        return field.closest('.form-field') || field.closest('.upload-item') || field.closest('td') || field.parentElement;
    }

    function errorId(field) {
        if (!field.id) {
            field.id = 'tx-field-' + Math.random().toString(36).slice(2);
        }
        return field.id + '-client-error';
    }

    function setFieldError(field, message) {
        if (!field) return;
        var wrapper = fieldWrapper(field);
        if (!wrapper) return;

        wrapper.classList.add('has-error', 'client-has-error');
        field.setAttribute('aria-invalid', 'true');

        var id = errorId(field);
        var error = wrapper.querySelector('[data-client-error-for="' + escapeSelector(field.id) + '"]');
        if (!error) {
            error = document.createElement('span');
            error.className = 'form-field__error client-field-error';
            error.dataset.clientErrorFor = field.id;
            error.id = id;
            wrapper.appendChild(error);
        }
        error.textContent = message;
        error.style.display = 'block';

        var describedBy = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean);
        if (describedBy.indexOf(id) === -1) {
            describedBy.push(id);
            field.setAttribute('aria-describedby', describedBy.join(' '));
        }
    }

    function clearFieldError(field) {
        if (!field) return;
        var wrapper = fieldWrapper(field);
        if (!wrapper) return;

        field.removeAttribute('aria-invalid');
        var id = field.id ? field.id + '-client-error' : '';
        if (id) {
            var describedBy = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean)
                .filter(function (item) { return item !== id; });
            if (describedBy.length) {
                field.setAttribute('aria-describedby', describedBy.join(' '));
            } else {
                field.removeAttribute('aria-describedby');
            }
        }

        var error = field.id ? wrapper.querySelector('[data-client-error-for="' + escapeSelector(field.id) + '"]') : null;
        if (error) error.remove();

        if (!wrapper.querySelector('.client-field-error')) {
            wrapper.classList.remove('has-error', 'client-has-error');
        }
    }

    function showSummary(form, errors) {
        var live = form.querySelector('[data-form-errors]');
        if (!live) return;
        live.textContent = errors.length ? errors.map(function (item) { return item.message; }).join(' ') : '';
    }

    function fieldByName(form, name) {
        return Array.from(form.querySelectorAll('[name]')).find(function (field) {
            return field.name === name;
        }) || null;
    }

    function validateVariant(form, errors) {
        var variantId = form.querySelector('[name="variant_id"]');
        var variantSelector = form.querySelector('#bahanKain, #bahanBaju, select#paketBahan') || form.querySelector('#paketBahan');
        var value = variantId ? toInt(variantId.value) : 0;

        if (variantSelector && !variantSelector.value) {
            setFieldError(variantSelector, 'Pilih varian atau bahan produk terlebih dahulu.');
            errors.push({ field: variantSelector, message: 'Pilih varian atau bahan produk terlebih dahulu.' });
            return;
        }

        if (value <= 0 && variantSelector) {
            setFieldError(variantSelector, 'Varian produk belum valid. Pilih ulang bahan/varian.');
            errors.push({ field: variantSelector, message: 'Varian produk belum valid. Pilih ulang bahan/varian.' });
            return;
        }

        clearFieldError(variantSelector);
    }

    function validateNonNegativeInteger(field, label, errors) {
        if (!field) return;
        var value = field.value.trim();
        if (value !== '' && !/^\d+$/.test(value)) {
            setFieldError(field, label + ' harus berupa angka 0 atau lebih.');
            errors.push({ field: field, message: label + ' harus berupa angka 0 atau lebih.' });
            return;
        }
        clearFieldError(field);
    }

    function qtyPairs(form) {
        return Array.from(form.querySelectorAll('.qty-input')).map(function (qty) {
            var match = qty.name.match(/^quantity_(short|long)_(.+)$/);
            if (!match) return null;
            var sleeve = match[1];
            var size = match[2];
            var color = fieldByName(form, 'warna_' + sleeve + '_' + size);
            return { qty: qty, sleeve: sleeve, size: size, color: color };
        }).filter(Boolean);
    }

    function labelSleeve(sleeve) {
        return sleeve === 'long' ? 'lengan panjang' : 'lengan pendek';
    }

    function validateQuantitiesAndColors(form, errors) {
        var totalQty = 0;

        qtyPairs(form).forEach(function (pair) {
            validateNonNegativeInteger(pair.qty, 'Qty ' + labelSleeve(pair.sleeve) + ' ukuran ' + pair.size, errors);
            var qtyValue = toInt(pair.qty.value);
            totalQty += qtyValue;

            if (qtyValue > 0) {
                if (!pair.color || !pair.color.value) {
                    setFieldError(pair.color || pair.qty, 'Warna ' + labelSleeve(pair.sleeve) + ' ukuran ' + pair.size + ' wajib dipilih.');
                    errors.push({ field: pair.color || pair.qty, message: 'Warna ' + labelSleeve(pair.sleeve) + ' ukuran ' + pair.size + ' wajib dipilih.' });
                    return;
                }

                if (pair.color.value === '__custom') {
                    var custom = fieldByName(form, 'custom_warna_' + pair.sleeve + '_' + pair.size);
                    if (!custom || custom.value.trim() === '') {
                        setFieldError(custom || pair.color, 'Warna custom ' + labelSleeve(pair.sleeve) + ' ukuran ' + pair.size + ' wajib diisi.');
                        errors.push({ field: custom || pair.color, message: 'Warna custom ' + labelSleeve(pair.sleeve) + ' ukuran ' + pair.size + ' wajib diisi.' });
                        return;
                    }
                    clearFieldError(custom);
                }

                clearFieldError(pair.color);
            } else if (pair.color) {
                clearFieldError(pair.color);
            }
        });

        if (totalQty < 1) {
            var firstQty = form.querySelector('.qty-input');
            setFieldError(firstQty, 'Minimal 1 ukuran harus diisi.');
            errors.push({ field: firstQty, message: 'Minimal 1 ukuran harus diisi.' });
        }
    }

    function validateDesignFiles(form, errors) {
        Array.from(form.querySelectorAll('input[type="file"][name^="desain_"]')).forEach(function (field) {
            clearFieldError(field);
            Array.from(field.files || []).forEach(function (file) {
                var name = file.name || '';
                var ext = name.indexOf('.') >= 0 ? name.split('.').pop().toLowerCase() : '';
                if (ALLOWED_DESIGN_EXTENSIONS.indexOf(ext) === -1) {
                    setFieldError(field, 'File desain harus jpg, jpeg, png, webp, atau pdf.');
                    errors.push({ field: field, message: 'File desain harus jpg, jpeg, png, webp, atau pdf.' });
                    return;
                }
                if (file.size > MAX_DESIGN_SIZE) {
                    setFieldError(field, 'Ukuran file desain maksimal 5 MB.');
                    errors.push({ field: field, message: 'Ukuran file desain maksimal 5 MB.' });
                }
            });
        });
    }

    function validateForm(form) {
        var errors = [];
        validateVariant(form, errors);
        validateNonNegativeInteger(form.querySelector('[name="sablon_price"]'), 'Harga sablon', errors);
        validateQuantitiesAndColors(form, errors);
        validateDesignFiles(form, errors);
        showSummary(form, errors);
        return errors;
    }

    function focusFirstError(errors) {
        var first = errors.find(function (item) { return item.field && typeof item.field.focus === 'function'; });
        if (!first) return;
        first.field.focus({ preventScroll: true });
        first.field.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function bindForm(form) {
        if (!form || form.dataset.clientValidationReady === '1') return;
        form.dataset.clientValidationReady = '1';
        form.setAttribute('novalidate', 'novalidate');

        form.addEventListener('submit', function (event) {
            var errors = validateForm(form);
            if (errors.length === 0) return;
            event.preventDefault();
            window.SiblingsUI?.toast?.('Periksa kembali input pesanan.', 'warning');
            focusFirstError(errors);
        });

        form.addEventListener('input', function (event) {
            if (event.target.matches('.qty-input, [name="sablon_price"], [name^="custom_warna_"], input[type="file"][name^="desain_"]')) {
                validateForm(form);
            }
        });

        form.addEventListener('change', function (event) {
            if (event.target.matches('#paketBahan, #bahanKain, #bahanBaju, [name^="warna_"], input[type="file"][name^="desain_"]')) {
                validateForm(form);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[action*="/transactions/cart"]').forEach(bindForm);
    });

    window.TransactionFormValidation = {
        validateForm: validateForm,
    };
})();
