/**
 * Siblings.co — Products page (stok management) interactions.
 * Tergantung pada window.SiblingsUI (ui.js).
 */
(function () {
    "use strict";

    const ui = window.SiblingsUI;
    const DEFAULT_MINIMUM_ORDER = 24;

    // ============================================================
    // Accordion toggles
    // ============================================================
    document.addEventListener("click", (e) => {
        const trigger = e.target.closest("[data-toggle]");
        if (!trigger) return;
        if (e.target.closest("[data-action], input, .btn-delete, .kain__actions-inline")) return;

        const type = trigger.dataset.toggle;
        const isOpen = trigger.getAttribute("aria-expanded") === "true";
        const next = !isOpen;
        trigger.setAttribute("aria-expanded", String(next));

        if (type === "kategori") {
            const item = trigger.closest(".kategori-item");
            item.classList.toggle("active", next);
        } else if (type === "kain") {
            const kain = trigger.closest(".kain");
            kain.classList.toggle("active", next);
            const opts = kain.querySelector(".kain__options");
            if (opts) opts.hidden = !next;
        }
    });

    // ============================================================
    // Filter kategori
    // ============================================================
    const filterEl = document.getElementById("filterKategori");
    if (filterEl) {
        filterEl.addEventListener("change", (e) => {
            const cid = e.target.value;
            document.querySelectorAll(".kategori-item").forEach((item) => {
                item.style.display = !cid || item.dataset.categoryId === cid ? "" : "none";
            });
        });
    }

    // ============================================================
    // Modal Form (tambah & edit)
    // ============================================================
    const modal = document.getElementById("modalForm");
    const titleEl = document.getElementById("modalFormTitle");
    const optList = document.getElementById("f_options_list");

    function openTambah() {
        if (!modal) return;
        titleEl.textContent = "Tambah Varian";
        modal.dataset.mode = "create";
        document.getElementById("f_variant_id").value = "";
        document.getElementById("f_category_field").style.display = "";
        document.getElementById("f_nama_produk_field").style.display = "";
        document.getElementById("f_minimum_order_field").style.display = "";
        document.getElementById("f_minimum_order").value = DEFAULT_MINIMUM_ORDER;
        ["f_nama_varian", "f_material", "f_harga"].forEach((id) => {
            document.getElementById(id).value = "";
        });
        document.getElementById("f_sleeve_price").value = "5000";
        document.getElementById("f_nama_produk_new").style.display = "none";
        document.getElementById("f_nama_produk_new").value = "";
        const catSelect = document.getElementById("f_category_id");
        if (!catSelect.value && catSelect.options.length > 1) {
            catSelect.selectedIndex = 1;
        }
        filterProducts();
        buildSleeveTabs();
        addOptionRow("short");
        switchSleeveTab("short");
        ui.openModal("modalForm");
    }

    // Filter products by category (from pre-loaded data)
    const categorySelect = document.getElementById("f_category_id");
    const produkSelect = document.getElementById("f_nama_produk");
    const produkNewInput = document.getElementById("f_nama_produk_new");
    
    function filterProducts() {
        const catId = parseInt(categorySelect.value, 10);
        if (!catId) return;

        // Clear options except first two
        while (produkSelect.options.length > 2) {
            produkSelect.remove(2);
        }

        // Filter from pre-loaded PRODUCTS data
        (window.PRODUCTS || [])
            .filter(p => parseInt(p.category_id, 10) === catId)
            .sort((a, b) => a.product_name.localeCompare(b.product_name))
            .forEach(p => {
                const opt = document.createElement("option");
                opt.value = p.product_name;
                opt.textContent = p.product_name;
                produkSelect.appendChild(opt);
            });

        // Reset selection
        produkSelect.value = "";
        produkNewInput.style.display = "none";
        produkNewInput.value = "";
    }
    
    categorySelect?.addEventListener("change", filterProducts);
    
    produkSelect?.addEventListener("change", function() {
        produkNewInput.style.display = this.value === "__new__" ? "block" : "none";
        if (this.value !== "__new__") {
            produkNewInput.value = "";
        }
    });
    
    // Initial filter
    filterProducts();

    function openEdit(varian, optionMap) {
        if (!modal) return;
        titleEl.textContent = "Edit Varian";
        modal.dataset.mode = "edit";
        document.getElementById("f_variant_id").value = varian.variant_id;
        document.getElementById("f_category_field").style.display = "none";
        document.getElementById("f_nama_produk_field").style.display = "none";
        document.getElementById("f_minimum_order_field").style.display = "none";
        document.getElementById("f_nama_varian").value = varian.variant_name || "";
        document.getElementById("f_material").value = varian.material || "";
        document.getElementById("f_harga").value = varian.price || "";
        document.getElementById("f_sleeve_price").value = varian.sleeve_price || 5000;

        buildSleeveTabs(sizeSurchargesFromOptions(optionMap));
        let hasRow = false;
        for (const [sizeId, colorMap] of Object.entries(optionMap || {})) {
            for (const [colorId, sleeveMap] of Object.entries(colorMap)) {
                for (const [tipe, optData] of Object.entries(sleeveMap || {})) {
                    const qty = (optData && optData.quantity != null) ? parseInt(optData.quantity, 10) : 1;
                    addOptionRow(tipe, parseInt(sizeId, 10), parseInt(colorId, 10), qty);
                    hasRow = true;
                }
            }
        }
        if (!hasRow) addOptionRow("short");
        switchSleeveTab("short");
        ui.openModal("modalForm");
    }

    function sizeSurchargesFromOptions(optionMap) {
        const sizeSurcharges = {};
        for (const [sizeId, colorMap] of Object.entries(optionMap || {})) {
            for (const sleeveMap of Object.values(colorMap || {})) {
                for (const optData of Object.values(sleeveMap || {})) {
                    const surcharge = (optData && optData.price_surcharge != null) ? parseFloat(optData.price_surcharge) : 0;
                    if (surcharge > (sizeSurcharges[sizeId] || 0)) {
                        sizeSurcharges[sizeId] = surcharge;
                    }
                }
            }
        }
        return sizeSurcharges;
    }

    function buildSizeSurchargeSection(sizeSurcharges = {}) {
        const section = document.createElement("div");
        section.className = "f-size-surcharges";

        const title = document.createElement("div");
        title.className = "f-size-surcharges__title";
        title.textContent = "Biaya tambahan ukuran";

        const desc = document.createElement("p");
        desc.className = "f-size-surcharges__desc";
        desc.textContent = "Nominal ini flat per ukuran di varian ini, berlaku untuk semua warna dan tipe lengan.";

        const grid = document.createElement("div");
        grid.className = "f-size-surcharges__grid";

        SIZES.forEach((s) => {
            const label = document.createElement("label");
            label.className = "f-size-surcharge";

            const name = document.createElement("span");
            name.textContent = s.size_name;

            const input = document.createElement("input");
            input.type = "number";
            input.min = 0;
            input.step = 1000;
            input.placeholder = "0";
            input.inputMode = "numeric";
            input.className = "form-control tabular-nums";
            input.dataset.sizeSurcharge = s.size_id;
            input.value = sizeSurcharges[s.size_id] || "";
            input.setAttribute("aria-label", `Biaya tambahan ukuran ${s.size_name}`);

            label.append(name, input);
            grid.appendChild(label);
        });

        section.append(title, desc, grid);
        return section;
    }

    function buildSleeveTabs(sizeSurcharges = {}) {
        optList.innerHTML = "";
        optList.appendChild(buildSizeSurchargeSection(sizeSurcharges));
        optList.insertAdjacentHTML("beforeend", `
            <div class="f-sleeve-tabs">
                <button type="button" class="f-sleeve-tab active" data-sleeve="short">Lengan Pendek</button>
                <button type="button" class="f-sleeve-tab" data-sleeve="long">Lengan Panjang</button>
            </div>
            <div class="f-sleeve-panel" data-sleeve="short">
                <div class="f-options-header">
                    <span>Ukuran</span><span>Warna</span><span>Qty</span><span></span>
                </div>
            </div>
            <div class="f-sleeve-panel" data-sleeve="long" hidden>
                <div class="f-options-header">
                    <span>Ukuran</span><span>Warna</span><span>Qty</span><span></span>
                </div>
            </div>
        `);
        optList.querySelectorAll(".f-sleeve-tab").forEach(tab => {
            tab.addEventListener("click", () => switchSleeveTab(tab.dataset.sleeve));
        });
    }

    function switchSleeveTab(sleeve) {
        optList.querySelectorAll(".f-sleeve-tab").forEach(t => t.classList.toggle("active", t.dataset.sleeve === sleeve));
        optList.querySelectorAll(".f-sleeve-panel").forEach(p => p.hidden = p.dataset.sleeve !== sleeve);
    }

    function getActiveSleeve() {
        return optList.querySelector(".f-sleeve-tab.active")?.dataset.sleeve || "short";
    }

    function getActivePanel() {
        return optList.querySelector(`.f-sleeve-panel[data-sleeve="${getActiveSleeve()}"]`);
    }

    function addOptionRow(sleeve = null, selectedSize = null, selectedColor = null, qty = 1) {
        const targetSleeve = sleeve || getActiveSleeve();
        const panel = optList.querySelector(`.f-sleeve-panel[data-sleeve="${targetSleeve}"]`);
        if (!panel) return;

        const row = document.createElement("div");
        row.className = "f-options-row";

        const sizeSelect = document.createElement("select");
        sizeSelect.className = "form-control";
        sizeSelect.setAttribute("aria-label", "Ukuran");
        SIZES.forEach((s) => {
            const opt = document.createElement("option");
            opt.value = s.size_id;
            opt.textContent = s.size_name;
            if (selectedSize && s.size_id == selectedSize) opt.selected = true;
            sizeSelect.appendChild(opt);
        });

        const colorSelect = document.createElement("select");
        colorSelect.className = "form-control";
        colorSelect.setAttribute("aria-label", "Warna");
        COLORS.forEach((c) => {
            const opt = document.createElement("option");
            opt.value = c.color_id;
            opt.textContent = c.color_name;
            if (selectedColor && c.color_id == selectedColor) opt.selected = true;
            colorSelect.appendChild(opt);
        });

        const qtyInput = document.createElement("input");
        qtyInput.type = "number";
        qtyInput.min = 1;
        qtyInput.value = qty;
        qtyInput.inputMode = "numeric";
        qtyInput.className = "form-control tabular-nums f-options-row__qty";
        qtyInput.setAttribute("aria-label", "Jumlah");

        const btnRemove = document.createElement("button");
        btnRemove.type = "button";
        btnRemove.className = "btn btn--icon btn--danger-soft f-options-row__remove";
        btnRemove.setAttribute("aria-label", "Hapus baris");
        btnRemove.innerHTML = '<i class="fas fa-times" aria-hidden="true"></i>';
        btnRemove.addEventListener("click", () => row.remove());

        row.append(sizeSelect, colorSelect, qtyInput, btnRemove);
        panel.appendChild(row);
    }

    function collectSizeSurcharges() {
        const sizeSurcharges = {};
        optList.querySelectorAll("[data-size-surcharge]").forEach(input => {
            sizeSurcharges[input.dataset.sizeSurcharge] = parseFloat(input.value || "0") || 0;
        });
        return sizeSurcharges;
    }

    function collectOptions() {
        const opts = [];
        const sizeSurcharges = collectSizeSurcharges();
        optList.querySelectorAll(".f-sleeve-panel").forEach(panel => {
            const sleeve = panel.dataset.sleeve;
            panel.querySelectorAll(".f-options-row").forEach(row => {
                const selects = row.querySelectorAll("select");
                const numberInputs = row.querySelectorAll('input[type="number"]');
                if (selects.length >= 2) {
                    const sizeId = parseInt(selects[0].value, 10);
                    opts.push({
                        sleeve_type: sleeve,
                        size_id: sizeId,
                        color_id: parseInt(selects[1].value, 10),
                        quantity: parseInt(numberInputs[0]?.value || "1", 10) || 1,
                        price_surcharge: sizeSurcharges[sizeId] || 0,
                    });
                }
            });
        });
        return opts;
    }

    document.getElementById("btnTambah")?.addEventListener("click", openTambah);
    document.getElementById("btnTambahEmpty")?.addEventListener("click", openTambah);
    document.getElementById("btnAddCombination")?.addEventListener("click", () => addOptionRow(getActiveSleeve()));

    // Edit & status actions (event delegation)
    document.addEventListener("click", async (e) => {
        const trigger = e.target.closest("[data-action]");
        if (!trigger) return;

        const action = trigger.dataset.action;

        if (action === "edit-variant") {
            try {
                const varian = JSON.parse(trigger.dataset.variant);
                const optionMap = JSON.parse(trigger.dataset.options || "{}");
                openEdit(varian, optionMap);
            } catch (err) {
                ui.toast("Gagal membaca data varian.", "danger");
            }
        }

        if (action === "toggle-variant") {
            const isActive = trigger.dataset.active === "1";
            const variantId = parseInt(trigger.dataset.variantId, 10);
            const variantName = trigger.dataset.variantName;

            const ok = await ui.confirm({
                title: isActive ? "Nonaktifkan varian?" : "Aktifkan varian?",
                message: isActive
                    ? `Varian "${variantName}" akan dinonaktifkan dan tidak bisa dipesan.`
                    : `Varian "${variantName}" akan diaktifkan kembali.`,
                confirmText: isActive ? "Nonaktifkan" : "Aktifkan",
                variant: isActive ? "danger" : "primary",
            });
            if (!ok) return;

            const kainEl = trigger.closest(".kain");
            kainEl.style.opacity = "0.6";

            const res = await postJson(window.ENDPOINTS.toggleActive, {
                type: "variant",
                id: variantId,
                active: !isActive,
            });

            if (res?.success) {
                trigger.dataset.active = isActive ? "0" : "1";
                trigger.setAttribute("aria-label", isActive ? `Aktifkan varian ${variantName}` : `Nonaktifkan varian ${variantName}`);
                kainEl.style.opacity = "";
                kainEl.classList.toggle("kain--inactive", isActive);
                ui.toast(isActive ? "Varian dinonaktifkan." : "Varian diaktifkan kembali.", "success");
            } else {
                kainEl.style.opacity = "";
                ui.toast(res?.message || "Gagal mengubah status.", "danger");
            }
        }

        if (action === "delete-option") {
            const ok = await ui.confirm({
                title: "Hapus ukuran?",
                message: "Ukuran ini akan dihapus dari warna.",
                confirmText: "Hapus",
                variant: "danger",
            });
            if (!ok) return;

            const td = trigger.closest("td");
            const res = await postJson(window.ENDPOINTS.destroyOption, { option_id: parseInt(trigger.dataset.optionId, 10) });
            if (res?.success) {
                td.innerHTML = '<span class="stok-table__empty">-</span>';
                ui.toast("Ukuran dihapus.", "success");
            } else {
                ui.toast(res?.message || "Gagal menghapus.", "danger");
            }
        }

        if (action === "toggle-option") {
            const optionId = parseInt(trigger.dataset.optionId, 10);
            const active = trigger.dataset.active === "1";
            const actionText = active ? "nonaktifkan" : "aktifkan";

            const ok = await ui.confirm({
                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} ukuran?`,
                message: `Ukuran ini akan ${actionText}.`,
                confirmText: actionText.charAt(0).toUpperCase() + actionText.slice(1),
                variant: active ? "warning" : "success",
            });
            if (!ok) return;

            const res = await postJson(window.ENDPOINTS.toggleOptionStatus, { option_id: optionId, active: !active });
            if (res?.success) {
                trigger.dataset.active = active ? "0" : "1";
                trigger.innerHTML = active
                    ? '<i class="fas fa-toggle-off" aria-hidden="true"></i>'
                    : '<i class="fas fa-toggle-on" aria-hidden="true"></i>';
                trigger.setAttribute("aria-label", active ? "Aktifkan ukuran" : "Nonaktifkan ukuran");
                const chip = trigger.closest(".stok-table__sleeve-chip");
                if (chip) chip.classList.toggle("stok-table__sleeve-chip--inactive", active);
                ui.toast(`Ukuran ${actionText}.`, "success");
            } else {
                ui.toast(res?.message || `Gagal ${actionText}.`, "danger");
            }
        }
    });

    // Submit form
    document.getElementById("btnSubmitForm")?.addEventListener("click", async function () {
        const btn = this;
        const mode = modal.dataset.mode;
        const payload = {
            variant_name: document.getElementById("f_nama_varian").value.trim(),
            material: document.getElementById("f_material").value.trim(),
            price: parseFloat(document.getElementById("f_harga").value) || 0,
            sleeve_price: parseInt(document.getElementById("f_sleeve_price").value) || 5000,
            minimum_order: parseInt(document.getElementById("f_minimum_order").value) || DEFAULT_MINIMUM_ORDER,
            options: collectOptions(),
        };

        if (mode === "create") {
            payload.category_id = parseInt(document.getElementById("f_category_id").value, 10);
            const produkSelect = document.getElementById("f_nama_produk");
            const produkNewInput = document.getElementById("f_nama_produk_new");

            if (produkSelect.value === "__new__") {
                payload.product_name = produkNewInput.value.trim();
            } else {
                payload.product_name = produkSelect.value.trim();
            }

            if (!payload.product_name || !payload.variant_name) {
                ui.toast("Nama produk dan nama varian wajib diisi.", "warning");
                return;
            }

            await ui.withLoading(btn, async () => {
                const res = await postJson(window.ENDPOINTS.store, payload);
                if (res?.success) {
                    ui.toast("Varian baru disimpan.", "success");
                    ui.closeModal("modalForm");
                    location.reload();
                } else {
                    ui.toast(res?.message || "Gagal menyimpan.", "danger");
                }
            });
        } else {
            payload.variant_id = parseInt(document.getElementById("f_variant_id").value, 10);

            await ui.withLoading(btn, async () => {
                const res = await postJson(window.ENDPOINTS.update, payload);
                if (res?.success) {
                    ui.toast("Perubahan tersimpan.", "success");
                    ui.closeModal("modalForm");
                    location.reload();
                } else {
                    ui.toast(res?.message || "Gagal menyimpan.", "danger");
                }
            });
        }
    });

    // ============================================================
    // Mode hapus kategori (checkbox) — merged into one button
    // ============================================================
    let deleteMode = false;
    const btnHapusMode = document.getElementById("btnHapusMode");
    const btnHapusLabel = btnHapusMode?.querySelector("span") || btnHapusMode;

    function exitDeleteMode() {
        deleteMode = false;
        document.querySelectorAll(".pilih-hapus").forEach((cb) => {
            cb.hidden = true;
            cb.checked = false;
        });
        btnHapusMode.classList.remove("btn--danger");
        btnHapusMode.classList.add("btn--soft");
        btnHapusMode.innerHTML = '<i class="fas fa-power-off" aria-hidden="true"></i> Nonaktifkan';
    }

    btnHapusMode?.addEventListener("click", async function () {
        if (!deleteMode) {
            // Enter delete mode
            deleteMode = true;
            document.querySelectorAll(".pilih-hapus").forEach((cb) => {
                cb.hidden = false;
                cb.checked = false;
            });
            this.classList.remove("btn--soft");
            this.classList.add("btn--danger");
            this.innerHTML = '<i class="fas fa-power-off" aria-hidden="true"></i> Nonaktifkan Terpilih';
        } else {
            // Nothing selected → cancel delete mode
            const checked = document.querySelectorAll(".pilih-hapus:checked");
            if (!checked.length) {
                exitDeleteMode();
                return;
            }
            // Execute delete
            const deleted = await deleteSelected();
            if (deleted) exitDeleteMode();
        }
    });

    async function deleteSelected() {
        const checked = [...document.querySelectorAll(".pilih-hapus:checked")];
        if (!checked.length) {
            ui.toast("Pilih kategori terlebih dahulu.", "warning");
            return false;
        }

        const ok = await ui.confirm({
            title: "Nonaktifkan varian terpilih?",
            message: `${checked.length} kategori akan dinonaktifkan seluruh variannya.`,
            confirmText: "Nonaktifkan",
            variant: "danger",
        });
        if (!ok) return false;

        // Collect unique variant IDs from checked categories.
        // Scope to .kain only because action buttons also carry data-variant-id.
        const variantIds = new Set();
        const kategoriEls = [];
        for (const cb of checked) {
            const kategoriItem = cb.closest(".kategori-item");
            kategoriEls.push(kategoriItem);
            kategoriItem.querySelectorAll(".kain[data-variant-id]").forEach((el) => {
                variantIds.add(parseInt(el.dataset.variantId, 10));
            });
        }

        const ids = [...variantIds].filter((id) => Number.isInteger(id) && id > 0);
        if (!ids.length) return false;

        // Optimistic: dim all selected
        kategoriEls.forEach((el) => {
            el.style.opacity = "0.4";
            el.style.pointerEvents = "none";
        });

        const res = await postJson(window.ENDPOINTS.destroyBatch, { type: "variants", ids });
        if (res?.success) {
            kategoriEls.forEach((el) => {
                el.style.opacity = "";
                el.style.pointerEvents = "";
                el.querySelectorAll(".kain[data-variant-id]").forEach((kainEl) => {
                    kainEl.classList.add("kain--inactive");
                    const toggle = kainEl.querySelector('[data-action="toggle-variant"]');
                    if (toggle) {
                        toggle.dataset.active = "0";
                        toggle.setAttribute("aria-label", `Aktifkan varian ${toggle.dataset.variantName || ""}`.trim());
                    }
                });
            });
            ui.toast(res.message || "Varian terpilih dinonaktifkan.", "success");
            return true;
        } else {
            kategoriEls.forEach((el) => {
                el.style.opacity = "";
                el.style.pointerEvents = "";
            });
            ui.toast(res?.message || "Gagal menghapus kategori.", "danger");
            return false;
        }
    }

    // ============================================================
    // Helpers
    // ============================================================
    async function postJson(url, body) {
        try {
            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": window.SiblingsUI?.getCsrfToken() || "",
                },
                body: JSON.stringify(body),
                credentials: "same-origin",
            });
            if (!res.ok) {
                return { success: false, message: `Server merespons ${res.status}.` };
            }
            return await res.json();
        } catch (err) {
            console.error(err);
            return { success: false, message: "Gagal menghubungi server." };
        }
    }
})();
