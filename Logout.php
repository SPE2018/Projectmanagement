<?php
    include_once 'util/LoginUtility.php';

    unset($_SESSION['user']);
    header("Location: index.php");