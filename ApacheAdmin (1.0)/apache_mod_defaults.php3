<html>
<!--Defaults-->
<script language=javascript>
mod_name='Apache Admin Defaults';
mod_vrsn='1.0';
mod_auth='Solomon Rutzky';
mod_info='This module lets you the change the colors and font for this admininstrator.\n';
mod_info+=' You should keep the browser window at a medium size so that you can view the\n';
mod_info+=' test window at the same time.  Once you select one of the menus with your \n';
mod_info+='mouse, you can then use your arrow keys to scroll through the list and the \n';
mod_info+='changes will take affect immediately in the test window.  Unlike the other \n';
mod_info+=' modules, clicking on the <i>Save</i> button here WILL write the changes to \n';
mod_info+='the server immediately.  Hence, you do not need to use the <i>Save Changes</i>\n';
mod_info+=' button in the top frame.<br>\n';
mod_date='03/05/1999';
</script>

<head><title>Apache Admin Module (defaults)</title></head>

<script language=javascript>
  function test_win()
  {
    data="<html>\n<head>\n<title>Test Window</title>\n</head>\n<body bgcolor=#";
    data+=document.defaults.bgcolor.options[document.defaults.bgcolor.selectedIndex].text+" text=#";
    data+=document.defaults.text.options[document.defaults.text.selectedIndex].text+" link=#";
    data+=document.defaults.link.options[document.defaults.link.selectedIndex].text+">\n";
    data+="<font face="+document.defaults.font.options[document.defaults.font.selectedIndex].text+">\n";
    data+="<br>\n<center>\n<font size=+2>This is some <a href=#>sample</a> text..<br>\n<FORM>\n<INPUT TYPE=BUTTON VALUE=Close onClick=window.close();>\n</FORM>\n</body>\n</html>\n";
    win=window.open("","Test_Window","height=150,width=200");
    win.document.open();
    win.document.write(data);
    win.document.close();    
  }

  function select_font(font_name)
  {
    for(i=0;i<document.defaults.font.length;i++)
    {
      if(document.defaults.font.options[i].text==font_name)
      {
        document.defaults.font.options[i].selected=1;
        break;
      }
    }
    return true;
  }
</script>

<?
error_reporting(7);

  function safe_color_options($saved_value)
  {
    $found=0;
    for($r_index=0;$r_index<16;$r_index+=3)
    {
      for($g_index=0;$g_index<16;$g_index+=3)
      {
        for($b_index=0;$b_index<16;$b_index+=3)
        {
          $color=sprintf("%X%X%X%X%X%X",$r_index,$r_index,$g_index,$g_index,$b_index,$b_index);
          if($found||$color!=$saved_value)
          {
            printf("<OPTION>%s\n",$color);
          }
          else
          {
            printf("<OPTION SELECTED>%s\n",$color);
            $found=1;
          }
        }
      }
    }
  }

  if(!$button)
  {
    $fp=fopen("apache_admin.css","r");
    $line=fgets($fp,20);
    $line=fgets($fp,20);
    $line=fgets($fp,20);
    $bgcolor=substr(fgets($fp,40),20,6);
    $text=substr(fgets($fp,40),15,6);
    $font=substr(fgets($fp,40),20);
    $font=substr($font,0,strstr($font,";")-2);
    $line=fgets($fp,20);
    $link=substr(fgets($fp,40),15,6);
    if($bgcolor==$text)
    {
      $bgcolor="ffffff";
      $text="000000";
    }
    fclose($fp);
  }
  else
  {
    $file=fopen("apache_admin.css","w");
    $data="<style>\n<!--\nBODY {\n       background: #".$bgcolor.";\n       color: #".$text.";\n       font-family: ".$font.";\n     }\nA    { color: #".$link."; }\nP    { color: #".$text."; font-family: ".$font."; }\n-->\n</style>\n";
    fputs($file,$data);
    fclose($file);
  }
#       background: #339999;
#       color: #006666;
#       font-family: arial;
#A    { color: #66CC99; }

  printf("<body bgcolor=#ffffff onLoad=select_font('%s');>\n",$font);

?>

<center>
<br>

<FORM ACTION=apache_mod_defaults.php3 METHOD=POST NAME=defaults>

<table border=0>
<tr><td>Font:</td><td><SELECT NAME=font onChange=test_win();>
<OPTION>Arial
<OPTION>Bookman
<OPTION>Courier
<OPTION>System
<OPTION>Times
</SELECT></td></tr>

<tr><td>BackGround Color:</td><td>
<SELECT NAME=bgcolor onChange=test_win();>
<? safe_color_options($bgcolor); ?>
</SELECT></td></tr>

<tr><td>Text Color:</td><td>
<SELECT NAME=text onChange=test_win();>
<? safe_color_options($text); ?>
</SELECT></td></tr>

<tr><td>Link Color:</td><td>
<SELECT NAME=link onChange=test_win();>
<? safe_color_options($link); ?>
</SELECT></td></tr>

</table>

<br>
<INPUT TYPE=RESET VALUE=Reset>
<INPUT TYPE=BUTTON VALUE=Test onClick=test_win();>
<INPUT TYPE=SUBMIT NAME=button VALUE=Save>

</FORM>

</center>

</body>
</html>
