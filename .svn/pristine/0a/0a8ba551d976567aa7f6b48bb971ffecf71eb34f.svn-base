<?php
$vehiclereps = array();
$counter = 0;
$vehiclename;
$total = 0;
$vehicles = GetVehicles_SQLite();

foreach($vehicles as $vehicle){
    
     $vehiclereps[$counter][1];
     foreach($reports as $report)
    {
        $id = $report->vehicleid;
        $vehiclename = $vehicles[$id]['vehicleno']; 
        //echo $vehicle['vehicleno'] ."==". $vehiclename; echo "<br/>";
        if($vehicle['vehicleno'] == $vehiclename){
         if(round($report->totaldistance/1000) > 0 )
            {
            $vehiclereps[$counter][0] = $vehicle['vehicleno'];
            $vehiclereps[$counter][1] += round(abs($report->totaldistance/1000));
            }
        
        }
    }
    $counter += 1;
}



?>