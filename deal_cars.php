<!doctype html>
<?php
require('deal_functions.php');
require('deal_values.php');
html_head("Cars for sale");
require('deal_header.php');
session_start();
require('deal_sidebar.php');

if (we_are_not_admin()) {
  exit;
}
# Code for your web page follows.
try {
  //open the database
  $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<h2>Cars for sale</h2>
<!-- display all cars -->
<table border=1>
  <tr>
   <td>Make</td><td>Model</td><td>Year</td><td>Type</td><td>Status</td><td>Mileage</td><td>Color</td><td>Price</td><td>Description</td>
  </tr>
 
<?php
$query = "SELECT * FROM cars WHERE status = 'available'";
$result = $db->query($query);
 foreach($result as $row) {
  print "<tr>";
  print "<td>".$row['make']."</td>";
  print "<td>".$row['model']."</td>";
  print "<td>".$row['year']."</td>";
  print "<td>".$row['type']."</td>";
  print "<td>".$row['status']."</td>";
  print "<td>".$row['mileage']."</td>";
  print "<td>".$row['color']."</td>";
  print "<td>".$row['price']."</td>";
  print "<td>".$row['description']."</td>";
  print "</tr>";
}

print "</table>";

  // close the database connection
  $db = NULL;
}

catch(PDOException $e) {
  echo 'Exception : '.$e->getMessage();
  echo "<br/>"; 
  $db = NULL;
}

require('deal_footer.php');
?>
