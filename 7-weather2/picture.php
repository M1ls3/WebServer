<?php 
    $id = $_GET['gorod']; 
    //$link = ["http://www.gismeteo.ua/city/hourly/" . $gorodId . "/", "http://www.gismeteo.ua/city/hourly/" . $gorodId . "/", "http://www.gismeteo.ua/city/hourly/" . $gorodId . "/", "http://www.gismeteo.ua/city/hourly/" . $gorodId . "/", "http://www.gismeteo.ua/city/hourly/" . $gorodId . "/", "http://www.gismeteo.ua/city/hourly/" . $gorodId . "/", "http://www.gismeteo.ua/city/hourly/" . $gorodId . "/", "http://www.gismeteo.ua/city/hourly/" . $gorodId . "/"];
    $link = ("http://www.gismeteo.ua/city/hourly/" . $id . "/");
    $cityNames = ["Kyiv", "Kharkiv", "Donetsk","Dnipro","Madrid","Tempere","London","Barselona"];
    $cityLink;
    $cityName;
    $array = ["4944", "5053", "5080", "5077", "1950", "471", "744", "1948"];

    for($i = 0; $i < count($array); $i++) {
        if ($id == $array[$i])
        {
            $cityLink = $link;
            //$cityLink = $link[$i];
            $cityName = $cityNames[$i];
       }
    }
?>

<?php
    $curl = curl_init($cityLink);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Параметр для повернення результату запиту у вигляді рядка
    curl_setopt($curl, CURLOPT_HEADER, false); // Виведення HTTP-заголовків у відповіді сервера
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Переадресація
    $html = curl_exec($curl);
    curl_close($curl);
?>

<?php 
    $temperature = array();
    $sunInfo = array(); 

    // Функція для отримання інформації про сонце
    function getSunInfo() {
        global $html, $sunInfo; 
        $eastPat = '/Восход\s—\s(\d{1,2}:\d{2})/'; 
        $westPat = '/Заход\s—\s(\d{1,2}:\d{2})/'; 
        $pat = '/\w{1,2}:\w{1,2}/'; //  час

        // Перевірка HTML на наявність часу сходу сонця і його збереження в масив $sunInfo
        if (preg_match_all($eastPat, $html, $result)) {
            if (preg_match_all($pat, $result[0][0], $correct)) {
                $sunInfo[] = $correct[0][0];
            }
        }

        // Перевірка HTML на наявність часу заходу сонця і його збереження в масив $sunInfo
        if (preg_match_all($westPat, $html, $result)) {
            if (preg_match_all($pat, $result[0][0], $correct)) {
                $sunInfo[] = $correct[0][0];
            }
        }
    }

    // Функція для отримання температур з HTML-коду
    function getTemperature() {
        global $html, $temperature; // Використання глобальної змінної $html для доступу до HTML-коду

        $pattern = '/<span class="unit unit_temperature_c">[^<]*<\/span>/'; // температур
        $anotherPat = '/[−+]?[0-9]+|[-+]?[0-9]+/'; // чисел

        // Пошук температур у HTML і збереження їх у масиві $temperature
        if (preg_match_all($pattern, $html, $result)) {
            for ($i = 5; $i < 13; $i++) {
                if (preg_match_all($anotherPat, $result[0][$i], $matche)) {
                    $temperature[$i - 5] = $matche[0][0];
                }
            }
        }
    }

    // Функція ініціалізації для отримання даних про температуру та сонце
    function init() {
        getTemperature(); // Виклик функції для отримання температур
        getSunInfo(); // Виклик функції для отримання інформації про сонце
    }
?>

