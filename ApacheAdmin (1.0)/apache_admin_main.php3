<html>

<!--
  ************************************************************************
  *                                                                      *
  * Program: apache_admin_main.php3                                      *
  * Description:  Web-based (JavaScript & PHP3) graphical                *
  *               administration tool for the Apache web server          *
  *               server.                                                *
  * Author:  Solomon Rutzky                                              *
  * Company: Chicago Net Visions, Inc.                                   *
  * Date:      Version:                                                  *
  * 09/05/1999 1.0                                                       *
  *                                                                      *
  * This script is Copyright (C) 1999 by Solomon Rutzky.                 *
  * Modifications are allowed so long as the copyright information,      *
  *   both here and in the help_window (including links) remain intact.  * 
  * Please view the GPL.txt file for complete details.                   *
  *                                                                      *
  * This program is free software; you can redistribute it and/or modify *
  * it under the terms of the GNU General Public License as published by *
  * the Free Software Foundation; either version 2 of the License, or    *
  * (at your option) any later version.                                  *
  *                                                                      *
  * This program is distributed in the hope that it will be useful,      *
  * but WITHOUT ANY WARRANTY; without even the implied warranty of       *
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        *
  * GNU General Public License for more details.                         *
  *                                                                      *
  * You should have received a copy of the GNU General Public License    *
  * along with this program; if not, write to the:                       *
  *    Free Software Foundation, Inc.                                    *
  *    59 Temple Place,  Suite 330                                       *
  *    Boston, MA  02111-1307                                            *
  *    USA                                                               *
  *                                                                      *
  ************************************************************************
-->

<head>

