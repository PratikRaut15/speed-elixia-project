<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
/**
 * Ak added,
 * For common functions
 * @return type
 */
function get_checkpoints() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpoints();
    $checkpointopt = "";
    if (!empty($checkpoints)) {
        foreach ($checkpoints as $check_end) {
            $checkpointopt .= "<option value='$check_end->checkpointid'>$check_end->cname</option>";
        }
    }
    return $checkpointopt;
}

function getcustombyid($id, $def = 'Air Conditioner', $cust_id = '') {
    $usermanager = new UserManager();
    $customerno = ($cust_id != '') ? $cust_id : $_SESSION['customerno'];
    $custom = $usermanager->get_custom_byid($id, $customerno);
    $customname = ($custom != '' && $custom->customname != '') ? $custom->customname : $def;
    return $customname;
}

/* realtime-data distance calculation */
function getdistance_new($vehicleid, $customerno) {
    $dm = new DeviceManager($customerno);
    $odo_reading = $dm->get_odometer_reading($vehicleid, $customerno);
    $firstodometer = $odo_reading['first_odo'];
    $lastodometer = $odo_reading['cur_odo'];
    if ($lastodometer < $firstodometer) {
        $lastodometer = $odo_reading['max_odo'] + $lastodometer;
    }
    $totaldistance = $lastodometer - $firstodometer;
    if (round($totaldistance) != 0) {
        return round(($totaldistance / 1000), 2);
    }
    return $totaldistance;
}

function calculateRealtimeDisatnce($vehicle, $customerno) {
    $firstodometer = $vehicle->first_odometer;
    $lastodometer = $vehicle->last_odometer;
    if ($lastodometer < $firstodometer) {
        $lastodometer = $vehicle->max_odometer + $lastodometer;
    }
    $totaldistance = $lastodometer - $firstodometer;
    if (round($totaldistance) != 0) {
        return round(($totaldistance / 1000), 2);
    }
    return $totaldistance;
}

/**
 * Convert seconds to hh:mm
 * @param int $seconds
 * @return char
 */
function get_hh_mm($seconds) {
    $hours = floor($seconds / 60 / 60);
    $remaining_seconds = $seconds - ($hours * 60 * 60);
    $minutes = floor($remaining_seconds / 60);
    $f_hours = strlen($hours) == 1 ? "0$hours" : $hours;
    $f_minutes = strlen($minutes) == 1 ? "0$minutes" : $minutes;
    return "$f_hours:$f_minutes";
}

/**
 * Convert seconds to hh:mm:ss
 * @param int $seconds
 * @return char
 */
function get_hh_ss($seconds) {
    $hours = floor($seconds / 60 / 60);
    $minutes = floor(($seconds - ($hours * 60 * 60)) / 60);
    $d_seconds = $seconds - (($hours * 60 * 60) + ($minutes * 60));
    $f_hours = strlen($hours) == 1 ? "0$hours" : $hours;
    $f_minutes = strlen($minutes) == 1 ? "0$minutes" : $minutes;
    $f_seconds = strlen($d_seconds) == 1 ? "0$d_seconds" : $d_seconds;
    return "$f_hours:$f_minutes:$f_seconds";
}

function datediff_cmn($STdate, $EDdate) {
    if (strtotime($STdate) > strtotime($EDdate)) {
        return 0;
    } else {
        return 1;
    }
}

function gendays_cmn($STdate, $EDdate) {
    $TOTALDAYS = Array();
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

function date_SDiff_cmn($dt1, $dt2, $timeZone = 'GMT', $check = false) {
    $startdate = $dt1;
    $tZone = new DateTimeZone($timeZone);
    $dt1 = new DateTime($dt1, $tZone);
    $dt2 = new DateTime($dt2, $tZone);
    $ts1 = $dt1->format('Y-m-d');
    $ts2 = $dt2->format('Y-m-d');
    $diff = abs(strtotime($ts1) - strtotime($ts2));
    $today = date('Y-m-d');
    $diff /= 3600 * 24;
    if ($check == 'm' && $diff != 0) {
        $datediff = datediff_cmn($startdate, $today);
        if ($datediff) {
            $diff = "-$diff";
        }
    }
    return $diff;
}

/**
 * will return in seconds
 * @param date $EndTime
 * @param date $StartTime
 * @return int in seconds
 */
function getduration_cmn($EndTime, $StartTime) {
    $diff = strtotime($EndTime) - strtotime($StartTime);
    return $diff;
}

/* basic validations */
function required_exit($string, $of = '', $text = ' cannot be empty') {
    $string = trim($string);
    $sanitised = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitised = filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);
    if ($sanitised != '') {
        return $sanitised;
    } else {
        exit(failure($of . $text));
    }
}

