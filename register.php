<!--<?php
    //include_once '../util/LoginUtility.php';
    //include_once 'functions.php';
    //Login::logout();
    //get_Head();
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<body>
    <h1>Sign up</h1>
    <div name="registerform">
        <?php
            //Registration::createRegisterForm();
        ?>
        <a href="login.php">login</a>
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
