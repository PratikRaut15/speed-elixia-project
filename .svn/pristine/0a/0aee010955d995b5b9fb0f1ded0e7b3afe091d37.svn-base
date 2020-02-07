<ul id="tabnav">
    <?php
    if (isset($_GET['id']) && $_GET['id']==1) {
        echo"<li><a class='selected' href='hierarchy.php?id=1'>Add Roles</a></li>";
        echo"<li><a href='hierarchy.php?id=2'>View Roles</a></li>";
    }else if (isset($_GET['id']) && $_GET['id']==2) {
        echo"<li><a href='hierarchy.php?id=1'>Add Roles</a></li>";
        echo"<li><a class='selected'  href='hierarchy.php?id=2'>View Roles</a></li>";
    } else {
        echo "<li><a href='hierarchy.php?id=1'>Add Roles</a></li>";
        echo "<li><a href='hierarchy.php?id=2'>View Roles</a></li>";
    }
    ?>
</ul>
<?php
include 'hierarchy_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1)
    include 'pages/addrole.php';
else if ($_GET['id'] == 2)
    include 'pages/viewroles.php';
else if ($_GET['id'] == 3)
    include 'pages/editrole.php';
?>
