<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="en" lang="en"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title> ©GithubManager v1.0, All Rights Reserved.</title>

<?php
date_default_timezone_set('America/Chicago');

require_once 'vendor/autoload.php';

$client = new Github\Client();

$client->authenticate("raychorn", "peekab00", Github\Client::AUTH_HTTP_PASSWORD);
?>
<?php
$__uri__ = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].(($_SERVER['SERVER_PORT'] != '80') ? ':'.$_SERVER['SERVER_PORT'] : '').$_SERVER["REQUEST_URI"];
?>

<link rel="icon" type="image/png" href="index_files/VyperLogixCorp-icon60x57.png">

	<script src="index_files/ga.js" async="" type="text/javascript"></script><script type="text/javascript" src="index_files/dynamic.js"></script>
	<script type="text/javascript" src="index_files/jquery.js"></script>
	<script type="text/javascript" src="index_files/jquery-ui.js"></script>
	<script type="text/javascript" src="index_files/jquery_003.js"></script>
	<script type="text/javascript" src="index_files/jquery_002.js"></script>
	<script type="text/javascript" src="index_files/jquery_005.js"></script>
	<script type="text/javascript" src="index_files/additional-methods.js"></script>
	<script type="text/javascript" src="index_files/jquery_004.js"></script>
	<script type="text/javascript" src="index_files/00_constants.js"></script>
	<script type="text/javascript" src="index_files/01_objectExplainer.js"></script>
	<script type="text/javascript" src="index_files/02_AnchorPosition.js"></script>
    
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-9863220-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
 
	<script type="text/javascript">
		var __default_options__ = {html:'',options:{minWidth:700,minHeight:500,width:700,height:500,modal:true}};
		var ___options___ = {
			'/':__default_options__
		};
		var ___pageSelector___ = null;

		$(document).ready(function() {
			include_css('http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/start/jquery-ui.css');
			
			function getCookie(name) {
				var cookieValue = null;
				if (document.cookie && document.cookie != '') {
					var cookies = document.cookie.split(';');
					for (var i = 0; i < cookies.length; i++) {
						var cookie = jQuery.trim(cookies[i]);
						// Does this cookie string begin with the name we want?
						if (cookie.substring(0, name.length + 1) == (name + '=')) {
							cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
							break;
						}
					}
				}
				return cookieValue;
			}
			var csrftoken = getCookie('csrftoken');
	
			function csrfSafeMethod(method) {
				// these HTTP methods do not require CSRF protection
				return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
			}

			function sameOrigin(url) {
				// test that a given url is a same-origin URL
				// url could be relative or scheme relative or absolute
				var host = document.location.host; // host + port
				var protocol = document.location.protocol;
				var sr_origin = '//' + host;
				var origin = protocol + sr_origin;
				// Allow absolute or scheme relative URLs to same origin
				return (url == origin || url.slice(0, origin.length + 1) == origin + '/') ||
					(url == sr_origin || url.slice(0, sr_origin.length + 1) == sr_origin + '/') ||
					// or any other URL that isn't scheme relative or absolute i.e relative.
					!(/^(\/\/|http:|https:).*/.test(url));
			}

			$.ajaxSetup({
				beforeSend: function(xhr, settings) {
					if (!csrfSafeMethod(settings.type) && sameOrigin(settings.url)) {
						// Send the token to same-origin, relative URLs only.
						// Send the token only if the method warrants CSRF protection
						// Using the CSRFToken value acquired earlier
						xhr.setRequestHeader("X-CSRFToken", csrftoken);
					}
				}
			});

			try {
				include_css('static/main.css');
			} catch (e) {}
			var href_tokens = window.location.href.split('/');
			___pageSelector___ = href_tokens[href_tokens.length-1];
			___pageSelector___ = (___pageSelector___.length == 0) ? '/' : ___pageSelector___;

			var __icons__ = [
				'<a href="http://www.vyperlogix.com" target="_top"><img src="/static/images/VyperLogixCorp-icon(60x57).png" height="101" /></a>',
				'<a href="http://github.com" target="_top"><img src="/static/images/github_icon.png" height="101" /></a>'
			];
			var __iconNum__ = __icons__.length-1;
			var __iconElement__ = $("#logo");
			function change_site_icon() {
				if (__iconElement__) {
					__iconElement__.html(__icons__[__iconNum__]);
					__iconNum__++;
					if (__iconNum__ > __icons__.length-1) {
						__iconNum__ = 0;
					}
					setTimeout(function(){change_site_icon();},5000);
				}
			}

			$("#logo").hide();

			$("#popUpDialog").hide();
			$("#popUpDialog").dialog({
				autoOpen: false,
				minWidth:600, 
				minHeight:200, 
				width:600, 
				height:500
			});

			$('#ajaxBusy').css({
				display:"none",
				margin:"0px",
				paddingLeft:"0px",
				paddingRight:"0px",
				paddingTop:"0px",
				paddingBottom:"0px",
				position: "absolute",
				top: "40px",
				left: "0px",
				width:"auto"
			});
			$(document).ajaxStart(function(){ 
				var pos = $('#btn_logout').position();
				$('#ajaxBusy').css({"left":(pos.left-70)+"px"});
				$('#ajaxBusy').show(); 
			}).ajaxStop(function(){ 
				$('#ajaxBusy').hide();
			});
  		});
		
	function not_yet_implemented() {
		alert('Not Yet Implemented !!!\nCheck back again for more...');
	}

    </script>

    
