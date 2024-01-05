<?php
$curl = curl_init("http://www.gismeteo.ua/city/hourly/4956/");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Параметр для повернення результату запиту у вигляді рядка
curl_setopt($curl, CURLOPT_HEADER, false); // Виведення HTTP-заголовків у відповіді сервера
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Переадресація
$html = curl_exec($curl); // Виконання запиту і збереження
curl_close($curl);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
.temp{
    margin-left: 15px;
    padding: 5px 10px;
    margin-right: 10px;
    background-color: #e0e0e0;
    border-radius: 5px;
}
.container {
    width: 80%;
    margin: auto;
    padding-top: 20px;
    text-align: left;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

</style>
<body>
    <div class="container">
        <?php 
            displayAllInfo();
        ?>
    </div>
</body>
</html>

<?php
$temperature = array();
$sunInfo = array();
$city;
    function getSunCharachteristics() {
        global $html, $sunInfo;
        $duringPat = '/\sДолгота дня:\s(\d{1,2}\sч\s\d{1,2}\sмин\s)\s/'; // Регулярні вирази
        $eastPat = '/Восход\s—\s(\d{1,2}:\d{2})/';
        $westPat = '/Заход\s—\s(\d{1,2}:\d{2})/';
        $pat = '/\w{1,2}:\w{1,2}/';

        if (preg_match_all($eastPat, $html, $result)) {
            $sunInfo[] = ($result[0])[0];
        }

        if (preg_match_all($westPat, $html, $result)) {
            $sunInfo[] = ($result[0])[0];
        }

        if (preg_match_all($duringPat, $html, $result)) {
            $sunInfo[] = ($result[0])[0];
        }
    }
    function getTemperature() {
        global $html, $temperature;
        $hour = 2;
        $pattern = '/<span class="unit unit_temperature_c">[^<]*<\/span>/';
        $anotherPat = '/[−+]?[0-9]+|[-+]?[0-9]+/';
        if (preg_match_all($pattern, $html, $result)) {
            for ($i = 5; $i < 13; $i++) {
                $temperature[$i - 5] = $hour . "г:" . " " . $result[0][$i] . "&deg";
                $hour += 3;
            }
        }
    }
    function displayAllInfo() {
        global $temperature, $sunInfo, $city;
        getCity();
        getSunCharachteristics();
        getTemperature();
        echo $city . "</br>";
        echo date("d.m.Y");
        echo "</br>";
        foreach($sunInfo as $item) {
            echo $item . "</br>";
        }
        echo "<div>Температура на протяжении дня</span>";
        foreach($temperature as $item) {
            echo "<span class=\"temp\">" . $item . "</span>";
        }
        echo "</br>";
    }

    function getCity() {
        global $html, $city;
        $pattern = '/<div class="page-title">\s*<h1>(.*?)<\/h1>/';
        if(preg_match_all($pattern, $html, $result)) {
           $city = $result[1][0];
        }
    }
?>