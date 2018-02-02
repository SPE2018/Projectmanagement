<?php
include_once 'user_manager.php';
include_once 'sql_util.php';

if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class Login {
    public static function checkLogin() {
        if((filter_input(INPUT_POST, 'btn_login')) == NULL) {
            return "";
        }
        if((filter_input(INPUT_POST, 'login_username')) != NULL) {
            $name = filter_input(INPUT_POST, 'login_username');
        }
        if((filter_input(INPUT_POST, 'login_password')) != NULL) {
            $pass = filter_input(INPUT_POST, 'login_password');
        }
        $user = UserManager::getUser($name);
        if ($user == null) {
            return '<p style="Color: red; Font-Size:20px"><b>Wrong credentials entered</b></p>';      
        }        
        if($pass != $user->password){
            return '<p style="Color: red; Font-Size:20px"><b>Wrong credentials entered</b></p>';             
        } else {
            if (!($user->enabled)) {
                return '<p style="Color: orange; Font-Size:20px"><b>Your account has to be enabled by an admin before you are able to login</b></p>';     
            }
            $_SESSION['user'] = $user->name;
            $_SESSION['userid'] = $user->userid;
            if($user->admin == true) {
                Login::admincheck($user->name, $pass);
            } elseif($user->enabled == true) {
                header("Location: index.php");  
            } else {
                return '<p style="Color: red; Font-Size:20px"><b>Wrong credentials entered</b></p>';   
            }
        }
        return "";
    }

    public static function admincheck($name, $pass) {
        $admin = UserManager::getUser($name);
        if($pass == $admin->password) {
            $_SESSION['user'] = $admin->name;
            header("Location: Admin/admin.php");
            return true;
        } else {
            echo "Wrong password for " . $name;
        }
        return false;
    }
    
    public static function createLoginForm() {
        $out = "";
        if((filter_input(INPUT_POST, 'btn_login')) != NULL) {
            $out = $out . Login::checkLogin();
        }
        $name = "";
        if((filter_input(INPUT_POST, 'login_username')) != NULL) {
            $name = filter_input(INPUT_POST, 'login_username');
        }
        /*$out = $out .  '<form action="login.php" method="post">username or e-mail-address:<br><input type="text" '
        . 'name="login_username" placeholder="username/e-mail" name="login_username" required><br><br>';

        $out = $out .  'password:<br><input type="password" '
        . 'name="login_password" placeholder="password" required><br><br>';

        $out = $out .  '<button type="submit" name="btn_login" value="yes">sign in</button></form>';*/
        
        $out = $out . '<form action="login.php" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Username</label>
                                <input class="form-control" type="text" id="name" name="login_username" value="' . $name . '" required>
                            </div>
                            <div class="form-group">
                                <label for="pass" class="col-form-label">Password</label>
                                <input class="form-control" type="password" id="pass" name="login_password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="save" name="btn_login" value="login">Login</button>
                        </div>
                    </form>
                </div>';
        return $out;
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['user']) && isset($_SESSION['userid']);
    }
    
    public static function getLoggedInName() {
        return $_SESSION['user'];
    }
    
    public static function getLoggedInId() {
        return $_SESSION['userid'];
    }
    
    public static function logout() {
        unset($_SESSION['user']);
        unset($_SESSION['userid']);
    }
}

class Registration {
    public static function checkRegistration() {
        $name = filter_input(INPUT_POST, 'register_username');
        $mail = filter_input(INPUT_POST, 'register_email');
        $pass = filter_input(INPUT_POST, 'register_password');
        $rptpw = filter_input(INPUT_POST, 'register_rptPassword');
        if((filter_input(INPUT_POST, 'btn_register')) != NULL) {
            if($pass != $rptpw) {
                return '<p style="Color: red; Font-Size:24">passwords do not match</p>';            
            } else {
                if (UserManager::addUser($name, $mail, $pass)) {
                    header("Location: index.php?newuser=true");
                } else {
                    return '<p style="Color: red; Font-Size:20px">User with this name already exists</p>';         
                }
            }
        }
        return "";
    }
    
    public static function createRegisterForm() {
        $out = "";
        if((filter_input(INPUT_POST, 'btn_register')) != NULL) {
            $out = $out . Registration::checkRegistration();
        }
        $out = $out .  "<div class='form-group'>";
        $out = $out .  '<label>Username:</label>';
        $out = $out .  '<form action="register.php" method="post"><input type="text" '
        . 'name="register_username" placeholder="username" class="form-control" required><br>';

        $out = $out .  '<label>E-Mail:</label>';
        $out = $out .  '<input type="email" placeholder="john.doe@example.gg" '
        . 'name="register_email" class="form-control" required><br>';

        $out = $out .  '<label>Password:</label>';
        $out = $out .  '<input type="password" placeholder="password" '
        . 'name="register_password" class="form-control" required><br>';

        $out = $out .  '<label>Repeat Password:</label>';
        $out = $out .  '<input type="password" placeholder="confirm password" '
        . 'name="register_rptPassword" class="form-control" required><br>';
        
        $out = $out .  '<button type="submit" name="btn_register" value="yes">sign up</button></form>';
        $out = $out .  "</div>";
        return $out;
    }
}