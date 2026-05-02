<?php
$menuData = [
    'Главная' => '/',
    'О нас' => '/',
    'Услуги' => [
        'Веб-разработка' => '/',
        'Дизайн' => '/',
        'SEO' => '/'
    ],
    'Контакты' => '/',
    'Блог' => [
        'Новости' => '/',
        'Статьи' => [
            'PHP' => '/',
            'JavaScript' => '/'
        ]
    ]
];

function renderNestedMenu($items, $isSub = false): string
{
    $tag = $isSub ? 'ul class="submenu"' : 'ul class="main-menu"';
    $html = "<$tag>";
    foreach ($items as $title => $link) {
        $hasChildren = is_array($link);
        $liClass = $hasChildren ? 'class="has-children"' : '';
        $html .= "<li $liClass>";
        if ($hasChildren) {
            $html .= "<span>$title</span>";
            $html .= renderNestedMenu($link, true);
        } else {
            $html .= "<a href='$link'>$title</a>";
        }
        $html .= "</li>";
    }
    $html .= "</ul>";
    return $html;
}
?>
<div class="result-card nested-menu-demo">
    <h3>Меню с вложенными подменю (рекурсия)</h3>
    <?= renderNestedMenu($menuData) ?>
</div>