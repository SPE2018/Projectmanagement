<?php
    $installed = false;
    $error = null;

    function query($con, $sql) {
        $result = $con->query($sql);
        if (!$result) {
            die("Error while querying: $sql: " . $con->error);
        }
        return $result;
    }
    
    function installAll() {
        global $installed;
        global $error;
        
        $con = null;
        
        $address = filter_input(INPUT_POST, "address");
        $db = filter_input(INPUT_POST, "db");
        $name = filter_input(INPUT_POST, "name");
        $pass = filter_input(INPUT_POST, "password");

        if (strlen($address) == 0 || strlen($db) == 0 || strlen($name) == 0) {
            $installed = false;
            $error = "Fehlerhafte Eingabe";
            return;
        }
        
        echo "Before: '$db'<br>";
        $db = str_replace(";", "", $db);
        echo "After: '$db'<br>";
        
        $con = new mysqli($address, $name, $pass);
        if ($con->connect_errno) {
            echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
            die();
        }
        query($con, "CREATE DATABASE IF NOT EXISTS `$db`;");

        $con = new mysqli($address, $name, $pass, $db);
        if ($con->connect_errno) {
            echo "Failed to connect to MySQL (2): (" . $con->connect_errno . ") " . $con->connect_error;
            die();
        }
        
        $con->set_charset('utf8');                
        
        query($con, "CREATE TABLE `projects` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(45) NOT NULL,
                        `created` date DEFAULT NULL,
                        `endby` date DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        
        query($con, "CREATE TABLE `milestones` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `project_id` int(11) NOT NULL,
                        `name` varchar(45) NOT NULL,
                        `desc` varchar(300) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        
        query($con, "CREATE TABLE `users` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(45) NOT NULL,
                        `mail` varchar(45) NOT NULL,
                        `password` varchar(90) NOT NULL,
                        `enabled` tinyint(1) DEFAULT '0',
                        `salt` varchar(45) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        
        query($con, "CREATE TABLE `tasks` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `milestone_id` int(11) NOT NULL,
                        `name` varchar(45) NOT NULL DEFAULT 'Task',
                        `previous_task` int(11) DEFAULT '-1',
                        `finished` tinyint(1) DEFAULT '0',
                        `desc` varchar(120) NOT NULL DEFAULT 'No Description',
                        `enddate` datetime DEFAULT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        
        query($con, "CREATE TABLE `projects_users` (
                        `project_id` int(11) NOT NULL,
                        `user_id` int(11) NOT NULL,
                        `permission` varchar(45) NOT NULL DEFAULT 'user'
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                      ");
        
        query($con, "CREATE TABLE `calendarlist` (
                        `id` mediumint(8) unsigned NOT NULL auto_increment,
                        `meetingdate` date default NULL,
                        `title` varchar(255) default NULL,
                        `timestart` time default NULL,
                        `timeend` time default NULL,
                        `location` varchar(255) default NULL,
                        `description` varchar(255) default NULL,
                        `project_id` INT default NULL,
                        PRIMARY KEY (`id`)
                      ) AUTO_INCREMENT = 1;");

        $installed = true;
    }

    if (filter_input(INPUT_POST, "install") !== null) {
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
            if ($installed == true) {
                echo "<p>Erfolgreich installiert.</p>";
            } else {
                if ($error != null) {
                    echo "<p><b>Anmerkung: $error</b></p><br><br>";
                }
                echo "<form method='post'>";                
                
                echo "Host Adress:<input type='text' name='address'><br><br>";                                
                echo "Database:<input type='text' name='db'><br>";
                                
                echo "Host Username:<input type='text' name='name'><br>";
                echo "Host Password:<input type='password' name='password'><br><br>";
                
                echo "<button type='submit' name='install' value='all'>Installieren</button>          
                </form>";
            }
        ?>
        

    </body>
</html>
