import axios from 'axios';
import './bootstrap';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

/* -------------------------------------------------------------------------
 | Toast
 | ---------------------------------------------------------------------- */
const TOAST_ICON = { success: 'check_circle', error: 'error', info: 'info' };

function toast(message, variant = 'success', duration = 3200) {
    const container = document.getElementById('toast-container');
    const template = document.getElementById('toast-template');
    if (!container || !template) return;

    const colors = {
        success: 'bg-primary text-on-primary',
        error: 'bg-error text-on-error',
        warning: 'bg-secondary text-on-primary',
        info: 'bg-primary text-on-primary',
    };

    // Buang toast lama agar tidak menumpuk
    while (container.children.length > 3) container.firstElementChild?.remove();

    const node = template.content.firstElementChild.cloneNode(true);
    node.classList.add(...(colors[variant] || colors.success).split(' ').filter(Boolean));

    const icon = node.querySelector('[data-toast-icon]');
    if (icon) icon.textContent = TOAST_ICON[variant] || TOAST_ICON.info;

    const msg = node.querySelector('[data-toast-message]');
    if (msg) msg.textContent = message;

    container.appendChild(node);
    requestAnimationFrame(() => node.classList.remove('translate-y-2', 'opacity-0'));

    setTimeout(() => {
        node.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => node.remove(), 300);
    }, duration);
}

window.pchToast = toast;

/* -------------------------------------------------------------------------
 | Cart drawer
 | ---------------------------------------------------------------------- */
const cartDrawer = document.getElementById('cart-drawer');
const cartBackdrop = document.getElementById('cart-drawer-backdrop');

function toggleCart(open) {
    if (!cartDrawer || !cartBackdrop) return;
    if (open) {
        cartBackdrop.classList.remove('hidden');
        requestAnimationFrame(() => {
            cartBackdrop.classList.remove('opacity-0');
            cartDrawer.classList.remove('translate-x-full');
        });
        cartDrawer.setAttribute('aria-hidden', 'false');
    } else {
        cartDrawer.classList.add('translate-x-full');
        cartBackdrop.classList.add('opacity-0');
        setTimeout(() => cartBackdrop.classList.add('hidden'), 300);
        cartDrawer.setAttribute('aria-hidden', 'true');
    }
}

window.pchToggleCart = toggleCart;

document.getElementById('cart-toggle-btn')?.addEventListener('click', () => toggleCart(true));
document.getElementById('cart-drawer-close')?.addEventListener('click', () => toggleCart(false));
cartBackdrop?.addEventListener('click', () => toggleCart(false));

/* Mobile drawer */
const mobileDrawer = document.getElementById('mobile-drawer');
const mobileBackdrop = document.getElementById('mobile-drawer-backdrop');

function toggleMobile(open) {
    if (!mobileDrawer || !mobileBackdrop) return;
    if (open) {
        mobileBackdrop.classList.remove('hidden');
        requestAnimationFrame(() => {
            mobileBackdrop.classList.remove('opacity-0');
            mobileDrawer.classList.remove('-translate-x-full');
        });
    } else {
        mobileDrawer.classList.add('-translate-x-full');
        mobileBackdrop.classList.add('opacity-0');
        setTimeout(() => mobileBackdrop.classList.add('hidden'), 300);
    }
}

document.getElementById('btn-mobile-menu-open')?.addEventListener('click', () => toggleMobile(true));
document.getElementById('btn-mobile-menu-close')?.addEventListener('click', () => toggleMobile(false));
mobileBackdrop?.addEventListener('click', () => toggleMobile(false));

/* Admin sidebar (drawer mobile, selalu terbuka di desktop) */
const adminSidebar = document.getElementById('admin-sidebar');
const adminSidebarBackdrop = document.getElementById('admin-sidebar-backdrop');

