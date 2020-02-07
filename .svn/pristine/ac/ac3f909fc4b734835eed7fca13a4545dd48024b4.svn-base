<!--<ul id="tabnav">-->
<?php
//if(isset($_GET['id']))
//{
//        if($_GET['id']==1)
//            echo "<li><a class='selected' href='approvals.php?id=1'>Vehicles</a></li>";
//        else
//            echo "<li><a href='approvals.php?id=1'>Vehicles</a></li>";
//    if($_GET['id']==2)
//        echo "<li><a class='selected' href='approvals.php?id=2'>Transactions</a></li>";
//    else
//        echo "<li><a href='approvals.php?id=2'>Transactions</a></li>";
//    if($_GET['id']==3)
//        echo "<li><a class='selected' href='approvals.php?id=3&vid=".$_GET['vid']."'>Edit Vehicles</a></li>";
//}
//else
//{
//    echo "<li><a class='selected' href='approvals.php?id=1'>Vehicles</a></li>";
//    echo "<li><a href='approvals.php?id=2'>Transactions</a></li>";
//}
?>
<!--</ul>-->
<?php
if(!isset($_GET['id']) || $_GET['id']==1)
{
include 'distance_dashboard_functions.php';
    include 'distance_hist.php';
}else if($_GET['id']==2)
{
include 'distance_dashboard_functions.php';
    include 'pages/approve_transaction.php';
}
else if($_GET['id']==3)
{
include 'distance_dashboard_functions.php';
include 'pages/editvehicle_new.php';

}
?>
