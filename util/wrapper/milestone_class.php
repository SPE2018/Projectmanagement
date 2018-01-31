<?php

class Milestone {
    
    public $id;
    public $name;
    public $startdate;
    public $enddate;
    public $desc;
    public $tasks = array();
    
    public function __construct($id, $name, $startdate, $enddate, $desc) {
        $this->id = intval($id);        
        $this->name = $name;
        $this->startdate=$startdate;
        $this->enddate=$enddate;
        $this->desc = $desc;
    }
    
}