<?php
require_once 'init.php';
function events($session_key, $limit = 0, $offset = 0) {
    global $mdb;

    if(!$session_key)
        return false;

    $user = get_user(array("session_key" => $session_key));
    if(!$user)
        return false;

    $query = "SELECT * FROM `{$mdb->events}`";
    $events =  $mdb->get_results($query);

    $user_regs = $mdb->get_col("SELECT `event_id` FROM `{$mdb->events_regs}` WHERE `user_id` = '{$user->id}'");

    $past = [];
    $future = [];
    $now = time();
    foreach($events as $event) {
        if($event->date_time >= $now)
            $future[] = $event;
        else
            $past[] = $event;
        $event->is_member = in_array($event->id, $user_regs) ? "1" : "0";
        if($event->is_member == "1")
            $event->extra_count = (string)( $mdb->get_var("SELECT `count` FROM `{$mdb->events_regs}` WHERE `user_id` = '{$user->id}' AND `event_id` = '{$event->id}'") - 1);
        else
            $event->extra_count= "0";
        if($event->image_id)
            $event->image_url = Attachment::get($event->image_id)->uri;
    
    }

    usort($future, function($a, $b) {
        if ($a->date_time == $b->date_time) {
            return 0;
        }
        return ($a->date_time < $b->date_time) ? -1 : 1;
    });

    usort($past, function($a, $b) {
        if ($a->date_time == $b->date_time) {
            return 0;
        }
        return ($a->date_time > $b->date_time) ? -1 : 1;
    });

    $result = new stdClass;
    $result->past = $past;
    $result->future = $future;
    $result->count_regs = (string) count($user_regs);

    return $result;
}
?>