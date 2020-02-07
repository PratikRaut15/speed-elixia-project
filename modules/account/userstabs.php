<?php
$addpermission = isset($pageAcessDetail['add_permission']) ? $pageAcessDetail['add_permission'] : 0;
$edit_permission = isset($pageAcessDetail['edit_permission']) ? $pageAcessDetail['edit_permission'] : 0;
$delete_permission = isset($pageAcessDetail['delete_permission']) ? $pageAcessDetail['delete_permission'] : 0;
?>
<ul id="tabnav">
    <?php
if ($_SESSION['switch_to'] == 1) {
    if ($addpermission == 1 || $_SESSION['role_modal'] == 'elixir') {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='users.php?id=1'>Add User</a></li>";
        } else {
            echo "<li><a href='users.php?id=1'>Add User</a></li>";
        }
    }
    if (isset($_GET['uid']) && $_GET['uid'] != "" && $edit_permission == 1 && $_SESSION['role_modal'] == 'elixir') {
        $uid = isset($_GET['uid']) ? $_GET['uid'] : '';
        echo "<li><a class='selected' href='users.php?id=3&uid=$uid'>Edit User</a></li>";
        if ($_GET['id'] == 4) {
            echo "<li><a class='selected' href='users.php?id=4'>User Audit Trail</a></li>";
        }
    }
    if ($_GET['id'] == 2) {
        echo "<li><a class='selected' href='users.php?id=2'>View Users</a></li>";
    } else {
        echo "<li><a href='users.php?id=2'>View Users</a></li>";
    }if ($_GET['id'] == 5) {
        echo "<li><a class='selected' href='users.php?id=5'>View deleted users</a></li>";
    } else {
        if ($_SESSION['userid'] == 298) {
            echo "<li><a href='users.php?id=5'>View deleted users</a></li>";
        }
    }
} else {
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='users.php?id=1'>Add User</a></li>";
        } else {
            echo "<li><a href='users.php?id=1'>Add User</a></li>";
        }
        if ($_GET['id'] == 2) {
            echo "<li><a class='selected' href='users.php?id=2'>View Users</a></li>";
        } else {
            echo "<li><a href='users.php?id=2'>View Users</a></li>";
        }
        if ($_GET['id'] == 3) {
            echo "<li><a class='selected' href='users.php?id=3&uid=$_GET[uid]'>Edit User</a></li>";
        }
        if ($_GET['id'] == 4) {
            echo "<li><a class='selected' href='users.php?id=4'>User Audit Trail</a></li>";
        }if ($_GET['id'] == 5) {
            echo "<li><a class='selected' href='users.php?id=5'>View deleted users</a></li>";
        } else {
            if ($_SESSION['userid'] == 298) {
                echo "<li><a href='users.php?id=5'>View deleted users</a></li>";
            }
        }
    } else {
        echo "<li><a class='selected' href='users.php?id=1'>Add User</a></li>";
        echo "<li><a href='users.php?id=2'>View Users</a></li>";
        if ($_SESSION['userid'] == 298) {
            echo "<li><a href='users.php?id=5'>View deleted users</a></li>";
        }
    }
}
?>
</ul>
<?php
require 'account_functions.php';
//<editor-fold defaultstate="collapsed" desc="Comman Code Used For Add/Edit User">
if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $vehicle = $_SESSION['Warehouse'];
        $vehicles = $_SESSION['Warehouse'] . "s";
    } else {
        $vehicle = 'Warehouse';
        $vehicles = 'Warehouses';
    }
} else {
    $vehicle = 'Vehicle';
    $vehicles = 'Vehicles';
}
/* Get ReporMaster */
$reportsMaster = getReportsMaster($_SESSION['customerno']);
/* Get Customer Checkpoint Exception */
$exceptions = getCheckpointExceptions();
//</editor-fold>
if (!isset($_GET['id']) || $_GET['id'] == 1) {
    include 'pages/adduser.php';
} elseif ($_GET['id'] == 2) {
    include 'pages/viewusers.php';
} elseif ($_GET['id'] == 3) {
    include 'pages/edituser.php';
} elseif ($_GET['id'] == 4) {
    include 'pages/user_history.php';
} elseif ($_GET['id'] == 5) {
    include 'pages/viewDeletedUsers.php';
}
?>