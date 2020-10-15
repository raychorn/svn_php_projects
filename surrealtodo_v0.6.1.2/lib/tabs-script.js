$(document).ready(function(){
	/* This code is executed after the DOM has been completely loaded */
	
	$.ajax({
	   type: "GET",
	   url: "ajax.php",
	   data: "action=tabs",
	   cache: false,
	   dataType: "json",
	   success: function(Tabs){

			$.each(Tabs, function(i){
				
				/* Sequentially creating the tabs and assigning a color from the array: */
				var tab = $('<li id="Tab-'+Tabs[i].tab_id+'" class="tabItem">'+
								'<div class="tabTitle">'+
									'<a href="#" class="tab"><span class="left"></span><div class="tabName">'+Tabs[i].name+'</div><span class="right"></span></a>'+
								'</div>'+
								'<div class="tabCreated">'+Tabs[i].date_created+'</div>'+
							'</li>');
				
				/* Adding the tab to the UL container: */
				$('#tabRow').append(tab);
		
			});
			
			$('#tabRow').sortable({
				update		: function(event, ui){		// The function is called after the tabs are rearranged
						
							// The toArray method returns an array with the ids of the tabs
				
							var arr = $("#tabRow").sortable('toArray');
							// Striping the Tab- prefix of the ids:
							arr = $.map(arr,function(val,key){
								return val.replace('Tab-','');
							});
							
							// Saving with AJAX
							$.get('ajax.php',{action:'rearrangeTabs',positions:arr,rand:Math.random()});
						}
			});

			/* Caching the tabs into a variable for better performance: */
			var the_tabs = $('.tab');
							
			the_tabs.live('click',function(e){
				/* If it is currently active, return false and exit: */
				if($(this).is('.activeTab')) return false;

				$('#contentHolder').empty();

				/* "this" points to the clicked tab hyperlink: */
				currentTabID = $(this).closest('.tabItem').attr('id').replace('Tab-','');

				/* Set the current tab: */
				$('a.tab').removeClass('activeTab');
				$(this).addClass('activeTab');
				$('#pageList').empty();

				$.getJSON("ajax.php",{"action":"pages","tab_id":currentTabID,rand:Math.random()},function(Pages){
						
					$.each(Pages, function(i){
					
						/* Sequentially creating the pages and assigning a color from the array: */
						var pageItem = $('<li id="Page-'+Pages[i].page_id+'" class="pageItem">'+
										'<div class="pageTitle">'+
											'<a href="" id="Page-'+Pages[i].page_id+'" class="page">'+
											'<span class="left"></span><span class="pageName">'+Pages[i].name+
												'</span><span class="right"></span></a>'+
										'</div>'+
										'<div class="pageCreated">'+Pages[i].date_created+'</div>'+
										'<div class="columns">'+Pages[i].columns+'</div>'+
									'</li>');
						
						/* Setting the page data for each hyperlink: */
						pageItem.find('a').data('page','ajax.php?action=lists&page_id='+Pages[i].page_id);
						makeTabsDroppable();

						/* Adding the tab to the UL container: */
						$('#pageList').append(pageItem).fadeIn('fast');

					});  // close $.each(Pages)
				
					// add link to create a new page
					var newPage = $(
						'<li id="newPageItem">'+
							'<abbr title="'+_('New Page')+'"><a href="" class="notebookIcons newPage">'+
							'</a></abbr>'+
						'</li>');
					
					$('#pageList').append(newPage);
					makePagesDroppable();

					// Make the first page the active page
					clickPage();  // function located in notebook.php
					
					$('#pageList').sortable({
						items		: 'li.pageItem',
						update		: function(event, ui){		// The function is called after the tabs are rearranged
								
									// The toArray method returns an array with the ids of the tabs
						
									var arr = $("#pageList").sortable('toArray');
									// Striping the Tab- prefix of the ids:
									arr = $.map(arr,function(val,key){
										return val.replace('Page-','');
									});
									
									// Saving with AJAX
									$.get('ajax.php',{action:'rearrangePages',positions:arr,rand:Math.random()});
						}
			});
		
			
				});  // close $.get("ajax.php")
				
				
				e.preventDefault();
			})
			
			
			$('a.page').live('click',function(e){

				/* "this" points to the clicked page hyperlink: */
				var element = $(this);
				
				/* If it is currently active, return false and exit: */
//				if($(this).is('.activePage')) return false;
				
				/* Set the current page: */
				$('a.page').removeClass('activePage');
				$(this).addClass('activePage');
				
				/* Checking whether the AJAX fetched page has been cached: */
				if(!element.data('cache'))
				{	
					/* If no cache is present, show the gif preloader and run an AJAX request: */
					$('#contentHolder').html('<img src="theme/default/images/ajax_preloader.gif" width="64" height="64" class="preloader" />');
		
					$.get(element.data('page'),{'rand':Math.random()},function(msg){
						$('#contentHolder').html(msg);
						
					});
				}
			})  //close .page live click
			
			/* Emulating a click on the first tab so page list is not empty: */
			clickTab(); // function located in notebook.php


// Listen for click on New Tab icon
			$('.newTab').live('click',function(e){
				addTab('newTab',0);

		
			});	 // close #newTab click
	
			// Listen for click on Add Page icon
			$('.newPage').live('click',function(e){
				addPage('newPage', currentTabID);
				e.preventDefault();

				});	 // close #newTab click


	   } // closing ajax success:
	   
	});  // close ajax


});

