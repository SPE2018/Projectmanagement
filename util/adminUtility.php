<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

//include_once 'LoginUtility.php';
include_once 'sql_util.php';
include_once 'user_manager.php';
//include_once 'functions.php';

class Admin{
    public static function display_usersToEnable() {
        echo '<table class="table">users with request to sign up:' .
        '<tr><th>username</th><th>enable?</th>';
        
        if(UserManager::getUser(filter_input(INPUT_GET, 'toenable')) == NULL) {
            return;
        }
        
        UserManager::enableUser(UserManager::getUser(filter_input(INPUT_GET, 'toenable')));
        if(UserManager::getDisabledUsers() != NULL) {
            $users = UserManager::getDisabledUsers();
            foreach($users as $u) {
                echo '<tr><td>' . $u->name . '</td><td><a href="admin.php?toenable=' . $u->name . '">enable</a></td></tr>';
            }
        }
        echo '</table>';
    }
    
    public static function display_EnabledUserList() {
        /*if(filter_input(INPUT_GET, 'toenable') != NULL) {
            UserManager::enableUser(UserManager::getUser(filter_input(INPUT_GET, 'toenable'))->userid);
        }*/
        if(filter_input(INPUT_GET, 'delete') != NULL) {
            UserManager::deleteUser(filter_input(INPUT_GET, 'delete'));
        }
        /*if(filter_input(INPUT_GET, 'toenable') != NULL) {
            UserManager::enableUser(UserManager::getUser(filter_input(INPUT_GET, 'toenable'))->userid);
        }*/
        echo '<table class="table">already enabled users:' .
        '<tr><th colspan=5>username</th><th>promote</th><th>delete</th><th>manage</th>';
        if(UserManager::getEnabledUsers() != NULL) {
            $users = UserManager::getEnabledUsers();
            foreach($users as $u) {
                echo '<tr><td colspan=5>' . $u->name
                        . '</td><td><a href="admin.php?promote=' . $u->name . '">promote to admin</a></td><td>'
                        . (($u->name == 'admin') ? 'cannot be deleted!</td>' : '<a href="admin.php?delete=' . $u->name . '">delete</a></td>') //if isset($u->permissions['admin'])
                        . '<td><a href="admin.php?manageprojects=' . $u->name . '">manage projects</a></td></tr>';
            }
        }
        echo '</table>';
    }
}