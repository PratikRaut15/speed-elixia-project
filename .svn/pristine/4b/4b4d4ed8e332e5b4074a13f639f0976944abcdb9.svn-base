<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='support.php?id=1'>Add Ticket</a></li>";
    else
        echo "<li><a href='support.php?id=1'>Add Ticket</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='support.php?id=2'>View Ticket</a></li>";
    else
        echo "<li><a href='support.php?id=2'>View Ticket</a></li>";
    if($_GET['id']==3)
        echo"<li><a class='selected' href='support.php?id=3&isid=".$_GET["isid"]."'>Edit Ticket</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='support.php?id=1'>Add Ticket</a></li>";
        echo "<li><a href='support.php?id=2'>View Ticket</a></li>";
    }
?>
</ul>
<?php

include 'support_functions.php';
if(!isset($_GET['id']) || $_GET['id']=='1')
    include 'pages/addissue.php';
else if($_GET['id']=='2')
    include 'pages/viewissues.php';
else if($_GET['id']=='3')
    include 'pages/editissue.php';
?>