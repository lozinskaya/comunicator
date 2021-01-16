<?php
class ImageAttachment extends Attachment {
    public static $allow_types = ["image"];
    public function __construct($row) {
        parent::__construct($row);
    }

    public static function upload($file, $account_id = 0, $crop_sizes = array())  {
        $upload = parent::upload($file, $account_id);
        if($upload != parent::UPLOAD_SUCCESS)
            return $upload;

        $crop_sizes = fill_array(array(
            "width" => 0,
            "height" => 0
        ), $crop_sizes);
        if($crop_sizes["width"] && $crop_sizes["height"]) {
            include_once RESUME_LK_PATH . 'ImageResize/ImageResize.php';
            
            $image = new \Gumlet\ImageResize(parent::$last_file);
            $image->crop($crop_sizes["width"], $crop_sizes["height"]);
            $loaded = $image->save(parent::$last_file);
            if(!$loaded)
                return self::UPLOAD_ERR;
        }
        return self::UPLOAD_SUCCESS;
    }
}
?>