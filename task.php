<?php
$pageTitle = "Динамическая PHP страница";
$heading = "Добро пожаловать на мою страницу!";
$currentYear = date("Y");

// Функция для вывода времени + склонения
function getTime() {
    $hours = date("G");
    $minutes = date("i");

    // Склонение для часов
    $hoursStr = $hours . " ";
    $hoursMod = $hours % 10;
    $hoursMod100 = $hours % 100;
    if ($hoursMod == 1 && $hoursMod100 != 11) {
        $hoursStr .= "час";
    } elseif ($hoursMod >= 2 && $hoursMod <= 4 && ($hoursMod100 < 10 || $hoursMod100 >= 20)) {
        $hoursStr .= "часа";
    } else {
        $hoursStr .= "часов";
    }

    // Склонение для минут
    $minutesStr = $minutes . " ";
    $minutesMod = $minutes % 10;
    $minutesMod100 = $minutes % 100;
    if ($minutesMod == 1 && $minutesMod100 != 11) {
        $minutesStr .= "минута";
    } elseif ($minutesMod >= 2 && $minutesMod <= 4 && ($minutesMod100 < 10 || $minutesMod100 >= 20)) {
        $minutesStr .= "минуты";
    } else {
        $minutesStr .= "минут";
    }

    return $hoursStr . " " . $minutesStr;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="src/assets/styles/style.css">
</head>
<body>
<div class="card">
    <h1><?php echo $heading; ?></h1>
    <div class="year-info">
        <strong>Текущий год:</strong> <?php echo $currentYear; ?>
    </div>
    <div class="time">
        <strong>Текущее время</strong>
        <span><?php echo getTime(); ?></span>
    </div>
</div>
</body>
</html>