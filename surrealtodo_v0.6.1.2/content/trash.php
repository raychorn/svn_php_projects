<div id="contentMenu">
    <ul>
        <li><a href="" class="emptyTrash"><?php echo _('Empty Trash'); ?></a></li>
        <li><a href="" class="checkAll"><?php echo _('Select All'); ?></a></li>
        <li><a href="" class="deleteSelected"><?php echo _('Delete Selected'); ?></a></li>
        <li><a href="" class="restoreSelected"><?php echo _('Restore Selected'); ?></a></li>
    </ul>
</div>

<div id="trash">
<div class="legend">
    <h1><?php echo _('Legend'); ?></h1>
    <ul>
    <li><?php echo _('Folder'); ?>
      <ul>
        <li><?php echo _('Page'); ?>
          <ul>
            <li><?php echo _('List'); ?>
              <ul>
                <li><?php echo _('Item'); ?></li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    </ul>
    <p><?php echo _('Black - Parent (not deleted)'); ?></p>
    <p class="deleted"><?php echo _('Red - Deleted Object'); ?></p>
    <p class="deletedChild"><?php echo _('Blue - Deleted Child Object'); ?></p>
</div>
<?php
	$tabsProcessed = array();

	// Select tabs in trash:
	$queryTabs = mysql_query("
		SELECT distinct tabs.tab_id as tab_id, tabs.name as tab_name, tabs.trash as trash, tabs.position as tab_position
		FROM  tabs FORCE INDEX (tab)
			 LEFT JOIN pages on tabs.tab_id = pages.tab_id
			 LEFT JOIN lists on pages.page_id = lists.page_id
			 LEFT JOIN items on lists.list_id = items.list_id
		WHERE tabs.trash = 1 OR tabs.trash = 9
		OR (pages.trash = 1 AND tabs.trash = 0)
		OR (lists.trash = 1 AND tabs.trash = 0)
		OR (items.trash = 1 AND tabs.trash = 0)
		ORDER BY tab_position
	");
	$tabIteration = 1;
	$numTabs = mysql_num_rows($queryTabs);
	
	// Process the items in trash
	while($rowTabs = mysql_fetch_assoc($queryTabs)){
		if (in_array($rowTabs['tab_id'], $tabsProcessed)) continue;
		buildTrashTree($rowTabs, $numTabs, $tabsProcessed, $tabIteration, "items");
		$tabsProcessed[] = $rowTabs['tab_id'];
		$tabIteration++;
	}  // close items in trash loop

if (count($tabsProcessed) == 0) echo '
	<div class="noResults">
		'._('Trash is Empty').'.
	</div>
	';
function buildTrashTree($rowTabs = array(), $numTabs, $tabsProcessed, $tabIteration, $level = "") {
		echo '<div class="trashTree">
				<ul>';
		echo '<li>';
		if ($rowTabs['trash'] == 1) echo '<input name="" type="checkbox" value="Tab-'.$rowTabs['tab_id'].'" class="trashCheckbox" />';
		if ($rowTabs['trash'] == 1) echo '<span class="deleted">';
		echo $rowTabs['tab_name'];
		if ($rowTabs['trash'] == 1) echo '</span>';

		// Select the pages on the tab
		if ($rowTabs['trash'] == 1) 
			$queryPages = mysql_query("
				SELECT distinct pages.page_id as page_id, pages.name as name, pages.trash as trash, pages.position as page_position
				FROM pages 
				WHERE pages.tab_id = ".$rowTabs['tab_id']."
				ORDER BY page_position
				");
		if ($rowTabs['trash'] == 0) 
			$queryPages = mysql_query("
				SELECT distinct pages.page_id as page_id, pages.name as name, pages.trash as trash, pages.position as page_position
				FROM pages 
					LEFT JOIN lists on pages.page_id = lists.page_id
					LEFT JOIN items on lists.list_id = items.list_id
				WHERE pages.tab_id = ".$rowTabs['tab_id']."
				AND pages.trash = 1 
				OR (pages.tab_id = ".$rowTabs['tab_id']." AND lists.trash = 1)
				OR (pages.tab_id = ".$rowTabs['tab_id']." AND items.trash = 1)
				ORDER BY page_position
				");
		$pageIteration = 1;
		$numPages = mysql_num_rows($queryPages);
		$pagesProcessed = array();

		// Print the pages beloging to tab
		while($rowPages = mysql_fetch_assoc($queryPages)){
			if (in_array($rowPages['page_id'], $pagesProcessed)) continue;
			if ($pageIteration == 1) echo '<ul>';
			echo '<li>';
			if ($rowPages['trash'] == 1) echo '<input name="" type="checkbox" value="Page-'.$rowPages['page_id'].'" class="trashCheckbox" />';
			if ($rowPages['trash'] == 1) echo '<span class="deleted">';
			if ($rowPages['trash'] == 0 && $rowTabs['trash'] == 1) echo '<span class="deletedChild">';
			echo $rowPages['name'];
			if ($rowPages['trash'] == 1) echo '</span>';

			// Select lists in on the page:
			if ($rowPages['trash'] == 1 or $rowTabs['trash'] == 1)
				$queryLists = mysql_query("
					SELECT distinct lists.list_id as list_id, lists.name as name, lists.trash as trash, lists.position as list_position
					FROM lists
					WHERE lists.page_id = ".$rowPages['page_id']."
					ORDER BY list_position
				");
			elseif ($rowPages['trash'] == 0)
				$queryLists = mysql_query("
					SELECT distinct lists.list_id as list_id, lists.name as name, lists.trash as trash, lists.position as list_position
					FROM lists
						LEFT JOIN items on lists.list_id = items.list_id
					WHERE lists.page_id = ".$rowPages['page_id']."
					AND lists.trash = 1
					OR (lists.page_id = ".$rowPages['page_id']." AND items.trash = 1)
					ORDER BY list_position
				");
			$listIteration = 1;
			$numLists = mysql_num_rows($queryLists);
			if ($numLists == 0) echo '</li>';
			$listsProcessed = array();
			
			// Print the lists in trash
			while($rowLists = mysql_fetch_assoc($queryLists)){
			if (in_array($rowLists['list_id'], $listsProcessed)) continue;
				if ($listIteration == 1) echo '<ul>';
				echo '<li>';
				if ($rowLists['trash'] == 1) echo '<input name="checkList" type="checkbox" value="List-'.$rowLists['list_id'].'" class="trashCheckbox" />';
				if ($rowLists['trash'] == 1) echo '<span class="deleted">';
				if ($rowLists['trash'] == 0 && $rowPages['trash'] == 1 or $rowTabs['trash'] == 1) echo '<span class="deletedChild">';
				echo $rowLists['name'];
				if ($rowLists['trash'] == 1) echo '</span>';

				// Select items in the list:
				$queryItems = mysql_query("
					SELECT id, text, trash, position
					FROM items 
					WHERE list_id = ".$rowLists['list_id']."
					ORDER BY position");
				$itemIteration = 1;
				$numItems = mysql_num_rows($queryItems);
				if ($numItems == 0) echo '</li>';
				// Print the items in trash
				while($rowItems = mysql_fetch_assoc($queryItems)){
					if ($itemIteration == 1) echo '<ul>';
					echo '';
					if ($rowItems['trash'] == 1) echo '<li><input name="" type="checkbox" value="Item-'.$rowItems['id'].'" class="trashCheckbox" />';
					if ($rowItems['trash'] == 1) echo '<span class="deleted">'.strip_html_tags($rowItems['text']).'</span></li>';
					if ($rowItems['trash'] == 0 && ($rowLists['trash'] == 1 or $rowPages['trash'] == 1 or $rowTabs['trash'] == 1)) 
						echo '<li><span class="deletedChild">'.strip_html_tags($rowItems['text']).'</span></li>';
					if ($itemIteration == $numItems) echo '</ul></li>';
					$itemIteration++;
				} // close item loop
				if ($listIteration == $numLists) echo '</ul></li>';
				$listsProcessed[] = $rowLists['list_id'];
				$listIteration++;
			} // close list loop
			if ($pageIteration == $numPages) echo '</ul></li>';
			$pagesProcessed[] = $rowPages['page_id'];
			$pageIteration++;
		} // close page loop
		if ($tabIteration == $numTabs) echo '</ul>';
		echo '</div> <!-- close trashTree-->';
	
}  // close function buildTrashTree

/**
 * Remove HTML tags, including invisible text such as style and
 * script code, and embedded objects.  Add line breaks around
 * block-level tags to prevent word joining after tag removal.
 */
function strip_html_tags( $text )
{
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text );
    $text = strip_tags( $text );
	if (strlen($text) > 100) $text = substr($text,0,100).'...('._('text trimmed').')';
	return $text;
}
?>
</div> <!-- close trash-->
<script type="text/javascript" src="min/?f=lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="min/?f=lib/jquery/jquery-ui-1.8.5.min.js"></script>
<script type="text/javascript" src="min/?f=lib/script.js"></script>
<script type="text/javascript" src="min/?f=lib/gettext.js"></script>

