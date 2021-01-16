<?php
require_once 'init.php';

function confirm_reg($session_key, $code) {
    global $mdb;
    $code = intval($code);
    if(!$session_key || !$code)
        return false;
    $user = get_user(array("session_key" => $session_key));
    if(!$user)
        return false;
    
    if($user->confirm_code != $code)
        return false;
    
    $update = $mdb->update($mdb->users, array("confirmed" => true), array("id" => $user->id));
    return SUCCESS;
}
?>