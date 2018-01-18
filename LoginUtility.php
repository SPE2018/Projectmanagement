<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class Login {
    public static function checkLogin () {
        $name = filter_input(INPUT_POST, 'login_username');
        $pass = filter_input(INPUT_POST, 'login_password');
        $user = SQL::getUser($name);
        if($name == NULL || $pass == NULL || $name == "username/e-mail") {
            echo '<p style="Color: 0xFF0000; Font-Size: 24">fill ALL fields!</p>';
            header("Location: login.php");
        }
        if($user->password == $pass && $user->enable == TRUE) {
            $_SESSION['user'] = $user->name;
            return;
        }
        header("Location: login.php");
    }

    public static function createLoginForm() {
        $name = filter_input(INPUT_POST, 'login_username');
        $pass = filter_input(INPUT_POST, 'login_password');
        if($pass != NULL && $name != NULL) {
            Login::checkValidation();
        } else {
            echo '<form action="login.php">username or e-mail-address:<br><input type="text"'
            . 'name="login_username" value="username/e-mail" name="login_username"><br><br>';

            echo 'password:<br><input type="password'
            . 'name="login_password"><br><br>';

            echo '<button type="submit" name="btn_login">sign in</button></form>';
        }
    }
}

class Registration {
    public static function checkRegistration () {
        $name = filter_input(INPUT_POST, 'register_username');
        $mail = filter_input(INPUT_POST, 'register_email');
        $pass = filter_input(INPUT_POST, 'register_password');
        $rptpw = filter_input(INPUT_POST, 'register_rptpassword');
        if((filter_input(INPUT_POST, 'btn_register')) != NULL) {
            if($name == NULL || $pass == NULL || $mail == NULL || $rptpw == NULL) {
                echo '<p style="Color:0xFF0000; Font-Size:24">fill ALL fields!</p>';            
                var_dump($rptpw);
                var_dump($pass);
            } elseif($pass != $rptpw) {
                echo '<p style="Color:0xFF0000; Font-Size: 24">passwords must equal</p>';
            } else {
                var_dump($rptpw);
                var_dump($pass);
                header("Location: register.php");
                sql_addUser($name, $mail, $pass);
            }
        }
        header("Location: index.php");
    }
    
    public static function createRegisterForm() {
        if((filter_input(INPUT_POST, 'btn_register')) == NULL) {
            Registration::checkRegistration();
        } else {
            echo "NEIN<br>";
        }
        echo '<form action="register.php" method="post">username:<br><input type="text"'
        . 'name="register_username" value="username"><br><br>';

        echo 'e-mail:<br><input type="email" name="email" value="john.doe@example.com"'
        . 'name="register_email"><br><br>';

        echo 'password:<br><input type="password"'
        . 'name="register_password"><br><br>';

        echo 'repeat password:<br><input type="password"'
        . 'name="register_rptPassword"><br><br>';

        echo '<button type="submit" name="btn_register">sign up</button></form>';
    }
}