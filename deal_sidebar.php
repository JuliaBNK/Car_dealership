<aside id="sidebar">
    <nav>
    <ul>
     <li>
        <a href="deal_login.php">Log in</a><br/>
      </li>    
<?php
  if (!empty($_SESSION['valid_user'])) {
?>
      <li>
        <a href="deal_add_car.php">Add a car</a><br/>
      </li>
     <li>
       <a href="deal_cars.php">Cars for sale</a><br/>
      </li>
      <li> 
      <a href="deal_find_car.php">Find a car</a><br/>
     </li>
      <li>
       <a href="deal_add_sale.php">Add a sale</a><br/>
      </li>
       <li>
       <a href="deal_sales.php">Sold cars</a><br/>
      </li>
      <li>
      <a href="deal_add_salesperson.php">Add a salesperson </a><br/>
      </li>
      <li>
      <a href="deal_salespersons.php">Salespersons </a><br/>
      </li>
      <li>
      <a href="deal_add_buyer.php">Add a buyer </a><br/>
      </li>
      <li>
       <a href="deal_buyers.php">Buyers</a><br/>
      </li>
      <li>
       <a href="deal_administrator.php">Access configuration</a><br/>
      </li>
       <li>
        <a href="deal_logout.php">Log out</a><br/>
      </li>
<?php
  }
?>

    </ul>
    </nav>
</aside>

