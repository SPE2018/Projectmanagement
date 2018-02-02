<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once("util/LoginUtility.php");
include_once("util/quote_helper.php");
echo get_head();

if (Login::isLoggedIn()) {
    echo get_navtop();
    $Projects = ProjectManager::getAllProjects(false, false);    
    foreach($Projects as $v) {
        echo '<a class="dropdown-item" href="projects.php?name='.$v->name.'">'.$v->name.'</a>';
    }
    echo get_navbottom();
} else {
    echo get_simplenav();
}

echo get_index_jumbotop();
echo get_jumbobot();

echo "<div id='quote' class='container' style='border:2px solid #cecece; border-radius: 10px; padding: 20px 20px 20px 20px;"
        . "background-color: #201515; display: none'>";

$quote_helper = new QuoteHelper();
$quote = $quote_helper->getRandom();
echo $quote;


echo "</div>";

?>
<script>$('#quote').fadeIn(5000);</script>
