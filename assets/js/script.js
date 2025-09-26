document.addEventListener('DOMContentLoaded', () => {
    function delayedRedirect(elementId, delay = 2000) {
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

    delayedRedirect('home-redirect');
    delayedRedirect('dashboard-redirect');
});