function failure($text) {
    $result = array('Status' => 'Failure', 'Error' => $text);
    return json_encode($result);
}

function success($text = '') {
    $result = array('Status' => 'Success', 'Msg' => $text);
    return json_encode($result);
}

function success_json($json) {
    $result = array('Status' => 'Success', 'data' => $json);
    return json_encode($result);
}

/**/
/* locations through google-api */
function location_cmn($lat, $long, $usegeolocation, $customerno = null) {
    $address = NULL;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    if (isset($lat) && isset($long)) {
        $GeoCoder_Obj = new GeoCoder($customerno);
        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    }
    return $address;
}

function city_by_google($lat, $long) {
    $city = NULL;
    if ($lat != '0' && $long != '0') {
        $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
        $location = json_decode(file_get_contents("$API&sensor=false"));
        $city = retval_issetor($location->results[0]->address_components[0]->long_name);
    }
    return $city;
}

function ret_issetor(&$var) {
    return isset($var) ? true : false;
}

function retval_issetor(&$var, $def = false) {
    return isset($var) ? $var : $def;
}

function ri(&$var, $def = false) {
    return isset($var) ? trim($var) : $def;
}

function echo_issetor(&$var, $default = false) {
    echo isset($var) ? $var : $default;
}

function exit_issetor(&$var, $default = false) {
    if (isset($var)) {
        return $var;
    } else {
        exit($default);
    }
}

function check_readonly(&$data) {
    if (ret_issetor($data) && $data != '') {
        echo "readonly";
    }
}

function comman_GetOdometerMax($date, $unitno, $customerno) {
    $date = substr($date, 0, 11);
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT max(odometer) as odometerm from vehiclehistory";
        $result = $db->query($query);
        foreach ($result as $row) {
            $ODOMETER = $row['odometerm'];
        }
    }
    return $ODOMETER;
}

function comman_ac_status($acinvert, $digitalio) {
    if ($acinvert == 1) {
        if ($digitalio != 0) {
            return true;
        }
    } else {
        if ($digitalio == 0) {
            return true;
        }
    }
    return false;
}

/**
 * open if true, close if false
 */
function door_status($doorinvert, $digitalio) {
    $return = false;
    if ($doorinvert == 1) {
        if ($digitalio == 0) {
            $return = false; //door close
        } else {
            $return = true; //door open
        }
    } else {
        if ($digitalio == 0) {
            $return = true; //door open
        } else {
            $return = false; //door close
        }
    }
    return $return;
}

function pdf_header($title, $subTitle, $customer_details = null, $middlecolumn = NULL) {
    $header = '<div style="width:auto; height:30px;">
    <table style="width: auto; border:none;">
        <tr>
            <td style="width:230px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
            <td style="width:620px; border:none;text-align:center;"><h3 style="text-transform:uppercase;">' . $title . '</h3><br /> </td>
            <td style="width:230px;border:none;"><img src="../../images/elixia_logo_75.png"  /></td>
        </tr>
    </table>
</div><hr />
<style type="text/css">
    table, td { border: solid 1px   }
    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>';
    $header .= pdf_filter_details($title, $subTitle, $customer_details, $middlecolumn);
    return $header;
}

