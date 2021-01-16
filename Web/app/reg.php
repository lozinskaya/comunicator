<?php
require_once 'init.php';

function reg($data) {
    global $mdb;

    $fields = array(
        "surname" => [
            "required" => true,
            "filter" => 'pil_string',
            "maxlen" => 50,
        ],
        "firstname" => [
            "required" => true,
            "filter" => 'pil_string',
            "maxlen" => 50,
        ],
        "birthday" => [
            "required" => true,
            "filter" => 'strtotime'
        ],
        "gender" => [
            "required" => true,
            "filter" => 'pil_string'
        ],
        "email" => [
            "required" => true,
            "filter" => 'pil_string',
            "maxlen" => 50
        ],
        "pass" => [
            "required" => true,
            "filter" => 'soltstring',
            "compare_with" => "r_pass"
        ],
        "r_pass" => [
            "required" => true,
            "filter" => 'soltstring',
            "no_insert" => true,
        ],
    );

    $fields = set_fields_vals($fields, $data);

    $fields_vals = [];

    foreach ($fields as $key => $field) {
        if ($field["required"] && !$field["value"])
            return ["result" => EMPTY_FIELDS, "comment" => $key];
        if(isset($field["compare_with"]) && $fields[$field["compare_with"]]["value"] != $field["value"]) {
            return NO_REPEAT_PASS;
        }
        if(!$field["value"])
            continue;
        if (isset($field["maxlen"]) && strlen($field["value"]) > $field["maxlen"])
            return ["result" => UNKNOWN_ERROR];
        if(!isset($field["no_insert"]) || !$field["no_insert"])
            $fields_vals[$key] = $field["value"];
    }


    $email = $fields_vals["email"];

    $reg_date = time();
    $reg_ip = $_SERVER["REMOTE_ADDR"];

    $account_id = db_where($mdb->users, array("email" => $email), ["id"]);
    if ($account_id)
        return ["result" => REPEAT_STRING];

    // Gen unique code
    $code = gen_unique_field("code", function ($i) use ($reg_date, $email) {
        return substr(soltstring($reg_date . $email . $i), 2, 10);
    }, $mdb->users);

    // Gen confirm code
    $confirm_code = "";
    for($j = 0; $j < 4; $j++)
        $confirm_code .= rand(0, 9);


    $service_fields = array(
        "reg_date" => $reg_date,
        "reg_ip" => $reg_ip,
        "code" => $code,
        "confirm_code" => $confirm_code
    );

    $all_fields = array_merge($fields_vals, $service_fields);
    // Insert reg
    $insert = $mdb->insert($mdb->users, $all_fields);

    if (!$insert) {
        return ["result" => UNKNOWN_ERROR];
    }

    $account_id = (string) $mdb->insert_id;

    global $config;

    $notify = new PIL_Mail($email, $config["mailing"]["support"]);
    $notify->subject = "Подтверждение регистрации";
    $notify->fill_template('confirm_reg', array("code" => $confirm_code));
    
    $send = $notify->send();

    if(!$send) {
        $mdb->delete($mdb->users, array("id" => $account_id));
        return ["result" => SEND_ERROR];
    }

    return ["result" => SUCCESS, "account_id" => $account_id];
}
?>