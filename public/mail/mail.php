<?php
 
if(isset($_POST['email'])) {
	// Require the Swift Mailer library
	require_once 'lib/swift_required.php';

	// Enter your SMTP settings here...
	// You can look up your mail server and also see if it supports TLS by going to:
	// http://mxtoolbox.com/diagnostic.aspx 
	// and entering smtp:yourdomain.com 
	// You'll be given a report stating the server name to use and whether your server supports TLS.
	
	
	// Change smtp.example.org to your own server address below
	// Enter your email account username and password below also...
	
	// If your server supports a security layer (Gmail enforces use of 'tls' and port 587) change port accordingly (587 or 25 usually) and use 'tls' or 'ssl' as a third argument like so:
	// FOR GMAIL: 		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'tls')
	// GENERIC TLS: 	$transport = Swift_SmtpTransport::newInstance('mail.mediumra.re', 25, 'tls')
	
	// If you choose not to use SSL or TLS then the following could work for you:
	// $transport = Swift_SmtpTransport::newInstance('mail.mediumra.re', 25)
	
	// or if you prefer/need to fall back to use PHP's inbuilt mail() function:
	// $transport = Swift_MailTransport::newInstance();
	
	$transport = Swift_SmtpTransport::newInstance($_SERVER['MAIL_HOST'], $_SERVER['MAIL_PORT'], $_SERVER['MAIL_ENCRYPTION'])
	  ->setUsername($_SERVER['MAIL_USERNAME'])
	  ->setPassword($_SERVER['MAIL_PASSWORD'])
	  ;

	
	$mailer = Swift_Mailer::newInstance($transport);
	
	$messageText = '';
	// Creating the message text using fields sent through POST
	
	foreach ($_POST as $key => $value)
		$messageText .= ucfirst($key).": ".$value."\n\n";
	
	$message = Swift_Message::newInstance($_SERVER['MAIL_SUBJECT'])
	  ->setFrom(array($_POST['email'] => $_POST['name']))
	  ->setTo(array($_SERVER['MAIL_TO_ADDR'] => $_SERVER['MAIL_TO_NAME']))->setBody($messageText);

	try{
		echo($mailer->send($message));
	}
	catch(Exception $e){
		// echo($e->getMessage());
		echo('Message could not be sent because of a technical error.');
	}
	exit;
}

?>