function toggleAdminSidebar(open) {
    if (!adminSidebar || !adminSidebarBackdrop) return;

    if (open) {
        adminSidebarBackdrop.classList.remove('hidden');
        requestAnimationFrame(() => {
            adminSidebarBackdrop.classList.remove('opacity-0');
            adminSidebar.classList.remove('-translate-x-full');
        });
        document.body.classList.add('overflow-hidden');
    } else {
        adminSidebar.classList.add('-translate-x-full');
        adminSidebarBackdrop.classList.add('opacity-0');
        setTimeout(() => adminSidebarBackdrop.classList.add('hidden'), 300);
        document.body.classList.remove('overflow-hidden');
    }
}

document.getElementById('admin-sidebar-open')?.addEventListener('click', () => toggleAdminSidebar(true));
document.getElementById('admin-sidebar-close')?.addEventListener('click', () => toggleAdminSidebar(false));
adminSidebarBackdrop?.addEventListener('click', () => toggleAdminSidebar(false));

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && adminSidebar && !adminSidebar.classList.contains('-translate-x-full')) {
        toggleAdminSidebar(false);
    }
});

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) document.body.classList.remove('overflow-hidden');
});

/* Filter menu sidebar admin */
const adminMenuFilter = document.querySelector('[data-menu-filter]');

adminMenuFilter?.addEventListener('input', (e) => {
    const query = e.target.value.trim().toLowerCase();

    document.querySelectorAll('[data-menu-group]').forEach((group) => {
        let visible = 0;

        group.querySelectorAll('[data-menu-item]').forEach((item) => {
            const match = !query || item.textContent.toLowerCase().includes(query);
            item.classList.toggle('hidden', !match);
            if (match) visible += 1;
        });

        group.classList.toggle('hidden', visible === 0);
    });
});

/* Grafik dashboard: baca nilai saat kursor di atas kolom */
const chartReadout = document.querySelector('[data-chart-readout]');

if (chartReadout) {
    const defaultLabel = chartReadout.textContent;

    document.querySelectorAll('[data-chart-col]').forEach((col) => {
        col.addEventListener('mouseenter', () => {
            chartReadout.textContent = col.dataset.chartTip || defaultLabel;
        });
        col.addEventListener('mouseleave', () => {
            chartReadout.textContent = defaultLabel;
        });
    });
}

/* User dropdown */
const userBtn = document.getElementById('user-dropdown-btn');
const userMenu = document.getElementById('user-dropdown-menu');

userBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    userMenu?.classList.toggle('hidden');
});

document.addEventListener('click', () => userMenu?.classList.add('hidden'));

/* Theme toggle (header + drawer mobile) */
function syncThemeIcon() {
    const dark = document.documentElement.classList.contains('dark');
    document.querySelectorAll('[data-theme-toggle-icon]').forEach((el) => {
        el.textContent = dark ? 'dark_mode' : 'light_mode';
    });
}

function toggleTheme() {
    const dark = !document.documentElement.classList.contains('dark');
    document.documentElement.classList.toggle('dark', dark);
    localStorage.setItem('pch-theme', dark ? 'dark' : 'light');
    syncThemeIcon();
}

document.addEventListener('click', (e) => {
    if (e.target.closest('[data-theme-toggle]')) toggleTheme();
});

syncThemeIcon();

/* Dismissible alerts */
document.addEventListener('click', (e) => {
    const closeBtn = e.target.closest('[data-alert-close]');
    if (closeBtn) closeBtn.closest('[data-dismissible-alert]')?.remove();
});

/* -------------------------------------------------------------------------
 | Cart badge + drawer refresh
 | ---------------------------------------------------------------------- */
const CART_URL = '/keranjang/ringkasan';

function updateCartBadges(count) {
    document.querySelectorAll('[data-cart-badge]').forEach((el) => {
        el.textContent = count;
        el.classList.toggle('hidden', count === 0);
    });

    const label = document.querySelector('[data-cart-count-label]');
    if (label) {
        label.textContent = `${count} Item`;
        label.classList.toggle('hidden', count === 0);
    }
}

