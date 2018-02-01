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

class ProjectUser {
    
    public $userid;
    public $name;
    public $project_id;
    public $permission;
    
    public function __construct($userid, $name, $project_id, $permission) {
        $this->userid = $userid;
        $this->name = $name;
        $this->project_id = $project_id;
        $this->permission = $permission;
    }
    
}