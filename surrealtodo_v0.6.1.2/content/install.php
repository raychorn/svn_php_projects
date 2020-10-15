<?php

require "../connect.php";
require "version.php";
if(!isset($_GET['locale']))
{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Surreal ToDo Install</title>
	
	<link rel="stylesheet" type="text/css" href="../theme/base.css" />
	
	</head>
	
	<body>
	<div id="install">
	<?php
				echo '
					<form action="'.$_SERVER['PHP_SELF'].'" method="get" name="form_locale">';
				echo _('Language');
	?>			
				<select title="Language" name="locale">
	<?php
				// open the locale directory 
				$localeDirectory = "../locale/";
				$directory = opendir($localeDirectory);
				
				// get each entry
				while($entryName = readdir($directory)) {
					$localeList[] = $entryName;
				}
				
				// close directory
				closedir($directory);
				sort($localeList);
				
				// loop through the array of files and print them all
						
								foreach ($localeList as $locales) {
									if (substr("$locales", 0, 1) != "."){ // don't list hidden files
										if (filetype($localeDirectory.$locales) == "file") continue;
										echo "<option>$locales</option>";
									}
								}
							?>
				</select>			
	<?php			
				echo '	<input name="continue" type="submit" value="'._('Continue').'" />
					</form>
				';
}
else {
	
	$locale = $_GET['locale'];
	bindtextdomain($locale, '../locale');
	bind_textdomain_codeset($locale, 'UTF-8');
	textdomain($locale);
	setlocale(LC_ALL,$locale);
	
	if(!isset($_GET['install'])) {  // if the form has NOT been submitted
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Surreal ToDo Install</title>
	
	<link rel="stylesheet" type="text/css" href="../theme/base.css" />
	
	</head>
	
	<body>
	<div id="install">
	<?php
	
		echo '<h1>Surreal ToDo v'.APP_VERSION.' '._('Installation').'</h1>';
	
		$query = mysql_query("show tables");
		if(!$query) {
			echo '<h2>'._('Database connection failed.').'</h2>';
			echo '<p>'._('Edit connect.php and set the mysql database connection information.').'</p>';
			exit;
		}
		$num_results = mysql_num_rows($query); 
	
		if($num_results > 0) {
			echo '<h2>'._('The database is not empty.').'</h2>';
			echo '<h2>'._('This install script will not continue.').'</h2>';
			echo '<p>Surreal ToDo '._('currently does not support sharing a database.').'</p>';
		}
	
		else {
			echo '<h2>'._('This script will create the needed tables in your database.').'</h2>';
			echo '
					<form action="'.$_SERVER['PHP_SELF'].'" method="get" name="form_install">
						<input name="locale" type="hidden" value="'.$locale.'" />
						<input name="install" type="submit" value="'._('Install').'" />
					</form>
				';
	
		}
	
	}
	
	if(isset($_GET['install'])) // if the form has been submitted
	{  
	
		$default_timezone = date_default_timezone_get();
		
		mysql_query("
			CREATE TABLE `config` (
			`name` varchar(128) NULL,
			`value` varchar(128) NULL,
			PRIMARY KEY  (`name`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");
		mysql_query("insert into config set name = 'version', value = '".APP_VERSION."'");
		mysql_query("insert into config set name = 'timezone', value = '".$default_timezone."'");
		mysql_query("insert into config set name = 'default_site_name', value = 'Surreal ToDo'");
		mysql_query("insert into config set name = 'date_format', value = 'Y-m-d'");
		mysql_query("insert into config set name = 'time_format', value = 'h:ia'");
		mysql_query("insert into config set name = 'locale', value = '".$locale."'");
		mysql_query("insert into config set name = 'theme', value = 'default'");
		mysql_query("insert into config set name = 'completed_item', value = '8'");
		
		mysql_query("
			CREATE TABLE `tabs` (
			  `tab_id` int(8) unsigned NOT NULL auto_increment,
			  `name` varchar(20) collate utf8_unicode_ci NOT NULL,
			  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
			  `position` int(8) unsigned default '0',
			  `trash` int(1) unsigned default '0',
			  PRIMARY KEY  (`tab_id`),
			  KEY `tab` (`trash`,`position`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		mysql_query("
			insert into tabs set name = '"._('Main Tab')."';
		");
		
		mysql_query("
			CREATE TABLE `pages` (
			  `page_id` int(8) unsigned NOT NULL auto_increment,
			  `name` varchar(20) collate utf8_unicode_ci NOT NULL,
			  `tab_id` int(8) unsigned NOT NULL default '0',
			  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
			  `position` int(8) unsigned default '1',
			  `columns` int(1) unsigned default '3',
			  `trash` int(1) unsigned default '0',
			  PRIMARY KEY  (`page_id`),
			  KEY `page` (`tab_id`,`trash`,`position`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		mysql_query("
			insert into pages set name = '"._('Main Page')."', tab_id = 1, position = 1;
		");
		
		mysql_query("
			CREATE TABLE `lists` (
			  `list_id` int(8) unsigned NOT NULL auto_increment,
			  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
			  `page_id` int(8) unsigned NOT NULL default '0',
			  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
			  `column_id` int(8) unsigned default NULL,
			  `position` int(8) unsigned default '1',
			  `expanded` int(1) NOT NULL default '1',
			  `trash` int(1) unsigned default '0',
			  `show_item_date` int(1) unsigned default '0',
			  PRIMARY KEY  (`list_id`),
			  KEY `list` (`page_id`,`trash`,`column_id`,`position`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		mysql_query("
			CREATE TABLE `items` (
			  `id` int(8) unsigned NOT NULL auto_increment,
			  `list_id` int(8) unsigned NOT NULL,
			  `text` mediumText collate utf8_unicode_ci NOT NULL,
			  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
			  `date_completed` datetime default NULL,
			  `position` int(8) unsigned NOT NULL default '0',
			  `trash` int(1) unsigned default '0',
			  PRIMARY KEY  (`id`),
			  KEY `item` (`list_id`,`trash`,`position`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Surreal ToDo Install</title>
	
	<link rel="stylesheet" type="text/css" href="../theme/base.css" />
	
	</head>
	
	<body>
	<div id="install">

<?php		
			echo '<h2>'._('Install Complete').'</h2>';
			echo '<a href="../index.php">'._('Continue').'</a>';

	} // close if(isset($_GET['install']))
} //close else $_GET['locale']
?>
</div>
</body>
</html>
