<?php
include_once "util/milestone_manager.php";
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Milestone</title>
         <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    </head>
    <body>
        <?php
        
        MilestoneManager::displayMilestone(
                    ProjectManager::getProjectId(),
                    MilestoneManager::getMilestoneId()
                );
        
        ?>
    </body>
</html>