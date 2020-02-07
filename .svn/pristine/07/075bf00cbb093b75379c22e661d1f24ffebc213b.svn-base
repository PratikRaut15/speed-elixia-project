<?php
$vehiclereps = array();
$counter = 0;
$vehiclename;
$total = 0;
$vehicles = GetVehicles_SQLite();
foreach($reports as $report)
{
    $sqlitedate = date("Y-m-d",$report->date);
    $date = explode('-', $sqlitedate);
    $date[1] = (int)$date[1]-1;
    $date[2] = (int)$date[2];
    $vehiclereps[$counter][0] = "new Date($date[0], $date[1], $date[2])";
    
    switch ($ReportType)
    {
        case 'Mileage':
            $id = $report->vehicleid;
            $vehiclereps[$counter][1] = $report->totaldistance/1000;
            $total += $report->totaldistance;
            $vehiclename = $vehicles[$id]['vehicleno'];
            break;
        case 'Utilization':
            $vehiclereps[$counter][1] = $report->idletime;
            $vehiclereps[$counter][2] = $report->runningtime;
            $vehiclename = $vehicles[$id]['vehicleno'];
            break;
        case 'Overspeed':
            $vehiclereps[$counter][1] = $report->overspeed;
            $total += $report->overspeed;
            $vehiclename = $vehicles[$id]['vehicleno'];
            break;
        case 'FenceConflict':
            $vehiclereps[$counter][1] = $report->fenceconflict;
            $total += $report->fenceconflict;
            $vehiclename = $vehicles[$id]['vehicleno'];
            break;
        case 'Speed':
            $distance = (int)$report->totaldistance/1000;
            $runningtime = (int)$report->runningtime/60;
            $vehiclereps[$counter][1] = $report->totaldistance/1000;
            if(isset($runningtime) && $runningtime>0)
                $vehiclereps[$counter][2] = (int)($distance/$runningtime);
            else
                $vehiclereps[$counter][2] = 0;
            $vehiclename = $vehicles[$id]['vehicleno'];
            break;
    }
    $counter += 1;
}
?>