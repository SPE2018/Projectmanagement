<?php


class TaskManager {
    
    public static function loadTasks($milestone_id) {
        $toReturn = array();
        $sql = "SELECT * FROM tasks WHERE milestone_id=$milestone_id;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
        
        foreach ($result as $i) {
            $task = new Task($i['id'], $i['milestone_id'], $i['name'], $i['previous_task'], $i['finished']);
            array_push($toReturn, $task);
        }
        return $toReturn;
    }

    public static function addTask($milestone_id, $name, $previous_id) {
        $sql = "INSERT INTO tasks (milestone_id, name, previous_id) VALUES('$milestone_id', '$name', '$previous_id');";
        SQL::query($sql);
    }
    
    public static function getTask($id) {
        $sql = "SELECT * FROM tasks WHERE id=$id;";
        $result = SQL::query($sql)->fetch_assoc();
        return new Task($result['id'], $result['milestone_id'], $result['name'], $result['previous_task'], $result['finished']);
    }
}