<?php
declare(strict_types=1);

namespace MenuItemDTO;
final class MenuItemDTO
{
    public function __construct(
        public int    $id,
        public ?int   $parentId,
        public string $title,
        public int    $sortOrder,
        public array  $children = []      // для дерева
    )
    {
    }
}