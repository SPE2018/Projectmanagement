<?php

class Milestone {
    
    public $id;
    public $name;
    public $startdate;
    public $enddate;
    public $desc;
    public $finisheddate;
    public $tasks = array();
    
    public function __construct($id, $name, $startdate, $enddate, $desc, $finisheddate) {
        $this->id = intval($id);        
        $this->name = $name;
        $this->startdate = date_create($startdate);
        $this->enddate = date_create($enddate);
        $this->desc = $desc;
        $this->finisheddate = date_create($finisheddate);
    }
    
    public function isFinished() {
        if (count($this->tasks) == 0) {
            return false;
        }
        foreach ($this->tasks as $t) {
            if ($t->finished == false) {
                return false;
            }
        }
        return true;
    }
    
}