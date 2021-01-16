<?php
define("IN_LK", true);

function redirect_auth() {
    header("Location: " . OFFICE_URI ."auth.php?r_to=" . urlencode($_SERVER["REQUEST_URI"]));
    exit;
}

require_once dirname(dirname(__FILE__)) . '/init.php';

if(!isset($_SESSION["account_id"]) || $_SESSION["account_id"] == 0) 
    redirect_auth();

global $account_id, $account, $account_data, $mdb, $team, $org, $errorText, $body_classes;
$account_id = $_SESSION["account_id"];
$account = get_admin(array("id" => $account_id));
$account_data = $account->data;
if(!$account)
    redirect_auth();
$errorText = '';


$body_classes = [];

global $main_menu;
$main_menu = [];
$main_menu[] = ['title' => 'Главная', 'icon' => ['svg' => 'lk/profile'], 'href' => LK_URI, 'id' => 'home'];
// $main_menu[] = ['title' => '<span class="mobile-hide">Выбор направления и кейса</span><span class="pc-hide">Направления</span>', 'icon' => ['svg' => 'lk/rating'], 'href' => LK_URI . 'subjects/', 'id' => 'subjects'];
            

function get_lk_header() {
    get_template_part('office-header');
}

function get_lk_footer() {
    get_template_part('office-footer');
}
?>