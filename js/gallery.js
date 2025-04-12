document.addEventListener("DOMContentLoaded", function () {
	const likeButtons = document.querySelectorAll('.like-button');

	likeButtons.forEach(button => {
		button.addEventListener('click', function() {
			const imageId = this.dataset.imageId;

			// Send AJAX request to the server
			fetch('', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: `image_id=${imageId}`
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					if (data.liked) {
						this.classList.add('liked');
						this.innerHTML = '<i class="fas fa-heart"></i> Liked';
					} else {
						this.classList.remove('liked');
						this.innerHTML = '<i class="fas fa-heart"></i>';
					}

					// Update the like count on the page
					const likeCountElement = this.closest('.image-container').querySelector('.like-count');
					likeCountElement.textContent = data.likeCount;
				} else {
					alert('Error processing your like request.');
				}
			})
			.catch(error => console.error('Error:', error));
		});
	});
});