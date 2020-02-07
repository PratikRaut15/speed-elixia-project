<?php
$vehicle_ses = '';
if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $vehicle_ses = $_SESSION['Warehouse'];
        $vehicles_ses = $_SESSION['Warehouse'];
    } else {
        $vehicle_ses = 'Warehouse';
        $vehicles_ses = 'Warehouse';
    }
} else {
    $vehicle_ses = 'Vehicle';
    $vehicles_ses = 'Vehicle No';
}
?>
<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='genset.php?id=1'>View $vehicle_ses</a></li>";
        } else {
            echo "<li><a href='genset.php?id=1'>View $vehicle_ses</a></li>";
        }
        if ($_GET['id'] == 2) {
            echo"<li><a class='selected' href='genset.php?id=2&vid=$_GET[vid]'>Edit $vehicle_ses</a></li>";
        }
    } else {
        echo "<li><a class='selected' href='genset.php?id=1'>View $vehicle_ses</a></li>";
    }
    ?>
</ul>
<?php
include 'genset_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1)
    include 'pages/viewvehicles.php';
else if ($_GET['id'] == 2)
    include 'pages/editvehicle.php';
?>