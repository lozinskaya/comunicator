<?php
global $config;

define("DEBUG", isset($_GET["debug"]) && $_GET["debug"] == 1);
if(DEBUG) {
    ini_set("display_errors", 1);
    error_reporting(E_ALL);
}
// DEFs
define("HOME_URI", "https://cafe.paulislava.space");
define("OFFICE_URI", HOME_URI . "/admin/");
define("RESTORE_URI", HOME_URI . "/admin/createpass.php?token=%s");

define("ROOT", dirname(__FILE__) . '/');
define("INCLUDE_PATH", ROOT . "includes/");
define("SITE_INCLUDE_PATH", ROOT . "site_includes/");
define("TEMPLATE_PATH", ROOT . "templates/");
define("SITE_TEMPLATE_PATH", TEMPLATE_PATH . "site/");
define("MAILING_TEMPLATE_PATH", TEMPLATE_PATH . "mail/");
define("SALT", "pil_cafe_20");

define("OFFICE_STYLE_VERS", "1.0");
define("SITE_STYLE_VERS", "1.0");
define("RESET_STYLE_VERS", "1.0");
define("ANIM_STYLE_VERS", "1.0");
define("BOOTSTRAP_STYLE_VERS", "1.0");

define("JQUERY_SCRIPT_VERS", "1.0");
define("BOOTSTRAP_SCRIPT_VERS", "1.0");
define("SITE_SCRIPT_VERS", "1.0");

// Answers
define("EMPTY_FIELDS", '0');
define("UNKNOWN_ERROR", '1');
define("SUCCESS", '2');
define("REPEAT_STRING", '3');
define("WRONG_USER", '4');
define("NO_REPEAT_PASS", '5');
define("SEND_ERROR", '6');
define("NO_CONFIRMED", '7');

define("TIMEZONE", 'Asia/Yekaterinburg');
define("DATE_FORMAT", "d-m-Y");

// Sizes
define("MB", 1024 * 1024);

// Attachments

define("ATTACHMENT_PATH", ROOT . "attachments");
define("ATTACHMENT_MAX_SIZE", 14 * MB); 
define("ATTACHMENT_URI_FORMAT", HOME_URI . "/attachments/%d.%s");


// Global
$config["qr_link"] = OFFICE_URI . "scan.php?code=%s";

// MySQL data
$config["mysql"]["host"] = 'localhost';
$config["mysql"]["db"] = 'admin_cafe';
$config["mysql"]["login"] = 'admin_cafe';
$config["mysql"]["pass"] = 'yBonaBQQGu';
$config["mysql"]["charset"] = 'utf8';
$config["mysql"]["prefix"] = "";


// Site info
$config["site"]["title"] = "Коммуникатор";
$config["site"]["url"] = "https://cafe.paulislava.space/";



// Notify system
$config["notify"]["from"] = "Коммуникатор <notify@cafe.paulislava.space>";
$config["notify"]["mails"] = ["p-kondratov@mail.ru"];
$config["notify"]["entity_orders"] = true;

$config["mailing"]["support"] = "Поддержка коммуникатора <support@cafe.paulislava.space>";


// APP
$config["app"]["max_people"] = 100;
?>