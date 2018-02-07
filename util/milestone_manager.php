<?php

include_once "sql_util.php";
include_once "edit_task_util.php";
include_once "elements/bootstrap_util.php";
include_once "elements/page_builder.php";
include_once "php/functions.php";

class MilestoneManager {
    
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
            $milestone = new Milestone($i['id'], $i['name'], $i['start'], $i['stop'], $i['desc'], $i['finisheddate']);
           
            array_push($toReturn, $milestone);
            
            $milestone->tasks = TaskManager::loadTasks($milestone->id);
        }
        return $toReturn;
    }
    
    public static function loadMilestoneFromId($project_id = null, $milestone_id) {
        if ($project_id == null) {
            $sql = "SELECT * FROM milestones WHERE id=$milestone_id;";
        } else {
            $sql = "SELECT * FROM milestones WHERE project_id=$project_id AND id=$milestone_id;";
        }
        $result = SQL::query($sql)->fetch_assoc();        

        $milestone = new Milestone($result['id'], $result['name'], $result['start'], $result['stop'], $result['desc'], $result['finisheddate']);
        $milestone->tasks = TaskManager::loadTasks($milestone->id);
        return $milestone;
    }
    
    public static function loadMilestoneFromName($project_id, $milestone_name) {
        $sql = "SELECT * FROM milestones WHERE project_id=$project_id AND name='$milestone_name';";
        $result = SQL::query($sql)->fetch_assoc();        

        $milestone = new Milestone($result['id'], $result['name'], $result['start'], $result['stop'], $result['desc'], $result['finisheddate']);
        $milestone->tasks = TaskManager::loadTasks($milestone->id);
        return $milestone;
    }
    
    public static function addMilestone($project_id, $name, $desc, $start, $stop) {
        $sql = "INSERT INTO milestones (`project_id`, `name`, `desc`, `start`, `stop`) VALUES('$project_id', '$name', '$desc', '$start', '$stop');";
        SQL::query($sql);    
    }
    
    public static function updateMilestone($milestone_id, $name, $desc, $start, $stop) {
        $sql = "UPDATE milestones SET "
                . "`name`='$name', `desc`='$desc', `start`='$start', `stop`='$stop' "
                . "WHERE id=$milestone_id;";
        SQL::query($sql);     
    }
        
    public static function finishMilestone($milestone_id) {
        $finisheddate = date("Y-m-d h:m");
        $sql = "UPDATE milestones SET "
                . "`finisheddate`='$finisheddate' "
                . "WHERE id=$milestone_id;";
        SQL::query($sql);    
    }
    
    public static function getMilestoneId() {
        $milestone_id = filter_input(INPUT_GET, "milestoneid");
        if ($milestone_id == null) {
            die("MilestoneID not set");
        }
        return $milestone_id;
    }
    
    public static function pressedSave() {
       $save = filter_input(INPUT_POST, "save");
       return $save == "true";
    }
    
    public static function shouldEditTask() {
         return filter_input(INPUT_GET, "taskid") != null
                && filter_input(INPUT_GET, "edit") == "true";
    }
    
    
    
    public static function save($milestone_id) {
        $name = filter_input(INPUT_POST, "name");
        $desc = filter_input(INPUT_POST, "desc");
        $start = filter_input(INPUT_POST, "start");
        $stop = filter_input(INPUT_POST, "stop");        
        
        MilestoneManager::updateMilestone($milestone_id, $name, $desc, $start, $stop);
        
        //MilestoneManager::updateMilestone($milestone_id, $name, $desc, $start, $stop);
          
    }
    
    public static function displayMilestone($project_id, $milestone_id) {
        // Load milestones including tasks
        $milestone = MilestoneManager::loadMilestoneFromId($project_id, $milestone_id);
        if ($milestone == null) {
            die("Milestone not found");
        }
        $builder = new PageBuilder();
        
        //$builder->add(ElementFactory::createImg("php/css/milestone.jpeg"));
        
        $builder->add(ElementFactory::createHeader("Milestone <small class='text-muted'>$milestone->name</small>", "h1"));
        $builder->add(ElementFactory::createHtml("<hr style='border-color: #EEEEEE; max-width: 700px' align='left'>")->open);
        $builder->add(ElementFactory::createP("$milestone->desc", ParagraphStyle::LEAD));
        $builder->add(ElementFactory::createHtml("<br>"));
        
        /////////////////////
        // Start & Enddate //
        /////////////////////
        
        $format = "F jS";
        if ($milestone->startdate->format("Y") != $milestone->enddate->format("Y")) {
            // If the start and end year are different, add the year to the format
            $format = $format . " Y";
        }
        $startdate = $milestone->startdate->format($format); // Monat ausgeschrieben / Tag des Monats + englischer Anhang / Jahr
        $stopdate = $milestone->enddate->format($format); // Monat ausgeschrieben / Tag des Monats + englischer Anhang / Jahr
        
        $builder->add(ElementFactory::createP("<small class='text-muted'>Started</small> $startdate", ParagraphStyle::LEAD));
        $builder->add(ElementFactory::createP("<small class='text-muted'>Ends by</small> $stopdate", ParagraphStyle::LEAD));
        
        $builder->add(ElementFactory::createHtml("<br>"));
        
        ////////////////
        // Task Table //
        ////////////////
        
        $today = new DateTime();

        $divTable = ElementFactory::createHtml("<div class='table'>", "</div>");
        $table = ElementFactory::createHtml("<table class='table'>", "</table>");
                
        $tasks = $milestone->tasks;
        
        $builder->add($divTable->open);
        $builder->add($table->open);
       
        foreach ($tasks as $t) {
            $trClass = "class='bg-primary'";
            $name = "$t->name";
            if ($t->finished) {
                $trClass = "class='bg-success'";
                $name = $name . " &#10004;";
            } else if ($today > $t->enddate) {
                $trClass = "class='bg-danger'";
                $name = "<b>" . $name . "</b>";
            }

            $tr = ElementFactory::createHtml("<tr>", "</tr>");
            $builder->add($tr->open);

            $td = ElementFactory::createHtml("<td style='border: none'>", "</td>");
            $builder->add($td->open);

            $builder->add(ElementFactory::createHtml("<button type='button' class='btn btn-info task' value='$t->id'>$name</button>"));

            $builder->add($td->close);
            $builder->add($tr->close);
        }
        
        $builder->add($table->close);
        $builder->add($divTable->close);
        
        $builder->add(ButtonFactory::createButton(ButtonType::PRIMARY, "Edit", false, "editmilestone", "$milestone_id"));        
        $builder->add(ButtonFactory::createButton(ButtonType::DANGER, "Delete", false, "deletemilestone", "$milestone_id"));
        
        $builder->add(ButtonFactory::createButton(ButtonType::SUCCESS, "Add new Task", false, "addtask", "$milestone_id"));
        
        ////////////////
        ////////////////
        ////////////////
        
        $builder->show();
    }
    
    public static function displayEditMilestone($project_id, $milestone_id) {
        if (MilestoneManager::pressedSave()) {
            //MilestoneManager::save($milestone_id);
            echo BUtil::success("Die Änderungen am Meilenstein wurden <strong>gespeichert.</strong>");
        }
        // If the user pressed the finish button, change the finish state of the task
        /*if (TaskEditor::toggledFinished()) {
            TaskEditor::handleFinished();
        }*/
        
        // Load milestone including tasks
        $milestone = MilestoneManager::loadMilestoneFromId($project_id, $milestone_id);
        if ($milestone == null) {
            die("Milestone not found");
        }        
        
        $builder = new PageBuilder();

        $builder->add(ElementFactory::createHtml(
                "<h1>Milestone " . $milestone->name . "</h1><br>")->open);
        
        $form = ElementFactory::createHtml(
                "<form method='post'>", "</form>");
        $form_group = ElementFactory::createHtml(
                '<div class="form-group">', "</div>");
        
        $builder->add($form->open);        
        $builder->add($form_group->open); 
        
        $builder->add(ElementFactory::createLabel("name", "Name:"));
        $builder->add(ElementFactory::createTextInput("name", $milestone->name));
        $builder->add(ElementFactory::createLabel("desc", "Beschreibung:"));
        $builder->add(ElementFactory::createTextInput("desc", $milestone->desc));
        
        $builder->add(ElementFactory::createLabel("start", "Startzeit:"));
        $builder->add(ElementFactory::createDatepicker("start", "start_picker", $milestone->startdate));
        $builder->add(ElementFactory::createLabel("stop", "Endzeit:"));
        $builder->add(ElementFactory::createDatepicker("stop", "stop_picker", $milestone->enddate));
        
        $builder->add(ButtonFactory::createButton(ButtonType::PRIMARY, "Speichern", false, "save_milestone", "true"));
        
        $builder->add($form->close);        
        $builder->add($form_group->close);
        
        $ol = ElementFactory::createHtml("<ol>", "</ol>");
        
        $builder->add($ol->open);
        
        $tasks_array = $milestone->tasks;
        foreach ($tasks_array as $task) {
            $finished = $task->finished ? "Fertig" : "Nicht fertig";
            
            $li = ElementFactory::createHtml("<li>", "</li>");
            $builder->add($li->open);
            $builder->add(ElementFactory::createHtml("<h4>" . $task->name . " ($finished)</h4>")->open);
            $builder->add(ElementFactory::createHtml(TaskEditor::displayTask($project_id, $milestone_id, $task->id))->open);
            $builder->add($li->close);
        }
        $builder->add($ol->close);
        
          
        
        
        $builder->show();
    }
    
    public static function shouldDisplayMilestoneModal() {
        $project_id = filter_input(INPUT_GET, "projectid");
        if ($project_id == null) {
            echo "false<br>";
            return false;
        }
        $milestone_id = filter_input(INPUT_GET, "milestoneid");
        if ($milestone_id == null) {
            echo "false<br>";
            return false;
        }
        echo "true<br>";
        return true;
    }
    
    public static function getMilestoneModal($projectid, $milestone) {        
        $modalname = $milestone->id;
        $modalname = str_replace(" ", "_", $modalname);
        $out = "";
        
        $out = $out . '<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#' . $modalname . '">Meilenstein ' . $milestone->name . '</button><br>';
        
        
        // Open Container
        $out = $out . "<div id='$modalname' class='modal fade' role='dialog'>";
        $out = $out . "<div class='modal-dialog'>";
        
        
        // Modal Content
        $out = $out . "<div class='modal-content'>";
        
        // Modal Header
        $out = $out . "<div class='modal-header'>";
        $out = $out . '<button type="button" class="close" data-dismiss="modal">&times;</button>
                       <h4 class="modal-title">' . $milestone->name . '</h4>';
        $out = $out . "</div>";
        // End Modal Header        
        
        // Modal Body
        $out = $out . '<div class="modal-body">';       
        
        $out = $out . "<p>Beschreibung: " . $milestone->desc . "</p>";
                
        //$out = $out . var_export($milestone, true);
        
        $out = $out . '</div>';
        // End Modal Body
        
        // Modal Footer
        $out = $out . '<div class="modal-footer">';
        $out = $out . '<a href="edit_milestone.php?projectid=' . $projectid . '&milestoneid=' . $milestone->id . '" class="btn btn-primary active">Bearbeiten</a>';
        $out = $out . '<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>';        
        $out = $out . '</div>';
        // End Modal Footer
        
        $out = $out . "</div>";
        // End Modal Content
                
        // Close Container
        $out = $out . "</div>";
        $out = $out . "</div>";              
        
        return $out;
    }
    
    public static function addMiSt() {
        $toReturn = ElementFactory::createLabel('MiStName', 'Name:')->get();
        $toReturn = $toReturn . '<br>' .  ElementFactory::createTextInput('param_MiStName', '')->get();
        $toReturn = $toReturn . '<br>' . ElementFactory::createLabel('MiStDesc', 'Description:')->get();
        $toReturn = $toReturn . '<br>' . ElementFactory::createTextInput('param_MiStDesc', '')->get();
        $toReturn = $toReturn . '<br>' . ElementFactory::createLabel('MiStStart', 'Start work latest by:')->get();
        $toReturn = $toReturn . '<br>' . ElementFactory::createDatepicker('param_MiStStart', 'param_MiStStart', new DateTime('now'))->get();
        $toReturn = $toReturn . '<br>' . ElementFactory::createLabel('MiStEnd', 'Finish latest by:')->get();
        $toReturn = $toReturn . '<br>' . ElementFactory::createDatepicker('param_MiStEnd', 'MiStEnd', new DateTime('now'))->get();
        
        $toReturn = $toReturn . '<div align=right>' . ButtonFactory::createButton(ButtonType::BASIC, 'Save', FALSE, 'Btn_SaveNewMiSt', 'custom_params')->marginget('ml-3 mt-4');
        for($i=0; $i<21; $i++) {
            $toReturn = $toReturn . '&nbsp;';
        }
        $toReturn = $toReturn . ButtonFactory::createButton(ButtonType::BASIC, 'Cancel', FALSE, 'Btn_CancelNewMiSt', 'cancelAddMiSt')->marginget('mt-4') . '</div>';
        return $toReturn;
    }
    
    public static function saveNewMiSt($pid) {
        MilestoneManager::addMilestone($pid, filter_input(INPUT_GET, 'MiStName'), filter_input(INPUT_GET, 'MiStDesc'), filter_input(INPUT_GET, 'MiStStart'), filter_input(INPUT_GET, 'MiStEnd'));
        echo $pid . "<br>" . filter_input(INPUT_GET, 'MiStName') . "<br>" . filter_input(INPUT_GET, 'MiStDesc') . "<br>" . filter_input(INPUT_GET, 'MiStStart') . "<br>" . filter_input(INPUT_GET, 'MiStEnd');
    }
}