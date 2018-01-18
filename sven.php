<?php
include "util/sql_util.php";

SQL::connect();

$loos = SQL::getUser("loospete");
//var_dump($loos);
/*sleep(1);
sql_addProject("project1");

var_dump(sql_getProjectFromId(5, true));*/

//var_dump(SQL::loadMilestones(5));

SQL::addMilestone(5, "Milestone1", "Das ist der erste Meilenstein");
SQL::addMilestone(5, "Milestone2", "Das ist der zweite Meilenstein");
SQL::addMilestone(5, "Milestone3", "Das ist der dritte Meilenstein");
SQL::addMilestone(5, "Milestone4", "Das ist der vierte Meilenstein");
SQL::addMilestone(5, "Milestone5", "Das ist der fünfte Meilenstein");
SQL::addMilestone(5, "Milestone6", "Das ist der sechste Meilenstein");

var_dump(SQL::loadMilestones(5));