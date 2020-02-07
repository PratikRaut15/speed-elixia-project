<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1){
        echo "<li><a class='selected' href='parts.php?id=1'>Add Part</a></li>";
        echo "<li><a href='parts.php?id=2'>View Parts</a></li>";
    }elseif($_GET['id']==2){
        echo "<li><a href='parts.php?id=1'>Add Part</a></li>";
        echo "<li><a class='selected' href='parts.php?id=2'>View Parts</a></li>";
    }elseif($_GET['id']==4){
        $pid = $_GET['pid'];
        echo "<li><a href='parts.php?id=1'>Add Part</a></li>";
        echo"<li><a class='selected' href='parts.php?id=4&pid=$pid'>Edit Part</a></li>";
        echo "<li><a href='parts.php?id=2'>View Parts</a></li>";
    }else{
        echo "<li><a href='parts.php?id=1'>Add Part</a></li>";
        echo "<li><a href='parts.php?id=2' class='selected'>View Parts</a></li>";
    }
}
else
{
    echo "<li><a class='selected' href='parts.php?id=1'>Add Part</a></li>";
    echo "<li><a href='parts.php?id=2'>View Parts</a></li>";
}
?>
</ul>
<?php
include 'part_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addpart.php';
else if($_GET['id']==2)
    include 'pages/viewparts.php';
else if($_GET['id']==4)
    include 'pages/editpart.php';

?>
