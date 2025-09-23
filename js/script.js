document.addEventListener('DOMContentLoaded', () => {
    const homeRedirect = document.getElementById('home-redirect');
    if (homeRedirect) {
        const homeUrl = homeRedirect.dataset.homeUrl;
        if (homeUrl) {
            setTimeout(() => {
                window.location.href = homeUrl;
            }, 3000);
        }
    }           
});