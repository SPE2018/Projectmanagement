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

        $user = new User($result['id'], $result['name'], $result['mail'], $result['password'], $result['enabled']);
        return $user;
    }
    
    public static function getDisabledUsers() {
        $sql = "SELECT * FROM users WHERE enabled=0;";
        $result = SQL::query($sql)->fetch_all();
        
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
        $sql = "INSERT INTO users (name, mail, password, enabled) VALUES('$username', '$mail', '$password', 0);";
        SQL::query($sql); // TODO: Error handling
    }

    public static function enableUser($id) {
        $sql = "UPDATE users SET 'enabled'=1 WHERE id=$id;";
        SQL::query($sql); // TODO: Error handling
    }
    
}