import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const button = document.getElementById('btnMenu');

    const close = () => {
        sidebar?.classList.remove('is-open');
        overlay?.classList.add('hidden');
    };

    button?.addEventListener('click', () => {
        sidebar?.classList.toggle('is-open');
        overlay?.classList.toggle('hidden');
    });

    overlay?.addEventListener('click', close);

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
