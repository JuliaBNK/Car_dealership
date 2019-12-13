<!doctype html>
<?php
session_start();
require('deal_functions.php');
html_head("Logout");
require('deal_header.php');
require('deal_values.php');

//save valid_user to see if we were logged in to begin with
$old_user = $_SESSION['valid_user'];
unset($_SESSION['valid_user']);
session_destroy();

require('deal_sidebar.php');
print "<h2>Log out</h2>";

if  (empty($old_user)) {
  print "You were not logged in to begin with.<br/>";
} else {
  print "Logged out<br/>";
}

require('deal_footer.php');
?>
