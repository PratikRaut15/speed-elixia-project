<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='dealer.php?id=1'>Add Dealer</a></li>";
    else
        echo "<li><a href='dealer.php?id=1'>Add Dealer</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='dealer.php?id=2'>View Dealers</a></li>";
    else
        echo "<li><a href='dealer.php?id=2'>View Dealers</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='dealer.php?id=4&dealerid=$_GET[dealerid]'>Edit Dealer</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='dealer.php?id=1'>Add Dealer</a></li>";
        echo "<li><a href='dealer.php?id=2'>View Dealers</a></li>";        /*echo "<li><a href='route.php?id=4'>Edit route</a></li>";*/
    }
?>
</ul>
<?php
include 'dealer_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1){
    include 'pages/adddealer.php';
}
else if($_GET['id']==2){
    include 'pages/viewdealers.php';
}
else if($_GET['id']==4){
    include 'pages/editdealer.php';
}
?>