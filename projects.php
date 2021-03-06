<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once("util/LoginUtility.php");
if (!Login::isLoggedIn()) {
    header("Location: login.php");
    return;
}

$name = get_parameter('name', 'GET', false);
$project = ProjectManager::getProjectFromName($name);
if ($project == null) {
    header("Location: index.php");
    return;
}

echo get_head();
echo get_navtop();

$id = $project->id;
$startdate = $project->createdDate;
$enddate = $project->endDate;
$user = UserManager::getUserByID(Login::getLoggedInId());

ProjectManager::displayProjectList();    

echo get_navbottom($user);
echo get_jumbotop("Project Overview");
?>
<h1 class="display-2"><?php echo $name; ?></h1>
<?php echo get_jumbobot();?>
            <main>
                <div class="container">
                    <table class="table">
                        <tr><td style="width: 25rem"></td><td style="width: 50rem"><?php echo get_tabs(); ?></td></tr>

                        <tr><td id="progressContent"><?php echo get_projectprogress($id, $name, $startdate, $enddate); ?></td><td id="content">
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
        <script src="php/js/progress.js"></script>
        <script src="php/js/buttons.js"></script>
        <script src="php/js/clock.js"></script>
        <script src="php/js/weather.js"></script>
        <script src="php/js/charts.js"></script>
<?php echo get_footer(); ?>
