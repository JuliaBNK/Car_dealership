<!doctype html>
<?php
require('deal_functions.php');
html_head("Add a Salesperson");
require('deal_header.php');
require('deal_sidebar.php');
require('deal_values.php');

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
  <!-- Display a form to capture information -->
  <h2> Add a New Salesperson </h2>
  <form action="deal_add_salesperson.php" method="post">
    <table border="0">
      <tr>
        <td> Last Name </td>
        <td align="left"><input type="text" name="last" size="35" maxlength="35"></td>
      </tr>
      <tr>
        <td> First Name </td>
        <td align="left"><input type="text" name="first" size="35" maxlength="35"></td>
      </tr>
      <tr>
      <td> Address </td>
        <td align="left"><input type="text" name="address" size="35" maxlength="75"></td>
      </tr>
      <tr>
      <td> City </td>
        <td align="left"><input type="text" name="city" size="35" maxlength="75"></td>
      </tr>
      <tr>
      <td> State </td>
        <td align="left"><input type="text" name="state" size="35" maxlength="2"></td>
      </tr>
      <tr>
      <td> Zip Code </td>
        <td align="left"><input type="text" name="zipcode" size="35" maxlength="5"></td>
      </tr>
      <tr>
      <td> Phone </td>
        <td align="left"><input type="text" name="phone" size="35" maxlength="10"></td>
      </tr>
      <tr>
      <td> Email </td>
        <td align="left"><input type="text" name="email" size="35" maxlength="55"></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
<?php
} else {
  # Process the information from the form displayed
  $last = $_POST['last'];
  $first = $_POST['first'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $zipcode = $_POST['zipcode'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];

  // clean up and validate data 
  $last = trim($last);
  $first = trim($first);
  $address = trim($address);
  $city = trim($city);
  $state = trim($state);
  $zipcode = trim($zipcode);
  $phone = trim($phone);
  $email = trim($email);

  $errors = validate_person($last, $first, $address, $city, $state, $zipcode, $phone, $email);
  if (empty($errors)) {
   try{
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //insert data
    $db->exec("INSERT INTO salespersons (last, first, address, city, state, zipcode, phone, email) VALUES ('$last', '$first','$address', '$city', '$state', '$zipcode', '$phone', '$email');");
    
    //get the last id value inserted into the table
    $last_id = $db->lastInsertId();
    
    //now output the data from the insert to a simple html table...
    print "<h2>Salesperson Added</h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td>Last Name</td><td>First Name</td><td>Address</td><td>City</td><td>State</td><td>Zipcode</td><td>Phone</td><td>Email</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM salespersons where id = '$last_id'")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
    print "<td>".$row['last']."</td>";
    print "<td>".$row['first']."</td>";
    print "<td>".$row['address']."</td>";
    print "<td>".$row['city']."</td>";
    print "<td>".$row['state']."</td>";
    print "<td>".$row['zipcode']."</td>";
    print "<td>".$row['phone']."</td>";
    print "<td>".$row['email']."</td>";
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
    echo "Errors found in salesperson's entry:<br/>";
    foreach($errors as $error) {
      echo " -  $error <br/>";
    }
    try_again("Please correct.<br/>");
  }
}


require('deal_footer.php');
?>

