<?php
    include_once '../util/LoginUtility.php';
    include_once '../util/note_manager.php';
    //get_Head();
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<body>
    <h1>Sign in</h1>
    <div name="loginform">
        <?php
            Login::createLoginForm();
        ?>
        <a href="register.php">register</a>
    </div>
</body>
