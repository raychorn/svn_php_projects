<?php
require_once "Mail.php";

require_once "documentHeader.php";

require_once 'JSON.php';

$resp = array();
$json = new Services_JSON();

$username = "vyperlogix@gmail.com";
$password = "peekab00";

$from = $username;
$to = "Ray C Horn <raychorn@gmail.com>";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";

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
}

if ($_is_method_post) {
	try {
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
}

if ($_is_method_post) {
	try {
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
}

$host = "smtp.gmail.com";
$port = "587";

$headers = array ('From' => $from, 'To' => $to, 'Subject' => $subject);
$smtp = Mail::factory('smtp', array ('host' => $host,'port' => $port,'auth' => true,'username' => $username,'password' => $password));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
	$resp['ERROR'] = $mail->getMessage();
} else {
	$resp['STATUS'] = "Message successfully sent!";
}
echo($json->encode($resp));
?>