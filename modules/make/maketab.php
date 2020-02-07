<ul id="tabnav">
<?php
if(isset($_GET['id']))
{

    if($_GET['id']==2)
        echo "<li><a class='selected' href='make.php?id=2'>View Vehicle Make</a></li>";
    else
        echo "<li><a href='make.php?id=2'>View Vehicle Make</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='make.php?id=4&mid=$_GET[mid]'>Edit Vehicle Make</a></li>";
}
else
{
    echo "<li><a href='make.php?id=2'>View Vehicle Make</a></li>";
}
?>
</ul>
<?php
include 'make_functions.php';

if($_GET['id']==2){    
include 'pages/viewmake.php';
}
else if($_GET['id']==4){
    include 'pages/editmake.php';
}
?>