async function refreshCart() {
    try {
        const { data } = await window.axios.get(CART_URL);

        updateCartBadges(data.count ?? 0);

        const itemsWrap = document.getElementById('cart-drawer-items');
        if (itemsWrap && data.html) itemsWrap.innerHTML = data.html;

        const summaryWrap = document.getElementById('cart-drawer-summary');
        if (summaryWrap && data.summaryHtml) summaryWrap.innerHTML = data.summaryHtml;

        // Perbarui subtotal di halaman checkout / keranjang (bila ada)
        const liveRegion = document.querySelector('[data-cart-live-region]');
        if (liveRegion) liveRegion.innerHTML = data.summaryHtml || '';

        document.dispatchEvent(new CustomEvent('pch:cart-refreshed', { detail: data }));

        return data;
    } catch (error) {
        console.error('Gagal memperbarui keranjang', error);
        return null;
    }
}

window.pchRefreshCart = refreshCart;

/* Add to cart */
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-add-to-cart]');
    if (!btn || btn.disabled) return;

    e.preventDefault();
    const productId = btn.dataset.productId;
    const original = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">hourglass_top</span><span>Memproses...</span>';

    try {
        const { data } = await window.axios.post('/keranjang', { product_id: productId, quantity: 1 });
        toast(data.message || 'Produk ditambahkan ke keranjang', 'success');

        await refreshCart();
        toggleCart(true);

        const badge = document.querySelector('[data-cart-badge]');
        badge?.classList.add('scale-125');
        setTimeout(() => badge?.classList.remove('scale-125'), 200);
    } catch (error) {
        toast(error.response?.data?.message || 'Gagal menambahkan produk.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = original;
    }
});

/* Quantity +/- & remove */
document.addEventListener('click', async (e) => {
    const qtyBtn = e.target.closest('[data-cart-qty]');
    const removeBtn = e.target.closest('[data-cart-remove]');

    if (qtyBtn) {
        e.preventDefault();
        const input = document.querySelector(`[data-cart-qty-input="${qtyBtn.dataset.cartQty}"]`);
        if (!input) return;

        const delta = Number(qtyBtn.dataset.delta);
        const next = Number(input.value) + delta;

        if (next < 1) return;

        await updateCartQty(qtyBtn.dataset.cartQty, next);
    }

    if (removeBtn) {
        e.preventDefault();
        try {
            const { data } = await window.axios.delete(`/keranjang/${removeBtn.dataset.cartRemove}`);
            toast(data.message || 'Produk dihapus.', 'success');
            await refreshCart();
        } catch (error) {
            toast(error.response?.data?.message || 'Gagal menghapus produk.', 'error');
        }
    }
});

async function updateCartQty(itemId, quantity) {
    try {
        const { data } = await window.axios.patch(`/keranjang/${itemId}`, { quantity });
        toast(data.message || 'Keranjang diperbarui.', 'success');
        await refreshCart();
    } catch (error) {
        toast(error.response?.data?.message || 'Gagal memperbarui keranjang.', 'error');
        await refreshCart();
    }
}

window.pchUpdateCartQty = updateCartQty;

document.addEventListener('change', (e) => {
    const input = e.target.closest('[data-cart-qty-input]');
    if (!input) return;
    const qty = Math.max(1, Number(input.value) || 1);
    updateCartQty(input.dataset.cartQtyInput, qty);
});

/* -------------------------------------------------------------------------
 | Wishlist
 | ---------------------------------------------------------------------- */
function syncWishlistBadge(count) {
    document.querySelectorAll('[data-wishlist-badge]').forEach((el) => {
        el.textContent = count;
        el.classList.toggle('hidden', count === 0);
    });
}

