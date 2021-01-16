<?php
global $page_title, $lk_page_id, $body_classes;

$body_classes[] = "lk-" . $lk_page_id;
$body_classes_string = implode(" ", $body_classes);
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/auth.css?v=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time()?>">
    <script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
    <title><?=$page_title?></title>
</head>
<body id="site" class="<?=$body_classes_string?>">