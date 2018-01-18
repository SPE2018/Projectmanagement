<?php
include_once ("functions.php");
echo get_head();
?>
    <body>

<div class="container">
<?php
echo get_nav();
?>
    <header>
        <div class="jumbotron">
            <h1>Milestones</h1>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-9">
            <main>
                <?php
                echo get_projecttable("project1");
                ?>

                <script>
                    var projectID = ['project1'];
                    onload = projectID.forEach(progressBar);
                    function progressBar(index)
                    {
                        var elem = document.getElementById(index);
                        var height = 1;
                        var id = setInterval(frame, 10);

                        function frame() {
                            if (height >= <?php echo get_progress(); ?>) {
                                clearInterval(id);
                            } else {
                                height++;
                                elem.style.height = height + '%';
                            }
                        }
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
