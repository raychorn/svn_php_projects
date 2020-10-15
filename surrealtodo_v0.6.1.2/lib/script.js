$(document).ready(function(){
	/* The following code is executed once the DOM is loaded */

	/* Enable translations using JSGettext */
	gt = new Gettext({ 'domain' : 'surrealtodo' });

	// If any link in the Tab is clicked, assign
	// the tab id to the currentTab variable for later use.

	$('.tabItem').live('click',function(e){
									   
		currentTab = $(this).closest('.tabItem');
		currentTab.data('id',currentTab.attr('id').replace('Tab-',''));
		currentTabID = $(this).closest('.tabItem').attr('id').replace('Tab-','');

		e.preventDefault();
	});

	// If any link in the Tab is clicked, assign
	// the tab id to the currentTab variable for later use.

	$('.page').live('click',function(e){
		
		currentPage = $(this);							   
		currentPageID = $(this).attr('id').replace('Page-','');

		e.preventDefault();
	});

	// If any link in the List is clicked, assign
	// the list item to the currentList variable for later use.

	$('.list, .newItem').live('click',function(e){
									   
		currentList = $(this).closest('.sortableList');
		currentList.data('id',currentList.attr('id').replace('List-',''));


	});


	// If any link in the item is clicked, assign
	// the item to the currentItem variable for later use.

	$('.item').live('click',function(e){
		
		currentItem = $(this);
		currentItem.data('id',currentItem.attr('id').replace('Item-',''));

	});

	// When a double click occurs, just simulate a click on the edit button:
	$('.item').live('dblclick',function(){
		editItem();
	});
	
	//  Listening for key press while editing items:

	$('.item').live('keydown',function(event){
		
		if (event.keyCode == '13') 	{//  Listening for a enter key press to save task:
			var text = currentItem.find("textarea").val();
			if(text == '') {
				currentItem.find('.text')
							.html(currentItem.data('origText'))
							.end()
							.removeData('origText');
			}
			else {			
				$.post("ajax.php",{'action':'editItem','id':currentItem.data('id'),'text':text,'rand':Math.random()});
				
				currentItem.removeData('origText')
							.find(".text")
							.html(text);
			}
			itemContextMenu();  // re-enable the contextmenu for all items
			enableSorting();
			$('.list').removeClass('noHover');
			$('.text').removeClass('editItem');
			event.preventDefault();

		}
	
		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel edit:
			currentItem.find('.text')
						.html(currentItem.data('origText'))
						.end()
						.removeData('origText');
						
			itemContextMenu();  // re-enable the contextmenu for all items
			enableSorting();
			$('.list').removeClass('noHover');
			$('.text').removeClass('editItem');
		}
		
	});

	//  Listening for key press while editing item created date:

	$('#itemCreated .dateCreatedInput').live('keydown',function(event){
		
		if (event.keyCode == '13') 	{//  Listening for a enter key press:
			var inputDate = $('#itemCreated .dateCreatedInput').val();
			currentItem.data('origDate',currentItem.find('.dateCreated').text());

			 if (inputDate == '') {
				$('li.item').nojeegoocontext();
				itemContextMenu();
				event.preventDefault();
				return false;
			 }
			if (inputDate == currentItem.data('origDate')) {
				$('li.item').nojeegoocontext();
				itemContextMenu();
				event.preventDefault();
				return false;
			}
			if (inputDate != currentItem.data('origDate'))
			{			
				AJAXupdateDate('items', currentItem.data('id'), 'date_created', inputDate);
				
				currentItem.find(".dateCreated")
							.text(inputDate);
			}
					
			$('li.item').nojeegoocontext();
			itemContextMenu();
			event.preventDefault();

		}
	
		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel edit:

			$('li.item').nojeegoocontext();
			itemContextMenu();  // re-enable the contextmenu for all items
			event.preventDefault();
		}
		
	});


	//  Listening for key press while editing item completed date:

	$('#itemCompleted .dateCompletedInput').live('keydown',function(event){
		
		if (event.keyCode == '13') 	{//  Listening for a enter key press:
			var inputDate = $('#itemCompleted .dateCompletedInput').val();
			currentItem.data('origDate',currentItem.find('.dateCompleted').text());

			 if (inputDate == '') {
				$('li.item').nojeegoocontext();
				itemContextMenu();
				event.preventDefault();
				return false;
			 }
			if (inputDate == currentItem.data('origDate')) {
				$('li.item').nojeegoocontext();
				itemContextMenu();
				event.preventDefault();
				return false;
			}
			if (inputDate != currentItem.data('origDate'))
			{			
				AJAXupdateDate('items', currentItem.data('id'), 'date_completed', inputDate);
				
				currentItem.find(".dateCompleted")
							.text(inputDate);
			}
					
			$('li.item').nojeegoocontext();
			itemContextMenu();
			event.preventDefault();

		}
	
		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel edit:

						
			$('li.item').nojeegoocontext();
			itemContextMenu();  // re-enable the contextmenu for all items
			event.preventDefault();
		}
		
	});

