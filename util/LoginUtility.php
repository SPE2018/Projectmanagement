<?php
include_once 'user_manager.php';
include_once 'sql_util.php';

if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class Login {
    public static function checkLogin () {
        if((filter_input(INPUT_POST, 'login_username')) != NULL) {
            $name = filter_input(INPUT_POST, 'login_username');
        }
        if((filter_input(INPUT_POST, 'login_password')) != NULL) {
            $pass = filter_input(INPUT_POST, 'login_password');
        }
        $user = UserManager::getUser($name);
        if($pass != $user->password){
            echo '<p style="Color: red; Font-Size:24">wrong password for ' . $name . '</p>';            
        } else {
            $_SESSION['user'] = $user->name;
            if($user->admin == true) {
                Login::admincheck($user->name, $pass);
            } elseif($user->enabled == true) {
                header("Location: ../index.php");  
            } else {
                echo '<p style="Color: orange; Font-Size:24">the name: ' . $name . ' does not match any existing account</p>';            
            }
        }
    }

    public static function admincheck($name, $pass) {
        $admin = UserManager::getUser($name);
        if($pass == $admin->password) {
            $_SESSION['user'] = $admin->name;
            header("Location: ../Admin/admin.php");
            return true;
        } else {
            echo "Wrong password for " . $name;
        }
        return false;
    }
    
    public static function createLoginForm() {
        if((filter_input(INPUT_POST, 'btn_login')) != NULL) {
            Login::checkLogin();
        }
        echo '<form action="login.php" method="post">username or e-mail-address:<br><input type="text" '
        . 'name="login_username" placeholder="username/e-mail" name="login_username" required><br><br>';

        echo 'password:<br><input type="password" '
        . 'name="login_password" placeholder="password" required><br><br>';

        echo '<button type="submit" name="btn_login" value="yes">sign in</button></form>';
    }
    
    public static function logout() {
        unset($_SESSION['user']);
    }
}

class Registration {
    public static function checkRegistration () {
        $name = filter_input(INPUT_POST, 'register_username');
        $mail = filter_input(INPUT_POST, 'register_email');
        $pass = filter_input(INPUT_POST, 'register_password');
        $rptpw = filter_input(INPUT_POST, 'register_rptPassword');
        if((filter_input(INPUT_POST, 'btn_register')) != NULL) {
            if(UserManager::getUser($name) != null) {
                echo '<p style="Color: red; Font-Size:24">username already in use!</p>';            
            } elseif(UserManager::getUserByMail($mail) != null) {
                echo '<p style="Color: red; Font-Size:24">this e-mail address is already assigne for an account!</p>';            
            } elseif($pass != $rptpw) {
                echo '<p style="Color: red; Font-Size:24">passwords do not match</p>';            
            } else {
                UserManager::addUser($name, $mail, $pass);
                header("Login.php");
            }
        }
    }
    
    public static function createRegisterForm() {
        if((filter_input(INPUT_POST, 'btn_register')) != NULL) {
            Registration::checkRegistration();
        }
        echo '<form action="register.php" method="post">username:<br><input type="text" '
        . 'name="register_username" placeholder="username" required><br><br>';

        echo 'e-mail:<br><input type="email" placeholder="john.doe@example.gg" '
        . 'name="register_email" required><br><br>';

        echo 'password:<br><input type="password" placeholder="password" '
        . 'name="register_password" required><br><br>';

        echo 'repeat password:<br><input type="password" placeholder="confirm password" '
        . 'name="register_rptPassword" required><br><br>';
        
        echo '<button type="submit" name="btn_register" value="yes">sign up</button></form>';
    }
}