<?php

    function myfun()
    {
        echo 'This is Config File';
    }

    ob_start();
    session_start();

    require_once('db.php');
    require_once('functions.php');
?>