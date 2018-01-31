<?php

class Milestone {
    
    public $id;
    public $name;
    public $desc;
    public $start;
    public $stop;
    public $tasks = array();
    
    public function __construct($id, $name, $desc, $start, $stop) {
        $this->id = intval($id);        
        $this->name = $name;
        $this->desc = $desc;
        $this->start = $start;
        $this->stop = $stop;
    }
    
}