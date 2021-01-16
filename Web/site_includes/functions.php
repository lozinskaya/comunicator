<?php
require_once 'enums.php';

function get_user($data) {
    global $mdb;
    $user = db_where($mdb->users, $data);
    return $user;
}

function get_session($data) {
    global $mdb;
    $user = db_where($mdb->sessions, $data);
    return $user;
}

function get_userinfo($user) {
    unset($user->pass);
    unset($user->session_key);
    return $user;
}

function get_tariff($tariff_id) {
    global $mdb;
    $tariff = db_where($mdb->tariffs, array("id" => $tariff_id));
    return $tariff;
}

function get_standart_tariff() {
    global $mdb;
    $tariff = db_where($mdb->tariffs, array("standart" => 1));
    return $tariff;
}

function get_admin($data) {
    if(!is_array($data) || count($data) == 0)
        return false;
    
    global $mdb;
    $account = db_where($mdb->admins, $data);
    if(!$account)
        return false;
        
    return $account;
}

function auth_admin($account) {
    $_SESSION["account_id"] = $account->id;
    global $mdb;
    $mdb->update($mdb->admins, array("auth_date" => time()), array("id" => $account->id));
}
?>