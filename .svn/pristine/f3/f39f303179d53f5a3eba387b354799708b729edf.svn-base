<style>
        .rightalign{
            text-align: right;
        }
    </style>

<?php 
if($_GET['id']!=5){
?>
<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='driver.php?id=1'>Add Driver</a></li>";
    else
        echo "<li><a href='driver.php?id=1'>Add Driver</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='driver.php?id=2'>View Drivers</a></li>";
    else
        echo "<li><a href='driver.php?id=2'>View Drivers</a></li>";

    if($_GET['id']==3 && $_SESSION['role_modal'] == 'elixir'){
        echo"<li><a class='selected' href='driver.php?id=3'>Assign Vehicle</a></li>";
    }
    else if($_SESSION['role_modal']== 'elixir'){
        echo "<li><a href='driver.php?id=3'>Assign Vehicle</a></li>";
    }
    if($_GET['id']==4)
        echo"<li><a class='selected' href='driver.php?id=4&did=$_GET[did]'>Edit Driver</a></li>";
        //echo"<li><a class='selected' href='driver.php?id=4&did=$_GET[did]'>Edit Driver</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='driver.php?id=1'>Add Driver</a></li>";
        echo "<li><a href='driver.php?id=2'>View Driver</a></li>";
        if($_SESSION['role_modal'] == 'elixir'){
            echo "<li><a href='driver.php?id=3'>Assign Vehicle</a></li>";
        }
    }
?>
</ul>
<?php 
}
?>    

<?php
include 'driver_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/adddriver.php';
else if($_GET['id']==2)
    include 'pages/viewdrivers.php';
else if($_GET['id']==3 && $_SESSION['role_modal'] == 'elixir')
    include 'pages/assignvehicle.php';
else if($_GET['id']==4)
    include 'pages/editdriver.php';
else if($_GET['id']==5)
    include 'pages/viewdrivers_exp.php';
?>
