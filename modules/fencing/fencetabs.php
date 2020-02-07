<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='fencing.php?id=1'>Create Fence</a></li>";
    else
        echo "<li><a href='fencing.php?id=1'>Create Fence</a></li>";
    if($_GET['id']==2){
        echo "<li><a class='selected' href='fencing.php?id=2'>View Fence List</a></li>";
    }
    else{
        echo "<li><a href='fencing.php?id=2'>View Fence List</a></li>";
    }
    if($_GET['id']==3){
        echo "<li><a class='selected' href='fencing.php?id=3&fid=$_GET[fid]'>View Fence On Map</a></li>";
    }
    if($_GET['id']==4){
        echo "<li><a class='selected' href='fencing.php?id=4'>Edit Fence</a></li>";
    }
    else{
        echo "<li><a href='fencing.php?id=4'>Edit Fence</a></li>";
    }
}
else
{
    echo "<li><a class='selected' href='fencing.php?id=1'>Create Fence</a></li>";
    echo "<li><a href='fencing.php?id=2'>View Fence List</a></li>";
    echo "<li><a href='fencing.php?id=4'>Edit Fence</a></li>";

}
?>
</ul>
<?php
include 'fence_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/createfence.php';
else if($_GET['id']==2)
    include 'pages/viewfence.php';
else if($_GET['id']==3)
    include 'pages/viewfenceonmap.php';
else if($_GET['id']==4)
    include 'pages/editfenceonmap.php';
?>
