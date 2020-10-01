<?php

    //Clean String Values
    function clean($string) {
        return htmlentities($string);
    }

    //Redirection
    function redirect($location) {
        return header("location:{$location}");
    }

    //Set Session Message
    function set_message($msg) {
        if($msg) {
            $_SESSION['Message'] = $msg;
        }
        else {
            $msg = "";
        }   
    }