<?php 
    init(); // погодових даних

    $width = 500;
    $height = 230;

    // Створення зображення з чорним фоном
    $im = imagecreatetruecolor($width, $height);
    //$color = imagecolorallocate($im, 0, 0, 0);
    //imagefilledrectangle($im, 0, 0, $width, $height, $color);
    //imageantialias($im, true); // згладжування ліній

    // Завантаження зображень 
    $sun = imagecreatefrompng('sun_sm.png');
    $sun_x = imagesx($sun);
    $sun_y = imagesy($sun);
    
    $moon = imagecreatefrompng('moon_sm.png');
    $moon_x = imagesx($moon);
    $moon_y = imagesy($moon);
    
    $left = imagecreatefrompng('left.png');
    $left_x = imagesx($left);
    $left_y = imagesy($left);
    
    $right = imagecreatefrompng('right.png');
    $right_x = imagesx($right);
    $right_y = imagesy($right);
    
    $day = imagecreatefrompng('center.png');
    $day_x = imagesx($day);
    $day_y = imagesy($day);
    
    // Calculate the position to center $day vertically within the canvas
    $day_pos_x = ($width - $day_x) / 2 - 60;
    $day_pos_y = 0;

    // Розрахунок позицій дня
    $start = (51 / 3) * (getHour($sunInfo[0]) + getMinutes($sunInfo[0]));
    $end = (51 / 3) * (getHour($sunInfo[1]) + getMinutes($sunInfo[1])) - $start;

    // зображень дня
    imagecopyresized($im, $left, $start-27, 0, 0, 0, $width/8, $height-50, 200, $left_x);
    imagecopyresized($im, $right, $start + $end-3, 0, 0, 0, $width/8, $height-50, 200, $right_y);
    imagecopyresized($im, $day, $start, $day_pos_y, 0, 0, $end, $height - 50, $day_x, $day_y);
    
    imagecopy($im, $sun, $start + ($start + $end - 13 - $start) / 2, 0, 0, 0, $sun_x, $sun_y);
    imagecopy($im, $moon, ($width - $moon_x) / 2 - 200, 0, 0, 0, $moon_x, $moon_y);
    imagecopy($im, $moon, ($width - $moon_x) / 2 + 200, 0, 0, 0, $moon_x, $moon_y);

    $data = array();
    for($i = 0; $i < count($temperature); $i++){
        // Перевірка знаку температури та збереження у масиві даних
        $temp = strpos($temperature[$i], '+') === 0 || strpos($temperature[$i], "0") === 0 ? $temperature[$i] : '-'. $temperature[$i];
        $data[$i] = intval($temp);
    }

    // Виклик функції для побудови графіка
    Graf($data, $im, $width, $height); ///

    ob_clean(); // Очистити буфер
    header("Content-Type: image/png"); // заголовок для відображення зображення у форматі PNG
    imagepng($im); // Виведення зображення
    //imagedestroy($im); // Звільнення пам'яті
?>

<?php 
    function Graf($data, $im, $width, $height){
        global $temperature, $sunInfo, $cityName;
        $baseWidth = $width;
        $baseHeight = $height;

        // значення ширини та висоти для графіка
        $height = 100;
        $width -= 130;

        // Знаходження мінімальної та максимальної температур
        $minTemperature = min($data);
        $maxTemperature = max($data);

        // Визначення кольорів для ліній графіка та тексту
        $colorLine = imagecolorallocate($im, 255, 0, 0);
        $colorX = imagecolorallocate($im, 20, 20, 255);
        $colorText = imagecolorallocate($im, 0, 0, 0);

        // Кількість точок на графіку
        $pointCount = count($data);

        // Розрахунок ширини стовпців графіка
        $barWidth = $width / ($pointCount - 1);
        
        // Побудова ліній графіка
        for ($i = 0; $i < $pointCount - 1; $i++) {
            $x1 = $i * $barWidth + 30;
            $y1 = $height - (($height / ($maxTemperature - $minTemperature)) * ($data[$i] - $minTemperature)) + 60;
        
            $x2 = ($i + 1) * $barWidth + 30;
            $y2 = $height - (($height / ($maxTemperature - $minTemperature)) * ($data[$i + 1] - $minTemperature)) + 60;

            imageline($im, $x1, $y1, $x2, $y2, $colorLine);
        }

        // Додавання значень температур на графіку
        for ($i = 0; $i < $pointCount; $i++) {
            $x1 = $i * $barWidth + 30;
            $y1 = $height - (($height / ($maxTemperature - $minTemperature)) * ($data[$i] - $minTemperature)) + 60;
            
            // Перевірка знаку температури та її відображення
            $temp = strpos($temperature[$i], '+') === 0 || strpos($temperature[$i], "0") === 0 ? $temperature[$i] : '-'. $temperature[$i];
            imagestring($im, 4, $x1 - 5, $y1 - 20, $temp, $colorLine);
        }

        // Побудова шкали годин на графіку
        imageline($im, $barWidth - 30, $baseHeight - 50, $baseWidth - $barWidth, $baseHeight - 50, $colorX);

        for ($i = 0; $i < $pointCount; $i++) {
            $x1 = $barWidth * $i + 30;
            $y1 = $baseHeight - 55;
            
            $x2 = $barWidth * $i + 30;
            $y2 = $baseHeight - 45;
            imageline($im, $x1, $y1, $x2, $y2, $colorX);
        }

        // Додавання підписів годин на графіку
        $hour = 2;
        for ($i = 0; $i < $pointCount; $i++) {
            $x1 = $barWidth * $i + 30;
            $y1 = $baseHeight - 45;
            $str = $hour;
            imagestring($im, 4, $x1 - 5, $y1, $hour, $colorX);
            $hour += 3;
        }

        // Додавання дати та місця на графіку
        $white = imagecolorallocate($im, 255, 255, 255);
        imagestring($im, 4, $baseWidth / 6, $baseHeight - 30, date("d.m.Y"), $white);
        imagestring($im, 4, 200, $baseHeight - 30, "In city " . $cityName, $white);
    }

    // Отримання години з часу сходу/заходу сонця
    function getHour($sun) {
        list($hours, $minutes) = explode(":", $sun);
        return $hours;
    }

    // Отримання хвилини з часу сходу/заходу сонця
    function getMinutes($sun) {
        list($hours, $minutes) = explode(":", $sun);
        return 0.01 * $minutes;
    }
?>
