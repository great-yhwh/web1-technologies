<?php
const TEMPLATES_DIR = __DIR__ . '/src/templates/';
const LAYOUTS_DIR   = __DIR__ . '/src/layouts/';

$page = $_GET['page'] ?? 'index';
$params = [];

switch ($page) {
    case 'index':
        $params['title'] = 'Главная';
        $params['content'] = '<p>Добро пожаловать! Выберите задание в меню.</p>';
        break;

    case 'catalog':
        $params['title'] = 'Каталог';
        $params['catalog'] = getCatalog();
        break;

    case 'about':
        $params['title'] = 'О нас';
        $params['phone'] = '+7 495 12-23-12';
        break;

    case 'task1':
        $params['title'] = 'Задание 1: do…while';
        $params['content'] = renderTemplate('task1');
        break;
    case 'task2':
        $params['title'] = 'Задание 2: Области и города';
        $params['content'] = renderTemplate('task2');
        break;
    case 'task3':
        $params['title'] = 'Задание 3: Транслитерация';
        $params['content'] = renderTemplate('task3');
        break;
    case 'task4':
        $params['title'] = 'Задание 4: Простое меню (цикл)';
        $params['content'] = renderTemplate('task4');
        break;
    case 'task5':
        $params['title'] = 'Задание 5: Вложенное меню (рекурсия)';
        $params['content'] = renderTemplate('task5');
        break;
    case 'task6':
        $params['title'] = 'Задание 6: Города на букву "К"';
        $params['content'] = renderTemplate('task6');
        break;

    case 'apicatalog':
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(getCatalog(), JSON_UNESCAPED_UNICODE);
        exit;

    default:
        http_response_code(404);
        echo "404 страница не найдена";
        exit;
}

function getCatalog(): array
{
    return [
        ['name' => 'Яблоко', 'price' => 24, 'image' => 'apple.png'],
        ['name' => 'Банан', 'price' => 12, 'image' => 'banana.png'],
        ['name' => 'Апельсин', 'price' => 15, 'image' => 'orange.png'],
    ];
}

function getMenu(): array
{
    return [
        ['title' => 'Задание 1', 'page' => 'task1'],
        ['title' => 'Задание 2', 'page' => 'task2'],
        ['title' => 'Задание 3', 'page' => 'task3'],
        ['title' => 'Задание 4', 'page' => 'task4'],
        ['title' => 'Задание 5', 'page' => 'task5'],
        ['title' => 'Задание 6', 'page' => 'task6'],
    ];
}


function renderTemplate($template, $params = []) {
    $file = TEMPLATES_DIR . $template . '.php';
    if (!file_exists($file)) {
        $file = LAYOUTS_DIR . $template . '.php';
    }
    if (!file_exists($file)) {
        return "Template not found: $template";
    }
    extract($params);
    ob_start();
    include $file;
    return ob_get_clean();
}

function render($page, $params = []) {
    $layoutParams = [
        'title'   => $params['title'] ?? '',
        'menu'    => renderTemplate('menu', ['menus' => getMenu()]),
        'content' => $params['content'] ?? renderTemplate($page, $params)
    ];
    return renderTemplate('main', $layoutParams);
}

echo render($page, $params);