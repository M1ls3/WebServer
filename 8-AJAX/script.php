<?php
if (isset($_GET['query'])) {
    // Отримання значення параметра 'query'
    $searchTerm = $_GET['query'];
    $url = 'https://www.e-tool.com.ua/search/result?query=' . urlencode($searchTerm);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $exec = curl_exec($curl);
    curl_close($curl);

    // Регулярний вираз для пошуку патерну відповідей у відповіді від сервера
    $itemPattern = '/<div class="cell title">\s*<a href="([^"]+)">\s*<span class="image">\s*<img class="float-center" src="([^"]+)"[^>]*>\s*<\/span>\s*([^<]+)<\/a>\s*<\/div>\s*<div class="cell">\s*<div class="price grid-x grid-padding-x">\s*<div class="cell shrink">\s*(\d+(?:\.\d+)?)&nbsp;<span>([^<]+)<\/span>\s*<\/div>/';
    
    // Пошук у тексті за допомогою регулярного виразу і зберігання відповідних частин у масиві $matches
    preg_match_all($itemPattern, $exec, $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        echo 'Not found!';
    } else {
        echo '<div class="result-box">';
        foreach ($matches as $item) {
            // Отримання окремих елементів з кожного відповідного результату
            $itemURL = $item[1];
            $imageURL = $item[2];
            $itemName = $item[3];
            $itemPrice = $item[4];
            $currency = $item[5];

            echo '<div class="find">';
            echo '<img src="' . $imageURL . '">';
            echo '<h2>' . $itemName . '</h2>';
            echo '<p class="price"> Ціна:  ₴ ' . $itemPrice . ' ' . $currency . '</p>';
            echo '</div>';
        }
        echo '</div>';
    }
}
?>

