<?php
if(isset($_POST['STdate']) && isset($_POST['EDdate'])){

    include_once 'reports_chk_all_functions.php';
    
    $STdate = GetSafeValueString($_POST['STdate'], 'string');
    $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";

    $title = 'Checkpoint Report';
    $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate ".$_POST['STime'], "End Date: $EDdate ".$_POST['ETime']);
    echo table_header($title, $subTitle, null);
    echo "<div class='container-fluid'>";
    
    if(strtotime($STdate)>strtotime($EDdate)){
        echo $error;
    }
    else if(isset($_SESSION['ecodeid'])){
        $startdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d',strtotime(GetSafeValueString($_POST['e_end'], 'string')));
        
        if(strtotime($STdate) < strtotime($startdate) || strtotime($EDdate) > strtotime($enddate)){
            echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
        }
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $VEHICLES = $vehiclemanager->Get_All_Vehicles_SQLite();
        
        foreach($VEHICLES as $key_vehicleid=>$vehicle_name){
            $vehicleid = $key_vehicleid; 
            $vehicle_number = $vehicle_name['vehicleno'];
            $checkpoints = getcheckpoints($vehicleid);
            $rawrep = getchkrep($STdate,$EDdate,$_POST['STime'],$_POST['ETime'],$vehicleid,$checkpoints);
            if(isset($rawrep) && count($rawrep)>0){
                table_html($rawrep[0]->vehicleno);
                $chkrep = processchkrep($rawrep);
                displayrep($chkrep,$vehicleid,$vehicle_number);
                echo "</tbody></table><br/>";
            }
        }
        
    }
    else
    {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $VEHICLES = $vehiclemanager->Get_All_Vehicles_SQLite();
        
        foreach($VEHICLES as $key_vehicleid=>$vehicle_name){
            $vehicleid = $key_vehicleid; 
            $vehicle_number = $vehicle_name['vehicleno'];
            $checkpoints = getcheckpoints($vehicleid);
            $vehicle = $vehiclemanager->get_vehicle_details($vehicleid);
            
            $rawrep = getchkrep($STdate,$EDdate,$_POST['STime'],$_POST['ETime'],$vehicleid,$checkpoints,$vehicle);
            
            if(isset($rawrep) && count($rawrep)>0){
                table_html($rawrep[0]->vehicleno);
                $chkrep = processchkrep($rawrep);
                displayrep($chkrep,$vehicleid, $vehicle_number);
                echo "</tbody></table><br/>";
            }
        }
    }
    echo "</div>";
}
?>
