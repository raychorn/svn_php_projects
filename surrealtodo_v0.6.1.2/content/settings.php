<?php

if(isset($_POST['save_settings'])) {
	
if ($_POST) {
  $kv = array();
  foreach ($_POST as $key => $value) {
	if($key == 'save_settings') continue;  
	mysql_query("UPDATE config set value = '$value' where name = '$key'");
  }
}
	header('location: '.BASE_URL);
}
?>

<div id="notebook">
  <div id="tabs">
    <ul id="tabRow">
      <li class="tabItem">
		<div class="tabTitle">
    <!-- No need for tabs at this time 
			<a href="#" class="tab active"><span class="left"></span><div class="tabName">Default</div><span class="right"></span></a>
    --> 
			<a href="#" class="dialog"><span></span><div></div><span></span></a>
		</div>
	  </li>
    </ul>
    <!-- close tabRow --> 
  </div>
  <!-- close tabs -->
  
  <div class="clear"></div>
  <div id="tabContent">
    <div id="contentHolder">
      <div id="Settings">
        <form action="" method="post" name="form_settings">
          <table>
            <tr>
              <th><?php echo _('Site Name'); ?>:</th>
              <td><input name="default_site_name" type="text" value="<?php echo $GLOBALS["config"]["default_site_name"]; ?>" size="25" /></td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <hr />
          <table>
            <tr>
              <th><?php echo _('Date Format'); ?>:
                </td>
              </th>
              <td><input type="radio" name="date_format" id="dateYMD" value="Y-m-d" <?php if ($GLOBALS["config"]["date_format"] == 'Y-m-d') echo 'checked="yes"'; ?>/>
                <label for="dateYMD">YYYY-MM-DD</label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="radio" name="date_format" id="dateMDY" value="m-d-Y" <?php if ($GLOBALS["config"]["date_format"] == 'm-d-Y') echo 'checked="yes"'; ?>/>
                <label for="dateMDY">MM-DD-YYYY</label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="radio" name="date_format" id="dateDMY" value="d-m-Y" <?php if ($GLOBALS["config"]["date_format"] == 'd-m-Y') echo 'checked="yes"'; ?>/>
                <label for="dateDMY">DD-MM-YYYY</label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <th><?php echo _('Time Format'); ?>: </th>
              <td><input type="radio" name="time_format" id="time24" value="H:i" <?php if ($GLOBALS["config"]["time_format"] == 'H:i') echo 'checked="yes"'; ?>/>
                <label for="time24">24 hour</label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="radio" name="time_format" id="timeAMPM" value="h:ia" <?php if ($GLOBALS["config"]["time_format"] == 'h:ia') echo 'checked="yes"'; ?>/>
                <label for="timeAMPM">AM/PM</label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <th><?php echo _('Time Zone'); ?>:</th>
              <td><select title="<?php echo _('Time Zone'); ?>" name="timezone">
                <option selected="selected"><?php echo $GLOBALS["config"]["timezone"]; ?></option>
                <?php
                $timezones = DateTimeZone::listIdentifiers();
                sort($timezones);
        
                foreach ($timezones as $timezone)
                {
                echo "<option>$timezone</option>";
                }
			?>
              </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <th><?php echo _('Language'); ?>:</th>
              <td><select title="<?php echo _('Language'); ?>" name="locale">
                <option selected="selected"><?php echo $GLOBALS["config"]["locale"]; ?></option>
                <?php
// open the locale directory 
$localeDirectory = BASE_DIR."/locale/";
$directory = opendir($localeDirectory);

// get each entry
while($entryName = readdir($directory)) {
	if ($entryName == $GLOBALS["config"]["locale"]) continue;
	$localeList[] = $entryName;
}

// close directory
closedir($directory);
sort($localeList);

// loop through the array of files and print them all
        
                foreach ($localeList as $locales) {
					if (substr("$locales", 0, 1) != "."){ // don't list hidden files
						if (filetype($localeDirectory.$locales) == "file") continue;
						echo "<option>$locales</option>";
					}
                }
			?>
              </select>
              <a href="" class="translation-info">Translation Info</a>
              </td>
              <td>
                  <div class="translation-detail">
                    <ol>
                      <li>To translate Surreal ToDo copy the /locale/en_US.utf8 folder and rename it for your language and country. (See the gettext standards for <a href="http://www.gnu.org/software/gettext/manual/gettext.html#Language-Codes">language</a> and <a href="http://www.gnu.org/software/gettext/manual/gettext.html#Country-Codes">country</a> codes.)</li>
                      <li>Rename the LC_MESSAGES/en_US.utf8.po file.</li>
                      <li>Open the file with your utf8 compatible text editor and make your translations. You'll see a msgid line that has the English version. Your translations go into the msgstr line. </li>
                      <li>Send your .po file to james@getSurreal.com and I'll send you back the compiled version and include it with future releases.</li>
                    </ol>
                  </div>
              </td>
            </tr>
          </table>
          <hr />
        <table>            
            <tr>
              <th><?php echo _('Theme'); ?></th>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>
