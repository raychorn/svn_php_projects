<?php
# ----------------------------------------------------
# ----- AUTO EMAIL ME PASSWORD AND DOWNLOAD
# ----- Version 1.7
# ----- Created on: 01/30/07
# ----- Designed by: American Financing
# ----------------------------------------------------


// Receiving variables
@$First_Name = addslashes($_POST['First_Name']);
@$Last_Name = addslashes($_POST['Last_Name']);
@$email = addslashes($_POST['email']);

// Validation
if (strlen($First_Name) == 0 )
{
header("Location: error.html");
exit;
}

if (strlen($Last_Name) == 0 )
{
header("Location: error.html");
exit;
}

if (! ereg('[A-Za-z0-9_-]+\@[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+', $email))
{
header("Location: error.html");
exit;
}

//Sending Email to form owner
# Email to Owner 
$pfw_header = "From: $email";
$pfw_subject = "USER: Auto eMail me Password and Download";
$pfw_email_to = "you@email.com";
$pfw_message = "First_Name: $First_Name\n"
. "Last_Name: $Last_Name\n"
. "email: $email\n";
@mail($pfw_email_to, $pfw_subject ,$pfw_message ,$pfw_header ) ;

//Sending auto respond Email to user
# Email to Owner 
$pfw_header = "From: you@email.com";
$pfw_subject = "Auto eMail me Password and Download";
$pfw_email_to = "$email";
$pfw_message = "Thank You for Trying out Auto eMail me Password and Download.\n"
. "\n"
. "Hello $First_Name $Last_Name,\n"
. "\n"
. "You can download the file at:\n"
. "http://www.americanfinancing.net\n"
. "\n"
. "The Password to unzip the file is: americanfinancing\n"
. "\n"
. "All in lower case.\n"
. "\n"
. "Please visit us at http://www.americanfinancing.net\n"
. "and apply for a Home Loan Today.";
@mail($pfw_email_to, $pfw_subject ,$pfw_message ,$pfw_header ) ;

//saving record in a text file
$pfw_file_name = "data.csv";
$pfw_first_raw = "First_Name,Last_Name,email\r\n";
$pfw_values = "$First_Name,$Last_Name,$email\r\n";
$pfw_is_first_row = false;
if(!file_exists($pfw_file_name))
{
 $pfw_is_first_row = true ;
}
if (!$pfw_handle = fopen($pfw_file_name, 'a+')) {
 die("Cannot open file ($pfw_file_name)");
 exit;
}
if ($pfw_is_first_row)
{
  if (fwrite($pfw_handle, $pfw_first_raw ) === FALSE) {
  die("Cannot write to file ($pfw_filename)");
  exit;
  }
}
if (fwrite($pfw_handle, $pfw_values) === FALSE) {
  die("Cannot write to file ($pfw_filename)");
  exit;
}
fclose($pfw_handle);

header("Location: thank_you.html");

?>
