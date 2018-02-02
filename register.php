<?php
include_once 'util/LoginUtility.php';
include_once 'php/functions.php';
Login::logout();

$page = "";

$page = $page . get_head();

$page = $page .  get_simplenav();

$page = $page .  get_index_jumbotop();
$page = $page .  get_jumbobot();

$page = $page .  "<div id='quote' class='container' style='border:2px solid #cecece; border-radius: 10px; padding: 20px 20px 20px 20px;"
        . "margin-bottom: 50px; background-color: #201515; max-width: 600px'>";

$page = $page . Registration::createRegisterForm();

echo $page;

echo "</div>";