document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-wishlist-btn]');
    if (!btn) return;

    e.preventDefault();

    if (btn.dataset.processing === '1') return;
    btn.dataset.processing = '1';

    const icon = btn.querySelector('.material-symbols-outlined');

    try {
        const { data } = await window.axios.post('/wishlist', { product_id: btn.dataset.productId });

        const active = Boolean(data.active);
        btn.classList.toggle('text-secondary', active);
        btn.classList.toggle('text-outline', !active);
        if (icon) icon.style.fontVariationSettings = active ? "'FILL' 1" : "'FILL' 0";

        syncWishlistBadge(data.count ?? 0);
        toast(data.message, 'success');

        if (active === false && btn.dataset.removeOnUncheck === '1') {
            setTimeout(() => btn.closest('[data-wishlist-item]')?.remove(), 400);
        }
    } catch (error) {
        if (error.response?.status === 401) {
            toast('Masuk terlebih dahulu untuk menyimpan favorit.', 'info');
            setTimeout(() => (window.location.href = '/masuk'), 900);
            return;
        }
        toast(error.response?.data?.message || 'Gagal menyimpan favorit.', 'error');
    } finally {
        btn.dataset.processing = '0';
    }
});

/* -------------------------------------------------------------------------
 | Live search (navbar desktop + drawer mobile)
 | ---------------------------------------------------------------------- */
const esc = (value) =>
    String(value ?? '').replace(
        /[&<>"']/g,
        (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c],
    );

const hideAllSearchDropdowns = (except = null) => {
    document.querySelectorAll('[data-live-search]').forEach((input) => {
        const dd = document.getElementById(input.dataset.liveSearch || '');
        if (!dd || dd === except) return;
        dd.classList.add('hidden');
        input.setAttribute('aria-expanded', 'false');
    });
};

function initLiveSearch(input) {
    const dropdown = document.getElementById(input.dataset.liveSearch || '');
    if (!dropdown) return;

    const clearBtn = input.parentElement?.querySelector('[data-search-clear]') || document.getElementById(input.dataset.searchClear || '');
    const baseUrl = input.dataset.searchUrl || '/produk';
    let timer = null;

    const show = () => {
        hideAllSearchDropdowns(dropdown);
        dropdown.classList.remove('hidden');
        input.setAttribute('aria-expanded', 'true');
    };
    const hide = () => {
        dropdown.classList.add('hidden');
        input.setAttribute('aria-expanded', 'false');
    };

    const renderResults = (items, keyword) => {
        if (items.length === 0) {
            dropdown.innerHTML = `
                <div class="px-4 py-6 text-center">
                    <span class="material-symbols-outlined text-[28px] text-outline block mb-1">search_off</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant font-semibold">Produk tidak ditemukan</p>
                    <p class="font-label-md text-label-md text-outline mt-0.5">Coba kata kunci lain, mis. “makanan kucing”</p>
                </div>`;
            return;
        }

        const rows = items
            .map((p) => {
                const price = Number(p.final_price ?? p.price ?? 0);
                const image = p.primary_image?.[0];
                const img = image?.url || (image?.path ? `/storage/${image.path}` : '/images/hero-card.jpg');

                return `
                    <a href="/produk/${encodeURIComponent(p.slug)}" role="option" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container-low transition-colors">
                        <img src="${esc(img)}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0 bg-surface-container-low" loading="lazy">
                        <span class="min-w-0 flex-1">
                            <span class="font-body-sm text-body-sm text-on-surface line-clamp-1">${esc(p.name)}</span>
                            <span class="font-label-md text-label-md text-primary font-bold">Rp ${new Intl.NumberFormat('id-ID').format(price)}</span>
                        </span>
                        <span class="material-symbols-outlined text-[18px] text-outline shrink-0">arrow_forward</span>
                    </a>`;
            })
            .join('');

        const footer = `
            <a href="${baseUrl}?q=${encodeURIComponent(keyword)}" class="flex items-center justify-center gap-1.5 mt-1 px-3 py-2.5 rounded-xl bg-surface-container-low text-primary font-label-lg text-label-lg hover:bg-surface-container transition-colors">
                <span>Lihat semua hasil</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>`;

        dropdown.innerHTML = rows + footer;
    };

    if (input.value.trim()) clearBtn?.classList.remove('hidden');

    input.addEventListener('input', () => {
        const val = input.value.trim();
        clearBtn?.classList.toggle('hidden', !val);
        clearTimeout(timer);

        if (val.length < 2) {
            hide();
            return;
        }

        timer = setTimeout(async () => {
            try {
                const { data } = await window.axios.get(`${baseUrl}`, {
                    params: { q: val, sort: 'popular' },
                });

                const items = Array.isArray(data.products) ? data.products : (data.products?.data ?? []);

                renderResults(items, val);
                show();
            } catch {
                hide();
            }
        }, 280);
    });

    clearBtn?.addEventListener('click', () => {
        input.value = '';
        clearBtn.classList.add('hidden');
        hide();
        input.focus();
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            hide();
            return;
        }
        if (e.key === 'Enter' && input.value.trim()) {
            e.preventDefault();
            window.location.href = `${baseUrl}?q=${encodeURIComponent(input.value.trim())}`;
        }
    });
}

