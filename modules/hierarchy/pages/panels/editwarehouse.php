<?php
if (isset($_SESSION['Warehouse'])) {
    $custom = $_SESSION['Warehouse'];
} else {
    $custom = "Warehouse";
}
?>
<span id="vehiclecomp" style="display:none;">Please enter a <?php echo $custom; ?> Name.</span>
<span id="samename" style="display:none;"><?php echo $custom; ?> already exists.</span>  
<span id="workkeyerr" style="display:none;">Please enter Work Key.</span>       
<span id="batcherr" style="display:none;">Please enter Batch.</span> 