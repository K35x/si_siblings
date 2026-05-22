/**
 * Siblings.co — UI helpers
 * Toast, modal, confirm, sidebar, custom-select, form-loading.
 * Pure vanilla JS, no dependencies.
 */
(function () {
    "use strict";

    const FOCUSABLE_SELECTOR = [
        "a[href]",
        "area[href]",
        "button:not([disabled])",
        "input:not([disabled]):not([type=hidden])",
        "select:not([disabled])",
        "textarea:not([disabled])",
        "iframe",
        "object",
        "embed",
        "[contenteditable]",
        '[tabindex]:not([tabindex="-1"])',
    ].join(",");

    const prefersReducedMotion = () =>
        window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    function getFocusable(root) {
        return Array.from(root.querySelectorAll(FOCUSABLE_SELECTOR)).filter(
            (el) => !el.hasAttribute("inert") && el.offsetParent !== null
        );
    }

    function trapFocus(root) {
        const handler = (e) => {
            if (e.key !== "Tab") return;
            const focusables = getFocusable(root);
            if (focusables.length === 0) {
                e.preventDefault();
                return;
            }
            const first = focusables[0];
            const last = focusables[focusables.length - 1];
            const active = document.activeElement;
            if (e.shiftKey && active === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && active === last) {
                e.preventDefault();
                first.focus();
            }
        };
        root.addEventListener("keydown", handler);
        return () => root.removeEventListener("keydown", handler);
    }

    function ensureToastContainer() {
        let el = document.querySelector(".toast-container");
        if (!el) {
            el = document.createElement("div");
            el.className = "toast-container";
            el.setAttribute("role", "region");
            el.setAttribute("aria-live", "polite");
            el.setAttribute("aria-label", "Notifikasi");
            document.body.appendChild(el);
        }
        return el;
    }

    const TOAST_ICON = {
        success: "fas fa-check-circle",
        danger: "fas fa-exclamation-circle",
        warning: "fas fa-exclamation-triangle",
        info: "fas fa-info-circle",
    };

    function showToast(message, type = "info", duration = 4000) {
        const container = ensureToastContainer();

        const toast = document.createElement("div");
        toast.className = `toast toast--${type}`;
        toast.setAttribute("role", type === "danger" ? "alert" : "status");
        toast.innerHTML = `
            <i class="toast__icon ${TOAST_ICON[type] || TOAST_ICON.info}" aria-hidden="true"></i>
            <div class="toast__body"></div>
            <button type="button" class="toast__close" aria-label="Tutup notifikasi">&times;</button>
        `;
        toast.querySelector(".toast__body").textContent = message;

        const reduce = prefersReducedMotion();
        const close = () => {
            if (!reduce) {
                toast.style.opacity = "0";
                toast.style.transform = "translateX(20px)";
                setTimeout(() => toast.remove(), 200);
            } else {
                toast.remove();
            }
        };

        toast.querySelector(".toast__close").addEventListener("click", close);
        container.appendChild(toast);

        if (duration > 0) {
            setTimeout(close, duration);
        }

        return { close };
    }

    let lastFocused = null;
    const activeTraps = new WeakMap();

    function openModal(idOrEl) {
        const el = typeof idOrEl === "string" ? document.getElementById(idOrEl) : idOrEl;
        if (!el) return;

        lastFocused = document.activeElement;
        el.classList.add("is-open", "show");
        el.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";

        const removeTrap = trapFocus(el);
        activeTraps.set(el, removeTrap);

        const focusables = getFocusable(el);
        const firstNonClose = focusables.find((f) => !f.matches("[data-modal-close], .modal__close"));
        (firstNonClose || focusables[0])?.focus();
    }

    function closeModal(idOrEl) {
        const el = typeof idOrEl === "string" ? document.getElementById(idOrEl) : idOrEl;
        if (!el) return;

        el.classList.remove("is-open", "show");
        el.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";

        const removeTrap = activeTraps.get(el);
        if (removeTrap) {
            removeTrap();
            activeTraps.delete(el);
        }

        if (lastFocused && typeof lastFocused.focus === "function") {
            lastFocused.focus();
        }
    }

    function bindModals() {
        document.addEventListener("click", (e) => {
            const opener = e.target.closest("[data-modal-open]");
            if (opener) {
                e.preventDefault();
                openModal(opener.getAttribute("data-modal-open"));
                return;
            }

            const closer = e.target.closest("[data-modal-close]");
            if (closer) {
                e.preventDefault();
                const overlay = closer.closest(".modal-overlay");
                if (overlay) closeModal(overlay);
                return;
            }

            if (e.target.classList && e.target.classList.contains("modal-overlay")) {
                closeModal(e.target);
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                document.querySelectorAll(".modal-overlay.is-open, .modal-overlay.show").forEach(closeModal);
            }
        });
    }

    function confirmDialog({
        title = "Konfirmasi",
        message = "Apakah Anda yakin?",
        confirmText = "Ya",
        cancelText = "Batal",
        variant = "danger",
    } = {}) {
        return new Promise((resolve) => {
            const overlay = document.createElement("div");
            overlay.className = "modal-overlay is-open";
            overlay.setAttribute("role", "dialog");
            overlay.setAttribute("aria-modal", "true");
            overlay.setAttribute("aria-labelledby", "ui-confirm-title");
            overlay.setAttribute("aria-describedby", "ui-confirm-body");

            const confirmClass = variant === "danger" ? "btn--danger" : "btn--primary";

            overlay.innerHTML = `
                <div class="modal modal--sm">
                    <div class="modal__header">
                        <h3 class="modal__title" id="ui-confirm-title"></h3>
                        <button type="button" class="modal__close" data-action="cancel" aria-label="Tutup">&times;</button>
                    </div>
                    <div class="modal__body" id="ui-confirm-body" data-role="message"></div>
                    <div class="modal__footer">
                        <button type="button" class="btn btn--ghost" data-action="cancel"></button>
                        <button type="button" class="btn ${confirmClass}" data-action="confirm"></button>
                    </div>
                </div>
            `;

            overlay.querySelector("#ui-confirm-title").textContent = title;
            overlay.querySelector("[data-role=message]").textContent = message;
            overlay.querySelector("[data-action=cancel]:not(.modal__close)").textContent = cancelText;
            overlay.querySelector("[data-action=confirm]").textContent = confirmText;

            const previouslyFocused = document.activeElement;
            const removeTrap = trapFocus(overlay);

            const finish = (result) => {
                document.body.style.overflow = "";
                document.removeEventListener("keydown", keyHandler);
                removeTrap();
                overlay.remove();
                if (previouslyFocused && typeof previouslyFocused.focus === "function") {
                    previouslyFocused.focus();
                }
                resolve(result);
            };

            overlay.addEventListener("click", (e) => {
                const action = e.target.closest("[data-action]");
                if (action) {
                    finish(action.getAttribute("data-action") === "confirm");
                } else if (e.target === overlay) {
                    finish(false);
                }
            });

            const keyHandler = (e) => {
                if (e.key === "Escape") {
                    finish(false);
                }
            };
            document.addEventListener("keydown", keyHandler);

            document.body.style.overflow = "hidden";
            document.body.appendChild(overlay);
            overlay.querySelector("[data-action=confirm]").focus();
        });
    }

    function bindSidebarToggle() {
        const toggle = document.querySelector(".sidebar-toggle");
        const sidebar = document.querySelector(".sidebar");
        if (!toggle || !sidebar) return;

        let overlay = document.querySelector(".sidebar-overlay");
        if (!overlay) {
            overlay = document.createElement("div");
            overlay.className = "sidebar-overlay";
            document.body.appendChild(overlay);
        }

        let removeTrap = null;
        let lastSidebarFocus = null;

        const open = () => {
            lastSidebarFocus = document.activeElement;
            sidebar.classList.add("is-open");
            overlay.classList.add("is-visible");
            toggle.setAttribute("aria-expanded", "true");
            document.body.style.overflow = "hidden";
            removeTrap = trapFocus(sidebar);
            const first = getFocusable(sidebar)[0];
            if (first) first.focus();
        };

        const close = () => {
            sidebar.classList.remove("is-open");
            overlay.classList.remove("is-visible");
            toggle.setAttribute("aria-expanded", "false");
            document.body.style.overflow = "";
            if (removeTrap) {
                removeTrap();
                removeTrap = null;
            }
            if (lastSidebarFocus && typeof lastSidebarFocus.focus === "function") {
                lastSidebarFocus.focus();
            }
        };

        toggle.addEventListener("click", () => {
            if (sidebar.classList.contains("is-open")) close();
            else open();
        });

        overlay.addEventListener("click", close);

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && sidebar.classList.contains("is-open")) close();
        });

        sidebar.querySelectorAll(".nav-item").forEach((link) => {
            link.addEventListener("click", () => {
                if (window.matchMedia("(max-width: 1024px)").matches) {
                    close();
                }
            });
        });
    }

    function bindFormLoading() {
        document.addEventListener("submit", (e) => {
            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (form.dataset.noLoading === "true") return;

            const submitBtn = form.querySelector(
                'button[type="submit"], input[type="submit"]'
            );
            if (!submitBtn || submitBtn.disabled) return;

            const loadingLabel = submitBtn.dataset.loadingLabel;
            if (loadingLabel) {
                submitBtn.dataset.originalLabel = submitBtn.textContent;
                submitBtn.textContent = loadingLabel;
            }
            submitBtn.disabled = true;
            submitBtn.setAttribute("aria-busy", "true");
        });
    }

    function bindClientValidation() {
        document.addEventListener(
            "invalid",
            (e) => {
                const field = e.target;
                if (!(field instanceof HTMLElement)) return;
                if (!field.matches("input, select, textarea")) return;

                const wrapper = field.closest(".form-field");
                if (wrapper) wrapper.classList.add("has-error");

                const form = field.form;
                if (form && !form.dataset.firstInvalidFocused) {
                    form.dataset.firstInvalidFocused = "true";
                    queueMicrotask(() => field.focus());

                    const errorRegion = form.querySelector('[data-form-errors]');
                    if (errorRegion) {
                        errorRegion.textContent = "Periksa kembali isian yang ditandai.";
                    }
                }
            },
            true
        );

        document.addEventListener("submit", (e) => {
            const form = e.target;
            if (form instanceof HTMLFormElement) {
                delete form.dataset.firstInvalidFocused;
            }
        });

        document.addEventListener("input", (e) => {
            const field = e.target;
            if (!(field instanceof HTMLElement)) return;
            const wrapper = field.closest(".form-field.has-error");
            if (wrapper && field.checkValidity && field.checkValidity()) {
                wrapper.classList.remove("has-error");
            }
        });
    }

    const ARROW_SVG =
        '<svg class="custom-select__arrow" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    function initCustomSelect(selectEl) {
        if (selectEl.dataset.customInit) return;
        selectEl.dataset.customInit = "true";
        selectEl.style.display = "none";

        const wrapper = document.createElement("div");
        wrapper.className = "custom-select";

        const options = Array.from(selectEl.options);
        const selectedValue = selectEl.value;
        const selectedText = selectEl.options[selectEl.selectedIndex]?.text || "";
        const placeholder = selectEl.dataset.placeholder || "Pilih…";
        const labelledBy = selectEl.getAttribute("aria-labelledby");
        const ariaLabel =
            selectEl.getAttribute("aria-label") ||
            selectEl.labels?.[0]?.textContent?.trim() ||
            placeholder;

        const hasValue = selectedValue !== "" && selectedValue !== null;

        wrapper.innerHTML = `
            <div class="custom-select__trigger${hasValue ? " has-value" : ""}" role="combobox" tabindex="0" aria-haspopup="listbox" aria-expanded="false">
                <span class="custom-select__value"></span>
                ${ARROW_SVG}
            </div>
            <div class="custom-select__dropdown" role="listbox">
                ${options
                    .map((opt) => {
                        const isPlaceholder = opt.value === "" || opt.disabled;
                        const isSelected = opt.value === selectedValue;
                        return `<div class="custom-select__option${isPlaceholder ? " is-placeholder" : ""}${isSelected ? " is-selected" : ""}" data-value="${opt.value.replace(/"/g, "&quot;")}" role="option" aria-selected="${isSelected ? "true" : "false"}"></div>`;
                    })
                    .join("")}
            </div>
        `;

        const trigger = wrapper.querySelector(".custom-select__trigger");
        if (labelledBy) trigger.setAttribute("aria-labelledby", labelledBy);
        else trigger.setAttribute("aria-label", ariaLabel);

        wrapper.querySelector(".custom-select__value").textContent = hasValue
            ? selectedText
            : placeholder;

        const optionEls = wrapper.querySelectorAll(".custom-select__option");
        options.forEach((opt, i) => {
            optionEls[i].textContent = opt.text;
        });

        selectEl.parentNode.insertBefore(wrapper, selectEl.nextSibling);
        bindCustomSelectEvents(wrapper, selectEl);
    }

    function bindCustomSelectEvents(wrapper, nativeSelect) {
        const trigger = wrapper.querySelector(".custom-select__trigger");
        const options = wrapper.querySelectorAll(".custom-select__option");
        const valueDisplay = wrapper.querySelector(".custom-select__value");
        const placeholder = nativeSelect.dataset.placeholder || "Pilih…";

        const closeMe = () => {
            wrapper.classList.remove("is-open");
            trigger.setAttribute("aria-expanded", "false");
        };

        const openMe = () => {
            closeAllCustomSelects();
            wrapper.classList.add("is-open");
            trigger.setAttribute("aria-expanded", "true");
            const sel = wrapper.querySelector(".custom-select__option.is-selected");
            (sel || options[0])?.scrollIntoView({ block: "nearest" });
        };

        trigger.addEventListener("click", (e) => {
            e.stopPropagation();
            if (wrapper.classList.contains("is-open")) closeMe();
            else openMe();
        });

        trigger.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                if (wrapper.classList.contains("is-open")) closeMe();
                else openMe();
            } else if (e.key === "Escape") {
                closeMe();
            } else if (e.key === "ArrowDown") {
                e.preventDefault();
                openMe();
                options[0]?.focus?.();
            }
        });

        options.forEach((option) => {
            option.tabIndex = -1;
            option.addEventListener("click", () => {
                const value = option.dataset.value;
                const text = option.textContent;
                nativeSelect.value = value;
                valueDisplay.textContent = value ? text : placeholder;
                trigger.classList.toggle("has-value", !!value);
                options.forEach((o) => {
                    o.classList.remove("is-selected");
                    o.setAttribute("aria-selected", "false");
                });
                option.classList.add("is-selected");
                option.setAttribute("aria-selected", "true");
                closeMe();
                nativeSelect.dispatchEvent(new Event("change", { bubbles: true }));
            });
        });

        document.addEventListener("click", (e) => {
            if (!wrapper.contains(e.target)) closeMe();
        });
    }

    function closeAllCustomSelects() {
        document.querySelectorAll(".custom-select.is-open").forEach((w) => {
            w.classList.remove("is-open");
            const t = w.querySelector(".custom-select__trigger");
            if (t) t.setAttribute("aria-expanded", "false");
        });
    }

    function autoInitCustomSelects() {
        document.querySelectorAll("select:not([data-no-custom])").forEach(initCustomSelect);
    }

    function announce(message) {
        let el = document.getElementById("ui-live-announcer");
        if (!el) {
            el = document.createElement("div");
            el.id = "ui-live-announcer";
            el.className = "sr-only";
            el.setAttribute("aria-live", "polite");
            el.setAttribute("role", "status");
            document.body.appendChild(el);
        }
        el.textContent = "";
        setTimeout(() => {
            el.textContent = message;
        }, 50);
    }

    document.addEventListener("DOMContentLoaded", () => {
        bindModals();
        bindSidebarToggle();
        bindFormLoading();
        bindClientValidation();
        autoInitCustomSelects();
    });

    window.SiblingsUI = {
        toast: showToast,
        openModal,
        closeModal,
        confirm: confirmDialog,
        announce,
        trapFocus,
        getFocusable,
        prefersReducedMotion,
    };
})();
