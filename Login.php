<?php
    include_once 'LoginUtility.php';
    //include_once 'functions.php';
    
    //get_Head();
?>
<body>
    <h1>Sign in</h1>
    <div name="loginform">
        <?php
            login_createLoginForm();
        ?>
        <a href="register.php">register</a>
    </div>

</body>
