<?php
require_once dirname(dirname(__FILE__)) . '/init.php';

global $redirect_to;
$redirect_to = trim($_REQUEST["r_to"]);
if (empty($redirect_to))
    $redirect_to = OFFICE_URI;
?>