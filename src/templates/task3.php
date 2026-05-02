<?php
function transliterate($string) {
    $rus = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z',
        'и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r',
        'с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'kh','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'shch',
        'ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya'
    ];
    $result = '';
    $len = mb_strlen($string, 'UTF-8');
    for ($i = 0; $i < $len; $i++) {
        $char = mb_substr($string, $i, 1, 'UTF-8');
        $lower = mb_strtolower($char, 'UTF-8');
        if (isset($rus[$lower])) {
            $trans = $rus[$lower];
            if ($char !== $lower) $trans = mb_convert_case($trans, MB_CASE_TITLE, 'UTF-8');
            $result .= $trans;
        } else {
            $result .= $char;
        }
    }
    return $result;
}
$test = "Привет, мир! Это задание №3.";
?>
<div class="result-card">
    <h3>Транслитерация строки</h3>
    <p><strong>Исходная строка:</strong> <?= htmlspecialchars($test) ?></p>
    <p><strong>Транслитерация:</strong> <?= transliterate($test) ?></p>
</div>