// The Add New item button:
	
	var timestamp=0;
	$('.newItem').live('click',function(e){
		
		// Only one item per second is allowed:
		if((new Date()).getTime() - timestamp<1000) return false;
		showItemDate = currentList.find('.showItemDate').text();
		
		// if the listBody was collapsed, expand it and save setting to database
		currentList.find('.listBody').css("display",function(value){

				if (value = 'hidden') {

					currentList.find('.listBody').show();

					currentList.data('id',currentList.attr('id').replace('List-',''));

					$.get("ajax.php",{"action":"listExpanded","id":currentList.data('id'),'rand':Math.random()});
					
				}
		});
		
		var listID = TaskListID.replace('List-','');
		
		$.get("ajax.php",{'action':'newItem','list_id':listID,"show_item_date":showItemDate,'rand':Math.random()},function(msg){

			// Appending the new item and fading it into view:
			$(msg).hide().appendTo('#'+TaskListID+'.listBody').fadeIn();
		});

		makeItemsSortable();
		// Updating the timestamp:
		timestamp = (new Date()).getTime();
		
		e.preventDefault();
	});



// * * * * * * * * * * * * * * *  List specific code starts here * * * * * * * * * * * * * * * * * * * * 


	// Add a new List
	
$('.newList').live('click',function(e){

		$.get("ajax.php",{'action':'newList','page_id':currentPageID,'rand':Math.random()},function(msg){

			// Appending the new list and fading it into view:
			$(msg).hide().prependTo('#Column-1').fadeIn();

			makeListsSortable();
			makeItemsSortable();
		});
		e.preventDefault();
	});


	//  Listening for key press while editing lists:

	$('.listTitle').live('keyup',function(event){
		
		if (event.keyCode == 13){ 	//  Listening for a enter key press to save task:
			var text = currentList.find("input[type=text]").val();
	
			if(text == '') {
				currentList.find('.listName')
							.text(currentList.data('origText'))
							.end()
							.removeData('origText');
			}
			else {
				$.get("ajax.php",{'action':'editList','id':currentList.data('id'),'name':text,'rand':Math.random()});
				
				currentList.removeData('origText')
							.find(".listName")
							.text(text);
			}
				listContextMenu();  // re-enable the context menu for all lists
				enableSorting();
		}
	
		if (event.keyCode == 27){ 	//  Listening for a ESC key press to cancel edit:
			currentList.find('.listName')
						.text(currentList.data('origText'))
						.end()
						.removeData('origText');
			listContextMenu();  // re-enable the context menu for all lists
			enableSorting();
		}
	});

	var list_dialog_buttons = {};
	list_dialog_buttons[_('Cancel')] = function(){ $(this).dialog('close'); }
	list_dialog_buttons[_('Delete')] = function(){ 

				$.get("ajax.php",{"action":"markDeleteList","id":currentList.data('id'),'rand':Math.random()},function(msg){
					currentList.fadeOut('fast');
				})
				
				$(this).dialog('close');
	}

	// Configuring the delete confirmation dialog
	$("#dialog-confirm-delete-list").dialog({
		resizable: true,
		width:300,
		modal: true,
		autoOpen:false,
		buttons: list_dialog_buttons
	});

