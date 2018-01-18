<?php

class Task {
    
    public $id;
    public $milestone_id;
    public $name;
    public $previous_task; // The id of the task that needs to be done before this one is active
    public $finished;
    
    public function __construct($id, $milestone_id, $name, $previous_id, $finished) {
        $this->id = $id;
        $this->milestone_id = $milestone_id;
        $this->name = $name;
        $this->previous_task = $previous_id;
        $this->finished = $finished != 0;
    }
    
}