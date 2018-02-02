<?php



//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN
//YET TO BE OVERRIDDEN









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
            <h1>Editmeeting</h1>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-9">
            <main>
                <?php
                $id=-1;
                if (isset($_GET["id"]))
                {
                    $id=$_GET["id"];
                }

                echo  edit_meeting($id);
                ?>
            </main>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <footer> Fusszeile </footer>
        </div>
    </div>

</div>
</body>
</html>