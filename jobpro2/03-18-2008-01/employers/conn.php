<?

//	'server'     'database_username'		'database_password' 
$connection = mysql_connect("localhost", "root", "peekaboo")
 or die (mysql_error());

 //	'database_name' 
 $db = mysql_select_db("jobpro2", $connection) or die (mysql_error());
?>