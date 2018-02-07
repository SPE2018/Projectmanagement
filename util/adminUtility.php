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
        echo '<table class="table admin" style="font-size: 22pt; color: #00bc8c">Users to enable:' .
        '<tr><th style="font-size: 18pt; color: #00bc8c">Username</th><th></th><th style="font-size: 18pt; color: #00bc8c; align: "right";>Enable</th>';
        if(UserManager::getEnabledUsers() != NULL) {
            $users = UserManager::getDisabledUsers();
            foreach($users as $u) {
                if($u->name == 'admin') {
                    continue;
                }
                echo '<tr><td style="font-size: 12pt; color: white">' . $u->name . '</td><td style="font-size: 12pt; color: white; align: "right";"><a class="btn btn-success" href="admin.php?enable=' . $u->name . '">Enable</a></td></tr>';
            }
        }
        echo '</table>';
    }

    public static function display_Admins() {
        echo '<table class="table admin" style="border: 1;font-size: 22pt; color: #00bc8c">Administrative Users:' .
        '<tr><th style="font-size: 18pt; color: #00bc8c">Username</th><th style="font-size: 18pt; color: #00bc8c; align: "right";">Demote</th>';
        if(UserManager::getAdmins() != NULL) {
            $users = UserManager::getAdmins();
            $admins = count($users);
            foreach($users as $u) {
                if($_SESSION['user'] == $u->name) {
                    echo '<tr><td  style="font-size: 12pt; color: white">' . $u->name . '</td>'
                    . (($admins <= 1) ? '<td style="font-size: 12pt; color: white">Cannot be demoted!</td>' : ('<td><a class="btn btn-warning" href="admin.php?demote=' . $u->name . '">Hand back administrator-permissions</a></td>'));
                } else {
                    echo '<tr><td style="font-size: 12pt; color: white">' . $u->name . '</td>'
                            . '<td style="font-size: 12pt; color: white; align: "right";"><a class="btn btn-danger" href="admin.php?demote=' . $u->name . '">Demote</a></td>';
                }
                echo "</tr>";
            }
        }
        echo '</table>';
    }
    
    public static function display_EnabledUserList() {
        echo '<table class="table admin" style="font-size: 22pt; color: #00bc8c">Enabled Users:' .
        '<tr><th  style="font-size: 18pt; color: #00bc8c">Username</th><th style="font-size: 18pt; color: #00bc8c; align: "center";">Promote</th><th  style="font-size: 18pt; color: #00bc8c"; align: "right";>Delete</th>';
        if(UserManager::getEnabledUsers() != NULL) {
            $users = UserManager::getEnabledUsers();
            foreach($users as $u) {
                if($u->admin == false) {
                    echo '<tr><td style="font-size: 12pt; color: white">' . $u->name
                            . '</td><td style="font-size: 12pt; color: white"; align: "center";><a class="btn btn-success" href="admin.php?promote=' . $u->name . '">Promote to admin</a></td><td style="align: "right";">'
                            . (($u->name == 'admin') ? 'Cannot be deleted!</td>' : '<a class="btn btn-danger" href="admin.php?delete=' . $u->name . '">Delete</a></td>'); //if isset($u->permissions['admin'])
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