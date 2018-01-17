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
    echo '<form action="login.php"><input type="text" name="username"'
    . 'value="username/e-mail" method="post" name="login_username"> Username or e-mail-address:<br>';
    
    echo '<input type="password" name="password"'
    . 'value="username/e-mail" method="post" name="login_password"> password:<br>';
    
    echo '<button type="submit" name="btn_login">anmelden</button></form>';
}