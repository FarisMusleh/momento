// Toggle like/unlike action using AJAX
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function () {
			if (!isLoggedIn) {
                window.location.href = 'account/login.php';
                return;
            }
            const imageId = this.dataset.imageId;
            const action = this.classList.contains('liked-button') ? 'unlike' : 'like';
            const btn = this;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'like_unlike.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    if (action === 'like') {
                        btn.classList.add('liked-button');
                    } else {
                        btn.classList.remove('liked-button');
                    }
                }
            };

            xhr.send(`image_id=${imageId}&action=${action}`);
        });
    });
});