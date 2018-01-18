<?php

include "util/sql_util.php";

SQL::connect();

$loos = SQL::getUser("loospete");
var_dump($loos);
/*sleep(1);
sql_addProject("project1");

var_dump(sql_getProjectFromId(5, true));*/

SQL::loadTasks(1);

?>