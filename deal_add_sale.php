<!doctype html>
<?php
require('deal_functions.php');
html_head("Add New Sale");
require('deal_header.php');
require('deal_sidebar.php');
require('deal_values.php');

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
  <!-- Display a form to capture information -->
  <h2> Add New Sale </h2>
  <form action="deal_add_sale.php" method="post">
    <table border="0">
      <tr>
        <td> Car ID </td>
        <td align="left"><input type="text" name="id" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td> Salesperson ID </td>
        <td align="left"><input type="text" name="salesperson_id" size="35" maxlength="35"></td>
      </tr>
      <tr>
      <td> Buyer ID </td>
        <td align="left"><input type="text" name="buyer_id" size="35" maxlength="35"></td>
      </tr>
      <tr>
      <td> Date </td>
        <td align="left"><input type="text" name="date" size="35" maxlength="35"></td>
      </tr>
      <tr>
      <td> Sale Price </td>
        <td align="left"><input type="text" name="sale_price" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
<?php

} else {
  # Process the information from the form displayed
  $id = $_POST['id'];
  $salesperson = $_POST['salesperson_id'];
  $buyer = $_POST['buyer_id'];
  $date = $_POST['date'];
  $sale_price = $_POST['sale_price'];

  // clean up and validate data 
  $id = trim($id);
  $salesperson = trim($salesperson);
  $buyer = trim($buyer);
  $date = trim($date);
  $sale_price = trim($sale_price);
  try{
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // update  status of the car to 'sold'
    $db->exec("UPDATE cars SET status = 'sold', salesperson_id = '$salesperson', buyer_id = '$buyer', sale_date = '$date', sale_price = '$sale_price' WHERE id = '$id';");

    //now output the data from the update to a simple html table...
    print "<h2>Sale Added</h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td> ID</td><td>Make</td><td>Model</td><td>Year</td><td>Salesperson</td><td>Buyer</td><td>Sale date</td><td>Sale Price</td><td>Status</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM cars where id = '$id'")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
    print "<td>".$row['id']."</td>";
    print "<td>".$row['make']."</td>";
    print "<td>".$row['model']."</td>";
    print "<td>".$row['year']."</td>";
    print "<td>".$row['salesperson_id']."</td>";
    print "<td>".$row['buyer_id']."</td>";
    print "<td>".$row['date']."</td>";
    print "<td>".$row['sale_price']."</td>";
    print "<td>".$row['status']."</td>";
    print "</tr>";
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

