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
      			<p align="justify"><a href="/php/links/getContentForFlash8.php" target="_self"><img src="/app/flash/images/undo.gif" width="33" height="18" border="0" title="Back to the Previous Page."></a>&nbsp;<a href="/php/links/getContentForFlash8Promo.php" target="_self">Flash 8 Promo for www.ez-ajax.com</a></p>
      		</td>
      	</tr>
		<tr>
			<td valign="top" bgcolor="#0060E0">
				<iframe frameborder="0" scrolling="No" width="450" height="250" src="/app/flash/ez-ajax-promo/ezajax_logo.html?nocache=<?php print_r(rand(1, 32767)); ?>"></iframe>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<p align="justify">This Flash 8 Promo was created to promote the ezAJAX Site.  It was coded using a proprietary technique that allows for Rapid Development of Flash components that can be quite complex using very little development time.  As you can see this is a very professional looking Flash 8 component.<br><br>The games posted to this site were also created using this same proprietary programming technique.  Programmers and non-programmers alike can use this same proprietary programming technique with little effort and very little training.</p>
			</td>
		</tr>
	</table>

 </body>
</html>
