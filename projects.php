<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once("util/LoginUtility.php");
if (!Login::isLoggedIn()) {
    header("Location: login.php");
}
echo get_head();

$name = get_parameter('name', 'GET', false);
$project = ProjectManager::getProjectFromName($name);
$id = $project->id;
$startdate = $project->createdDate; //get_parameter('startdate', 'GET', false);
$enddate = $project->endDate; //get_parameter('enddate', 'GET',false);

echo get_navtop();
ProjectManager::displayProjectList();    
echo get_navbottom();
echo get_jumbotop();
?>
<h1 class="display-2"><?php echo $name; ?></h1>
<?php echo get_jumbobot();?>
            <main>
                <div class="container">
                    <table class="table">
                        <tr><td style="width: 25rem"></td><td style="width: 50rem"><?php echo get_tabs(); ?></td></tr>

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
