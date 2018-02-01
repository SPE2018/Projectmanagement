<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
echo get_head();
$Projects = ProjectManager::getAllProjects(false, false);

$name = get_parameter('name', 'GET', false);
$project = ProjectManager::getProjectFromName($name);
$id = $project->id;
$startdate = $project->createdDate; //get_parameter('startdate', 'GET', false);
$enddate = $project->endDate; //get_parameter('enddate', 'GET',false);

echo get_navtop();
foreach($Projects as $v) {
    echo '<a class="dropdown-item" href="projects.php?name=' . $v->name . '">' . $v->name . '</a>';
}
echo get_navbottom();
echo get_jumbotop();
?>
<h1 class="display-2"><?php echo $name; ?></h1>
<?php echo get_jumbobot();?>
            <main>
                <div class="container">
                    <table class="table">
                        <tr><td style="width: 21.75rem"></td><td><?php echo get_tabs(); ?></td></tr>
                        <tr><td><?php echo get_projecttable($id, $name, $startdate, $enddate); ?></td><td id="content">
                            </td></tr>
                    </table>
                </div>
            </main>

        <script>
            var pid = <?php echo json_encode($id)?>;
            var name = <?php echo json_encode($name)?>;
            var startdate = <?php echo json_encode($startdate)?>;
            var enddate = <?php echo json_encode($enddate)?>;
        </script>
        <script src="php/js/script.js"></script>
    </body>
</html>
