import { Catalog } from "./src/components/catalog.js";

const renderPostItem = (item) => `
    <a  
        href="posts.html?id=${item.id}"
        class="post-item"
    >
        <span class="post-item__title">${item.title}</span>
        <span class="post-item__body">${item.body}</span>
    </a>
`;

const getPostItems = async ({ limit, page }) => {
    let response;
    try {
        response = await fetch(
            `https://jsonplaceholder.typicode.com/posts?_limit=${limit}&_page=${page}`
        );
    } catch (error) {
        console.error("Ошибка при загрузке постов:", error);
        const catalogEl = document.getElementById('catalog');
        if (catalogEl) {
            catalogEl.innerHTML = `
                <p style="color: red; padding: 30px; text-align: center;">
                    Не удалось загрузить посты.<br><br>
                    ${error.message}<br><br>
                    <button onclick="location.reload()">Повторить попытку</button>
                </p>`;
        }
        throw error;
    }

    if (!response.ok) {
        const error = new Error(`HTTP error! Status: ${response.status}`);
        console.error("Ошибка при загрузке постов:", error);
        const catalogEl = document.getElementById('catalog');
        if (catalogEl) {
            catalogEl.innerHTML = `
                <p style="color: red; padding: 30px; text-align: center;">
                    Не удалось загрузить посты.<br><br>
                    ${error.message}<br><br>
                    <button onclick="location.reload()">Повторить попытку</button>
                </p>`;
        }
        throw error;
    }

    const total = +response.headers.get('x-total-count');
    const items = await response.json();
    return { items, total };
};

const init = () => {
    const catalogElement = document.getElementById("catalog");

    new Catalog(catalogElement, {
        renderItem: renderPostItem,
        getItems: getPostItems
    }).init();
};

// Запуск
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}