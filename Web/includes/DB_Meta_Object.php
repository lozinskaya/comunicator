<?php
class DB_Meta_Object {
    public static $db_table;
    public static $db_meta_table;
    public $id;
    private $row;
    
    public function __construct($row)
    {
        $this->row = $row;
        foreach ( get_object_vars($row) as $key => $value )
            $this->$key = $value;
    }
    
    public function get_field($key) {
        return get_meta_field(static::$db_meta_table, $key, $this->id);
    }

    public function update_field($key, $value) {
        return update_meta_field(static::$db_meta_table, $key, $value, $this->id);
    }    

    public function get_data() {
        return get_meta_data(static::$db_meta_table, $this->id);
    }

    public function update_data($data) {
        if(!count($data))
            return true;

        return update_meta_data(static::$db_meta_table, $data, $this->id);
    }    

    public function update($data) {
        if(!count($data))
            return true;
            
        global $mdb;
        return $mdb->update(static::$db_table, $data, array("id" => $this->id));
    }

    public function delete() {
        global $mdb;
        return $mdb->delete(static::$db_table, array("id" => $this->id));
    }

    public static function get($id) {
		if(!$id)
			return false;
		$where = array("id" => $id);
		$row = db_where(static::$db_table, $where);
		return $row ? new static($row) : false;
    }
    
    public static function insert($data) {
        global $mdb;
        return $mdb->insert(static::$db_table, $data);
    }
}
?>