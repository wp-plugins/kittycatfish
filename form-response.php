<?php
// contact form processing

// get values
$name = $_GET['name'];
$email = $_GET['email'];
$need = $_GET['need'];
$message = $_GET['message'];
$rand_msg = $_GET['rand_msg'];

// check that values are filled in correctly
$errors = array();

// name
if ($name != ''){
	if (!preg_match("/\A([\w\'-.#$\/ ])*\z/", $name)){
		// syntax error
		$errors['kc_contact_name'] = 'syntax';
	}
}else{
	$errors['kc_contact_name'] = 'empty';
}

//email address
if ($email != ''){
	if (!preg_match("/\A([\w-])+([\.\w-])*@([\w-])+(\.[\w-]+)*\.([a-zA-Z]{2,6})\z/", $email)){
		// syntax error
		$errors['kc_contact_email'] = 'syntax';
	}
}else{
	$errors['kc_contact_email'] = 'empty';
}

// message
if ($message != ''){
	if (preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i", $message)){
		$errors['kc_contact_message'] = 'syntax';
	}
}

// build response
if (!empty($errors)){
	// send error messages
	$response = '{"status":"errors", "errors":'.json_encode($errors).'}';
}else{
	$response = '{"status":"ok"}';
	
	// email form data
	$email_address = 'matt@wisetoweb.com';
	$return_address = $email;
	$subject = "New Submission from KittyCatfish Contact Form";
	$message = $subject."\n----------------------------------------\n\n";
	$message .= "Name: $name \nEmail: $email \nNeed: $need \nMessage:\n$message \n\nDisplayed Message was: $rand_msg";
	
	$message = stripslashes($message);
	
	// text-only headers
	$headers = "From: ".$return_address."\r\nReturn-Path: ".$return_address."\r\nReply-To: ".$return_address."\r\n";
	
	// send message
	mail($email_address, $subject, $message, $headers);
}

// send response
echo $response;
?>