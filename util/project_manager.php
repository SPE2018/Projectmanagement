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
    
    
    public static function getAllProjects($loadMilestones = false, $loadTasks = false) {
        $toReturn = array();
        $sql = "SELECT * FROM projects WHERE;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC); // TODO: Error handling
        
        foreach ($result as $r) {            
            $project = new Project($r['id'], $r['name'], $r['created'], $r['endby']);  
            if ($loadMilestones) {
                $sql = "SELECT * FROM milestones WHERE project_id=" . $r['id'] . ";";
                $result_milestone = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
                foreach ($result_milestone as $i) {
                    $milestone = new Milestone($i['id'], $i['name'], $i['desc'], $i['start'], $i['stop']);
                    array_push($project->milestones, $milestone);
                    if ($loadTasks) {
                    $milestone->tasks = TaskManager::loadTasks($milestone->id);
                }
                }
            }
            array_push($toReturn, $project);
        }
    }
    
    public static function getProjectFromId($id, $loadMilestones = false, $loadTasks = false) {
        $sql = "SELECT * FROM projects WHERE id='$id';";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling

        $project = new Project($id, $result['name'], $result['created'], $result['endby']);

        if ($loadMilestones) {
            $sql = "SELECT * FROM milestones WHERE project_id=$id;";
            $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
            foreach ($result as $i) {
                $milestone = new Milestone($i['id'], $i['name'], $i['desc'], $i['start'], $i['stop']);
                array_push($project->milestones, $milestone);
                if ($loadTasks) {
                    $milestone->tasks = TaskManager::loadTasks($milestone->id);
                }
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
    
    public static function getProjectId() {
        $project_id = filter_input(INPUT_GET, "projectid");
        if ($project_id == null) {
            die("ProjectID not set");
        }
        return $project_id;
    }
    
    public static function displayProject() {
        $project_id = ProjectManager::getProjectId();
        
        // Load project including milestones and tasks
        $project = ProjectManager::getProjectFromId($project_id, true, true);
        if ($project == null) {
            die("Project not found");
        }
        
        $milestones_array = $project->milestones;
        
        $out = "";
        
        $out = $out . "<h1>" . $project->name . "</h1><br>";
        
        $out = $out . "<ol>";
        foreach ($milestones_array as $milestone) {
            $out = $out . "<li>";
            //$out = $out . "<p><h3>Milestone " . $milestone->name . " (" . $milestone->id . ")</h3>";            
            $out = $out . MilestoneManager::getMilestoneModal($project_id, $milestone);
            $out = $out . "" . $milestone->desc . "</p>";
            $out = $out . "  <ol>";            
            $tasks_array = $milestone->tasks;
            foreach ($tasks_array as $task) {
                $out = $out . "<li>";
                $out = $out . "<h4>" . $task->name . "</h4>";
                $out = $out . "</li>";
            }
            $out = $out . "  </ol>";
            $out = $out . "</li>";
        }
        $out = $out . "</ol>";
        
        
        echo $out;
    }
    
}
