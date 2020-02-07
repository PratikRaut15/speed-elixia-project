<ul id="tabnav">
  <?php
  if (isset($_GET['id'])) {
   if ($_GET['id'] == 1)
    echo "<li><a class='selected' href='pickup.php?id=1'>Add Field Marshal</a></li>";
   else
    echo "<li><a href='pickup.php?id=1'>Add Field Marshal</a></li>";
   if ($_GET['id'] == 2)
    echo "<li><a class='selected' href='pickup.php?id=2'>View Field Marshal</a></li>";
   else
    echo "<li><a href='pickup.php?id=2'>View Field Marshal</a></li>";
   if ($_GET['id'] == 3) {
    @$uid = $_GET["uid"];
    echo"<li><a class='selected' href='pickup.php?id=3&uid=$uid'>Edit Pickup User</a></li>";
   }
   if ($_GET['id'] == 4)
    echo "<li><a class='selected' href='pickup.php?id=4'>Mapping</a></li>";
   else
    echo "<li><a href='pickup.php?id=4'>Mapping</a></li>";
  }
  else {
   echo "<li><a class='selected' href='pickup.php?id=1'>Add Pickup User</a></li>";
   echo "<li><a href='pickup.php?id=2'>View Pickup Users</a></li>";
   echo "<li><a href='pickup.php?id=4'>Mapping</a></li>";
  }
  ?>
</ul>
<?php
include 'pickup_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1)
 include 'pages/addpickup.php';
else if ($_GET['id'] == 2)
 include 'pages/viewpickups.php';
else if ($_GET['id'] == 3)
include 'pages/editpickup.php';
  //include 'pages/viewpickups.php';
else if ($_GET['id'] == 4)
//include_once 'assign_function.php';
 include_once 'pages/assign_page.php';
?>
