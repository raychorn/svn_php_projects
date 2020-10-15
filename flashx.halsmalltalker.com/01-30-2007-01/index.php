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

	<script type="text/javascript">
		var so = new SWFObject("/flash/main/ContentContainer.swf", "ContentContainer", "100%", "100%", "8", "#164f9f");
	//	so.addVariable("flashVarText", "this is passed in via FlashVars for example only");
		so.write("flashcontent");
	</script>
 </body>
</html>
