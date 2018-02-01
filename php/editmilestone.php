<?php
include_once("util/sql_util.php");
    $milestone_id = $_GET['milestone_id'];
    $name = $_GET['name'];
    $desc = $_GET['desc'];
    $start = $_GET['start'];
    $stop = $_GET['stop'];
    MilestoneManager::updateMilestone($milestone_id, $name, $desc, $start, $stop);