

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.js-links a');
        if (!link) return;

        e.preventDefault();

        fetch(link.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            window.history.pushState({}, '', response.url);
            return response.text();
        })
        .then(html => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            const newContent = tempDiv.querySelector('#content');
            if (newContent) {
                document.getElementById('content').innerHTML = newContent.innerHTML;
            }
        })
        .catch(() => {
            window.location.href = link.href;
        });
    });

    document.addEventListener('submit', function (e) {
        const form = e.target;

        if (!form.closest('#content')) return;

        e.preventDefault();

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            window.history.pushState({}, '', response.url);
            return response.text();
        })
        .then(html => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            const newContent = tempDiv.querySelector('#content');
            if (newContent) {
                document.getElementById('content').innerHTML = newContent.innerHTML;
            }
        })
        .catch(() => {
            window.location.reload();
        });
    });

    window.addEventListener('popstate', function () {
        location.reload();
    });

});
