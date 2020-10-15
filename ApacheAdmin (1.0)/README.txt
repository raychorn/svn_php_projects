Apache Admin
by Solomon Rutzky
of Chicago Net Visions, Inc. (http://www.cnvisions.com/)

NOTES
=====
1) this tool requires PHP3 (available at http://www.php.net/)

2) this requires that PHP NOT be configured for safe mode (reason
   being that the httpd.conf file is usually not in the doc_root and
   safe_mode restricts all access to the doc_root.)

3) as with any web-based application, security is an issue (especially
   when allowing the editing of a web-server config file).  So please
   make use of the .htaccess file as I am not to be blamed for any
   problems and/or security breaches now that you have been warned. 

4) as this is a web-based admin tool, if you save bad data AND attempt
   to restart the server, it might not restart.  If this happens, you
   will not have any ability to fix the problem with this tool. 
   [I am looking into using the apachectl checkcfg (or whatever that
    option is) to do a pre-test so that bad data is not saved.]


INSTALLATION AND CONFIGURATION
==============================
1) untar/unzip archive into whatever web-accessable directory you would like to run
   this from.

2) edit the .htaccess file and set the AuthUserFile option to the appropriate directory
   and filename that your administrator password will be/is kept in.

3) in the apache_admin_main.php3 script, edit the following three variables:
   $conf_file="httpd.conf";
   $conf_dir="/path/to/conf_file"; #(no trailing slash!)
   $restart_cmd="/usr/local/apache/sbin/apachectl restart";
   '$conf_file' should point to the name of the default config file.
   '$conf_dir' should point to the full path of where the config file is stored.
   '$restat_cmd' should point to the appropriate restart command.

4) edit any config variables that may be in the module files.  For example, 
   apache_mod_vhosts has "base_dir=" which should point to the base directory where
   virtual hosts will be stored.

5) point your browser to whatever URL is the directory for apache_admin:
   http://your.site.com/apache_admin/
   or wherever.

6) if you want to change the font and/or colors, go to the defaults module.

7) if you have any questions, use the 'HELP' button; the information will change
   depending on what module you are in.


USE
===
1) Select a module from the list and click on the 'Continue' button
2) make any changes
3) Select other modules to make additional changes as you see fit
4) when done making changes, click on the 'Save Changes' button
5) Check the box if you want the changes to take affect immediately, or just click
   the 'Ok' button to commit the changes to the actual config file
6) If you have made changes that you do not want to save and would like to start over,
   you can revert to the original config file by clicking on the 'Reload' button


FEATURES
========
1) The majority of the application is JavaScript based so it runs on the local machine
   (which is more than likely underutilized) thus allowing any number of changes to be
   made without placing additional load on the server.  Only reading the config file upon
   start up, saving the changes, and loading a new module ever hit the server.

2) Only specific directives that a module is designed to manipulate are touched.  This 
   means that any unknown directives or any type or configuration of comment blocks is
   ignored and left intact (with the exception of anything in a <> block.  For example,
   comments in a <DIRECTORY> block will be stripped out.)  This allows for the config
   file to be edited manually or new and unknown directives to be placed in it while
   still allowing it to be maintained by this tool (and without having to worry about
   those changes being deleted).

3) The background color, link color, font color, and font face can all be changed very
   easily by using the DEFAULTS module.  Any changes can be tested prior to saving by
   making the browser take up no more than 3/4 of the screen and hitting the 'Test'
   button in the DEFAULTS module.  This will pop up a small test window.  If using a 
   Windows OS, you will be able to select a particular drop-down list and after a
   selection is made from that list, you can use the up and down arrows to scroll
   through the valid options.  Any changes are instantly reflected in the test window.
   The color drop-down lists consist of only browser-safe colors.

4) The 'Help' button will display context-sensitive information.  Meaning, there will be
   module specific information based on what module is currently loaded.  If you are
   of anything in terms of a specific module, just load it and click on the 'Help'
   button and learn about it.


CREATING A NEW MODULE
=====================
If you would like to add a module of your own to this utility, that can be done 
fairly easily by following these guidelines:

1) name the module file starting with "apache_mod_".
   For example:  apache_mod_test.html
   if the filename does not start with "apache_mod_", it will never appear in
   the drop-down list.  The file extension does not matter, hence you can create
   standard HTML files with just JavaScript code, or PHP3 files or whatever.

2) line #2 needs to be, within HTML comment tags, the module name that will appear
   in the drop-down list.  So, the first two lines should look like:
   <html>
   <!--Test Stuff-->
   which would then show "Test Stuff" in the drop-down list.

3) Anywhere in the HTML after line 2 (though usually starting at line 3), you should 
   include a JavaScript block that sets certain module information variables that are
   used for the online help as follows:
   mod_name = full name of module
   mod_vrsn = version number
   mod_auth = name of module creator
   mod_date = date of creation or last update
   mod_info = information about the module, how it is to be used, etc. (it is a good 
              idea to include information specific to each Apache directive that can
              be manipulated by the module.)  This string can contain any HTML tags.

   For example:

<script language=javascript>
mod_name='View Config File';
mod_vrsn='1.0';
mod_auth='Solomon Rutzky';
mod_info='This module lets you view the entire contents of the config file showing any';
mod_info+=' changes that have been made so far.\n  There is no editing capability in this';
mod_info+=' module.\n  Any changes you make will NOT be saved.  You can, however, cut and';
mod_info+=' paste the contents into a local editor for the purposes of printing or having';
mod_info+=' a local backup or whatever.\n';
mod_date='03/01/1999';
</script>

4) The configuration file data exists in a JavaScript object stored in the top frame.  
   That object is:  parent.menu.conf
   The object is an array in which each element is a line from the config file; the first
   line starts at element 0 and continues on until the end.  The end, or last line, of the
   file can easily be determined by referencing:  parent.menu.conf.length
   Any module can manipulate that array, and the changes are only saved to the real config
   file when the 'Save Changes' button is clicked and the save action is confirmed.

