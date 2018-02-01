<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
echo get_head();
$Projects = ProjectManager::getAllProjects(false, false);
$id = get_parameter('id', 'GET', false);
$name = get_parameter('name', 'GET', false);
$startdate = get_parameter('startdate', 'GET', false);
$enddate = get_parameter('enddate', 'GET',false);

echo get_navtop();
foreach($Projects as $v)
    echo '<a class="dropdown-item" href="projects.php?id='.$v->id.'&name='.$v->name.'&startdate='.$v->createdDate.'&enddate='.$v->endDate.'">'.$v->name.'</a>';
echo get_navbottom();
echo get_jumbotop();
?>
<h1 class="display-2"><?php echo $name; ?></h1>
<?php echo get_jumbobot();?>
        <div class="container">
            <main>
                    <table class="table">
                        <tr><td></td><td class="col-12"><?php echo get_tabs(); ?></td></tr>
                        <tr><td><?php echo get_projecttable($id, $name, $startdate, $enddate); ?></td><td id="content">
                            </td></tr>
                    </table>
            </main>
        </div>
        <script>
            var pid = <?php echo json_encode(get_parameter('id', 'GET',false))?>;
            var name = <?php echo json_encode(get_parameter('name', 'GET',false))?>;
            var startdate = <?php echo json_encode(get_parameter('startdate', 'GET',false))?>;
            var enddate = <?php echo json_encode(get_parameter('enddate', 'GET',false))?>;

            $(".msBtn").click(function () {
                var mid = ($(this).val());
                $('html, body').animate({scrollTop : 110},600);
                $("#content").load("milestone.php?pid=" + pid + "&mid=" + mid);
            });
        </script>
        <script src="php/js/script.js"></script>
    </body>
</html>
