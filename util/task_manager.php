<?php
class TaskManager {
    
    public static function getExistingTask($array, $id) {
        foreach ($array as $task) {
            if ($task->id == $id) {
                return $task;
            }
        }
        return null;
    }
    
    public static function updateTask($task_id, $name, $desc, $prev, $end) {   
        $sql = "UPDATE tasks SET "
                . "`name`='$name', `desc`='$desc', `previous_task`='$prev', `enddate`='$end' "
                . "WHERE id=$task_id;";
        SQL::query($sql); 
    }

    public static function removeTask($task_id) {
        $sql = "DELETE FROM tasks WHERE id=$task_id;";
        SQL::query($sql);
    }
    
    public static function loadTasks($milestone_id) {
        $toReturn = array();
        $sql = "SELECT * FROM tasks WHERE milestone_id=$milestone_id;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
        
        foreach ($result as $i) {
            $previous_task_id = $i['previous_task'];
            $previous_task = TaskManager::getExistingTask($toReturn, $previous_task_id);
            
            $task = new Task($i['id'], $i['milestone_id'], $i['name'], $i['desc'], $previous_task, $i['finished'], $i['enddate']);
            array_push($toReturn, $task);
        }
        return $toReturn;
    }

    public static function addTask($milestone_id, $name, $desc, $previous_id, $enddate) {
        $sql = "INSERT INTO tasks (`milestone_id`, `name`, `desc`, `previous_task`, `enddate`) VALUES('$milestone_id', '$name', '$desc', '$previous_id', '$enddate');";
        SQL::query($sql);
    }
    
    public static function getTask($id) {
        $sql = "SELECT * FROM tasks WHERE id=$id;";
        $result = SQL::query($sql)->fetch_assoc();
        
        $previous_task_id = $result['previous_task'];
        $previous_task = null;
        if ($previous_task_id >= 0) {
            $previous_task = TaskManager::getTask($previous_task_id);
        }
        
        return new Task($result['id'], $result['milestone_id'], $result['name'], $result['desc'], $previous_task, $result['finished'], $result['enddate']);
    }
    
    public static function setFinished($taskid, $finished) {
        $finished = $finished ? 1 : 0;
        $sql = "UPDATE tasks SET `finished`='$finished' WHERE id='$taskid';";
        SQL::query($sql);
        
        // Get the milestone that belongs to this task        
        $sql = "SELECT id, milestone_id FROM tasks WHERE id='$taskid'";
        $result = SQL::query($sql)->fetch_assoc();
        $milestone_id = $result['milestone_id'];
        $milestone = MilestoneManager::loadMilestoneFromId(null, $milestone_id);
        
        foreach ($milestone->tasks as $task) {
            if ($task->finished == false) {
                return false;
            }
        }
        
        // If we reach this point, all tasks are finished
        // -> The milestone is finished aswell
        MilestoneManager::finishMilestone($milestone_id);
        return true;
    }
}