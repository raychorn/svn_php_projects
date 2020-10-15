<?php
	function var_print_r($mixed = null) {
	  ob_start();
	  print_r($mixed);
	  $content = ob_get_contents();
	  ob_end_clean();
	  return $content;
	}

	function cdataEscape($datum = "") {
		return "<![CDATA[ " . $datum . " ]]>";
	}
	
	function getValueFromArray($k = "",$a = null) {
		return (($a == null) ? "" : ((array_key_exists($k,$a)) ? $a[$k] : ""));
	}
	
	function getValueFromGetOrPostArray($k = "") {
		$val = "";
		if (strlen($k) > 0) {
			$val = getValueFromArray($k, $_GET);
			if (strlen($val) == 0) {
				$val = getValueFromArray($k, $_POST);
			}
		}
		return $val;
	}
	
	class MSSQLDB {
		var $db_connection;
		var $serverName;
		var $username;
		var $userPassword;
		var $status;
		var $statusMsg;
		 
		// Constructor
		function __construct($inDatabaseName, $serverName = "", $username = "", $userPassword = "") {
			if (strlen($serverName) == 0) {
				$serverName = "localhost,1433";
			}
			$this->serverName = $serverName;
			if (strlen($username) > 0) {
				$this->username = $username;
			}
			if (strlen($userPassword) > 0) {
				$this->userPassword = $userPassword;
			}
			$this->db_connection = mssql_connect($this->serverName, $this->username, $this->userPassword);
						  
			$retVal = mssql_select_db($inDatabaseName);
		}
		 
		function sqlEscape($sql) {
			$fix_str = str_replace("'","''",stripslashes($sql));
			$fix_str = str_replace("\0","[NULL]",$fix_str);
			
			return $fix_str;
		}

		function query_database($inQuery) {  // Generic query function
			// Always include the link identifier (in this case $this->db_connection) in mssql_query
			$query_result = mssql_query($inQuery, $this->db_connection);
			if ($query_result == false) {
				$this->status = -200;
				$this->statusMsg = 'Query failed: '.$inQuery;
				return;
			}

			if (stripos($inQuery, "insert ") == 0) {
				$result = array();	// fetch the results as an array
				while ($row = mssql_fetch_object($query_result)) {
					 $result[] = $row;
				}
				
				return $result;						// return result
			} else {
				$query = 'select SCOPE_IDENTITY() AS last_insert_id';	// get the last insert id
				$query_result = mssql_query($query);
				if ($query_result == false) {
					$this->status = -201;
					$this->statusMsg = 'Query failed: '.$inQuery ;
					return;
				}
													 
				$query_result    = mssql_fetch_object($query_result);
				
				return $query_result->last_insert_id;
			}
		}
	}
?>

