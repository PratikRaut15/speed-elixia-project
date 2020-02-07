<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    
        if($_GET['id']==1)
            echo "<li><a class='selected' href='delivery.php?id=1'>Orders</a></li>";
        else
            echo "<li><a href='delivery.php?id=1'>Orders</a></li>";
   
        
        if($_GET['id']==4)
            echo "<li><a class='selected' href='delivery.php?id=4'>Order Details</a></li>";
        //else
            //echo "<li><a href='delivery.php?id=2'>Status</a></li>";
        /**
        if($_GET['id']==3)
            echo"<li><a class='selected' href='delivery.php?id=3'>Reasons</a></li>";
        else
            echo "<li><a href='delivery.php?id=3'>Reasons</a></li>";
        */
}
else
{
    echo "<li><a class='selected' href='delivery.php?id=1'>Orders</a></li>";
    /**
    echo "<li><a href='delivery.php?id=2'>Status</a></li>";
    echo "<li><a href='delivery.php?id=3'>Reasons</a></li>";
     * *
     */
}
?>
</ul>
<?php
include 'delivery_functions.php';

if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/orders.php';
else if($_GET['id']==2)
    include 'pages/status.php';
else if($_GET['id']==3)
    include 'pages/reasons.php';
else if($_GET['id']==4)
    include 'pages/vieworder.php';


?>
