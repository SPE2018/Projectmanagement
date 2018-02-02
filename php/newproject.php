<?php
include_once("../util/sql_util.php");
include_once("../util/LoginUtility.php");
$name = $_GET['name'];
$startdate = $_GET['startdate'];
$enddate = $_GET['enddate'];

if (!Login::isLoggedIn()) {
    return;
}
$userid = Login::getLoggedInId();

ProjectManager::addProject($name, $startdate, $enddate);

$project = ProjectManager::getProjectFromName($name);
ProjectManager::addUserToProject($project->id, $userid, "leader");

