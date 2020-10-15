<?php
include_once("../includes/documentHeader.php");
//include_once("documentHeader.php");

function emitComponentRecords($xRet) {
	$dStream = "";
	for ($i = 0; $i < count($xRet); $i++) {
		$dStream = $dStream . '<component id="' . $xRet[$i]->id . '"/>';
		$dStream = $dStream . '<name>' . $xRet[$i]->id . '</name>';
		$dStream = $dStream . '<sampleURL>' . $xRet[$i]->sampleURL . '</sampleURL>';
		$dStream = $dStream . '<downloadURL>' . $xRet[$i]->downloadURL . '</downloadURL>';
		$dStream = $dStream . '<sourceURL>' . $xRet[$i]->sourceURL . '</sourceURL>';
		$dStream = $dStream . '</component>';
	}
	return $dStream;
}

try {
	$cmd = getValueFromGetOrPostArray("cmd");

	$responseID = 0;
	$responseMsg = "";
	
	$debugMsg = "";
	$dataStream = "";

	$db = new MSSQLDB('Flex2Components', 'SQL2005', 'sa', 'sisko@7660$boo');

	if ($cmd == "getList") {
		$query = "SELECT id, name, sampleURL, downloadURL, sourceURL FROM Flex2Components ORDER BY name;";
		$xRet = $db->query_database($query);
		if (count($xRet) > 0) {
			$dataStream = $dataStream . emitComponentRecords($xRet);
		}
	} else if ($cmd == "add") {
		$name = $db->sqlEscape(getValueFromGetOrPostArray("name"));
		$sample = $db->sqlEscape(getValueFromGetOrPostArray("sample"));
		$download = $db->sqlEscape(getValueFromGetOrPostArray("download"));
		$source = $db->sqlEscape(getValueFromGetOrPostArray("source"));
		
		$dataField = getValueFromGetOrPostArray("dataField");

		$query = "INSERT INTO Flex2Components (name, sampleURL, downloadURL, sourceURL) VALUES ('$name','$sample','$download','$source');";
		$xRet = $db->query_database($query);
		if (count($xRet) > 0) {
			$dataStream = $dataStream . emitComponentRecords($xRet);
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
	<datum>$dataStream</datum>
</response>
XML;
echo $xmlstr;
?> 

