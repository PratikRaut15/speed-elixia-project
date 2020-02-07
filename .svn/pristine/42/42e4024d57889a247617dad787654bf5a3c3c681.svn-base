<?php include 'enh_checkpoint_functions.php'; ?>
<?php
class VOautocomplete{};		
       	if(isset($_POST['data']))
        {
           if($_POST['data']!=''){
            $q='%'.$_POST['data'].'%'; 
            }
             $VehicleManager = new VehicleManager($_SESSION['customerno']);
             $devices = $VehicleManager->getvehicleswithchks_ById($q);
           
            if($devices)
            {
                    $data = array();
                    foreach($devices as $row)
                    {
                        $vehicle = new VOautocomplete();
                        $vehicle->vehicleno = $row->vehicleno;
                        $vehicle->vehicleid = $row->vehicleid;
                        $data[] = $vehicle;
                    }
                    echo json_encode($data);
            }
        }
        
        
?>