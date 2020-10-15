
<div id="contentMenu">
    <ul>
        <li><a href=""><?php echo _('Search Results'); ?></a></li>
    </ul>
</div>

<div class="clear"></div>

<div id="search">

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
</div>

<?php
    $search_string = '%'.str_replace("*","%",$_GET['search']).'%';
        
	$tabsProcessed = array();

	// Select all the matching tabs:
	if ($search_string == '%%')
		$queryTabs = mysql_query("
			SELECT tabs.tab_id as tab_id, tabs.name as tab_name, tabs.position as tab_position
			FROM tabs as tabs
			WHERE tabs.name like '$search_string'
			AND tabs.trash = 0
			ORDER BY tab_position
		");
	if ($search_string != '%%')
		$queryTabs = mysql_query("
			SELECT distinct tabs.tab_id as tab_id, tabs.name as tab_name, tabs.position as tab_position
			FROM  tabs
				 LEFT JOIN pages on tabs.tab_id = pages.tab_id
				 LEFT JOIN lists on pages.page_id = lists.page_id
				 LEFT JOIN items on lists.list_id = items.list_id
			WHERE tabs.trash = 0 
			AND tabs.name like '$search_string'
			OR (pages.trash = 0 
				AND tabs.trash = 0 
				AND pages.name like '$search_string')
			OR (lists.trash = 0
				AND pages.trash = 0 
				AND tabs.trash = 0 
				AND lists.name like '$search_string')
			OR (items.trash = 0 
				AND lists.trash = 0 
				AND pages.trash = 0 
				AND tabs.trash = 0 
				AND items.text like '$search_string')
			ORDER BY tab_position
		");
	$tabIteration = 1;
	$numTabs = mysql_num_rows($queryTabs);
	
	// Process the tabs
	while($rowTabs = mysql_fetch_assoc($queryTabs)){
		if (in_array($rowTabs['tab_id'], $tabsProcessed)) continue;

		echo '<div class="searchTree">
				<ul>';
		echo '<li>';
		echo '<a href="index.php?tab='.$rowTabs['tab_id'].'">'.$rowTabs['tab_name'].'</a>';

		// Select the pages on the tab
		if ($search_string == '%%')
			$queryPages = mysql_query("
				SELECT distinct pages.page_id as page_id, pages.name as name, pages.position as page_position
				FROM pages as pages
				WHERE pages.name like '$search_string'
				AND pages.trash = 0
				AND pages.tab_id = ".$rowTabs['tab_id']."
				ORDER BY page_position
			");
		if ($search_string != '%%')
			$queryPages = mysql_query("
				SELECT distinct pages.page_id as page_id, pages.name as name, pages.position as page_position
				FROM  pages
					 LEFT JOIN lists on pages.page_id = lists.page_id
					 LEFT JOIN items on lists.list_id = items.list_id
				WHERE pages.name like '$search_string'
				AND pages.trash = 0
				AND pages.tab_id = ".$rowTabs['tab_id']."
				OR (lists.name like '$search_string' 
					AND lists.trash = 0
					AND pages.trash = 0
					AND pages.tab_id = ".$rowTabs['tab_id'].")
				OR (items.text like '$search_string' 
					AND items.trash = 0
					AND lists.trash = 0
					AND pages.trash = 0
					AND pages.tab_id = ".$rowTabs['tab_id'].")
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
			echo '<a href="index.php?tab='.$rowTabs['tab_id'].'&page='.$rowPages['page_id'].'">'.$rowPages['name'].'</a>';

			// Select lists in on the page:
			if ($search_string == '%%')
				$queryLists = mysql_query("
					SELECT distinct lists.list_id as list_id, lists.name as name, lists.column_id as list_column, lists.position as list_position
					FROM lists as lists
					WHERE lists.name like '$search_string'
					AND lists.trash = 0
					AND lists.page_id = ".$rowPages['page_id']."
					ORDER BY list_column, list_position
				");
			if ($search_string != '%%')
				$queryLists = mysql_query("
					SELECT distinct lists.list_id as list_id, lists.name as name, lists.column_id as list_column, lists.position as list_position
					FROM  lists
						 LEFT JOIN items on lists.list_id = items.list_id
					WHERE lists.name like '$search_string'
					AND lists.trash = 0
					AND lists.page_id = ".$rowPages['page_id']."
					OR (items.text like '$search_string' 
						AND items.trash = 0
						AND lists.trash = 0
						AND lists.page_id = ".$rowPages['page_id'].")
					ORDER BY list_column, list_position
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
				echo '<a href="index.php?tab='.$rowTabs['tab_id'].'&page='.$rowPages['page_id'].'">'.$rowLists['name'].'</a>';

				// Select items in the list:
				$queryItems = mysql_query("
					SELECT id, text, position
					FROM items 
					WHERE text like '$search_string'
					AND trash = 0
					AND list_id = ".$rowLists['list_id']."
					ORDER BY position
					");
				$itemIteration = 1;
				$numItems = mysql_num_rows($queryItems);
				if ($numItems == 0) echo '</li>';
				// Print the items in trash
				while($rowItems = mysql_fetch_assoc($queryItems)){
					if ($itemIteration == 1) echo '<ul>';
					echo '<li>';
					echo '<a href="index.php?tab='.$rowTabs['tab_id'].'&page='.$rowPages['page_id'].'">'.strip_html_tags($rowItems['text']).'</a>';
					echo '</li>';
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
		$tabsProcessed[] = $rowTabs['tab_id'];
		$tabIteration++;
	}  // close items in loop

	if (count($tabsProcessed) == 0) echo '
		<div class="noResults">
			'._('No matches found').'.
		</div>
		';
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

	</div>
<script type="text/javascript" src="min/?f=lib/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="min/?f=lib/jquery/jquery-ui-1.8.5.min.js"></script>
<script type="text/javascript" src="min/?f=lib/script.js"></script>
<script type="text/javascript" src="min/?f=lib/gettext.js"></script>