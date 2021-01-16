<?php
// Need defined MAILING_TEMPLATE_PATH
class PIL_Mail {
    public $message = '';
    public $subject = '';
    public $headers = [];
    public $to, $from;
    
    function __construct($to, $from) {
        $this->headers = array( "Content-type: text/html", 
        "From: {$from}");
        $this->to = $to;
        $this->from = $from;
    }
    
    public function fill_template($template, $args) {
        $this->message = self::template($template, $args);
    }
    
    public static function template($template, $args)
    {
        $file = MAILING_TEMPLATE_PATH . "{$template}.php";
        $html = file_get_contents($file);
        foreach($args as $key => $val)
        {
            $templatekey = "%" . strtoupper($key) . "%";
            $html = str_replace($templatekey, $val, $html);
        }
        return $html;
    }
    
    public function send() {
        return mail($this->to, $this->subject, $this->message, implode("\r\n", $this->headers));
    }
    
}
?>