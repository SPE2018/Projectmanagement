<?php
include_once("util/sql_util.php");
include_once("php/functions.php");

$pid = get_parameter('pid', 'GET', false);
$mid = get_parameter('mid', 'GET', false);
if($mid !== false){
    MilestoneManager::displayMilestone($pid, $mid);
}
