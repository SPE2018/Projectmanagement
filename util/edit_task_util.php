<?php

include_once "sql_util.php";
include_once "elements/bootstrap_util.php";

class TaskEditor {
    
    public static function toggledFinished() {
        return filter_input(INPUT_GET, "taskid") != null
                && filter_input(INPUT_GET, "finished") != null;
    }
    
    public static function handleFinished() {
        $taskid = filter_input(INPUT_GET, "taskid");
        $finished = filter_input(INPUT_GET, "finished") == "true";
        TaskManager::setFinished($taskid, $finished);
        echo BUtil::success("Die Änderungen am Task wurden <strong>gespeichert.</strong>");        
    }
    
    public static function editTask() {
         // If the user pressed the finish button, change the finish state of the task
        if (TaskEditor::toggledFinished()) {
            TaskEditor::handleFinished();
        }
        $project_id = filter_input(INPUT_GET, "projectid");
        $milestone_id = filter_input(INPUT_GET, "milestoneid");
        $task_id = filter_input(INPUT_GET, "taskid");
        
        echo "<h1>EDITING</h1>";
        
        $task = TaskManager::getTask($task_id);                       
        
        $out = "";
        
        $out = $out . ButtonFactory::createButton(ButtonType::DANGER, "Test Button", false, "btn", "test")->get();
        $out = $out . ButtonFactory::createButton(ButtonType::DARK, "Test Button", false, "btn", "test")->get();
        $out = $out . ButtonFactory::createButton(ButtonType::SUCCESS, "Test Button", false, "btn", "test")->get();
        
        echo $out;
    }
    
    public static function displayTask($project_id, $milestone_id, $task_id) {                
        $task = TaskManager::getTask($task_id);
        
        $modalname = $task_id . "_task";
        
        $out = "";
        
        $out = $out . '<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#' . $modalname . '">Open Modal</button>';
        
        
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
        
        $isFinished = $task->finished ? "Ja" : "Nein";        
        $out = $out . "<p>Fertig: " . $isFinished . "</p><br>";
        
        $previous_task = $task->previous_task;
        $previous = "Kein vorheriger Task";
        if ($previous_task != null) {
            $previous = $previous_task->name;
            if ($previous_task->finished) {
                $previous = $previous . " (Fertig)";
            } else {
                $previous = $previous . " (Nicht Fertig)";
            }
        }
        $out = $out . "<p>Vorheriger Task: " . $previous . "</p><br>";
                
        //$out = $out . var_export($task, true);
        
        $out = $out . $task->desc;
        
        $out = $out . '</div>';
        // End Modal Body
        
        // Modal Footer
        $out = $out . '<div class="modal-footer">';
        $out = $out . "<form action='edit_milestone.php' method='get'>";
        // Open form container
        $out = $out . '<div class="form-group">';
        $out = $out . "<input type='hidden' name='projectid' value='$project_id'>";
        $out = $out . "<input type='hidden' name='milestoneid' value='$milestone_id'>";
        $out = $out . "<input type='hidden' name='taskid' value='$task_id'>";
        if ($task->finished) {
            $out = $out . '<button type="submit" class="btn btn-danger" name="finished" value="false">Task als nicht fertiggestellt setzen</button>';
        } else {
            if ($previous_task != null && !$previous_task->finished) {
                $out = $out . '<button type="button" class="btn btn-disabled disabled"'
                        . ' data-toggle="tooltip" data-placement="auto top" title="Der vorherige Task ist noch nicht fertig!">Task fertigstellen</button>';
                $out = $out . "<script>
                                $(document).ready(function(){
                                    $('[data-toggle=\"tooltip\"]').tooltip(); 
                                });
                                </script>
                                ";
            } else {
                $out = $out . '<button type="submit" class="btn btn-success" name="finished" value="true">Task fertigstellen</button>';
            }
        }
        
        $out = $out . '<button type="submit" class="btn btn-default" name="edit" value="true">Bearbeiten</button>';
        
        // Close form container
        $out = $out . '</div>'; 
        $out = $out . "</form>";
        $out = $out . '</div>';
        // End Modal Footer
        
        $out = $out . "</div>";
        // End Modal Content
        
        
        $out = $out . "</div>";
        $out = $out . "</div>";
        
        
        
        return $out;
    }
}