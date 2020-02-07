<?php
if (!isset($Mpath)) {
    $Mpath = '';
}
include_once "../../deliveryapi/class/config.inc.php";
include_once "../../config.inc.php";
include_once $Mpath . "../../config.inc.php";
require_once $Mpath . '../../lib/system/Log.php';
require_once $Mpath . '../../lib/system/Sanitise.php';
require_once $Mpath . '../../lib/system/DatabasePickupManager.php';
require_once $Mpath . '../../lib/bo/CustomerManager.php';
require_once $Mpath . '../../lib/bo/MappingManager.php';
require_once $Mpath . '../../lib/bo/DeliveryManager.php';
require_once $Mpath . 'class/PickupManager.php';
require_once $Mpath . '../../lib/comman_function/reports_func.php';
require_once $Mpath . '../../lib/system/utilities.php';
if (!isset($_SESSION)) {
    session_start();
}
$max_orders = 16;
define("MAX_ORDERS", $max_orders);
$distUrl = "http://www.speed.elixiatech.com/location.php";
function addcustomer($name, $phoneno, $email, $address) {
    $name = GetSafeValueString($name, "string");
    $phoneno = GetSafeValueString($phoneno, "string");
    $email = GetSafeValueString($email, "string");
    $address = GetSafeValueString($address, "string");
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->add_cusotmer($name, $phoneno, $email, $address);
    return $responce;
}

function editcustomer($customerid, $name, $phoneno, $email, $address) {
    $customerid = GetSafeValueString($customerid, "string");
    $name = GetSafeValueString($name, "string");
    $phoneno = GetSafeValueString($phoneno, "string");
    $email = GetSafeValueString($email, "string");
    $address = GetSafeValueString($address, "string");
    $user = new stdClass();
    $user->customerid = $customerid;
    $user->name = $name;
    $user->phone = $phoneno;
    $user->email = $email;
    $user->address = $address;
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->edit_customer($user);
    return $responce;
}

function editorder($pickupboyid, $oid, $status) {
    $pickupboyid = GetSafeValueString($pickupboyid, "string");
    $oid = GetSafeValueString($oid, "string");
    $status = GetSafeValueString($status, "string");
    $user = new stdClass();
    $user->pickupboyid = $pickupboyid;
    $user->oid = $oid;
    $user->status = $status;
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->edit_order($user);
    return $responce;
}

function editshiper($shiperid, $name, $phoneno, $email) {
    $customerid = GetSafeValueString($shiperid, "string");
    $name = GetSafeValueString($name, "string");
    $phoneno = GetSafeValueString($phoneno, "string");
    $email = GetSafeValueString($email, "string");
    $user = new stdClass();
    $user->sid = $shiperid;
    $user->sname = $name;
    $user->phone = $phoneno;
    $user->email = $email;
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->edit_shiper($user);
    return $responce;
}

function addshiper($name, $phoneno, $email) {
    $name = GetSafeValueString($name, "string");
    $phoneno = GetSafeValueString($phoneno, "string");
    $email = GetSafeValueString($email, "string");
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->add_shiper($name, $phoneno, $email);
    return $responce;
}

function getcustomers() {
    $usermanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $users = $usermanager->getcustomers();
    return $users;
}

function getshipers() {
    $usermanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $users = $usermanager->getshipers();
    return $users;
}

function deluser($uid) {
    $usermanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $userid = GetSafeValueString($uid, 'string');
    $usermanager->DeleteCustomer($userid, $_SESSION['customerno']);
    //delete_exception_by_userid($uid, $_SESSION['userid']);
}

function delshiper($uid) {
    $usermanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $userid = GetSafeValueString($uid, 'string');
    $usermanager->DeleteShiper($userid, $_SESSION['customerno']);
    //delete_exception_by_userid($uid, $_SESSION['userid']);
}

function getshiper($shiperid) {
    $customer = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $user = $customer->getShiper($shiperid);
    return $user;
}

function getpickupboy($userid) {
    $customer = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $user = $customer->getPickupboy($userid);
    return $user;
}

function getpinno($userid) {
    $customer = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $user = $customer->getPinno($userid);
    return $user;
}

function addvendor($vendor, $maping) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->add_vendor($vendor, $maping);
    return $responce;
}

function addpickup($vendor, $maping) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->add_pickup($vendor, $maping);
    return $responce;
}

function editpickup($vendor, $maping) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->edit_pickup($vendor, $maping);
    return $responce;
}

