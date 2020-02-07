<ul id="tabnav">
    <?php
    if (isset($_GET['id']) && $_GET['id'] == 1) {
        //echo"<li><a class='selected' href='conditions.php?id=1'>Add Conditions</a></li>";
        echo"<li><a href='conditions.php?id=2' class='selected' >View Conditions</a></li>";
    } else if (isset($_GET['id']) && $_GET['id'] == 2) {
        //echo"<li><a href='conditions.php?id=1'>Add Conditions</a></li>";
        echo"<li><a class='selected'  href='conditions.php?id=2'>View Conditions</a></li>";
    } else {
        //echo "<li><a href='conditions.php?id=1' class='selected'>Add Conditions</a></li>";
        echo "<li><a href='conditions.php?id=2'>View Conditions</a></li>";
    }
    ?>
</ul>
<?php
include 'hierarchy_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1)
    include 'pages/addconditions.php';
else if ($_GET['id'] == 2)
    include 'pages/viewconditions.php';
else if ($_GET['id'] == 3)
    include 'pages/editconditions.php';
?>
