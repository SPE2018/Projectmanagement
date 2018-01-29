<?php
    include_once '../util/adminUtility.php';
    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
    //get_Head();
?>
<body>
    <h1>office of da big bozz</h1>
    <div name="adminform">
        <?php
            Admin::display_usersToEnable();
            Admin::display_EnabledUserList()
        ?>
        <a href=../index.php">home</a>
    </div>

</body>
