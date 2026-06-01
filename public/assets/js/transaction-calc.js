/**
 * Shared transaction form calculator.
 * Provides reusable helpers for price calculation and submit state management.
 *
 * Usage in form pages:
 *   <script src="<?= asset('js/transaction-calc.js') ?>"></script>
 *   <script>
 *     TransactionCalc.init({
 *       basePrice: () => parseInt(document.getElementById('paketBahan').value, 10) || 0,
 *       longSleeveSurcharge: 5000,
 *       minQty: 24,
 *       addons: () => 0,          // optional
 *       submitCondition: null,     // optional custom valid check
 *     });
 *   </script>
 */
window.TransactionCalc = (function () {
    /**
     * Compute total qty and price from all size inputs.
     * @param {Object} opts
     * @param {number} opts.base - Price per piece
     * @param {number} opts.longSleeve - Long sleeve surcharge per piece
     * @param {Function} [opts.addonCalc] - Returns extra total (e.g. kaos kaki)
     * @returns {{ qty: number, total: number }}
     */
    function compute(opts) {
        let qty = 0;
        let total = 0;

        document.querySelectorAll('.qty-input.short').forEach(function (input) {
            var q = parseInt(input.value, 10) || 0;
            var extra = parseInt(input.dataset.surcharge, 10) || 0;
            qty += q;
            total += q * (opts.base + extra);
        });

        document.querySelectorAll('.qty-input.long').forEach(function (input) {
            var q = parseInt(input.value, 10) || 0;
            var extra = parseInt(input.dataset.surcharge, 10) || 0;
            qty += q;
            total += q * (opts.base + extra + opts.longSleeve);
        });

        if (opts.addonCalc) {
            total += opts.addonCalc();
        }

        return { qty: qty, total: total };
    }

    /**
     * Update DOM elements with computed values and manage submit state.
     * @param {Object} cfg
     * @param {Function} cfg.basePrice - Returns current base price
     * @param {number} cfg.longSleeveSurcharge - Surcharge per long sleeve piece
     * @param {number} [cfg.minQty=0] - Minimum qty to enable submit
     * @param {Function} [cfg.addonCalc] - Returns extra total
     * @param {Function} [cfg.submitCondition] - Custom (qty, base) => boolean
     * @param {Function} [cfg.onCalc] - Callback after calculation (for extra UI updates)
     */
    function init(cfg) {
        var totalQtyEl = document.getElementById('totalQty');
        var totalHargaEl = document.getElementById('totalHarga');
        var submitBtn = document.getElementById('btnSubmit');
        var minQty = cfg.minQty || 0;

        function calc() {
            var base = cfg.basePrice();
            var result = compute({
                base: base,
                longSleeve: cfg.longSleeveSurcharge || 0,
                addonCalc: cfg.addonCalc || null,
            });

            if (totalQtyEl) {
                var qtyText = result.qty.toLocaleString('id-ID');
                totalQtyEl.textContent = minQty > 0
                    ? qtyText + ' / ' + minQty
                    : qtyText;
            }
            if (totalHargaEl) {
                totalHargaEl.textContent = 'Rp ' + result.total.toLocaleString('id-ID');
            }

            var valid;
            if (cfg.submitCondition) {
                valid = cfg.submitCondition(result.qty, base);
            } else {
                valid = result.qty >= minQty && base > 0;
            }

            if (submitBtn) {
                submitBtn.disabled = !valid;
                submitBtn.setAttribute('aria-disabled', String(!valid));
            }

            if (cfg.onCalc) {
                cfg.onCalc(result, base);
            }
        }

        // Bind events
        document.querySelectorAll('.qty-input').forEach(function (el) {
            el.addEventListener('input', calc);
        });

        function validationMessage(input) {
            if (input.classList.contains('qty-input')) return 'Jumlah harus bilangan bulat';
            return 'Harga harus bilangan bulat';
        }

        function validateIntegerInput(event) {
            var value = event.target.value;
            if (value === '' || /^\d+$/.test(value)) return true;
            window.SiblingsUI?.toast?.(validationMessage(event.target), 'warning');
            event.target.focus();
            return false;
        }

        document.querySelectorAll('.qty-input, input[name="sablon_price"], #hargaSablon').forEach(function (el) {
            el.addEventListener('input', function (event) {
                validateIntegerInput(event);
            });
        });

        if (submitBtn) {
            submitBtn.closest('form')?.addEventListener('submit', function (event) {
                var invalid = Array.from(document.querySelectorAll('.qty-input, input[name="sablon_price"], #hargaSablon'))
                    .find(function (input) { return input.value !== '' && !/^\d+$/.test(input.value); });
                if (invalid) {
                    event.preventDefault();
                    window.SiblingsUI?.toast?.(validationMessage(invalid), 'warning');
                    invalid.focus();
                    return;
                }

                var base = cfg.basePrice();
                var result = compute({
                    base: base,
                    longSleeve: cfg.longSleeveSurcharge || 0,
                    addonCalc: cfg.addonCalc || null,
                });
                if (minQty > 0 && result.qty > 0 && result.qty < minQty && !confirm('Minimal pemesanan ' + minQty + ' pcs. Lanjutkan?')) {
                    event.preventDefault();
                }
            });
        }

        // Return calc function for external trigger (e.g. on select change)
        return calc;
    }

    return { init: init, compute: compute };
})();
