<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\ProductDTO;
use PDO;

final class ProductRepository
{
    public function __construct(private PDO $pdo) {}

    /** @return ProductDTO[] */
    public function getAll(): array
    {
        /** @noinspection SqlResolve */
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY id");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->hydrate($r), $rows);
    }

    public function getById(int $id): ?ProductDTO
    {
        /** @noinspection SqlResolve */
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    private function hydrate(array $row): ProductDTO
    {
        return new ProductDTO(
            id: (int)$row['id'],
            name: $row['name'],
            image: $row['image'],
            price: (float)$row['price'],
            description: $row['description']
        );
    }
}