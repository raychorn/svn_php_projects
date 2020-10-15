
<div id="notebook">
    <div id="tabs">
        <ul id="tabRow">
	        <!-- The jQuery generated tabs go here -->
        </ul> <!-- close tabRow -->
    <div id=icons_TabRow>
    	<abbr title="<?php echo _('New Tab'); ?>" class="notebookIcons newTab"></abbr>
        <abbr title="<?php echo _('New List'); ?>" class="notebookIcons newList"></abbr>
    </div>
    </div> <!-- close tabs -->
    <div class="clear"></div>
    
    <div id="tabContent">
        <div id="contentHolder">
            <!-- The AJAX fetched content goes here -->
        </div> <!-- close contentHolder -->
    </div> <!-- close tabContent -->

</div>  <!-- close notebook -->
<div id="pages">
    <ul id="pageList">
        <!-- The jQuery generated pages go here -->
    </ul> <!-- close pageList -->
</div> <!-- close pages -->

<!-- These divs are used as the base for the confirmation jQuery UI POPUP. Hidden by CSS. -->

<div id="dialog-confirm-delete-list" class="dialog" title="<?php echo _('Delete Entire List?'); ?>"><?php echo _('All Items in this List will be deleted.'); ?></div>
<div id="dialog-confirm-delete-page" class="dialog" title="<?php echo _('Delete Entire Page?'); ?>"><?php echo _('All Lists and Items on this Page will be deleted.'); ?></div>
<div id="dialog-confirm-delete-tab" class="dialog" title="<?php echo _('Delete Entire Tab?'); ?>"><?php echo _('All Pages, Lists and Items on this Tab will be deleted.'); ?></div>

<?php
/*  Look for a tab and page to be specified in the url and generate javascript needed to click the tab or page.
	If the tab or page is not specified generate javascript to click on the first tab or page in the list.
*/
if (isset($_GET['tab'])){
	$tabID = $_GET['tab']
?>
		<script type="text/javascript">
			function clickTab(){
			$(document).find("#Tab-<?php echo $tabID; ?>").find("a.tab").click();
			}
		</script>
<?php
	if (isset($_GET['page'])){
		$pageID = $_GET['page']
/*
		The below script is only loaded if a specific page was listed in the url.  In order for subsequent tab changes to properly 
		load the page for that tab, a counter is started so that it only uses the page from the url for the first time.
*/
?>
		<script type="text/javascript">
			var pageCount = 0;  
			function clickPage(){
			pageCount++;
			if (pageCount >1) $(document).find('.page').eq(0).click();
			else $(document).find("a#Page-<?php echo $pageID; ?>").click();
			}
		</script>
<?php
	}
	else {
	?>
			<script type="text/javascript">
				function clickPage(){
					$(document).find('.page').eq(0).click();
				}
			</script>
	<?php
	}
}
else {
?>
		<script type="text/javascript">
			function clickTab(){
				$(document).find('.tab').eq(0).click();
			}
			function clickPage(){
				$(document).find('.page').eq(0).click();
			}
		</script>
<?php
}


require "./content/menus.php"; 

/*  Check for newer version

	** Needs to be rewritten to account for installations behind a firewall
	
$webVersion = file_get_contents('http://www.getsurreal.com/surrealtodo/version.php?version='.APP_VERSION); 
if ($webVersion !== false) {
   if ($webVersion == "2") {
	   echo '<div id="newVersion"><a href="http://sourceforge.net/projects/surrealtodo">New version available.</a></div>';
   }
}

*/
?>
<script type="text/javascript" src="min/?f=lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="min/?f=lib/jquery/jquery-ui-1.8.5.min.js"></script>
<script type="text/javascript" src="min/?f=lib/jquery/jquery.livequery.js"></script>
<script type="text/javascript" src="min/?f=lib/jquery/plugin/jquery.jeegoocontext.min.js"></script>
<script type="text/javascript" src="min/?f=lib/tabs-script.js"></script>
<script type="text/javascript" src="min/?f=lib/script.js"></script>
<script type="text/javascript" src="min/?f=lib/gettext.js"></script>
<script type="text/javascript" src="min/?f=lib/context-menu.js"></script>
<script type="text/javascript" src="lib/tiny_mce/tiny_mce.js"></script>