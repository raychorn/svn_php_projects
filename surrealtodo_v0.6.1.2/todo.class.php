<?php

// Load all the application config settings
$query	= mysql_query("SELECT name, value FROM config");
while($row = mysql_fetch_array($query)){
	$GLOBALS["config"][ $row["name"] ] = $row["value"];
}

$locale = $GLOBALS["config"]["locale"];
bindtextdomain($locale, './locale');
bind_textdomain_codeset($locale, 'UTF-8');
textdomain($locale);
setlocale(LC_ALL,$locale);

/* Defining the ToDo class */

class ToDo{
	
	/* An array that stores the item data: */
	
	private $data;
	
	/* The constructor */
	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
	
	/*
		This is an in-build "magic" method that is automatically called 
		by PHP when we output the Item objects with echo. 
	*/
		
	public function __toString(){
		// The string we return is outputted by the echo statement
		
		if ( $this->data['date_created'] == '') {
			$date_created =  date($GLOBALS["config"]["date_format"].' '.$GLOBALS["config"]["time_format"]);
		}
		else 
			$date_created = date($GLOBALS["config"]["date_format"].' '.$GLOBALS["config"]["time_format"], strtotime($this->data['date_created']));

		
		if ( $this->data['date_completed'] == '') {
			$date_completed = '';
			return '
					<li id="Item-'.$this->data['id'].'" class="item">'
						.ToDo::showDate($GLOBALS["show_item_date"],$this->data).'
						<div class="text">'.$this->data['text'].'</div>
						<div class="dateCreated">'.$date_created.'</div>
						<div class="dateCompleted">'.$date_completed.'</div>
					</li>';
		}
		else { 
			$date_completed = date($GLOBALS["config"]["date_format"].' '.$GLOBALS["config"]["time_format"], strtotime($this->data['date_completed']));
			$build_item = '
				<li id="Item-'.$this->data['id'].'" class="item">'
					.ToDo::completedItemClasses()
					.ToDo::showDate($GLOBALS["show_item_date"],$this->data)
					.$this->data['text'].'</div>
					<div class="dateCreated">'.$date_created.'</div>
					<div class="dateCompleted">'.$date_completed.'</div>
				</li>';
				
		return $build_item;
		}
	}
	
	private static function showDate($show_item_date,$data) {
		if ($data['date_created'] == '') $data['date_created'] = date($GLOBALS["config"]["date_format"]);
		else $data['date_created'] = date($GLOBALS["config"]["date_format"], strtotime($data['date_created']));
	if ($show_item_date) return '<span class="showDate">'.$data['date_created'].'</span>';	
	}
	
	private static function completedItemClasses(){
		$itemClasses = '';
		if (in_array($GLOBALS["config"]["completed_item"],array(4,5,6,12,7,13,14,15))) $itemClasses .= '<span class="checkmark"></span>';
		$itemClasses .= '<div class="text ';
		if (in_array($GLOBALS["config"]["completed_item"],array(1,3,5,9,7,13,11,15))) $itemClasses .= 'strikethrough';
		if (in_array($GLOBALS["config"]["completed_item"],array(2,3,6,10,7,11,14,15))) $itemClasses .= ' italic';
		if (in_array($GLOBALS["config"]["completed_item"], array(8,9,10,12,11,13,14,15))) $itemClasses .= ' gray';
		$itemClasses .= '">';
		return $itemClasses;
	}
	/*
		The following are static methods. These are available
		directly, without the need of creating an object.
	*/
	
	
	/*
		The tabs method queries for all the tab
		tabs in the database and returns it JSON encoded.
	*/
		
