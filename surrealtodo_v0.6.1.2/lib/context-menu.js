
function itemContextMenu() {
	$('li.item').jeegoocontext('menuItem', {
		livequery: true,
		widthOverflowOffset: 0,
		heightOverflowOffset: 1,
		submenuLeftOffset: -4,
		submenuTopOffset: -5,
		onSelect: function(e, context){
			if($(this).hasClass('disabled'))
			{              
				if(confirm('These options are disabled. Do you want to enable these options?'))$(this).removeClass('disabled');
				$(context).animate(200);
				return true;
			}
			itemID = $(context).attr('id').replace('Item-','');
			currentItem = $(context);
			currentItem.data('id',currentItem.attr('id').replace('Item-',''));
			switch($(this).attr('id'))
			{
				case 'edit':
					editItem();
					break;
				case 'advancedEdit':
					advancedEditItem();
					break;
				case 'delete':
					deleteItem();
					break;
				case 'markComplete':
					$.get("ajax.php",{"action":"taskComplete","id":itemID,"value":"CURRENT_TIMESTAMP"},function(msg){
					$(context).parent().find('.dateCompleted').replaceWith('<div class="dateCompleted">'+msg+'</div>')});
					$('.activePage').click();
					break;
				case 'markIncomplete':
					$.get("ajax.php",{"action":"taskComplete","id":itemID,"value":"NULL"},function(){
					$(context).parent().find('.dateCompleted').replaceWith('<div class="dateCompleted"></div>')});
					$('.activePage').click();
					break;
				default:
					return false;
			}
			$(context).animate(200);
		},
		onHover: function(e, context){
			if($(this).hasClass('disabled'))return false;
		},
		onShow: function(e, context){
			$('#itemCreated #dateCreated').replaceWith('<div id="dateCreated">Created: '+$(context).find('.dateCreated').text()+'</div>');
			$('.dateCreatedInput').replaceWith('<input name="dateCreated" type="text" value="'+$(context).find('.dateCreated').text()+'" class="dateCreatedInput" />');

			if($(context).find('.dateCompleted').text() == '')
			{
				$('#itemCompleted').hide();
				$('#markComplete').show();
				$('#markIncomplete').hide();
			}
			else
			{
				$('#dateCompleted').replaceWith('<div id="dateCompleted">Completed: '+$(context).find('.dateCompleted').text()+'</div>');
				$('.dateCompletedInput').replaceWith('<input name="dateCompleted" type="text" value="'+$(context).find('.dateCompleted').text()+'" class="dateCompletedInput" />');
				$('#itemCompleted').show();
				$('#markComplete').hide();
				$('#markIncomplete').show();
			}
			
			$('.active').animate(200);
			$(context).animate(200);
		},
		onHide: function(e, context){
			$(context).animate(200);
		}              
	});
	
} //close itemContextMenu

function listContextMenu() {
	$('.listName').jeegoocontext('menuList', {
		livequery: true,
		widthOverflowOffset: 0,
		heightOverflowOffset: 1,
		submenuLeftOffset: -4,
		submenuTopOffset: -5,
		onSelect: function(e, context){
			if($(this).hasClass('disabled'))
			{              
				if(confirm('These options are disabled. Do you want to enable these options?'))$(this).removeClass('disabled');
				$(context).animate(200);
				return true;
			}
			currentList = $(context).closest('.sortableList');
			currentList.data('id',currentList.attr('id').replace('List-',''));
			switch($(this).attr('id'))
			{
				case 'edit':
					$('.listName').nojeegoocontext();
					editList();
					break;
				case 'delete':
					$("#dialog-confirm-delete-list").dialog('open');
					break;
				case 'duplicate':
					duplicateList(context);
					break;
				case 'show_item_dates':
					$.get("ajax.php",{"action":"showItemDate","id":currentList.data('id'),"value":1});
					$('.activePage').click();
					break;
				case 'hide_item_dates':
					$.get("ajax.php",{"action":"showItemDate","id":currentList.data('id'),"value":0});
					$('.activePage').click();
					break;
				default:
					return false;
			}
			$(context).animate(200);
		},
		onHover: function(e, context){
			if($(this).hasClass('disabled'))return false;
		},
		onShow: function(e, context){
			if($(context).parent().find('.showItemDate').text() == '0')
			{
				$('#hide_item_dates').hide();
				$('#show_item_dates').show();
			}
			else
			{
				$('#hide_item_dates').show();
				$('#show_item_dates').hide();
			}

			$('#listCreated #dateCreated').replaceWith('<div id="dateCreated">Created: '+$(context).parent().find('.listCreated').text()+'</div>');
			$('.dateCreatedInput').replaceWith('<input name="dateCreated" type="text" value="'+$(context).parent().find('.listCreated').text()+'" class="dateCreatedInput" />');

			$(context).animate(200);
		},
		onHide: function(e, context){
			$(context).animate(200);
		}              
	});
	
}  //close listContextMenu()

