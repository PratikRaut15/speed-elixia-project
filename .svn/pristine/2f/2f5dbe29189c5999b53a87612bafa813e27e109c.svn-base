<?php
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/VehicleManager.php';
if(isset($_POST['vehicle_id'])){
    $vm = new VehicleManager($_POST['customer_no']);
    $data = $vm->get_filter_vehicle($_POST['vehicle_id']);
    ?>
<ul>
    <?php
    if($data){
        foreach($data as $thisdata)
        {
        ?>
<li onClick='fill("<?php echo $thisdata->vehicleno; ?>")'><?php echo $thisdata->vehicleno; ?></li>
<?php
        }
    }
    ?>
</ul>
<?php
}
?>