<?php

class User {
    
    public $userid;
    public $name;
    public $mail;
    public $password;
    
    public function __construct($userid, $name, $mail, $password) {
        $this->userid = $userid;
        $this->name = $name;
        $this->mail = $mail;
        $this->password = $password;
    }
    
}
