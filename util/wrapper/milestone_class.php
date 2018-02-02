<?php

class Milestone {

    public $id;
    public $name;
    public $desc;
    public $tasks = array();

    public function __construct($id, $name, $desc) {
        $this->id = intval($id);
        $this->name = $name;
        $this->desc = $desc;
    }

}