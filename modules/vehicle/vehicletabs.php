<?php
$addpermission = isset($pageAcessDetail['add_permission']) ? $pageAcessDetail['add_permission'] : 0;
$edit_permission = isset($pageAcessDetail['edit_permission']) ? $pageAcessDetail['edit_permission'] : 0;
$delete_permission = isset($pageAcessDetail['delete_permission']) ? $pageAcessDetail['delete_permission'] : 0;
$vehicle_ses = '';
if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION["Warehouse"])) {
        $vehicles_ses = $vehicle_ses = $_SESSION["Warehouse"];
    } else {
        $vehicles_ses = $vehicle_ses = 'Warehouse';
    }
} else {
    $vehicle = 'Vehicle';
    $vehicles_ses = 'Vehicle No';
}
?>
<ul id="tabnav">
    <?php
if ($_SESSION['switch_to'] == 1) {
    //maintenance
    if ($addpermission == 1 || $_SESSION['role_modal'] == 'elixir') {
        if ($_GET['id'] == 6) {
            echo "<li><a class='selected' href='vehicle.php?id=6'>Add $vehicle_ses</a></li>";
        } else {
            echo "<li><a href='vehicle.php?id=6'>Add $vehicle_ses</a></li>";
        }
    }
    if ($edit_permission == 1 || $_SESSION['role_modal'] == 'elixir') {
        if ($_GET['id'] == 4) {
            echo "<li><a class='selected' href='vehicle.php?id=4&vid=$_GET[vid]'>Edit $vehicle_ses</a></li>";
        }
        if ($_GET['id'] == 7) {
            echo "<li><a class='selected' href='vehicle.php?id=7&vid=$_GET[vid]'>Edit $vehicle_ses</a></li>";
        }
        if ($_GET['id'] == 9) {
            echo "<li><a class='selected' href='vehicle.php?id=9&vid=$_GET[vid]'>Vehicle Audit Trail</a></li>";
        }
    }
    echo "<li><a href='vehicle.php?id=2'>View" . $vehicle_ses . "s</a></li>";
} else {
    if (isset($_GET['id'])) {
        if ($_SESSION['use_maintenance'] == '0' || $_SESSION['switch_to'] == '0') {
            if ($_GET['id'] == 1) {
                echo "<li><a class='selected' href='vehicle.php?id=1'>Add $vehicle_ses</a></li>";
            } else {
                echo "<li><a href='vehicle.php?id=1'>Add $vehicle_ses</a></li>";
            }
        } elseif ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == '1') {
            if ($_GET['id'] == 6) {
                echo "<li><a class='selected' href='vehicle.php?id=6'>Add $vehicle_ses</a></li>";
            } else {
                echo "<li><a href='vehicle.php?id=6'>Add $vehicle_ses</a></li>";
            }
        }
        if ($_GET['id'] == 2) {
            echo "<li><a class='selected' href='vehicle.php?id=2'>View $vehicle_ses</a></li>";
        } else {
            echo "<li><a href='vehicle.php?id=2'>View $vehicle_ses</a></li>";
        }

        if ($_SESSION['use_tracking'] == 1 && $_SESSION['switch_to'] != '1') {
            if ($_GET['id'] == 3) {
                echo "<li><a class='selected' href='vehicle.php?id=3'>Map $vehicle_ses</a></li>";
            } else {
                echo "<li><a href='vehicle.php?id=3'>Map $vehicle_ses</a></li>";
            }
        }
        if ($_GET['id'] == 4) {
            echo "<li><a class='selected' href='vehicle.php?id=4&vid=$_GET[vid]'>Edit $vehicle_ses</a></li>";
        }

        if ($_GET['id'] == 7) {
            echo "<li><a class='selected' href='vehicle.php?id=7&vid=$_GET[vid]'>Edit $vehicle_ses</a></li>";
        }

        if ($_GET['id'] == 5) {
            echo "<li><a class='selected' href='vehicle.php?id=5&vid=$_GET[vid]'>$vehicle_ses History</a></li>";
        }

        if ($_SESSION['switch_to'] != '1') {
            if ($_GET['id'] == 8) {
                echo "<li><a class='selected' href='vehicle.php?id=8'>$vehicle_ses Sequence</a></li>";
            } else {
                echo "<li><a href='vehicle.php?id=8'>$vehicle_ses Sequence</a></li>";
            }
        }
        if ($_GET['id'] == 9) {
            echo "<li><a class='selected' href='vehicle.php?id=9&vid=$_GET[vid]'>Vehicle Audit Trail</a></li>";
        }
        if ($_GET['id'] == 10) {
            echo "<li><a class='selected' href='vehicle.php?id=10'>View deleted vehicles</a></li>";
        } else {
            //Only visible to Malcom Anthony for testing purposes.
            if ($_SESSION['userid'] == 298) {
                echo "<li><a  href='vehicle.php?id=10'>View deleted vehicles</a></li>";
            }
        }
    } else {
        if ($_SESSION['use_maintenance'] == '1') {
            echo "<li><a href='vehicle.php?id=6'>Add $vehicle_ses</a></li>";
        } elseif ($_SESSION['use_maintenance'] == '0') {
            echo "<li><a class='selected' href='vehicle.php?id=1'>Add $vehicle_ses</a></li>";
        }
        if ($_SESSION['userid'] == '298') {
            echo "<li><a class='selected' href='vehicle.php?id=10'>View $vehicle_ses</a></li>";
        }
        echo "<li><a href='vehicle.php?id=2'>View " . $vehicle_ses . "s</a></li>";
        if ($_SESSION['use_tracking'] == 1) {
            echo "<li><a href='vehicle.php?id=3'>Map $vehicle_ses</a></li>";
        }
        if ($_GET['id'] == 9) {
            echo "<li><a class='selected' href='vehicle.php?id=9&vid=$_GET[vid]'>Vehicle Audit Trail</a></li>";
        }
    }
}
?>
</ul>
<?php
include 'vehicle_functions.php';
include '../nomenclature/nomenclature_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1) {
    include 'pages/addvehicle.php';
} elseif ($_GET['id'] == 2) {
    include 'pages/viewvehicles.php';
} elseif ($_GET['id'] == 3) {
    include 'pages/mapvehicles.php';
} elseif ($_GET['id'] == 4) {
    include 'pages/editvehicle.php';
} elseif ($_GET['id'] == 5) {
    include 'pages/vehiclehist.php';
} elseif ($_GET['id'] == 6) {
    include 'pages/addvehicle_latest.php';
} elseif ($_GET['id'] == 7) {
    include 'pages/editvehicle_latest.php';
} elseif ($_GET['id'] == 8) {
    include 'pages/vehicle_sequence.php';
} elseif ($_GET['id'] == 9) {
    include 'pages/vehicle_logs.php';
} elseif ($_GET['id'] == 10) {
    include 'pages/viewDeletedVehicles.php';
}

?>