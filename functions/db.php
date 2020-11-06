<?php

    $con = mysqli_connect('localhost', 'samrat', 'samrat', 'RestSaveLogin');

    if(!$con) {
        echo 'Connection Error';
    }

    //Function Clean String values
    function escape($string) {
        global $con;
        return mysqli_real_escape_string($con, $string);
    }

    //Query Function
    function Query($query) {
        global $con;
        return mysqli_query($con, $query);
    }

    //Confirmation Function
    function confirm($result) {
        global $con;
        if(!$result){
            die('Query Failed'.mysqli_error($con));
        }
    }

    //Fetch Data From Database
    function fetch_data($result) {
        return mysqli_fetch_assoc($result);
    }

    //Row Values From Database
    function row_count($count) {
        return mysqli_num_rows($count);
    }
?>