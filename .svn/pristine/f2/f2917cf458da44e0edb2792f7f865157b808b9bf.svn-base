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
        $date= date("Y-m-d", $report->date);
        $vehiclename = $vehicles[$id]['vehicleno']; 
         if($vehicle['vehicleno'] == $vehiclename && $report->average > 0)
        {
            if(round(($report->totaldistance/1000) / $report->average)  > 0)
            {
            
            $vehiclereps[$counter][0] = $vehicle['vehicleno'];
            $vehiclereps[$counter][1] += round(($report->totaldistance/1000) / $report->average , 2);
            $vehiclereps[$counter][2] += getFuelRefill($id, $date);
            }
        }
    }
    $counter += 1;
}



?>