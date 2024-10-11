import axios from 'axios';

document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.querySelector('input[type="file"]');
    const imagePreview = document.createElement('img');
    imagePreview.classList.add('mt-4', 'max-w-full', 'h-auto');
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    if (!imageInput.nextElementSibling) {
                        imageInput.parentElement.appendChild(imagePreview);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // Set the CSRF token for all axios requests
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Like functionality
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('like-button')) {
            const postId = e.target.dataset.postId;
            toggleLike(postId);
        }
    });

    // Comment functionality
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.classList.contains('comment-form')) {
            e.preventDefault();
            const postId = e.target.dataset.postId;
            const content = e.target.querySelector('input[name="content"]').value;
            addComment(postId, content, e.target);
        }
    });
});

function toggleLike(postId) {
    axios.post(`/api/posts/${postId}/like`)
        .then(response => {
            const likeButton = document.querySelector(`#post-${postId} .like-button`);
            const likeCount = likeButton.querySelector('.like-count');
            likeCount.textContent = parseInt(likeCount.textContent) + (response.status === 201 ? 1 : -1);
        })
        .catch(error => console.error('Error:', error));
}

function addComment(postId, content, form) {
    axios.post(`/api/posts/${postId}/comments`, { content })
        .then(response => {
            console.log('Comment response:', response.data);
            const commentsDiv = document.querySelector(`#post-${postId} .comments`);
            const newComment = document.createElement('div');
            newComment.classList.add('mb-2');
            newComment.innerHTML = `<span class="font-bold">${response.data.user.name}:</span> ${response.data.content}`;
            commentsDiv.appendChild(newComment);
            form.reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add comment. Please try again.');
        });
}