function getpickup() {
    $pkpmanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $pkp = $pkpmanager->getpickup();
    return $pkp;
}

function getordercount($pid) {
    $pkpmanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $pkp = $pkpmanager->getordercount($pid);
    return $pkp;
}

function getordercount_status($pid, $status) {
    $pkpmanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $pkp = $pkpmanager->getordercount_status($pid, $status);
    return $pkp;
}

function getOrder($oid) {
    $pkpmanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $pkp = $pkpmanager->getorders($oid);
    return $pkp;
}

function getvendors() {
    $usermanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $users = $usermanager->getvendors();
    return $users;
}

function getvendor($vendorid) {
    $customer = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $user = $customer->getVendor($vendorid);
    return $user;
}

function editvendor($vendor, $maping) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->edit_vendor($vendor, $maping);
    return $responce;
}

function delvendor($uid) {
    $usermanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $userid = GetSafeValueString($uid, 'string');
    $usermanager->DeleteVendor($userid, $_SESSION['customerno']);
    //delete_exception_by_userid($uid, $_SESSION['userid']);
}

function getvendor_no($customerid, $vendorid) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->getvendor_no($customerid, $vendorid);
    return $responce;
}

function delpkpboy($pid) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $response = $pickup->DeletePickpBoy($pid);
    return $response;
}

function addtask($taskname) {
    $taskname = GetSafeValueString($taskname, "string");
    $taskmanager = new TaskManager($_SESSION['customerno']);
    $taskmanager->add_task($taskname, $_SESSION['userid']);
}

function edittask($taskname, $taskid) {
    $taskid = GetSafeValueString($taskid, "string");
    $taskname = GetSafeValueString($taskname, "string");
    $taskmanager = new TaskManager($_SESSION['customerno']);
    $taskmanager->edit_task($taskid, $taskname, $_SESSION['userid']);
}

function gettask() {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->get_all_task();
    return $tasks;
}

function deltask($taskid) {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $TaskManager->DeleteTask($taskid, $_SESSION['userid']);
}

function gettasksbyid($id) {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->get_task($id);
    return $tasks;
}

function upload_checkpoint($all_form) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $checkpointmanager = new Pickup($customerno, $userid);
    $skipped = 0;
    $added = 0;
    $notexistsvendor = 0;
    foreach ($all_form as $form) {
        $checkpoint = new stdClass();
        $checkpoint->customerid = GetSafeValueString($form["customerid"], "String");
        if ($form["vendorno"] != 0 && $form["vendorno"] != '') {
            $checkpoint->vendorno = GetSafeValueString($form["vendorno"], "String");
        } else {
            $checkpoint->vendorno = "-1";
        }
        $checkpoint->orderid = GetSafeValueString($form["orderid"], "String");
        $checkpoint->shipperid = GetSafeValueString($form["shipperid"], "String");
        $checkpoint->pickupdate = date_maker_xls($form["pickupdate"]);
        $checkpoint->name = GetSafeValueString($form["name"], "String");
        $checkpoint->address = GetSafeValueString($form["address"], "String");
        $checkpoint->landmark = GetSafeValueString($form["landmark"], "String");
        $checkpoint->pincode = GetSafeValueString($form["pincode"], "String");
        if ($form["fieldmarshal"] == 0 || $form["fieldmarshal"] == '') {
            $checkpoint->pickupboy = getPickup_Boy($form['vendorno']);
        } else {
            $checkpoint->pickupboy = GetSafeValueString($form["fieldmarshal"], "String");
        }
        $checkpoint->customerno = $customerno;
        if ($checkpoint->vendorno != -1 && $checkpoint->customerid != "") {
            $chkvendor = $checkpointmanager->is_vendor_exists($checkpoint->vendorno, $checkpoint->customerid);
            if ($chkvendor == 0) {
                $notexistsvendor++;
            }
        }
        $chk = $checkpointmanager->is_order_exists($checkpoint);
        if ($chk == 1) {
            $skipped++;
        } else {
            $checkpointmanager->SaveCheckpoint($checkpoint, $userid);
            $added++;
        }
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
        'notexistsvendor' => $notexistsvendor
    );
}

