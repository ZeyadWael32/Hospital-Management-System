document.addEventListener('DOMContentLoaded', () => {
    const homeRedirect = document.getElementById('home-redirect');
    const dashboardRedirect = document.getElementById('dashboard-redirect');
    if (homeRedirect) {
        const homeUrl = homeRedirect.dataset.url;
        if (homeUrl) {
            setTimeout(() => {
                window.location.href = homeUrl;
            }, 3000);
        }
    }
    if (dashboardRedirect) {
        const dashboardUrl = dashboardRedirect.dataset.url;
        if (dashboardUrl) {
            setTimeout(() => {
                window.location.href = dashboardUrl;
            }, 3000);
        }
    }
});