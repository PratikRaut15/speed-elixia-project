<?php 
include '../panels/header.php';
echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/nomenclature.js'></script>";
?>
    <div class="entry">
       <center><?php include 'nomenclaturetabs.php'?></center>
    </div>
<?php include '../panels/footer.php';?>