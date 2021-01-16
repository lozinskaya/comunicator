<?php
global $enums;
$enums = [];

$enums['gender'] = array(
    "male" => "Мужчина",
    "female" => "Женщина",
    "other" => "Не указан"
);

function get_enum($enum) {
    return $GLOBALS["enums"][$enum];
}
?>