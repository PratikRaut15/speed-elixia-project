<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='article.php?id=1'>Add Article Type</a></li>";
    else
        echo "<li><a href='article.php?id=1'>Add Article Type</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='article.php?id=2'>View Article Type</a></li>";
    else
        echo "<li><a href='article.php?id=2'>View Article Type</a></li>";
    if($_GET['id']==3)
        echo "<li><a class='selected' href='article.php?id=3'>Assign Article to Vehicle</a></li>";
    else
        echo "<li><a href='article.php?id=3'>Assign Article to Vehicle</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='article.php?id=3&aid=$_GET[aid]'>Edit Article Type</a></li>";
}
else
{
    echo "<li><a class='selected' href='article.php?id=1'>Add Article Type</a></li>";
    echo "<li><a href='article.php?id=2'>View Article Type</a></li>";
    echo "<li><a href='article.php?id=3'>Assign Article To Vehicle</a></li>";
}
?>
</ul>
<?php
include 'article_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addart.php';
else if($_GET['id']==2)
    include 'pages/viewart.php';
else if($_GET['id']==3)
    include 'pages/mapart.php';
else if($_GET['id']==4)
    include 'pages/editart.php';
?>
