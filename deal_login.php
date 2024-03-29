<!doctype html>
<?php
session_start();
require('deal_functions.php');
html_head("Login");
require('deal_header.php');
require('deal_values.php');

# Has the user entered a login and password?
if (isset($_POST['login']) && isset($_POST['password']))
{
  $login = $_POST['login'];
  $password = $_POST['password'];
  $passwd = sha1($password);

  try
  {
    //open the database
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //check for login in the users table
    $sql = "SELECT count(*) FROM salespersons WHERE login = '$login' AND password = '$passwd'";
    $result = $db->query($sql)->fetch(); //count the number of entries with the login name
    if ( $result[0] == 1) {
      $_SESSION['valid_user'] = $login;
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
require('deal_sidebar.php');
//if the $_SESSION variable is set, then the user entered a valid login and password
if (isset($_SESSION['valid_user'])) {
  echo "You are logged in as: ".$_SESSION['valid_user'].'<br/>';
} else {
  if (isset($login)) {
    //user tried to login but either wrong password or wrong login
    echo "Either your login or password is incorrect.".'<br/>';
    try_again("Please correct.<br/>");
  } else {
    //the user is coming through for the first time so display a form to accept login and password
    ?>
    <h2>Log in to Dealership Site</h2>
    <form action="deal_login.php" method="post">
      <table border="0">
        <tr>
          <td bgcolor="#cccccc" width="100">Login</td>
          <td><input type="text" name="login"></td>
        </tr>
        <tr>
          <td bgcolor="#cccccc" width="100">Password</td>
          <td><input type="password" name="password"></td>
        </tr>
        <tr>
          <td colspan="2" align="right"><input type="submit" name="submit" value="Log In"></td>
        </tr>
      </table>
    </form>
    <?php
  }
}

require('deal_footer.php');
?>

