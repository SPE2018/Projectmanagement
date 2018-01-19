<?php
    include_once '../LogIn/LoginUtility.php';
    include_once '../util/user_manager.php';
    include_once '../util/sql_util.php';
    include_once 'adminUtility.php';
    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
    //get_Head();
?>
<body>
    <h1>office of da big bozz</h1>
    <div name="adminform">
        <?php
            echo "users with enable pending:";
            Admin::display_usersToEnable();
            echo "all users:";
            Admin::display_userList()
        ?>
        <a href=index.php">home</a>
    </div>

</body>
