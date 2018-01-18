<?php

class ProjectManager {
    
    public $projects = array();
    
    public static function add($project) {
        array_push($this->projects, $project);
    }
    
    public static function get($name) {
        foreach ($this->projects as $p) {
            if ($p->name == $name) {
                return $p;
            }
        }
        return null;
    }
    
}
