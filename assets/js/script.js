function delayedRedirect(elementId, delay) {
    const element = document.getElementById(elementId);
    if (element) {
        const url = element.dataset.url;
        if (url) {
            setTimeout(() => {
                window.location.href = url;
            }, delay);
        }
    }
}

function showProfileModal(modalId) {
    if (window.showProfileModal) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }
}

function showPasswordModal(modalId) {
    if (window.showPasswordModal) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('toggleIcon');

    // Mobile: toggle overlay open/close. Desktop: toggle expanded collapsed.
    const isMobile = window.matchMedia('(max-width: 767.98px)').matches;

    if (isMobile) {
        sidebar.classList.toggle('open');
        const expanded = sidebar.classList.contains('open');
        toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        // swap icon to X when open
        toggleIcon.innerHTML = expanded ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
    } else {
        sidebar.classList.toggle('expanded');
        const expanded = sidebar.classList.contains('expanded');
        toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        toggleIcon.innerHTML = expanded ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
        // persist desktop state in sessionStorage so navigation doesn't lose expanded state
        try { sessionStorage.setItem('sidebarExpanded', expanded ? '1' : '0'); } catch (e) { /* ignore */ }
    }
}

document.addEventListener('DOMContentLoaded', () => {

    delayedRedirect('home-redirect', 2000);
    delayedRedirect('dashboard-redirect', 2000);


    if (window.history.replaceState) {
        const url = new URL(window.location.href);

        url.searchParams.delete('success');
        url.searchParams.delete('error');

        window.history.replaceState({}, '', url.toString());
    }

    // Restore sidebar desktop state from sessionStorage
    try {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const saved = sessionStorage.getItem('sidebarExpanded');
        if (saved === '1' && sidebar && !window.matchMedia('(max-width: 767.98px)').matches) {
            sidebar.classList.add('expanded');
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
            if (toggleIcon) toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
        }
    } catch (e) { /* ignore storage errors */ }

    // Ensure the toggle button is wired up (click + keyboard) so it works even if onclick removed
    const toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', (ev) => {
            ev.preventDefault();
            try { toggleSidebar(); } catch (e) { console.error(e); }
        });
        // keyboard accessibility: Enter or Space
        toggleBtn.addEventListener('keydown', (ev) => {
            if (ev.key === 'Enter' || ev.key === ' ') {
                ev.preventDefault();
                try { toggleSidebar(); } catch (e) { console.error(e); }
            }
        });
    }

    // Close overlay sidebar when clicking outside (mobile)
    document.addEventListener('click', (e) => {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        if (!sidebar) return;
        if (!window.matchMedia('(max-width: 767.98px)').matches) return; // only for mobile overlay
        if (sidebar.classList.contains('open')) {
            const withinSidebar = sidebar.contains(e.target) || (toggleBtn && toggleBtn.contains(e.target));
            if (!withinSidebar) sidebar.classList.remove('open');
        }
    });
});


