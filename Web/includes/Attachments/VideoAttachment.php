<?php
class VideoAttachment extends Attachment {
    const MAX_SIZE = 50 * MB;
    public static $allow_types = ["video"];
    public function __construct($row) {
        parent::__construct($row);
    }


}
?>