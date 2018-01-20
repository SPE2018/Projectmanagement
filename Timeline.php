<?php
include_once("php/functions.php");
include_once ("util/project_manager.php");
include_once ("util/wrapper/project_class.php");
echo get_head();
?>
    <body>

<div class="container-fluid">
<?php
echo get_nav();
?>
    <header>
        <div class="jumbotron jumbotron-billboard">
            <div class="img"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Jumbotron</h2>
                        <p>
                            Lorem ipsum is the best
                        </p>
                        <a href="#" class="btn btn-success btn-lg">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="row">
        <div class="col-sm-12">
            <main>
                <?php
                    $arr = array();
                    $Project1 = new Project(1, "GameDev", "2018-01-15", "2018-01-21");
                    $arr[] = $Project1;
                    $Project2 = new Project(1, "WebDev", "2018-01-13", "2018-01-25");
                    $arr[] = $Project2;
                    echo get_projecttable($Project1->name, $Project1->createdDate, $Project1->endDate);
                    echo get_projecttable($Project2->name, $Project2->createdDate, $Project2->endDate);
                ?>

                <script>
                    var arr = [];
                    arr = <?php echo json_encode($arr) ?>;

                    for(var i = 0; i < arr.length; i++){
                        var progress = calcProgress(arr[i].createdDate, arr[i].endDate);
                        var project = arr[i].name;
                        progressBar(project, progress);
                        collapseHandle(project, progress);
                        adjustmentHeight(project);
                        createChart(project);
                    }

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
                    };

                    function adjustmentHeight(project)
                    {
                        var bar = document.getElementById("progress-"+project);
                        var tdHeight = document.getElementById("progress-td-"+project).offsetHeight;
                        bar.style.height = tdHeight+"px";

                    };

                    function collapseHandle(project, progress) {
                        $("#pm-btn-" + project).click(function () {
                            $(".collapse-"+project).toggleClass('collapse');
                            $("#pm-btn-" + project).toggleClass('fa-plus');
                            $("#pm-btn-" + project).toggleClass('fa-minus');
                            $(".fa-plus").click(progressBar(project, progress));
                        });
                    };

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
