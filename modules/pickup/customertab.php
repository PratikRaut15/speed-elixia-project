<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='customer.php?id=1'>Add Customer</a></li>";
    else
        echo "<li><a href='customer.php?id=1'>Add Customer</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='customer.php?id=2'>View Customers</a></li>";
    else
        echo "<li><a href='customer.php?id=2'>View Customers</a></li>";
    if($_GET['id']==3)
        echo"<li><a class='selected' href='customer.php?id=3&vid=$_GET[vid]'>Edit Customers</a></li>";
}
else
{
    echo "<li><a class='selected' href='customer.php?id=1'>Add Customer</a></li>";
    echo "<li><a href='customer.php?id=2'>View Customers</a></li>";
}
?>
</ul>
<?php
include 'pickup_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addcustomer.php';
else if($_GET['id']==2)
    include 'pages/viewcustomers.php';
else if($_GET['id']==3)
    include 'pages/editcustomer.php';
?>
