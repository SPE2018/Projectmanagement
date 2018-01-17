<?php

include "wrapper/user_class.php";
include "wrapper/project_class.php";
include "wrapper/milestone_class.php";

/*
 * Globals
 */

// Projects array
$projects = array();

// Current Connection
$con = null;

/*
 * Connection and utility functions
 */

function sql_connect() {
    global $con;
    $mysqli = new mysqli("localhost", "root", "", "planit");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        die();
    }
    $mysqli->set_charset('utf8');
    $con = $mysqli;
    return $mysqli;
}

function sql_query($sql) {
    global $con;
    $result = $con->query($sql);
    if (!$result) {
        die("Error while querying: $sql: " . $con->error);
    }
    return $result;
}

function sql_get() {
    global $con;
    return $con;
}

/*
 * Users
 */

function sql_getUser($username) {
    $sql = "SELECT * FROM users WHERE name='$username'";
    $result = sql_query($sql)->fetch_assoc();
    
    $user = new User($result['id'], $result['name'], $result['mail'], $result['password']);
    return $user;
}

function sql_addUser($username, $mail, $password) {
    $sql = "INSERT INTO users ('name', 'mail', 'password') VALUES('$username', '$mail', '$password');";
    sql_query($sql);
}

/*
 * Users end
 */

/*
 * Projects
 */

function sql_getProjectFromId($id, $loadMilestones = false) {
    $sql = "SELECT * FROM projects WHERE id='$id'";
    $result = sql_query($sql)->fetch_assoc();
    
    $project = new Project($id, $result['name'], $result['created'], $result['endby']);
    
    if ($loadMilestones) {
        // TODO: load milestones...
        $sql = "SELECT * FROM milestones WHERE project_id=$id";
        $result = sql_query($sql)->fetch_all(MYSQLI_ASSOC);
        //var_dump($result);
        foreach ($result as $i) {
            var_dump($i);
            //$milestone = new Milestone($result['id']);
        }
    }
    
    return $project;
}

function sql_addProject($name) {
    $created = time(); // Current time in seconds
    $endby = time() + 60*60*24*7; // 7 days ahead in seconds
    $sql = "INSERT INTO projects (name, created, endby) VALUES('$name', from_unixtime($created), from_unixtime($endby));";
    sql_query($sql);
    
    
}

/*
 * Projects end
 */

