<?php
set_time_limit(0);
require "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/CheckpointManager.php';
require '../../lib/bo/UserManager.php';
include_once '../../lib/bo/VehicleManager.php';
//include_once '../../modules/reports/reports_travel_functions.php';
require_once('../../modules/reports/html2pdf.php');
require_once '../../lib/bo/simple_html_dom.php';
include_once '../../lib/bo/GeoCoder.php';

function get_location_detail($lat,$long,$customerno){
    $geo_location="N/A";
    if($lat!="" && $long!=""){
        $geo_obj = new GeoCoder($customerno);
        $geo_location = $geo_obj->get_location_bylatlong($lat,$long);
    }

    return $geo_location;
}

class VODatacap{}
//$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
$serverpath = "/var/www/html/speed";
$cm = new CustomerManager();
$customernos = $cm->getcustomerdetail();
if(isset($customernos))
{
    foreach($customernos as $customer)
    {
        ob_start();
        $DATA = '';
       // date_default_timezone_set("Asia/Calcutta");

        $timezone = $cm->timezone_name_cron('Asia/Kolkata', $customer->customerno);
        date_default_timezone_set(''.$timezone.'');
        $date1 = date("d-m-Y");
        $date = date('d-m-Y',strtotime("-1 day ".$date1));
        $location = "../../customer/$customer->customerno/reports/dailyreport.sqlite";
        $cust_name = $cm->get_customer_company($customer->customerno);
        $cust_pass = strtolower(substr(preg_replace('/[\s\.]/', '', $cust_name), 0,3)).$customer->customerno;
        if(file_exists($location))
        {
            $DATA = GetDailyReport_Data($location,$date);
        }
?>
    <div style="width:auto; height:30px;">
    <table style="width: auto; border:none;">
    <tr>
    <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
    <td style="width:420px; border:none;">

                            <h3 style="text-transform:uppercase;">Summary Report</h3><br />
    </td>
    <td style="width:230px;border:none;">
    <img src="../../images/elixia_logo_75.png"  /></td>
    </tr>
    </table>

    </div>
    <hr />
    <h4>
    <div align="center" style="text-align:center;">
     <?php echo $customer->customercompany; ?></div><div align="right" style="text-align:center;" >
                              <?php echo date ("F j, Y",strtotime($date));?>
    </div>
    </h4>
    <style type="text/css">
    table, td { border: solid 1px  #999999; color:#000000; }
    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
    </style>
<?php
        echo "<table id='search_table_2' align='center' style='width: 1000px; font-size:9px; text-align:center; border:1px solid #000;'>";
        echo "<tbody><tr style='background-color:#CCCCCC;'>
                            <td style='width:50px;height:auto; text-align: center;'>Sr. No</td>
                            <td style='width:70px;height:auto; text-align: center;'>Vehicle No</td>
                            <td style='width:100px;height:auto; text-align: center;'>Group</td>
                            <td style='width:250px;height:auto; text-align: center;'>Start Location</td>
                            <td style='width:250px;height:auto; text-align: center;'>End Location</td>
                            <td style='width:70px;height:auto; text-align: center;'>Distance Travelled [km]</td>
                            <td style='width:70px;height:auto; text-align: center;'>Running Time [hh:mm]</td>
                            <td style='width:70px;height:auto; text-align: center;'>Genset/AC Usage [hh:mm]</td>
                            <td style='width:70px;height:auto; text-align: center;'>Overspeed (times)</td>
                        </tr>";
        $vehiclemanager = new VehicleManager($customer->customerno);
//        $groupid = 0;
//        $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);
//        if(isset($vehicles))
//            {
//                $geocode = '1';
//                foreach($vehicles as $vehicle )
//                {
//                    echo "<tr><td>Vehicle no. $vehicle->vehicleno</td>";
//                $cat=get_summary_report_pdf($thiscustomerno,$vehicle->vehicleid,$vehicle->deviceid,$date,$geocode);
//                    echo '</tr>';
//                }
//            }
        if(isset($DATA))
            {
                $geocode = '1';
                $i=1;
                $data = '';
                foreach($DATA as $vehicle)
                {
                    $vehicles = $vehiclemanager->get_vehicle_details($vehicle->vehicleid);
                    $filelocation = "$serverpath/customer/$customer->customerno/reports/chkreport.sqlite";
//                    $lastcheckpoint = get_last_checkpoint($filelocation,$vehicle->vehicleid,$customer->customerno);
/*                    $devices = $cm->getdevicedata($vehicle->uid);
                    if(isset($devices)){
                        if($devices->tamper == 0){
                            $tamper = 'Normal';
                        }
                        else{
                            $tamper = 'Tampered';
                        }
                        if($devices->powercut == 0){
                            $powercut = 'Powercut';
                        }
                        else{
                            $powercut = 'Normal';
                        }
                    }
 *
 */
                    $groupname = $cm->getgroupname($vehicle->uid);
                    $hours = floor($vehicle->runningtime/60);
                    $minutes = $vehicle->runningtime%60;
                    if ($minutes < 10) {
                        $minutes = '0' . $minutes;
                    }
                    $hourss = floor($vehicle->genset/60);
                    $minutess = $vehicle->genset%60;
                    if ($minutess < 10) {
                        $minutess = '0' . $minutess;
                    }
                    $data .= "<tr><td style='text-align: center;'>$i</td><td style='text-align: center;'>$vehicles->vehicleno</td>";
                    $data .= "<td style='text-align: center;'>$groupname</td>";
                     $location_first = get_location_detail($vehicle->first_dev_lat, $vehicle->first_dev_long,$customer->customerno);
                    $location = get_location_detail($vehicle->dev_lat, $vehicle->dev_long,$customer->customerno);

                    $data .= "<td style='text-align: center;'>$location_first</td>";
                    $data .= "<td style='text-align: center;'>$location</td>";

                    $data .= "<td style='text-align: center;'>".round(($vehicle->totaldistance / 1000) , 1)."</td>";
//                    $data .= "<td style='text-align: center;'>$vehicle->avgspeed</td>";
                    $data .= "<td style='text-align: center;'>$hours:$minutes</td>";
                    $data .= "<td style='text-align: center;'>$hourss:$minutess</td>";
                    $data .= "<td style='text-align: center;'>$vehicle->overspeed</td>";
//                    $data .= "<td style='text-align: center;'>$tamper</td>";
 //                   $data .= "<td style='text-align: center;'>$powercut</td>";
//                    $data .= "<td style='text-align: center;'>$lastcheckpoint->cname ($lastcheckpoint->date)</td>";
                    $data .= "</tr>";
                    $i++;
                }
            }
            echo $data;
        echo '</tbody></table>';
        echo "<hr style='margin-top:5px;'>";
 echo "<div align='right' style='text-align:center;'> Report Generated On: ";
 echo date(speedConstants::DEFAULT_DATETIME);
 echo "</div><hr>";
// echo "<p style='font-size:10px; text-align:center;'>Note: In case the device is tampered or powercut please call us on 022-25137470/71, and schedule an appointment for checking.</p>";
        $content = ob_get_clean();
        try
        {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetProtection(array('print'), $cust_pass);
            $html2pdf->pdf->SetDisplayMode('fullpage');

           $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            //$html2pdf->Output('travelhistory_'.$cat.'.pdf');
            $actualpath = $serverpath."/customer/".$customer->customerno."/reports/pdf/";
            if(!file_exists($actualpath)){
            $filename = '../../customer/'.$customer->customerno.'/';
            if(!file_exists($filename)){
                    mkdir("../../customer/" . $customer->customerno, 0777);
                    $unitnofolder = '../../customer/'.$customer->customerno.'/reports';
                        if(!file_exists($unitnofolder))
                        {
                        mkdir("../../customer/".$customer->customerno."/reports", 0777);
                        $filename1 = '../../customer/'.$customer->customerno.'/reports/pdf';
                        if(!file_exists($filename1))
                            {
                                mkdir("../../customer/".$customer->customerno."/reports/pdf", 0777);
                                $html2pdf->Output($serverpath."/customer/".$customer->customerno."/reports/pdf/".$date."_summaryreport.pdf", 'F');
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
                            }
                        else{
                                $html2pdf->Output($serverpath."/customer/".$customer->customerno."/reports/pdf/".$date."_summaryreport.pdf", 'F');
                            }
                        }
                    }
            else {
                    $unitnofolder = '../../customer/'.$customer->customerno.'/reports';
                    if(!file_exists($unitnofolder))
                    {
                        mkdir("../../customer/".$customer->customerno."/reports", 0777);
                        $filename1 = '../../customer/'.$customer->customerno.'/reports/pdf';
                        if(!file_exists($filename1))
                        {
                                mkdir("../../customer/".$customer->customerno."/reports/pdf", 0777);
                                $html2pdf->Output($serverpath."/customer/".$customer->customerno."/reports/pdf/".$date."_summaryreport.pdf", 'F');
                        }
                        else
                        {
                                $html2pdf->Output($serverpath."/customer/".$customer->customerno."/reports/pdf/".$date."_summaryreport.pdf", 'F');
                        }
                    }
                    else{
                        $filename1 = '../../customer/'.$customer->customerno.'/reports/pdf';
                        if(!file_exists($filename1))
                        {
                                mkdir("../../customer/".$customer->customerno."/reports/pdf", 0777);
                                $html2pdf->Output($serverpath."/customer/".$customer->customerno."/reports/pdf/".$date."_summaryreport.pdf", 'F');
                        }
                        else
                        {
                                $html2pdf->Output($serverpath."/customer/".$customer->customerno."/reports/pdf/".$date."_summaryreport.pdf", 'F');
                        }
                    }
                }
        }
        else if(file_exists($actualpath)){
                    $html2pdf->Output($serverpath."/customer/".$customer->customerno."/reports/pdf/".$date."_summaryreport.pdf", 'F');
        }

        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }

        $finalreport = '';
        $finalreport = '<div style="width:1000px; height:30px;">
                        <table style="width: 1000px;  border:1px solid #000;">
                        <tr>
                        <td colspan="9" style="width:1000px; text-align: center; text-transform:uppercase; border:none;"><h4 style="text-transform:uppercase;">Summary Report</h4></td>
                        </tr>
                        </table>

                        </div>';
        $finalreport .= "<div style='width:1000px; height:30px;'>
                        <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr>
                        <td colspan='3' style='text-align:left;'><b>Date : $date</b></td>
                        <td colspan='3' style='text-align:center;'><b>Company:  $customer->customercompany</b></td>
                        <td colspan='3' style='text-align:center;'><b>Report Generated On : ".date(speedConstants::DEFAULT_DATETIME)."</b></td>
                        </tr>
                        </table>
                        </div>
                        <hr />
                        <style type='text/css'>
                        table, td { border: solid 1px  #999999; color:#000000; }
                        hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                        </style>
                        <table id='search_table_2' style='width: 1000px; font-size:9px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>
                        <tr style='background-color:#CCCCCC;'>
                            <td style='width:50px;height:auto; text-align: center;'>Sr. No</td>
                            <td style='width:70px;height:auto; text-align: center;'>Vehicle No</td>
                            <td style='width:100px;height:auto; text-align: center;'>Group</td>
                            <td style='width:250px;height:auto; text-align: center;'>Start Location</td>
                            <td style='width:250px;height:auto; text-align: center;'>End Location</td>
                            <td style='width:70px;height:auto; text-align: center;'>Distance Travelled [km]</td>
                            <td style='width:70px;height:auto; text-align: center;'>Running Time [hh:mm]</td>
                            <td style='width:70px;height:auto; text-align: center;'>Genset/AC Usage [hh:mm]</td>
                            <td style='width:70px;height:auto; text-align: center;'>Overspeed (times)</td>
                        </tr>";
        $contentcsv = $finalreport;
        $contentcsv .= $data;
        $contentcsv .= "</tbody></table><hr style='margin-top:5px;'>";
//        $contentcsv .= "<p style='font-size:10px; text-align:center;'>Note: In case the device is tampered or powercut please call us on 022-25137470/71, and schedule an appointment for checking.</p><hr>";
        $html = str_get_html($contentcsv);
        echo $html;
            $actualpath2 = $serverpath."/customer/".$customer->customerno."/reports/csv/";
            if(!file_exists($actualpath2)){
            $cfilename = '../../customer/'.$customer->customerno.'/';
            if(!file_exists($cfilename)){
                    mkdir("../../customer/" . $customer->customerno, 0777);
                    $reportfolder = '../../customer/'.$customer->customerno.'/reports';
                        if(!file_exists($reportfolder))
                        {
                        mkdir("../../customer/".$customer->customerno."/reports", 0777);
                        $filename2 = '../../customer/'.$customer->customerno.'/reports/csv';
                        if(!file_exists($filename2))
                            {
                                mkdir("../../customer/".$customer->customerno."/reports/csv", 0777);
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
                            }
                        else{
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
                            }
                        }
                    }
            else {
                    $reportfolder = '../../customer/'.$customer->customerno.'/reports';
                    if(!file_exists($reportfolder))
                    {
                        mkdir("../../customer/".$customer->customerno."/reports", 0777);
                        $filename2 = '../../customer/'.$customer->customerno.'/reports/csv';
                        if(!file_exists($filename2))
                        {
                                mkdir("../../customer/".$customer->customerno."/reports/csv", 0777);
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
                        }
                        else
                        {
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
                        }
                    }
                    else{
                        $filename2 = '../../customer/'.$customer->customerno.'/reports/csv';
                        if(!file_exists($filename2))
                        {
                                mkdir("../../customer/".$customer->customerno."/reports/csv", 0777);
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
                        }
                        else
                        {
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
                        }
                    }
                }
        }
        else if(file_exists($actualpath2)){
                                $directory = $serverpath."/customer/".$customer->customerno."/reports/csv/".$date."_summaryreport.xls";
                                $fp = fopen($directory, "w");
                                fwrite($fp, $html);

                                fclose($fp);
        }
    }
}

function GetDailyReport_Data($location,$days)
{
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
                $sqlday = date("dmy",strtotime($days));
                $query = "SELECT uid,avgspeed,genset,overspeed,totaldistance,fenceconflict,idletime,runningtime,vehicleid,dev_lat,dev_long,first_dev_lat,first_dev_long from A$sqlday";
                $result = $db->query($query);
                if(isset($result) && $result!="")
                {
                    foreach ($result as $row)
                    {
                        $Datacap = new VODatacap();
                        $Datacap->date = strtotime($day);
                        $Datacap->uid = $row['uid'];
                        $Datacap->avgspeed = $row['avgspeed'];
                        $Datacap->genset = $row['genset'];
                        $Datacap->overspeed = $row['overspeed'];
                        $Datacap->totaldistance = $row['totaldistance'];
                        $Datacap->fenceconflict = $row['fenceconflict'];
                        $Datacap->idletime = $row['idletime'];
                        $Datacap->runningtime = $row['runningtime'];
                        $Datacap->vehicleid = $row['vehicleid'];
                        $Datacap->dev_lat = $row['dev_lat'];
                        $Datacap->dev_long = $row['dev_long'];
                        $Datacap->first_dev_lat = $row['first_dev_lat'];
                        $Datacap->first_dev_long = $row['first_dev_long'];
                        $REPORT[] = $Datacap;
                    }
                }
    return $REPORT;
}

/*function get_last_checkpoint($location,$vehicleid,$customerno)
{
    $path = "sqlite:$location";
    $db = new PDO($path);
                $Query = "select chkid,date from V".$vehicleid." ORDER BY date DESC LIMIT 1";
                $result = $db->query($Query);
                if(isset($result) && $result!="")
                {
                    foreach ($result as $row)
                    {
                        $Datacap = new VODatacap();
                        $Datacap->date = convertDateToFormat($row['date'],speedConstants::DEFAULT_DATETIME);
                        $Datacap->chkid = $row['chkid'];
                        $Datacap->cname = getlatlng_chkpt($row['chkid'],$customerno);
                    }
                }
    return $Datacap;
}

function getlatlng_chkpt($chkid,$customerno)
{
    $checkpointmanager = new CheckpointManager($customerno);
    $cname = $checkpointmanager->get_checkpointname($chkid);
    return $cname;
}
 *
 */
?>
