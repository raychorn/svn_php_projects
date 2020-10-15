Surreal ToDo

Download from SourceForge at http://www.sourceforge.net/projects/surrealtodo/

Project home and demo located at http://www.getsurreal.com/surrealtodo/

Author: James LeRoy


What's New

	v0.6.1.2
		Updated install and upgrade scripts
		Added Danish translation
		Added German translation

	v0.6.1
		Fixed bug with Advanced Edit Save
		Added French Translation (user contributed)

	v0.6
		Added advanced text editing (hyperlinks, images, font styles, font colors, lists, tables, etc)
		Improved performance for enabling browser caching of non dynamic data
		Improved performance by combining images into sprites
		Improved performance by minifying javascript and CSS files
		Added ability to choose format of completed items (strikethrough, gray, italic, check mark)
		Added ability to be translated to any language
			Added Russian Translation (user contributed)
			Added Traditional Chinese Translation (user contributed)
			Added Simplified Chinese Translation (user contributed)
			Added Japanese Translation (google translation needs user review)
		Added ability to choose 1, 2 or 3 columns per page

	v0.5.2
		Added ability for user to set date and time format
		Added ability to edit dates
		Added theme capability
			Added Dusk to Dawn theme contributed by Alexufo
			
	v0.5.1
		Added ability to set hyperlink for list items
		Added ability to duplicate a tab, page or list allowing template functionality

	v0.5
		Added trashcan feature to allow restoring deleted objects
		Added search feature

	v0.4.1
		Disable custom context menu when editing in order to restore default contextmenu with cut, copy, paste, etc. options
		Fix - do not allow saving empty text
		Fix - clicking on active tab cleared page contents
		Added settings configuration to set default text for new Tabs, Pages, Lists and Items
		Added check if new version is available
		Fix - edit multiple tasks only able to save or cancel on last one edited
		spellcheck - use browsers built-in spell check
		removed 'new page' and 'settings' as sortable
		Added CSS to install script
		Added CSS to update script
		Fix - new tasks are not sortable in new lists
		Cleaned up some undefined php variables

	v0.4
		Multiple Pages per Tab
		Move Lists to different Pages via drag and drop
		Move Lists to different Tabs via drag and drop
		Move Pages to different Tabs via drag and drop


	v0.3.1
		Front end changes:
		  -lists organized into 3 columns
		  -list expand and collapse changed to accordion
		Fixes:
		  -fix "ESC" doesn't work to cancel edit with Google Chrome and Safari
		  -on tab edit set that tab to be active
		Additions:
		  -added install script
		  -added upgrade script
	
Installation

	Copy files to your website folder.
	Edit connect.php to supply your database connection settings.
	Browse to your site and the install script will setup your database.


Upgrade

	If you are running a version prior to v0.3 you must first upgrade to v0.3 manually.
	
	Backup your database.
	
	Document your database connection settings.
	Delete or rename the folder containing the older version.
	Extract or copy files to your website folder.
	Edit connect.php with your database connection settings.
	Browse to your site and the upgrade script will update your database.