</head>

<body>
<header>
	<div>
      <table id="navigation_content_holder" align="center" border="0" cellpadding="0" cellspacing="0" width="1000">
        <tbody><tr>
        <td align="left" valign="top">
            <div style="display: none;" id="logo">
                <a href="http://www.vyperlogix.com/" target="_top"><img src="index_files/VyperLogixCorp-icon60x57.png" height="101"></a>
            </div>
        
            <!--start of navigation_holder-->
            <div style="padding-left:0px;">
                <div id="navigation"> 
                  <span class="navigation"><a id="anchor_navbar_home" href="<?php echo($__uri__); ?>" target="_top">HOME</a> </span>     

                </div>
          </div>
        </td>
        <td align="right" valign="top" width="*">

            <div>
            </div>


        </td>
        </tr>
        <tr>
        	<td bgcolor="white">
<div id="div_ERROR" style="color:#F00;"></div>
<div style="display: none; margin: 0px; padding: 0px; position: absolute; top: 40px; left: 0px; width: auto;" id="ajaxBusy"><img src="index_files/ajax-loader.gif" border="0"></div>
            </td>
        </tr>
      </tbody></table>
	</div>
    
    <div style="clear: both"></div>
</header>

<div>
            <table id="container" align="center" border="0" cellpadding="0" cellspacing="0" width="1000">
              <tbody><tr>
                <td id="main_content_holder" align="left" valign="top">
                
                	<table width="100%">
                		<tbody><tr>
                			<td id="main_background" width="80%">
                			</td>
                			<td align="right" valign="top" width="*">

		                   		<div id="main_sign_in_holder" style="display:none;">
		                        </div>

			                   <div id="main_sign_in_holder2">
			                        GithubManager™ is the fastest way to get your
 Zip File that contains a Project or blob of code into your Github Repo.
								</div>
                			</td>
                		</tr>
                	</tbody></table>


                <div style="clear: both">&nbsp;</div>
                <div style="clear: both; padding-top:0px;">&nbsp;</div>
                        <div id="shipment_container">              
                                        
                                <div class="bubble">
                                <div class="rectangle"><div class="header_font">Want Code in YOUR Github Repo Now ?</div></div>
                                <div class="triangle-l">&nbsp;</div>
                                
                                <div class="info">
                                
                                <p>
                              </p><div class="right_box"><img src="index_files/github-logo-social-coding.jpg" width="200"></div>
                                <p></p>
                                <br>
                                	<ul id="django_cloud_info" style="list-style-type:square">
                                        <li><nobr>Instant Results !!!</nobr></li>
                                        <li><nobr>Instant Graficiation !!!</nobr></li>
                                        <li><nobr>Instant Repo !!!</nobr></li>
                                        <li><nobr>Just one mouse click away...</nobr></li>
                                        <li><nobr>Reduce your development cost !!!</nobr></li>
                                        <li><nobr>Reduce your time to market !!!</nobr></li>
                                	</ul>
                                <br>
                                

                                
                                </div>
                                </div>
                        </div> 
                         <div id="django_rightside_container">              
                                        
                                <div class="bubble">
                                <div class="rectangle"><div class="header_font">Need Github Support?</div></div>
                                <div class="triangle-l"></div>
                                
                                <div class="infoRight">
                                	<table style="display:inline; padding-left: -20px;" width="100%">
                                		<tbody><tr>
                                			<td align="left">
			                                	<ul id="django_programmer_info" style="list-style-type:square">
			                                        <li><nobr>Github Guru's for Hire !!!</nobr></li>
			                                        <li>Let our Github Gurus do all the work for you !!!</li>
			                                        <li>We push your code into your Repos !!!</li>
			                                	</ul>
                                			</td>
                                			<td align="right"><img src="index_files/programmer206x240.jpg" width="190"></td>
                                		</tr>
                                	</tbody></table>

                                

                                
                                </div>
                                </div>
                        </div>
                </td>
              </tr>
            </tbody></table>   
