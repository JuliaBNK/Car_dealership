<!doctype html>
<?php
require('deal_functions.php');
html_head("Deal administrator");
require('deal_header.php');
require('deal_values.php');
session_start();
require('deal_sidebar.php');

if (we_are_not_admin()) {
  exit;
}

if (!isset($_POST['submit']))
{
  try
  {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

    <h2>Change Access Priviledges</h2>
    <form action="deal_administrator.php" method="post">
      <!-- display types -->
      <table border=1>
        <tr>
          <td>Click one to Change</td><td>User</td><td>Login</td>
        </tr>
<?php
    $result = $db->query("SELECT * FROM salespersons");
    foreach($result as $row)
    {
      print "<tr>";
      print "<td><input type='radio' name='id' value=".$row['id']."></td>";
      print "<td>".$row['first']." ".$row['last']."</td>";
      print "<td>".$row['login']."</td>";
      print "</tr>";
    }
?>
      </table>
      <p>Clicking an entry with a login will remove administration privileges.</p>
      <p>Clicking an entry without a login will enable administration privileges. Enter login and password below:</p>
      Login: <input type="text" name="login"/><br/>
      Password: <input type="text" name="password"/><br/>
      <input type="submit" name="submit" value = "Submit"/><br/>
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
?>

  <h2>Administration Priviledges Changed</h2>
<?php
  $id = $_POST['id'];
  $login = $_POST['login'];
  $password = $_POST['password'];


try
  {
    if (empty($id)) {
      echo "You did not select any users to change privileges.<br/>";
    } else {
      //open the database
      $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //update user as appropriate
      $sql = "SELECT * FROM salespersons where id = $id";
      $result = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
      if (empty($result['login'])) {
        //set the login and password to enable administrator privileges
        //clean up and validate data
        $login = trim($login);
        if ( empty($login) ) {
          try_again("Login cannot be empty.");
        }
        $password = trim($password);
        if ( strlen($password) < 8 ) {
          try_again("Password must be at least 8 characters.");
        }
        //change password into a sha1 hash
        $passwd = sha1($password);
        //check for duplicate login
        $sql = "select count(*) from salespersons where login = '$login'";
        $result = $db->query($sql)->fetch(); //count the number of entries with the name
        if ( $result[0] > 0) {
          try_again($login." is not unique. Logins must be unique.");
        }
        $db->exec("UPDATE salespersons SET login = '$login', password = '$passwd' WHERE id = $id");
      } else {
        //remove the login and password
        $db->exec("UPDATE salespersons SET login = NULL, password = NULL WHERE id = $id");
      }

     //now output the data to a simple html table...
      print "<table border=1>";
      print "<tr>";
      print "<td>User</td><td>Login</td>";
      print "</tr>";
      $sql = "SELECT * FROM salespersons where id = $id";
      $result = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
      print "<tr>";
      print "<td>".$result['first']." ".$result['last']."</td><td>".$result['login']."</td>";
      print "</tr>";
      print "</table>";
    }

    // close the database connection
    $db = NULL;
  }
catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage().'<br/>';
    $db = NULL;
  }
}
require('deal_footer.php');
?>
