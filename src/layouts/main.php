<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="src/assets/styles/style.css">
</head>
<body>
<div class="container">
    <?= $menu ?>
    <h1><?= htmlspecialchars($title) ?></h1>
    <?= $content ?>
    <footer style="margin-top: 40px; text-align: center; color: #6c757d;">
        Текущий год: <?= date('Y') ?>
    </footer>
</div>
</body>
</html>