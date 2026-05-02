<?php
function taskDoWhile() {
    $i = 0;
    $output = "<ul>";
    do {
        if ($i == 0) $output .= "<li>0 – это ноль.</li>";
        elseif ($i % 2 == 0) $output .= "<li>$i – чётное число.</li>";
        else $output .= "<li>$i – нечётное число.</li>";
        $i++;
    } while ($i <= 10);
    $output .= "</ul>";
    return $output;
}
?>
<div class="result-card">
    <h3>do…while (0…10)</h3>
    <?= taskDoWhile() ?>
</div>