<?
error_reporting(7);

  if(!$conf_file)
  {
    $conf_file="httpd.conf";
  }
  if(!$conf_dir)
  {
    $conf_dir="/usr/local/apache/conf"; #(no trailing slash!)
  }
  $restart_cmd="/usr/local/apache/bin/apachectl restart";

  switch($action)
  {
    case 'menu':
      printf("<title>Apache Admin (menu)</title>\n<link rel=stylesheet href=apache_admin.css type=text/css>\n</head>\n<body bgcolor=#ffffff oonLoad=parent.module.reload();>\n<center>\n");
?>
<script language=javascript>
  function save_window()
  {
    save_html='<html>\n<head>\n<title>Save Conf File</title>\n<link rel=stylesheet href=apache_admin.css type=text/css>\n</head>\n<body>\n<center>\nAre you sure that you want to save these changes?<br>';
    save_html+='\n\n<FORM ACTION=apache_admin_main.php3 METHOD=POST TARGET=_self>\n<table width=90% border=0><tr>\n<td width=50% align=center><p>';
    save_html+='<INPUT TYPE=SUBMIT NAME=action VALUE=Save></p></td>\n<td align=center>\n<INPUT TYPE=BUTTON VALUE=Cancel onClick=window.close();>';
    save_html+='</td>\n</tr>\n<tr><td colspan=2><br><p></td></tr>\n<tr>\n<td align=center><INPUT TYPE=CHECKBOX NAME=restart VALUE=1></td>\n<td>';
    save_html+='<p>Restart Web Server as well so that changes take effect?</td>\n</tr></table>\n\n';
    for(i=0;i<conf.length;i++)
    {
      save_html+='<INPUT TYPE=HIDDEN NAME=line[] VALUE=\''+conf[i]+'\'>\n';
    }   
    save_html+='\n<INPUT TYPE=HIDDEN NAME=conf_dir VALUE=<? printf("%s",$conf_dir);?>>\n<INPUT TYPE=HIDDEN NAME=conf_file VALUE=<? printf("%s",$conf_file);?>>\n</FORM>\n\n</body>\n</html>\n';
    win=window.open('','Save_Window','height=200,width=250');
    win.document.open();
    win.document.write(save_html);
    win.document.close();
  }

  function help_window()
  {
    help_html='<html>\n<head>\n<title>Apache Admin Help</title>\n<link rel=stylesheet href=apache_admin.css type=text/css>\n</head>\n<body>\n\n<center>\n\n<table width=95% border=0 cellpadding=5>\n<tr>';
    help_html+='<th valign=top align=right>About:</th><td><p>&copy; 1999 <a href="mailto:srutzky@cnvisions.com?subject=Apache Admin">Solomon Rutzky';
    help_html+='</a>\n (<a href=http://www.shlomo.com/ target=shlomo>www.shlomo.com</a>)<br>of <a href=http://www.cnvisions.com/ target=cnv>';
    help_html+='Chicago Net Visions, Inc.</a><br>\nVersion 1.0 - 09/06/1999<br>Please see the <a href=GPL.txt target=gpl>license</a> for complete details.<br></p></td></tr>\n<tr><th valign=top align=right>';
    help_html+='General:</th><td><p>Apache Admin is used to administrate the httpd.conf file for the Apache web server.\n  This file should ';
    help_html+='contain all of the contents of the srm.conf and access.conf files as well so that all options can be administrated from ';
    help_html+='this single application.\n  The <i>Reload</i> button is used to cancel and changes made and reloads the previous contents of the ';
    help_html+='config file.\n  The <i>Save Changes</i> button is used to commit all changes made to the actual config file on the server.\n  This ';
    help_html+='means that you can make any number of changes and if mistakes are made, simply <i>Reload</i> the config file before saving any ';
    help_html+='changes.\n  The drop down list will contain whatever configuration modules are available to use.\n  If you can\'t figure out what the ';
    help_html+='<i>Help</i> button does by now, then please step away from the computer.<br><br></p></td></tr>\n';
    if(top.module.mod_name)
    {
      help_html+='<tr><th valign=top align=right>Module:</th>\n<td><p>Name: '+top.module.mod_name+'<br>\nVersion: '+top.module.mod_vrsn+'<br>\nAuthor: ';
      help_html+=top.module.mod_auth+'<br>\nDate: '+top.module.mod_date+'<br>\n<br>\n'+top.module.mod_info+'\n<p></td></tr>\n';
    }
    else
    {
      help_html+='<tr><th valign=top align=right>Module:</th>\n<td>This area will contain module specific information<br>\n</td></tr>\n';
    }
    help_html+='<tr><td colspan=2 align=center><p><br><FORM><INPUT TYPE=BUTTON VALUE=Done onClick=window.close();></FORM></p></td></tr>\n</table>\n\n</body>\n</html>\n';
    win=window.open('','Help_Window','width=550,height=350,scrollbars,resizable');
    win.document.open();
    win.document.write(help_html);
    win.document.close();
  }

  conf=new Array;
    
<?
      $fp=fopen($conf_dir."/".$conf_file,"r");
      $num=0;
      while($line=fgets($fp,2000))
      {
        printf("  conf[%s]='%s'\n",$num++,addslashes(strtr(chop($line),"'","|")));
      }
      fclose($fp);
      printf("</script>\n\n");

      printf("<FORM METHOD=GET NAME=menu onSubmit='document.menu.action=document.menu.mod.options[this.mod.selectedIndex].value;' TARGET=module>\n<table width=90%% border=0 cellpadding=0 cellspacing=0>\n");
      printf("<tr><td width=33%% align=left><p>File: <INPUT TYPE=BUTTON VALUE=Load onClick=window.open('apache_admin_main.php3?action=Load&conf_dir=%s&conf_file=%s','Load_Window','width=450,height=350,scrollbars,resizable');>&nbsp;<INPUT TYPE=BUTTON VALUE='Save Changes' onClick=save_window();></td><td width=33%% aign=center>\n<SELECT NAME=mod>\n",$conf_dir,$conf_file);
      $dir=opendir(".");
      while($file=readdir($dir))
      {
        if(substr($file,0,11)=="apache_mod_")
        {
          $fp=fopen($file,"r");
          $line=fgets($fp,50);
          $line=fgets($fp,50);
          printf("<OPTION VALUE=%s>%s\n",$file,substr($line,4,strlen(chop($line))-7));
        }
      }
      closedir($dir);
      printf("</SELECT>\n");

      printf("<INPUT TYPE=SUBMIT NAME=ex_button VALUE=Continue>\n</td><td width=33%% align=right><p><INPUT TYPE=BUTTON VALUE='Help' onClick=help_window();>\n");
      printf("</td></tr>\n</table>\n\n</FORM>\n\n<p>\n<p>\n</center>\n\n</body>\n");
      break;

    case 'intro':
      printf("<title>Apache Admin (intro)</title>\n<link rel=stylesheet href=apache_admin.css type=text/css>\n</head>\n<body>\n<center>\n<br><br>\n<table width=85%% border=0><tr><td align=center><p>\n");
      readfile("apache_admin_intro.txt");
      printf("</td></tr></table>\n\n</body>\n");
      break;

    case 'Load':
      printf("<html>\n<head>\n<title>Load File</title>\n<link rel=stylesheet href=apache_admin.css type=text/css>\n</head>\n<body>\n\n<center>\n\n");
      printf("<FORM ACTION=apache_admin_main.php3?action=Load METHOD=POST NAME=dirs TARGET=_self>\n<table width=90%% border=0 cellpadding=0 cellspacing=0>\n<tr><td align=center><p>\n");
      printf("Currently in:<br> %s<br>\n<SELECT NAME=conf_dir onChange=if(document.dirs.conf_dir.selectedIndex){document.dirs.submit();}>\n<OPTION>Change to...\n<OPTION VALUE=%s>Up one level\n",$conf_dir,substr($conf_dir,0,strlen($conf_dir)-strlen(strrchr($conf_dir,'/'))));
      $dir=opendir($conf_dir);
      while($file=readdir($dir))
      {
        if(filetype($conf_dir."/".$file)=="dir")
        {
          switch($file)
          {
            case ".":
            case "..":
              break;
            default:
              $cdirs[]=$file;
          }
        }
        elseif(strrchr($file,'.')!='.html'&&strrchr($file,'.')!='.php3'&&strrchr($file,'.')!='.htaccess')
        {
          $cfiles[]=$file." ";
        }
      }
      closedir($dir);
      if($cdirs)
      {
        sort($cdirs);
      }
      for($x=0;$x<sizeof($cdirs);$x++)
      {
        printf("<OPTION VALUE=%s>%s</OPTION>\n",$conf_dir."/".$cdirs[$x],$cdirs[$x]);
      }
      printf("</SELECT></FORM>\n</td></tr>\n<tr><td align=center><FORM ACTION=apache_admin_main.php3?action=menu METHOD=POST NAME=files TARGET=menu>\n<INPUT TYPE=HIDDEN NAME=conf_dir VALUE=%s>\n<p>config file to edit:<br><SELECT NAME=conf_file SIZE=10>",$conf_dir);
      if($cfiles)
      {
        sort($cfiles);
      }
      for($x=0;$x<sizeof($cfiles);$x++)
      {
        if($cfiles[$x]!=$conf_file)
        {
          printf("<OPTION>%s</OPTION>\n",$cfiles[$x]);
        }
        else
        {
          printf("<OPTION SELECTED>%s</OPTION>\n",$cfiles[$x]);
        }
      }
      printf("</SELECT>\n<br><br><INPUT TYPE=BUTTON VALUE=Open onClick='document.files.submit(); setTimeout(\"window.close()\",2000);'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=BUTTON VALUE=Cancel onClick=window.close();>\n</td></tr></table>\n");
      break;

    case 'Save':
      printf("<html>\n<head>\n<title>File Saved</title>\n<link rel=stylesheet href=apache_admin.css type=text/css>\n</head>\n<body bgcolor=#ffffff>\n<center>\n");
      $fp=fopen($conf_dir."/".$conf_file,"w");
      for($index=0;$index<sizeof($line);$index++)
      {
        fputs($fp,strtr(stripslashes($line[$index]),"|","'")."\n");
      }
      fclose($fp);
      printf("<table width=90%% border=0>\n<tr><td align=center><p>The Apache configuration file has been saved.<br></td></tr>\n");
      if($restart)
      {
        system($restart_cmd);
        printf("<tr><td><br></td></tr>\n<tr><td align=center><p>Web server restarted successfully. Any changes should now be in effect.<br></td></tr>\n");
      }
      printf("<tr><td><br></td></tr>\n<tr><td align=center><p><FORM><INPUT TYPE=BUTTON VALUE=OK onClick=window.close();></FORM></td></tr>\n");
      printf("\n</table>\n\n</body>\n");
      break;
    default:
      printf("<title>Apache Admin</title>\n</head>\n<FRAMESET ROWS=45,* BORDER=1 FRAMEBORDER=1 FRAMESPACING=0>\n<FRAME NAME=menu SRC=apache_admin_main.php3?action=menu MARGINHEIGHT=0 SCROLLING=NO NORESIZE>\n<FRAME NAME=module SRC=apache_admin_main.php3?action=intro>\n</FRAMESET>\n\n");
      break;
  }
?>


</html>
