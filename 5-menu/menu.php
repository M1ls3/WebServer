<!DOCTYPE html>
<html>
<head>
    <title>Вибір області</title>
</head>
<body>

<form action="table.php" method="post">
    <label for="region">Оберіть область:</label>
    <select name="region" id="region">
        <?php
        $file = fopen("oblinfo.txt", "r");
        if ($file) {
            $i = 0;
            while (!feof($file)) {
                $region_name = trim(fgets($file));
                // Виводимо тільки кожне 3 значення (назву області)
                if ($i % 3 === 0) {
                    echo "<option value='$i'>$region_name</option>";
                }
                $i++;
            }
            fclose($file);
            }
        ?>
    </select>
    <br>
    <input type="submit" value="Відправити запит">
    <?php
        echo "<footer><b><a href='http://server/index.html'>Повернутися на головну сторінку</a></footer>";
    ?>
</form>

</body>
</html>
