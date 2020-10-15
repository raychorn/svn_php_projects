<?php

require "../connect.php";
require "version.php";

if(!isset($_GET['upgrade'])) {  // if the form has not been submitted
	$query = mysql_query("show tables") or die(mysql_error()); 
	if(!$query) {
		echo '<h2>'._('Database connection failed.').'</h2>';
		echo '<p>'._('Edit connect.php and set the mysql database connection information.').'</p>';
		exit;
	}
	$num_results = mysql_num_rows($query); 
	if($num_results == 0) {
		header('location: ./install.php');
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Surreal ToDo <?php echo _('Upgrade'); ?></title>

<link rel="stylesheet" type="text/css" href="../theme/base.css" />

</head>

<body>
<div id="upgrade">
<h1>Surreal ToDo <?php echo _('Upgrade'); ?></h1>
<?php
	while($row = mysql_fetch_row($query)){
		if ($row[0] == 'config') {
			// Check the current version
			
			// Examine the config table layout to see how to retrieve the version
			$queryConfig = mysql_query("SHOW columns FROM config");
			
			while ($rowConfig = mysql_fetch_assoc($queryConfig)) {
				if ($rowConfig['Field'] == 'value')  {  // table is in new config format
					$query = mysql_query("SELECT value FROM config where name = 'version'");
					$num_results = mysql_num_rows($query); 
					if($num_results == 0) {
						$schema_version = "Undetermined";
						break;
					}
					$row = mysql_fetch_assoc($query);
					$schema_version = $row['value'];
					break;
				}
				if ($rowConfig['Field'] == 'version')  {  // table is in old config format
					$query = mysql_query("SELECT version FROM config");
					$num_results = mysql_num_rows($query); 
					if($num_results == 0) {
						$schema_version = "Undetermined";
						break;
					}
					$row = mysql_fetch_assoc($query);
					$schema_version = $row['version'];
					break;
				}
			}
				break;
		}
		if ($row[0] == 'projects') {
			$schema_version = "pre_v0.3";
			break;
		}
		if ($row[0] == 'tabs') {
			$schema_version = "0.3";
			break;
		}
	}

	switch ($schema_version){
		
		case 'pre_v0.3':
			echo '<h2>'._('Unable to upgrade versions prior to v0.3').'<h2>';
			break;
			
		case APP_VERSION :
			echo '<h2>'._('Upgrade is not needed.').'</h2>';
			echo '<br /><br /><a href="./index.php">'._('Continue').'</a>';
			break;

		case $schema_version :
			echo '<h2>'._('This script will upgrade your database from version').' '.$schema_version.' -> '.APP_VERSION.'.</h2>';
			echo '<h2>'._('Please!  Backup your database before updating.').'</h2>';
			echo '
					<form action="'.$_SERVER['PHP_SELF'].'" method="get" name="form_upgrade">
						<input name="version" type="hidden" value="v'.$schema_version.'" />
						<input name="upgrade" type="submit" value="'._('Upgrade').'" />
						
					</form>
				';
			break;
			
		default :
				echo '<h2>'._('Unable to upgrade.').'</h2';
			if(version_compare(APP_VERSION, $schema_version) == -1){
				echo '<h2>'._('Your database is at a higher revision than the application.').'</h2>';
			}
			else {
				echo '<h2>'._('Unable to determine the version of your database.').'</h2>';
			}
			break;	
		
			
	} // close switch $status

} // close if(!isset($_GET['upgrade']))

if(isset($_GET['version'])) {

		
	$version = $_GET['version'];
	
	switch($version) {
		
		case 'v0.3':
			echo '<h2>Starting upgrade...</h2>';
			
			mysql_query("alter table todo change date_added date_created timestamp NOT NULL default CURRENT_TIMESTAMP");		
			mysql_query("alter table todo change date_closed date_completed datetime default NULL");		
			mysql_query("alter table lists add column column_id int(8) unsigned default '1'");
			
			mysql_query("CREATE TABLE `config` (
			  `config_id` int(8) unsigned NOT NULL,
			  `version` varchar(20) collate utf8_unicode_ci NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
			mysql_query("insert into config set config_id = 0, version = '0.3.1'");
			
			$query = mysql_query("SELECT * FROM lists");
			while($row = mysql_fetch_assoc($query)){
				$listID = $row['list_id'];
				list($left,$top,$zindex) = explode('x',$row['position']);
				if($left < 300) mysql_query("update lists set column_id = 1, position = ".$top." where list_id = ".$listID);
				if($left > 600) mysql_query("update lists set column_id = 3, position = ".$top." where list_id = ".$listID);
				if(($left >= 300) && ($left <= 600)) mysql_query("update lists set column_id = 2, position = ".$top." where list_id = ".$listID);
			}

			mysql_query("alter table lists change position position int(8) unsigned");		
		
			$tabquery = mysql_query("SELECT * from tabs");
			while($tabrow = mysql_fetch_assoc($tabquery)){
				$tabID = $tabrow['tab_id'];
				$position = 1;
				
				for ($columnID = 1; $columnID <= 3; ++$columnID ) {
					$position = 1;
					$query = mysql_query("SELECT * FROM lists where column_id = ".$columnID." AND tab_id = ".$tabID." ORDER BY position");
					while($row = mysql_fetch_assoc($query)){
						$listID = $row['list_id'];
						mysql_query("update lists set position = ".$position." where list_id = ".$listID);
						$position = ++$position;
					}
				} // close FOR loop
			}  // close while tabs
			
		case 'v0.3.1':
			
			// Select all the exiting tabs in order to create a default page for each
			
			mysql_query("
				CREATE TABLE `pages` (
				  `page_id` int(8) unsigned NOT NULL auto_increment,
				  `name` varchar(20) collate utf8_unicode_ci NOT NULL default 'New Page',
				  `tab_id` int(8) unsigned NOT NULL default '0',
				  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
				  `position` int(8) unsigned default '1',
				  PRIMARY KEY  (`page_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
			");			

			$query = mysql_query("SELECT * FROM tabs ORDER BY tab_id ASC");
			
			// For each tab create a 'Main Page' and reassgn the lists to the 'Main Page'
			
			while($row = mysql_fetch_assoc($query)){
				$tabID = $row['tab_id'];
				mysql_query("INSERT into pages set name = 'Main Page', tab_id = $tabID");
				$pageID = mysql_insert_id();
				mysql_query("UPDATE lists set tab_id = $pageID where tab_id = $tabID");
			}
				
			mysql_query("alter table lists change tab_id `page_id` int(8) unsigned NOT NULL default '0'");
			
			mysql_query("update config set version = '0.4'");
			mysql_query("alter table config add PRIMARY KEY (`config_id`)");			

		case 'v0.4':
		
			$default_timezone = date_default_timezone_get();
			
			mysql_query("drop table config");		
			mysql_query("CREATE TABLE `config` (
			  `name` varchar(128) NULL,
			  `value` varchar(128) NULL,
			  PRIMARY KEY  (`name`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
			mysql_query("insert into config set name = 'version', value = '0.4.1'");
			mysql_query("insert into config set name = 'timezone', value = '".$default_timezone."'");
			mysql_query("insert into config set name = 'default_site_name', value = 'Surreal ToDo'");
			mysql_query("insert into config set name = 'default_tab_name', value = 'New Tab'");
			mysql_query("insert into config set name = 'first_page_name', value = 'Main Page'");
			mysql_query("insert into config set name = 'default_page_name', value = 'New Page'");
			mysql_query("insert into config set name = 'default_list_name', value = 'New List'");
			mysql_query("insert into config set name = 'default_item_name', value = 'Double-click to Edit.'");
			
		case 'v0.4.1':
			mysql_query("update config set value = '0.5' where name = 'version'");
			mysql_query("drop index todo_id on todo");
			mysql_query("drop index list_id on lists");
			mysql_query("rename table todo to items");
			mysql_query("alter table tabs add column trash int(1) unsigned default '0'");
			mysql_query("alter table pages add column trash int(1) unsigned default '0'");
			mysql_query("alter table lists add column trash int(1) unsigned default '0'");
			mysql_query("alter table items add column trash int(1) unsigned default '0'");
			mysql_query("create index item on items (list_id, trash, position)");
			mysql_query("create index list on lists (page_id, trash, column_id, position)");
			mysql_query("create index page on pages (tab_id, trash , position)");
			mysql_query("create index tab on tabs (trash, position)");

		case 'v0.5':
			mysql_query("update config set value = '0.5.1' where name = 'version'");
			mysql_query("alter table items add column link varchar(255) collate utf8_unicode_ci default '#'");
		
		case 'v0.5.1':
			mysql_query("update config set value = '0.5.2' where name = 'version'");
			mysql_query("insert into config set name = 'date_format', value = 'Y-m-d'");
			mysql_query("insert into config set name = 'time_format', value = 'h:ia'");
			mysql_query("insert into config set name = 'theme', value = 'default'");

		case 'v0.5.2':
			mysql_query("update config set value = '0.6' where name = 'version'");
			mysql_query("insert into config set name = 'locale', value = 'en_US.utf8'");
			mysql_query("alter table pages add column columns int(1) unsigned default '3'");
			mysql_query("insert into config set name = 'completed_item', value = '8'");
			mysql_query("alter table items change text `text` mediumText collate utf8_unicode_ci NOT NULL");
			mysql_query("alter table lists add column show_item_date int(1) unsigned default '0'");
			mysql_query("update items set color = '' where color = '#d89c3a'");
			$query = mysql_query("select * from items 
									WHERE fontStyle <> '' 
									OR fontWeight <> '' 
									OR fontSize <> '' 
									OR color <> '' 
									OR indent <> '' 
									OR link <> '#'
							");
		
			while($row = mysql_fetch_assoc($query)){
	
				if ($row['link'] != '#') $text = '<a href="'.$row['link'].'">'.$row['text'].'</a>';
				else $text = $row['text'];
				$newItem = '<span style="font-style:'.$row['fontStyle'].'; font-weight:'.$row['fontWeight'].'; font-size:'.$row['fontSize'].'; color:'.$row['color'].'; margin-left:'.$row['indent'].';">'.$text.'</span>';
				
				mysql_query("update items set text = '$newItem', fontStyle = '', fontWeight = '', fontSize = '',
				color = '', indent = '', link = '#' WHERE id = ".$row['id']);
			}
		
			mysql_query("alter table items drop column fontStyle");
			mysql_query("alter table items drop column fontWeight");
			mysql_query("alter table items drop column fontSize");
			mysql_query("alter table items drop column color");
			mysql_query("alter table items drop column indent");
			mysql_query("alter table items drop column link");

		case 'v0.6':  // no schema updates
			mysql_query("update config set value = '0.6.1' where name = 'version'");
		
		case 'v0.6.1':  // no schema updates
			mysql_query("update config set value = '0.6.1.1' where name = 'version'");

		case 'v0.6.1.1':  // no schema updates
			mysql_query("update config set value = '0.6.1.2' where name = 'version'");

		case 'v0.6.1.2':  // current version
			break;
		
		default:
			echo "Unable to determine your version to perform upgrade.";
	} // close switch
	
	echo '<h2>'._('Upgrade Complete').'</h2>';
	echo '<a href="../">'._('Continue').'</a>';
}
?>
</div>
</body>
</html>
