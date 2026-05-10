if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

function init() {
    const menu = document.getElementById('list-items');
    if (!menu) return;

    const items = new ListItems(menu);
    items.init();
}

function ListItems(el) {
    this.el = el;

    this.init = function () {
        this.el.addEventListener('click', (e) => {
            const arrow = e.target.closest('.list-item__arrow');

            if (!arrow || arrow.classList.contains('placeholder')) {
                return;
            }

            const parent = arrow.closest('[data-parent]');
            if (!parent) {
                return;
            }

            e.stopPropagation();
            this.toggleItems(parent);
        });
    };

    this.toggleItems = function (parent) {
        parent.classList.toggle('list-item_open');
    };
}