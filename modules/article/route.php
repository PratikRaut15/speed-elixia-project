<?php
include 'article_functions.php';
if(isset($_GET['delartid']))
{
    delart($_GET['delartid']);
    header('location: article.php?id=2');
}
else if(isset ($_POST['artid']))
{
    editart($_POST);
    header('location: article.php?id=2');
}
else if(isset($_POST['artname']) && isset($_POST['maxtemp']) && isset($_POST['mintemp']))
{
    addart($_POST);
    header('location: article.php?id=2');
}
else if(isset ($_POST))
{
    unset($_POST['map']);
    mapart($_POST);
    header('location: article.php?id=2');
}
?>
