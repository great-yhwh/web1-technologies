<?php
declare(strict_types=1);

$pdo = require __DIR__ . '/db.php';

use MenuItemDTO\MenuItemRepository;
require_once __DIR__ . '/MenuItemDTO.php';
require_once __DIR__ . '/MenuItemRepository.php';

$repository = new MenuItemRepository($pdo);
$tree = $repository->getTree();

function renderMenu(array $items, int $level = 0): string
{
    $arrowIcon = '<img src="img/chevron-down.png" class="list-item__arrow" alt="toggle">';
    $folderIcon = '<img src="img/folder.png" class="list-item__folder" alt="folder">';

    $html = '';
    foreach ($items as $item) {
        $title = htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
        $hasChildren = !empty($item->children);

        if ($hasChildren) {
            $openClass = $level === 0 ? ' list-item_open' : '';
            $html .= sprintf('
                <div class="list-item%s" data-parent data-id="%d">
                    <div class="list-item__inner">
                        %s
                        %s
                        <span>%s</span>
                    </div>
                    <div class="list-item__items">
                        %s
                    </div>
                </div>',
                $openClass,
                $item->id,
                $arrowIcon,
                $folderIcon,
                $title,
                renderMenu($item->children, $level + 1)
            );
        } else {
            $html .= sprintf('
                <div class="list-item" data-id="%d">
                    <div class="list-item__inner">
                        <div class="list-item__arrow placeholder"></div>
                        %s
                        <span>%s</span>
                    </div>
                </div>',
                $item->id,
                $folderIcon,
                $title
            );
        }
    }
    return $html;
}

$menuHtml = renderMenu($tree);

$template = file_get_contents(__DIR__ . '/index.html');
if ($template === false) {
    die('Не удалось загрузить шаблон');
}

$output = str_replace('{{ menu }}', $menuHtml, $template);

echo $output;