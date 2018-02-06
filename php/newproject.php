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

if (strlen($name) > 45) {
    $name = substr($name, 0, 45);
}

ProjectManager::addProject($name, $startdate, $enddate);

$project = ProjectManager::getProjectFromName($name);
ProjectManager::addUser($project->id, $userid, "leader");

echo "<br><div class='alert alert-success'><strong>Succes!</strong> " . $name . " is now ready to use.</div><br><br><br><br>";