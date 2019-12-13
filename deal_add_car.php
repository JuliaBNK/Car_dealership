<!doctype html>
<?php
require('deal_functions.php');
html_head("Add a Car");
require('deal_header.php');
require('deal_values.php');
session_start();
require('deal_sidebar.php');

if (we_are_not_admin()) {
  exit;
}

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
  <!-- Display a form to capture information -->
  <h2> Add a Car </h2>
  <form action="deal_add_car.php" method="post">
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
      <td> Year </td>
        <td align="left"><input type="text" name="year" size="35" maxlength="4"></td>
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
      <td> Mileage </td>
        <td align="left"><input type="text" name="mileage" size="35" maxlength="9"></td>
      </tr>
      <tr>
      <td> Color </td>
        <td align="left"><input type="text" name="color" size="35" maxlength="35"></td>
      </tr>
      <tr>
      <td> Price </td>
        <td align="left"><input type="text" name="price" size="35" maxlength="6"></td>
      </tr>
      <tr>
        <td> Description </td>
        <td align="left"><input type="text" name="description" size="80" maxlength="120"></td>
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
  $model = $_POST['model'];
  $year = $_POST['year'];
  $type = $_POST['type'];
  $mileage = $_POST['mileage'];
  $color = $_POST['color'];
  $price = $_POST['price'];
  $description = $_POST['description'];

  // clean up and validate data 
  $make = trim($make);
  $model = trim($model);
  $year = trim($year);
  $type = trim($type);
  $mileage = trim($mileage);
  $color = trim($color);
  $price = trim($price);
  $description = trim($description); 
 //  $price = doubleval($price);
 // $year = intval($year);
 // $mileage = intval($mileage);
 
  $errors = validate_car($make, $model, $year, $mileage, $color, $price, $description);
  
  if (empty($errors)) { 
  try{
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //insert data
    $db->exec("INSERT INTO cars (make, model, year, type, mileage, color, price, description, status) VALUES ('$make', '$model','$year','$type', '$mileage', '$color', '$price', '$description', 'Available');");
    //get the last id value inserted into the table
    $last_id = $db->lastInsertId();

    //now output the data from the insert to a simple html table...
    print "<h2>Car Added</h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td> ID</td><td>Make</td><td>Model</td><td>Year</td><td>Type</td><td>Mileage</td><td>Color</td><td>Price</td><td>Description</td><td>Status</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM cars where id = '$last_id'")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
    print "<td>".$row['id']."</td>";
    print "<td>".$row['make']."</td>";
    print "<td>".$row['model']."</td>";
    print "<td>".$row['year']."</td>";
    print "<td>".$row['type']."</td>";
    print "<td>".$row['mileage']."</td>";
    print "<td>".$row['color']."</td>";
    print "<td>".$row['price']."</td>";
    print "<td>".$row['description']."</td>";
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
}else {
    echo "Errors found in car's entry:<br/>";
    foreach($errors as $error) {
      echo " -  $error <br/>";
    }
    try_again("Please correct.<br/>");
  }
}
require('deal_footer.php');

?>
