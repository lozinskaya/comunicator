<?php
require_once 'init.php';
function event_reg($session_key, $event_id, $count, $abort = false) {
    $event_id = intval($event_id);
    $count = abs($count);
    if(!$session_key || !$event_id)
        return false;

    $user = get_user(array("session_key" => $session_key));
    if(!$user)
        return false;

    global $mdb;

    if($abort) {
        $do = $mdb->delete($mdb->events_regs, array("event_id" => $event_id, "user_id" => $user->id));
    } else {
        $do = $mdb->replace($mdb->events_regs, array(
            "event_id" => $event_id,
            "user_id" => $user->id,
            "count" => $count,
            "create_date" => time()
        ));
    }

    if(!$do)
        return false;

    return SUCCESS;
}
?>