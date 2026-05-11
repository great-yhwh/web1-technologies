<?php
declare(strict_types=1);

namespace App\DTO;

final class ReviewDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly string $author,
        public readonly int $rating,
        public readonly string $text,
        public readonly string $createdAt
    ) {}
}