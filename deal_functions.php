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

?>
