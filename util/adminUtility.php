<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

include_once 'sql_util.php';
include_once 'user_manager.php';
//include_once 'functions.php';

class Admin{
    public static function display_DisabledUserList() {
        if(filter_input(INPUT_GET, 'enable') != NULL) {
            UserManager::enableUser(UserManager::getUser(filter_input(INPUT_GET, 'enable'))->userid);
        }
        echo '<table class="table">users to enable:' .
        '<tr><th colspan=5>username</th><th></th><th>enable</th>';
        if(UserManager::getEnabledUsers() != NULL) {
            $users = UserManager::getDisabledUsers();
            foreach($users as $u) {
                if($u->name == 'admin') {
                    continue;
                }
                echo '<tr><td colspan=5>' . $u->name . '</td><td></td><td><a href="admin.php?enable=' . $u->name . '">enable</a></td></tr>"';
            }
        }
        echo '</table>';
    }

    public static function display_Admins() {
        
        echo '<table class="table">administrative users:' .
        '<tr><th colspan=5>username</th><th>demote</th>';
        if(UserManager::getAdmins() != NULL) {
            $users = UserManager::getAdmins();
            foreach($users as $u) {
                echo '<tr><td colspan=5>' . $u->name . '</td>'
                . (($u->name == 'admin') ? '<td>cannot be demoted!</td>' : ('<td><a href="admin.php?demote=' . $u->name . '">demote</a></td>'));
            }
        }
        echo '</table>';
    }
    
    public static function display_EnabledUserList() {

        echo '<table class="table">already enabled users:' .
        '<tr><th colspan=5>username</th><th>promote</th><th>delete</th><th>manage</th>';
        if(UserManager::getEnabledUsers() != NULL) {
            $users = UserManager::getEnabledUsers();
            foreach($users as $u) {
                if($u->admin == false) {
                    echo '<tr><td colspan=5>' . $u->name
                            . '</td><td><a href="admin.php?promote=' . $u->name . '">promote to admin</a></td><td>'
                            . (($u->name == 'admin') ? 'cannot be deleted!</td>' : '<a href="admin.php?delete=' . $u->name . '">delete</a></td>') //if isset($u->permissions['admin'])
                            . '<td><a href="admin.php?manageprojects=' . $u->name . '">manage projects</a></td></tr>';
                }
            }
        }
        echo '</table>';
    }
    
    public static function updateLists() {
        if(filter_input(INPUT_GET, 'delete') != NULL) {
            UserManager::deleteUser(filter_input(INPUT_GET, 'delete'));
        }
        if(filter_input(INPUT_GET, 'promote') != NULL) {
            UserManager::promoteUser(filter_input(INPUT_GET, 'promote'));
        }
        if(filter_input(INPUT_GET, 'demote') != NULL) {
            UserManager::demoteUser(filter_input(INPUT_GET, 'demote'));
        }
    }
}