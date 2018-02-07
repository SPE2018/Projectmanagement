<?php
include_once 'user_manager.php';
include_once 'sql_util.php';

if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class Login {
    
    // Password security: https://wblinks.com/notes/storing-passwords-the-wrong-better-and-even-better-way/
    
    const ITERATIONS = 100000; // Strings will be hashed 100 000 times, it takes around 1 second to generate the final hash with this many iterations
    const PEPPER = "MeinSuperTollerPfeffer";
    
    const WRONG_INPUT = "Wrong credentials entered.";
       
    public static function myHash($string, $salt) {
        for ($i=0; $i<Login::ITERATIONS; $i++) {
            $string = hash("sha256", $salt . Login::PEPPER . $string);        
        }
        return $string;
    }
    
    
    public static function checkLogin() {        
        if((filter_input(INPUT_POST, 'btn_login')) == NULL) {
            return "Not Pressed";
        }     
        if((filter_input(INPUT_POST, 'login_username')) != NULL) {
            $name = filter_input(INPUT_POST, 'login_username');
        }        
        $user = UserManager::getUser($name);
        if ($user == null) {
            return BUtil::danger(Login::WRONG_INPUT);
        }      
        if((filter_input(INPUT_POST, 'login_password')) != NULL) {
            $pass = filter_input(INPUT_POST, 'login_password');
            $salt = $user->salt;
            $pass = Login::myHash($pass, $salt);
        }
           
        if($pass != $user->password){
            return BUtil::danger(Login::WRONG_INPUT);
        } else {
            if (!($user->enabled)) {
                return BUtil::danger("Your account has to be enabled by an admin before you are able to login.");
                //return '<p style="Color: orange; Font-Size:20px"><b>Your account has to be enabled by an admin before you are able to login</b></p>';
            }
            $_SESSION['user'] = $user->name;
            $_SESSION['userid'] = $user->userid;
            if($user->admin == true) {
                Login::admincheck($user->name, $pass);
            } elseif($user->enabled == true) {
                header("Location: index.php");  
            } else {
                return BUtil::danger(Login::WRONG_INPUT);
            }
        }
        return "Hello";
    }

    public static function admincheck($name, $pass) {
        $admin = UserManager::getUser($name);
        if($pass == $admin->password) {
            $_SESSION['user'] = $admin->name;
            header("Location: admin.php");
            return true;
        } else {
            echo "Wrong password for " . $name;
        }
        return false;
    }
    
    public static function createLoginForm() {
        $out = Login::checkLogin();
        $name = "";
        if((filter_input(INPUT_POST, 'login_username')) != NULL) {
            $name = filter_input(INPUT_POST, 'login_username');
        }
        
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
    
    const SALT_LENGTH = 15;
    
    public static function generateSalt() {
        $randString="";
        $charUniverse="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $len = strlen($charUniverse) - 1;
        for ($i=0; $i<Registration::SALT_LENGTH; $i++) {
            $randChar = $charUniverse[rand(0,$len)];
            $randString = $randString.$randChar;
        }
        return $randString;
    }
    
    public static function checkRegistration() {
        $name = filter_input(INPUT_POST, 'register_username');
        $mail = filter_input(INPUT_POST, 'register_email');
        $pass = filter_input(INPUT_POST, 'register_password');
        $rptpw = filter_input(INPUT_POST, 'register_rptPassword');
        $first = filter_input(INPUT_POST, 'first') == "true";
        
        if((filter_input(INPUT_POST, 'btn_register')) != NULL) {
            if($pass != $rptpw) {
                return '<p style="Color: red; Font-Size:24">passwords do not match</p>';            
            } else {
                $salt = Registration::generateSalt();
                $pass = Login::myHash($pass, $salt);
                $admin = $first ? 1 : 0;
                if (UserManager::addUser($name, $mail, $pass, $salt, $admin)) {
                    if ($first) {
                        header("Location: index.php");
                    } else {
                        header("Location: index.php?newuser=true");
                    }
                } else {
                    return '<p style="Color: red; Font-Size:20px">User with this name already exists</p>';         
                }
            }
        }
        return "";
    }
    
    public static function createRegisterForm($first = false) {
        $out = "";
        if((filter_input(INPUT_POST, 'btn_register')) != NULL) {
            $first = filter_input(INPUT_POST, 'first') == "true";
            $out = $out . Registration::checkRegistration();
            if ($first && strlen($out) > 0) {
                // Only head back to install when there is an error in checkRegistration and $first == true
                header("Location: install.php?createadmin=true&alert=$out");
            }
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
        
        if ($first) {
            $out = $out .  '<input type="hidden" '
                . 'name="first" value="true"><br>';
        }
        
        $out = $out .  '<button type="submit" name="btn_register" value="yes">sign up</button></form>';
        $out = $out .  "</div>";
        return $out;
    }
}