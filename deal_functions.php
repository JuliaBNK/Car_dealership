<?php
//require('mlib_values.php');
# CSCI59P standard functions

function html_head($title) {
  echo '<html lang="en">';
  echo '<head>';
  echo '<meta charset="utf-8">';
  echo "<title>$title</title>";
  echo '<link rel="stylesheet" href="deal.css">';
  echo '</head>';
  echo '<body>';
}


function try_again($str){
  echo $str;
  echo "<br/>";
  // emulation of pressing the back button on the browser
  echo '<a href="#" onclick="history.back(); return false;">Try Again</a>';
  require('deal_footer.php');
  exit;

}


function validate_person($last, $first, $address, $city, $state, $zipcode, $phone, $email) {
  $error_messages = array(); # Create empty error_messages array.
  if ( strlen($last)  == 0 ) {
    array_push($error_messages,"Last name field cannot be empty.");
  }

  if (!preg_match("/^[a-zA-Z ]*$/",$last)) {
    array_push($error_messages,"Only letters and white space can be used in last name.");
  }

  if ( strlen($first)  == 0 ) {
    array_push($error_messages,"First name field cannot be empty.");
  }

  if (!preg_match("/^[a-zA-Z -]*$/",$first)) {
    array_push($error_messages,"Only letters and white space can be used in first name.");
  }

  if ( strlen($address) == 0 ) {
    array_push($error_messages, "Address field cannot be empty.");
  }else  if (!preg_match("/^\\d+ [a-zA-Z0-9 \.]+$/", $address) ) {
     array_push($error_messages, "Address doesn't match common format (i.e. 123 First St Apt. 321).");
  }

  if ( strlen($city) == 0 ) {
    array_push($error_messages, "City field cannot be empty.");
  }else if (!preg_match("/^[a-zA-Z ]+$/", $city) ) {
    array_push($error_messages, "Only letters and white space can be used in city name.");
  }

  if ( strlen($state) == 0 ) {
    array_push($error_messages, "State field cannot be empty.");
  } else if (!preg_match("/^[A-Z][A-Z]$/", $state) ) {
    array_push($error_messages, "Use state abbreviation (i.e. CA, NY).");
  }

  if ( strlen($zipcode) == 0 ) {
    array_push($error_messages, "Zipcode field cannot be empty.");
  } else if ( strlen($zipcode) < 5 ) {
   array_push($error_messages, "Zip Code should contain five digits.");
  } else if (!preg_match("/^[0-9]+$/", $zipcode) ) {
    array_push($error_messages, "Zip Code should contain five digits.");
  }

  if ( strlen($phone) == 0 ) {
    array_push($error_messages, "Phone field cannot be empty.");
  }else if ( strlen($phone) < 10 ) {
    array_push($error_messages, "The phone is uncomplete.");
  }

  if (!preg_match("/^[0-9]*$/", $phone )) {
    array_push($error_messages, "Phone number should contain only digits from 1 through 9 without spaces or dashes.");
  }

  if ( strlen($phone) > 10 ) {
     array_push($error_messages, "Enter 10 digits of the area code and phone number without spaces or dashes.");
  }
  if ( strlen($email) == 0 ) {
    array_push($error_messages, "Email field cannot be empty.");
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($error_messages, "Invalid email format.");
  }
 

try {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //check for duplicate records
    if ( strlen($last) != 0 and strlen($first) != 0 and strlen($phone) != 0) {
      $sql = "SELECT COUNT(*) FROM salespersons WHERE last = '$last' AND first  = '$first' AND phone = '$phone'";
      $result = $db->query($sql)->fetch(); //count the number of entries with the same last and first name and phone number
      if ( $result[0] > 0) {
        array_push($error_messages, "Record of $first $last is already exist.");
      }
    }
  }

  catch(PDOException $e){
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db = NULL;
  }

  return $error_messages;
}


function validate_car($make, $model, $year, $mileage, $color, $price, $description) {
  $error_messages = array(); # Create empty error_messages array.
  if ( strlen($make)  == 0 ) {
    array_push($error_messages,"Make field cannot be empty.");
  }

  if ( strlen($model)  == 0 ) {
    array_push($error_messages,"Model field cannot be empty.");
  }

  if (strlen($year) == 0) {
    array_push($error_messages, "Year field cannot be empty.");
  } else if ( !preg_match("/^[0-9][0-9][0-9][0-9]$/", $year))  {
  array_push($error_messages, "Four digits should be used in year field");
  } else {
    $year = intval($year);
    if ($year > 2020 || $year < 1980) {
    array_push($error_messages,"You cannot enter year earlier than 1980 and later than 2020");
  }
 }

  if ( strlen($mileage) == 0 ) {
    array_push($error_messages, "Mileage field cannot be empty.");
  } else if (!preg_match("/^[0-9]+$/", $mileage)) {
   array_push($error_messages, "Only digits can be used in mileage field.");
  } else {
  $mileage = doubleval($mileage);
  if ($mileage < 0) {
   array_push($error_messages,"Mileage should be equal or greater than 0");
  }  
  }

  if ( strlen($color) == 0 ) {
    array_push($error_messages, "Color field cannot be empty.");
  }

  if (!preg_match("/^[a-zA-Z ]*$/",$color)) {
    array_push($error_messages,"Only letters and white space can be used in color field.");
  }

  if ( strlen($price)  == 0 ) {
    array_push($error_messages, "Price field cannot be empty.");
  }else if( !preg_match("/^[0-9]+$/", $price )){
     array_push($error_messages,"Only digits can be used in price field.");
  }
  else {
  $price = doubleval($price); 
  }


  if ( strlen($description) == 0 ) {
    array_push($error_messages, "Description field cannot be empty.");
  }

  return $error_messages;
}


// Validate the date format as YYYY-MM-DD
function MyCheckDate( $postedDate ) {
   if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $postedDate, $datebit)) {
      return checkdate($datebit[2] , $datebit[3] , $datebit[1]);
   } else {
      return false;
   }
}


?>
