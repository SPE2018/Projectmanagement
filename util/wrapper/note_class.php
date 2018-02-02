<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
include_once 'note_class.php';
include_once 'user_class.php';
include_once 'project_class.php';
include_once '../util/user_manager.php';
include_once '../util/project_manager.php';
include_once '../util/note_manager.php';

class Note {
    public $project;
    public $user;
    public $time;
    public $header;
    public $comment;
    
    public function __construct($project, $timestamp,  $header, $comment) {
        if(ProjectManager::getProjectFromId($project) == null) {
            echo '<script type="text/javascript" language="javascript">
                alert("UnknownProjectID");
                </script>';
            return null;
        }
        if(UserManager::getUser(isset($_SESSION['user'])) == null) {
            echo '<script type="text/javascript" language="javascript">
                alert("UnknownUserID");
                </script>';
            return null;
        } 
        $this->user = $_SESSION['user'];
        $this->project = $project;
        $this->time = ($timestamp == 'now') ? date("Y-m-d h:m") : $timestamp;
        $this->header = $header;
        $this->comment = $comment;
    }
}