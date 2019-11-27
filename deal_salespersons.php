<!doctype html>
<?php
require('deal_functions.php');
require('deal_values.php');
html_head("Salespersons");
require('deal_header.php');
require('deal_sidebar.php');

# Code for your web page follows.
try {
  //open the database
  $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<h2>Salespersons</h2>
<!-- display all salespersons of the car dealership -->
<table border=1>
  <tr>
   <td>ID</td><td>Last Name</td><td>First Name</td><td>Address</td><td>City</td><td>State</td><td>Zip Code</td><td>Phone</td><td>Email</td>
  </tr>
 
<?php
$query = "SELECT * FROM salespersons";
$result = $db->query($query);
 foreach($result as $row) {
  print "<tr>";
  print "<td>".$row['id']."</td>";
  print "<td>".$row['last']."</td>";
  print "<td>".$row['first']."</td>";
  print "<td>".$row['address']."</td>";
  print "<td>".$row['city']."</td>";
  print "<td>".$row['state']."</td>";
  print "<td>".$row['zipcode']."</td>";
  print "<td>".$row['phone']."</td>";
  print "<td>".$row['email']."</td>";
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