function pdf_filter_details($title, $subTitle, $customer_details = null, $middlecolumn = NULL) {
    $currentdate = date(speedConstants::DEFAULT_DATETIME);
    $text_format = implode('<br/>', $subTitle);
    $company_name = '';
    $generated_by = '';
    if (isset($_SESSION['customerno'])) {
        $company_name = "Company Name: " . $_SESSION['customercompany'] . "<br/>Customer No: " . $_SESSION['customerno'] . '<br/>';
        $generated_by = "Generated By: {$_SESSION['username']}<br/>";
    } elseif ($customer_details != null) {
        $company_name = "Company Name: $customer_details->customercompany<br/>Customer No: $customer_details->customerno<br/>";
        if (isset($_SESSION['report_gen_user'])) {
            $generated_by = "Generated By: {$_SESSION['report_gen_user']}<br/>";
        }
    }
    $middlecolumn = $middlecolumn == NULL ? '' : $middlecolumn;
    $finalreport = '
    <table align="center" style="background-color:#CCCCCC;width: auto;font-weight:bold;font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
        <tbody>
            <tr><td colspan="3" style="width:600px;height:auto;">' . $title . '</td></tr>
            <tr>
                <td style="text-align:left;border-right:none;">' . $text_format . '<br/>' . $company_name . '<br/></td>
                ' . $middlecolumn . '
                <td>' . $generated_by . 'Generated on: ' . $currentdate . '</td>
            </tr>
        </tbody></table><br/>';
    return $finalreport;
}

