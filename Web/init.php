<?php
if (isset($_COOKIE["sess_id"]))
    session_id($_COOKIE["sess_id"]);

session_start();

setcookie("sess_id", session_id(), 1000 * 60 * 60 * 24 * 30, "/");


require_once 'config.php';

define("WP_DEBUG", DEBUG);
require_once INCLUDE_PATH . 'mdb.php';

define("DB_CHARSET", $config["mysql"]["charset"]);
$mdb = new mdb($config["mysql"]["login"], $config["mysql"]["pass"], $config["mysql"]["db"], $config["mysql"]["host"]);

$mdb->prefix = $config["mysql"]["prefix"];
$mdb->users = $mdb->prefix . "users";
$mdb->sessions = $mdb->prefix . "sessions";
$mdb->cards = $mdb->prefix . "cards";
$mdb->tariffs = $mdb->prefix . "tariffs";
$mdb->admins = $mdb->prefix . "admins";
$mdb->events = $mdb->prefix . "events";
$mdb->attachments = $mdb->prefix . "attachments";
$mdb->attachments_data = $mdb->prefix . "attachments_data";
$mdb->events_regs = $mdb->prefix . "events_regs";
$mdb->balance_refills = $mdb->prefix . "balance_refills";

require_once INCLUDE_PATH . 'functions.php';
require_once INCLUDE_PATH . 'Mailing/Mailing.php';
require_once SITE_INCLUDE_PATH . 'functions.php';

Attachment::$db_table = $mdb->attachments;
Attachment::$db_meta_table = $mdb->attachments_data;

global $config, $Page, $mdb;
$Page["title"] = $config["site"]["title"];
date_default_timezone_set(TIMEZONE);
?>