</div>
<div style="clear: both;"></div>

<table id="footer_content_holder" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td>
    <div class="footer_left_box">
    <div class="footer_font">©&nbsp;2013 GitHubManager™&nbsp;<a href="http://www.vyperlogix.com/" target="_top">VyperLogix.Com</a>, All rights reserved.<br>All References to GitHub are Copyrighted/Trademarks by and of <a href="http://github.com/" target="_top">Github</a>.</div>
    </div>
    
     <div class="footer_right_box">
    <div class="footer_font">
    </div>
    </div></td>
  </tr>
</tbody></table>


<div aria-labelledby="ui-dialog-title-popUpDialog" role="dialog" tabindex="-1" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" style="display: none; position: absolute; overflow: hidden; z-index: 1000; outline: 0px none;"><div style="-moz-user-select: none;" unselectable="on" class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix"><span style="-moz-user-select: none;" unselectable="on" id="ui-dialog-title-popUpDialog" class="ui-dialog-title">???</span><a style="-moz-user-select: none;" unselectable="on" role="button" class="ui-dialog-titlebar-close ui-corner-all" href="#"><span style="-moz-user-select: none;" unselectable="on" class="ui-icon ui-icon-closethick">close</span></a></div><div class="ui-dialog-content ui-widget-content" id="popUpDialog" style="">
	<p id="popup_dialog_content"></p>
</div><div style="-moz-user-select: none;" unselectable="on" class="ui-resizable-handle ui-resizable-n"></div><div style="-moz-user-select: none;" unselectable="on" class="ui-resizable-handle ui-resizable-e"></div><div style="-moz-user-select: none;" unselectable="on" class="ui-resizable-handle ui-resizable-s"></div><div style="-moz-user-select: none;" unselectable="on" class="ui-resizable-handle ui-resizable-w"></div><div unselectable="on" style="z-index: 1001; -moz-user-select: none;" class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se"></div><div unselectable="on" style="z-index: 1002; -moz-user-select: none;" class="ui-resizable-handle ui-resizable-sw"></div><div unselectable="on" style="z-index: 1003; -moz-user-select: none;" class="ui-resizable-handle ui-resizable-ne"></div><div unselectable="on" style="z-index: 1004; -moz-user-select: none;" class="ui-resizable-handle ui-resizable-nw"></div></div></body></html>