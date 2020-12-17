<?php
/*
***
***
Name: 			contact.php
Written by: 	ThemeTrades
Theme Version:	1.0.0
***
***
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
To Show Error : error_reporting(E_ALL);
To Hide Error : error_reporting(0);
*/

/* include reCaptcha package autoload file */
require('recaptcha-master/src/autoload.php');

/* reCaptcha Secret key */
$reCaptchaSecretKey = '<Put here your reCaptcha Secret Key>';

$emailReceiver 		 = "<Put here your email address>"; 
$defaultEmailSubject = '<Put here your default email subject >';
/* is You Want To Add Form Subject To Your Default Subject : true/false */
$additionalSubject = true;  
$defaultEmailSender    = "<Put here your default sender name>";

if (!empty($_POST) && !empty($_POST['email'])) {

		$respondArray = array();
		
        /* validate the ReCaptcha, if something is wrong, we throw an Exception,
			i.e. code stops executing and goes to catch() block */
        
        if (!isset($_POST['g-recaptcha-response'])) {
            $respondArray['status'] = 0;
			$respondArray['msg'] = 'ReCaptcha is not set.';
			putMessage($respondArray);
        }

        /* do not forget to enter your secret key from https://www.google.com/recaptcha/admin */
        
        $recaptcha = new \ReCaptcha\ReCaptcha($reCaptchaSecretKey, new \ReCaptcha\RequestMethod\CurlPost());
        
        /* we validate the ReCaptcha field together with the user's IP address */
        
        $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        if (!$response->isSuccess()) {
            $respondArray['status'] = 0;
			$respondArray['msg'] = 'ReCaptcha was not validated.';
			putMessage($respondArray);
		}
        
		$email = trim($_POST['email']);
		isEmailValid($email);
		
		$messageString = '';
		$post = $_POST;
		unset($post['g-recaptcha-response']);
		if($additionalSubject && !empty($post['subject'])){
				$defaultEmailSubject .= $post['subject'];
		}
		
		foreach($post as $key => $value){
			if(!is_array($value)){
				$fieldName = ucfirst(str_replace('_',' ',$key));
				$messageString .= $fieldName." : ".$value."<br>";
			}
		}
		
		$emailHeader  	= "MIME-Version: 1.0\r\n";
		$emailHeader 	.= "Content-type: text/html; charset=iso-8859-1\r\n";
		$emailHeader 	.= "From:$defaultEmailSender <$email>";
		$emailHeader 	.= "Reply-To: $email\r\n"."X-Mailer: PHP/".phpversion();
		if(mail($emailReceiver, $defaultEmailSubject, $messageString, $emailHeader)){
			$respondArray['status'] = 1;
			$respondArray['message'] = 'Thanks for Contact.We will contact to you soon.';
		} else {
			$respondArray['status'] = 0;
			$respondArray['message'] = 'There is something wrong. Please wait or submit again.';
		}
		putMessage($respondArray);
}

function putMessage($respondArray){
	echo json_encode($respondArray);
	exit;
}

function isEmailValid($email){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$respondArray['status'] = 0;
			$respondArray['msg'] = 'Please enter valid email address.';
			putMessage($respondArray);
		}
}