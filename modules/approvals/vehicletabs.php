<!-- <ul id="tabnav">
<?php
/*if(isset($_GET['id']))
{
        if($_GET['id']==1)
            echo "<li><a class='selected' href='approvals.php?id=1'>Vehicles</a></li>";
        else
            echo "<li><a href='approvals.php?id=1'>Vehicles</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='approvals.php?id=2'>Transactions</a></li>";
    else
        echo "<li><a href='approvals.php?id=2'>Transactions</a></li>";
    if($_GET['id']==3)
        echo "<li><a class='selected' href='approvals.php?id=3&vid=".$_GET['vid']."'>Edit Vehicles</a></li>";
}
else
{
    echo "<li><a class='selected' href='approvals.php?id=1'>Vehicles</a></li>";
    echo "<li><a href='approvals.php?id=2'>Transactions</a></li>";
}
 * 
 */
?>
</ul>
-->
<br/>
<?php
if(!isset($_GET['id']) || $_GET['id']==1)
{
include 'approval_functions.php';
    include 'pages/viewvehicles.php';
}else if($_GET['id']==2)
{
include 'approval_functions.php';
include 'pages/approve_transaction.php';
}
else if($_GET['id']==3)
{
include '../vehicle/vehicle_functions.php';
include 'pages/editvehicle_new.php';
}
else if($_GET['id']==4)
{
include 'approval_functions.php';
include 'pages/approvedata.php';
}
else if($_GET['id']==5)
{
include 'approval_functions.php';
include 'pages/approveacc.php';
}
?>