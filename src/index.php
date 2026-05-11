<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Repository\ProductRepository;

$pdo = require __DIR__ . '/db.php';
$productRepo = new ProductRepository($pdo);
$products = $productRepo->getAll();

$productsHtml = '';
foreach ($products as $product) {
    $productsHtml .= '
        <div class="product-card">
            ' . ($product->image ? '<img src="' . htmlspecialchars($product->image) . '" alt="' . htmlspecialchars($product->name) . '">' : '') . '
            <h2>' . htmlspecialchars($product->name) . '</h2>
            <p class="price">' . number_format($product->price, 2) . ' руб.</p>
            <p>' . htmlspecialchars(mb_substr($product->description, 0, 100)) . '...</p>
            <a href="product.php?id=' . $product->id . '">Подробнее</a>
        </div>
    ';
}

$template = file_get_contents(__DIR__ . '/templates/catalog.html');
if ($template === false) {
    die('Не удалось загрузить шаблон catalog.html');
}

$output = str_replace('{{ products }}', $productsHtml, $template);

echo $output;