document.querySelectorAll('[data-live-search]').forEach(initLiveSearch);

document.addEventListener('click', (e) => {
    if (e.target.closest('[data-live-search]') || e.target.closest('[role="listbox"]')) return;
    hideAllSearchDropdowns();
});

/* -------------------------------------------------------------------------
 | Modal
 | ---------------------------------------------------------------------- */
window.pchOpenModal = (name) => {
    const modal = document.getElementById(`${name}-modal`);
    modal?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
};

window.pchCloseModal = (name) => {
    const modal = document.getElementById(`${name}-modal`);
    modal?.classList.add('hidden');
    document.body.style.overflow = '';
};

document.addEventListener('click', (e) => {
    const closeBtn = e.target.closest('[data-modal-close]');
    if (closeBtn) {
        window.pchCloseModal(closeBtn.dataset.modalClose);
        return;
    }

    const opener = e.target.closest('[data-modal-open]');
    if (opener) {
        window.pchOpenModal(opener.dataset.modalOpen);
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;

    document.querySelectorAll('[data-modal]:not(.hidden)').forEach((m) => {
        m.classList.add('hidden');
    });
    document.body.style.overflow = '';
});

/* -------------------------------------------------------------------------
 | Flash sale countdown
 | ---------------------------------------------------------------------- */
(function initCountdown() {
    const h = document.getElementById('countdown-hours');
    const m = document.getElementById('countdown-minutes');
    const s = document.getElementById('countdown-seconds');
    if (!h || !m || !s) return;

    // Berakhiran tiap akhir pekan (Sabtu 23:59)
    const target = (() => {
        const end = new Date();
        end.setHours(23, 59, 59, 0);
        while (end.getDay() !== 6 || end <= new Date()) {
            end.setDate(end.getDate() + 1);
        }
        return end.getTime();
    })();

    const tick = () => {
        const diff = Math.max(0, Math.floor((target - Date.now()) / 1000));
        if (diff === 0) return;

        h.textContent = String(Math.floor(diff / 3600)).padStart(2, '0');
        m.textContent = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
        s.textContent = String(diff % 60).padStart(2, '0');
    };

    tick();
    setInterval(tick, 1000);
})();

/* -------------------------------------------------------------------------
 | Auto-submit filter form
 | ---------------------------------------------------------------------- */
document.querySelectorAll('[data-auto-submit]').forEach((el) => {
    el.addEventListener('change', () => el.closest('form')?.submit());
});

/* Gambar produk: preview sebelum upload + tombol hapus */
document.querySelectorAll('[data-image-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const preview = document.getElementById('image-preview');
        if (!preview) return;

        preview.innerHTML = '';

        Array.from(input.files).slice(0, 6).forEach((file) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            img.className = 'w-full aspect-square object-cover rounded-xl border border-surface-outline-app';

            const badge = document.createElement('span');
            badge.className =
                'absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-surface-container-lowest/90 text-[10px] font-semibold';
            badge.textContent = `${Math.round(file.size / 1024)}KB`;

            wrapper.append(img, badge);
            preview.appendChild(wrapper);
        });
    });
});

/* -------------------------------------------------------------------------
 | Auth: lihat/sembunyi password, isi akun demo, meter kekuatan password,
 |      serta kunci tombol submit saat form sedang diproses.
 | ---------------------------------------------------------------------- */
