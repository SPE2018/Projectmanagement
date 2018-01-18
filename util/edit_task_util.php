<?php

include_once "sql_util.php";

class TaskEditor {
    public static function displayTask() {
        $task_id = filter_input(INPUT_GET, "taskid");
        if ($task_id == null) {
            die("TaskID not set");
        }
        
        $task = TaskManager::getTask($task_id);
        
        $modalname = $task_id . "_task";
        
        $out = "";
        
        $out = $out . '<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#' . $modalname . '">Open Modal</button>';
        
        
        $out = $out . "<div id='$modalname' class='modal fade' role='dialog'>";
        $out = $out . "<div class='modal-dialog'>";
        
        // Modal Content
        $out = $out . "<div class='modal-content'>";
        
        // Modal Header
        $out = $out . "<div class='modal-header'>";
        $out = $out . '<button type="button" class="close" data-dismiss="modal">&times;</button>
                       <h4 class="modal-title">' . $task->name . '</h4>';
        $out = $out . "</div>";
        // End Modal Header        
        
        // Modal Body
        $out = $out . '<div class="modal-body">';
        
        $isFinished = $task->finished ? "Yes" : "No";
        
        $out = $out . "<p>Fertig: " . $isFinished . "</p>";
                
        $out = $out . var_export($task, true);
        
        $out = $out . '</div>';
        // End Modal Body
        
        // Modal Footer
        $out = $out . '<div class="modal-footer">';
        $out = $out . '<button type="button" class="btn btn-default" data-dismiss="modal">Speichern</button>';
        $out = $out . '</div>';
        // End Modal Footer
        
        $out = $out . "</div>";
        // End Modal Content
        
        
        $out = $out . "</div>";
        $out = $out . "</div>";
        
        
        
        echo $out;
    }
}