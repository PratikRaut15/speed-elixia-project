<?php
include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once '../../lib/bo/CommunicationQueueManager.php';
include_once 'files/dailyreport.php';

class VODRM{}

$drm = new DailyReportManager(0);
$devices = $drm->GetDevicesForReport('dailyreport');

$Bad=1;

$cqm = new CommunicationQueueManager();
$cvo = new VOCommunicationQueue();
$cvo->email = 'sanketsheth@elixiatech.com';
$cvo->subject = "Error CronDaily Report";
$cvo->phone = "";
$cvo->type = 0;
 set_time_limit(0);
if(isset($devices))
{
    $reports = array();    
    $ids = array();
    foreach ($devices as $device)
    {
        if($Bad!=0)
        {
            $unitno = $device->unitno;
            $customerno = $device->customerno;
            $date = $device->date;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        
            if(file_exists($location))
            {
                $Data = DataFromSqlite($location);
                if($Data!=0)
                {
                    if(count($Data)>0)
                    {
                        $reports[$customerno][] = DailyReport($device, $date, $Data);
                        $ids[] = $device->repid;
                    }
                }
                else
                {
                    $Bad = 0;
                }
            }
        }
        else 
        {
            break;
        }
    }
    if($Bad!=0)
    {
        foreach ($reports as $customer_report)
        {
            $customerno = $customer_report[0]['customerno'];
            $path = "sqlite:../../customer/$customerno/reports/dailyreport.sqlite";
            if($Bad!=0)
            {
                try
                {
                    $db = new PDO($path);
                    $db->exec('BEGIN IMMEDIATE TRANSACTION');
                    $datecheck = array();
                    foreach ($customer_report as $report)
                    {
                        if(array_search(strtotime($report['date']), $datecheck)==FALSE)
                        {
                            $datecheck[] = strtotime($report['date']);
                            TRANSACTION($report, $db,1);
                        }
                        else
                        {
                            TRANSACTION($report, $db,0);
                        }
                    }
                    $db->exec('COMMIT TRANSACTION');
                }
                catch (PDOException $e)
                {
                    $Bad = 0;
                }
            }
            else
            {   
                $cvo->message = "Error while Pushing Data into sqlite for report ids - ".implode(',', $ids);
                $cvo->customerno = $customerno;                
                $cqm->InsertQ($cvo); 
                break;
            }
        }
        if($Bad!=0) { $drm->DeleteRepMan($ids); }
    }
    else
    {
        $cvo->message = "Error while fetching data from sqlite for reportids - ".implode(',', $ids);
        $cvo->customerno = 0;
        $cqm->InsertQ($cvo); 
    }
}
?>