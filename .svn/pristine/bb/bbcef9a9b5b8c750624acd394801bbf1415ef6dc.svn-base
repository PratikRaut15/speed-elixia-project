<?php

include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once "../../lib/comman_function/reports_func.php";

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

class VOOutput {
    
}

function getvehicles() {
    $renewaldata = Array();
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->getvehicle_renewals();
    return $devices;
}

function getvehicles_renewal_html() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->getvehicle_renewals();
    $today = date("Y-m-d");
    $vehicledata = "";
    $vehicledata.='<style type="text/css">
                table, th { width:80px; }
                table, td { border: solid 1px;   }
                hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                </style>';
    $vehicledata .="<tr style='background-color:#CCCCCC;font-weight:bold;'>
        <th>Vehicle No.</th>
        <th>Group</th>
        <th>Registration <br> Date</th>
        <th>Tax <br>Expiry<br> Date</th>
        <th>Tax<br> Pending<br> Days</th>
        <th>Insurance<br> Expiry<br> Date</th>
        <th>Insurance<br> Pending<br> Days</th>";
    //<th>Permit</th>
    $vehicledata .="<th>Permit<br> Expiry<br> Date</th>
        <th>Permit<br> Pending<br> Days</th>";
    //<th>Fitness</th>
    $vehicledata .="    <th>Fitness <br>Expiry<br> Date</th>
        <th>Fitness<br> Pending<br> Days</th>
        <th>PUC <br>Expiry<br> Date</th>
        <th>PUC<br> Pending<br> Days</th>
</tr>";
        $fromdate1 = date('Y-m-d');
        $todate1 = date('2016-12-31');
        $months = getmonths_range($fromdate1, $todate1);
        $months_count = count($months);
        
    if(isset($devices)){

        foreach ($devices as $row) {
            //reg date diff
            if ($row->valert_reg_expiry != "0000-00-00 00:00:00" && $row->valert_reg_expiry != "") {
                $reg_expiry = date('d-M-Y', strtotime($row->valert_reg_expiry));
            } else {
                $reg_expiry = "N/A";
            }

            //tax date diff
            if ($row->tax_to_date != "0000-00-00" && $row->tax_to_date != "") {
                $taxtodate = date('d-M-Y', strtotime($row->tax_to_date));
                $diff_tax = date_SDiff_cmn($row->tax_to_date, $today, 'GMT', 'm');
            } else {
                $taxtodate = $diff_tax = "N/A";
            }


            // insurance diff
            if ($row->valert_insurance_expiry != "0000-00-00 00:00:00" && $row->valert_insurance_expiry != "") {
                $insurance_expiry = date('d-M-Y', strtotime($row->valert_insurance_expiry));
                $diff_insurance_exp = date_SDiff_cmn($row->valert_insurance_expiry, $today, 'GMT', 'm');
            } else {
                $insurance_expiry = $diff_insurance_exp = "N/A";
            }

            // other1 diff--permit
            if ($row->other1_expiry != "0000-00-00 00:00:00" && $row->other1_expiry != "") {
                $other1_expiry = date('d-M-Y', strtotime($row->other1_expiry));
                $diff_other1_exp = date_SDiff_cmn($row->other1_expiry, $today, 'GMT', 'm');
            } else {
                $other1_expiry = $diff_other1_exp = "N/A";
            }

            // other3 diff--fitness
            if ($row->other3_expiry != "0000-00-00 00:00:00" && $row->other3_expiry != "") {
                $other3_expiry = date('d-M-Y', strtotime($row->other3_expiry));
                $diff_other3_exp = date_SDiff_cmn($row->other3_expiry, $today, 'GMT', 'm');
            } else {
                $other3_expiry = $diff_other3_exp = "N/A";
            }

            // PUC diff
            if ($row->puc_expiry != "0000-00-00 00:00:00" && $row->puc_expiry != "") {
                $puc_expiry = date('d-M-Y', strtotime($row->puc_expiry));
                $diff_puc_exp = date_SDiff_cmn($puc_expiry, $today, 'GMT', 'm');
            } else {
                $puc_expiry = $diff_puc_exp = "N/A";
            }

            $vehicledata .="<tr>"
                    . "<td>" . $row->vehicleno . "</td>"
                    . "<td>" . $row->grname . "</td>"
                    . "<td>" . $reg_expiry . "</td>"
                    . "<td>" . $taxtodate . "</td>"
                    . "<td>" . $diff_tax . "</td>"
                    . "<td>" . $insurance_expiry . "</td>"
                    . "<td>" . $diff_insurance_exp . "</td>"
                    // . "<td>".$row->other_upload1."</td>"    
                    . "<td>" . $other1_expiry . "</td>"
                    . "<td>" . $diff_other1_exp . "</td>"
                    //. "<td>".$row->other_upload3."</td>"    
                    . "<td>" . $other3_expiry . "</td>"
                    . "<td>" . $diff_other3_exp . "</td>"
                    . "<td>" . $puc_expiry . "</td>"
                    . "<td>" . $diff_puc_exp . "</td>"
                    . "</tr>";
        }
    }else{
        $vehicledata .="<tr><td colspan=13>No Vehicles Created</td></tr>";
    }
    $vehicledata.="</tbody>";
    $vehicledata .="</table>";
    return $vehicledata;
}

function getvehicles_renewal_xls() {
    $finalreport = '';
    $finalreport.="<table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>";
    $finalreport .= getvehicles_renewal_html();
    echo $finalreport;
}

function getvehicles_renewal_pdf() {
    $finalreport = '';
    $finalreport .= "
        <table id='search_table_2' align='center' style='width:100%; font-size:11px; text-align:center;border-collapse:collapse; border:1px solid #000;'>    
         <tbody>";
    $finalreport .= getvehicles_renewal_html();
    echo $finalreport;
}

function getmonths_range($STdate,$EDdate){
    $TOTALDAYS = Array();
    
    $STdate = date("Y-m", strtotime($STdate)).'-01';
    $EDdate = date("Y-m", strtotime($EDdate)).'-31';  
    $last_month = null;
    
    while (strtotime($STdate) <= strtotime($EDdate)) 
    {
        $cur_month = date('m',strtotime($STdate));
        if($cur_month!=$last_month){
            $TOTALDAYS[] = $STdate;   //skipped other days
            $last_month = $cur_month;
        }
        
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 days'));
        
    }
    return $TOTALDAYS;
}

?>
