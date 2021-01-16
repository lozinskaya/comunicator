<?php
require_once 'init.php';

function auth($email, $pass) {
    if(!$email || !$pass)
        return EMPTY_FIELDS;
    global $mdb;

    $pass = soltstring($pass);
    $user = get_user(array("email" => $email, "pass" => $pass));
    
    if(!$user) // если пользователь не найден
        return WRONG_USER;

    $date = time();

    $session_key = gen_unique_field("session_key", function($i) use($user, $date) {
        return soltstring($i . "_" . $user->id . "_" . $date);
    }, $mdb->users);

    $update_session = $mdb->update($mdb->users, array(
        "session_key" => $session_key,
        "auth_date" => $date
    ), array("id" => $user->id));

    if(!$update_session)
        return UNKNOWN_ERROR;
    
    return array(
        "result" => SUCCESS,
        "session_key" => $session_key,
        "info" => get_userinfo($user)
    );
}
?>