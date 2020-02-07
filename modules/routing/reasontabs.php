<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    
        if($_GET['id']==1)
            echo "<li><a class='selected' href='reasonmaster.php?id=1'>Add Reason</a></li>";
        else
            echo "<li><a href='reasonmaster.php?id=1'>Add Reason</a></li>";
        
        if($_GET['id']==2)
            echo "<li><a class='selected' href='reasonmaster.php?id=2'>Reason</a></li>";
        else
            echo "<li><a href='reasonmaster.php?id=2'>Reason</a></li>";
        
        if($_GET['id']==3)
            echo "<li><a class='selected' href='reasonmaster.php?id=2'>Edit Reason</a></li>";
        
   
        
        
}
else
{
    echo "<li><a  href='reasonmaster.php?id=2'>Add Reason</a></li>";
    echo "<li><a class='selected' href='reasonmaster.php?id=2'>Reason</a></li>";
    
}
?>
</ul>
<?php
include 'assign_function.php';

if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addreason.php';
else if($_GET['id']==2)
    include 'pages/reason.php';
else if($_GET['id']==3)
    include 'pages/editreason.php';



?>
