<?php
include_once ("functions.php");
echo get_head();
?>
<body>

<<<<<<< HEAD
include "util/sql_util.php";

SQL::connect();

$loos = SQL::getUser("loospete");
//var_dump($loos);
/*sleep(1);
sql_addProject("project1");

var_dump(sql_getProjectFromId(5, true));*/

var_dump(SQL::loadMilestones(5));
=======
<div class="container">
    <?php
>>>>>>> d4638bc0bc0706cdeeac51295ea50ae6eedd7d67

    echo get_nav();
    ?>
    <header>
        <div class="jumbotron">
            <h1>Milestones</h1>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-9">
            <main>
                <?php

                ?>
            </main>
        </div>
        <div class="col-lg-3">
            <aside></aside>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <footer></footer>
        </div>
    </div>

</div>
</body>
</html>
