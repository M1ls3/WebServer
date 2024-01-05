<!DOCTYPE html>
<html>
<head>
    <title>Таблиця областей України</title>
    
    <style>
    /* Стилі для таблиці */
    table {
        border-collapse: collapse; /* Злиття границь комірок таблиці */
        width: 100%; /* Ширина таблиці */
        margin: 20px auto; /* Відступи зверху та знизу, авто-вирівнювання по центру */
    }

    th, td {
        border: 1px solid #000; /* Границі комірок */
        padding: 8px; /* Відступи всередині комірок */
        text-align: center; /* Вирівнювання тексту в комірках по лівому краю */
    }

    th {
        background-color: #f2f2f2; /* Фон заголовків стовпців */
    }
</style>
</head>
<body>

<?php
$file = fopen("oblinfo.txt", "r"); // Відкриття файлу для читання

if ($file) {
    echo "<table>"; // Відкриття таблиці
    echo "<tr><th>№</th><th>Область</th><th>Населення тис.</th><th>Число ВНЗ</th><th>Число ВНЗ на 100 тис. населення</th></tr>"; // Рядок заголовків стовпців таблиці

    $i = 1; // Лічильник областей
    while (!feof($file)) { // Поки не досягнуто кінця файлу
        $region_name = trim(fgets($file)); // Отримання назви області
        if (empty($region_name)) {
            break; // Вихід з циклу, якщо досягнуто кінця файлу
        }

        $population = (int)(fgets($file)); // Отримання кількості населення області
        $universities = intval(fgets($file)); // Отримання кількості ВНЗ в області

        echo "<tr>"; // Відкриття рядка таблиці
        echo "<td>" . $i . "</td>"; // Номер області
        echo "<td>$region_name</td>"; // Назва області
        echo "<td>$population</td>"; // Кількість населення
        echo "<td>$universities</td>"; // Кількість ВНЗ

        // Розрахунок числа ВНЗ на 100 тис. населення
        $universities_per_population = 0;
        if ($population > 0) {
            $universities_per_population = round(($universities / $population) * 100, 2);
        }
        echo "<td>$universities_per_population</td>"; // Число ВНЗ на 100 тис. населення

        echo "</tr>";
        $i++;
    }

    echo "</table>";
    fclose($file); // Закриття файлу
}
echo "<b><a href='http://server/index.html'>Повернутися на головну сторінку</a>";
?>

</body>
</html>
