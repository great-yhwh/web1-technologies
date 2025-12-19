if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init)
} else {
    init()
}

function init() {
    const data = {
        name: 'Каталог товаров',
        hasChildren: true,
        items: [
            {
                name: 'Мойки',
                hasChildren: true,
                items: [
                    {
                        name: 'Ulgran',
                        hasChildren: true,
                        items: [
                            {
                                name: 'Smth',
                                hasChildren: false,
                                items: []
                            },
                        ]
                    },
                    {
                        name: 'Vigro Mramor',
                        hasChildren: false,
                        items: []
                    },
                    {
                        name: 'Handmade',
                        hasChildren: true,
                        items: [
                            {
                                name: 'Smth',
                                hasChildren: false,
                                items: []
                            },
                            {
                                name: 'Smth',
                                hasChildren: false,
                                items: []
                            }
                        ]
                    },
                    {
                        name: 'Vigro Glass',
                        hasChildren: false,
                        items: []
                    }
                ]
            },
            {
                name: 'Фильтры',
                hasChildren: true,
                items: [
                    {
                        name: 'Ulgran',
                        hasChildren: true,
                        items: [
                            {
                                name: 'Smth',
                                hasChildren: false,
                                items: []
                            },
                            {
                                name: 'Smth',
                                hasChildren: false,
                                items: []
                            }
                        ]
                    },
                    {
                        name: 'Vigro Mramor',
                        hasChildren: false,
                        items: []
                    }
                ]
            }
        ]
    };

    const items = new ListItems(document.getElementById('list-items'), data)

    items.render()
    items.init()

    console.log(items.renderTest(data));

    function ListItems(el, data) {
        this.el = el;
        this.data = data;

        // Иконка стрелки
        const arrowIcon = `
            <svg class="list-item__arrow" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
            </svg>`;

        // Иконка папки
        const folderIcon = `
            <svg class="list-item__folder" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
            </svg>`;

        this.init = function () {
            const parents = this.el.querySelectorAll('[data-parent]')

            parents.forEach(parent => {
                const open = parent.querySelector('.list-item__arrow')

                open.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleItems(parent);
                });
            })
        }

        this.render = function () {
            this.el.insertAdjacentHTML('beforeend', this.renderParent(this.data))
        }

        this.renderParent = function (data) {
            let html = `
            <div class="list-item list-item_open" data-parent>
                <div class="list-item__inner">
                    ${arrowIcon}
                    ${folderIcon}
                    <span>${data.name}</span>
                </div>
                <div class="list-item__items">`;

            if (data.hasChildren) {
                data.items.forEach(item => {
                    if (item.hasChildren) {
                        html += this.renderParent(item);
                    } else {
                        html += this.renderChildren(item);
                    }
                });
            }

            html += `
                    </div>
                </div>`;

            return html;
        }

        this.renderChildren = function (data) {
            return `
            <div class="list-item">
                <div class="list-item__inner">
                    <div class="list-item__arrow placeholder"></div>
                    ${folderIcon}
                    <span>${data.name}</span>
                </div>
            </div>`;
        }

        this.toggleItems = function (parent) {
            parent.classList.toggle('list-item_open')
        }

        this.renderTest = function (data) {
            return `<div class="test">${data.name}</div>`
        }
    }
}