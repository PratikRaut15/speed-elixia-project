<ul id="tabnav">
<?php
include 'history_functions.php';
$vehicleid = GetSafeValueString($_GET['vid'], 'string');
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='history.php?id=1&vid=".$vehicleid."'>Vehicle Data</a></li>";
    else
        echo "<li><a href='history.php?id=1&vid=".$vehicleid."'>Vehicle Data</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='history.php?id=2&vid=".$vehicleid."'>Device Data</a></li>";
    else
        echo "<li><a href='history.php?id=2&vid=".$vehicleid."'>Device Data</a></li>";
    if ($_SESSION['Session_UserRole']=='elixir')
    {
        if($_GET['id']==3)
            echo"<li><a class='selected' href='history.php?id=3&vid=".$vehicleid."'>SIM Data</a></li>";
        else
            echo "<li><a href='history.php?id=3&vid=".$vehicleid."'>SIM Data</a></li>";
    }
    if($_GET['id']==4)
        echo"<li><a class='selected' href='history.php?id=4&vid=".$vehicleid."'>Miscellaneous Data</a></li>";
    else
        echo "<li><a href='history.php?id=4&vid=".$vehicleid."'>Miscellaneous Data</a></li>";
    if($_GET['id']==5)
        echo"<li><a class='selected' href='history.php?id=5&vid=".$vehicleid."'>Vehicle Map</a></li>";
    else
        echo "<li><a href='history.php?id=5&vid=".$vehicleid."'>Vehicle Map</a></li>";
}
else
{
    echo "<li><a class='selected' href='history.php?id=1&vid=".$vehicleid."'>Vehicle Data</a></li>";
    echo "<li><a href='history.php?id=2&vid=".$vehicleid."'>Device Data</a></li>";
    echo "<li><a href='history.php?id=3&vid=".$vehicleid."'>SIM Data</a></li>";
    echo "<li><a href='history.php?id=4&vid=".$vehicleid."'>Miscellaneous Data</a></li>";
    echo "<li><a href='history.php?id=5&vid=".$vehicleid."'>Vehicle Map</a></li>";
}
?>
</ul>
<?php
if($_GET['id']==1 || !isset($_GET['id']))
    echo GetVehicle ();
else if($_GET['id']==2)
    echo GetDevice();
else if($_GET['id']==3)
    echo GetSim();
else if($_GET['id']==4)
    echo GetMisc ();
else if($_GET['id']==5)
    include 'pages/viewvehhistory.php';
?>
