<?php

class Task {
    
    public $id;
    public $milestone_id;
    public $name;
    public $desc;
    public $previous_task; // The task that needs to be done before this one is active
    public $finished;
    
    public function __construct($id, $milestone_id, $name, $desc, $previous_task, $finished) {
        $this->id = $id;
        $this->milestone_id = $milestone_id;
        $this->name = $name;
        $this->desc = $desc;
        $this->previous_task = $previous_task;
        $this->finished = $finished != 0;
    }
    
}