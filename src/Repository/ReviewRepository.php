<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\ReviewDTO;
use PDO;

final class ReviewRepository
{
    public function __construct(private PDO $pdo) {}

    /** @return ReviewDTO[] */
    public function getByProductId(int $productId): array
    {
        /** @noinspection SqlResolve */
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
        $stmt->execute([$productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->hydrate($r), $rows);
    }

    public function add(int $productId, string $author, int $rating, string $text): void
    {
        /** @noinspection SqlResolve */
        $stmt = $this->pdo->prepare("
            INSERT INTO reviews (product_id, author, rating, text) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$productId, $author, $rating, $text]);
    }

    public function update(int $reviewId, string $author, int $rating, string $text): void
    {
        /** @noinspection SqlResolve */
        $stmt = $this->pdo->prepare("
            UPDATE reviews SET author = ?, rating = ?, text = ? WHERE id = ?
        ");
        $stmt->execute([$author, $rating, $text, $reviewId]);
    }

    public function delete(int $reviewId): void
    {
        /** @noinspection SqlResolve */
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$reviewId]);
    }

    private function hydrate(array $row): ReviewDTO
    {
        return new ReviewDTO(
            id: (int)$row['id'],
            productId: (int)$row['product_id'],
            author: $row['author'],
            rating: (int)$row['rating'],
            text: $row['text'],
            createdAt: $row['created_at']
        );
    }
}