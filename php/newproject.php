<?php
include_once("util/sql_util.php");
    $name = $_GET['name'];
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    ProjectManager::addProject($name, $startdate, $enddate);
