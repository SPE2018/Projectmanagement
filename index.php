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
            <div class="col-12">
    <?php
    if ($loggedIn) {
        echo "<h1 class='display-10 mb-2'>Welcome back  <b>" . Login::getLoggedInName() . "</b></h1>";
        echo "<h3 class='display-10 mb-2'>Let's planIT...</b></h3>";
        echo "<script>$('#user').fadeIn(3000);</script>";
    } else {
        if (filter_input(INPUT_GET, "newuser") != null) {
            echo "<h1 class='display-10 mb-2'>Welcome to planIT,  <b>" . Login::getLoggedInName() . "</b></h1>";
        }
        else {
            echo "<h1 class='display-10 mb-2'>Manage your project</h1>";
        }
    }
    ?>
                <p class="text-justify" style="font-size: 15pt;">You may use this onlinetool for managing your projects: Add milestones, built of tasks, with a date to start working on it and a date
when it's planned to be finished latest.<br>See statistics about the project's progress and your timemanagement on diagrams and add
Users as participants to have a great overview over your employees, responsible for each project.<br><br>Whether software development or feeding
the animals on a big farm, there's always a way you can improve your works efficiency, using <span style="font-size: 22pt; color: #00bc8c">PlanIT</span</p>
            </div>
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
                    Featured
                </div>
                <img class="card-img-top" src="php/images/progress.PNG" alt="Card image cap">
                <div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<footer>
    <div class="row bg-secondary p-3 mt-5 m-0"><div class="ml-2">&#9400; <script>document.write(moment().year());</script> planIT</div> <a href="#" class="ml-auto mr-3">Terms</a><a href="#" class="mr-3">Privacy</a><a href="#" class="mr-4">Security</a></div>
</footer>
<script src="php/js/script.js"></script>
<script>$('#quote').fadeIn(5000);</script>
