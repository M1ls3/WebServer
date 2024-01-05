<!DOCTYPE html>
<html>
<head>
    <title>Інформація про область</title>
    <style>
        /* Стилі для таблиці */
        table {
            border-collapse: collapse;
            width: 60%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php
$selected_region = $_POST['region'] ?? ''; // Отримання вибраної області
$file = fopen("oblinfo.txt", "r");

if ($file) {
    for ($i = 0; !feof($file); $i++) { // Якщо не кінець файлу
            $region_name = trim(fgets($file));
            $population = intval(fgets($file));
            $universities = intval(fgets($file));
        // Перевірка, чи поточна ітерація збігається з обраною областю
        if ($i == $selected_region/3) {
            echo "<h3>Інформація про область: $region_name</h3>";

            // Виведення інформації в табличному вигляді
            echo "<table>";
            echo "<tr><th>№</th><th>Область</th><th>Населення тис.</th><th>Число ВНЗ</th><th>Число ВНЗ на 100 тис. населення</th></tr>";
            echo "<tr>";
            echo "<td>$i</td>";
            echo "<td>$region_name</td>";
            echo "<td>$population</td>";
            echo "<td>$universities</td>";

            $universities_per_population = 0;
            if ($population > 0) {
                $universities_per_population = round(($universities / $population) * 100, 2);
            }
            echo "<td>$universities_per_population</td>"; // Число ВНЗ на 100 тис. населення
            echo "</tr>";
            echo "</table>";
            break;
        }
    }

    fclose($file);
}
echo "<b><a href='menu.php'>Повернутись до меню областей</a></b>";
?>

</body>
</html>
