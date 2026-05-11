<?php
declare(strict_types=1);

namespace App\DTO;

final class ProductDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $image,
        public readonly float $price,
        public readonly string $description
    ) {}
}