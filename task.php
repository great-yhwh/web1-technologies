<?php
// задание 1 анализ знаков
function task1($a, $b) {
    if ($a >= 0 && $b >= 0) {
        $result = $a - $b;
        $msg = "Оба положительные; разность: $a - $b = $result";
    } elseif ($a < 0 && $b < 0) {
        $result = $a * $b;
        $msg = "Оба отрицательные; произведение: $a × $b = $result";
    } else {
        $result = $a + $b;
        $msg = "Разные знаки; сумма: $a + $b = $result";
    }
    return $msg;
}

// задание 2 вывод чисел от a до 15
function task2($a) {
    if ($a < 0 || $a > 15) return "Значение должно быть в диапазоне [0,15]";
    $output = "Числа от $a до 15: ";
    switch ($a) {
        case 0: $output .= "0 ";
        case 1: $output .= "1 ";
        case 2: $output .= "2 ";
        case 3: $output .= "3 ";
        case 4: $output .= "4 ";
        case 5: $output .= "5 ";
        case 6: $output .= "6 ";
        case 7: $output .= "7 ";
        case 8: $output .= "8 ";
        case 9: $output .= "9 ";
        case 10: $output .= "10 ";
        case 11: $output .= "11 ";
        case 12: $output .= "12 ";
        case 13: $output .= "13 ";
        case 14: $output .= "14 ";
        case 15: $output .= "15 ";
            break;
    }
    return $output;
}

// задание 3 арифметические функции
function add($x, $y) { return $x + $y; }
function subtract($x, $y) { return $x - $y; }
function multiply($x, $y) { return $x * $y; }
function divide($x, $y) {
    return $y == 0 ? "Ошибка: деление на ноль" : $x / $y;
}

function mathOperation($arg1, $arg2, $operation) {
    switch ($operation) {
        case '+': return add($arg1, $arg2);
        case '-': return subtract($arg1, $arg2);
        case '*': return multiply($arg1, $arg2);
        case '/': return divide($arg1, $arg2);
        default: return "Неизвестная операция";
    }
}

// задание 3 демонстрация всех операций
function task3() {
    $a = 10; $b = 5;
    return "
        <p>10 + 5 = " . mathOperation(10, 5, '+') . "</p>
        <p>10 - 5 = " . mathOperation(10, 5, '-') . "</p>
        <p>10 × 5 = " . mathOperation(10, 5, '*') . "</p>
        <p>10 ÷ 5 = " . mathOperation(10, 5, '/') . "</p>
    ";
}

// задание 4 вывод года тремя способами
function task4() {
    $way1 = date('Y');
    $way2 = date('o');
    $way3 = (new DateTime())->format('Y');
    return "
        <p>Способ 1 (date('Y')): $way1</p>
        <p>Способ 2 (date('o')): $way2</p>
        <p>Способ 3 (DateTime::format): $way3</p>
    ";
}

// задание 5 рекурсивная степень
function power($val, $pow) {
    if ($pow == 0) return 1;
    if ($pow < 0) return 1 / power($val, -$pow);
    return $val * power($val, $pow - 1);
}

function task5() {
    return "
        <p>2³ = " . power(2, 3) . "</p>
        <p>5⁰ = " . power(5, 0) . "</p>
        <p>3⁻² = " . power(3, -2) . "</p>
    ";
}

// обработка выбранного задания
$result = "";
$activeTask = null;
if (isset($_GET['task'])) {
    $activeTask = (int)$_GET['task'];
    switch ($activeTask) {
        case 1:
            $a = rand(-10, 10);
            $b = rand(-10, 10);
            $result = "<h3>Результат задания 1</h3>";
            $result .= "<p>Случайные значения: a = $a, b = $b</p>";
            $result .= "<p>" . task1($a, $b) . "</p>";
            break;
        case 2:
            $a = rand(0, 15);
            $result = "<h3>Результат задания 2</h3>";
            $result .= "<p>Случайное a = $a</p>";
            $result .= "<p>" . task2($a) . "</p>";
            break;
        case 3:
            $result = "<h3>Результат задания 3</h3>";
            $result .= task3();
            break;
        case 4:
            $result = "<h3>Результат задания 4</h3>";
            $result .= task4();
            break;
        case 5:
            $result = "<h3>Результат задания 5</h3>";
            $result .= task5();
            break;
        default:
            $result = "<p>Выберите задание из списка</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP lesson17</title>
    <link rel="stylesheet" href="src/assets/styles/style.css">
</head>
<body>
<div class="container">
    <h1>Функции. Ветвление</h1>
    <div class="sub">Выберите задание — результат появится ниже</div>

    <div class="button-panel">
        <form method="GET" style="margin:0">
            <button type="submit" name="task" value="1" class="task-btn">Задание 1</button>
            <button type="submit" name="task" value="2" class="task-btn">Задание 2</button>
            <button type="submit" name="task" value="3" class="task-btn">Задание 3</button>
            <button type="submit" name="task" value="4" class="task-btn">Задание 4</button>
            <button type="submit" name="task" value="5" class="task-btn">Задание 5</button>
        </form>
    </div>

    <div class="result-card">
        <?php
        if ($result) {
            echo $result;
        } else {
            echo "<p style='text-align:center; color:#64748b;'>✨ Нажмите на любую кнопку, чтобы увидеть результат выполнения задания ✨</p>";
        }
        ?>
    </div>
    <footer>
        Текущий год: <?= date('Y') ?>
    </footer>
</div>
</body>
</html>