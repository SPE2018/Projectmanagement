<!--<?php
    //include_once '../util/LoginUtility.php';
    //include_once 'functions.php';
    //Login::logout();
    //get_Head();
?>
<body>
    <h1>Sign up</h1>
    
    <div name="registerform">
        <?php
            //Registration::createRegisterForm();
        ?>
        <a href="login.php">back to login</a>
    </div>
</body>-->

<?php
include_once '../util/LoginUtility.php';
Login::logout();

echo get_head();

echo get_simplenav();

echo get_index_jumbotop();
echo get_jumbobot();

echo "<div id='quote' class='container' style='border:2px solid #cecece; border-radius: 10px; padding: 20px 20px 20px 20px;"
        . "background-color: #201515; display: none'>";

Registration::createRegisterForm();


echo "</div>";
