<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
require '../../lib/bo/RouteManager.php';
require '../../lib/bo/UserManager.php';


$newdateformat = date('jS F Y');

//$cm = new CustomerManager();
//$customernos = $cm->getcustomernos();
//if(isset($customernos))
//{
//    foreach($customernos as $thiscustomerno)
//    {

        $routemanager = new RouteManager(30);
        $routes = $routemanager->get_all_routes();
        if(isset($routes))
        {
            foreach($routes as $thisroute)
            {

            $message ="";
            $message .= "<html><head></head><body><table border='1'>
            <thead><tr>
            <th id='formheader' colspan='100%'>Route Report For ".ucfirst($thisroute->routename)." For $newdateformat </th>
            </tr></thead>
            <tbody><tr>
            <th>Vehicle No.</th>
            <th></th>";

            $routemanager = new RouteManager(30);
            $checkpoints = $routemanager->getchksforroute($thisroute->routeid);
            $lastelement = end($checkpoints);
            $firstelement = array_shift(array_values($checkpoints));
            if(isset($checkpoints))
            {
                foreach($checkpoints as $checkpoint)
                {
                    $routemanager = new RouteManager(30);
                    $cname = $routemanager->getchknameforroute($checkpoint->checkpointid);
                    $message .="<th id='$checkpoint->checkpointid'>$cname</th>";
                }
            }
            $message .="</tr>";

            $routemanager = new RouteManager(30);
            $vehicles = $routemanager->getvehiclesforroute($thisroute->routeid);
            if(isset($vehicles))
            {
                foreach($vehicles as $vehicle )
                {
                    $routemanager = new RouteManager(30);
                    $vehicleno = $routemanager->getvehiclenoforroute($vehicle->vehicleid);
                    $reportsequences = report_desc_seq($vehicle->vehicleid, 30,$firstelement->checkpointid,$lastelement->checkpointid);
                    $reportsequencesout = report_desc_out_seq($vehicle->vehicleid, 30,$firstelement->checkpointid,$lastelement->checkpointid);
                    $message .= "<tr>";
                    $message .= "<td rowspan='2' id='$vehicle->vehicleid'>$vehicleno</td>";
                    $message .= "<td>In</td>";
                    foreach($checkpoints as $checkpoint )
                    {
                    $breakrow = false;
                    foreach($reportsequences as $reportsequence )
                    {
                        if($checkpoint->checkpointid == $reportsequence->chkiddesc)
                        {
                             if($oldinid != $reportsequence->chkiddesc.'_'.$vehicle->vehicleid){
                            $reports = pullreportin($vehicle->vehicleid, $checkpoint->checkpointid,30);
                            $message .= "<td>".date(speedConstants::DEFAULT_TIME, strtotime($reports->datein))."</td>";
                            $oldinid = $reportsequence->chkiddesc.'_'.$vehicle->vehicleid;
                            $breakrow = true;
                             }
                        }
                    }
                    if($breakrow == false)
                    {
                        $message .= "<td></td>";
                    }
                    }
                    $message .="</tr>";
                    $message .= "<tr>";
                    $message .= "<td>Out</td>";
                    foreach($checkpoints as $checkpoint )
                    {
                        $breakrow = false;
                        foreach($reportsequences as $reportsequence )
                        {
                            if($checkpoint->checkpointid == $reportsequence->chkiddesc)
                            {
                                if($oldid != $reportsequence->chkiddesc.'_'.$vehicle->vehicleid){

                                $report = pullreportout($vehicle->vehicleid, $checkpoint->checkpointid,30);
                                $reportss = pullreportin($vehicle->vehicleid, $checkpoint->checkpointid,30);

                                if(strtotime($reportss->datein)>strtotime($report->dateout))
                                $message .= "<td id='$vehicle->vehicleid'>Not Left</td>";

                                else
                                $message .= "<td id='$vehicle->vehicleid'>".date(speedConstants::DEFAULT_TIME, strtotime($report->dateout))."</td>";
                                $oldid = $reportsequence->chkiddesc.'_'.$vehicle->vehicleid;
                                $breakrow = true;

                                }
                            }
                        }
                        if($breakrow == false)
                        {
                            $reportss = pullreportin($vehicle->vehicleid, $checkpoint->checkpointid,30);
                            $report = pullreportout($vehicle->vehicleid, $checkpoint->checkpointid,30);
                            if(strtotime($reportss->datein)>strtotime($report->dateout))
                            $message .= "<td>Not Left</td>";
                            else
                            $message .= "<td></td>";
                        }
                    }
                    $message .="</tr>";

                }
            }

            $message .="</tbody></table></body></html>";


            $cqm = new CommunicationQueueManager();
            $cvo = new VOCommunicationQueue();

            $cvo->message = $message;

            //Email

            $cvo->subject = "Route Report For ".ucfirst($thisroute->routename)." For $newdateformat";
            $cvo->phone = "";
            $cvo->type = 0;
            $cvo->customerno = 30;
            $cvo->email = 'amtptgj@yahoo.co.in';
            $cqm->InsertQ($cvo);
            $cvo->email = 'prakash.bakshi@dfpcl.com';
            $cqm->InsertQ($cvo);
            $cvo->email = 'kiran.jadhav@dfpcl.com';
            $cqm->InsertQ($cvo);
            $cvo->email = 'zatakia.ankit@gmail.com';
            $cqm->InsertQ($cvo);
            }
        }