function upload_vendors($all_form) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $pm = new Pickup($customerno, $userid);
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $address_check = urlencode(GetSafeValueString($form['address'], "string"));
        $address_pincode = urlencode($form['address'] . ' ' . $form['pincode']);
        $lat = "";
        $long = "";
        $accuracy = 0;
        $key = "";
        $google_api1 = signUrlLocation("http://maps.google.com/maps/api/geocode/json?address=$address_check&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
        $google_api2 = signUrlLocation("http://maps.google.com/maps/api/geocode/json?address=$address_pincode&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $google_api1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        $results1 = curl_exec($ch1);
        $array_data1 = json_decode($results1);
        $partial_match = retval_issetor($array_data1->results[0]->partial_match, null);
        if ($array_data1->status === 'OK' && $partial_match == 0) {
            $location = $array_data1->results[0]->geometry->location;
            $lat = $location->lat;
            $long = $location->lng;
            $accuracy = 1;
        } else {
            //if ($form['pincode'] != "") {
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $google_api2);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            $results2 = curl_exec($ch1);
            $array_data2 = json_decode($results2);
            if ($array_data2->status === 'OK') {
                $location = $array_data2->results[0]->geometry->location;
                $lat = $location->lat;
                $long = $location->lng;
                $accuracy = 2;
            }
            //}
        }
        $vendor = new stdClass();
        $vendor->vendorname = GetSafeValueString($form["vendorname"], "String");
        $vendor->vendorcompany = GetSafeValueString($form["vendorcompany"], "String");
        $vendor->redif = GetSafeValueString($form["rediff"], "String");
        $vendor->paytm = GetSafeValueString($form["paytm"], "String");
        //$vendor->email = GetSafeValueString($form["email"], "String");
        $vendor->phone1 = GetSafeValueString($form["phone1"], "String");
        $vendor->phone2 = GetSafeValueString($form["phone2"], "String");
        $vendor->address = GetSafeValueString($form["address"], "String");
        $vendor->pincode = GetSafeValueString($form["pincode"], "String");
        $vendor->customerno = $customerno;
        $vendor->lat = $lat;
        $vendor->lng = $long;
        $pm->SaveVendors($vendor, $userid);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped
    );
}

function getPickup_Boy($vendorno) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    //$response = $pickup->DeletePickpBoy($pid);
    $response = $pickup->getPickpBoy($vendorno);
    return $response;
}

function get_order_list($customerno, $userid, $filterdata) {
    $pickup = new Pickup($customerno, $userid);
    $datarows = $pickup->getOrdersAll($customerno, $userid, $filterdata);
    $title = 'Pickup List';
    $subTitle = array();
    $vgroupname = "";
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $customer_details = null;
    if (!isset($_SESSION['customerno'])) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
    }
    echo excel_header_pickup($title, $subTitle, $customer_details);
    if (isset($datarows)) {
        $start = 0;
        ?>
    <table  id='search_table_2' style="width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;">
      <tr style="background-color:#CCCCCC;font-weight:bold;">
        <td style='width:20px; text-align: center;'>Sr.No</td>
        <td style='width:50px;height:auto; text-align: center;'>Pickup ID</td>
        <td style='width:50px;height:auto; text-align: center;'>Customer</td>
        <td style='width:300px;height:auto; text-align: center;'>Vendor</td>
        <td style='width:300px;height:auto; text-align: center;'>Fulfillment ID</td>
        <td style='width:100px;height:auto; text-align: center;'>AWB No</td>
        <td style='width:50px;height:auto; text-align: center;'>Shipper</td>
        <td style='width:100px;height:auto; text-align: center;'>Pickup Boy</td>
        <td style='width:50px;height:auto; text-align: center;'>Status</td>
      </tr>
      <?php if (isset($eachdate)) {
            ?>
        <tr style="width:335px;height:auto; text-align: center;background-color:#D8D5D6;">
          <td align="center" colspan="10"><b><?php
if (isset($eachdate)) {
                echo $eachdate;
            }
            ?></b></td>
        </tr>
        <?php
}
        $i = 1;
        foreach ($datarows as $change) {
            ?>
        <tr>
          <td  width='20px' style='text-align: center;' ><?php echo $i; ?></td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->oid)) {
                echo $change->oid;
            }
            ?> </td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->customername)) {
                echo $change->customername;
            }
            ?> </td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->vendorname)) {
                echo $change->vendorname;
            }
            ?> </td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->fulfillmentid)) {
                echo $change->fulfillmentid;
            }
            ?> </td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->awbno)) {
                echo $change->awbno;
            }
            ?> </td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->sname)) {
                echo $change->sname;
            }
            ?> </td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->realname)) {
                echo $change->realname;
            }
            ?> </td>
          <td style='width:50px;height:auto; text-align: center;'> <?php
if (isset($change->status)) {
                echo $change->status;
            }
            ?> </td>
        </tr>
        <?php
