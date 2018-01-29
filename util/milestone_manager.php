<?php

include_once "sql_util.php";

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
            $milestone = new Milestone($i['id'], $i['name'], $i['desc']);
           
            array_push($toReturn, $milestone);
            
            $milestone->tasks = TaskManager::loadTasks($milestone->id);
        }
        return $toReturn;
    }
    
    public static function loadMilestoneFromId($project_id, $milestone_id) {
        $sql = "SELECT * FROM milestones WHERE project_id=$project_id AND id=$milestone_id;";
        $result = SQL::query($sql)->fetch_assoc();        

        $milestone = new Milestone($result['id'], $result['name'], $result['desc']);
        $milestone->tasks = TaskManager::loadTasks($milestone->id);
        return $milestone;
    }
    
    public static function addMilestone($project_id, $name, $desc) {
        $sql = "INSERT INTO milestones (`project_id`, `name`, `desc`) VALUES('$project_id', '$name', '$desc');";
        $result = SQL::query($sql); // TODO: Error handling        
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
    
    public static function save() {
        
    }
    
    public static function displayMilestone($project_id, $milestone_id) {
        if (MilestoneManager::pressedSave()) {
            MilestoneManager::save();
            echo '<div class="alert alert-success">
                Die Änderungen wurden <strong>gespeichert.</strong>
              </div>';
        } else {
            echo "Modify<br>";
        }
        
        // Load milestone including tasks
        $milestone = MilestoneManager::loadMilestoneFromId($project_id, $milestone_id);
        if ($milestone == null) {
            die("Milestone not found");
        }
        
        
        $out = "";       
        

        $out = $out . "<h1>Milestone " . $milestone->name . "</h1><br>";
        
        $out = $out . "<form method='post'>"; 
        $out = $out . '<div class="form-group">';
        
        $out = $out . '<label for="desc">Beschreibung:</label>';
        $out = $out . "<input class='form-control' id='desc' type='text' name='desc' value='" . $milestone->desc . "'>";     
        
        $out = $out . "<ol>";            
        $tasks_array = $milestone->tasks;
        foreach ($tasks_array as $task) {
            $out = $out . "<li>";
            $out = $out . "<h4>" . $task->name . "</h4>";
            $out = $out . "</li>";
        }
        $out = $out . "</ol>";
        
        $out = $out . "<button class='btn btn-primary' type='submit' name='save' value='true'>Speichern</button>";     
        $out = $out . "</div>"; // Close form group
        $out = $out . "</form>";     
        
        
        echo $out;
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
        $modalname = $milestone->name;
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
                
        $out = $out . var_export($milestone, true);
        
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
    
}