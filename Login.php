<?php
    include_once 'LoginUtility.php';
    include_once 'functions.php';
    
    get_Head();
?>
<body>
    <h1>LogIn</h1>
    <div name="loginform">
        <?php
            login_createLoginForm();
        ?>
    </div>
    <a href="register.php">register</a>
</body>
