<?php 
define('BASE_DIR', dirname(__FILE__));
define('BASE_URL', str_replace("/index.php","",$_SERVER['SCRIPT_NAME']));

require BASE_DIR."/content/version.php";
require BASE_DIR."/connect.php";

if (!empty($_GET["content"])) {
	$content = $_GET["content"];
}
else {
	$content = "content/notebook.php";
}
if (isset($_GET['search'])) $content = "content/search.php";

// Check to make sure we are running PHP 5.2 or greater
if (version_compare(PHP_VERSION, '5.2.0') < 0) {
    echo 'Surreal ToDo '._('requires PHP 5.2 or greater.  Your version is') . ' ' . PHP_VERSION . "\n";
	break;
}

// Load all the application config settings
$query	= mysql_query("SELECT name, value FROM config");
if (!$query) header('location: ./content/install.php'); 
while($row = mysql_fetch_array($query)){
	$GLOBALS["config"][ $row["name"] ] = $row["value"];
}

// Set the language
$locale = $GLOBALS["config"]["locale"];
bindtextdomain($locale, './locale');
bind_textdomain_codeset($locale, 'UTF-8');
textdomain($locale);
setlocale(LC_ALL,$locale);
$localeFile = '/locale/'.$locale.'/LC_MESSAGES/'.$locale;

// Verify the database version coincides with the application version
// If not attempt to upgrade
if ($GLOBALS["config"]["version"] != APP_VERSION) header('location: ./content/upgrade.php'); 

// Set the user defined timezone
date_default_timezone_set($GLOBALS["config"]["timezone"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-quiv="Cache-Control" content="<?php if ($_SERVER['HTTP_HOST'] == 'webapps') echo 'no-cache'; else echo 'public';?>">
<meta http-quiv="Expires" content="<?php echo date('D, d M Y H:i:s \G\M\T', time() + 2592000); ?>">
<title><?php echo $GLOBALS["config"]["default_site_name"]; ?></title>
<?php 
echo '<link rel="stylesheet" type="text/css" href="min/?f=theme/base.css,theme/'.$GLOBALS["config"]["theme"].'/styles.css,theme/'.$GLOBALS["config"]["theme"].'/jquery/jquery-ui.css" />';
if (file_exists(BASE_DIR.$localeFile.".po")) {
	echo '<link rel="gettext" type="application/x-po" href=".'.$localeFile.'.po" />';
}
?>
</head>
<body>
<div id="container">
  <div id="header">
    <h1><a href="<?php echo BASE_URL; ?>"><?php echo $GLOBALS["config"]["default_site_name"]; ?></a></h1>
    <ul class="headerMenu">
      <li><a href="<?php echo BASE_URL; ?>"><?php echo _('Notebook'); ?></a></li>
      <li><a href="<?php echo BASE_URL; ?>/index.php?content=content/trash.php"><?php echo _('Trash'); ?></a></li>
      <li><a href="<?php echo BASE_URL; ?>/index.php?content=content/settings.php"><?php echo _('Settings'); ?></a></li>
      <li class="search">
        <form>
          <input name="search" type="text" value="<?php echo _('Search'); ?>..." onclick="this.select()" />
        </form>
      </li>
    </ul>
  </div>
  <!-- close header -->
  <?php require BASE_DIR.'/'.$content; ?>
</div>
<!-- close container -->
</body>
</html>