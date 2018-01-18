<?php
    include_once 'LoginUtility.php';
    //include_once 'functions.php';
    
    //get_Head();
?>
<body>
    <h1>Sign up</h1>
    
    <div name="registerform">
        <?php
            login_createRegisterForm();
        ?>
        <a href="login.php">back to login</a>
    </div>
</body>

