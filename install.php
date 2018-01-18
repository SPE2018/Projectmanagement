<?php
    $installed = false;

    function query($con, $sql) {
        $result = $con->query($sql);
        if (!$result) {
            die("Error while querying: $sql: " . $con->error);
        }
        return $result;
    }
    
    function installAll() {
        global $installed;
        
        $con = new mysqli("localhost", "root", "", "planit");
        if ($con->connect_errno) {
            echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
            die();
        }
        $con->set_charset('utf8');
        
        query($con, "CREATE TABLE `projects` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(45) NOT NULL,
                        `created` date DEFAULT NULL,
                        `endby` date DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;");
        
        query($con, "CREATE TABLE `milestones` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `project_id` int(11) NOT NULL,
                        `name` varchar(45) NOT NULL,
                        `desc` varchar(300) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;");
        
        query($con, "CREATE TABLE `users` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(45) NOT NULL,
                        `mail` varchar(45) NOT NULL,
                        `password` varchar(90) NOT NULL,
                        `enabled` tinyint(1) DEFAULT '0',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;");
        
        query($con, "CREATE TABLE `tasks` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `milestone_id` int(11) NOT NULL,
                        `name` varchar(45) NOT NULL DEFAULT 'Task',
                        `previous_task` int(11) DEFAULT '-1',
                        `finished` tinyint(1) DEFAULT '0',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;");
        
        $installed = true;
    }

    if (filter_input(INPUT_GET, "install") !== null) {
        installAll();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Install planIT</title>
    </head>
    <body>
        <h1>Willkommen bei planIT</h1>
        <?php
            global $installed;
            if ($installed) {
                echo "<p>Erfolgreich installiert.</p>";
            } else {
                echo "<form>
                <button type='submit' name='install' value='all'>Installieren</button>          
                </form>";
            }
        ?>
        

    </body>
</html>
