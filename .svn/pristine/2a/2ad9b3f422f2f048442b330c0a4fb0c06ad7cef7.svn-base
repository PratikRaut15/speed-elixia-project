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
    $vehicle = 'Vehicle';
    $vehicles_ses = 'Vehicle No';
}
?>
<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='probity.php?id=1'>Add Probity $vehicle_ses</a></li>";
            echo "<li><a  href='probity.php?id=2'>View Probity $vehicle_ses</a></li>";
            echo "<li><a  href='probity.php?id=5'>Delete Probity $vehicle_ses</a></li>";
        }

        if ($_GET['id'] == 2) {
            echo "<li><a  href='probity.php?id=1'>Add Probity $vehicle_ses</a></li>";
            echo "<li><a class='selected' href='probity.php?id=2'>View Probity $vehicle_ses</a></li>";
//            echo "<li><a  href='probity.php?id=5'>Delete Probity $vehicle_ses</a></li>";
        }
        if ($_GET['id'] == 4) {
            echo "<li><a class='selected' href='probity.php?id=2'>Edit Probity $vehicle_ses</a></li>";
            echo "<li><a  href='probity.php?id=2'>View Probity $vehicle_ses</a></li>";
            //echo "<li><a  href='probity.php?id=5'>Delete Probity $vehicle_ses</a></li>";
        }
        if ($_GET['id'] == 5) {
            //echo "<li><a  href='probity.php?id=2'>Edit Probity $vehicle_ses</a></li>";
            echo "<li><a  href='probity.php?id=2'>View Probity $vehicle_ses</a></li>";
            echo "<li><a  href='probity.php?id=5' class='selected'>Delete Probity $vehicle_ses</a></li>";
        }
    }
    ?>
</ul>

<?php
include 'probity_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1)
    include 'pages/addvehicle.php';
else if ($_GET['id'] == 2) {
    include 'pages/viewvehicles.php';
} else if ($_GET['id'] == 4) {
    include 'pages/editvehicle.php';
} else if ($_GET['id'] == 5) {
    include 'pages/delprobity.php';
}
?>