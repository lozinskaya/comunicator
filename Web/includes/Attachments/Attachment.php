<?php
class Attachment extends DB_Meta_Object {
    public static $db_table = 'attachments';
    public static $db_meta_table = 'attachments_data';
    public static $uploaded_id = 0;
    public static $last_file = '';
    const DIR = ATTACHMENT_PATH . '/';
    const MAX_SIZE = ATTACHMENT_MAX_SIZE;

    const UPLOAD_ERR = 0;
    const UPLOAD_SIZE_MAX = 1;
    const UPLOAD_WRONG_TYPE = 2;
    const INSERT_ERROR = 3;
    const MOVE_ERROR = 4;
    const UPLOAD_SUCCESS = 5;

    public static $allow_types = [];
    public static $allow_extensions = [];
    public static $restrict_types = [];
    public static $restrict_extensions = [];

    public $uri = "";

    public function __construct($row) {
        parent::__construct($row);
        $this->uri = $this->get_uri();
    }

    public function get_uri() {
        return sprintf(ATTACHMENT_URI_FORMAT, $this->id, $this->extension);
    }

    public static function upload($file, $account_id = 0, $allow_types = [], $allow_extensions = [], $restrict_types = [], $restrict_extensions = []) {
        if($file['error'] !== UPLOAD_ERR_OK)
            return static::UPLOAD_ERR;

        if($file['size'] > static::MAX_SIZE)
            return static::UPLOAD_SIZE_MAX;

        $check_types = count($allow_types) > 0 ? $allow_types : static::$allow_types;
        if(count($check_types) > 0)
        {
            $check_type = false;
            foreach($check_types as $allow_type) 
                if(substr($file['type'], 0, strlen($allow_type) + 1) ==  "{$allow_type}/")
                {
                    $check_type = true;
                    break;
                }

            if(!$check_type) {
                return static::UPLOAD_WRONG_TYPE;
            }
        }
        
        $check_restrict_types = count($restrict_types) > 0 ? $restrict_types : static::$restrict_types;
        if(count($check_restrict_types) > 0)
        {
            $check_restrict_type = false;
            foreach($check_restrict_types as $restrict_type) 
                if(substr($file['type'], 0, strlen($restrict_type) + 1) ==  "{$restrict_type}/")
                {
                    $check_restrict_type = true;
                    break;
                }

            if($check_restrict_type) {
                return static::UPLOAD_WRONG_TYPE;
            }
        }

        $check_extensions = count($allow_extensions) > 0 ? $allow_extensions : static::$allow_extensions;
        $check_restrict_extensions = count($restrict_extensions) > 0 ? $restrict_extensions : static::$restrict_extensions;
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        if((count($check_extensions) > 0 && !in_array($ext, $check_extensions)) ||
            (count($check_restrict_extensions) > 0 && in_array($ext, $check_restrict_extensions))) {
            return static::UPLOAD_WRONG_TYPE;
            }

        global $mdb;
        $date = time();
        $insert = static::insert(array(
            "account_id" => $account_id, 
            "filename" => $file['name'],
            "type" => $file['type'],
            "extension" => $ext,
            "size" => $file['size'],
            "create_date" => $date
        ));

        if(!$insert)
            return static::INSERT_ERROR;

        $id = $mdb->insert_id;
        $dest_path = static::DIR . "{$id}.{$ext}";
        $loaded = move_uploaded_file($file["tmp_name"], $dest_path);
        if(!$loaded)
            return static::MOVE_ERROR;
        static::$uploaded_id = $id;
        static::$last_file = $dest_path;
        return static::UPLOAD_SUCCESS;
    }
}
?>