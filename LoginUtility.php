<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

function login_checkValidation () {
    $name = filter_input(INPUT_POST, 'login_username');
    $pass = filter_input(INPUT_POST, 'login_password');
    $user = sql_getUser($name);
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

function login_checkRegistration () {
    $name = filter_input(INPUT_POST, 'register_username');
    $mail = filter_input(INPUT_POST, 'register_email');
    $pass = filter_input(INPUT_POST, 'register_password');
    $rptpw = filter_input(INPUT_POST, 'register_rptpassword');
    if(!($name != NULL && $mail != NULL && $pass != NULL && $rptpw != NULL && $pass == $rptpw)) {
        echo '<p style="Color: 0xFF0000; Font-Size: 24">fill ALL fields!</p>';
        header("Location: register.php");
    }
    if($pass != $rptpw) {
        echo '<p style="Color: 0xFF0000; Font-Size: 24">passwords must equal</p>';
    }
    sql_addUser($name, $mail, $pass);
    header("Location: index.php");
}

function login_createLoginForm() {
    $name = filter_input(INPUT_POST, 'login_username');
    $pass = filter_input(INPUT_POST, 'login_password');
    if($pass != NULL && $name != NULL) {
        login_checkValidation();
    } else {
        echo '<form action="login.php">username or e-mail-address:<br><input type="text"'
        . 'name="login_username" value="username/e-mail" method="post" name="login_username"><br><br>';

        echo 'password:<br><input type="password'
        . 'method="post" name="login_password"><br><br>';

        echo '<button type="submit" name="btn_login">sign in</button></form>';
    }
}

function login_createRegisterForm() {
    echo '<form action="register.php">username:<br><input type="text"'
    . 'name="register_username" value="username" method="post"><br><br>';
    
    echo 'e-mail:<br><input type="email" name="email" value="john.doe@example.com"'
    . 'method="post" name="register_email"><br><br>';
    
    echo 'password:<br><input type="password"'
    . 'method="post" name="register_password"><br><br>';
    
    echo 'repeat password:<br><input type="password"'
    . 'method="post" name="register_rptPassword"><br><br>';
    
    echo '<button type="submit" name="btn_register">sign up</button></form>';
}