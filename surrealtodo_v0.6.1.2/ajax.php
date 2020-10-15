<?php

require "connect.php";
require "todo.class.php";


if(isset($_GET['id'])) $id = (int)$_GET['id'];
if(isset($_GET['action'])) $action = $_GET['action'];
if(isset($_POST['action'])) $action = $_POST['action'];

try{

	switch($action)
	{
// fetching data actions
		case 'tabs':
			ToDo::tabs();
			break;
			
		case 'pages':
			ToDo::pages($_GET['tab_id']);
			break;

		case 'lists':
			ToDo::lists($_GET['page_id']);
			break;
			
// item actions
		case 'newItem':
			ToDo::newItem($_GET['list_id'],$_GET['show_item_date']);
			break;
			
		case 'editItem':
			ToDo::editItem($_POST['id'],$_POST['text']);
			break;
			
		case 'markDeleteItem':
			ToDo::markDeleteItem($id);
			break;
			
		case 'deleteItem':
			ToDo::delete($id);
			break;

		case 'rearrangeItems':
			ToDo::rearrangeItems($_GET['positions']);
			break;
			
		case 'changeList':
			ToDo::changeList($id,$_GET['list_id']);
			break;

		case 'taskComplete':
			ToDo::taskComplete($id,$_GET['value']);
			break;
			
// list actions
		case 'newList':
			ToDo::newList($_GET['page_id']);
			break;

		case 'editList':
			ToDo::editList($id,$_GET['name']);
			break;

		case 'markDeleteList':
			ToDo::markDeleteList($id);
			break;
			
		case 'deleteList':
			ToDo::deleteList($id);
			break;
			
		case 'rearrangeLists':
			ToDo::rearrangeLists($_GET['positions']);
			break;
			
		case 'changeColumn':
			ToDo::changeColumn($_GET['list_id'],$_GET['column_id']);
			break;

		case 'listToggleExpanded':
			ToDo::listToggleExpanded($id);
			break;

		case 'listExpanded':
			ToDo::listExpanded($id);
			break;

		case 'duplicateList':
			ToDo::duplicateList($id);
			break;

		case 'showItemDate':
			ToDo::showItemDate($id,$_GET['value']);
			break;

// page actions			
		case 'newPage':
			ToDo::newPage($id);
			break;
			
		case 'editPage':
			ToDo::editPage($_GET['page_id'],$_GET['name']);
			break;

		case 'markDeletePage':
			ToDo::markDeletePage($_GET['page_id']);
			break;

		case 'deletePage':
			ToDo::deletePage($_GET['page_id']);
			break;
			
		case 'rearrangePages':
			ToDo::rearrangePages($_GET['positions']);
			break;

		case 'dropListOnPage':
			ToDo::dropListOnPage($_GET['list_id'],$_GET['page_id']);
			break;
			
		case 'duplicatePage':
			ToDo::duplicatePage($id);
			break;

		case 'duplicatePageLists':
			ToDo::duplicatePageLists($id, $_GET['newPageID']);
			break;

		case 'returnPage':
			ToDo::returnPage($id);
			break;
		
		case 'columnsPerPage':
			ToDo::columnsPerPage($id, $_GET['columns']);
			break;

// tab actions
		case 'newTab':
			ToDo::newTab();
			break;

		case 'editTab':
			ToDo::editTab($id,$_GET['name']);
			break;

		case 'markDeleteTab':
			ToDo::markDeleteTab($id);
			break;

		case 'deleteTab':
			ToDo::deleteTab($id);
			break;
			
		case 'rearrangeTabs':
			ToDo::rearrangeTabs($_GET['positions']);
			break;
			
		case 'dropListOnTab':
			ToDo::dropListOnTab($_GET['list_id'],$_GET['tab_id']);
			break;
			
		case 'dropPageOnTab':
			ToDo::dropPageOnTab($_GET['page_id'],$_GET['tab_id']);
			break;

		case 'duplicateTab':
			ToDo::duplicateTab($id);
			break;

		case 'duplicateTabPages':
			ToDo::duplicateTabPages($id, $_GET['newTabID']);
			break;

		case 'returnTab':
			ToDo::returnTab($id);
			break;

// trash actions
		case 'emptyTrash':
			ToDo::emptyTrash();
			break;

		case 'deleteSelected':
			ToDo::deleteSelected($_GET['strIDs']);
			break;
			
		case 'restoreSelected':
			ToDo::restoreSelected($_GET['strIDs']);
			break;

		case 'updateDate':
			ToDo::updateDate($_GET['table'],$id,$_GET['dateField'],$_GET['date']);
			break;

	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

echo "1";
?>