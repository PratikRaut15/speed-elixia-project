<?php
require_once "database.inc.php";
class api {
    static $SMS_TEMPLATE_FOR_QUICK_SHARE = "Vehicle No: {{VEHICLEno}}\r\nLocation: {{LOCATION}}\r\nShared by: {{USERNAME}}";
    //<editor-fold defaultstate="collapsed" desc="Constructor">
    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    function pushTripData($objPushLrDataRequest){
            $arrResult = array();
            $objTripManager = new Trips($objPushLrDataRequest->customerno, $objPushLrDataRequest->userid); 
            $arrConsigneeDetails = $objTripManager->getconsigneeautotdata($objPushLrDataRequest->distributorName);
            $arrConsigneeDetails = $arrConsigneeDetails[0];
           
            if(isset($arrConsigneeDetails) && !empty($arrConsigneeDetails)){
                $arrResult = array();
                $order                      = (object)$objPushLrDataRequest;
                $objOrder                   = new stdClass();
                $objOrder->invoiceNo        = $order->invoiceNo;
                $objOrder->statusdatetime   = $order->todaysDate;
                $objOrder->vehicleno        = $order->truckNo;
                $objOrder->vehicleId        = $order->vehicleId;
                $objOrder->userid           = $order->userid;
                $objOrder->tripstatus       = 3; /*Updated , to display in close trip reports*/
                $objOrder->customerno   = $objPushLrDataRequest->customerno;
                $objOrder->userid       = $objPushLrDataRequest->userid;
                $objOrder->todaysDate   = $objPushLrDataRequest->todaysDate;
                $objOrder->consigneeid  = $arrConsigneeDetails['id'];
                $objOrder->consignee  = $arrConsigneeDetails['value'];

                $arrResult = $objTripManager->addTripDetails((array) $objOrder);
            }else{
                return $arrResult;
            }
            
                   
        return $arrResult;
    }

    // </editor-fold>
    function get_userdetails_by_key($userkey) {
        $SQL = "SELECT customerno,userid,role,roleid,realname FROM user WHERE isdeleted=0 and sha1(userkey)='" . $userkey . "'";
        $Query = sprintf($SQL);
        $result = $this->db->query($Query, __FILE__, __LINE__);
        if ($this->db->num_rows($result) > 0) {
            while ($row = $this->db->fetch_array($result)) {
                $data = array(
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'role' => $row['role'],
                    'roleid' => $row['roleid'],
                    'realname' => $row['realname'],
                );
                return $data;
            }
        }
        return null;
    }

    function checkValidity($customerno) {
        $devices = $this->checkforvalidity($customerno);
        $initday = 0;
        if (isset($devices)) {
            foreach ($devices as $thisdevice) {
                $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                if ($days > 0) {
                    $initday = $days;
                }
            }
        }
        return $initday;
    }

    function checkforvalidity($customerno, $deviceid = null) {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d ";
        if ($deviceid != null) {
            $Query .= " AND deviceid = $deviceid";
        }
        $devicesQuery = sprintf($Query, $customerno);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $device = new stdClass();
            $device->deviceid = $row['deviceid'];
            $device->today = $row["today"];
            $device->expirydate = $row["expirydate"];
            $devices[] = $device;
        }
        return $devices;
    }

    function check_validity_login($expirydate, $currentdate) {
        date_default_timezone_set("Asia/Calcutta");
        $expirytimevalue = '23:59:59';
        $expirydate = date('Y-m-d H:i:s', strtotime("$expirydate $expirytimevalue"));
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    function failure($text) {
        return array('Status' => '0', 'Error' => $text);
    }

    function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }
    
    function validateInvoiceNumber($invoiceNo)
    {
        $SQL = "SELECT triplogno from tripdetails WHERE customerno=795 AND triplogno=".$invoiceNo;
        $Query = sprintf($SQL);
        $result = $this->db->query($Query, __FILE__, __LINE__);
        if ($this->db->num_rows($result) > 0) {
            return false;
        }
        return true; 
    }
    // </editor-fold>
}
?>
