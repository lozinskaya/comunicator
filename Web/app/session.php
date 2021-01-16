<?php
require_once 'init.php';

function session_info($session_key) {
    if(!$session_key)
        return false;
    $user = get_user(array("session_key" => $session_key));
    if(!$user)
        return false;

    global $mdb;
    
    $session_info = array();
    $session_info["balance"] = (string) $user->balance;

    $active_session = get_session(array("user_id" => $user->id, "exit_time" => NULL));
    if($active_session) {
        $now = time();
        $tariff = get_tariff($active_session->tariff_id);

        $minutes = ceil(($now - $active_session->enter_time) / 60);
        $tariff_sum = $tariff->price * $active_session->count;
        $session_sum = $tariff_sum * $minutes;
        $active_session->sum = (string)$session_sum;
        $active_session->tariff_sum = (string)$tariff_sum;
        $active_session->duration_min = (string)$minutes;
        $active_session->exit_time = "0";
        $session_info["balance"] =(string) ($session_info["balance"] - $session_sum);
        $session_info["active"] = $active_session;
    } else {
        $last_session = $mdb->get_row("SELECT * FROM `{$mdb->sessions}` WHERE `user_id` = '{$user->id}' ORDER BY `exit_time` DESC LIMIT 1");
        if($last_session) {
            $minutes = ceil(($last_session->exit_time - $last_session->enter_time) / 60);
            $last_tariff = get_tariff($last_session->tariff_id);

            $tariff_sum = $last_tariff->price * $last_session->count;
            $session_sum = $tariff_sum * $minutes;
            $last_session->sum = (string)$session_sum;
            $last_session->duration = (string)$minutes;

            $session_info["last"] = $last_session;
        }
    }

    $session_info["is_active"] = $active_session ? 1 : 0;
    $session_info["result"] = SUCCESS;

    global $mdb, $config;
    $session_info["people_count"] = (int) $mdb->get_var("SELECT COUNT(*) FROM `{$mdb->sessions}` WHERE `exit_time` IS NULL");
    $session_info["max_people"] = $config["app"]["max_people"];
    return $session_info;
}
?>