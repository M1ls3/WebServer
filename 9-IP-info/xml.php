<?php
    if (isset($_GET['ip'])) {
        $ip = $_GET['ip'];
        
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        $url = "https://ipapi.co/{$ip}/xml";
        $xml = simplexml_load_file($url);

        header('Content-Type: application/xml');
        echo $xml->asXML();
    }
?>
