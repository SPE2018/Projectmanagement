<!DOCTYPE html>
<html>
<?php
include_once ("php/functions.php");
echo get_head();
?>
<body>

    <?php
        echo get_bootstrap();
    ?>

    <header>
        <div class="jumbotron">
            <h1>Dates</h1>
        </div>
    </header>

    <div class="container">
        <?php
            get_meetinglist();
        ?>
    </div>

</body>
</html>