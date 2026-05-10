<?php
declare(strict_types=1);

namespace MenuItemDTO;

use PDO;

require_once __DIR__ . '/MenuItemDTO.php';

final readonly class MenuItemRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getAllFlat(): array
    {
        /** @noinspection SqlResolve */
        $stmt = $this->pdo->query("SELECT id, parent_id, title, sort_order FROM menu_items");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn(array $row) => new MenuItemDTO(
                id: (int)$row['id'],
                parentId: $row['parent_id'] === null ? null : (int)$row['parent_id'],
                title: $row['title'],
                sortOrder: (int)$row['sort_order']
            ),
            $rows
        );
    }

    public function getTree(): array
    {
        $flat = $this->getAllFlat();
        $grouped = [];

        foreach ($flat as $item) {
            $grouped[$item->parentId ?? 'null'][] = $item;
        }

        $build = function (?int $parentId = null) use (&$build, $grouped): array {
            $key = $parentId === null ? 'null' : (string)$parentId;
            $branch = $grouped[$key] ?? [];

            foreach ($branch as $item) {
                $item->children = $build($item->id);
            }

            usort($branch, fn(MenuItemDTO $a, MenuItemDTO $b) => $a->sortOrder === $b->sortOrder ? $a->id <=> $b->id : $a->sortOrder <=> $b->sortOrder
            );

            return $branch;
        };

        return $build();
    }
}