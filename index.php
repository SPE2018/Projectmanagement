<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once("util/LoginUtility.php");
include_once("util/quote_helper.php");
echo get_head();

if (UserManager::countUsers() == 0) {
    header("Location: install.php?createadmin=true");
    return;
}

$loggedIn = Login::isLoggedIn();
if ($loggedIn) {
    $user = UserManager::getUserByID(Login::getLoggedInId());
    echo get_navtop();
    ProjectManager::displayProjectList();    
    echo get_navbottom($user);
} else {
    echo get_simplenav();
}

echo get_index_jumbotop();
echo "<div id='quote' class='container text-center' style='padding: 20px 20px 20px 20px;"
    . "display: none'>";

$quote_helper = new QuoteHelper();
$quote = $quote_helper->getRandom();
echo $quote;

echo "</div>";

echo get_jumbobot();
?>
<main>
    <div class="container">
        <div class="row mb-5">
            <div class="col-6">
    <?php
    if ($loggedIn) {
        echo "<h1 class='display-10 mb-2'>Welcome back  <b>" . Login::getLoggedInName() . "!</b></h1>";
        echo "<h3 class='display-10 mb-2'>Let's planIT...</b></h3>";
        echo "<script>$('#user').fadeIn(3000);</script>";
    } else {
        if (filter_input(INPUT_GET, "newuser") != null) {
            echo "<h1 class='display-10 mb-2'>Welcome to planIT</h1>";
        }
        else {
            echo "<h1 class='display-10 mb-2'>Manage your project</h1>";
        }
    }
    ?>

                <p><br>You may use this onlinetool for managing your projects: Add milestones, built of tasks, with a date to start working on it and a date
                when it's planned to be finished latest.<br><br>See statistics about the project's progress and your time management on diagrams and add
                Users as participants to have a great overview over your employees, responsible for each project.<br><br>Whether software development or feeding
                the animals on a big farm, there's always a way you can improve your works efficiency, using <span class="text-success">planIT</span>.</p>
            </div>
            <div class="col-6 p-5 mt-5"><img src="php/images/planIT_logo_dark.png" style="width: 25rem" id="logo"></div>
        </div>
        <div class="row mb-5">
            <div class="card m-4" style="width: 18rem;">
                <div class="card-header">
                    Project Progress
                </div>
                <img class="card-img-top" src="php/images/progress.PNG" alt="Card image cap">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card m-4" style="width: 18rem;">
                <div class="card-header">
                    Stats
                </div>
                <img class="card-img-top" src="php/images/chart.PNG" alt="Card image cap">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
            <div class="card m-4" style="width: 18rem;">
                <div class="card-header">
                    Manage your milestones
                </div>
                <img class="card-img-top" src="php/images/milestonecard.PNG" alt="Card image cap">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="php/js/buttons.js"></script>
<script>$('#quote').fadeIn(5000);</script>
<?php echo get_footer();
