<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
       include_once("includes\documentHeader.php");
	   print_r(docHeader('', 'flashx.ez-ajax.com'));
  ?>
 </head>
 <body>
		<noscript>You must enable JavaScript to use this site.<br>Please adjust your browser's settings to enable JavaScript or use a browser that supports JavaScript.<br>
		<a href="http://flashx.ez-ajax.com" target="_blank">flashx.ez-ajax.com</a>
		</noscript>

	<div id="table_outerContentWrapper" style="position: absolute; width: 900px; top: 0px; left: 0px;">
		<div class="ContentWrapper">
			<div class="twoColumnLeft">
				+++ Menu Goes Here +++
			</div>
			<div class="twoColumnRight" style="<?php if (!ezIsBrowserIE()) { print_r('margin-left: 30px;'); }; ?>">
				<img src="images/flashx.ez-ajax.com logo2a.jpg" width="869" height="116" border="0" title="Your Premier Flash Exchange - Upload your Flash and download thousands whenever you wish.">
				<div id="div_primaryContentContainer">
					<iframe name="contentFrame" id="contentFrame" frameborder="0" scrolling="Auto" width="780" height="600" style="background: #164f9f" src="/links/<?php if ($_REQUEST['linkname'] != '') { print_r($_REQUEST['linkname']); } else { print_r('homePage.php'); }; ?>"></iframe>
				</div>
			   <?php
					$today = getdate();
					$todayYYYY = $today["year"];
					$siteAdvice = ((!ezIsBrowserIE()) ? '<b><i>This site is best when viewed using IE 7.x (1024x768) resolution.</i></b>' : '');
					$_poweredHTML = '<p align="justify"><small>' . $siteAdvice . '&nbsp;The contents of this Site are protected under U.S. and International Copyright Laws. &copy 1990-' . $todayYYYY . ' Hierarchical Applications Limited, All Rights Reserved.</small></p>';
					print_r($_poweredHTML);
			   ?>
			</div>
		</div>
	</div>
 </body>
</html>
