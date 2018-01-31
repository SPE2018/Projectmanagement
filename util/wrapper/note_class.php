<?php
class Note {
    public $id;
    public $project;
    public $user;
    public $time;
    public $header;
    public $comment;
    
    public function __construct($id, $project, $header, $comment) {
        if(ProjectManager::getProjectFromId($project) == null) {
            echo '<script type="text/javascript" language="javascript">
                alert("UnknownProjectIDError");
                </script>';
            return null;
        }
        $this->id = intval($id);  
        $this->user = $_SESSION['user'];
        $this->project = $project;
        $this->time = date('D, d.M.Y h:i a');
        $this->header = $header;
        $this->comment = $comment;
    }
}