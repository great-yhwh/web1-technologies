-- Таблица товаров
CREATE TABLE products (
                          id INTEGER PRIMARY KEY AUTOINCREMENT,
                          name TEXT NOT NULL,
                          image TEXT,                -- путь к файлу (относительный)
                          price DECIMAL(10,2) NOT NULL,
                          description TEXT NOT NULL
);

-- Таблица отзывов
CREATE TABLE reviews (
                         id INTEGER PRIMARY KEY AUTOINCREMENT,
                         product_id INTEGER NOT NULL,
                         author TEXT NOT NULL,
                         rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
                         text TEXT NOT NULL,
                         created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
-- Добавим пару тестовых товаров
INSERT INTO products (name, image, price, description) VALUES
                                                           ('Ноутбук Pro', 'img/notebook.jpg', 1200.00, 'Мощный ноутбук для работы и игр'),
                                                           ('Мышь беспроводная', 'img/mouse.jpg', 25.99, 'Компактная мышь с тихими кликами');