// Toggle the expand and collapse of a List
$('.listName').live('dblclick',function(e){
	$('#notebook').css('-moz-user-select', 'none')
			      .css('-khtml-user-select', 'none');

	currentList = $(this).closest('.sortableList');
	currentList.data('id',currentList.attr('id').replace('List-',''));
	var listID = currentList.data('id');

	$(currentList).find('.listBody').slideToggle(600);
	
	$.get("ajax.php",{action:'listToggleExpanded',id:listID,rand:Math.random()});

});

// Show transaltion information
$('a.translation-info').live('click',function(e){
	$('.translation-detail').show();
	event.preventDefault();
});

	//  Listening for key press while editing list created date:

	$('#listCreated .dateCreatedInput').live('keydown',function(event){
		if (event.keyCode == '13') 	{//  Listening for a enter key press:
			var inputDate = $('#listCreated .dateCreatedInput').val();
			currentList.data('origDate',currentList.find('.listCreated').text());
			 if (inputDate == '') {
				$('.listName').nojeegoocontext();
				listContextMenu();
				event.preventDefault();
				return false;
			 }
			if (inputDate == currentList.data('origDate')) {
				$('.listName').nojeegoocontext();
				listContextMenu();
				event.preventDefault();
				return false;
			}
			if (inputDate != currentList.data('origDate'))
			{			
				AJAXupdateDate('lists', currentList.data('id'), 'date_created', inputDate);
				
				currentList.find('.listCreated')
							.text(inputDate);
			}
					
			$('.listName').nojeegoocontext();
			listContextMenu();
			event.preventDefault();

		}
	
		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel edit:

						
			$('.listName').nojeegoocontext();
			listContextMenu();  // re-enable the contextmenu for all lists
			event.preventDefault();
		}
		
	});