document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-password-toggle]');
    if (!btn) return;

    const input = document.getElementById(btn.dataset.passwordToggle);
    if (!input) return;

    const reveal = input.type === 'password';
    input.type = reveal ? 'text' : 'password';

    const icon = btn.querySelector('.material-symbols-outlined');
    if (icon) icon.textContent = reveal ? 'visibility_off' : 'visibility';

    btn.setAttribute('aria-label', reveal ? 'Sembunyikan password' : 'Tampilkan password');
});

/* Isi cepat akun demo (halaman login) */
document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-demo-fill]');
    if (!btn) return;

    const email = document.getElementById('field-email');
    const password = document.getElementById('field-password');

    if (email) {
        email.value = btn.dataset.demoEmail || '';
        email.dispatchEvent(new Event('input', { bubbles: true }));
    }

    if (password) {
        password.value = btn.dataset.demoPassword || '';
        password.dispatchEvent(new Event('input', { bubbles: true }));
        password.focus();
        password.closest('div')?.animate(
            [
                { boxShadow: '0 0 0 0 rgba(5, 150, 105, 0)' },
                { boxShadow: '0 0 0 6px rgba(5, 150, 105, 0.18)' },
                { boxShadow: '0 0 0 0 rgba(5, 150, 105, 0)' },
            ],
            { duration: 900, easing: 'ease-out' },
        );
    }
});

/* Meter kekuatan password */
document.querySelectorAll('[data-password-strength]').forEach((wrap) => {
    const input = wrap.closest('form')?.querySelector('input[name="password"]');
    const bar = wrap.querySelector('[data-strength-bar]');
    const label = wrap.querySelector('[data-strength-label]');
    if (!input || !bar || !label) return;

    const rules = {
        length: wrap.querySelector('[data-strength-rule="length"]'),
        letter: wrap.querySelector('[data-strength-rule="letter"]'),
        number: wrap.querySelector('[data-strength-rule="number"]'),
    };

    const palette = ['bg-error', 'bg-error', 'bg-amber-500', 'bg-success'];
    const labels = ['Belum diisi', 'Lemah', 'Cukup', 'Kuat'];
    const labelColors = ['text-error', 'text-error', 'text-amber-600', 'text-success'];

    const syncRule = (el, ok) => {
        if (!el) return;
        el.classList.toggle('text-success', ok);
        el.classList.toggle('font-semibold', ok);
        el.classList.toggle('text-outline', !ok);
        const icon = el.querySelector('.material-symbols-outlined');
        if (icon) icon.textContent = ok ? 'check_circle' : 'radio_button_unchecked';
    };

    const update = () => {
        const value = input.value;
        const passed = {
            length: value.length >= 8,
            letter: /[a-zA-Z]/.test(value),
            number: /[0-9]/.test(value),
        };

        const score = value ? Object.values(passed).filter(Boolean).length : 0;

        bar.style.width = `${(score / 3) * 100}%`;
        palette.forEach((cls) => bar.classList.remove(cls));
        bar.classList.add(palette[score]);
        labelColors.forEach((cls) => label.classList.remove(cls));
        label.classList.add(labelColors[score]);
        label.textContent = labels[score];

        Object.entries(rules).forEach(([key, el]) => syncRule(el, passed[key]));
    };

    input.addEventListener('input', update);
    update();
});

/* Kunci tombol submit saat form sedang diproses */
document.querySelectorAll('form[data-busy]').forEach((form) => {
    form.addEventListener('submit', () => {
        const btn = form.querySelector('button[type="submit"]:not([disabled])');
        if (!btn) return;

        btn.disabled = true;
        btn.classList.add('cursor-wait', 'opacity-80');
        btn.innerHTML =
            `<span class="flex items-center justify-center gap-2">` +
            `<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>` +
            `<span>${esc(form.dataset.busyText || 'Memproses...')}</span>` +
            `</span>`;
    });
});