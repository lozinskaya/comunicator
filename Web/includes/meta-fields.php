<?php
function unescape_meta_field($val) {
    $val = str_replace("\\array", "array", $val);
    return $val;
}

function escape_meta_field($val) {
    $val = str_replace("array", "\\array", $val);
    return $val;
}

function get_meta_field($table, $key, $obj_id)
{
    global $wpdb;
    $val = stripslashes($wpdb->get_var("SELECT `meta_value` FROM `{$table}` WHERE `meta_key` = '{$key}' AND `obj_id` = '{$obj_id}'"));
    $val = replace_quote($val);
    if ($val == "array") {
        $count = get_meta_field($table, "{$key}_count", $obj_id);
        $keys = get_meta_field($table, "{$key}_keys", $obj_id);

        $keys = json_decode(html_entity_decode($keys));
        $val = [];
        for ($i = 0; $i < $count; $i++) {
            $i_key = isset($keys[$i]) ? $keys[$i] : $i;
            $val[$i_key] = get_meta_field($table, "{$key}_{$i_key}", $obj_id);
        }
    } else {
        $val = unescape_meta_field($val);
    }

    return $val;
}

function update_meta_field($table, $key, $val, $obj_id, $ignore_words = false)
{
    global $wpdb;
    $updating = true;
    if (is_array($val)) {
        $wpdb->query("DELETE FROM `{$table}` WHERE `obj_id` = '{$obj_id}' AND `meta_key` LIKE `{$key}%`");
        $i = 0;
        $keys = array_keys($val);
        while ($i < count($val) && $updating) {
            $i_key = $keys[$i];

            $updating = update_meta_field($table, "{$key}_{$i_key}", $val[$i_key], $obj_id);
            $i++;
        }
        update_meta_field($table, "{$key}_count", count($val), $obj_id, true);
        update_meta_field($table, $key, "array", $obj_id, true);
        update_meta_field($table, "{$key}_keys", json_encode($keys), $obj_id, true);
    } else {
        if(!$ignore_words)
            $val = escape_meta_field($val);
        $updating = $wpdb->replace($table, array("meta_key" => $key, "obj_id" => $obj_id, "meta_value" => $val));
    }
    return $updating;
}

function get_meta_data($table, $obj_id) {
    global $wpdb;
    if(!$obj_id)
        return false;

    $rows = db_where($table, array("obj_id" => $obj_id), "*", true);
    
	$data = new stdClass;
	foreach($rows as $row) {
        if ($row->meta_value == "array") {
            $data->{$row->meta_key} = get_meta_field($table, $row->meta_key, $obj_id);
        } else {
            $data->{$row->meta_key} = unescape_meta_field($row->meta_value);
        }
    }
    
    $data = stripslashes_deep($data);
	return $data;
}

function update_meta_data($table, $data, $obj_id) {
    $updating = true;
    foreach($data as $key => $val)
    {
        $updating = update_meta_field($table, $key, $val, $obj_id);
        if(!$updating) {
            break;
        }
    }
    return $updating;
}


require_once 'DB_Meta_Object.php';
?>