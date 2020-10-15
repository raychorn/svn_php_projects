<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
       include_once("php\includes\documentHeader.php");
	   print_r(docHeader('', false));
  ?>
  <link href="/commonStyles.css" rel="stylesheet" type="text/css">
  <script language="JavaScript1.2" type="text/javascript">
  		var btnIds = ['btn_menuBomb', 'btn_menuBook'];
		
		function resetAllButtons() {
			var i = -1;
			var oBtn = -1;
			for (i = 0; i < btnIds.length; i++) {
				oBtn = document.getElementById(btnIds[i]);
				if (!!oBtn) {
					oBtn.disabled = false;
					oBtn.style.border = 'thin solid silver';
				}
			}
		}
		function clickBombButton() {
			var oBtnBomb = document.getElementById('btn_menuBomb');
			var oDiv = document.getElementById('div_siteMenuContent');
			if ( (!!oDiv) && (!!oBtnBomb) ) {
				oDiv.innerHTML = '<?php print_r(flashContent('flashmainbombmenuclip', 200, 1000, '/app/flash/flash/menu/flashmainbombmenuclip.swf', '#164f9f')); ?>';
				resetAllButtons();
				oBtnBomb.disabled = true;
				oBtnBomb.style.border = 'medium solid yellow';
			}
		}

		function clickBookButton() {
			var oBtnBook = document.getElementById('btn_menuBook');
			var oDiv = document.getElementById('div_siteMenuContent');
			if ( (!!oDiv) && (!!oBtnBook) ) {
				oDiv.innerHTML = '<?php print_r(flashContent('flashmainbookmenuclip', 200, 1000, '/app/flash/flash/menu/flashmainbookmenuclip.swf', '#164f9f')); ?>';
				resetAllButtons();
				oBtnBook.disabled = true;
				oBtnBook.style.border = 'medium solid yellow';
			}
		}
  </script>
 </head>
 <body>
	<?php print(handleNoScript()); ?>
	<a id="anchor_imageLogoRight" name="anchor_imageLogoRight" style="position: absolute; top: -15px; left: 700px;"></a>
	<div id="table_outerContentWrapper" style="position: absolute; width: 900px; top: 0px; left: 0px;">
		<div class="ContentWrapper">
			<div id="divSiteMenuContent" class="twoColumnLeft">
				<div id="div_ezajax_3d_logo"><button id="btn_menuBomb" class="<?php print(buttonClassNameForBrowser()); ?>" onClick="clickBombButton(); return false;">Menu 1</button>&nbsp;<button id="btn_menuBook" class="<?php print(buttonClassNameForBrowser()); ?>" onClick="clickBookButton(); return false;">Menu 2</button></div>
				<div id="div_siteMenuContent"></div>
			</div>
			<div class="twoColumnRight" style="<?php if (!ezIsBrowserIE()) { print_r('margin-left: 30px;'); }; ?>">
				<img src="/app/flash/images/flashSiteHeader.jpg" width="780" height="100" border="0">
				<br>
				<div id="div_primaryContentContainer">
					<iframe name="contentFrame" id="contentFrame" frameborder="0" scrolling="Auto" width="780" height="600" style="background: #164f9f" src="/php/links/<?php if ($_REQUEST['linkname'] != '') { print_r($_REQUEST['linkname']); } else { print_r('getContentForSiteBackground.php'); }; ?>"></iframe>
				</div>
			   <?php
					$ezClusterLink = "ezCluster&#8482";
					$ezAJAXLink = "ezAJAX&#8482";
					$today = getdate();
					$todayYYYY = $today["year"];
					$siteAdvice = ((!ezIsBrowserIE()) ? '<b><i>This site is best when viewed using IE 7.x (1024x768) resolution.</i></b>' : '');
					$_poweredHTML = '<p align="justify"><small>This Site has been redesigned to use PHP 5.2.0 instead of ColdFusion MX 7.&nbsp;Our apologies for any problems you may have encountered during our redesign.&nbsp;&nbsp;' . $siteAdvice . '&nbsp;The contents of this Site are protected under U.S. and International Copyright Laws. &copy 1990-' . $todayYYYY . ' Hierarchical Applications Limited, All Rights Reserved.</small></p>';
					print_r($_poweredHTML);
			   ?>
			</div>
		</div>
	</div>
	<script language="javascript1.2" type="text/javascript">
		resetAllButtons();
		clickBookButton();
	</script>
 </body>
</html>
