<?php
include_once "sql_util.php";

class UserManager {
    
    /**
     * @param type $username
     * @return Returns User if the user exists or null if the user does not exist
     */
    public static function getUser($username) {
        $sql = "SELECT * FROM users WHERE name='$username';";
        $result = SQL::query($sql)->fetch_assoc(); 
        if ($result == null) {
            return null;
        }

        $user = new User($result['id'], $result['name'], $result['mail'], $result['password'], $result['salt'], $result['admin'], $result['enabled']);
        return $user;
    }
    
    public static function userExists($username) {
        $sql = "SELECT count(*) as amount FROM users WHERE name='$username';";
        $result = SQL::query($sql)->fetch_assoc(); 
        if ($result == null) {
            return null;
        }
        return intval($result['amount']) > 0;
    }
    
    public static function getUserByID($id) {
        $sql = "SELECT * FROM users WHERE id=$id;";
        $result = SQL::query($sql)->fetch_assoc(); 
        if ($result == null) {
            return null;
        }

        $user = new User($result['id'], $result['name'], $result['mail'], $result['password'], $result['salt'], $result['admin'], $result['enabled']);
        return $user;
    }
    
    public static function getUsernameFromId($id) {
         $sql = "SELECT `id`, `name` FROM users WHERE id='$id';";
        $result = SQL::query($sql)->fetch_assoc(); 
        if ($result == null) {
            return null;
        }
        return $result['name'];
    }
    public static function getEnabledUsers() {
        $sql = "SELECT * FROM users WHERE enabled=1;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);        
        $toReturn = array();
        
        foreach ($result as $r) {
            $user = new User($r['id'], $r['name'], $r['mail'], $r['password'], $r['salt'], $r['admin'], $r['enabled']);
            array_push($toReturn, $user);
        }
        return $toReturn;
    }
    
    public static function getAdmins() {
        $sql = "SELECT * FROM users WHERE admin=1;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);        
        $toReturn = array();
        
        foreach ($result as $r) {
            $user = new User($r['id'], $r['name'], $r['mail'], $r['password'], $r['salt'], $r['admin'], $r['enabled']);
            array_push($toReturn, $user);
        }
        return $toReturn;
    }
    
    public static function getDisabledUsers() {
        $sql = "SELECT * FROM users WHERE enabled=0;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
         
        $toReturn = array();
        
        foreach ($result as $r) {
            $user = new User($r['id'], $r['name'], $r['mail'], $r['password'], $r['salt'], $r['admin'], $r['enabled']);
            array_push($toReturn, $user);
        }
        return $toReturn;
    }

    public static function getAllUsers() {
        $sql = "SELECT * FROM users WHERE enabled=1;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);

        $toReturn = array();

        foreach ($result as $r) {
            $user = new User($r['id'], $r['name'], $r['mail'], $r['password'], $r['salt'], $r['admin'], $r['enabled']);
            array_push($toReturn, $user);
        }
        return $toReturn;
    }

    public static function countUsers() {
        $sql = "SELECT count(*) as amount FROM users";
        $result = SQL::query($sql)->fetch_assoc();
        if ($result == null) {
            return 0;
        }
        return intval($result['amount']);
    }
    
    public static function countAdmins() {
        $sql = "SELECT count(*) as amount FROM users WHERE admin=1";
        $result = SQL::query($sql)->fetch_assoc();
        if ($result == null) {
            return 0;
        }
        return intval($result['amount']);
    }
    
    /**
     * Adds a user to the database.
     * This user needs to be activated/enabled by an admin
     */
    public static function addUser($username, $mail, $password, $salt, $admin = 0) {        
        if((UserManager::userExists($username)) == false) {
            $enabled = $admin == 1 ? 1 : 0;
            $sql = "INSERT INTO users (name, mail, password, enabled, salt, admin) VALUES('$username', '$mail', '$password', $enabled, '$salt', $admin);";
            SQL::query($sql); 
            return true;
        }           
        return false;
    }
    
    public static function promoteUser($username) {
        if(UserManager::getUser($username) != NULL) {
            $sql = "UPDATE users SET `admin` = 1 WHERE `name`='$username';";
            SQL::query($sql); 
        }
    }
    
    public static function demoteUser($username) {
        if(UserManager::getUser($username) != NULL) {
            $sql = "UPDATE users SET `admin` = 0 WHERE `name`='$username';";
            SQL::query($sql); 
        }
    }
    
    public static function deleteUser($username) {
        if(UserManager::getUser($username) != NULL) {
            $sql = "DELETE from users WHERE `name`='$username';";
            SQL::query($sql); 
        }
    }

    public static function enableUser($id) {
        if(UserManager::getUserByID($id) != NULL) {
            $sql = "UPDATE users SET `enabled`=1 WHERE `id`=$id;";
            SQL::query($sql); 
        } else {
            echo '<p style="Color: red; Font-Size:24">user with this name does already exists</p>';            
        }
    }
    
}