function excel_header($title, $subTitle, $customer_details = null, $middlecolumn = NULL) {
    $middlecolumn = $middlecolumn == NULL ? '' : $middlecolumn;
    $currentdate = date(speedConstants::DEFAULT_DATETIME);
    $text_format = implode('<br/>', $subTitle);
    $company_name = '';
    $generated_by = '';
    if (isset($_SESSION['customerno'])) {
        $company_name = "Company Name: " . $_SESSION['customercompany'] . "<br/>Customer No: " . $_SESSION['customerno'] . '<br/>';
        $generated_by = "Generated By: {$_SESSION['username']}<br/>";
    } elseif ($customer_details != null) {
        $company_name = "Company Name: $customer_details->customercompany<br/>Customer No: $customer_details->customerno<br/>";
        if (isset($_SESSION['report_gen_user'])) {
            $generated_by = "Generated By: {$_SESSION['report_gen_user']}<br/>";
        }
    }
    $header = '
        <div style="width:1120px;">
            <table style="width: 1120px;  border:1px solid;background-color:#CCCCCC;border-collapse:collapse;">
                <tr><td colspan="4" style="width:1120px; text-align: center; text-transform:uppercase;"><h4 style="text-transform:uppercase;">' . $title . '</h4></td></tr>
                <tr>
                    <td colspan="2" style="text-align:left;font-weight:bold;">' . $text_format . '<br/>' . $company_name . '<br/></td>
                    ' . $middlecolumn . '
                    <td colspan="2" style="text-align:right;font-weight:bold;">' . $generated_by . 'Generated On: ' . $currentdate . '</td>
                </tr>
            </table>
        </div><hr/>
        <style type="text/css">
            table, td { border: solid 1px  #999999; color:#000000; }
            hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
        ';
    return $header;
}

function excel_header_pickup($title, $subTitle, $customer_details = null) {
    $currentdate = date(speedConstants::DEFAULT_DATETIME);
    $text_format = implode('<br/>', $subTitle);
    $company_name = '';
    $generated_by = '';
    if (isset($_SESSION['customerno'])) {
        $company_name = "Company Name: " . $_SESSION['customercompany'] . "<br/>Customer No: " . $_SESSION['customerno'] . '<br/>';
        $generated_by = "Generated By: {$_SESSION['username']}<br/>";
    } elseif ($customer_details != null) {
        $company_name = "Company Name: $customer_details->customercompany<br/>Customer No: $customer_details->customerno<br/>";
        if (isset($_SESSION['report_gen_user'])) {
            $generated_by = "Generated By: {$_SESSION['report_gen_user']}<br/>";
        }
    }
    $header = '
        <div style="width:1120px;">
            <table style="width: 1120px;  border:1px solid;background-color:#CCCCCC;border-collapse:collapse;">
                <tr><td colspan="4" style="width:1120px; text-align: center; text-transform:uppercase;"><h4 style="text-transform:uppercase;">' . $title . '</h4></td></tr>
                <tr><td colspan="2" style="text-align:left;font-weight:bold;">' . $company_name . '<br/></td>
                    <td colspan="2" style="text-align:right;font-weight:bold;">' . $generated_by . 'Generated On: ' . $currentdate . '</td>
                </tr>
            </table>
        </div><hr/>
        <style type="text/css">
            table, td { border: solid 1px  #999999; color:#000000; }
            hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
        ';
    return $header;
}

function table_header($title, $subTitle, $columns = null, $fluid = false, $middlecolumn = NULL,$tabletype=NULL){
    $ext = '';
    if ($fluid) {
        $ext = '-fluid';
    }
    if($tabletype == 'dashboardDetails'){
        $style = 'style="overflow-y:scroll;height:400px;"';
    }
    else{
        $style= '';
    }

    $currentdate = date(speedConstants::DEFAULT_DATETIME);
    $text_format = implode('<br/>', $subTitle);
    $groupname='';
    if(isset($_SESSION['groupname'])&& !empty($_SESSION['groupname'])){
        $groupname = "<br/>Group Name : ".$_SESSION['groupname'];
    }

    $company_name = "Company Name: " . $_SESSION['customercompany'] . "<br/>Customer No: " . $_SESSION['customerno'];
    $styleset1 = $styleset2 = $styleset3 = $subheaderright3 = $subheaderleft = '';
    if (isset($middlecolumn) && !empty($middlecolumn)) {
        $styleset1 = 'style="width:37%;"';
        $styleset3 = 'style="width:29%;"';
        $subheaderright3 = 'style="width:90%"';
        $subheaderleft = 'style="width:70%"';
    } else {
        $middlecolumn = '';
    }
    $header = '
        <div class="container" style="width:965px;">
            <table class="table newTable" >
                <thead>
                    <tr><th colspan="100%" style="font-weight:bold;font-size:14px;">' . $title . '</th></tr>
                    <tr>
                        <th colspan="100%">
                            <div class="newTableSubHeader" ' . $styleset1 . ' ><div class="newTableSubHeaderLeft" ' . $subheaderleft . '>' . $text_format .'&nbsp;'.$groupname.'<br/>' . $company_name . '<br/></div></div>
                            ' . $middlecolumn . '
                            <div class="newTableSubHeader" ' . $styleset3 . ' ><div class="newTableSubHeaderRight" ' . $subheaderright3 . '>Generated By: ' . $_SESSION['username'] . '<br/>Generated On: ' . $currentdate . '</div>
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="clearfix"></div>';
    if ($columns != null) {
        if (count($columns) > 24) {
            echo "<style>.newTable th, .newTable td{padding:4px;}</style>";
        }
        $header .= '<div class="container' . $ext . '" '.$style.'>
        <table class="table newTable" >
            <thead>
                <tr>';
        foreach ($columns as $s_columns) {
            $header .= "<th>$s_columns</th>";
        }
        $header .= '</tr>
                </thead>
                <tbody>
                    ';
    }

    return $header;
}

/* Vehicles manipulation functions */
function get_user_groups($customerno, $userid, $form = 'array') {
    if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
        $groups = array($_SESSION['groupid']);
    } else {
        $umanager = new UserManager();
        $groups = $umanager->get_user_groups_arr($customerno, $userid);
        if (empty($groups)) {
            $groups = array(0);
        }
    }
    if ($form == 'csv') {
        $groups = implode(',', $groups);
    }
    if ($groups == 0) {
        $groups = null;
    }
    return $groups;
}

function vehicles_array($veh_data) {
    $vehicles = array();
    if ($veh_data != null) {
        foreach ($veh_data as $single) {
            $vehicles[$single->vehicleid]['vehno'] = $single->vehicleno;
            if (isset($single->delboyname)) {
                $vehicles[$single->vehicleid]['delboyname'] = $single->delboyname;
            }
            if (isset($single->seatcapacity)) {
                $vehicles[$single->vehicleid]['seatcapacity'] = $single->seatcapacity;
            }

        }
    }
    return $vehicles;
}

function pickup_array($veh_data) {
    $vehicles = array();
    if ($veh_data != null) {
        foreach ($veh_data as $single) {
            $vehicles[$single[userid]]['username'] = $single[username];
        }
    }
    return $vehicles;
}

function vehicle_id_array($veh_data, $form = 'array') {
    $vehicles = array();
    if ($veh_data != null) {
        foreach ($veh_data as $single) {
            $vehicles[] = $single->vehicleid;
        }
    }
    if ($form == 'csv') {
        $vehicles = implode(', ', $vehicles);
    }
    return $vehicles;
}

function groupBased_vehicles($customerno) {
    $vehiclemanager = new vehiclemanager($customerno);
    if (isset($_SESSION['ecodeid'])) {
        $vehiclesbygroup = $vehiclemanager->get_groups_vehicles_ecode();
    } elseif ($_SESSION['groupid'] != 0) {
        $vehiclesbygroup = $vehiclemanager->get_groups_vehicles($_SESSION['groupid']);
    } elseif ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
        $vehiclesbygroup = $vehiclemanager->get_all_vehiclesbyheirarchy();
    } else {
        $vehiclesbygroup = $vehiclemanager->get_all_vehicles();
    }
    return $vehiclesbygroup;
}

