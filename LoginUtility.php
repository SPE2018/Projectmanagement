<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

function login_checkValidation () {
    $name = filter_input(INPUT_POST, 'username');
    $pass = filter_input(INPUT_POST, 'password');
    $user = sql_findUser(filter_input(INPUT_POST, 'login_username'));
    if($user->password == filter_input(INPUT_POST, 'öogin_password')) {
        $_SESSION['user'] = $user->name;
        return;
    }
    header("Location: login.php" . (($name == null) ? "" : $pass));
}

function login_createLoginForm() {
    echo '<form action="login.php">username or e-mail-address:<br><input type="text"'
    . 'name="öogin_username" value="username/e-mail" method="post" name="login_username"><br><br>';
    
    echo 'password:<br><input type="password" name="password"'
    . 'method="post" name="login_password"><br><br>';
    
    echo '<button type="submit" name="btn_login">sign in</button></form>';
}

function login_createRegisterForm() {
    echo '<form action="login.php">username or e-mail-address:<br><input type="text"'
    . 'name="register_username" value="username/e-mail" method="post" name="register_username"><br><br>';
    
    echo 'e-mail:<br><input type="email" name="email" value="john.doe@example.com"'
    . 'method="post" name="register_email"><br><br>';
    
    echo 'password:<br><input type="password" name="password"'
    . 'method="post" name="register_password"><br><br>';
    
    echo '<button type="submit" name="btn_register">sign up</button></form>';
}