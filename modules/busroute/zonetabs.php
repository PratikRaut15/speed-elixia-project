<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='zone.php?id=1'>Create Zone</a></li>";
        } else {
            echo "<li><a href='zone.php?id=1'>Create Zone</a></li>";
        }if ($_GET['id'] == 2) {
            echo "<li><a class='selected' href='zone.php?id=2'>View Zone List</a></li>";
        } else {
            echo "<li><a href='zone.php?id=2'>View Zone List</a></li>";
        }
        if ($_GET['id'] == 3) {
            echo "<li><a class='selected' href='zone.php?id=3&zid=$_GET[zid]'>View Zone On Map</a></li>";
        }
        if ($_GET['id'] == 4) {
            echo "<li><a class='selected' href='zone.php?id=4'>Edit Zone</a></li>";
        } else {
            echo "<li><a href='zone.php?id=4'>Edit Zone</a></li>";
        }
    } else {
        echo "<li><a class='selected' href='zone.php?id=1'>Create Zone</a></li>";
        echo "<li><a href='zone.php?id=2'>View Zone List</a></li>";
        echo "<li><a href='zone.php?id=4'>Edit Zone</a></li>";
    }
    ?>
</ul>
<?php
include 'zone_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1) {
    include 'pages/createZone.php';
} else if ($_GET['id'] == 2) {
    include 'pages/veiwZone.php';
} else if ($_GET['id'] == 3) {
    include 'pages/viewZoneOnMap.php';
} else if ($_GET['id'] == 4) {
    include 'pages/editZonesOnMap.php';
}
?>
