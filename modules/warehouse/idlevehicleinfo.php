<?php
include_once '../../lib/bo/VehicleManager.php';
include_once 'rtd_functions.php';
?>
<?php
$idlehr = $_REQUEST['idle'];
$cid = $_REQUEST['cid'];
$vm = new VehicleManager($cid);
$vehicles = $vm->getvehiclesforridlestatus($idlehr);
?>
<style type="text/css">
 .table, tr, th, td{border:1px solid #ccc;}
</style>
<table>
    <thead>
        <tr>
            <th>Vehicle No</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($vehicles as $vehicle){
        ?>
            <tr>
                <td><?php echo $vehicle->vehicleno;?></td>
               
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
