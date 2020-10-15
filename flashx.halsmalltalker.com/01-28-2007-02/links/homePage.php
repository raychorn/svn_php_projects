<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
       include_once("..\includes\documentHeader.php");
	   print_r(docHeader('../..'));
  ?>
 </head>
 <body>
  <?php
       if (stristr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) == false) {
          	$ar = splitStringToArray($_SERVER['SCRIPT_NAME']);
				$scriptName = $ar[count($ar) - 1];
            redirectBrowser('/?linkname=' . $scriptName);
       }
  ?>
      <table width="590">
      	<tr>
      		<td class="primaryContentContainerHeader">
      			<p align="justify"><a href="/links/<?php print_r($scriptName); ?>" target="_self">Home Page</a></p>
      		</td>
      	</tr>
		<tr>
			<td valign="top">
				<p align="justify">This is the home page...</p>
			</td>
		</tr>
	</table>
 </body>
</html>
