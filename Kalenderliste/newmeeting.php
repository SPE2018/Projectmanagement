<?php
include_once ("php/functions.php");
echo get_head();
?>
<body>

<div class="container">
    <?php

    echo get_bootstrap();
    ?>
    <header>
        <div class="jumbotron">
            <h1>Meetingverwaltung</h1>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-9">
            <main>
                <?php
                $save = get_parameter("save", false);
                $table = "calendarlist";

                if($save !== false)
                {
                    //fügt neuen Datensatz hinzu
                    neuer_Datensatz($table );
                }

                echo  new_meeting();
                ?>
            </main>
        </div>
    </div>
</div>
</body>
</html>