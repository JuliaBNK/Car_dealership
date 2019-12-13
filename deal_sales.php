<!doctype html>
<?php
require('deal_functions.php');
require('deal_values.php');
html_head("Sold Cars");
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

<h2>Sold Cars</h2>
<!-- display all cars -->
<table border=1>
  <tr>
   <td>Make</td><td>Model</td><td>Year</td><td>Type</td><td>Mileage</td><td>Sale Price</td><td>Sale date</td><td>Salesperson</td><td>Buyer</td>
  </tr>
 
<?php
$query = "SELECT * FROM cars WHERE status = 'Sold'";
$result = $db->query($query);
 foreach($result as $row) {

  print "<tr>";
  print "<td>".$row['make']."</td>";
  print "<td>".$row['model']."</td>";
  print "<td>".$row['year']."</td>";
  print "<td>".$row['type']."</td>";
  print "<td>".$row['mileage']."</td>";
  print "<td>".$row['sale_price']."</td>";
  print "<td>".$row['sale_date']."</td>";
  $salesperson_id = $row['salesperson_id'];
  $result1 = $db->query("SELECT * FROM salespersons WHERE id = $salesperson_id")->fetch();
  $salesperson_name = $result1['first']." ".$result1['last'];
  print "<td>".$salesperson_name."</td>";
  $buyer_id = $row['buyer_id'];
  $result2 = $db->query("SELECT * FROM buyers where id = $buyer_id")->fetch();
  $buyer_name = $result2['first']." ".$result2['last'];
  print "<td>".$buyer_name."</td>";
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
