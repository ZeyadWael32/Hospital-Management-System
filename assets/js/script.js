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

// Sidebar toggle removed per user request. Sidebar will always be visible.

document.addEventListener('DOMContentLoaded', () => {

    delayedRedirect('home-redirect', 2000);
    delayedRedirect('dashboard-redirect', 2000);


    if (window.history.replaceState) {
        const url = new URL(window.location.href);

        url.searchParams.delete('success');
        url.searchParams.delete('error');

        window.history.replaceState({}, '', url.toString());
    }

    // Sidebar toggle removed — no persisted state to restore.

    // Toggle button removed from markup — nothing to wire here.

    // No overlay behavior needed since sidebar is always visible.
});