$i++;
        }
        echo "</table>";
    }
}

function add_zone($zoneid, $zonename) {
    $dm = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $response = $dm->addPickupZone($zoneid, $zonename);
    return $response;
}

function add_area($zoneid, $areaid, $areaname, $lat, $lng) {
    $dm = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $response = $dm->addPickupArea($zoneid, $areaid, $areaname, $lat, $lng);
    return $response;
}

function add_slot($slotid, $start, $end) {
    $dm = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $response = $dm->addPickupSlot($slotid, $start, $end);
    return $response;
}

function get_vehicles_arr() {
    $customerno = exit_issetor($_SESSION['customerno']);
    $vehiclemanager = new UserManager($customerno);
    $vehiclesbygroup = $vehiclemanager->get_all_pickup($customerno);
    $vehicle_arr = pickup_array($vehiclesbygroup);
    return $vehicle_arr;
}

function get_Mapped_Zone_Slot_pickup() {
    $customerno = exit_issetor($_SESSION['customerno']);
    $mm = new MappingManager($customerno);
    $data = $mm->getVehZoneSlot_js_arr_pickup();
    return $data;
}

function get_zones() {
    $customerno = exit_issetor($_SESSION['customerno']);
    $mm = new MappingManager($customerno);
    $data = $mm->getCustomerZones_pickup();
    return $data;
}

function get_slots() {
    //$dm = new DeliveryManager($_SESSION['customerno']);
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $data = $pickup->get_customer_slots_pickup();
    if (!empty($data)) {
        return $data;
    } else {
        return
        array(
            1 => array('timing' => '7:00 - 11:30')
        );
    }
}

function get_areas() {
    $customerno = exit_issetor($_SESSION['customerno']);
    $mm = new MappingManager($customerno);
    $data = $mm->getCustomerAreas_pickup();
    return $data;
}

function get_google_location($address) {
    $address = urlencode($address);
    $file_to_send = signUrl("http://maps.google.com/maps/api/geocode/json?address=$address&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $data1 = file_get_contents($file_to_send);
    $data2 = json_decode($data1);
    $return = array('lat' => null, 'lng' => null);
    if (isset($data2->results[0]->geometry->location)) {
        $return = (array) $data2->results[0]->geometry->location;
    }
    return $return;
}

function get_startll_byvehSlot($vehid, $slot) {
    $customerno = exit_issetor($_SESSION['customerno']);
    $mm = new MappingManager($customerno);
    $data = $mm->get_startll_pickup($vehid, $slot);
    return $data;
}

function latlong_arr($vehid, $slot, $date) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $data = $pickup->zoneSlotBasedOrders_pickup($vehid, $slot, $date);
    return $data;
}

function getTimeRqrd($start_point, $ltlng) {
    global $distUrl;
    $mn_url = "$distUrl?startLoc=$start_point&endLoc=$ltlng";
    //echo $mn_url;die();
    $data = json_decode(file_get_contents($mn_url));
    if (isset($data->rows[0]->elements[0]->duration)) {
        return $data->rows;
    } else {
        return 'N/A';
    }
}

function encodeBase64UrlSafe($value) {
    return str_replace(array('+', '/'), array('-', '_'), base64_encode($value));
}

function decodeBase64UrlSafe($value) {
    return base64_decode(str_replace(array('-', '_'), array('+', '/'), $value));
}

function signUrl($myUrlToSign, $privateKey) {
    $url = parse_url($myUrlToSign);
    $urlPartToSign = $url['path'] . "?" . $url['query'];
    $decodedKey = decodeBase64UrlSafe($privateKey);
    $signature = hash_hmac("sha1", $urlPartToSign, $decodedKey, true);
    $encodedSignature = encodeBase64UrlSafe($signature);
    return $myUrlToSign; // . "&signature=" . $encodedSignature;
}

function signUrlLocation($myUrlToSign, $privateKey) {
    $url = parse_url($myUrlToSign);
    $urlPartToSign = $url['path'] . "?" . $url['query'];
    $decodedKey = decodeBase64UrlSafe($privateKey);
    $signature = hash_hmac("sha1", $urlPartToSign, $decodedKey, true);
    $encodedSignature = encodeBase64UrlSafe($signature);
    return $myUrlToSign; // . "&signature=" . $encodedSignature;
}

function updateimagename($pickupid, $ext) {
    $pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $responce = $pickup->update_pickupboy_imgname($pickupid, $ext);
    return 'ok';
}

?>