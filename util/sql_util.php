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

    /**
     * @param type $username
     * @return Returns User if the user exists or null if the user does not exist
     */
    public static function getUser($username) {
        $sql = "SELECT * FROM users WHERE name='$username';";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling
        if ($result == null) {
            return null;
        }

        $user = new User($result['id'], $result['name'], $result['mail'], $result['password'], $result['enabled']);
        return $user;
    }

    /**
     * Adds a user to the database.
     * This user needs to be activated/enabled by an admin
     */
    public static function addUser($username, $mail, $password) {
        $sql = "INSERT INTO users ('name', 'mail', 'password', 'enabled') VALUES('$username', '$mail', '$password', 0);";
        SQL::query($sql); // TODO: Error handling
    }

    public static function enableUser($id) {
        $sql = "UPDATE users SET 'enabled'=1 WHERE id=$id;";
        SQL::query($sql); // TODO: Error handling
    }

    /*
     * Users end
     */

    /*
     * Projects
     */

    public static function getAllProjects($loadMilestones = false) {
        $toReturn = array();
        $sql = "SELECT * FROM projects WHERE;";
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
