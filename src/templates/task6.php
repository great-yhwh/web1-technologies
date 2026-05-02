<?php
function taskCitiesWithK(): string
{
    $regions = [
        'Московская область' => ['Москва', 'Зеленоград', 'Клин', 'Химки', 'Подольск'],
        'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт', 'Гатчина'],
        'Рязанская область' => ['Рязань', 'Касимов', 'Скопин', 'Сасово', 'Ряжск'],
        'Нижегородская область' => ['Нижний Новгород', 'Арзамас', 'Дзержинск', 'Бор', 'Кстово']
    ];
    $html = '';
    foreach ($regions as $region => $cities) {
        $filtered = array_filter($cities, function ($city) {
            return mb_substr($city, 0, 1, 'UTF-8') === 'К';
        });
        if (!empty($filtered)) {
            $html .= "<p><strong>$region:</strong><br>" . implode(', ', $filtered) . ".</p>";
        }
    }
    return $html ?: '<p>Городов на букву "К" не найдено.</p>';
}
?>
<div class="result-card">
    <h3>Города на букву "К"</h3>
    <?= taskCitiesWithK() ?>
</div>