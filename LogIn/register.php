<?php
    include_once '../util/LoginUtility.php';
    //include_once 'functions.php';
    
    //get_Head();
?>
<body>
    <h1>Sign up</h1>
    
    <div name="registerform">
        <?php
            Registration::createRegisterForm();
        ?>
        <a href="login.php">back to login</a>
    </div>
</body>

