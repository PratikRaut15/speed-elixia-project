
<ul id="tabnav">
    <?php
    
    
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 1)
            echo "<li><a class='selected' href='battery_srno.php?id=1'>Add Battery Serial No.</a></li>";
        else
            echo "<li><a href='battery_srno.php?id=1'>Add Battery Serial No.</a></li>";
        if ($_GET['id'] == 2)
            echo "<li><a class='selected' href='battery_srno.php?id=2'>View Battery Serial No.</a></li>";
        else
            echo "<li><a href='battery_srno.php?id=2'>View Battery Serial No.</a></li>";
        if ($_GET['id'] == 4)
            echo"<li><a class='selected' href='battery_srno.php?id=4&bmid=$_GET[bmid]'>Edit Battery Serial No.</a></li>";
        if ($_GET['id'] == 3)
            echo"<li><a class='selected' href='battery_srno.php?id=3'>Upload Battery Serial No. Data</a></li>";
        else
            echo "<li><a href='battery_srno.php?id=3'>Upload Battery Serial No. Data</a></li>";
    }
    else {
        echo "<li><a href='battery_srno.php?id=1'>Add Battery Serial No.</a></li>";
        echo "<li><a class='selected' href='battery_srno.php?id=2'>View Battery Serial No.</a></li>";
    }
    ?>
</ul>
<?php
include 'batterysrno_functions.php';
if (!isset($_GET['id']) || $_GET['id'] == 1)
    include 'pages/addbatterysrno.php';
else if ($_GET['id'] == 2)
    include 'pages/viewbatterysrno.php';
else if ($_GET['id'] == 3)
    include 'pages/uploadbatterysrno.php';
else if ($_GET['id'] == 4) {
    include 'pages/editbatterysrno.php';
}
?>