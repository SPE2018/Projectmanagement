<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
$mode = get_parameter('mode', 'GET', false);
$mid = get_parameter('mid', 'GET', false);
$pid = get_parameter('pid', 'GET', false);
$tid = get_parameter('tid', 'GET', false);

if($mode === "milestoneview"){
    MilestoneManager::displayMilestone($pid, $mid);
}
if($mode === "taskmodal"){
    MilestoneManager::displayMilestone($pid, $mid);
    echo TaskEditor::displayTaskModal($pid, $mid, $tid);
    echo '<script>$("#' . $tid . '_task").modal("show");</script>';
}
if($mode === "projectusers"){
    ProjectManager::displayProjectUsers($pid);
}