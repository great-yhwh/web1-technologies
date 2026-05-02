<?php
$simpleMenu = ['Главная', 'О нас', 'Услуги', 'Контакты'];
?>
<div class="result-card">
    <h3>Простое меню (цикл по массиву)</h3>
    <ul class="simple-menu">
        <?php foreach ($simpleMenu as $item): ?>
            <li><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
    <p><em>Примечание: это плоский список без вложенности.</em></p>
</div>