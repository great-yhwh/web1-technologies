(function() {
    const flash = document.getElementById('flashMessage');
    if (flash && flash.innerText.trim() !== '') {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(() => {
                if (flash.parentNode) flash.remove();
            }, 500);
        }, 4000);
    }

    const form = document.getElementById('uploadForm');
    const fileInput = document.getElementById('imageInput');
    const clientErrorSpan = document.getElementById('clientError');

    if (form && fileInput) {
        form.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            if (!file) {
                clientErrorSpan.textContent = 'Выберите файл.';
                e.preventDefault();
                return;
            }
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                clientErrorSpan.textContent = 'Разрешены только форматы JPG, PNG и GIF.';
                e.preventDefault();
                return;
            }
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                clientErrorSpan.textContent = 'Размер файла не должен превышать 5 МБ.';
                e.preventDefault();
                return;
            }
            clientErrorSpan.textContent = '';
        });
        fileInput.addEventListener('change', function() {
            clientErrorSpan.textContent = '';
        });
    }

    const manageBtn = document.getElementById('manageBtn');
    const gallery = document.getElementById('gallery');
    const modal = document.getElementById('deleteModal');
    const modalConfirm = document.getElementById('modalConfirm');
    const modalCancel = document.getElementById('modalCancel');
    let currentFilename = null;
    let currentGalleryItem = null;
    let deleteMode = false;

    if (!manageBtn || !gallery || !modal) return;

    function enableDeleteMode() {
        deleteMode = true;
        gallery.classList.add('delete-mode');
        manageBtn.textContent = 'Отмена';
        manageBtn.classList.add('cancel-mode');
    }

    function disableDeleteMode() {
        deleteMode = false;
        gallery.classList.remove('delete-mode');
        manageBtn.textContent = 'Удалить фото';
        manageBtn.classList.remove('cancel-mode');
        currentFilename = null;
        currentGalleryItem = null;
    }

    manageBtn.addEventListener('click', () => {
        if (deleteMode) {
            disableDeleteMode();
        } else {
            enableDeleteMode();
        }
    });

    gallery.addEventListener('click', (e) => {
        const deleteBtn = e.target.closest('.delete-btn');
        if (!deleteBtn) return;
        if (!deleteMode) return;

        e.preventDefault();
        const filename = deleteBtn.getAttribute('data-file');
        const galleryItem = deleteBtn.closest('.gallery-item');
        if (!filename || !galleryItem) return;

        currentFilename = filename;
        currentGalleryItem = galleryItem;
        document.getElementById('modalMessage').innerText = `Удалить фото "${filename}"?`;
        modal.classList.add('active');
    });

    function performDelete() {
        if (!currentFilename || !currentGalleryItem) return;
        fetch(window.location.href, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'delete_image=' + encodeURIComponent(currentFilename)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentGalleryItem.remove();
                    const photoCountSpan = document.querySelector('.photo-count');
                    if (photoCountSpan) {
                        let count = parseInt(photoCountSpan.textContent);
                        if (!isNaN(count)) {
                            photoCountSpan.textContent = (count - 1) + ' фото';
                        }
                    }
                    if (gallery.children.length === 0) {
                        location.reload();
                    }
                    if (deleteMode) disableDeleteMode();
                } else {
                    alert('Не удалось удалить файл.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Ошибка при удалении.');
            })
            .finally(() => {
                modal.classList.remove('active');
                currentFilename = null;
                currentGalleryItem = null;
            });
    }

    modalConfirm.addEventListener('click', performDelete);
    modalCancel.addEventListener('click', () => {
        modal.classList.remove('active');
        currentFilename = null;
        currentGalleryItem = null;
    });
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
            currentFilename = null;
            currentGalleryItem = null;
        }
    });
})();