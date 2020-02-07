<?php
/**
 * Start page of Sales-module
 */
date_default_timezone_set("Asia/Calcutta");
require_once '../panels/header.php';
?>
<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
        if($_GET['id']==1){
            echo "<li><a class='selected' href='expensemng.php?id=1'>Add Expense</a></li>";
        }else{
            echo "<li><a href='expensemng.php?id=1'>Add Expense</a></li>";
        }    
        if($_GET['id']==2){
            echo "<li><a class='selected' href='expensemng.php?id=2'>View Expense</a></li>";
        }else{
            echo "<li><a href='expensemng.php?id=2'>View Expense</a></li>";
        }
        if($_GET['id']==3){
            echo"<li><a class='selected' href='expensemng.php?id=3'>Edit Expense</a></li>";
        }
}else{
        echo "<li><a class='selected' href='expensemng.php?id=1'>Add Expense</a></li>";
        echo "<li><a href='expensemng.php?id=2'>View Expense</a></li>";
}
?>
</ul>
<?php
include 'expense_function.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addexpense.php';
else if($_GET['id']==2)
    include 'pages/viewexpense.php';
else if($_GET['id']==3)
    include 'pages/expense_edit.php';

require_once '../panels/footer.php';
?>
