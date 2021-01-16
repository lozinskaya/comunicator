<?php
session_start();
unset($_SESSION["account_id"]);
session_destroy();
$redirect_to = $_REQUEST["redirect_to"];
$url = $redirect_to == "" ? 'auth.php' : $redirect_to;
header("Location: {$url}");
?>