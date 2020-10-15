<?php
include_once("../includes/documentHeader.php");

$errors = array();
$debug = array();
$data = "";
$success = "false";

function echo_errors($errors) {
	$errorsStream = "";
	$errorsStream = $errorsStream . "<error>";
	for($i = 0; $i < count($errors); $i++) {
		$errorsStream = $errorsStream . "<msg>" . cdataEscape($errors[$i]) . "</msg>";
	}
	$errorsStream = $errorsStream . "</error>";
	return $errorsStream;
}

function echo_debug($debug) {
	$debugStream = "";
	$debugStream = $debugStream . "<debug>";
	for($i = 0; $i < count($debug); $i++) {
		$debugStream = $debugStream . "<msg>" . cdataEscape($debug[$i]) . "</msg>";
	}
	$debugStream = $debugStream . "</debug>";
	return $debugStream;
}

$cmd = getValueFromGetOrPostArray("cmd");

array_push($debug,"cmd=" . $cmd);

$t = getdate();

$today = date('m-d-y', $t[0]);

$file_path = $_SERVER['DOCUMENT_ROOT']."/#myUploads/" . $today . "/" . $_SERVER['REMOTE_ADDR'];

array_push($debug,"\$file_path=" . $file_path);

switch($cmd) {

	case "verifyUpload":
		$filename = getValueFromGetOrPostArray("filename");

		$fname = $file_path . "/" . $filename;
		array_push($debug,"\$filename=" . $fname);
		
		$success = "true";
		
		$_bool = file_exists ( $fname);
		$data = $data . "<verification>" . (($_bool) ? "1" : "0") . "</verification>";

		break;

	case "SendEmailMessage":
		$fromAddr = getValueFromGetOrPostArray("fromAddr");
		array_push($debug,"\$fromAddr=" . $fromAddr);
	
		$toAddr = getValueFromGetOrPostArray("toAddr");
		array_push($debug,"\$toAddr=" . $toAddr);
	
		$subj = getValueFromGetOrPostArray("subj");
		array_push($debug,"\$subj=" . $subj);
	
		$msg = getValueFromGetOrPostArray("msg");
		array_push($debug,"\$msg=" . $msg);
	
		$msg = wordwrap($msg, 70);
	
		$smtp=new SMTPMAIL;
  
		array_push($debug,"\$smtp=" . var_print_r($smtp));
		
		if(!$smtp->send_smtp_mail($toAddr,$subj,$msg,"",$fromAddr)) {
			$success = "false";
			array_push($errors,"SMTP Failed to send the requested email.");
		} else {
			$success = "true";
		}
		break;

	default:
		$success = "false";
		array_push($errors,"No action was requested.");
}

?>
<?php
	$success = cdataEscape($success);
	$errStr = echo_errors($errors);
	$debugStr = echo_debug($debug);
$xmlstr = <<<XML
<?xml version="1.0" encoding="iso-8859-1"?>
<results>
	<success>$success</success>
	$data
	$errStr
	$debugStr
</results>
XML;
echo $xmlstr;
?> 
