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
                    $Project = new Project(1, "Project", "2017-12-24", "2018-02-25");
                    echo get_projecttable($Project->name, $Project->createdDate, $Project->endDate);
                ?>

                <script>
                    var projectID = ['Project'];
                    onload = projectID.forEach(progressBar);
                    function progressBar(index)
                    {
                        var bar = document.getElementById("progress-"+index);
                        var tdHeight = document.getElementById("progress-td-"+index).offsetHeight;
                        bar.style.height = tdHeight+"px";
                        var elem = document.getElementById(index);
                        var height = 0;
                        elem.style.height = height + '%';
                        var id = setInterval(frame, 25);

                        function frame() {
                            if (height >= <?php echo get_progress($Project->createdDate, $Project->endDate); ?>) {
                                clearInterval(id);
                            } else {
                                height++;
                                elem.style.height = height + '%';
                            }
                        }
                    };

                    onload = projectID.forEach(collapseHandle);
                    function collapseHandle(index) {
                        $("#pm-btn-" + index).click(function () {
                            for(var i = 0; i<projectID.length;i++) {
                                if (index == projectID[i]) {
                                    $(".collapse-"+index).toggleClass('collapse');
                                }
                            }
                            $("#pm-btn-" + index).toggleClass('fa-plus');
                            $("#pm-btn-" + index).toggleClass('fa-minus');
                           progressBar(index);
                        });
                    };


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
                        width: 650,
                        height: 400,
                        margin: {
                            l: 50,
                            r: 50,
                            b: 30,
                            t: 30,
                            pad: 4
                        }
                    };

                    for(var i = 0; i<projectID.length; i++){
                        Plotly.newPlot("chart-"+projectID[i], data, layout);
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
