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
            Admin::updateLists();
            Admin::display_DisabledUserList();
            Admin::display_Admins();
            Admin::display_EnabledUserList()
        ?>
        <a href=../LogIn/Login.php>sign in</a><br>
        <a href=../LogIn/register.php>sign up</a><br>
        <a href=../index.php>home</a><br>
    </div>

</body>