	public static function tabs(){
		
		// Select all the tabs:
		$query = mysql_query("
			SELECT * 
			FROM tabs 
			WHERE trash = 0 
			ORDER BY position ASC
			");
		
		$tabs = array();
		
		// Filling the $tabs array with tab objects:
		
		while($row = mysql_fetch_assoc($query)){
			$tabs[] = $row;
		}
		array_walk($tabs, 'ToDo::formatDate');
		echo json_encode($tabs);
		
	exit;

	} // close tabs method

	/*
		The formatDate method goes throgh the array and formats the date as specified in the application settings
	*/
	
	private static function formatDate(&$item) {
		if ($item['date_created']) 
			$item['date_created'] = date($GLOBALS["config"]["date_format"].' '.$GLOBALS["config"]["time_format"], strtotime($item['date_created']));
	}

	/*
		The pages method queries for all the pages
		in the database and returns it JSON encoded.
	*/

	public static function pages($tab_id){

		// Select all the pages:
		$query = mysql_query("
			SELECT * 
			FROM pages 
			WHERE tab_id = $tab_id 
			AND trash = 0
			ORDER BY position ASC
		");
		
		$pages = array();
		
		// Filling the $pages array with new page objects:
		
		while($row = mysql_fetch_assoc($query)){
			$pages[] = $row;
		}
		array_walk($pages, 'ToDo::formatDate');
		echo json_encode($pages);
		
	exit;

	} // close pages method

	public static function lists($page_id){

		// Select all the lists:
		$query = mysql_query("
			SELECT * 
			FROM lists 
			WHERE page_id = $page_id
			AND trash = 0
			ORDER BY column_id, position ASC
		");
		
		$lists = array();
		
		// Filling the $lists array with new list objects:
		
		while($row = mysql_fetch_assoc($query)){
			$lists[] = $row;
		}
		array_walk($lists, 'ToDo::formatDate');
		
		// Select how many columns for page
		$query_columns = mysql_query("
			SELECT columns
			FROM pages
			WHERE page_id = $page_id
		");
		$columns = mysql_fetch_array($query_columns);
		$num_columns = $columns[0];
				
		echo '
			<div id="tabData">	
		';
		
		for ($columns =1; $columns <=$num_columns; ++$columns) {
			
			echo '<ul id="Column-'.$columns.'" class="column column-'.$num_columns.'">';
		
			ToDo::buildList($lists, $columns);

			echo '</ul> <!-- close column -->';

		} // close foreach columns
		
			echo '</div>';
			
		echo "<script type='text/javascript'>
			$(document).ready(function(){
				itemContextMenu();
				listContextMenu();
				tabContextMenu();
				pageContextMenu();	
				$('.settings').nojeegoocontext();  // prevent the contextmenu from working on the Settings Page
				makeItemsSortable();
				makeListsSortable();
		});</script>";
		
		exit;		
	} 

private static function buildList($lists = array(), $columns, $ECHO = true) {
	
		foreach($lists as $i => $value) {
			$GLOBALS["show_item_date"] = $lists[$i]['show_item_date'];
				if($lists[$i]['column_id'] <> $columns) continue;
			
			$listID = $lists[$i]['list_id'];
			$display = '';
			if($lists[$i]['expanded'] == 0) $display = "none";
			
			if ($ECHO) echo '
				<li id="List-'.$listID.'" class="sortableList">
				<ul id="List-'.$listID.'" class="list">
					<div id="List-'.$listID.'" class="listTitle">
						<abbr title="'._('New Item').'" class="notebookIcons newItem" onclick=myCallback(this)></abbr>
						<div class="listName" onclick=myCallback(this)>'.$lists[$i]["name"].'</div>
						<div class="listCreated">'.$lists[$i]["date_created"].'</div>
						<div class="showItemDate">'.$lists[$i]["show_item_date"].'</div>
					</div>  <!-- close listTitle -->
						
					<div id="List-'.$listID.'" class="listBody" style="display:'.$display.'">
		';   //close echo          
	
	
				// Select all the items, ordered by position:
					$query = mysql_query("
						SELECT * 
						FROM items 
						WHERE list_id = $listID 
						AND trash = 0
						ORDER BY position ASC
					");
				
				$items = array();
				
				// Filling the $items array with new item objects:
				
				while($row = mysql_fetch_assoc($query)){
					$items[] = new ToDo($row);
				}
				
				// Looping and outputting the $items array. The __toString() method
				// is used internally to convert the objects to strings:
				
				foreach($items as $item){
					if ($ECHO) echo $item;
				}
		if ($ECHO) echo '
						</div> <!-- close listBody -->
					</ul> <!-- close list -->
				</li> <!-- close sortableList -->
		';
			if ($ECHO) echo "\n\n";
		}  // close foreach lists
	
}

	/*
		The newItem method takes only the text of the item,
		writes to the databse and outputs the new item back to
		the AJAX front-end.
	*/

	public static function newItem($list_id,$show_item_date){
		$GLOBALS["show_item_date"] = intval($show_item_date);
		$posResult = mysql_query("SELECT MAX(position)+1 FROM items WHERE list_id = $list_id");

		$text = _('Double-click to edit');
		
		if(mysql_num_rows($posResult))
			list($position) = mysql_fetch_array($posResult);

		if(!$position) $position = 1;

		mysql_query("INSERT INTO items SET text='$text', position = $position, list_id = $list_id");

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Error inserting item!");
		
		// Creating a new item and outputting it directly:
		
		echo (new ToDo(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text,
			'date_created'=> '',
			'date_completed'=> ''
		)));
		
		exit;
	}

	/*
		The editItem method takes the item id and the new text
		of the item. Updates the database.
	*/
		
	public static function editItem($id, $text){
		
//		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");
		
		mysql_query("	UPDATE items
						SET text='".$text."'
						WHERE id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}
	
	/*
		The markDeleteItem method. Takes the id of the item
		and sets the trash field to 1.
	*/
	
	public static function markDeleteItem($id){
		
		mysql_query("UPDATE items set trash = 1 WHERE id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}

	/*
		The delete method. Takes the id of the item
		and deletes it from the database.
	*/
	
	public static function delete($id){
		
		mysql_query("DELETE FROM items WHERE id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}
	
	/*
		The rearrangeItems method is called when the ordering of
		the items is changed. Takes an array parameter, which
		contains the ids of the items in the new order.
	*/
	
	public static function rearrangeItems($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the item positions en masse:
		
		mysql_query("	UPDATE items SET position = CASE id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}
	
	/*
		The changeList method takes the item id and the new list ID
		of the ToDo. Updates the database.
	*/
		
	public static function changeList($id, $list_id){
		
		if(!$list_id) throw new Exception("Wrong update text!");
		
		mysql_query("	UPDATE items
						SET list_id='".$list_id."'
						WHERE id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	/*
		The taskComplete method takes the item id and the 
		current datetime and Updates the database.
	*/
		
	public static function taskComplete($id, $value){
		
		
		mysql_query("	UPDATE items
						SET date_completed=".$value."
						WHERE id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
			
		$query = mysql_query("SELECT date_completed from items WHERE id=".$id);
		while($row = mysql_fetch_assoc($query)){
			$date_completed = $row['date_completed'];
		}
		
		echo date($GLOBALS["config"]["date_format"].' '.$GLOBALS["config"]["time_format"], strtotime($date_completed));
		
		exit;
	}

	/*
		The newList method creates a new list
		and updates the database.
	*/
		
	public static function newList($page_id){
		
		$listName = _('New List');
		
		mysql_query("INSERT INTO lists SET name='".$listName."', column_id = '1', position = '0', page_id = $page_id");
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
		
		$listID = mysql_insert_id();
		$date_created =  date($GLOBALS["config"]["date_format"].' '.$GLOBALS["config"]["time_format"]);

        echo '
				<li id="List-'.$listID.'" class="sortableList">
					<ul id="List-'.$listID.'" class="list">
						<div id="List-'.$listID.'" class="listTitle">
							<abbr title="'._('New Item').'" class="notebookIcons newItem" onclick=myCallback(this)></abbr>
							<div class="listName" onclick=myCallback(this)>'.$listName.'</div>
							<div class="listCreated">'.$date_created.'</div>
							<div class="showItemDate">0</div>
						</div>  <!-- close listTitle -->
					<div id="List-'.$listID.'" class="listBody">
						</div>  <!-- close listBody -->
					</ul> <!-- close list -->
				</li>
		
		';

		exit;
	}

	/*
		The editList method takes the List id and the new name
		of the list and updates the database.
	*/
		
	public static function editList($id, $text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");

		mysql_query("	UPDATE lists
						SET name='".$text."'
						WHERE list_id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	/*
		The markDeleteList method. Takes the id of the List item
		and sets trash field to 1.
	*/
	
	public static function markDeleteList($id){
		
		mysql_query("UPDATE lists set trash = 1 WHERE list_id=".$id);
		
	}

	/*
		The deleteList method. Takes the id of the List item
		and deletes it from the database.  Then matches all items
		and deletes them from the database.
	*/
	
	public static function deleteList($id){
		
		mysql_query("DELETE FROM lists WHERE list_id=".$id);
		mysql_query("DELETE FROM items WHERE list_id=".$id);
		
	}

	/*
		The rearrangeLists method is called when the ordering of
		the tabs is changed. Takes an array parameter, which
		contains the ids of the tabs in the new order.
	*/
		
	public static function rearrangeLists($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the list positions en masse:
		
		mysql_query("	UPDATE lists SET position = CASE list_id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}


	/*
		The changeColumn method takes the List id and the new column ID
		of the List. Updates the database.
	*/
		
	public static function changeColumn($list_id, $column_id){
		
		if(!$list_id) throw new Exception("Wrong update text!");
		
		mysql_query("	UPDATE lists
						SET column_id='".$column_id."'
						WHERE list_id=".$list_id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	/*
		The listToggleExpanded method checks the current setting, 
		toggles it and updates the database.
	*/
		
	public static function listToggleExpanded($id){
		
		$query = mysql_query("Select expanded from lists WHERE list_id=".$id);
		while($row = mysql_fetch_assoc($query)){
			$expanded = $row['expanded'];
		}
		
		if($expanded == 0) mysql_query("UPDATE lists set expanded = 1 WHERE list_id=".$id);
		if($expanded == 1) mysql_query("UPDATE lists set expanded = 0 WHERE list_id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	/*
		The listExpanded method sets the list to expaned 
		and updates the database.
	*/
		
	public static function listExpanded($id){
		
		mysql_query("UPDATE lists set expanded = 1 WHERE list_id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	/*
		The duplicateList method takes a source list_id and
		creates a new list with the same name and items.
	*/
		
	public static function duplicateList($id, $newPageID = ''){
		
		$queryList = mysql_query("SELECT * FROM lists WHERE list_id=".$id);
		while($row = mysql_fetch_assoc($queryList)){
			$name = $row['name'];
			$page_id = $row['page_id'];
			if ($newPageID != '') $page_id = $newPageID;
			$column_id = $row['column_id'];
			$expanded = $row['expanded'];
			$show_item_date = $row['show_item_date'];
			
			mysql_query("INSERT INTO lists (name, page_id, column_id, expanded, show_item_date) 
				VALUES ('".$name."',".$page_id.",".$column_id.",".$expanded.",".$show_item_date.")");
			$newListID = mysql_insert_id();

			$queryItems = mysql_query("SELECT * FROM items WHERE list_id=".$id." AND trash = 0");
			while($rowItems = mysql_fetch_assoc($queryItems)){
				mysql_query("INSERT INTO items set
					list_id = ".$newListID.",
					text	= '".$rowItems['text']."',
					position= ".$rowItems['position']);
			}
			$lists = array(array(
				'list_id' => $newListID,
				'name' => $row['name'],
				'column_id' => $row['column_id'],
				'date_created' => $row['date_created'],
				'expanded' => $row['expanded'],
				'show_item_date' => $row['show_item_date']
			));
			if ($newPageID != '') ToDo::buildList($lists, $row['column_id'], false);
			if ($newPageID == '') ToDo::buildList($lists, $row['column_id']);
		}
		if ($newPageID == '') exit;
	}

	public static function showItemDate($id,$value) {
		mysql_query("update lists set show_item_date = $value where list_id = $id");
	}

	/*
		The newTab method creates a new tab
		and updates the database.
	*/
		
	public static function newTab(){
		
		mysql_query("INSERT INTO tabs SET name= '"._('New Tab')."'");
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
		
		$tab_id = mysql_insert_id();
		
		// set the position of the newly created tab to its id so that it is last in the order
		mysql_query("UPDATE tabs SET position=".$tab_id." WHERE tab_id=".$tab_id);
		mysql_query("INSERT INTO pages SET name = '"._('Main Page')."', tab_id = $tab_id, position = 1");

		ToDo::returnTab($tab_id, false);
		
		exit;
	}

	public static function returnTab($tab_id, $exit = true) {

		// Select the new tab:
		$query = mysql_query("SELECT * FROM tabs where tab_id = $tab_id");
		
		$tabs = array();
		
		// Filling the $tabs array with tab objects:
		
		while($row = mysql_fetch_assoc($query)){
			$tabs[] = $row;
		}
		
		echo json_encode($tabs);
		
		if ($exit) exit;
		
	}

	/*
		The editTab method takes the Tab id and the new name
		of the tab and updates the database.
	*/
		
	public static function editTab($id, $text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");

		mysql_query("	UPDATE tabs
						SET name='".$text."'
						WHERE tab_id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	/* The markDeleteTab method takes the tab id and sets the 
		trash field to 1.
	*/
	
	public static function markDeleteTab($id){
		
		mysql_query("UPDATE tabs set trash = 1 WHERE tab_id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}

	/*
		The deleteTab method takes the id of the current Tab
		and matches all Pages, Lists and Items that belong to that tab 
		and deletes them from the database.  
	*/
	
	public static function deleteTab($id){
		
		$queryPages = mysql_query("Select page_id from pages WHERE tab_id=".$id);
		while($rowPages = mysql_fetch_assoc($queryPages)){

			$queryLists = mysql_query("Select list_id from lists WHERE page_id=".$rowPages['page_id']);
			while($rowLists = mysql_fetch_assoc($queryLists)){
			
				mysql_query("DELETE FROM items WHERE list_id=".$rowLists['list_id']);
				
		}
			mysql_query("DELETE FROM lists WHERE page_id=".$rowPages['page_id']);
		}
		
		mysql_query("DELETE FROM pages WHERE tab_id=".$id);
		mysql_query("DELETE FROM tabs WHERE tab_id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}

	/*
		The rearrangeTabs method is called when the ordering of
		the tabs is changed. Takes an array parameter, which
		contains the ids of the tabs in the new order.
	*/
	
	public static function rearrangeTabs($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the Tab positions en masse:
		
		mysql_query("	UPDATE tabs SET position = CASE tab_id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}
	
	/*
		Move a list from one tab to another
	*/
	
	public static function dropListOnTab($listID, $tabID) {
		$query = mysql_query("Select page_id from pages WHERE tab_id = $tabID and position = 1");
		while($row = mysql_fetch_assoc($query)){
			$pageID = $row['page_id'];
		}
		mysql_query("	UPDATE lists SET position = 0, column_id = 1, page_id = $pageID
						WHERE list_id = $listID");
	}

	/*
		Move a page from one tab to another
	*/
	
	public static function dropPageOnTab($pageID, $tabID) {

		mysql_query("	UPDATE pages SET position = 0, tab_id = $tabID
						WHERE page_id = $pageID");
	}

	/*
		The duplicateTab method takes a source tab_id and
		creates a new tab with the same name, pages, lists and items.
	*/
		
	public static function duplicateTab($id){
		$query = mysql_query("SELECT * FROM tabs WHERE tab_id=".$id);
		while ($row = mysql_fetch_assoc($query)){
			mysql_query("INSERT INTO tabs set
							name = '".$row['name']."'
						");
			$newTabID = mysql_insert_id();
			mysql_query("update tabs set position = $newTabID where tab_id = $newTabID");
			echo $newTabID;
			ToDo::duplicateTabPages($id, $newTabID);
		}
		exit;
	}

	public static function duplicateTabPages($id, $newTabID) {
		$queryPage = mysql_query("SELECT page_id FROM pages WHERE tab_id=".$id." AND trash = 0");
		while($rowPage = mysql_fetch_assoc($queryPage)){
			$page_id = $rowPage['page_id'];
			ToDo::duplicatePage($page_id, $newTabID);
		}
		exit;
	}




	/*
		The newPage method creates a new page
		and updates the database.
	*/
		
	public static function newPage($tab_id){
		
		mysql_query("INSERT INTO pages SET name='"._('New Page')."', tab_id = $tab_id");
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
		
		$page_id = mysql_insert_id();
		
		// set the position of the newly created tab to its id so that it is last in the order
		mysql_query("UPDATE pages SET position= $page_id WHERE page_id= $page_id");
		
		ToDo::returnPage($page_id, false);

		exit;
	}

	/*
		the returnPage private method takes the page_id
		and echos the JSON encoded data
	*/
	
	public static function returnPage($page_id, $exit = true) {
		// Select the new page:
		$query = mysql_query("SELECT * FROM pages where page_id = $page_id");
		
		$pages = array();
		
		// Filling the $tabs array with tab objects:
		
		while($row = mysql_fetch_assoc($query)){
			$pages[] = $row;
		}
		
		echo json_encode($pages);
		
		if($exit) exit;
	
	}

	/*
		The editPage method takes the Page id and the new name
		of the page and updates the database.
	*/
		
	public static function editPage($page_id, $text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");

		mysql_query("	UPDATE pages
						SET name='".$text."'
						WHERE page_id= $page_id"
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	/* The markDeletePage method takes the page id and sets the 
		trash field to 1.
	*/
	
	public static function markDeletePage($page_id){
		
		mysql_query("UPDATE pages set trash = 1 WHERE page_id=".$page_id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}

	/*
		The deletePage method. Takes the id of the current Page
		and deletes it from the database.  Then matches all Lists that
		belong to that page	and deletes them from the database.
	*/
	
	public static function deletepage($page_id){
		
		$query = mysql_query("Select list_id from lists WHERE page_id = $page_id");
		while($row = mysql_fetch_assoc($query)){

			mysql_query("DELETE FROM items WHERE list_id=".$row['list_id']);
			
		}
		
		mysql_query("DELETE FROM lists WHERE page_id = $page_id");
		mysql_query("DELETE FROM pages WHERE page_id = $page_id");
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}

	/*
		The rearrangePages method is called when the ordering of
		the pages is changed. Takes an array parameter, which
		contains the ids of the pages in the new order.
	*/
	
	public static function rearrangePages($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the Page positions en masse:
		
		mysql_query("	UPDATE pages SET position = CASE page_id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}

	/*
		Move a list from one page to another
	*/
	
	public static function dropListOnPage($listID, $pageID) {

		mysql_query("	UPDATE lists SET position = 0, column_id = 1, page_id = $pageID
						WHERE list_id = $listID");
	}

	/*
		The duplicatePage method takes a source page_id and
		creates a new page with the same name, lists and items.
	*/
		
	public static function duplicatePage($id, $newTabID = ''){
		
		$query = mysql_query("SELECT * FROM pages WHERE page_id=".$id);
		while ($row = mysql_fetch_assoc($query)){
			$name = $row['name'];
			$tab_id = $row['tab_id'];
			if ($newTabID != '') $tab_id = $newTabID;
			mysql_query("INSERT INTO pages (name, tab_id) 
				VALUES ('".$name."',".$tab_id.")");
			$newPageID = mysql_insert_id();
			mysql_query("update pages set position = $newPageID where page_id = $newPageID");
			if ($newTabID == '') echo $newPageID;
			ToDo::duplicatePageLists($id, $newPageID);
		}
		if ( $newTabID == '') exit;
	}

	public static function duplicatePageLists($id, $newPageID) {
		$queryList = mysql_query("SELECT list_id FROM lists WHERE page_id=".$id." AND trash = 0");
		while($rowList = mysql_fetch_assoc($queryList)){
			$list_id = $rowList['list_id'];
			ToDo::duplicateList($list_id, $newPageID);
		}
	}
	
	public static function columnsPerPage($id, $columns) {
		mysql_query("UPDATE pages set columns = $columns WHERE page_id = $id");
		if ($columns == 2) 
			mysql_query("UPDATE lists set column_id = 2 WHERE page_id = $id AND column_id = 3");
		if ($columns == 1) 
			mysql_query("UPDATE lists set column_id = 1 WHERE page_id = $id");
	}

	/*
		Delete all items in trash
	*/
	
	public static function emptyTrash() {
		
		// select all the tabs in trash and delete them
		$query = mysql_query("
			SELECT tab_id
			FROM tabs
			WHERE trash = 1
		");

		while($row = mysql_fetch_assoc($query)){
			ToDo::deleteTab($row['tab_id']);
		}

		// select all the pages in trash and delete them
		$query = mysql_query("
			SELECT page_id
			FROM pages
			WHERE trash = 1
		");

		while($row = mysql_fetch_assoc($query)){
			ToDo::deletePage($row['page_id']);
		}

		// select all the lists in trash and delete them
		$query = mysql_query("
			SELECT list_id
			FROM lists
			WHERE trash = 1
		");

		while($row = mysql_fetch_assoc($query)){
		echo $row['list_id']."<br />";
			ToDo::deleteList($row['list_id']);
		}

		// select all the items in trash and delete them
		$query = mysql_query("
			SELECT id
			FROM items
			WHERE trash = 1
		");

		while($row = mysql_fetch_assoc($query)){
			ToDo::delete($row['id']);
		}

	}

	/* 
		Delete selected objects from trash
	*/
	
	public static function deleteSelected($stringIDs) {
		$arrayIDs = explode(",",$stringIDs);
		$tabIDs = array_filter($arrayIDs, "ToDo::tabIDFilter");
		$pageIDs = array_filter($arrayIDs, "ToDo::pageIDFilter");
		$listIDs = array_filter($arrayIDs, "ToDo::listIDFilter");
		$itemIDs = array_filter($arrayIDs, "ToDo::itemIDFilter");
	
		if (count($tabIDs > 0)) {
			foreach($tabIDs as $tab_id) {
				ToDo::deleteTab(str_replace("Tab-","", $tab_id));
			}
		}

		if (count($pageIDs > 0)) {
			foreach($pageIDs as $page_id) {
				ToDo::deletePage(str_replace("Page-","", $page_id));
			}
		}
		if (count($listIDs > 0)) {
			foreach($listIDs as $list_id) {
				ToDo::deleteList(str_replace("List-","", $list_id));
			}
		}
		if (count($itemIDs > 0)) {
			foreach($itemIDs as $item_id) {
				ToDo::delete(str_replace("Item-","", $item_id));
			}
		}

	}

	/* 
		Restore selected objects from trash
	*/
	
	public static function restoreSelected($stringIDs) {
		$arrayIDs = explode(",",$stringIDs);
		$tabIDs = array_filter($arrayIDs, "ToDo::tabIDFilter");
		$pageIDs = array_filter($arrayIDs, "ToDo::pageIDFilter");
		$listIDs = array_filter($arrayIDs, "ToDo::listIDFilter");
		$itemIDs = array_filter($arrayIDs, "ToDo::itemIDFilter");
	
		if (count($tabIDs > 0)) {
			foreach($tabIDs as $tab_id) {
				mysql_query("UPDATE tabs SET trash = 0 where tab_id = ".str_replace("Tab-","", $tab_id));
			}
		}

		if (count($pageIDs > 0)) {
			foreach($pageIDs as $page_id) {
				mysql_query("UPDATE pages SET trash = 0 where page_id = ".str_replace("Page-","", $page_id));
			}
		}
		if (count($listIDs > 0)) {
			foreach($listIDs as $list_id) {
				mysql_query("UPDATE lists SET trash = 0 where list_id = ".str_replace("List-","", $list_id));
			}
		}
		if (count($itemIDs > 0)) {
			foreach($itemIDs as $id) {
				mysql_query("UPDATE items SET trash = 0 where id = ".str_replace("Item-","", $id));
			}
		}

	}
	
	/*
		Filter functions are used separate ID for tabs, pages, lists and items from a mixed string
	*/
	private static function tabIDFilter($str) {
		if (strstr($str, "Tab-"))
			return true; 
	}
	private static function pageIDFilter($str) {
		if (strstr($str, "Page-"))
			return true; 
	}
	private static function listIDFilter($str) {
		if (strstr($str, "List-"))
			return true; 
	}
	private static function itemIDFilter($str) {
		if (strstr($str, "Item-"))
			return true; 
	}
			
	/*
		The convertDateForMySQL method takes the user inputed date and converts
		it to MySQL compatibale date based on what the date format preference is set to.
	*/
	
	private static function convertDateForMySQL($date) {
		if ($GLOBALS["config"]["date_format"] == 'Y-m-d') {
			$date = date('Y-m-d H:i', strtotime($date));
			return $date;
		}
		if ($GLOBALS["config"]["date_format"] == 'm-d-Y') {
			$time = substr($date,10);
			$newDate = str_replace($time,"",$date);
			$dateArray = explode("-",$newDate);
			$m = $dateArray[0];
			$d = $dateArray[1];
			$y = $dateArray[2];
			$newDate = $y.'-'.$m.'-'.$d.$time;
			$date = date('Y-m-d H:i', strtotime($newDate));
			return $date;
		}
		if ($GLOBALS["config"]["date_format"] == 'd-m-Y'){
			$date = date('Y-m-d H:i', strtotime($date));
			return $date;
		}
	}

	public static function updateDate($table, $id, $dateField, $date){
		if ($table == 'items') $idField = 'id';
		if ($table == 'lists') $idField = 'list_id';
		if ($table == 'pages') $idField = 'page_id';
		if ($table == 'tabs') $idField = 'tab_id';
		$date = ToDo::convertDateForMySQL($date);
		mysql_query("update ".$table." set ".$dateField." = '".$date."' where ".$idField." = ".$id);
		exit;
		
	}
	/*
		A helper method to sanitize a string:
	*/
	
	public static function esc($str){
		
		if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
		
		return mysql_real_escape_string(strip_tags($str));
	}
	
} // closing the class definition
