<?php
function db_where($table, $where = [], $columns = [], $many = false, $where_format = null) {
    global $mdb;
	if ( ! is_array( $where ) ) {
		return false;
    }

    $conditions = get_where_cond($where);
    
	if($columns != []) {
		foreach($columns as &$column) {
			$column = '`' . $column . '`';
		}
		$columns_cond = implode(', ', $columns);
	}
	else
		$columns_cond = '*';
    $query = "SELECT $columns_cond FROM `$table`";
    if(!empty($conditions))
        $query .= " WHERE {$conditions}";
        
	if( !$many )
	    $query .= ' LIMIT 1';

	$mdb->check_current_query = false;
	
	if( $many )
	    return $mdb->get_results( $query );
	else 
	    return $mdb->get_row( $query );
}

function get_where_cond($where) {

	$conditions = $values = array();
	foreach ( $where as $field => $value ) {
		if ( is_null( $value ) ) {
			$conditions[] = "`$field` IS NULL";
			continue;
		}
        $value = sqlstring($value, false);
		$conditions[] = "`$field` = '" . $value . "'";
	}
	
	$conditions = implode( ' AND ', $conditions );

    return $conditions;
}


function soltstring($string)
{
    return md5(SOLT. $string);
}

function sqlstring($string, $notags = true)
{
    global $mdb;
    if($notags)
        $string = strip_tags($string);
    return trim($mdb->_real_escape($string));
}

function display_menu($menu, $active_id = false, $tag = "ul", $tagclass = [], $litag = "li", $liclass = []) {
    global $Page;
    if(!$active_id)
        $active_id = $Page["id"];
    $class = implode(" ", $tagclass);
    echo '<'.$tag.' class="'.$class.'">';
    foreach($menu as $item) {
        display_menu_item($item, $active_id, $litag, $liclass);
        if(is_array($item["submenu"]) && count($item["submenu"] > 0)) {
        /*echo '<ul class="submenu" id="item-'.$item["id"].'-sub">';
        foreach($item["submenu"] as $subItem) {
            display_menu_item($subItem);
        }
        echo '</ul>'; */
        $subclass = $tagclass;
        $subclass[] = "submenu";
        display_menu($item["submenu"], $active_id, $tag, $subclass, $litag, $liclass);
        }
    }
    echo '</'.$tag.'>';
}

function display_menu_item($item, $active_id, $tag = "li", $tagclass = []) {
    $classes = $item["classes"];
    if($active_id == $item["id"] || (is_array($active_id) && in_array($item["id"], $active_id)))
        $classes[] = "active";
    $classes = array_merge($classes, $tagclass);
    $class = implode(" ", $classes);
    echo '<'.$tag.' class="'.$class.'" id="item-'. $item["id"] .'"><a href=' . $item["href"] . '>' . $item['title'] . '</a></'.$tag.'>';
}

function set_get_param($query, $param, $val) {
    parse_str($query, $params);
    $params[$param] = $val;
    return http_build_query($params);
}

function del_get_param($query, $param) {
    parse_str($query, $params);
    unset($params[$param]);
    return http_build_query($params);
}

function format_money($summ) {
    return number_format($summ, 0, '', ' ');
}

function url_with_param($param, $val) {
    $parts = explode('?', $_SERVER['REQUEST_URI'], 2);
    $query = $parts[1];
    parse_str($query, $params);
    $params[$param] = $val;
    if($val === false)
        unset($params[$param]);
    return $parts[0] . '?'. http_build_query($params);
}

function html_attrs($attrs) {
    $attrs = array_map(function($key) use ($attrs)
    {
        if(is_bool($attrs[$key]))
        {
            return $attrs[$key]?$key:'';
        }
        return $key.'="'.$attrs[$key].'"';
    }, array_keys($attrs));
    return join(' ' , $attrs);
}

function json_result($result) {
    $return = is_array($result) ? $result : array("result" => $result);
    $return["success"] = $return["result"] == SUCCESS;
    return json_encode($return);
}

function gen_unique_field($field, $func, $table = '') {
    global $mdb;
    $i = 1;
    do { 
    	$val = $func($i++);
    	$check = $mdb->get_var("SELECT COUNT(*) FROM `{$table}` WHERE `{$field}` = '{$val}' LIMIT 1");
    } while($check > 0); 
    return $val;
}

function gen_code($field, $table = '', $length = 12) {
    return gen_unique_field($field, function($i) use($length) { 
        return substr(md5(time() . $i), 3, 3 + $length);
        
    }, $table);
}

function get_in_enum($val, $enum, $standartVal)

{

    if (in_array($val, $enum))

        return $val;

    else

        return $standartVal;
}

function get_in_enum_keys($val, $enum, $standartVal)

{

    if (in_array($val, array_keys($enum)))

        return $val;

    else

        return $standartVal;
}

function get_in_enum_by_key($key, $enum, $standartVal) {
    if (in_array($key, array_keys($enum)))

        return $enum[$key];
    else
        return $standartVal;
}

function pil_string($str)
{
    return strip_tags(trim($str));
}


function set_field_val($field, $val)
{
    $field["value"] = !isset($field["filter"]) ? $val : call_user_func($field["filter"], $val);
    return $field;
}

function set_fields_vals($fields, $vals)
{
    foreach ($fields as $key => &$field) {
        if (!isset($vals[$key]))
            continue;
        $field = set_field_val($field, $vals[$key]);
    }
    return $fields;
}

function get_template_part($path) { 
    include_once SITE_TEMPLATE_PATH . $path . ".php";
}

function check_upload($index)
{
    return isset($_FILES[$index]) && $_FILES[$index]["error"] !== UPLOAD_ERR_NO_FILE;
}

// Заполнение массива значениями по умолчанию при отстутствии заданных
function fill_array($basics, $vals)
{

    foreach ($basics as $key => $val)

        if (!isset($vals[$key]))

            $vals[$key] = $val;

    return $vals;
}

require_once INCLUDE_PATH . "meta-fields.php";
require_once INCLUDE_PATH . "Attachments/attachments.php";
?>