<?php
// open the theme directory 
$themeDirectory = BASE_DIR."/theme/";
$directory = opendir($themeDirectory);
$info = '';
$preview = '';

// get each entry
while($entryName = readdir($directory)) {
	$dirArray[] = $entryName;
}

// close directory
closedir($directory);

//	count elements in array
$indexCount	= count($dirArray);

// sort 'em
sort($dirArray);

// print 'em
// loop through the array of files and print them all
for($index=0; $index < $indexCount; $index++) {
	if($index == 0) echo '<table>';
	if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
		$theme_name = ucwords(str_replace("_"," ",$dirArray[$index]));
		if (filetype($themeDirectory.$dirArray[$index]) == "file") continue;
		if (file_exists($themeDirectory.$dirArray[$index].'/info.txt')) {
			$info = file_get_contents($themeDirectory.$dirArray[$index].'/info.txt');}
		if (file_exists($themeDirectory.$dirArray[$index].'/preview.png')) {
			$preview = "<img class='preview' src='".BASE_URL."/theme/$dirArray[$index]/preview.png' />";}
		print('<tr><td nowrap="nowrap"><input type="radio" name="theme" id="theme" value="'.$dirArray[$index].'"');
		if ($GLOBALS["config"]["theme"] == $dirArray[$index]) print('checked="yes"');
		print("/> $theme_name</td><td>$preview</td><td>$info</td></tr>");
	if ($index == $indexCount) echo '</table';
	}
}

?>
              </td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <hr />
          <table cellspacing="12px">
          	<tr>
            	<th><?php echo _('Completed Item Format'); ?>:</th>
                <td><input type="checkbox" name="strikethrough" value="1" onclick="calc_completed_item(form_settings)" <?php if (in_array($GLOBALS["config"]["completed_item"],array(1,3,5,9,7,11,13,15))) echo 'checked="yes"'; ?>/>
                <label for="strikethrough"><?php echo _('Strikethrough'); ?></label></td>
                <td><input type="checkbox" name="italic" value="2" onclick="calc_completed_item(form_settings)" <?php if (in_array($GLOBALS["config"]["completed_item"],array(2,3,6,10,7,11,14,15))) echo 'checked="yes"'; ?>/>
                <label for="italic"><?php echo _('Italic'); ?></label></td>
                <td><input type="checkbox" name="gray" value="8" onclick="calc_completed_item(form_settings)" <?php if (in_array($GLOBALS["config"]["completed_item"], array(8,9,10,12,11,13,14,15))) echo 'checked="yes"'; ?>/>
                <label for="gray"><?php echo _('Gray'); ?></label></td>
                <td><input type="checkbox" name="checkmark" value="4" onclick="calc_completed_item(form_settings)" <?php if (in_array($GLOBALS["config"]["completed_item"],array(4,5,6,12,7,13,14,15))) echo 'checked="yes"'; ?>/>
                <label for="checkmark"><?php echo _('Checkmark'); ?></label></td>
                </td>
                <td><input type="hidden" name="completed_item" value="<?php echo $GLOBALS["config"]["completed_item"]; ?>" /></td>
             </tr>
          </table>
          <hr />
          <table>
            <tr>
              <td><input name="save_settings" type="submit" value="<?php echo _('Save'); ?>" /></td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </form>
      </div>
    </div>
    <!-- close contentHolder --> 
  </div>
  <!-- close tabContent --> 
<script type="text/javascript">
function calc_completed_item(form){
	var value = 0;
	if(form.strikethrough.checked == true) value = value + parseInt(form.strikethrough.value);
	if(form.italic.checked == true) value = value + parseInt(form.italic.value);
	if(form.checkmark.checked == true) value = value + parseInt(form.checkmark.value);
	if(form.gray.checked == true) value = value + parseInt(form.gray.value);
	form.completed_item.value =  value;
	}</script>
</div>
<!-- close settings--> 
