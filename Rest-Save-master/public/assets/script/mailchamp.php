<?php
/*
***
***
Name: 			mailchamp.php
Written by: 	ThemeTrades
Theme Version:	1.0.0
***
***
*/
if(isset($_POST)){
	$response['status'] = 0;
	$response['message'] = '';
    
	
    $email = trim(strip_tags($_POST['email']));
	if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        // MailChimp API credentials
        $apiKey = '<Put here your MailChamp API Key>';
        $listID = '<Put here your MailChamp list ID>';
        
        // MailChimp API URL
        $memberID = md5(strtolower($email));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
        
        // member information
        $json = json_encode([
            'email_address' => $email,
			'status'        => 'subscribed'
        ]);
        
        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
		// store the status message based on response code
        if ($httpCode == 200) {
			$response['status'] = 1;
            $response['message'] = 'You have successfully subscribed.';
        } else {
            switch ($httpCode) {
                case 214:
                    $message = 'You are already subscribed.';
                    break;
                default:
                    $message = 'Some problem occurred, please try again.';
                    break;
            }
            $response['message'] = $message;
        }
    }else{
        $response['message'] = 'Please enter valid email address.';
    }
	echo json_encode($response);
	exit;
}
