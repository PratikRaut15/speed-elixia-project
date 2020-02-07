<?php
$chkpt_type = '';
if ($_SESSION['customerno'] == '118') {
    $chkpt_type = 'Circular Zone Type';
} else {
    $chkpt_type = 'Checkpoint Type';
}
?>

<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='checkpointtype.php?id=1'>Create " . $chkpt_type . "</a></li>";
        } else {
            echo "<li><a href='checkpointtype.php?id=1'>Create " . $chkpt_type . "</a></li>";
        }
        if ($_GET['id'] == 2) {
            echo "<li><a class='selected' href='checkpointtype.php?id=2'>View " . $chkpt_type . "</a></li>";
        } else {
            echo "<li><a href='checkpointtype.php?id=2'>View " . $chkpt_type . "</a></li>";
        }
        if ($_GET['id'] == 3) {
            echo "<li><a class='selected' href='checkpointtype.php?id=3&ctid='" . $_GET['ctid'] . "'>Edit " . $chkpt_type . "</a></li>";
        }
    } else {
        echo "<li><a class='selected' href='checkpointtype.php?id=1'>Create " . $chkpt_type . "</a></li>";
        echo "<li><a href='checkpointtype.php?id=2'>View " . $chkpt_type . "</a></li>";
    }
    ?>
</ul>
<?php
include 'checkpoint_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1) {
    include 'pages/createchktype.php';
} elseif ($_GET['id'] == 2) {
    include 'pages/viewchktype.php';
} elseif ($_GET['id'] == 3) {
    include 'pages/editchktype.php';
}
?>
