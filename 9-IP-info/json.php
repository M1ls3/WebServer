<?php
    if (isset($_POST['ip'])) {
        $ip = $_POST['ip'];
        $url = "https://ipapi.co/{$ip}/json";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);

        if ($json !== false) { echo $json; } 
        else { echo json_encode(['error' => 'Failed to fetch IP information.']); }

        curl_close($ch);
    } else { echo json_encode(['error' => 'IP address not provided.']); }
?>
