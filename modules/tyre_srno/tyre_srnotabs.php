<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
//        if ($_GET['id'] == 1)
//            echo "<li><a class='selected' href='tyre_srno.php?id=1'>Add Tyre Serial No.</a></li>";
//        else
//            echo "<li><a href='tyre_srno.php?id=1'>Add Tyre Serial No.</a></li>";
        if ($_GET['id'] == 2)
            echo "<li><a class='selected' href='tyre_srno.php?id=2'>View Tyre Serial No.</a></li>";
        else
            echo "<li><a href='tyre_srno.php?id=2'>View Tyre Serial No.</a></li>";
        if ($_GET['id'] == 4)
            echo"<li><a class='selected' href='tyre_srno.php?id=4&vehid=$_GET[vehid]'>Edit Tyre Serial No.</a></li>";
        if ($_GET['id'] == 3)
            echo"<li><a class='selected' href='tyre_srno.php?id=3'>Upload Tyre Serial No. Data</a></li>";
        else
            echo "<li><a href='tyre_srno.php?id=3'>Upload Tyre Serial No. Data</a></li>";
    }
    else {
        echo "<li><a href='tyre_srno.php?id=1'>Add Tyre Serial No.</a></li>";
        echo "<li><a class='selected' href='tyre_srno.php?id=2'>View Tyre Serial No.</a></li>";
    }
    ?>
</ul>
<?php
include 'tyresrno_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 2)
    include 'pages/viewtyresrno.php';
else if ($_GET['id'] == 3)
    include 'pages/uploadtyresrno.php';
else if ($_GET['id'] == 4) {
    include 'pages/edittyresrno.php';
}
?>