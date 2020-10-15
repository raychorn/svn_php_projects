<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
       include_once("includes\documentHeader.php");
	   print_r(docHeader('', 'flashx.ez-ajax.com'));
  ?>
  <script language="JavaScript1.2" type="text/javascript" src="js/00_constants.js"></script>
  <script language="javascript1.2" type="text/javascript" src="js/01_clientWid$.js"></script>
  <script language="JavaScript1.2" type="text/javascript" src="js/01_clientHt$.js"></script>
  <script language="JavaScript1.2" type="text/javascript" src="js/01_getViewportWidth.js"></script>
  <script language="JavaScript1.2" type="text/javascript" src="js/01_getViewportHeight.js"></script>
  <script language="javascript1.2" type="text/javascript">
  		function resizeFlash() {
			try {
				var oTD = document.getElementById('table_td_movie');
				if (!!oTD) {
					oTD.width = ezGetViewportWidth();
					oTD.height = ezGetViewportHeight();
		//			alert('oTD.width = [' + oTD.width + ']' + ', oTD.height = [' + oTD.height + ']');
				}
			} catch (e) { alert(222); };
		}
		
  		window.onresize = function() { resizeFlash() };
  </script>
 </head>
 <body>
		<noscript>You must enable JavaScript to use this site.<br>Please adjust your browser's settings to enable JavaScript or use a browser that supports JavaScript.<br>
		<a href="http://flashx.ez-ajax.com" target="_blank">flashx.ez-ajax.com</a>
		</noscript>

	<script language="javascript1.2" type="text/javascript">
		var version = deconcept.SWFObjectUtil.getPlayerVersion();
		if (document.getElementById && (version['major'] > 0)) {
			document.write('<?php print_r(flashContent('ContentContainer', '100%', '100%', '/flash/main/ContentContainer.swf?flashroot=/flash/main/', '#164f9f')); ?>');
			resizeFlash();
		} else {
			document.write('<div id="flashcontent" style="position: absolute; width: 100%; height: 100%; z-index: 200;">');
			document.write('<strong>You need to upgrade your Flash Player</strong>');
			document.write('This is replaced by the Flash content. ');
			document.write('Place your alternate content here and users without the Flash plugin or with ');
			document.write('Javascript turned off will see this. Content here allows you to leave out <code>noscript</code> ');
			document.write('tags. Include a link to <a href="/flash/main/ContentContainer.html">bypass the detection</a> if you wish.');
			document.write('</div>');
		}
	</script>
	
 </body>
</html>
