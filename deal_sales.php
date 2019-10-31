<!doctype html>
<?php
require('deal_functions.php');
require('deal_values.php');
html_head("Sales");
require('deal_header.php');
require('deal_sidebar.php');

# Code for your web page follows.
try {
  //open the database
  $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<h2>Sold cars</h2>
<!-- display all cars -->
<table border=1>
  <tr>
   <td>Make</td><td>Model</td><td>Year</td><td>Type</td><td>Mileage</td><td>Sale Price</td><td>Sale date</td><td>Salesperson</td><td>Buyer</td><td>Status</td>
  </tr>
 
<?php
$query = "SELECT * FROM cars WHERE status = 'sold'";
$result = $db->query($query);
 foreach($result as $row) {
  print "<tr>";
  print "<td>".$row['make']."</td>";
  print "<td>".$row['model']."</td>";
  print "<td>".$row['year']."</td>";
  print "<td>".$row['type']."</td>";
  print "<td>".$row['mileage']."</td>";
  print "<td>".$row['sale_price']."</td>";
  print "<td>".$row['date']."</td>";
  print "<td>".$row['salesperson_id']."</td>";
  print "<td>".$row['buyer_id']."</td>";
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
