<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

include_once 'LoginUtility.php';
include_once 'util/sql_util.php';
//include_once 'functions.php';

class Admin{
    //public static function
}