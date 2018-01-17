<?php

class Project {
    
    public $id;
    public $name;
    public $milestones = array();
    public $createdDate;
    public $endDate;
    
    public function __construct($id, $name, $createdDate, $endDate) {
        $this->id = $id;
        $this->name = $name;
        $this->createdDate = $createdDate;
        $this->endDate = $endDate;
    }
    
}