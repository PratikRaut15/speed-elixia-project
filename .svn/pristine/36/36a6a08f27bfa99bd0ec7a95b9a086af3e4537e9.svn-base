<ul id="tabnav">
  <?php
  if (isset($_GET['id'])) {
   if ($_GET['id'] == 21)
    echo "<li><a class='selected' href='pick.php?id=21'>Add Order</a></li>";
   else
    echo "<li><a href='pick.php?id=21'>Add Orders</a></li>";
   if ($_GET['id'] == 22)
    echo "<li><a class='selected' href='pick.php?id=22'>Vendor Orders</a></li>";
   else
    echo "<li><a href='pick.php?id=22'>Vendor Orders</a></li>";
   if ($_GET['id'] == 23) {
    echo "<li><a class='selected' href='pick.php?id=23'>User Orders</a></li>";
   } else
    echo "<li><a href='pick.php?id=23'>User Orders</a></li>";
   if ($_GET['id'] == 19)
    echo "<li><a class='selected' href='pick.php?id=19'>Edit Order</a></li>";
  }
  else {
   echo "<li><a class='selected' href='pick.php?id=22'>Add Orders</a></li>";
   echo "<li><a href='pick.php?id=21'>Vendor Orderd</a></li>";
   echo "<li><a href='pick.php?id=23'>User Orders</a></li>";
  }
  ?>
</ul>
<?php
include 'pickup_functions.php';
if ($_GET['id'] == 3)
 include 'orders.php';
else if ($_GET['id'] == 21)
 include 'pages/add_orders.php';
else if ($_GET['id'] == 19)
 include 'order_edit.php';
else if ($_GET['id'] == 20)
 include 'userorders.php';
?>