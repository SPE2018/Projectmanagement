<?php

class User {
    
    public $userid;
    public $name;
    public $mail;
    public $password;
    public $enabled; // boolean
    
    public function __construct($userid, $name, $mail, $password, $enabled = 0) {
        $this->userid = intval($userid);
        $this->name = $name;
        $this->mail = $mail;
        $this->password = $password;
        $this->enabled = $enabled != 0;
    }
    
}
