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
        <td> Status </td>
        <td align="left"><select name="status">
<?php
   try
   {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //display possible status(available or sold)
    $result = $db->query("SELECT distinct status FROM cars");
    foreach($result as $row)
    {
      print "<option value=".$row['status'].">".$row['status']."</option>";
    }
    // close the database connection
    $db = NULL;
    }
  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db = NULL;
  }  
?>
   </select><br/>
      </tr>


      <tr>
      <td> Type </td>
      <td align="left"><select name="type"> 
<?php
  // Replace text field with a select pull down menu.
  try
  {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //display all types in the types table
    $result = $db->query("SELECT distinct type FROM cars");
    foreach($result as $row)
    {
      print "<option value=".$row['type'].">".$row['type']."</option>";
    }

    // close the database connection
    $db = NULL;
  }
  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db = NULL;
  }
?>  
      </select>
      </td>
      </tr>
     <tr> 
        <td> Make </td>
        <td align="left"><select name="make">
<?php
   try
   {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //display all makes in the cars table
    $result = $db->query("SELECT distinct make FROM cars");
    foreach($result as $row)
    {
      print "<option value=".$row['make'].">".$row['make']."</option>";
    }
    // close the database connection
    $db = NULL;
    }

  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db = NULL;
  }  
?>
   </select><br/>
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
  $status = $_POST['status'];
  $type = $_POST['type']; 
  $make = $_POST['make'];
  $mileage = $_POST['mileage'];
  $price = $_POST['price'];

  // clean up and validate data
  $status  = trim($status);
  $make = trim($make);
  $type = trim($type);
  $mileage = trim($mileage);
  $price = trim($price);

  $error_messages = array(); # Create empty error_messages array.


  if ( strlen($mileage) == 0 ) {
    $mileage =doubleval($mileage);
    $mileage = 1000000;
  } else if (strlen($mileage) > 0 && !preg_match("/^[0-9]+$/", $mileage)) {
   array_push($error_messages, "Only digits can be used in mileage field.");
  } else if (strlen($mileage) > 0 && preg_match("/^[0-9]+$/", $mileage)) {
  $mileage = doubleval($mileage);
  }

if ( strlen($price)  == 0 ) {
    $price = 1000000; 
  }else if( strlen($price) > 0 && !preg_match("/^[0-9]+$/", $price )){
     array_push($error_messages,"Only digits can be used in price field.");
  }
  else {
  $price = doubleval($price); 
  }



if (empty($error_messages)) { 
  try{
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $result1 = $db->query("SELECT count(*) FROM cars WHERE make = '$make' AND type = '$type' AND mileage <= $mileage AND price <= $price AND status = '$status'")->fetch();    
    $count = $result1[0];

    print "<h2>Cars Found: $count </h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td>Make</td><td>Model</td><td>Year</td><td>Type</td><td>Mileage</td><td>Color</td><td>Price</td><td>Sale price</td><td>Description</td><td>Status</td>";
    print "</tr>";

    $result = $db->query("SELECT * FROM cars WHERE make = '$make' AND type = '$type' AND mileage <= $mileage AND price <= $price AND status = '$status'");
    foreach($result as $row) {
      print "<tr>";
      print "<td>".$row['make']."</td>";
      print "<td>".$row['model']."</td>";
      print "<td>".$row['year']."</td>";
      print "<td>".$row['type']."</td>";
      print "<td>".$row['mileage']."</td>";
      print "<td>".$row['color']."</td>";
      print "<td>".$row['price']."</td>";
      print "<td>".$row['sale_price']."</td>";
      print "<td>".$row['description']."</td>";
      print "<td>".$row['status']."</td>";
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
}else {
    echo "Errors found in search entry:<br/>";
    foreach($error_messages as $error) {
      echo " -  $error <br/>";
    }
    try_again("Please correct.<br/>");
  }
}
require('deal_footer.php');
?>


