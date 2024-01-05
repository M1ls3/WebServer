<!DOCTYPE html>
<html>
<head>
    <title>Таблиця за обраним напрямком навчання</title>
    <style>
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
$selected_direction = $_POST['selected_direction'] ?? ''; //  Присвоєння selected_direction оператором злиття
if (!empty($selected_direction))
    {
        $data = file('data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        echo "$selected_direction <a href='napr.php'>Повернутися до списку</a>";
        echo "<table>";
        echo "<tr><th>№</th><th>Середня сума балів</th><th>Число бюджетників</th><th>Недобір</th><th>Число контрактників</th><th>ВНЗ</th></tr>";

        $specialty = '';
        $num_of_universities = (int)($data[1]);
        for ($i = 0; $i < count($data); $i += ($num_of_universities * 4)+1) {
            $num_of_universities = (int)($data[$i+1]);
            if ($data[$i] == $selected_direction) // Перевірка 
            {
                $specialty = trim($data[$i]);
                $start_index = $i + 2;

                for ($j = 0; $j < $num_of_universities; $j++) {
                    $index = $start_index + ($j * 4);
                    $average_grade = trim($data[$index]);
                    $budget_amount = trim($data[$index + 1]);
                    $contractors_amount = trim($data[$index + 2]);
                    $universitie_name = trim($data[$index + 3]);
                    echo "<tr>";
                    echo "<td>" . ($j + 1) . "</td>";
                    echo "<td>" . ($average_grade) . "</td>";
                    echo "<td>" . ($budget_amount) . "</td>";
                    if ($contractors_amount > 0)
                    {
                        echo "<td>" . ('-') . "</td>";
                        echo "<td>" . ($contractors_amount) . "</td>";
                    }
                    elseif ($contractors_amount == 0)
                    {
                        echo "<td>" . ('-') . "</td>";
                        echo "<td>" . ('-') . "</td>";
                    }
                    else 
                    {
                        echo "<td>" . ($contractors_amount * -1) . "</td>";
                        echo "<td>" . ('-') . "</td>";
                    }
                    echo "<td>" . ($universitie_name) . "</td>";
                    echo "</tr>";
                }
                return;
            }
        }
        echo "</table>";
    }
else 
{
    echo "<a href='napr.php'>Обери спеціальність</a>";
}
?>
</body>
</html>
