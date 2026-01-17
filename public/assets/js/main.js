document.addEventListener('DOMContentLoaded', function() {
    let links = document.querySelectorAll('.js-links a');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let url = this.href;
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(response) {
                window.history.pushState({}, '', response.url);
                return response.text();
            })
            .then(function(html) {
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                let newContent = tempDiv.querySelector('#content').innerHTML;
                
                document.getElementById('content').innerHTML = newContent;
                
            })
            .catch(function(err) {
                console.log('Coś nie działa');
                window.location.href = url;
            });
        });
    })

    let form = document.querySelector('#content form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let url = this.action;
            let formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(response) {
                window.history.pushState({}, '', response.url);
                return response.text();
            })
            .then(function(html) {
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                let newContent = tempDiv.querySelector('#content').innerHTML;
                document.getElementById('content').innerHTML = newContent;
            })
            .catch(function(err) {
                window.location.reload(); 
            });
        });
    }

    window.onpopstate = function() {
        location.reload();
    };
});