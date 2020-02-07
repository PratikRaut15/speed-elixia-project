<?php  include_once 'transaction_functions.php'; 
$vehicleid = $_REQUEST['vehicleid'];
$type=  $_REQUEST['type'];
?>
<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='transaction.php?id=1'>Add Transaction</a></li>";
    else
        echo "<li><a href='transaction.php?id=1'>Add Transaction</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='transaction.php?id=2'>View Transactions</a></li>";
    else
        echo "<li><a href='transaction.php?id=2'>View Transactions</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='transaction.php?id=1'>Add Transaction</a></li>";
        echo "<li><a  href='transaction.php?id=2'>View Transactions</a></li>";
    }
?>
</ul>

<?php
if($_GET['type']==7)
{
    include 'pages/fuel.php';
}
else
{
    include 'pages/modifytransaction.php';
}
?>