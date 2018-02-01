<?php
include_once "sql_util.php";

class UserManager {
    
    /**
     * @param type $username
     * @return Returns User if the user exists or null if the user does not exist
     */
    public static function getUser($username) {
        $sql = "SELECT * FROM users WHERE name='$username';";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling
        if ($result == null) {
            return null;
        }

        $user = new User($result['id'], $result['name'], $result['mail'], $result['password'], $result['admin'], $result['enabled']);
        return $user;
    }
    
    public static function getUserByID($id) {
        $sql = "SELECT * FROM users WHERE id=$id;";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling
        if ($result == null) {
            return null;
        }

        $user = new User($result['id'], $result['name'], $result['mail'], $result['password'], $result['enabled']);
        return $user;
    }
    
    public static function getEnabledUsers() {
        $sql = "SELECT * FROM users WHERE enabled=1;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);        
        $toReturn = array();
        
        foreach ($result as $r) {
            $user = new User($r['id'], $r['name'], $r['mail'], $r['password'], $r['admin'], $r['enabled']);
            array_push($toReturn, $user);
        }
        return $toReturn;
    }
    
    public static function getAdmins() {
        $sql = "SELECT * FROM users WHERE admin=1;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);        
        $toReturn = array();
        
        foreach ($result as $r) {
            $user = new User($r['id'], $r['name'], $r['mail'], $r['password'], $r['admin'], $r['enabled']);
            array_push($toReturn, $user);
        }
        return $toReturn;
    }
    
    public static function getDisabledUsers() {
        $sql = "SELECT * FROM users WHERE enabled=0;";
        $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);        
        $toReturn = array();
        
        foreach ($result as $r) {
            $user = new User($r['id'], $r['name'], $r['mail'], $r['password'], $r['enabled']);
            array_push($toReturn, $user);
        }
        return $toReturn;
    }

    /**
     * Adds a user to the database.
     * This user needs to be activated/enabled by an admin
     */
    public static function addUser($username, $mail, $password) {
        if(UserManager::getUser($username) == NULL) {
            $sql = "INSERT INTO users (name, mail, password, enabled) VALUES('$username', '$mail', '$password', 0);";
            SQL::query($sql); // TODO: Error handling
        } else {
            echo '<p style="Color: red; Font-Size:24">user with this name does already exists</p>';            
        }
    }
    
    public static function promoteUser($username) {
        if(UserManager::getUser($username) != NULL) {
            $sql = "UPDATE users SET `admin` = 1 WHERE `name`='$username';";
            SQL::query($sql); // TODO: Error handling
        }
    }
    
    public static function demoteUser($username) {
        if(UserManager::getUser($username) != NULL) {
            $sql = "UPDATE users SET `admin` = 0 WHERE `name`='$username';";
            SQL::query($sql); // TODO: Error handling
        }
    }
    
    public static function deleteUser($username) {
        if(UserManager::getUser($username) != NULL) {
            $sql = "DELETE from users WHERE `name`='$username';";
            SQL::query($sql); // TODO: Error handling
        }
    }

    public static function enableUser($id) {
        if(UserManager::getUserByID($id) != NULL) {
            $sql = "UPDATE users SET `enabled`=1 WHERE `id`=$id;";
            SQL::query($sql); // TODO: Error handling
        } else {
            echo '<p style="Color: red; Font-Size:24">user with this name does already exists</p>';            
        }
    }
    
}