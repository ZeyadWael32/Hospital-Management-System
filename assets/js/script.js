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
    const toggleButton = document.getElementById('toggleIcon');

    sidebar.classList.toggle('expanded');

    if (sidebar.classList.contains('expanded')) {
        toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
    } else {
        toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
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
});


