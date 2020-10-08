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
            $msg = "Please click the link to active your Restsave account: http://Restsave.org/login/Pages/activate.php?Email=$Email&Code=$validation_code";
            $header = "From: no-reply-admin@life3.io";

            send_email($email,$subject,$msg,$header);

            return true;
        }
    }

    //Activation
    function activation() {
        if($_SERVER['REQUEST_METHOD']=="GET") {
            $Email = $_GET['Email'];
            $Code = $_GET['Code'];

            $sql = "select * from users where Email='$Email' and Validation_Code='$Code'";
            $result = Query($sql);
            confirm($result);

            if(fetch_data($result)) {
                $sqlquery = "update users set Active='1', Validation_Code='0' where Email='$Email' and Validation_Code='$Code'";
                $result2 = Query($sqlquery);
                confirm($result2);
                set_message('<p style="color:blue">Your RestSave account has been activated.</p>');
                redirect('signin.php');
            }
            else {
                echo '<p style="color:red">Your Restsave account has not been activated.</p>';
            }
        }
    }

    //User Login Validation
    function login_validation() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $UserEmail = clean($_POST['Uemail']);
            $UserPass = clean($_POST['Upass']);
            $Remember = isset($_POST['remember']);

            // $Errors = [];

            // if(!empty($Errors)) {
            //     foreach ($Errors as $Error) {
            //         echo error_validation($Error);
            //     }
            // }

            if(user_login($UserEmail, $UserPass, $Remember)) {
                redirect("https://drive.google.com/drive/folders/1VHjuMVQsq8nccqJ4WTnj8b1h99Hi6SIp");
            }
            else {
                echo error_validation("*Please enter correct email or password");
            }
        }

    }

    //User Login Validation
    function login_validation() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $UserEmail = clean($_POST['Uemail']);
            $UserPass = clean($_POST['Upass']);
            $Remember = isset($_POST['remember']);

            // $Errors = [];

            // if(!empty($Errors)) {
            //     foreach ($Errors as $Error) {
            //         echo error_validation($Error);
            //     }
            // }

            if(user_login($UserEmail, $UserPass, $Remember)) {
                redirect("https://drive.google.com/drive/folders/1VHjuMVQsq8nccqJ4WTnj8b1h99Hi6SIp");
            }
            else {
                echo error_validation("*Please enter correct email or password");
            }
        }

    }

    //log in check
    function user_login($Uemail, $Upass, $Remember) {
        $query = "select * from users where Email='$Uemail' and Active='1'";
        $result = Query($query);

        if($row=fetch_data($result)) {
            $db_pass = $row['Password'];
            if(md5($Upass) == $db_pass) {
                if($Remember == true) {
                    setcookie('email', $Uemail, time() + 86400);
                }
                $_SESSION['Email'] = $Uemail; 
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**************Recover Function************** */
    function recover_password() {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            if(isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
                $email = $_POST['UserEmail'];

                if(email_exists($email)) {
                    $code = md5($email.microtime());
                    setcookie('temp_code',$code,time()+86400);

                    $sql = "update users set Validation_Code='$code' where Email='$email'";
                    Query($sql);

                    $subject = "Please reset your Life3 account password";
                    $message = "Please click the link to reset your password: .... Your code is: {$code} http://Restsave.org/login/Pages/code.php?Email=$email&Code=$code";
                    $header = "From: no-reply-admin@life3.io";

                    if(send_email($email,$subject,$message,$header)) {
                        echo '<div style="color:blue">Please check your email.</div>';
                    } else {
                        echo error_validation("*Sending failed...");
                    }

                } else {
                    echo error_validation("*Email not found...");
                }
            }
            else {
                redirect("signin.php");
            }
        }
    }

    //Validation Code
    function validation_code() {
        if(isset($_COOKIE['temp_code'])) {
            if(!isset($_GET['Email']) && !isset($_GET['Code'])) {
                redirect('signin.php');
            } else if(empty($_GET['Email']) && empty($_GET['Code'])) {
                redirect("signin.php");
            } else {
                if(isset($_POST['recover-code'])) {
                    $code = $_POST['recover-code'];
                    $email = $_GET['Email'];

                    $query = "select * from users where Validation_Code='$code' and Email='$email'";
                    $result = Query($query);

                    if(fetch_data($result)) {
                        setcookie('temp_code',$code,time()+86400);
                        redirect("reset.php?Email=$email&Code=$code");
                    }
                    else {
                        echo error_validation("*Query failed...");
                    }
                }
            }

        } else {
            set_message('<div style="color:red">*Your code has been expired.</div>');
            redirect("recover.php");
        }
    }

 
