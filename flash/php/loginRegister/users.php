<?php
include_once("../includes/documentHeader.php");
//include_once("documentHeader.php");

try {
	$cmd = getValueFromGetOrPostArray("cmd");
	$username = getValueFromGetOrPostArray("username");
	$password = getValueFromGetOrPostArray("password");
	$password2 = getValueFromGetOrPostArray("password2");

	$responseID = 0;
	$responseMsg = "";
	
	$debugMsg = "";

	$db = new MSSQLDB('UserLoginDemo', 'SQL2005', 'sa', 'sisko@7660$boo');

	if ($cmd == "register") {
		if ( (strlen($username) > 0) && (strlen($password) > 0) && (strlen($password2) > 0) ) {
			if ($password == $password2) {
				$_username = $db->sqlEscape($username);
				$query = "SELECT id, username, password FROM users WHERE (username = '$_username');";
				$xRet = $db->query_database($query);
				if (count($xRet) == 0) {
					$_password = $db->sqlEscape($password);
					$query = "INSERT INTO users (username, password) VALUES ('$_username','$_password');";
					$recID = $db->query_database($query);
					$responseID = $recID;
					$responseMsg = "User Registration Successful.";
				} else {
					$responseID = -229;
					$responseMsg = "Warning: User Name entered has already been taken by someone else...";
				}
			} else {
				$responseID = -228;
				$responseMsg = "Warning: Password and Repeated Password do not match...";
			}
		} else {
			$responseID = -220;
			$responseMsg = "Warning: Missing parms...";
			if (strlen($username) == 0) {
				$responseID = -222;
				$responseMsg = "Warning: Missing User Name...";
			} else if (strlen($password) == 0) {
				$responseID = -224;
				$responseMsg = "Warning: Missing Password...";
			} else if (strlen($password2) == 0) {
				$responseID = -226;
				$responseMsg = "Warning: Missing Repeated Password...";
			}
		}
	} else if ($cmd == "login") {
		if ( (strlen($username) > 0) && (strlen($password) > 0) ) {
			$username = $db->sqlEscape($username);
			$password = $db->sqlEscape($password);
			$query = "SELECT id, userName, password FROM Users WHERE (userName = '$username') AND (password = '$password');";
			$xRet = $db->query_database($query);
			if (count($xRet) == 1) {
				$recID = $xRet[0]->id;
				$responseID = $recID;
				$responseMsg = "Login Successful.";
			} else {
				$responseID = -100;
				$responseMsg = "Login unsuccessful. [$query]" . "[" . var_print_r($xRet) . "]";
			}
		} else {
			$responseID = -210;
			$responseMsg = "Warning: Missing parms...";
		}
	} else {
	  $responseID = -200;
	  $responseMsg = "Warning: Missing cmd...";
	}
	$responseMsg = "[$responseID] :: " . $responseMsg;
} catch (Exception $e) {
	$responseID = -999;
	$responseMsg = "Exception: " . var_print_r($e);
}
?>
<?php
	$responseID = cdataEscape($responseID);
	$responseMsg = cdataEscape($responseMsg);
	$debugMsg = cdataEscape($debugMsg);
$xmlstr = <<<XML
<?xml version="1.0" encoding="iso-8859-1"?>
<response>
	<status>
		<code>$responseID</code>
		<message>$responseMsg</message>
	</status>
	<debug>$debugMsg</debug>
</response>
XML;
echo $xmlstr;
?> 

