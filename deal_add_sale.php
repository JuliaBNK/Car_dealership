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
  try
  {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //get todays date
    $result = $db->query("SELECT CURDATE()")->fetch();
    $today = $result[0];
?>
  <!-- Display a form to capture information -->
  <h2> Add New Sale </h2>
  <form action="deal_add_sale.php" method="post">
  <table border="0">
     <tr>
      <td>Choose a car</td>
      <td align="left"> <select name="car">
      <option value="0"></option> 
    
<?php
    //display all available cars in the cars table
    $result = $db->query("SELECT * FROM cars WHERE status = 'Available'");
    foreach($result as $row)
    {
      print "<option value=".$row['id'].">".$row['make']." ".$row['model']." ".$row['year']."</option>";
    }
?>
    </select>
    </td>
    </tr>

      <tr>
      <td>Salesperson</td>
      <td align="left"> <select name="salesperson">
      <option value="0"> </option> 
<?php
    //display all salespersons from salespersons table
    $result = $db->query("SELECT * FROM salespersons");
    foreach($result as $row)
    {
      print "<option value=".$row['id'].">".$row['first']." ".$row['last']."</option>";
    }
?> 
    </select>
    </td>
    </tr>

    <tr>
      <td>Buyer</td>
      <td align="left"> <select name="buyer">
      <option value="0"> </option> 
<?php
    //display all buyers from buyers table
    $result = $db->query("SELECT * FROM buyers");
    foreach($result as $row)
    {
      print "<option value=".$row['id'].">".$row['first']." ".$row['last']."</option>";
    }
?> </select>
    </td>
    </tr>

      <tr>
      <td> Date (yyyy-mm-dd) </td>
      <td>
       <?php print "<input type 'text' name='date' value='$today' /><br/>";?>
      </td>
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
// close the database connection
    $db = NULL;
  }
  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage().'<br/>';
    $db = NULL;
  }

} else {
  # Process the information from the form displayed
  $car = $_POST['car'];
  $salesperson = $_POST['salesperson'];
  $buyer = $_POST['buyer'];
  $date = $_POST['date'];
  $sale_price = $_POST['sale_price'];

  // clean up and validate data 
  $car = trim($car);
  $salesperson = trim($salesperson);
  $buyer = trim($buyer);
  $date = trim($date);
  $sale_price = trim($sale_price);

  
   // errors
  $error_messages = array(); # Create empty error_messages array.
  if ($car == 0) {
    array_push($error_messages, "You need to choose a car.");
  }

  if ($salesperson == 0) {
    array_push($error_messages, "You need to choose a salesperson.");
  }

  if ($buyer == 0) {
    array_push($error_messages, "You need to choose a buyer.");
  }

try{
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //get todays date   
    $result = $db->query("SELECT CURDATE()")->fetch();
    $today = $result[0];
  }
catch(PDOException $e){
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db = NULL;
 }
   //validate sale date
    if (!MyCheckDate($date)) {
      array_push($error_messages, "You have entered an invalid date. Format is yyyy-mm-dd.");
    }

    //check for date in the past
    if ($date < $today) {
     array_push($error_messages, "The sale date is earlier than today. ");
    }

    if ( strlen($sale_price)  == 0 ) {
      array_push($error_messages, "Sale price field cannot be empty.");
    }else if( !preg_match("/^[0-9]+$/", $sale_price )){
      array_push($error_messages,"Only digits can be used in price field.");
    }else {
  $sale_price = doubleval($sale_price); 
  }



  if (empty($error_messages)) { 
  try{
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     //get the name for the salesperson
    $result = $db->query("SELECT * FROM salespersons where id = $salesperson")->fetch();
    $salesperson_name = $result['first']." ".$result['last'];

    //get the name for the buyer
    $result = $db->query("SELECT * FROM buyers where id = $buyer")->fetch();
    $buyer_name = $result['first']." ".$result['last'];

    // update  status of the car to 'sold'
    $db->exec("UPDATE cars SET status = 'Sold', salesperson_id = $salesperson, buyer_id = $buyer, sale_date = '$date', sale_price = $sale_price WHERE id = $car");

    //now output the data from the update to a simple html table...
    print "<h2>Sale Added </h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td> ID</td><td>Make</td><td>Model</td><td>Year</td><td>Salesperson</td><td>Buyer</td><td>Sale date</td><td>Sale Price</td><td>Status</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM cars where id = $car")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
    print "<td>".$row['id']."</td>";
    print "<td>".$row['make']."</td>";
    print "<td>".$row['model']."</td>";
    print "<td>".$row['year']."</td>";
    print "<td>".$salesperson_name."</td>";
    print "<td>".$buyer_name."</td>";
    print "<td>".$row['sale_date']."</td>";
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
}else {
    echo "Errors in your entry:<br/>";
    foreach($error_messages as $error) {
      echo " -  $error <br/>";
    }
    try_again("Please correct.<br/>");
  }
}

require('deal_footer.php');
?>

