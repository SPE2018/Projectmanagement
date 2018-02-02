<?php
    //include_once 'util/LoginUtility.php';
    //include_once 'util/note_manager.php';
    //get_Head();
?>

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<body>
    <h1>Sign in</h1>
    <div name="loginform">
        <?php
            //Login::createLoginForm();
        ?>
        <a href="register.php">register</a>
    </div>
</body>
-->

<?php
include_once 'util/LoginUtility.php';
include_once 'php/functions.php';

$page = "";
$page = $page . get_head();

$page = $page . get_simplenav();

$page = $page . get_index_jumbotop();
$page = $page . get_jumbobot();

$login = Login::createLoginForm();

echo $page
        . "<div id='quote' class='container' style='border:2px solid #cecece; border-radius: 10px; padding: 20px 20px 20px 20px;"
        . "margin-bottom: 50px; background-color: #201515; max-width: 600px'>"
        . $login;



echo "</div>";