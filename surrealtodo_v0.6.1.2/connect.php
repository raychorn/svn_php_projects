<?php

/* Database config */

$db_host		= '127.0.0.1:33307';
$db_user		= 'root';
$db_pass		= 'peekab00';
$db_database	= 'surreal1_todo'; 

/* End config */

$link = @mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_set_charset('utf8');
mysql_select_db($db_database,$link);

?>
