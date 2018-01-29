<?php
    include_once '../util/adminUtility.php';
    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
    //get_Head();
?>
<body>
    <h1>office of da big bozz</h1>
    <div name="adminform">
        <?php
            echo '<p style="Color: darkgreen; Font-Size:26">Welcome, Master =))</p><br>';       
            Admin::display_DisabledUserList();
            Admin::display_EnabledUserList()
        ?>
        <a href=../LogIn/Login.php.php">home</a>
    </div>

</body>
