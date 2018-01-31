<?php
include_once("functions.php");
include_once ("../util/project_manager.php");
    $name = $_GET['name'];
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    ProjectManager::addProject($name, $startdate, $enddate);
