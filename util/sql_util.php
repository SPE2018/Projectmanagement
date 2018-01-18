<?php

include_once "wrapper/user_class.php";
include_once "wrapper/project_class.php";
include_once "wrapper/milestone_class.php";
include_once "wrapper/task_class.php";

class SQL {

    /*
     * Connection and utility functions
     */

    public static function connect() {
        static $con = null;
        if ($con == null) {
            $con = new mysqli("localhost", "root", "", "planit");
            if ($con->connect_errno) {
                echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
                die();
            }
            $con->set_charset('utf8');
        }
        return $con;
    }

    public static function query($sql) {
        $con = SQL::connect();
        $result = $con->query($sql);
        if (!$result) {
            die("Error while querying: $sql: " . $con->error);
        }
        return $result;
    }

    /*
     * Users
     */

    

    /*
     * Users end
     */

    /*
     * Projects
     */

    

    public static function loadMilestones($project_id, $limit = 0, $offset = 0) {
        $toReturn = array();
        $sql = "SELECT * FROM milestones WHERE project_id=$project_id";
        if ($limit > 0) {
            $sql = $sql . " OFFSET $offset LIMIT $limit;";
        } else {
            $sql = $sql . ";";
        }
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);        
        foreach ($result as $i) {
            $milestone = new Milestone($i['id'], $i['name'], $i['desc']);
            echo $milestone->name . "<br>";
           
            array_push($toReturn, $milestone);
            
            $milestone->tasks = SQL::loadTasks($milestone->id);
        }
        return $toReturn;
    }
    
    public static function addMilestone($project_id, $name, $desc) {
        $sql = "INSERT INTO milestones (`project_id`, `name`, `desc`) VALUES('$project_id', '$name', '$desc');";
        $result = SQL::query($sql); // TODO: Error handling        
    }

    public static function loadTasks($milestone_id) {
        $toReturn = array();
        $sql = "SELECT * FROM tasks WHERE milestone_id=$milestone_id;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
        
        foreach ($result as $i) {
            $task = new Task($i['id'], $i['milestone_id'], $i['name'], $i['previous_task'], $i['finished']);
            array_push($toReturn, $task);
        }
        return $toReturn;
    }

    public static function addTask($milestone_id, $name, $previous_id) {
        $sql = "INSERT INTO tasks (milestone_id, name, previous_id) VALUES('$milestone_id', '$name', '$previous_id');";
        SQL::query($sql);
    }

    /*
     * Projects end
     */

}
