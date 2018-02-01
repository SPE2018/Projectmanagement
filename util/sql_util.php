<?php

include_once "wrapper/user_class.php";
include_once "wrapper/project_class.php";
include_once "wrapper/milestone_class.php";
include_once "wrapper/task_class.php";
include_once "task_manager.php";
include_once "milestone_manager.php";
include_once "user_manager.php";
include_once "project_manager.php";

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


}