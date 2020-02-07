<?php include '../panels/header.php';?>
<div class="entry">
<center>
    	
<?php
include 'rtd_functions.php';
$vehicles = getvehicles_all_by_customer(); 

 //echo "<pre>".print_r($vehicles);
 
 ?>

    <table>
        <thead>
            <tr>
                <td>Vehicles</td>
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
   
    

</center>
</div>
<?php include '../panels/footer.php';?>
