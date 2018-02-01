<?php

include_once "sql_util.php";

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
    
    
    public static function getAllProjects($loadMilestones = false) {
        $toReturn = array();
        $sql = "SELECT * FROM projects;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC); // TODO: Error handling
        
        foreach ($result as $r) {            
            $project = new Project($r['id'], $r['name'], $r['created'], $r['endby']);  
            if ($loadMilestones) {
                $sql = "SELECT * FROM milestones WHERE project_id=" . $r['id'] . ";";
                $result_milestone = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
                foreach ($result_milestone as $i) {
                    $milestone = new Milestone($i['id'], $i['name'], $i['desc']);
                    array_push($project->milestones, $milestone);
                }
            }
            array_push($toReturn, $project);
        }
    }
    
    public static function getProjectFromId($id, $loadMilestones = false) {
        $sql = "SELECT * FROM projects WHERE id='$id';";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling

        $project = new Project($id, $result['name'], $result['created'], $result['endby']);

        if ($loadMilestones) {
            $sql = "SELECT * FROM milestones WHERE project_id=$id;";
            $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
            foreach ($result as $i) {
                $milestone = new Milestone($i['id'], $i['name'], $i['desc']);
                array_push($project->milestones, $milestone);
            }
        }
        return $project;
    }

    public static function addProject($name) {
        $created = time(); // Current time in seconds
        $endby = time() + 60*60*24*7; // 7 days ahead in seconds
        $sql = "INSERT INTO projects (name, created, endby) VALUES('$name', from_unixtime($created), from_unixtime($endby));";
        SQL::query($sql); // TODO: Error handling   
    }
    
    public static function updateProject($project) {
        $id = $project->id;
        $name = $project->name;
        $endDate = $project->endDate;
        
        $sql = "UPDATE projects SET `name`='$name', `endby`='$endDate' WHERE id=$id";
        SQL::query($sql); // TODO: Error handling  
    }
    
}
