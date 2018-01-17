<?php

include "sql/sql_util.php";

sql_connect();

$loos = sql_getUser("loospete");
var_dump($loos);
sleep(1);
sql_addProject("project1");

var_dump(sql_getProjectFromId(5, true));

?>