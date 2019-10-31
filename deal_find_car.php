<!doctype html>
<?php
require('deal_functions.php');
html_head("Find a Car");
require('deal_header.php');
require('deal_sidebar.php');
require('deal_values.php');

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
  <!-- Display a form to capture information -->
  <h2> Find a Car </h2>
  <form action="deal_find_car.php" method="post">
    <table border="0">
      <tr>
        <td> Make </td>
        <td align="left"><input type="text" name="make" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td> Model </td>
        <td align="left"><input type="text" name="model" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td> Max Year </td>
        <td align="left"><input type="text" name="year" size="35" maxlength="35"></td>
      </tr>
      <tr>
      <td> Type </td>
        <td align="left"><input type="text" name="type" size="35" maxlength="35"></td>
      </tr>
     <tr>
        <td> Max Mileage </td>
        <td align="left"><input type="text" name="mileage" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td> Max Price </td>
        <td align="left"><input type="text" name="price" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
<?php
} else {
  # Process the information from the form displayed
  $make = $_POST['make'];
  $year = $_POST['year'];
  $type = $_POST['type'];
  $mileage = $_POST['mileage'];
  $price = $_POST['price'];

  // clean up and validate data 
  $make = trim($make);
  $year = trim($year);
  $type = trim($type);
  $mileage = trim($mileage);
  $price = trim($price);
  try{
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
    //now output the data from the search to a simple html table...
    print "<h2>Available Cars</h2>";
    print "<table border=1>";
    print "<tr>";
    print <td>Make</td><td>Model</td><td>Year</td><td>Type</td><td>Status</td><td>Mileage</td><td>Color</td><td>Price</td><td>Description</td>
    print "</tr>";
<?php
    $query = "SELECT * FROM cars WHERE make = '$make' AND year >= '$year' AND type = '$type' AND mileage <= '$mileage' AND price <= '$price' AND statue = 'available';";
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
  catch(PDOException $e){
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db = NULL;
 }
}
require('deal_footer.php');
?>


