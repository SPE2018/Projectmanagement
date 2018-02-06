<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once("util/LoginUtility.php");
include_once("util/quote_helper.php");
echo get_head();

$loggedIn = Login::isLoggedIn();
if ($loggedIn) {
    echo get_navtop();
    ProjectManager::displayProjectList();    
    echo get_navbottom(Login::getLoggedInName());
} else {
    echo get_simplenav();
}

echo get_index_jumbotop();
echo get_jumbobot();

if ($loggedIn) {
    echo "<div id='user' class='container' style='border:2px solid #cecece; border-radius: 10px; padding: 20px 20px 20px 20px;"
        . "background-color: #151525; display: none; margin-bottom: 20px'>";
    
    echo "<p style='font-size: 25px'>Welcome to planIT, <b>" . Login::getLoggedInName() . "</b></p>";
    
    echo "</div>";
    echo "<script>$('#user').fadeIn(3000);</script>";
} else {
    if (filter_input(INPUT_GET, "newuser") != null) {
        echo "<div id='user' class='container' style='border:2px solid #cecece; border-radius: 10px; padding: 20px 20px 20px 20px;"
        . "background-color: #353545; margin-bottom: 20px'>";
    
        echo "<p style='font-size: 25px'>Welcome to planIT!<br><b>You can log into your account after an admin has enabled it.</b></p>";

        echo "</div>";
    }
}

echo "<div id='quote' class='container' style='border:2px solid #cecece; border-radius: 10px; padding: 20px 20px 20px 20px;"
        . "background-color: #201515; display: none'>";

$quote_helper = new QuoteHelper();
$quote = $quote_helper->getRandom();
echo $quote;

echo "</div>";



?>
<script src="php/js/script.js"></script>
<script>$('#quote').fadeIn(5000);</script>
