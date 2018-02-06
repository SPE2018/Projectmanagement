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
        echo '<table class="table admin">Users to enable:' .
        '<tr><th>Username</th><th></th><th>Enable</th>';
        if(UserManager::getEnabledUsers() != NULL) {
            $users = UserManager::getDisabledUsers();
            foreach($users as $u) {
                if($u->name == 'admin') {
                    continue;
                }
                echo '<tr><td>' . $u->name . '</td><td></td><td><a class="btn btn-success" href="admin.php?enable=' . $u->name . '">Enable</a></td></tr>';
            }
        }
        echo '</table>';
    }

    public static function display_Admins() {
        echo '<table class="table admin">Administrative Users:' .
        '<tr><th colspan=5>Username</th><th>Demote</th>';
        if(UserManager::getAdmins() != NULL) {
            $users = UserManager::getAdmins();
            $admins = count($users);
            foreach($users as $u) {
                if($_SESSION['user'] == $u->name) {
                    echo '<tr><td colspan=5>' . $u->name . '</td>'
                    . (($admins <= 1) ? '<td>Cannot be demoted!</td>' : ('<td><a class="btn btn-warning" href="admin.php?demote=' . $u->name . '">Hand back administrator-permissions</a></td>'));
                } else {
                    /*echo '<tr><td colspan=5>' . $u->name . '</td>'
                    . (($_SESSION['user'] == 'admin') ? ('<td><a href="admin.php?demote=' . $u->name . '">Demote</a></td>') : ('<td>Only "admin" can demote admins</td>'));*/
                    echo '<tr><td colspan=5>' . $u->name . '</td>'
                            . '<td><a class="btn btn-danger" href="admin.php?demote=' . $u->name . '">Demote</a></td>';
                }
                echo "</tr>";
            }
        }
        echo '</table>';
    }
    
    public static function display_EnabledUserList() {
        echo '<table class="table admin">Enabled Users:' .
        '<tr><th colspan=5>Username</th><th>Promote</th><th>Delete</th>';
        if(UserManager::getEnabledUsers() != NULL) {
            $users = UserManager::getEnabledUsers();
            foreach($users as $u) {
                if($u->admin == false) {
                    echo '<tr><td colspan=5>' . $u->name
                            . '</td><td><a class="btn btn-success" href="admin.php?promote=' . $u->name . '">Promote to admin</a></td><td>'
                            . (($u->name == 'admin') ? 'Cannot be deleted!</td>' : '<a class="btn btn-danger" href="admin.php?delete=' . $u->name . '">Delete</a></td>'); //if isset($u->permissions['admin'])
                            //. '<td><a href="admin.php?manageprojects=' . $u->name . '">manage projects</a></td></tr>';
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