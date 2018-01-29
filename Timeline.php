<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once ("util/project_manager.php");
include_once ("util/wrapper/project_class.php");
echo get_head();
?>
    <body>
<?php
echo get_nav();
?>
    <header>
        <div class="jumbotron jumbotron-billboard">
            <div class="img"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="display-4">Project Overview</h1>
                        <p>
                            Lorem ipsum is the best
                        </p>
                        <button type="button" class="btn btn-success btn-lg"  data-toggle="modal" data-target="#myModal">New Project</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">New Project</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Project Name</label>
                                <input class="form-control" type="text" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="startdate" class="col-form-label">Start Date</label>
                                <input class="form-control" type="date" id="startdate" name="startdate">
                            </div>
                            <div class="form-group">
                                <label for="enddate" class="col-form-label">End Date</label>
                                <input class="form-control" type="date" id="enddate" name="enddate">
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="save">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <?php
                        $arr = array();
                        $name = get_parameter("name", false);
                        $startdate = get_parameter("startdate", false);
                        $enddate = get_parameter("enddate", false);
                        ProjectManager::addProject($name, $startdate, $enddate);
                        $Project = ProjectManager::getProjectFromId(1, $loadMilestones = false);
                        $arr[] = $Project;
                        echo get_projecttable($Project->name, $Project->createdDate, $Project->endDate);
                    ?>

                    <script>
                        var arr = [];
                        arr = <?php echo json_encode($arr) ?>;

                        onload = initProject();
                        $['#save'].click(initProject());

                        function initProject() {
                            for (var i = 0; i < arr.length; i++) {
                                var progress = calcProgress(arr[i].createdDate, arr[i].endDate);
                                var project = arr[i].name;
                                progressBar(project, progress);
                                collapseHandle(project, progress);
                                adjustmentHeight(project);
                                createChart(project);
                            }
                        }

                        // Calculate the actual project progress
                        function calcProgress(startDate, endDate){
                            var now = moment().format("YYYY-MM-DD");
                            now = moment(now);
                            var start = moment(startDate);
                            var end = moment(endDate);
                            var startToNow = moment.duration(now.diff(start));
                            var startToEnd = moment.duration(end.diff(start));
                            startToNow = startToNow.asDays();
                            startToEnd = startToEnd.asDays();
                            var progress = (startToNow/startToEnd)*100;
                            return progress;
                        }

                        // Show the progress bar --> animated
                        function progressBar(project, progress)
                        {
                            var elem = document.getElementById(project);
                            var height = 0;
                            elem.style.height = height + '%';
                            var id = setInterval(frame, 25);

                            function frame() {
                                if (height >= progress) {
                                    clearInterval(id);
                                } else {
                                    height++;
                                    elem.style.height = height + '%';
                                }
                            }
                        }

                        // Adjust the height of the progress column
                        function adjustmentHeight(project)
                        {
                            var bar = document.getElementById("progress-"+project);
                            var tdHeight = document.getElementById("progress-td-"+project).offsetHeight;
                            bar.style.height = tdHeight+"px";

                        }

                        // Handle the collapse of the project tables
                        function collapseHandle(project, progress) {
                            $("#pm-btn-" + project).click(function () {
                                $(".collapse-"+project).toggleClass('collapse');
                                $("#pm-btn-" + project).toggleClass('fa-plus');
                                $("#pm-btn-" + project).toggleClass('fa-minus');
                            });
                        }

                        // Create the chart for the trend analysis
                        function createChart(project){
                            var trace1 = {
                                x: [1, 2, 3, 4],
                                y: [10, 15, 13, 17],
                                type: 'scatter',
                                line: {
                                    color: '#28a745'
                                }
                            };

                            var trace2 = {
                                x: [1, 2, 3, 4],
                                y: [16, 5, 11, 9],
                                type: 'scatter',
                                line: {
                                    color: '#dc3545'
                                }
                            };

                            var data = [trace1, trace2];

                            var layout = {
                                autosize: true,
                                width: 350,
                                height: 250,
                                font: {
                                  color: '#ffffff'
                                },
                                paper_bgcolor: "#222",
                                plot_bgcolor: "#222",
                                margin: {
                                    l: 50,
                                    r: 50,
                                    b: 30,
                                    t: 30,
                                    pad: 4
                                }
                            };
                            Plotly.newPlot("chart-"+project, data, layout);
                        }
                    </script>
                </div>
            </div>
        </div>
    </main>

        <div class="col-lg-3">
            <aside></aside>
        </div>


    <div class="row">
        <div class="col-lg-12">
            <footer></footer>
        </div>
    </div>


    </body>
</html>
