<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;

$pdo = require __DIR__ . '/db.php';
$productRepo = new ProductRepository($pdo);
$reviewRepo = new ReviewRepository($pdo);

$id = (int)($_GET['id'] ?? 0);
$product = $productRepo->getById($id);
if (!$product) {
    die('Товар не найден');
}

// Обработка CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    require_once __DIR__ . '/feedback_action.php';
    doFeedbackAction($reviewRepo, $_POST, $id);
    header("Location: product.php?id=$id");
    exit;
}

$reviews = $reviewRepo->getByProductId($id);

$totalRating = 0;
$totalCount = count($reviews);
foreach ($reviews as $review) {
    $totalRating += $review->rating;
}
$avgRating = $totalCount > 0 ? round($totalRating / $totalCount, 1) : 0;
$starsFull = floor($avgRating);
$starsHalf = ($avgRating - $starsFull) >= 0.5 ? 1 : 0;

$starsFullInt = (int)$starsFull;
$starsHalfInt = $starsHalf;
$starsEmptyInt = 5 - $starsFullInt - $starsHalfInt;
$ratingStars = str_repeat('★', $starsFullInt) . ($starsHalf ? '½' : '') . str_repeat('☆', $starsEmptyInt);

// Генерируем HTML для отзывов
$reviewsHtml = '';
foreach ($reviews as $review) {
    $reviewStars = '';
    for ($i = 1; $i <= 5; $i++) {
        $reviewStars .= $i <= $review->rating ? '★' : '☆';
    }
    $reviewsHtml .= '
        <div class="review-item" data-rating="' . $review->rating . '" data-text="' . htmlspecialchars($review->text . ' ' . $review->author) . '">
            <div class="review-header">
                <strong>' . htmlspecialchars($review->author) . '</strong>
                <div class="review-rating">' . $reviewStars . '</div>
                <small>' . $review->createdAt . '</small>
            </div>
            <p>' . nl2br(htmlspecialchars($review->text)) . '</p>
            <div class="review-actions">
                <button class="edit-review"
                    data-id="' . $review->id . '"
                    data-author="' . htmlspecialchars($review->author) . '"
                    data-rating="' . $review->rating . '"
                    data-text="' . htmlspecialchars($review->text) . '">✏️ Редактировать</button>
                <form method="post" style="display:inline">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="review_id" value="' . $review->id . '">
                    <button type="submit" onclick="return confirm(\'Удалить отзыв?\')">🗑️ Удалить</button>
                </form>
            </div>
        </div>';
}
if (empty($reviews)) {
    $reviewsHtml = '<p class="no-reviews">Пока нет отзывов. Будьте первым!</p>';
}

// Загружаем шаблон
$template = file_get_contents(__DIR__ . '/templates/product.html');
if ($template === false) {
    die('Не удалось загрузить шаблон product.html');
}

$replacements = [
    '{{ page_title }}' => htmlspecialchars($product->name),
    '{{ product_name }}' => htmlspecialchars($product->name),
    '{{ product_image }}' => $product->image ? '<img src="' . htmlspecialchars($product->image) . '" alt="' . htmlspecialchars($product->name) . '">' : '',
    '{{ product_price }}' => number_format($product->price, 2),
    '{{ product_description }}' => nl2br(htmlspecialchars($product->description)),
    '{{ avg_rating }}' => $avgRating,
    '{{ rating_stars }}' => $ratingStars,
    '{{ reviews_count }}' => $totalCount,
    '{{ reviews_list }}' => $reviewsHtml,
];

$output = str_replace(array_keys($replacements), array_values($replacements), $template);

echo $output;