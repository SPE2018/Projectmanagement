<?php
include_once 'user_class.php';
include_once 'project_class.php';
include_once '../util/user_manager.php';
include_once '../util/project_manager.php';
include_once '../util/note_manager.php';

class Note {
    public $id;
    public $project;
    public $user;
    public $time;
    public $header;
    public $comment;
    
    public function __construct($load_create, $id, $project = 0, $header = "void", $comment = "void") {
        echo $load_create . $id . $project . $header . $comment . '<br>';
        if(ProjectManager::getProjectFromId((($load_create == 'load') ? 0 : $project)) == null) {
            echo '<script type="text/javascript" language="javascript">
                alert("UnknownProjectID");
                </script>';
            return null;
        }
        if((UserManager::getUser((($load_create == 'load') ? UserManager::getUser('admin')->name : $_SESSION['user']))) == null) {
            echo '<script type="text/javascript" language="javascript">
                alert("UnknownUserID");
                </script>';
            return null;
        }
        if($load_create == 'load' && ProjectManager::getProjectFromId((NoteManager::getNoteByID($id)) == null)) {
            return null;
        }
        $this->id = intval($id);  
        $this->user = ($load_create == 'load') ? 1 : $_SESSION['user'];
        $this->project = ($load_create == 'load') ? ((ProjectManager::getProjectFromId(NoteManager::getNoteByID($id))!= null) ? (ProjectManager::getProjectFromId(NoteManager::getNoteByID($id))->project) : null) : $project;
        $this->time = ($load_create == 'load' && NoteManager::getNoteByHead($header) != null) ? (NoteManager::getNoteByHead($header)->time) : date("YYYY-MM-DD hh:mm");
        $this->header = $header;
        $this->comment = ($load_create == 'load' && NoteManager::getNoteByHead($header) != null) ? (NoteManager::getNoteByHead($header)->comment) : $comment;
        if($this->project == null) {
            return null;
        }
    }
}