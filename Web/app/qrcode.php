<?php
require_once "init.php";
function qrcode($code) {
    global $config;
    if(!$code)
        die(EMPTY_FIELDS);
    $reflink = urlencode(sprintf($config["qr_link"],$code)); 
    $url = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=512x512&chl=$reflink";
    $content = file_get_contents($url); // получает qr код со страницы $url
    $size = sizeof($content);
    header('Content-Type: image/png');
    header("Content-length: $size");
    echo $content;
}
?>