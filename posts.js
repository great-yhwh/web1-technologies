const getPost = async (id) => {
    const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}`);
    if (!response.ok) {
        if (response.status === 404) {
            throw new Error('Пост не найден');
        }
        throw new Error(`Ошибка сервера: ${response.status}`);
    }
    return response.json();
};

const getComments = async (id) => {
    const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}/comments`);
    if (!response.ok) {
        throw new Error(`Не удалось загрузить комментарии (статус ${response.status})`);
    }
    return response.json();
};

const renderPost = (post) => `
    <div class="post-detail">
        <h1>${post.title}</h1>
        <p>${post.body.replace(/\n/g, '<br>')}</p>
    </div>
`;

const renderComments = (comments) => {
    if (!comments || comments.length === 0) {
        return "<p>Комментариев пока нет.</p>";
    }

    return `
        <h2>Комментарии (${comments.length})</h2>
        <div class="comments-list">
            ${comments.map(comment => `
                <div class="comment">
                    <h4>${comment.name}</h4>
                    <p class="comment-email">${comment.email}</p>
                    <p>${comment.body}</p>
                </div>
            `).join('')}
        </div>
    `;
};

const loadPostData = async () => {
    const postContainer = document.getElementById("post-container");
    const commentsContainer = document.getElementById("comments-container");

    const params = new URLSearchParams(window.location.search);
    const postId = params.get("id");

    // Проверяем ID сразу, без try/catch
    if (!postId || isNaN(+postId)) {
        postContainer.innerHTML = `
            <div class="error">
                <h2>Ошибка загрузки</h2>
                <p>Некорректный ID поста в URL</p>
                <a href="catalog.html" style="color: blue;">← Вернуться к списку постов</a>
            </div>`;
        commentsContainer.innerHTML = "";
        return;
    }

    postContainer.innerHTML = "<p>Загрузка поста...</p>";
    commentsContainer.innerHTML = "<p>Загрузка комментариев...</p>";

    try {
        const [post, comments] = await Promise.all([
            getPost(postId),
            getComments(postId)
        ]);

        postContainer.innerHTML = renderPost(post);
        commentsContainer.innerHTML = renderComments(comments);
    } catch (error) {
        console.error(error);
        postContainer.innerHTML = `
            <div class="error">
                <h2>Ошибка загрузки</h2>
                <p>${error.message}</p>
                <a href="catalog.html" style="color: blue;">← Вернуться к списку постов</a>
            </div>`;
        commentsContainer.innerHTML = "";
    }
};

// Инициализация
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", loadPostData);
} else {
    loadPostData();
}