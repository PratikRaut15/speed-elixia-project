<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    
        if($_GET['id']==1)
            echo "<li><a class='selected' href='statusmaster.php?id=1'>Add Status</a></li>";
        else
            echo "<li><a href='statusmaster.php?id=1'>Add Status</a></li>";
        
        if($_GET['id']==2)
            echo "<li><a class='selected' href='statusmaster.php?id=2'>Status</a></li>";
        else
            echo "<li><a href='statusmaster.php?id=2'>Status</a></li>";
        
        if($_GET['id']==3)
            echo "<li><a class='selected' href='statusmaster.php?id=2'>Edit</a></li>";
        
   
        
        
}
else
{
    echo "<li><a  href='statusmaster.php?id=2'>Add Status</a></li>";
    echo "<li><a class='selected' href='statusmaster.php?id=2'>Status</a></li>";
    
}
?>
</ul>
<?php
include 'delivery_functions.php';

if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addstatus.php';
else if($_GET['id']==2)
    include 'pages/status.php';
else if($_GET['id']==3)
    include 'pages/editstatus.php';



?>