// * * * * * * * * * * * * * * *  List specific code ends here * * * * * * * * * * * * * * * * * * * * 



	//  Listening for key press while editing Tab name:

	$('.tabName').live('keyup',function(event){
		if (event.keyCode == '13') 	{//  Listening for a enter key press to save Tab Name:
			var text = currentTab.find("input[type=text]").val();

			if(text == '') {
				currentTab.find('.tabName')
							.text(currentTab.data('origText'))
							.end()
							.removeData('origText');
			}
			else {
				$.get("ajax.php",{'action':'editTab','id':currentTab.data('id'),name:text,rand:Math.random()});
				
				currentTab.removeData('origText')
							.find(".tabName")
							.text(text);
			}
			tabContextMenu();  // re-enable the context menu for all tabs
			enableSorting();
		}

		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel Tab edit:
			currentTab.find('.tabName')
						.text(currentTab.data('origText'))
						.end()
						.removeData('origText');

			tabContextMenu();  // re-enable the context menu for all tabs
			enableSorting();
		}
		
	});

	//  Listening for key press while editing tab created date:

	$('#tabCreated .dateCreatedInput').live('keydown',function(event){
		if (event.keyCode == '13') 	{//  Listening for a enter key press:
			var inputDate = $('#tabCreated .dateCreatedInput').val();
			currentTab.data('origDate',currentTab.find('.tabCreated').text());

			 if (inputDate == '') {
				$('.tabItem .tabTitle').nojeegoocontext();
				tabContextMenu();
				event.preventDefault();
				return false;
			 }
			if (inputDate == currentTab.data('origDate')) {
				$('.tabItem .tabTitle').nojeegoocontext();
				tabContextMenu();
				event.preventDefault();
				return false;
			}
			if (inputDate != currentTab.data('origDate'))
			{			
				AJAXupdateDate('tabs', currentTab.data('id'), 'date_created', inputDate);
				
				currentTab.find('.tabCreated')
							.text(inputDate);
			}
					
			$('.tabItem .tabTitle').nojeegoocontext();
			tabContextMenu();
			event.preventDefault();

		}
	
		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel edit:

						
			$('.tabItem .tabTitle').nojeegoocontext();
			tabContextMenu();  // re-enable the contextmenu for all lists
			event.preventDefault();
		}
		
	});


	//  Listening for key press while editing Page name:

	$('.page').live('keyup',function(event){
		if (event.keyCode == '13') 	{//  Listening for a enter key press to save Tab Name:
			var text = currentPage.find("input[type=text]").val();

			if(text == '') {
				currentPage.find('.pageName')
							.text(currentPage.data('origText'))
							.end()
							.removeData('origText');
			}
			else {
			$.get("ajax.php",{'action':'editPage','page_id':currentPageID,'name':text,'rand':Math.random()});

			currentPage.removeData('origText')
						.find('.pageName')
						.text(text);
			}
			pageContextMenu();  // re-enable the context menu for all pages
			enableSorting();
		}

		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel Tab edit:
			currentPage.find('.pageName')
						.text(currentPage.data('origText'))
						.end()
						.removeData('origText');
			pageContextMenu();  // re-enable the context menu for all pages
			enableSorting();
		}
		
	});

	//  Listening for key press while editing page created date:

	$('#pageCreated .dateCreatedInput').live('keydown',function(event){
		if (event.keyCode == '13') 	{//  Listening for a enter key press:
			var inputDate = $('#pageCreated .dateCreatedInput').val();
			currentPage.data('origDate',currentPage.find('.pageCreated').text());

			 if (inputDate == '') {
				$('.page').nojeegoocontext();
				pageContextMenu();
				event.preventDefault();
				return false;
			 }
			if (inputDate == currentPage.data('origDate')) {
				$('.page').nojeegoocontext();
				pageContextMenu();
				event.preventDefault();
				return false;
			}
			if (inputDate != currentPage.data('origDate'))
			{			
				AJAXupdateDate('pages', currentPage.data('id'), 'date_created', inputDate);
				
				currentPage.find('.pageCreated')
							.text(inputDate);
			}
					
			$('.page').nojeegoocontext();
			pageContextMenu();
			event.preventDefault();

		}
	
		if (event.keyCode == '27') 	{//  Listening for a ESC key press to cancel edit:

						
			$('.page').nojeegoocontext();
			pageContextMenu();  // re-enable the contextmenu for all lists
			event.preventDefault();
		}
		
	});

	var page_dialog_buttons = {};
	page_dialog_buttons[_('Cancel')] = function(){ $(this).dialog('close'); }
	page_dialog_buttons[_('Delete')] = function(){ 

				$.get("ajax.php",{"action":"markDeletePage","page_id":currentPageID,'rand':Math.random()},function(msg){
					currentPage.fadeOut('fast');
//					if ($('.page').index() == 0)
						$('.page').eq(0).click();
//					else $('.page').eq(0).click();
				})
				$(this).dialog('close');
	}

	// Configuring the page delete confirmation dialog
	$("#dialog-confirm-delete-page").dialog({
		resizable: true,
		width:300,
		modal: true,
		autoOpen:false,
		buttons: page_dialog_buttons
	});

	var tab_dialog_buttons = {};
	tab_dialog_buttons[_('Cancel')] = function(){ $(this).dialog('close'); }
	tab_dialog_buttons[_('Delete')] = function(){ 

				$.get("ajax.php",{"action":"markDeleteTab","id":tabID,'rand':Math.random()},function(msg){
					currentTab.fadeOut('fast');
					$('.tab').eq(0).click();
				})
				$(this).dialog('close');
	}

	// Configuring the tab delete confirmation dialog
	$("#dialog-confirm-delete-tab").dialog({
		resizable: true,
		width:400,
		modal: true,
		autoOpen:false,
		buttons: tab_dialog_buttons
	});


	// Listening for click on emptyTrash
	$('a.emptyTrash').live('click',function(e){
		$.get("ajax.php",{action:'emptyTrash',rand:Math.random()});
		$('.trashTree').fadeOut('slow');
		e.preventDefault();
	});

	// Listening for click on checkAll
	$('a.checkAll').live('click',function(e){
	  $('.trashCheckbox').attr('checked', true);
		e.preventDefault();
	});
	
	// Listening for click on deleteSelected
	$('a.deleteSelected').live('click',function(e){
     var strIDs = ""
     $('.trashCheckbox:checked').each(function() {
       strIDs = strIDs+($(this).val())+',';
     });
	$.get("ajax.php",{action:'deleteSelected','strIDs':strIDs,rand:Math.random()});
	$('.trashCheckbox:checked').parent('li').fadeOut('slow');
		e.preventDefault();
	});

	// Listening for click on restoreSelected
	$('a.restoreSelected').live('click',function(e){
     var strIDs = ""
     $('.trashCheckbox:checked').each(function() {
       strIDs = strIDs+($(this).val())+',';
     });
	$.get("ajax.php",{action:'restoreSelected','strIDs':strIDs,rand:Math.random()});
	$('.trashCheckbox:checked').parent('li').fadeOut('slow');
		e.preventDefault();
	});

}); // Closing $(document).ready()

