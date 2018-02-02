<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once("util/LoginUtility.php");
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

