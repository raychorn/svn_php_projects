<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>EzAJAX Downloads</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<style type="text/css">
<!--
body {
	font: 100% Verdana, Arial, Helvetica, sans-serif;
	background: #FFFFFF;
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	text-align: center; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
	color: #000000;
}

/* Tips for Elastic layouts 
1. Since the elastic layouts overall sizing is based on the user's default fonts size, they are more unpredictable. Used correctly, they are also more accessible for those that need larger fonts size since the line length remains proportionate.
2. Sizing of divs in this layout are based on the 100% font size in the body element. If you decrease the text size overall by using a font-size: 80% on the body element or the #container, remember that the entire layout will downsize proportionately. You may want to increase the widths of the various divs to compensate for this.
3. If font sizing is changed in differing amounts on each div instead of on the overall design (ie: #sidebar1 is given a 70% font size and #mainContent is given an 85% font size), this will proportionately change each of the divs overall size. You may want to adjust based on your final font sizing.
*/
.oneColElsCtrHdr #container {
	width: 46em;  /* this width will create a container that will fit in an 800px browser window if text is left at browser default font sizes */
	background: #0066FF;
	margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
	border: 1px solid #000000;
	text-align: left; /* this overrides the text-align: center on the body element. */
}
.oneColElsCtrHdr #header { 
	background: #0066FF; 
	padding: 0 10px 0 20px;  /* this padding matches the left alignment of the elements in the divs that appear beneath it. If an image is used in the #header instead of text, you may want to remove the padding. */
} 
.oneColElsCtrHdr #header h1 {
	margin: 0; /* zeroing the margin of the last element in the #header div will avoid margin collapse - an unexplainable space between divs. If the div has a border around it, this is not necessary as that also avoids the margin collapse */
	padding: 10px 0; /* using padding instead of margin will allow you to keep the element away from the edges of the div */
}
.oneColElsCtrHdr #mainContent {
	padding: 0 20px; /* remember that padding is the space inside the div box and margin is the space outside the div box */
	background: #FFFF66;
}
.oneColElsCtrHdr #footer { 
	padding: 0 10px; /* this padding matches the left alignment of the elements in the divs that appear above it. */
	background: #0066FF;
} 
.oneColElsCtrHdr #footer p {
	margin: 0; /* zeroing the margins of the first element in the footer will avoid the possibility of margin collapse - a space between divs */
	padding: 10px 0; /* padding on this element will create space, just as the the margin would have, without the margin collapse issue */
	font-size: 9px;
	color: #FFFF00;
}
.caption { margin: 5px; padding: 5px; border: none; font-size:90%; color: black } 
-->
</style></head>

<body class="oneColElsCtrHdr">
<?php include_once('includes/core.php'); ?>

<div id="container">
  <div id="header">
    <div class="caption left">
<img src="images/ezAJAX Logo 08-17-2006a (125x125).gif" align="top"/> 
<font size="+3">EzAJAX Download Site</font></div> 
  <!-- end #header --></div>
  <div id="mainContent">
    <h1> EzAJAX 0.94 (04-05-2008) </h1>
    <?php 
	//	echo "BEGIN:<br/>";
		
		$_fpath = $_SERVER['SCRIPT_FILENAME'];
		$_toks = splitString($_fpath,'/');
		
	//	echo "\$_fpath=[$_fpath]<br/>";
		array_pop($_toks);
		$_p = join_list($_toks,"/").'/downloads';
		$ii = strpos($_toks[0],":");
	//	echo "\$ii=[$ii]<br/>";
		if ($ii == false) {
			$_p = '/'.$_p;
		}
	//	echo "\$_p=[$_p]<br/>";
	//	do_dump($_toks);
		$_files = glob($_p.'/*.exe');
		foreach ( $_files as $item ) {
			$toks = splitString($item,'/');
			$fname = array_pop($toks);
	//		echo "\$fname=[$fname]<br/>";
			echo '<p><a href="downloads/'.$fname.'" target="_blank">'.$fname.'</a></p>';
		}
	//	do_dump($_files);

	//	do_dump($_REQUEST);
	//	do_dump($_SERVER);

	//	echo "END!<br/>";
	?>
    <h2>EzAJAX PAD Files</h2>
    <?php 
	//	echo "BEGIN:<br/>";
		
		$_fpath = $_SERVER['SCRIPT_FILENAME'];
		$_toks = splitString($_fpath,'/');
		
	//	echo "\$_fpath=[$_fpath]<br/>";
		array_pop($_toks);
		$_p = join_list($_toks,"/").'/pad';
		$ii = strpos($_toks[0],":");
	//	echo "\$ii=[$ii]<br/>";
		if ($ii == false) {
			$_p = '/'.$_p;
		}
	//	echo "\$_p=[$_p]<br/>";
	//	do_dump($_toks);
		$_files = glob($_p.'/*.xml');
		foreach ( $_files as $item ) {
			$toks = splitString($item,'/');
			$fname = array_pop($toks);
	//		echo "\$fname=[$fname]<br/>";
			$t = splitString($fname,'-');
			$f = join_list($t," ");
			echo '<p><a href="pad/'.$fname.'" target="_blank">'.$f.'</a></p>';
		}
	//	do_dump($_files);

	//	do_dump($_REQUEST);
	//	do_dump($_SERVER);

	//	echo "END!<br/>";
	?>
    <h2>Order your Copy of EzAJAX today !</h2>
    <p><a href="http://www.regnow.com/softsell/nph-softsell.cgi?items=14605-1" target="_blank">Click Here to place your order.</a></p>
    <p>&nbsp;</p>
	<!-- end #mainContent --></div>
  <div id="footer">
    <p>&copy; Copyright 2008, Ez-AJAX.Com and EzAJAX.US, All Rights Reserved.  </p>
  <!-- end #footer --></div>
<!-- end #container --></div>
<p><small>Get your Low-Cost Domain Names and Web Hosting from <a href="http://www.EzCheapSites.Com" target="_blank">EzCheapSites.Com</a></small></p>
</body>
</html>