// * * * * * * * * * * * * * * *  Functions here * * * * * * * * * * * * * * * * * * * * 

	// Global variables, holding a jQuery object 
	// containing the current Tab, List and Item id:
	var currentTab;
	var currentTabID;
	var currentPage;
	var currentPageID;
	var currentList;
	var currentItem;
	var TaskListID;
	
	var gt; // Define global variable for Gettext used for language translations

function _ (msgid) { return gt.gettext(msgid); }

function AJAXupdateDate(table, id, dateField, date) {
	$.get("ajax.php",{'action':'updateDate','table':table,'id':id,'dateField':dateField,'date':date,'rand':Math.random()});
}

function myCallback(caller)
{
  TaskListID = caller.parentNode.id;
}

function editTab(context)
{
	$(currentTab).find('a.tab').click();
	disableSorting();
	
	var container = currentTab.find('.tabName');
	
	if(!currentTab.data('origText'))
	{
		// Saving the current value of the tab so we can
		// restore it later if the user discards the changes:
		
		currentTab.data('origText',container.text());
	}
	else
	{
		// This will block the edit button if the edit box is already open:
		return false;
	}
	
	$('<input type="text">').val(container.text()).appendTo(container.empty());
	
	// If the text is the default text of a new tab then highlight it for easy editing
	if($('.tabName input').val() == _('New Tab')) {
		$('.tabName input').focus().select();	
	}
	// If not then just place the cursor into the field
	else $('.tabName input').focus();
}

function editPage(context){
	$(currentPage).find('a.page').click();

	var container = currentPage.find('.pageName');
	
	if(!currentPage.data('origText'))
	{
		// Saving the current value of the page name so we can
		// restore it later if the user discards the changes:
		
		currentPage.data('origText',container.text());
	}
	else
	{
		// This will block the edit button if the edit box is already open:
		return false;
	}
	
	$('<input type="text">').val(container.text()).appendTo(container.empty());
	
	// If the text is the default text of a new tab then highlight it for easy editing
	if($('.pageName input').val() == "New Page") {
		$('.pageName input').focus().select();	
	}
	// If not then just place the cursor into the field
	else $('.pageName input').focus();
	disableSorting();

}