function groupBased_vehicles_cron($customerno, $userid, $groupid = null) { 
    $umanager = new UserManager();
    $vm = new VehicleManager($customerno);
    //$groupid = null;
    if(isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) == 'consignee')
    {
        $vehicles = $vm->getvehiclesforrtdwithpagination();
        return $vehicles;
    }
    else
    {
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != '0') {
                $groupid = $_SESSION['groupid'];
            }
            if ($groupid == null) {
                $groups = $umanager->get_groups_fromuser($customerno, $userid);
            } else {
                $temp_class = new stdClass;
                $temp_class->groupid = $groupid;
                $groups = array($temp_class);
            }
            $vehicles = array();
            if (isset($groups)) {
                $group_in = array();
                foreach ($groups as $group) {
                    $group_in[] = $group->groupid;
                }
                if ($groups == null) {
                    $groupid = 0;
                    $group_veh = $vm->get_all_vehicles_by_group($groupid);
                } else {
                    $group_veh = $vm->get_all_vehicles_by_group($group_in);
                }
                if ($group_veh != null) {
                    $vehicles = array_merge($vehicles, $group_veh);
                }
            } elseif ($groups == null) {
                $groupid = 0;
                $vehicles = $vm->get_all_vehicles_by_group(array($groupid));
            }
            return $vehicles;
            /*
        * else if (isset($groups)) {
        foreach ($groups as $thisgroup) {
        $group_veh = $vm->get_all_vehicles_by_group(array($thisgroup->groupid));
        if ($group_veh != null) {
        $vehicles = array_merge($vehicles, $group_veh);
        }
        }
        }
        */
    }

}

function groupBased_warehosue_cron($customerno, $userid) {
    $umanager = new UserManager();
    $vm = new VehicleManager($customerno);
    $groupid = null;
    if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != '0') {
        $groupid = $_SESSION['groupid'];
    }
    if ($groupid == null) {
        $groups = $umanager->get_groups_fromuser($customerno, $userid);
    } else {
        $temp_class = new stdClass;
        $temp_class->groupid = $groupid;
        $groups = array($temp_class);
    }
    $vehicles = array();
    if (isset($groups)) {
        foreach ($groups as $thisgroup) {
            $group_veh = $vm->get_all_warehouse_by_group(array($thisgroup->groupid));
            if ($group_veh != null) {
                $vehicles = array_merge($vehicles, $group_veh);
            }
        }
    } elseif ($groups == null) {
        $groupid = 0;
        $vehicles = $vm->get_all_warehouse_by_group(array($groupid));
    }
    return $vehicles;
}

/*Common Header*/
function report_header($type, $title, $subTitle, $columns, $customer_details, $fluid = false, $middlecolumn = NULL) {
    $ext = '';
    $finalreport = '';
    if ($fluid) {
        $ext = '-fluid';
    }
    if ($type != 'HTML') {
        $finalreport .= pdf_logo($title);
    }
    $finalreport .= table_headings($type, $title, $subTitle, $customer_details, $middlecolumn = NULL);
    if ($columns != null) {
        if (count($columns) > 24) {
            echo "<style>.newTable th, .newTable td{padding:4px;}</style>";
        }
        $finalreport .= '<div class="container' . $ext . '"><table class="table newTable" align="center" ><thead><tr>';
        foreach ($columns as $s_columns) {
            if ($type == 'HTML') {
                $finalreport .= "<th>$s_columns</th>";
            } else {
                $finalreport .= "<td><b>$s_columns</b></td>";
            }
        }
        $finalreport .= '</tr></thead><tbody>';
    }
    return $finalreport;
}

