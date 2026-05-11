<?php
declare(strict_types=1);

use App\Repository\ReviewRepository;

function doFeedbackAction(
    ReviewRepository $reviewRepo,
    array $post,
    int $productId
): void {
    $action = $post['action'] ?? '';

    switch ($action) {
        case 'add':
            $author = trim($post['author'] ?? '');
            $rating = (int)($post['rating'] ?? 0);
            $text = trim($post['text'] ?? '');
            if ($author && $rating >= 1 && $rating <= 5 && $text) {
                $reviewRepo->add($productId, $author, $rating, $text);
            }
            break;

        case 'edit':
            $reviewId = (int)($post['review_id'] ?? 0);
            $author = trim($post['author'] ?? '');
            $rating = (int)($post['rating'] ?? 0);
            $text = trim($post['text'] ?? '');
            if ($reviewId && $author && $rating >= 1 && $rating <= 5 && $text) {
                $reviewRepo->update($reviewId, $author, $rating, $text);
            }
            break;

        case 'delete':
            $reviewId = (int)($post['review_id'] ?? 0);
            if ($reviewId) {
                $reviewRepo->delete($reviewId);
            }
            break;
    }
}