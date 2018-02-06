<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once("util/LoginUtility.php");
if (!Login::isLoggedIn()) {
    header("Location: login.php");
    return;
}
echo get_head();

$name = get_parameter('name', 'GET', false);
$project = ProjectManager::getProjectFromName($name);
$id = $project->id;
$startdate = $project->createdDate; //get_parameter('startdate', 'GET', false);
$enddate = $project->endDate; //get_parameter('enddate', 'GET',false);

echo get_navtop();
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

                        <tr><td><?php echo get_projecttable($id, $name, $startdate, $enddate); ?></td><td id="content">
                            </td></tr>
                    </table>
                </div>
            </main>
        <footer>
            <div class="row bg-secondary p-3 mt-5 m-0"><div class="ml-2">&#9400; <script>document.write(moment().year());</script> planIT</div> <a href="#" class="ml-auto mr-3">Terms</a><a href="#" class="mr-3">Privacy</a><a href="#" class="mr-4">Security</a></div>
        </footer>
        <script>
            var pid = <?php echo json_encode($id)?>;
            var name = <?php echo json_encode($name)?>;
            var startdate = <?php echo json_encode($startdate)?>;
            var enddate = <?php echo json_encode($enddate)?>;
        </script>
        <script src="php/js/script.js"></script>
    </body>
</html>
