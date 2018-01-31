<?php

class Dispatcher {
    
    private static $inst;
    public $lang;
    
    private $strings;
    
    public function __construct($lang) {
        Dispatcher::$inst = $this;
        
        $this->lang = $lang;
        
        $this->strings = array();
    }
    
    private function load($lang) {
        $handle = fopen("../lang/$lang.properties", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $arr = explode("=", $line);
                $key = $arr[0];
                $val = $arr[1];
                
            }

            fclose($handle);
        } else {
            die("Language not found");
        } 
    }
    
    public static function inst() {
        return Dispatcher::$inst;
    }
    
}