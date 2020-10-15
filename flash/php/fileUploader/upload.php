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

array_push($debug,"action=" . $_REQUEST['action']);

$t = getdate();

$today = date('m-d-y', $t[0]);

$file_path = $_SERVER['DOCUMENT_ROOT']."/#myUploads/" . $today . "/" . $_SERVER['REMOTE_ADDR'];

switch($_REQUEST['action']) {

	case "upload":

		$file_temp = $_FILES['file']['tmp_name'];
		$file_name = $_FILES['file']['name'];
	
		array_push($debug,"\$file_temp=" . $file_temp);
		array_push($debug,"\$file_name=" . $file_name);
		array_push($debug,"\$file_path=" . $file_path);
		
		if (!is_dir ( $file_path)) {
			mkdir( $file_path, 0700, true);
		}
	
		//checks for duplicate files
		if(!file_exists($file_path."/".$file_name)) {
	
			 //complete upload 
			 $filestatus = move_uploaded_file($file_temp,$file_path."/".$file_name);
	
			 if(!$filestatus) {
				 $success = "false";
				 array_push($errors,"Upload failed. Please try again.");
			 }
		} else {
			$success = "false";
			array_push($errors,"File already exists on server.");
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