//    }
//}
            function pullreportin($vehicleid, $checkpointid, $customerno)
            {
            $ServerDate_IST = date("Y-m-d H:i:s");
            //$ServerDate_IST = date('Y-m-d H:i:s');
            $EarlierDate_IST = date('Y-m-d H:i:s', strtotime($ServerDate_IST) - 24 * 3600);
            $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
            $Query = "select * from V".$vehicleid." WHERE chkid='".$checkpointid."' AND status='0' AND DATETIME(date) BETWEEN '".$EarlierDate_IST."' AND '".$ServerDate_IST."' ORDER BY date DESC LIMIT 1";
            //$CHKMS = array();
            try
            {
                $db = new PDO($path);
                $result = $db->query($Query);
                if(isset($result) && $result != "")
                {
                    foreach ($result as $row)
                    {
                        $checkpoint = new VORoute();
                        //$checkpoint->datein = date(speedConstants::DEFAULT_TIME, strtotime($row["date"]));
                        $checkpoint->datein = $row["date"];
                    }
                }
            }
            catch (PDOException $e)
            {
                $checkpoint = 0;
            }
            return $checkpoint;
            }


            function pullreportout($vehicleid, $checkpointid, $customerno)
            {
            $ServerDate_IST = date("Y-m-d H:i:s");
            //$ServerDate_IST = date('Y-m-d H:i:s');
            $EarlierDate_IST = date('Y-m-d H:i:s', strtotime($ServerDate_IST) - 24 * 3600);
            $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
            $Query = "select * from V".$vehicleid." WHERE chkid='".$checkpointid."' AND status='1' AND DATETIME(date) BETWEEN '".$EarlierDate_IST."' AND '".$ServerDate_IST."' ORDER BY date DESC LIMIT 1";

            try
            {
                $db = new PDO($path);
                $result = $db->query($Query);
                if(isset($result) && $result != "")
                {
                    foreach ($result as $row)
                    {
                        $checkpoint = new VORoute();
                        //$checkpoint->dateout = date(speedConstants::DEFAULT_TIME,strtotime($row["date"]));
                        $checkpoint->dateout = $row["date"];
                    }
                }
            }
            catch (PDOException $e)
            {
                $checkpoint = 0;
            }
            return $checkpoint;
            }

function report_desc_seq($vehicleid, $customerno,$firstelement,$lastelement)
{
            $ServerDate_IST = date("Y-m-d H:i:s");
            //$ServerDate_IST = date('Y-m-d H:i:s');
            $EarlierDate_IST = date('Y-m-d H:i:s', strtotime($ServerDate_IST) - 24 * 3600);
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V".$vehicleid." WHERE status='0' AND DATETIME(date) BETWEEN '".$EarlierDate_IST."' AND '".$ServerDate_IST."' ORDER BY date DESC";
    $reportdesc = array();
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if(isset($result) && $result != "")
        {
            foreach ($result as $row)
            {
                $checkpoint = new VORoute();
                $checkpoint->datedesc = $row["date"];
                $checkpoint->chkiddesc = $row["chkid"];
                $reportdesc[] = $checkpoint;
                if($checkpoint->chkiddesc == $firstelement || $checkpoint->chkiddesc == $lastelement)
                {
                    return $reportdesc;
                }
            }
        }
    }
    catch (PDOException $e)
    {
        $reportdesc = 0;
    }
    return $reportdesc;
}

function report_desc_out_seq($vehicleid, $customerno,$firstelement,$lastelement)
{
            $ServerDate_IST = date("Y-m-d H:i:s");
            //$ServerDate_IST = date('Y-m-d H:i:s');
            $EarlierDate_IST = date('Y-m-d H:i:s', strtotime($ServerDate_IST) - 24 * 3600);
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V".$vehicleid." WHERE status='1' AND DATETIME(date) BETWEEN '".$EarlierDate_IST."' AND '".$ServerDate_IST."' ORDER BY date DESC";
    $reportdescout = array();
    try
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if(isset($result) && $result != "")
        {
            foreach ($result as $row)
            {
                $checkpoint = new VORoute();
                $checkpoint->datedesc = $row["date"];
                $checkpoint->chkiddesc = $row["chkid"];
                $reportdescout[] = $checkpoint;
                if($checkpoint->chkiddesc == $firstelement || $checkpoint->chkiddesc == $lastelement)
                {
                    return $reportdescout;
                }
            }
        }
    }
    catch (PDOException $e)
    {
        $reportdescout = 0;
    }
    return $reportdescout;
}
?>
