<!DOCTYPE html>
<html>
<head>
    <title>Список напрямків навчання</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Список напрямків навчання</h2>

    <form action="data.php" method="post">
        <?php
        $lines = file('napr.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Отримання рядків з файлу
        $lines = array_map('trim', $lines); // Видалення зайвих символів пробілів, табуляції, переносу рядка, array_map - використовуєть для всіх елементів масиву
        sort($lines); // Сортування напрямків за алфавітом

        // Виведення відсортованого списку напрямків у вигляді радіокнопок
        foreach ($lines as $line) {
            echo '<input type="radio" name="selected_direction" value="'. htmlspecialchars($line) . '"> ' . htmlspecialchars($line) . '<br>'; // Перетворює звичайні символи на символи html
        }
        ?>
        <br>
        <input type="submit" value="Відправити запит">
        <?php
        echo "<footer><b><a href='http://server/index.html'>Повернутися на головну сторінку</a></footer>";
        ?>
    </form>
</body>
</html>
