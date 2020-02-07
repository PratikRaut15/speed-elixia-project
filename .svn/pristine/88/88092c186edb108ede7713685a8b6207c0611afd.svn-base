<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='ecode.php?id=1'>Generate Client Code</a></li>";
    else
        echo "<li><a href='ecode.php?id=1'>Generate Client Code</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='ecode.php?id=2'>View Client Codes</a></li>";
    else
        echo "<li><a href='ecode.php?id=2'>View Client Codes</a></li>";
    if($_GET['id']==3)
        echo "<li><a class='selected' href='ecode.php?id=3'>Edit Client Code</a></li>";
    
}
else
{
    echo "<li><a class='selected' href='ecode.php?id=1'>Generate Client Code</a></li>";
    echo "<li><a href='ecode.php?id=2'>View Client Codes</a></li>";
}
?>
</ul>
<?php
include 'ecode_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/createecode.php';
else if($_GET['id']==2)
    include 'pages/viewecode.php';
else if($_GET['id']==3)
    include 'pages/editcode.php';
?>
