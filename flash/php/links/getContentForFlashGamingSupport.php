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
            redirectBrowser('/?linkname=' . $ar[count($ar) - 1]);
       }
  ?>
      <table width="590">
      	<tr>
      		<td class="primaryContentContainerHeader">
      			<p align="justify"><a href="/php/links/getContentForFlashGamingSupport.php" target="_self">Flash Gaming Support</a></p>
      		</td>
      	</tr>
		<tr>
			<td valign="top">
				<p align="justify">Here are some various Flash Gaming Support files you may download at your liesure.</p>
				<p align="justify">Keep in mind the tool that was used to create this Flash Clip is proprietary however those who recognize the file type will certainly be able to use it.</p>
				<p align="justify"><a href="/app/flash/downloads/Login+Register_templateClip.zip" target="_blank">Login/Register Movie Clip with ColdFusion MX 7 Code (338 Kb)</a></p>
			</td>
		</tr>
	</table>

 </body>
</html>
