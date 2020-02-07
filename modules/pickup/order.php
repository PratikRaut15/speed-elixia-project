<ul id="tabnav">
  <?php
  if (isset($_GET['id'])) {
   if ($_GET['id'] == 8)
    echo "<li><a class='selected' href='pick.php?id=8'>Add Pickup</a></li>";
   else
    echo "<li><a href='pick.php?id=8'>Add Pickup</a></li>";
   if ($_GET['id'] == 3)
    echo "<li><a class='selected' href='pick.php?id=3'>Vendor Pickups</a></li>";
   else
    echo "<li><a href='pick.php?id=3'>Vendor Pickups</a></li>";
   if ($_GET['id'] == 20) {
    echo "<li><a class='selected' href='pick.php?id=20'>User Pickups</a></li>";
   } else
    echo "<li><a href='pick.php?id=20'>User Pickups</a></li>";
   if ($_GET['id'] == 19)
    echo "<li><a class='selected' href='pick.php?id=19'>Edit Pickup</a></li>";
  }
  else {
   echo "<li><a class='selected' href='pick.php?id=8'>Add Pickup</a></li>";
   echo "<li><a href='pick.php?id=3'>Vendor Pickups</a></li>";
   echo "<li><a href='pick.php?id=20'>User Pickups</a></li>";
  }
  ?>
</ul>
<?php
//include 'pickup_functions.php';
if ($_GET['id'] == 3)
 include 'orders.php';
else if ($_GET['id'] == 8)
 include 'pages/add_orders.php';
else if ($_GET['id'] == 19)
 include 'order_edit.php';
else if ($_GET['id'] == 20)
 include 'userorders.php';
?>