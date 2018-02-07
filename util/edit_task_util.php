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
        $milestone_finished = TaskManager::setFinished($taskid, $finished);
        echo BUtil::success("Your changes have been <strong>saved.</strong>");
        if ($milestone_finished) {
            echo BUtil::success("<strong>Hooray!</strong> The milestone has been completed!");
        }
    }
    
    public static function addTaskToDb() {
        $name = filter_input(INPUT_GET, "name");
        $desc = filter_input(INPUT_GET, "desc");
        
        $previous = filter_input(INPUT_GET, "selectprevious");        
        $previous_id = -1;
        if ($previous != "No Previous Task") {
            $previous_id = intval(
                             explode(": ", $previous)[0]
                           );
        }
        $enddate = filter_input(INPUT_GET, "end");
        $milestone_id = filter_input(INPUT_GET, "milestone_id");
        
        TaskManager::addTask($milestone_id, $name, $desc, $previous_id, $enddate);
    }
    
    public static function createTask($milestone_id) {
        $milestone = MilestoneManager::loadMilestoneFromId(null, $milestone_id);        
        $tasks = $milestone->tasks;
        
        $builder = new PageBuilder();
        
        $builder->add(ElementFactory::createHtml("<input type='hidden' id='param_milestone_id' value='$milestone_id'>"));
        
        $builder->add(ElementFactory::createLabel("", "Task Name:"));
        $builder->add(ElementFactory::createTextInput("param_name", "My Awesome Task"));
        $builder->add(ElementFactory::createLabel("", "Task Description:"));
        $builder->add(ElementFactory::createTextInput("param_desc", "My Awesome Task Description"));
        
        $builder->add(ElementFactory::createLabel("", "Previous Task:"));
        $selectionBox = ElementFactory::createHtml("<select class='form-control' id='param_selectprevious'>", "</select>");
        $builder->add($selectionBox->open);
        $builder->add(ElementFactory::createHtml("<option>No Previous Task</option>"));
        foreach ($tasks as $task) {
            $builder->add(ElementFactory::createHtml("<option>$task->id: $task->name</option>"));
        }
        $builder->add($selectionBox->close);
        
        $builder->add(ElementFactory::createLabel("", "Deadline:"));
        $builder->add(ElementFactory::createDatepicker("param_end", "end_picker", new DateTime()));
        
        $builder->add(ButtonFactory::createButton(ButtonType::SUCCESS, "Create Task", false, "createnewtask", "custom_params"));
        $builder->add(ButtonFactory::createButton(ButtonType::DANGER, "Cancel", false, "cancel", ""));
        
        $builder->show();
    }
    
    public static function editTask() {
         // If the user pressed the finish button, change the finish state of the task
        if (TaskEditor::toggledFinished()) {
            TaskEditor::handleFinished();
        }
        $project_id = filter_input(INPUT_GET, "projectid");
        $milestone_id = filter_input(INPUT_GET, "milestoneid");
        $task_id = filter_input(INPUT_GET, "taskid");        
        
        $task = TaskManager::getTask($task_id);                       
        
        $out = "";
        
        $out = $out . ButtonFactory::createButton(ButtonType::DANGER, "Test Button", false, "btn", "test")->get();
        $out = $out . ButtonFactory::createButton(ButtonType::DARK, "Test Button", false, "btn", "test")->get();
        $out = $out . ButtonFactory::createButton(ButtonType::SUCCESS, "Test Button", false, "btn", "test")->get();
        
        echo $out;
    }
    
    public static function displayTask($project_id, $milestone_id, $task_id) {
        echo ($task_id);       
    }
    
    public static function displayTaskModal($project_id, $milestone_id, $task_id) {                
        $task = TaskManager::getTask($task_id);
        
        $modalname = $task_id . "_task";

        $out = "";
        
        $out = $out . "<div id='$modalname' class='modal fade' role='dialog'>";
        $out = $out . "<div class='modal-dialog'>";
        
        // Modal Content
        $out = $out . "<div class='modal-content'>";
        
        // Modal Header
        $out = $out . "<div class='modal-header'>";
        $out = $out . '<h4 class="modal-title">' . $task->name . '</h4><button type="button" class="close" data-dismiss="modal">&times;</button>';
        $out = $out . "</div>";
        // End Modal Header        
        
        // Modal Body
        $out = $out . '<div class="modal-body">';
        
        $isFinished = $task->finished ? '<i class="fas fa-check fa-1x text-success"></i>' : '<i class="fas fa-times fa-1x text-danger"></i>';
        $out = $out . "<p><b>Completed:&emsp;</b>" . $isFinished . "</p><br>";
        
        $previous_task = $task->previous_task;
        $previous = "No previous task";
        if ($previous_task != null) {
            $previous = $previous_task->name;
            if ($previous_task->finished) {
                $previous = $previous . " (completed)";
            } else {
                $previous = $previous . " (in processing)";
            }
        }
        $out = $out . "<p><b>Previous Task:&emsp;</b>" . $previous . "</p><br>";
                
        //$out = $out . var_export($task, true);
        
        $out = $out . "<p><b>Description: &emsp;</b></p><p>" . $task->desc ."</p>";
        
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
            $out = $out . '<button type="submit" class="btn btn-danger mr-2" name="finished" value="false">Set Task Incompleted</button>';
        } else {
            if ($previous_task != null && !$previous_task->finished) {
                $out = $out . '<button type="button" class="btn btn-disabled disabled"'
                        . ' data-toggle="tooltip" data-placement="auto top" title="Der vorherige Task ist noch nicht fertig!">Finish Task</button>';
                $out = $out . "<script>
                                $(document).ready(function(){
                                    $('[data-toggle=\"tooltip\"]').tooltip(); 
                                });
                                </script>
                                ";
            } else {
                $out = $out . '<button type="button" class="btn btn-success mr-2" name="finished" value="true">Finish Task</button>';
            }
        }
        
        $out = $out . '<button type="submit" class="btn btn-default" name="edit" value="true">Edit</button>';
        
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