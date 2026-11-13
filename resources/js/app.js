import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuButton = document.getElementById('btnMenu');
    const toggleButton = document.getElementById('btnSidebarToggle');
    const root = document.documentElement;
    const storageKey = 'tani:sidebar-collapsed';
    const desktop = window.matchMedia('(min-width: 1024px)');

    const close = () => {
        sidebar?.classList.remove('is-open');
        overlay?.classList.add('hidden');
    };

    const syncToggleLabel = () => {
        const collapsed = root.classList.contains('sidebar-collapsed');
        const label = collapsed ? 'Buka sidebar' : 'Tutup sidebar';

        toggleButton?.setAttribute('aria-label', label);
        toggleButton?.setAttribute('title', label);
    };

    const setCollapsed = (collapsed) => {
        root.classList.toggle('sidebar-collapsed', collapsed);
        syncToggleLabel();

        try {
            localStorage.setItem(storageKey, collapsed ? '1' : '0');
        } catch (error) {}
    };

    syncToggleLabel();

    menuButton?.addEventListener('click', () => {
        sidebar?.classList.toggle('is-open');
        overlay?.classList.toggle('hidden');
    });

    toggleButton?.addEventListener('click', () => {
        if (desktop.matches) {
            setCollapsed(!root.classList.contains('sidebar-collapsed'));
        } else {
            close();
        }
    });

    overlay?.addEventListener('click', close);

    sidebar?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (!desktop.matches) close();
        });
    });

    desktop.addEventListener('change', (event) => {
        if (event.matches) close();
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') close();
    });

    document.querySelectorAll('.alert-auto').forEach((alert) => {
        window.setTimeout(() => {
            alert.style.transition = 'opacity .35s ease, transform .35s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-6px)';
            window.setTimeout(() => alert.remove(), 380);
        }, 5000);
    });
});
