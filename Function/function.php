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
       //Display Message Function
       function display_message() {
        if(isset($_SESSION['Message'])) {
            echo $_SESSION['Message'];
            unset($_SESSION['Message']);
        }
    }
    //Generate Token
    function Token_Generator() {
        $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        return $token;
    }

    //Send Email Function
    function send_email($email, $sub, $msg, $header) {
        return mail($email, $sub, $msg, $header);
    }

    //print error
    function error_validation($Error) {
        return '<div style="color:red">'.$Error.'</div>';
    }
    //User Vlidation Function
    function user_validation() {
        if($_SERVER['REQUEST_METHOD']=='POST') {
            $FirstName = clean($_POST['FirstName']);
            $LastName = clean($_POST['LastName']);
            $UserName = clean($_POST['UserName']);
            $Email = clean($_POST['Email']);
            $Password = clean($_POST['Password']);
            $CPassword = clean($_POST['CPassword']);

            //echo $FirstName,$LastName,$UserName,$Email,$Password,$CPassword;
            $Errors = [];
            $Max = 20;
            $Min = 02;

            //Check the firstname characters
            if(strlen($FirstName)<$Min) {
                $Errors[] = "*First name cannot be less than {$Min} Characters ";
            }

            if(strlen($FirstName)>$Max) {
                $Errors[] = "*First name cannot be more than {$Max} Characters ";
            }

            //Check the lastname characters
            if(strlen($LastName)<$Min) {
                $Errors[] = "*Last name cannot be less than {$Min} Characters ";
            }

            if(strlen($LastName)>$Max) {
                $Errors[] = "*Last name cannot be more than {$Max} Characters ";
            }

            //Check the user characters
            if(!preg_match("/^[a-zA-Z,0-9]*$/", $UserName)) {
                $Errors[] = "*User name cannot be accept those characters ";
            }

            //Check the mail existence
            if(email_exists($Email)) {
                $Errors[] = "*Email already registered ";
            }
            //Check the username existence
            if(user_exists($UserName)) {
                $Errors[] = "*User name already registered ";
            }
            //Confirm password
            if($Password != $CPassword) {
                $Errors[] = "*Password does not matched ";
            }

            if(!empty($Errors)) {
                foreach($Errors as $Error){
                    echo error_validation($Error); 
                }
            }
            else {
                if(user_registration($FirstName, $LastName, $UserName, $Email, $Password)) {
                    set_message('<p style="color:blue">Register Successfully...Check email</p>');
                    redirect("../Pages/signin.php");   //TODO TEST HERE
                }
                else {
                    set_message('<p style="color:blue">Register Failed...Pleas try again</p>');
                    redirect("../Pages/signup.php");    //TODO TEST HERE
                }
            }
        }
    }
    //Check Email Existence 
    function email_exists($email) {
        $sql = "select * from users where Email = '$email'";
        $result = Query($sql);
        if(fetch_data($result)) {
            return true;
        }
        else {
            return false;
        }
    }

    //Check UserName Existence 
    function user_exists($user) {
        $sql = "select * from users where UserName = '$user'";
        $result = Query($sql);
        if(fetch_data($result)) {
            return true;
        }
        else {
            return false;
        }
    }

    //User Registration Function
    function user_registration($FName, $LName, $UName, $email, $pass) {
        $FirstName = escape($FName);
        $LastName = escape($LName);
        $UserName = escape($UName);
        $Email = escape($email);
        $Pass = escape($pass);

        if(email_exists($Email)) {
            return true;
        } else if(user_exists($UserName)) {
            return true;
        } else {
            $Password = md5($Pass);
            $validation_code = md5($UserName.microtime());

            $sql = "insert into users (FirstName, LastName, UserName, Email, Password, Validation_Code, Active)
            values ('$FirstName','$LastName','$UserName','$Email','$Password','$validation_code','0')";

            $result = Query($sql);
            confirm($result);

            $subject = "Active your Life3 Account ";
            $msg = "Please click the link to active your Life3 account: http://life3.io/login/Pages/activate.php?Email=$Email&Code=$validation_code";
            $header = "From: no-reply-admin@life3.io";

            send_email($email,$subject,$msg,$header);

            return true;
        }
    }
 