function pageContextMenu() {
	$('.page').jeegoocontext('menuPage', {
		livequery: true,
		widthOverflowOffset: 0,
		heightOverflowOffset: 1,
		submenuLeftOffset: -4,
		submenuTopOffset: -5,
		onSelect: function(e, context){
			if($(this).hasClass('disabled'))
			{              
				if(confirm('These options are disabled. Do you want to enable these options?'))$(this).removeClass('disabled');
				$(context).animate(200);
				return true;
			}
			currentPage = $(context).parent().parent();
			currentPage.data('id',currentPage.attr('id').replace('Page-',''));
			switch($(this).attr('id'))
			{
				case 'edit':
					editPage(currentPage);
					$('.page').nojeegoocontext();
					break;
				case 'one-column':
					$.get("ajax.php",{"action":"columnsPerPage","id":currentPage.data('id'),"columns":1});
					$(context).parent().parent().find('.columns').text('1');
					$(context).click();
					break;
				case 'two-column':
					$.get("ajax.php",{"action":"columnsPerPage","id":currentPage.data('id'),"columns":2});
					$(context).parent().parent().find('.columns').text('2');
					$(context).click();
					break;
				case 'three-column':
					$.get("ajax.php",{"action":"columnsPerPage","id":currentPage.data('id'),"columns":3});
					$(context).parent().parent().find('.columns').text('3');
					$(context).click();
					break;
				case 'delete':
					$(context).click();
					$("#dialog-confirm-delete-page").dialog('open');
					break;
				case 'duplicate':
					duplicatePage(context);
					break;
				default:
					return false;
			}
			$(context).animate(200);
		},
		onHover: function(e, context){
			if($(this).hasClass('disabled'))return false;
		},
		onShow: function(e, context){
			$(this).find('.ok').remove();
			
			$num_columns = $(context).parent().parent().find('.columns').text();
			switch ($num_columns) {
				case '1':
					$('#one-column').addClass('icon').prepend('<span class="icon ok"></span>');
					$('#two-column').removeClass('icon').find('.ok').remove();
					$('#three-column').removeClass('icon').find('.ok').remove();
					break;				
				case '2':
					$('#two-column').addClass('icon').prepend('<span class="icon ok"></span>');
					$('#one-column').removeClass('icon').find('.ok').remove();
					$('#three-column').removeClass('icon').find('.ok').remove();
					break;				
				case '3':
					$('#three-column').addClass('icon').prepend('<span class="icon ok"></span>');
					$('#two-column').removeClass('icon').find('.ok').remove();
					$('#one-column').removeClass('icon').find('.ok').remove();
					break;				
			}
	
			$('#pageCreated #dateCreated').replaceWith('<div id="dateCreated">Created: '+$(context).parent().parent().find('.pageCreated').text()+'</div>');
			$('.dateCreatedInput').replaceWith('<input name="dateCreated" type="text" value="'+$(context).parent().parent().find('.pageCreated').text()+'" class="dateCreatedInput" />');

			$(context).animate(200);
		},
		onHide: function(e, context){
			$(context).animate(200);
		}              
	});
	
}  // close pageContextMenu()

function tabContextMenu() {
	$('.tabItem .tabTitle').jeegoocontext('menuTab', {
		livequery: true,
		widthOverflowOffset: 0,
		heightOverflowOffset: 1,
		submenuLeftOffset: -4,
		submenuTopOffset: -5,
		onSelect: function(e, context){
			if($(this).hasClass('disabled'))
			{              
				if(confirm('These options are disabled. Do you want to enable these options?'))$(this).removeClass('disabled');
				$(context).animate(200);
				return true;
			}
			currentTab = $(context).parent();
			currentTab.data('id',currentTab.attr('id').replace('Tab-',''));
			tabID = currentTab.data('id');
			switch($(this).attr('id'))
			{
				case 'edit':
					editTab();
					$('.tabItem .tabTitle').nojeegoocontext();
					break;
				case 'delete':
					$(context).click();
					$("#dialog-confirm-delete-tab").dialog('open');
					break;
				case 'duplicate':
					duplicateTab(context);
					break;
				default:
					return false;
			}
			$(context).animate(200);
		},
		onHover: function(e, context){
			if($(this).hasClass('disabled'))return false;
		},
		onShow: function(e, context){

			$('#tabCreated #dateCreated').replaceWith('<div id="dateCreated">Created: '+$(context).parent().find('.tabCreated').text()+'</div>');
			$('.dateCreatedInput').replaceWith('<input name="dateCreated" type="text" value="'+$(context).parent().find('.tabCreated').text()+'" class="dateCreatedInput" />');

			$(context).animate(200);
		},
		onHide: function(e, context){
			$(context).animate(200);
		}              
	});
	
}  // close tabContextMenu()
