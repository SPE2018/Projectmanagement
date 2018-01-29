<?php

class User {
    
    public $userid;
    public $name;
    public $mail;
    public $password;
    public $admin; //boolean
    public $enabled; // boolean
    
    public function __construct($userid, $name, $mail, $password, $admin = 0, $enabled = 0) {
        $this->userid = intval($userid);
        $this->name = $name;
        $this->mail = $mail;
        $this->password = $password;
        $this->admin = $admin != 0;
        $this->enabled = (($admin) ? 1 : $enabled != 0);
    }
}
