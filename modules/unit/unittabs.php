<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='unit.php?id=1'>View Units</a></li>";
    else
        echo "<li><a href='unit.php?id=1'>View Units</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='unit.php?id=2'>Modify Unit</a></li>";
    if($_GET['id']==3)
        echo "<li><a class='selected' href='unit.php?id=3'>Unit Audit Trail</a></li>";
}
else
{
    echo "<li><a class='selected' href='unit.php?id=1'>View Units</a></li>";
}
?>
</ul>
<?php
include 'unit_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/viewunits.php';
else if($_GET['id']==2)
    include 'pages/editunit.php';
else if($_GET['id']==3)
    include 'pages/unit_history.php';
?>
