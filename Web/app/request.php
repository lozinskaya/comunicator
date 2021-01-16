<?php
$action = trim($_REQUEST["action"]);

switch($action) {
    case "login":
        require_once 'auth.php';
        echo json_result(auth($_REQUEST["email"], $_REQUEST["pass"]));
    break;
    case "reg":
        require_once 'reg.php';
        echo json_result(reg($_REQUEST));
    break;
    case "qrcode":
        require_once 'qrcode.php';
        echo qrcode($_REQUEST["code"]);
    break;
    case "session":
        require_once 'session.php';
        echo json_result(session_info($_REQUEST["sessionkey"]));
    break;
    case "confirm_reg":
        require_once 'confirm_reg.php';
        echo json_result(confirm_reg($_REQUEST["sessionkey"], $_REQUEST["code"]));
    break;
    case "events":
        require_once 'events.php';
        echo json_encode(events($_REQUEST["sessionkey"]));
    break;
    case "event_reg":
        require_once 'event_reg.php';
        echo json_result(event_reg($_REQUEST["sessionkey"], $_REQUEST["event_id"], $_REQUEST["count"], $_REQUEST["abort"] == 1));
    break;
}
?>