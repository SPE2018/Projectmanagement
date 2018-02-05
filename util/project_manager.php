<?php

include_once "sql_util.php";
include_once 'LoginUtility.php';

class ProjectManager {
    
    public static function displayProjectList() {
        $user_id = Login::getLoggedInId();
        $projects = ProjectManager::getAllProjects($user_id);
        if (count($projects) == 0) {
            echo '<p>No projects found</p>';
        }
        foreach($projects as $v) {
            echo '<a class="dropdown-item" href="projects.php?name=' . $v->name . '">' . $v->name . '</a>';
        }
    }
    
    public static function getAllProjects($user_id, $loadMilestones = false, $loadTasks = false) {        
        $user = UserManager::getUserByID($user_id);
        if ($user == null) {
            return array();
        } else if (!($user->enabled)) {
            return array();
        }
        
        $sql = "";
        
        if ($user->admin) {
            $sql = "SELECT * FROM projects;";
        } else {
            $sql = "SELECT * FROM projects as p "
                    . "JOIN projects_users as pu ON pu.project_id=p.id "
                    . "WHERE user_id=$user_id;";
        }
        
        $toReturn = array();        
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC); // TODO: Error handling
        
        foreach ($result as $r) {            
            $project = new Project($r['id'], $r['name'], $r['created'], $r['endby']);  
            if ($loadMilestones) {
                $sql = "SELECT * FROM milestones WHERE project_id=" . $r['id'] . ";";
                $result_milestone = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
                foreach ($result_milestone as $i) {
                    $milestone = new Milestone($i['id'], $i['name'], $i['start'], $i['stop'], $i['desc']);
                    array_push($project->milestones, $milestone);
                    if ($loadTasks) {
                    $milestone->tasks = TaskManager::loadTasks($milestone->id);
                }
                }
            }
            array_push($toReturn, $project);
        }
        return $toReturn;
    }
    
    public static function getProjectFromId($id, $loadMilestones = false, $loadTasks = false) {
        $sql = "SELECT * FROM projects WHERE id='$id';";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling

        $project = new Project($id, $result['name'], $result['created'], $result['endby']);

        if ($loadMilestones) {
            $sql = "SELECT * FROM milestones WHERE project_id=$id;";
            $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
            foreach ($result as $i) {
                $milestone = new Milestone($i['id'], $i['name'], $i['start'], $i['stop'], $i['desc']);
                array_push($project->milestones, $milestone);
                if ($loadTasks) {
                    $milestone->tasks = TaskManager::loadTasks($milestone->id);
                }
            }
        }
        return $project;
    }

    public static function getProjectFromName($name) {
        $sql = "SELECT * FROM projects WHERE `name`='$name';";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling
        $id = $result['id'];
        $project = new Project($id, $result['name'], $result['created'], $result['endby']);

        $sql = "SELECT * FROM milestones WHERE project_id=$id;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
        foreach ($result as $i) {
            $milestone = new Milestone($i['id'], $i['name'], $i['start'], $i['stop'], $i['desc']);
            array_push($project->milestones, $milestone);
            $milestone->tasks = TaskManager::loadTasks($milestone->id);
        }
        return $project;
    }

    public static function getProjectIdFromName($name) {
        $sql = "SELECT * FROM projects WHERE `name`='$name';";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling

        return $result['id'];
    }

    public static function addProject($name, $startdate, $enddate) {
        $sql = "INSERT INTO projects (name, created, endby) VALUES('$name', '$startdate', '$enddate')";
        SQL::query($sql); // TODO: Error handling   
    }
    
    public static function updateProject($project) {
        $id = $project->id;
        $name = $project->name;
        $endDate = $project->endDate;
        
        $sql = "UPDATE projects SET `name`='$name', `endby`='$endDate' WHERE id=$id";
        SQL::query($sql); // TODO: Error handling  
    }

    public static function deleteProject($id) {
        $sql = "DELETE FROM projects WHERE id=$id";
        SQL::query($sql); // TODO: Error handling
    }
    
    public static function getProjectUsers($id) {
        $sql = "SELECT project_id, user_id, permission, `name` FROM projects_users
                JOIN users on users.id = projects_users.user_id
                WHERE project_id='$id'";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC); // TODO: Error handling
        
        $toReturn = array();
        
        foreach ($result as $r) {
            $user = new ProjectUser($r['user_id'], $r['name'], $id, $r['permission']);
            
            array_push($toReturn, $user);
        }
        
        return $toReturn;
    }
    
    public static function getProjectId() {
        $project_id = filter_input(INPUT_GET, "projectid");
        if ($project_id == null) {
            die("ProjectID not set");
        }
        return $project_id;
    }
    
    public static function displayProject() {
        $project_id = ProjectManager::getProjectId();
        
        // Load project including milestones and tasks
        $project = ProjectManager::getProjectFromId($project_id, true, true);
        if ($project == null) {
            die("Project not found");
        }
        
        $milestones_array = $project->milestones;
        
        $out = "";
        
        $out = $out . "<h1>" . $project->name . "</h1><br>";
        
        $out = $out . "<ol>";
        foreach ($milestones_array as $milestone) {
            $out = $out . "<li>";
            //$out = $out . "<p><h3>Milestone " . $milestone->name . " (" . $milestone->id . ")</h3>";            
            $out = $out . MilestoneManager::getMilestoneModal($project_id, $milestone);
            $out = $out . "<p>" . $milestone->desc . "</p>";
            $out = $out . "  <ol>";            
            $tasks_array = $milestone->tasks;
            foreach ($tasks_array as $task) {
                $out = $out . "<li>";
                $out = $out . "<h4>" . $task->name . "</h4>";
                $out = $out . "</li>";
            }
            $out = $out . "  </ol>";
            $out = $out . "</li>";
        }
        $out = $out . "</ol>";
        
        
        echo $out;
    }
    
    public static function userPartOfProject($project_id, $user_id) {
        $sql = "SELECT count(*) AS amount FROM projects_users WHERE project_id = $project_id AND user_id = $user_id;";
        $result = SQL::query($sql)->fetch_assoc();
        if ($result != null) {
            // If the user is already added to this project
            if (intval($result['amount']) > 0) {                
                return true;
            }
        }
        return false;
    }
    
    function getMeetings($project_id) {
        $sql = "SELECT * FROM meetings WHERE project_id = $project_id";
    }
    
    public static function addUser($project_id, $user_id, $role) {
        if (ProjectManager::userPartOfProject($project_id, $user_id)) {
            echo BUtil::danger("This user <strong>is already part of</strong> this project.");
            return;
        }
        
        $sql = "INSERT INTO projects_users (project_id, user_id, permission) VALUES ($project_id, $user_id, '$role');";
        SQL::query($sql);
        
        echo BUtil::success("The user has been added to this project <strong>successfully.</strong>");
    }
    
    public static function removeUser($project_id, $user_id) {
         if (ProjectManager::userPartOfProject($project_id, $user_id) == false) {
             // can't remove a user that is not part of this project...
             echo BUtil::danger("This user <strong>is not part of</strong> this project.");
             return;
         }
         $sql = "DELETE FROM projects_users WHERE project_id = $project_id AND user_id = ";
    }
    
    public static function displayProjectUsers($project_id) {
        $builder = new PageBuilder();
        
        $project = ProjectManager::getProjectFromId($project_id);
        if ($project == null) {
            die("Could not find project");
        }
        
        $builder->add(ElementFactory::createHeader("Users", "h1"));
        
        $divTable = ElementFactory::createHtml("<div class='table'>", "</div>");
        $table = ElementFactory::createHtml("<table class='table-hover'>", "</table>");
                
        $users = ProjectManager::getProjectUsers($project_id);
        
        $builder->add($divTable->open);
        $builder->add($table->open);
       
        $firstLeader = true; // The first leader can't be demoted
        
        foreach ($users as $u) {
            $trClass = "class='bg-primary'";
            if ($u->permission == 'leader') {
                $trClass = "class='bg-success'";
            }
            
            $tr = ElementFactory::createHtml("<tr $trClass>", "</tr>");            
            $builder->add($tr->open);
            
            // Username column
            $td = ElementFactory::createHtml("<td>", "</td>");
            $builder->add($td->open);            
            $builder->add(ElementFactory::createHtml("$u->name ($u->userid)"));            
            $builder->add($td->close);
            // Permission column
            $td = ElementFactory::createHtml("<td>", "</td>");
            $builder->add($td->open);            
            $builder->add(ElementFactory::createHtml("$u->permission"));            
            $builder->add($td->close);            
            // Remove button column
            $td = ElementFactory::createHtml("<td>", "</td>");
            $builder->add($td->open);    
            if ($u->permission != 'leader') {
                $builder->add(ButtonFactory::createButton(ButtonType::DANGER, "Remove", false, "remove_user", "$u->userid"));            
            }
            $builder->add($td->close); 
            if ($u->permission == 'leader') {
                // Make project leader demote button column
                $td = ElementFactory::createHtml("<td>", "</td>");
                $builder->add($td->open);
                if ($firstLeader) {
                    //$builder->add(ButtonFactory::createButton(ButtonType::DISABLED, "Can't demote", false, "disabled", " "));            
                    $firstLeader = false;
                } else {
                    $builder->add(ButtonFactory::createButton(ButtonType::DANGER, "Demote", false, "demote_user", "$u->userid"));            
                }
                $builder->add($td->close);                
            } else {
                // Make project leader promote button column
                $td = ElementFactory::createHtml("<td>", "</td>");
                $builder->add($td->open);            
                $builder->add(ButtonFactory::createButton(ButtonType::SUCCESS, "Promote", false, "promote_user", "$u->userid"));            
                $builder->add($td->close); 
            }
            
            
            $builder->add($tr->close);
        }
        
        $builder->add($table->close);
        $builder->add($divTable->close);

        $allUsers = UserManager::getAllUsers();

        $builder->add(ElementFactory::createLabel("add_user_select", "Add User to this project:"));
        //$builder->add(ElementFactory::createTextInput("add_user_input", "username"));
        $searchBox = ElementFactory::createHtml("<select class='form-control' id='add_user_select'>", "</select>");
        $builder->add($searchBox->open);
        foreach ($allUsers as $u) {
            $builder->add(ElementFactory::createHtml("<option>$u->name</option>"));
        }
        $builder->add($searchBox->close);
        $builder->add(ButtonFactory::createButton(ButtonType::SUCCESS, "Add to project", false, "add_user", " "));
        
        $builder->show();
    }
    
}