function pdf_logo($title) {
    $logo = '<div style="width:auto; height:30px;">
    <table style="width: auto; border:none;">
        <tr>
            <td style="width:430px; border:none;"><img style="width:20%; height: 100%;" src="../../images/elixiaspeed_logo.png" /></td>
            <td style="width:420px; border:none;"><h3 style="text-transform:uppercase;">' . $title . '</h3><br /> </td>
            <td style="width:230px;border:none;"><img src="../../images/elixia_logo_75.png"  /></td>
        </tr>
    </table>
</div><hr />
<style type="text/css">
    table, td { border: solid 1px   }
    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>';
    return $logo;
}

function table_headings($type, $title, $subTitle, $customer_details, $middlecolumn = NULL) {
    $headings = '';
    $currentdate = date(speedConstants::DEFAULT_DATETIME);
    $text_format = implode('<br/>', $subTitle);
    $company_name = "Company Name: " . $customer_details->customercompany . "<br/>";
    $customerno = "Customer No: " . $customer_details->customerno;
    $styleset1 = $styleset2 = $styleset3 = $subheaderright3 = $subheaderleft = '';
    if (isset($middlecolumn) && !empty($middlecolumn)) {
        $styleset1 = 'style="width:37%;"';
        $styleset3 = 'style="width:29%;"';
        $subheaderright3 = 'style="width:90%"';
        $subheaderleft = 'style="width:70%"';
    } else {
        $middlecolumn = '';
    }
    if ($type == 'HTML') {
        $headings .= '<div class="container" style="width:965px;"><table class="table newTable" >
        <thead>
            <tr><th colspan="100%" style="font-weight:bold;font-size:14px;">' . $title . '</th></tr>
            <tr>
                <th colspan="100%">
                    <div class="newTableSubHeader" ' . $styleset1 . ' ><div class="newTableSubHeaderLeft" ' . $subheaderleft . '>' . $text_format . '<br/>' . $company_name . $customerno . '<br/></div></div>
                    ' . $middlecolumn . '
                    <div class="newTableSubHeader" ' . $styleset3 . ' ><div class="newTableSubHeaderRight" ' . $subheaderright3 . '>Generated By: ' . $_SESSION['username'] . '<br/>Generated On: ' . $currentdate . '</div>
                </div>
            </th>
        </tr>
    </thead>
</table></div><div class="clearfix"></div>';
    } else {
        if (isset($_SESSION['customerno'])) {
            $generated_by = "Generated By: {$_SESSION['username']}<br/>";
        } elseif ($customer_details != null && isset($_SESSION['report_gen_user'])) {
            $generated_by = "Generated By: {$_SESSION['report_gen_user']}<br/>";
        } else {
            $generated_by = "";
        }
        $headings .= '<table align="center" style="background-color:#CCCCCC;width: auto;font-weight:bold;font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
  <tbody>
    <tr><td colspan="3" style="width:600px;height:auto;">' . $title . '</td></tr>
    <tr>
        <td style="text-align:left;border-right:none;">' . $text_format . '<br/>' . $company_name . $customerno . '<br/></td>
        ' . $middlecolumn . '
        <td>' . $generated_by . 'Generated on: ' . $currentdate . '</td>
    </tr>
</tbody></table><br/>';
    }
    return $headings;
}

function renderReport($type, $content, $filename, $savefile = 0) {
    if (strtoupper($type) == 'PDF') {
        $filename = $filename . '.pdf';
        renderPDF($content, $filename, $savefile);
    } elseif (strtoupper($type) == 'XLS' || strtoupper($type) == 'EXCEL') {
        $filename = $filename . '.xls';
        renderXLS($content, $filename, $savefile);
    } else {
        exit('Please enter proper report format');
    }
}

function renderPDF($content, $filename, $savefile) {
    require_once "../../vendor/autoload.php";
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        if ($savefile) {
            $html2pdf->Output($filename, 'F');
        } else {
            $html2pdf->Output($filename);
        }
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}

function renderXLS($content, $filename, $savefile) {
    if ($savefile) {
        $fp = fopen($filename, "w");
        fwrite($fp, $content);
        fclose($fp);
    } else {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        echo $content;
    }
}

/**/
?>
