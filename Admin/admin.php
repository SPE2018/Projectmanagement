<?php
    include_once '../util/adminUtility.php';
    include_once '../util/note_manager.php';
    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
    //get_Head();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<body>
    <h1>office of da big bozz</h1>
    <div name="adminform">
        <?php
            $sql = "SELECT * FROM users;";
            $result = SQL::query($sql)->fetch_all(MYSQLI_ASSOC);
            echo '<p style="Color: darkgreen; Font-Size:26">Welcome, Master ' . $_SESSION['user'] . '</p><br>';       
            Admin::updateLists();
            Admin::display_DisabledUserList();
            Admin::display_Admins();
            Admin::display_EnabledUserList();
            if(isset($_SESSION['user'])) {
                NoteManager::createNoteModal($_SESSION['user'], 1, '[usertext]');
                echo '<a href=../LogIn/note.php>note</a><br>';
            }
        ?>
        <a href=../LogIn/Logout.php>sign out</a><br>
        <a href=../index.php>home</a><br>
    </div>

</body>