function editList() {
		disableSorting();
		var container = currentList.find('.listName');

		if(!currentList.data('origText'))
		{
			// Saving the current value of the list name so we can
			// restore it later if the user discards the changes:
			
			currentList.data('origText',container.text());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<input type="text">').val(container.text()).appendTo(container.empty());
		
		// If the text is the default text of a new list then highlight it for easy editing
		if($('.listName input').val() == _('New List')) {
			$('.listName input').focus().select();	
		}
		// If not then just place the cursor into the field
		else $('.listName input').focus();
}
function editItem() {
		$('.list').addClass('noHover');
		disableSorting();
		$('li.item').nojeegoocontext(); // disable the custom context menu while editing
		
		var container = currentItem.find('.text');
		
		if(!currentItem.data('origText'))
		{
			// Saving the current value of the item so we can
			// restore it later if the user discards the changes:
			
			currentItem.data('origText',container.html());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<textarea class="editItem inline"></textarea>').val(container.html()).appendTo(container.empty());

		// If the text is the default text of a new task then highlight it for easy editing
		if($('textarea').val() == _('Double-click to edit')) {
			$('textarea').focus().select();	
		}
		// If not then just place the cursor into the field
		else $('textarea').focus();
	
}
function advancedEditItem() {
		$('.list').addClass('noHover');
		disableSorting();
		$('li.item').nojeegoocontext(); // disable the custom context menu while editing
		
		var container = currentItem.find('.text');
		if(!currentItem.data('origText'))
		{
			// Saving the current value of the item so we can
			// restore it later if the user discards the changes:
			
			currentItem.data('origText',container.html());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<textarea name="content" class="inline"></textarea>').val(container.html().replace('<p class="inline">','<p>')).appendTo(container.empty());

		enableEditor("300",$('textarea').width());
		
		// If the text is the default text of a new task then highlight it for easy editing
		if($('textarea').val() == _('Double-click to edit')) {
			$('textarea').focus().select();	
		}
		// If not then just place the cursor into the field
		else $('textarea').focus();
	
}
function advancedSave(){
	var text = tinyMCE.get('content').getContent().replace('<p>','<p class="inline">');

	tinyMCE.triggerSave();

	if(text == '') {
		currentItem.find('.text')
					.html(currentItem.data('origText'))
					.end()
					.removeData('origText');
	}
	else {			
		$.post("ajax.php",{'action':'editItem','id':currentItem.data('id'),'text':text,'rand':Math.random()});
		
		currentItem.removeData('origText')
					.find(".text")
					.html(text);
	}
	itemContextMenu();  // re-enable the contextmenu for all items
				
	tinyMCE.execCommand('mceRemoveControl', false, 'content');
	$('.list').removeClass('noHover');
	enableSorting();
}
function advancedCancel(){
	currentItem.find('.text')
				.html(currentItem.data('origText'))
				.end()
				.removeData('origText');
	itemContextMenu();  // re-enable the contextmenu for all items
	tinyMCE.execCommand('mceRemoveControl', false, 'content');
	$('.list').removeClass('noHover');
	enableSorting();
}
function deleteItem() {
		$.get("ajax.php",{"action":"markDeleteItem","id":currentItem.data('id'),'rand':Math.random()});
		currentItem.fadeOut('fast');
	
}
function duplicateList(context) {
	currentList = $(context);
	currentListID = $(context).parent().attr('id').replace('List-','');
	$.get("ajax.php",{action:'duplicateList','id':currentListID,rand:Math.random()},function(msg){
		$(context).parents().filter('.column').prepend(msg).fadeIn('fast');
	});
}
function duplicatePage(context) {
	currentPage = $(context);
	currentPageID = $(context).attr('id').replace('Page-','');
	$.get("ajax.php",{action:'duplicatePage','id':currentPageID,rand:Math.random()},function(newPageID){
		addPage('returnPage',newPageID);
	});
}
function duplicateTab(context) {
	currentTab = $(context);
	currentTabID = $(context).parent().attr('id').replace('Tab-','');
	$.get("ajax.php",{action:'duplicateTab','id':currentTabID,rand:Math.random()},function(newTabID){
		addTab('returnTab',newTabID);
	});
}
function makeListsSortable() {
	$('.column').sortable({
		handle: '.listName',
		items		: '.sortableList',
		connectWith	: '.column',
		update		: function(event, ui){		// The function is called after the lists are rearranged
		
			// The toArray method returns an array with the ids of the lists
			var columnClass = "#"+$(ui.item).parent().attr('id')+".column";
			var arr = $(columnClass).sortable('toArray');

			arr = $.map(arr,function(val,key){
				return val.replace('List-','');
			});
			
			// Saving with AJAX
			$.get('ajax.php',{action:'rearrangeLists',positions:arr,rand:Math.random()});
		},
		receive		: function(event, ui) {
			//Run this code whenever an item is dragged and dropped into this list
			var listID = $(ui.item).attr('id').replace('List-','');
			var columnID = $(ui.item).parent().attr('id').replace('Column-','');
			$.get("ajax.php",{'action':'changeColumn','list_id':listID,'column_id':columnID,rand:Math.random()});
		},
		/* Opera fix: */
		
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});
}
function makeItemsSortable() {
	$('.listBody').sortable({
		items		: 'li.item',
		connectWith	: '.listBody',
		update		: function(event, ui){		// The function is called after the items are rearranged
		
			// The toArray method returns an array with the ids of the items

			var listClass = "#"+$(ui.item).parent().attr('id')+".listBody";
			var arr = $(listClass).sortable('toArray');
			// Striping the Item- prefix of the ids:
			arr = $.map(arr,function(val,key){
				return val.replace('Item-','');
			});
			
			// Saving with AJAX
			$.get('ajax.php',{action:'rearrangeItems',positions:arr,rand:Math.random()});
		},
		receive		: function(event, ui) {
			//Run this code whenever an item is dragged and dropped into this list
			var itemID = $(ui.item).attr('id').replace('Item-','');
			var listID = $(ui.item).parent().attr('id').replace('List-','');
			$.get("ajax.php",{'action':'changeList','id':itemID,'list_id':listID,rand:Math.random()});
		},
		/* Opera fix: */
		
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});
	
}
function makeTabsDroppable() {
	$('.tabItem').droppable({
		accept		: '.sortableList, .pageItem',
		tolerance	: 'pointer',
		drop		: function(event, ui){		// The function is called after an object is dropped on the tab

				newParentTabID = $(this).attr('id').replace('Tab-','');

				if (ui.draggable.attr('class') == 'sortableList ui-sortable-helper') {

					listID = $(ui.draggable).attr('id').replace('List-','');

					// Saving with AJAX
					$.get('ajax.php',{action:'dropListOnTab',list_id:listID,tab_id:newParentTabID,rand:Math.random()});
				}

				if (ui.draggable.attr('class') == 'pageItem ui-droppable ui-sortable-helper') {

					pageID = $(ui.draggable).attr('id').replace('Page-','');

					// Saving with AJAX
					$.get('ajax.php',{action:'dropPageOnTab',page_id:pageID,tab_id:newParentTabID,rand:Math.random()});
					if (pageID == currentPageID) $('#contentHolder').empty();
				}

				$(ui.draggable).remove().fadeOut('fast');
				
				}
	});	
}
function makePagesDroppable() {
	$('.pageItem').droppable({
		accept		: '.sortableList',
		tolerance	: 'pointer',
		drop		: function(event, ui){		// The function is called after an object is dropped on the tab
					
				newParentPageID = $(this).attr('id').replace('Page-','');

				listID = $(ui.draggable).attr('id').replace('List-','');

				// Saving with AJAX
				$.get('ajax.php',{action:'dropListOnPage',list_id:listID,page_id:newParentPageID,rand:Math.random()});

				$(ui.draggable).remove().fadeOut('fast');
				
				}
	});
}
function enableEditor(editorHeight,editorWidth) {
tinyMCE.init({
	// General options
	mode : "textareas",
	height: editorHeight,
	width : editorWidth,
	theme : "advanced",
	save_onsavecallback : "advancedSave",
	save_oncancelcallback : "advancedCancel",
	plugins : "safari,style,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,directionality,fullscreen,nonbreaking,xhtmlxtras",
	// Theme options
	theme_advanced_buttons1 : "save,cancel,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",
	theme_advanced_buttons2 : "fontselect,fontsizeselect,|,forecolor,backcolor",
	theme_advanced_buttons3 : "search,replace,|,undo,redo,|,bullist,numlist,|,outdent,indent,|,code,preview,fullscreen",
	theme_advanced_buttons4 : "link,unlink,|,image,emotions,media,|,hr,advhr,|,insertdate,inserttime",
	theme_advanced_buttons5 : "tablecontrols",
	theme_advanced_toolbar_location : "bottom",
	theme_advanced_toolbar_align : "left",
	theme_advanced_resizing_min_width : editorWidth,
	theme_advanced_resizing_max_width : editorWidth,
	height : editorHeight,
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true
});			
}
function disableSorting() {
	$('#tabRow').sortable('disable');	
	$('#pageList').sortable('disable');	
	$('.column').sortable('disable');	
	$('.listBody').sortable('disable');	
}
function enableSorting() {
	$('#tabRow').sortable('enable');	
	$('#pageList').sortable('enable');	
	$('.column').sortable('enable');	
	$('.listBody').sortable('enable');	
}