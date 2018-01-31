<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once ("util/project_manager.php");
include_once ("util/wrapper/project_class.php");
echo get_head();
$Projects = ProjectManager::getAllProjects(false, false);
?>
<div class="container-fluid">
    <?php
echo get_navtop();
foreach($Projects as $v)
    echo '<a class="dropdown-item" href="projects.php?name='.$v->name.'&startdate='.$v->createdDate.'&enddate='.$v->endDate.'">'.$v->name.'</a>';
echo get_navbottom();
echo get_jumbotop();
?>
    <h1 class="display-2"><?php echo get_parameter('name', false); ?></h1>
    <?php echo get_jumbobot();?>

    <main>
        <div class="container">
            <div class="col-lg-12">
                <?php echo get_tabs(); ?>
                <div class="row justify-content-md-center">
                    <?php
                            $name = get_parameter('name', false);
                            $startdate = get_parameter('startdate', false);
                            $enddate = get_parameter('enddate', false);
                            echo get_projecttable($name, $startdate, $enddate);
                            echo get_chart($name);
                    ?>
                </div>
            </div>
        </div>
    </main>
    <div class="row">
        <div class="col-lg-12">
            <footer></footer>
        </div>
    </div>
        <script>
            var name = <?php echo json_encode(get_parameter('name', false))?>;
            var startdate = <?php echo json_encode(get_parameter('startdate', false))?>;
            var enddate = <?php echo json_encode(get_parameter('enddate', false))?>;
        </script>
        <script src="php/js/script.js"></script>
    </body>
</html>
