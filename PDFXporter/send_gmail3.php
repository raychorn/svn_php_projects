<?php
require_once('class.phpgmailer.php');

require_once "documentHeader.php";

require_once 'JSON.php';

$crlf = "\r\n";

$resp = array();
$json = new Services_JSON();

$username = "vyperlogix@gmail.com";
$password = "peekab00";

$time_start = microtime_float();

$_is_method_post = false;
$resp['DEBUG.1'] = var_dump_ret($_SERVER);
$REQUEST_METHOD = "GET";
if (isset($_SERVER['REQUEST_METHOD'])) {
	$REQUEST_METHOD = strtoupper($_SERVER['REQUEST_METHOD']);
}
$_is_method_post = strcmp($REQUEST_METHOD,"POST") == 0;
$resp['DEBUG.2'] = var_dump_ret($_is_method_post);

if ($_is_method_post) {
	$resp['DEBUG.3'] = var_dump_ret($_POST);
	try {
		$to = '';
		if (isset($_POST["to"])) {
			$_to_ = $_POST["to"];
			if (strlen($_to_) > 0) {
				$to = $_to_;
			}
		}
	}
	catch (Exception $e) {
		$resp['ERROR.1'] = 'Exception caught: '.$e->getMessage()."\n";
	}

	try {
		$_subject_ = '';
		if (isset($_POST["subject"])) {
			$_subject_ = $_POST["subject"];
			if ( ($_subject_) && (strlen($_subject_) > 0) ) {
				$subject = $_subject_;
			}
		}
	}
	catch (Exception $e) {
		$resp['ERROR.2'] = 'Exception caught: '.$e->getMessage()."\n";
	}
	
	try {
		$body = '';
		if (isset($_POST["body"])) {
			$_body_ = $_POST["body"];
			if ( ($_body_) && (strlen($_body_) > 0) ) {
				$body = $_body_;
			}
		}
	}
	catch (Exception $e) {
		$resp['ERROR.3'] = 'Exception caught: '.$e->getMessage()."\n";
	}
	
	try {
		$altbody = '';
		if (isset($_POST["altbody"])) {
			$_altbody_ = $_POST["altbody"];
			if ( ($_altbody_) && (strlen($_altbody_) > 0) ) {
				$altbody = $_altbody_;
			}
		}
	}
	catch (Exception $e) {
		$resp['ERROR.4'] = 'Exception caught: '.$e->getMessage()."\n";
	}
} else {
	$from = $username;
	$to = "raychorn@gmail.com";
	$subject = "Hi!";
	$body = "<p>Hi,<BR/><BR/>How <u>are</u> you?</p>";
	$altbody = "Hi,\n\nHow are you?\n";
}

$mail = new PHPGMailer();
$mail->Username = $username; 
$mail->Password = $password;
$mail->From = $from; 
$mail->FromName = 'Vyper Logix Web Team';
$mail->Subject = $subject;
$mail->AddAddress($to);
if (strlen($altbody) > 0) {
	$resp['DEBUG.4'] = 'IsHTML(true)';
	$mail->Body = stripslashes($altbody);
	$mail->IsHTML(true);
} else {
	$resp['DEBUG.5'] = 'IsHTML(false)';
	$mail->Body = stripslashes($body);
	$mail->AltBody = stripslashes($altbody);
	$mail->IsHTML(false);
}
$mail->Send();

$resp['STATUS'] = $mail->ErrorInfo;

$time_end = microtime_float();
$resp['ELAPSED_TIME'] = $time_end - $time_start;

echo($json->encode($resp));
?>