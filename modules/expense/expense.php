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
            echo "<li><a class='selected' href='expense.php?id=1'>Add Category</a></li>";
        }else{
            echo "<li><a href='expense.php?id=1'>Add Category</a></li>";
        }    
        if($_GET['id']==2){
            echo "<li><a class='selected' href='expense.php?id=2'>View Category</a></li>";
        }else{
            echo "<li><a href='expense.php?id=2'>View Category</a></li>";
        }
        if($_GET['id']==4){
            echo"<li><a class='selected' href='expense.php?id=3&did=$_GET[did]'>Edit Category</a></li>";
        }
}else{
        echo "<li><a class='selected' href='expense.php?id=1'>Add Category</a></li>";
        echo "<li><a href='expense.php?id=2'>View Category</a></li>";
}
?>
</ul>
<?php
include 'expense_function.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addcategory.php';
else if($_GET['id']==2)
    include 'pages/viewcategory.php';
else if($_GET['id']==3)
    include 'pages/category_edit.php';

require_once '../panels/footer.php';
?>
