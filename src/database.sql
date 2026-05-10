CREATE TABLE menu_items (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            parent_id INTEGER NULL,
                            title TEXT NOT NULL,
                            sort_order INTEGER NOT NULL DEFAULT 0,
                            FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

INSERT INTO menu_items (parent_id, title, sort_order) VALUES
                                                          (NULL, 'Каталог товаров', 1),

                                                          (1, 'Мойки', 1),
                                                          (2, 'Ulgran', 1),
                                                          (3, 'Smth', 1),
                                                          (2, 'Vigro Mramor', 2),
                                                          (2, 'Handmade', 3),
                                                          (6, 'Smth', 1),
                                                          (6, 'Smth', 2),
                                                          (2, 'Vigro Glass', 4),

                                                          (1, 'Фильтры', 2),
                                                          (10, 'Ulgran', 1),
                                                          (11, 'Smth', 1),
                                                          (11, 'Smth', 2),
                                                          (10, 'Vigro Mramor', 2);