<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
echo get_head();



echo get_navtop();
foreach($Projects as $v)
    echo '<a class="dropdown-item" href="projects.php?id='.$v->id.'&name='.$v->name.'&startdate='.$v->createdDate.'&enddate='.$v->endDate.'">'.$v->name.'</a>';
echo get_navbottom();
echo get_index_jumbotop();
echo get_jumbobot();
?>