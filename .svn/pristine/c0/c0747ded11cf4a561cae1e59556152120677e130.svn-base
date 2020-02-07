<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==8)
        echo "<li><a class='selected' href='pick.php?id=8'>Add Order</a></li>";
    else
        echo "<li><a href='pick.php?id=8'>Add Order</a></li>";
    if($_GET['id']==3)
        echo "<li><a class='selected' href='pick.php?id=3'>View Orders</a></li>";
    else
        echo "<li><a href='pick.php?id=3'>View Orders</a></li>";
    if($_GET['id']==19)
        echo "<li><a class='selected' href='pick.php?id=19'>Edit Order</a></li>";
}else{
    echo "<li><a class='selected' href='pick.php?id=8'>Add Order</a></li>";
    echo "<li><a href='pick.php?id=3'>View Orders</a></li>";
}
?>
</ul>
<?php
//include 'pickup_functions.php';
if($_GET['id']==3)
    include 'orders.php';
else if($_GET['id']==8)
    include 'pages/add_orders.php';
else if($_GET['id']==19)
    include 'order_edit.php';

?>


