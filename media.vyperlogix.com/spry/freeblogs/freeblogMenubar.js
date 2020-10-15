var freeblog_timerID = -1;
var s_freeblog = '';
function init_freeblog_menubar() {
	MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"http://media.vyperlogix.com/spry/freeblogs/header/SpryMenuBarDownHover.gif", imgRight:"http://media.vyperlogix.com/spry/freeblogs/header/SpryMenuBarRightHover.gif"});
}
function freeblog_menubar() {
	s_freeblog += '<ul id="MenuBar1" class="MenuBarHorizontal">';
	s_freeblog += '<li><NOBR><a class="MenuBarItemSubmenu" href="http://python2.wordpress.com/about/">The FREE Blogs Network<img class="MenuBarItemSubmenuImgR" src="http://media.vyperlogix.com/icons/blogger/Write_100x100.gif" border="0"/></a></NOBR>';
	s_freeblog += '<ul>';
	s_freeblog += '<li><NOBR><a href="http://vyperlogix.net/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/blog_100x100.gif" border="0"/>The Vyper Logix Network</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://bloggercity.net/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/D_100x100.gif" border="0"/>BloggerCity.Net&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://mybloogz.com/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/P_100x100.gif" border="0"/>MyBloogz.Com&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://bloggerville.net/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/blog_100x100.gif" border="0"/>Bloggerville.Net&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://easyblogz.com/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/D_100x100.gif" border="0"/>Easy FREE Blogz</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://easyblogz.net/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/blog_100x100.gif" border="0"/>EasyBlogz.Net</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://mybloogz.net/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/blog_100x100.gif" border="0"/>MyBloogz.Net</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://ezblogz.net/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/blog_100x100.gif" border="0"/>EzBlogz.Net</a></NOBR></li>';
	s_freeblog += '<li><NOBR><a href="http://bloggercity.us/"><img class="MenuBarItemSubmenuImgL" src="http://media.vyperlogix.com/icons/blogger/C_100x100.gif" border="0"/>BloggerCity.Us</a></NOBR></li>';
	s_freeblog += '</ul>';
	s_freeblog += '</li>';
	s_freeblog += '</ul>';

	freeblog_timerID = setInterval("var obj = document.getElementById('div_MenuBar1'); if (obj) { clearInterval(freeblog_timerID); freeblog_timerID = -1; obj.innerHTML = s_freeblog; init_freeblog_menubar();}",250);
}
freeblog_menubar();
