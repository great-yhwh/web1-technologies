const showAddBtn = document.getElementById('showAddReviewBtn');
const addFormDiv = document.getElementById('addReviewForm');
const cancelAddBtn = document.getElementById('cancelAddReviewBtn');
if (showAddBtn) {
    showAddBtn.addEventListener('click', () => {
        addFormDiv.style.display = 'block';
        addFormDiv.scrollIntoView({ behavior: 'smooth' });
    });
}
if (cancelAddBtn) {
    cancelAddBtn.addEventListener('click', () => {
        addFormDiv.style.display = 'none';
    });
}

const editFormDiv = document.getElementById('editReviewForm');
const cancelEditBtn = document.getElementById('cancelEditReviewBtn');
document.querySelectorAll('.edit-review').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit_review_id').value = btn.dataset.id;
        document.getElementById('edit_author').value = btn.dataset.author;
        const rating = parseInt(btn.dataset.rating);
        const radioId = `edit_star${rating}`;
        if (document.getElementById(radioId)) {
            document.getElementById(radioId).checked = true;
        }
        document.getElementById('edit_text').value = btn.dataset.text;
        editFormDiv.style.display = 'block';
        editFormDiv.scrollIntoView({ behavior: 'smooth' });
    });
});
if (cancelEditBtn) {
    cancelEditBtn.addEventListener('click', () => {
        editFormDiv.style.display = 'none';
    });
}

document.querySelectorAll('.close-form').forEach(closeBtn => {
    closeBtn.addEventListener('click', () => {
        closeBtn.closest('.review-form').style.display = 'none';
    });
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (addFormDiv) addFormDiv.style.display = 'none';
        if (editFormDiv) editFormDiv.style.display = 'none';
    }
});

let currentRatingFilter = 'all';
let currentSearch = '';

function filterReviews() {
    const reviews = document.querySelectorAll('.review-item');
    reviews.forEach(review => {
        const rating = review.dataset.rating;
        const text = review.dataset.text.toLowerCase();
        const matchesRating = currentRatingFilter === 'all' || parseInt(rating) === parseInt(currentRatingFilter);
        const matchesSearch = text.includes(currentSearch.toLowerCase());
        if (matchesRating && matchesSearch) {
            review.style.display = '';
        } else {
            review.style.display = 'none';
        }
    });
}

document.querySelectorAll('.rating-filters button').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.rating-filters button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentRatingFilter = btn.dataset.rating;
        filterReviews();
    });
});

const searchInput = document.getElementById('searchReviews');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        currentSearch = e.target.value;
        filterReviews();
    });
}

document.querySelectorAll('.review-item').forEach(item => {
    if (!item.dataset.text) {
        const textElem = item.querySelector('p')?.innerText || '';
        const authorElem = item.querySelector('.review-header strong')?.innerText || '';
        item.dataset.text = (authorElem + ' ' + textElem).toLowerCase();
    }
});