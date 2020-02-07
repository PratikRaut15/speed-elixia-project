<ul id="tabnav">
<?php
if(isset($_GET['id']))
{

    if($_GET['id']==2)
        echo "<li><a class='selected' href='model.php?id=2'>View Vehicle Model</a></li>";
    else
        echo "<li><a href='model.php?id=2'>View Vehicle Model</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='model.php?id=4&mid=$_GET[modelid]'>Edit Vehicle model</a></li>";
}
else
{
    echo "<li><a href='model.php?id=2'>View Vehicle model</a></li>";
}
?>
</ul>
<?php
include 'model_functions.php';

if($_GET['id']==2){    
include 'pages/viewmodel.php';
}
else if($_GET['id']==4){
    include 'pages/editmodel.php';
}
?>