function addTab(action, id) {
				var d = new Date();
				var curr_date = d.getDate();
				var curr_month = d.getMonth();
				curr_month++;
				var curr_year = d.getFullYear();
				var curr_hour = d.getHours();
				var curr_min = d.getMinutes();
				
				currentDate = curr_year + "-" + curr_month + "-" + curr_date + " " + curr_hour + ":" + curr_min ;
				

				$.getJSON("ajax.php",{'action':action,'id':id,rand:Math.random()},function(Tabs){

					$.each(Tabs, function(i){
						
						var tabCreated = Tabs[i].date_created;
						tabCreated = tabCreated.substring(0,tabCreated.length-3); // remove the seconds section from the date
					
						/* Build the new tab and append it to the tabRow */
						var tab = $(
						'<li id="Tab-'+Tabs[i].tab_id+'" class="tabItem">'+
							'<div class="tabTitle">'+
								'<a href="#" class="tab"><span class="left"></span><div class="tabName">'+Tabs[i].name+'</div><span class="right"></span></a>'+
							'</div>'+
							'<div class="tabCreated">'+tabCreated+'</div>'+
						'</li>');
						
						/* Adding the tab to the UL container: */
						$('#tabRow').append(tab);
		
					}); // close .each loop
					makeTabsDroppable();
				});  // close newTab ajax call
}

function addPage(action,id) {
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	curr_month++;
	var curr_year = d.getFullYear();
	var curr_hour = d.getHours();
	var curr_min = d.getMinutes();
	
	currentDate = curr_year + "-" + curr_month + "-" + curr_date + " " + curr_hour + ":" + curr_min ;
	
		$.getJSON("ajax.php",{'action':action,'id':id,rand:Math.random()},function(Pages){

			$.each(Pages, function(i){

				var pageCreated = Pages[i].date_created;
				pageCreated = pageCreated.substring(0,pageCreated.length-3); // remove the seconds section from the date
			
				/* Sequentially creating the pages and assigning a color from the array: */
				var pageItem = $('<li id="Page-'+Pages[i].page_id+'" class="pageItem">'+
								'<div class="pageTitle">'+
								'<a href="" id="Page-'+Pages[i].page_id+'" class="page">'+
								'<span class="left"></span><span class="pageName">'+Pages[i].name+
									'</span><span class="right"></span></a>'+
								'</div>'+
								'<div class="pageCreated">'+pageCreated+'</div>'+
								'<div class="columns">'+Pages[i].columns+'</div>'+
							'</li>');
				
				/* Setting the page data for each hyperlink: */
				pageItem.find('a').data('page','ajax.php?action=lists&page_id='+Pages[i].page_id);


				// remove the new page and settings links so they can be added back to the end
				$('#newPageItem').remove();

				/* Adding the tab to the UL container: */
				$('#pageList').append(pageItem).fadeIn('fast');

				// add link to create a new page
				var newPage = $(
					'<li id="newPageItem">'+
						'<abbr title="'+_('New Page')+'"><a href="" class="notebookIcons newPage">'+
						'</a></abbr>'+
					'</li>');
					
					$('#pageList').append(newPage);
										
				$('#pageList').append(newPage).fadeIn('fast');

			});  // close $.each(Pages)
			makePagesDroppable();
		});  // close newTab ajax call
}
