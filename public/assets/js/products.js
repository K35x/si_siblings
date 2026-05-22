/**
 * Siblings.co — Products page (stok management) interactions.
 * Tergantung pada window.SiblingsUI (ui.js).
 */
(function () {
    "use strict";

    const ui = window.SiblingsUI;

    // ============================================================
    // Accordion toggles
    // ============================================================
    document.addEventListener("click", (e) => {
        const trigger = e.target.closest("[data-toggle]");
        if (!trigger) return;
        if (e.target.closest("[data-action], input, .btn-delete, .aksi")) return;

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
        } else if (type === "ukuran") {
            const item = trigger.closest(".ukuran-item");
            const list = item.querySelector(".warna-list");
            if (list) list.hidden = !next;
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
        ["f_nama_produk", "f_nama_varian", "f_bahan", "f_tipe_sablon", "f_harga"].forEach((id) => {
            document.getElementById(id).value = "";
        });
        optList.innerHTML = "";
        addOptionRow();
        ui.openModal("modalForm");
    }

    function openEdit(varian, optionMap) {
        if (!modal) return;
        titleEl.textContent = "Edit Varian";
        modal.dataset.mode = "edit";
        document.getElementById("f_variant_id").value = varian.variant_id;
        document.getElementById("f_category_field").style.display = "none";
        document.getElementById("f_nama_produk_field").style.display = "none";
        document.getElementById("f_nama_varian").value = varian.nama_varian || "";
        document.getElementById("f_bahan").value = varian.bahan || "";
        document.getElementById("f_tipe_sablon").value = varian.tipe_sablon_bordir || "";
        document.getElementById("f_harga").value = varian.harga_start_from || "";

        optList.innerHTML = "";
        let hasRow = false;
        for (const [sizeId, colorMap] of Object.entries(optionMap || {})) {
            for (const [colorId, optData] of Object.entries(colorMap)) {
                const qty = (optData && optData.qty) ? parseInt(optData.qty, 10) : 1;
                addOptionRow(parseInt(sizeId, 10), parseInt(colorId, 10), qty);
                hasRow = true;
            }
        }
        if (!hasRow) addOptionRow();
        ui.openModal("modalForm");
    }

    function addOptionRow(selectedSize = null, selectedColor = null, qty = 1) {
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
        qtyInput.className = "form-control tabular-nums";
        qtyInput.setAttribute("aria-label", "Jumlah");

        const btnRemove = document.createElement("button");
        btnRemove.type = "button";
        btnRemove.className = "btn btn--icon btn--danger-soft";
        btnRemove.setAttribute("aria-label", "Hapus baris ukuran dan warna");
        btnRemove.innerHTML = '<i class="fas fa-times" aria-hidden="true"></i>';
        btnRemove.addEventListener("click", () => row.remove());

        row.append(sizeSelect, colorSelect, qtyInput, btnRemove);
        optList.appendChild(row);
    }

    function collectOptions() {
        const opts = [];
        optList.querySelectorAll(":scope > div").forEach((row) => {
            const selects = row.querySelectorAll("select");
            const qtyInput = row.querySelector('input[type="number"]');
            if (selects.length >= 2) {
                opts.push({
                    size_id: parseInt(selects[0].value, 10),
                    color_id: parseInt(selects[1].value, 10),
                    qty: parseInt(qtyInput?.value || "1", 10) || 1,
                });
            }
        });
        return opts;
    }

    document.getElementById("btnTambah")?.addEventListener("click", openTambah);
    document.getElementById("btnTambahEmpty")?.addEventListener("click", openTambah);
    document.getElementById("btnAddCombination")?.addEventListener("click", () => addOptionRow());

    // Edit & delete actions (event delegation)
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

        if (action === "delete-variant") {
            const ok = await ui.confirm({
                title: "Nonaktifkan varian?",
                message: `Varian "${trigger.dataset.variantName}" akan dinonaktifkan.`,
                confirmText: "Nonaktifkan",
                variant: "danger",
            });
            if (!ok) return;

            const res = await postJson(ENDPOINTS.destroy, { variant_id: parseInt(trigger.dataset.variantId, 10) });
            if (res?.success) {
                ui.toast("Varian dinonaktifkan.", "success");
                location.reload();
            } else {
                ui.toast(res?.message || "Gagal menghapus varian.", "danger");
            }
        }

        if (action === "delete-option") {
            const ok = await ui.confirm({
                title: "Hapus warna?",
                message: "Warna ini akan dihapus dari varian.",
                confirmText: "Hapus",
                variant: "danger",
            });
            if (!ok) return;

            const res = await postJson(ENDPOINTS.destroyOption, { option_id: parseInt(trigger.dataset.optionId, 10) });
            if (res?.success) {
                trigger.closest(".warna-row").remove();
                ui.toast("Warna dihapus.", "success");
            } else {
                ui.toast(res?.message || "Gagal menghapus warna.", "danger");
            }
        }

        if (action === "delete-size") {
            const ok = await ui.confirm({
                title: "Hapus semua warna?",
                message: "Seluruh warna pada ukuran ini akan dihapus.",
                confirmText: "Hapus",
                variant: "danger",
            });
            if (!ok) return;

            const sizeItem = trigger.closest(".ukuran-item");
            const optIds = [...sizeItem.querySelectorAll(".warna-row")]
                .map((r) => parseInt(r.dataset.optionId, 10))
                .filter(Boolean);

            for (const oid of optIds) {
                await postJson(ENDPOINTS.destroyOption, { option_id: oid });
            }
            ui.toast("Ukuran dibersihkan.", "success");
            location.reload();
        }
    });

    // Submit form
    document.getElementById("btnSubmitForm")?.addEventListener("click", async () => {
        const mode = modal.dataset.mode;
        const payload = {
            nama_varian: document.getElementById("f_nama_varian").value.trim(),
            bahan: document.getElementById("f_bahan").value.trim(),
            tipe_sablon: document.getElementById("f_tipe_sablon").value.trim(),
            harga: parseFloat(document.getElementById("f_harga").value) || 0,
            options: collectOptions(),
        };

        if (mode === "create") {
            payload.category_id = parseInt(document.getElementById("f_category_id").value, 10);
            payload.nama_produk = document.getElementById("f_nama_produk").value.trim();

            if (!payload.nama_produk || !payload.nama_varian) {
                ui.toast("Nama produk dan nama varian wajib diisi.", "warning");
                return;
            }

            const res = await postJson(ENDPOINTS.store, payload);
            if (res?.success) {
                ui.toast("Varian baru disimpan.", "success");
                ui.closeModal("modalForm");
                location.reload();
            } else {
                ui.toast(res?.message || "Gagal menyimpan.", "danger");
            }
        } else {
            payload.variant_id = parseInt(document.getElementById("f_variant_id").value, 10);

            const res = await postJson(ENDPOINTS.update, payload);
            if (res?.success) {
                ui.toast("Perubahan tersimpan.", "success");
                ui.closeModal("modalForm");
                location.reload();
            } else {
                ui.toast(res?.message || "Gagal menyimpan.", "danger");
            }
        }
    });

    // ============================================================
    // Mode hapus kategori (checkbox)
    // ============================================================
    let deleteMode = false;
    document.getElementById("btnHapusMode")?.addEventListener("click", function () {
        deleteMode = !deleteMode;

        document.querySelectorAll(".pilih-hapus").forEach((cb) => {
            cb.hidden = !deleteMode;
            cb.checked = false;
        });

        let bulkBtn = document.getElementById("hapusTerpilih");
        if (deleteMode) {
            this.classList.add("btn--danger-soft");
            if (!bulkBtn) {
                bulkBtn = document.createElement("button");
                bulkBtn.id = "hapusTerpilih";
                bulkBtn.type = "button";
                bulkBtn.className = "btn btn--danger";
                bulkBtn.innerHTML = '<i class="fas fa-trash" aria-hidden="true"></i> Hapus Terpilih';
                bulkBtn.addEventListener("click", deleteSelected);
                this.parentElement.appendChild(bulkBtn);
            }
        } else {
            this.classList.remove("btn--danger-soft");
            bulkBtn?.remove();
        }
    });

    async function deleteSelected() {
        const checked = [...document.querySelectorAll(".pilih-hapus:checked")];
        if (!checked.length) {
            ui.toast("Pilih kategori terlebih dahulu.", "warning");
            return;
        }

        const ok = await ui.confirm({
            title: "Nonaktifkan varian terpilih?",
            message: `${checked.length} kategori akan dinonaktifkan beserta seluruh variannya.`,
            confirmText: "Nonaktifkan",
            variant: "danger",
        });
        if (!ok) return;

        for (const cb of checked) {
            const variantEls = cb.closest(".kategori-item").querySelectorAll("[data-variant-id]");
            for (const el of variantEls) {
                await postJson(ENDPOINTS.destroy, { variant_id: parseInt(el.dataset.variantId, 10) });
            }
        }
        ui.toast("Kategori dinonaktifkan.", "success");
        location.reload();
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
