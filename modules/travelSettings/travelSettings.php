<?php 
include '../panels/header.php';
echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/travelSettings.js'></script>";
?>
    <div class="entry">
       <center><?php 
       include 'travelSettingtabs.php'; ?></center>
    </div>
<?php include '../panels/footer.php';?>