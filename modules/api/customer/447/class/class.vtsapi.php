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

        foreach ($objPushLrDataRequest->chitthiDetails as $order) {
            $order                      = (object)$order;
            $objOrder                   = new stdClass();
            $objOrder->triplogno        = $order->chitthiNo;
            $objOrder->chitthiNo        = $order->chitthiNo;
            $objOrder->statusdatetime   = $order->date;
            $objOrder->chitthiDate      = $order->date;
            $objOrder->vehicleno        = $order->truckNo;
            $objOrder->vehicleid        = 0;

            $objOrder->tripstatus       = 3; /*Updated , to display in close trip reports*/
            $objOrder->statusodometer   = 0;
            $objOrder->loadingdate      = '';
            $objOrder->routename        = '';
            $objOrder->remark           = '';
            $objOrder->perdaykm         = '';
            $objOrder->budgetedkms      = 0;
            $objOrder->budgetedhrs      = 0;
            $objOrder->billingparty     = 0;
            $objOrder->mintemp          = 0;
            $objOrder->maxtemp          = 0;
            $objOrder->drivername       = 0;
            $objOrder->drivermobile1    = 0;
            $objOrder->drivermobile2    = 0;
            $objOrder->etarrivaldate    = 0;
            $objOrder->materialtype     = 0;
            $objOrder->consignor        = 0;
            $objOrder->consignee        = 0;
            $objOrder->consignorid      = 0;
            $objOrder->consigneeid      = 0;

            $objOrder->tokenNo      = $order->tokenNo;
            $objOrder->custCode     = $order->custCode;
            $objOrder->rakeNo       = $order->rakeNo;
            $objOrder->brandCode    = $order->brandCode;
            $objOrder->bags         = $order->bags;
            $objOrder->truckNo      = $order->truckNo;
            $objOrder->delType      = $order->delType;
            $objOrder->qtyType      = $order->qtyType;
            $objOrder->stockyCode   = $order->stockyCode;
            $objOrder->customerno   = $objPushLrDataRequest->customerno;
            $objOrder->userid       = $objPushLrDataRequest->userid;
            $objOrder->todaysDate   = $objPushLrDataRequest->todaysDate;
   
            if ($objOrder->vehicleno != "") {
                /* Get Vehicle Id From Vehicle No */
                $objVehicleManager = new VehicleManager($objPushLrDataRequest->customerno);
                $arrVehicleDetails = $objVehicleManager->get_all_vehicles_byId($objOrder->vehicleno);
                if (isset($arrVehicleDetails) && !empty($arrVehicleDetails)) {
                    $objOrder->vehicleid = $arrVehicleDetails[0]->vehicleid;
                }
                if ($objOrder->vehicleid > 0){
                    $objTripManager         = new Trips($objPushLrDataRequest->customerno, $objPushLrDataRequest->userid);
                    $isVehicleTripExists    = $objTripManager->checkTripVehicleno($objOrder->vehicleno);
                    /*IF CRUD operation is to Update Trip details*/

                    if(isset($order->CRUD_OPERATION_FLAG) && $order->CRUD_OPERATION_FLAG == 'U'){
                        $objOrder->tripid    = $isVehicleTripExists;
                        $objOrder->isdeleted = 0;
                        $objOrder->istripend = 0;
                        $arrResult['status'] = "0";
                    if ($isVehicleTripExists){
                         $objTripManager    = new Trips($objPushLrDataRequest->customerno, $objPushLrDataRequest->userid);
                         $gettripdetails    = $objTripManager->gettripdetails($objOrder->vehicleid ,$objOrder->triplogno);
  
                         $tripstatusid          = $gettripdetails['tripstatusid'];
                         $objOrder->actualhrs   = $gettripdetails['actualhrs'];
                         $objOrder->actualkms   = $gettripdetails['actualkms'];

                          if($tripstatusid == '12'){
                            $isVehicleTripExists = $objTripManager->add_tripdetails((array) $objOrder);
                         }
                        /*If Trip status is Reported  then add new trip */
                          if ($isVehicleTripExists != 0){
                                $objOrder->istripend = 0;
                                $objTripManager->edittripdetails((array) $objOrder);
                            }
                        $objOrder->tripid   = $isVehicleTripExists;
                        $orderId            = $objTripManager->insertTripOrderMapping($objOrder);

                        if($orderId){
                            $arrRes = array();
                            $arrRes['vehicleNo']        = $objOrder->vehicleno;
                            $arrRes['chitthiNo']        = $order->chitthiNo;
                            $arrResult['tripDetails'][] = $arrRes;
                            $arrResult['status']        = "1";
                            if (isset($order->deliveryChallan) && !empty($order->deliveryChallan)){
                                foreach ($order->deliveryChallan as $lr){
                                    $lr                     = (object)$lr;
                                    $objLr                  = new stdClass();
                                    $objLr->orderId         = $orderId;
                                    $objLr->delNo           = $lr->delNo;
                                    $objLr->delDate         = $lr->delDate;
                                    $objLr->chlNo           = $lr->chlNo;
                                    $objLr->chlDate         = $lr->chlDate;
                                    $objLr->diNo            = $lr->diNo;
                                    $objLr->sapNo           = $lr->sapNo;
                                    $objLr->sapDate         = $lr->sapDate;
                                    $objLr->lrNo            = $lr->lrNo;
                                    $objLr->invNo           = $lr->invNo;
                                    $objLr->delFrom         = $lr->delFrom;
                                    $objLr->qtyType         = $lr->qtyType;
                                    $objLr->delType         = $lr->delType;
                                    $objLr->bCoCode         = $lr->bCoCode;

                                    $objLr->delSite         = $lr->delSite;
                                    $objLr->saleType        = $lr->saleType;
                                    $objLr->endoCode        = $lr->endoCode;
                                    $objLr->salesProCode    = $lr->salesProCode;
                                    $objLr->stockyCode      = $lr->stockyCode;
                                    $objLr->clientCode      = $lr->clientCode;
                                    $objLr->siteCode        = $lr->siteCode;
                                    $objLr->frgtRate        = $lr->frgtRate;
                                    $objLr->placeCode       = $lr->placeCode;
                                    $objLr->loading         = $lr->loading;
                                    $objLr->tpvPlacecode    = $lr->tpvPlacecode;
                                    $objLr->rem1            = $lr->rem1;
                                    $objLr->transpBillNo    = $lr->transpBillNo;
                                    $objLr->customerno      = $objPushLrDataRequest->customerno;
                                    $objLr->userid          = $objPushLrDataRequest->userid;
                                    $objLr->todaysDate      = $objPushLrDataRequest->todaysDate;
                                   if(isset($lr->CRUD_OPERATION_FLAG) && $lr->CRUD_OPERATION_FLAG != ''){
                                        if($lr->CRUD_OPERATION_FLAG == 'U' || $lr->CRUD_OPERATION_FLAG == 'C'){
                                              $objLr->isdeleted      = 0;
                                        }
                                        else if($lr->CRUD_OPERATION_FLAG == 'D'){
                                             $objLr->isdeleted      = 1;
                                        }
                                        $objTripManager->insertTripLrMapping($objLr);
                                    }
                                }
                            }
                        }
                    }
                    }                    /*IF CRUD operation is to Delete Trip details*/
                    else if(isset($order->CRUD_OPERATION_FLAG) && $order->CRUD_OPERATION_FLAG == 'D'){
                    $arrResult['status'] = "0";
                    if($isVehicleTripExists) {
                         $objTripManager  = new Trips($objPushLrDataRequest->customerno, $objPushLrDataRequest->userid);
                         $gettripdetails  = $objTripManager->gettripdetails($objOrder->vehicleid ,$objOrder->triplogno);
                         $tripstatusid    = $gettripdetails['tripstatusid'];
                         $objOrder->actualhrs  = $gettripdetails['actualhrs'];
                         $objOrder->actualkms  = $gettripdetails['actualkms'];
                         $objOrder->isdeleted  = 1;
                         $objOrder->tripid     = $isVehicleTripExists;
                         $objOrder->istripend  = 0;

                            if($isVehicleTripExists != 0) {
                            $isVehicleTripExists = $objTripManager->edittripdetails((array) $objOrder);
                        }
                        /*If Trip status is Reported  then add new trip */
                         if($tripstatusid == '12'){
                            $isVehicleTripExists = $objTripManager->add_tripdetails((array) $objOrder);
                        }
                        
                        $orderId            = $objTripManager->insertTripOrderMapping($objOrder);
                        if($orderId){
                        $arrRes = array();
                        $arrRes['vehicleNo']        = $objOrder->vehicleno;
                        $arrRes['chitthiNo']        = $order->chitthiNo;
                        $arrResult['tripDetails'][] = $arrRes;
                        $arrResult['status']        = "1";
                            if (isset($order->deliveryChallan) && !empty($order->deliveryChallan)){
                                foreach ($order->deliveryChallan as $lr) {
                                    $lr                     = (object)$lr;
                                    $objLr                  = new stdClass();
                                    $objLr->orderId         = $orderId;
                                    $objLr->delNo           = $lr->delNo;
                                    $objLr->delDate         = $lr->delDate;
                                    $objLr->chlNo           = $lr->chlNo;
                                    $objLr->chlDate         = $lr->chlDate;
                                    $objLr->diNo            = $lr->diNo;
                                    $objLr->sapNo           = $lr->sapNo;
                                    $objLr->sapDate         = $lr->sapDate;
                                    $objLr->lrNo            = $lr->lrNo;
                                    $objLr->invNo           = $lr->invNo;
                                    $objLr->delFrom         = $lr->delFrom;
                                    $objLr->qtyType         = $lr->qtyType;
                                    $objLr->delType         = $lr->delType;
                                    $objLr->bCoCode         = $lr->bCoCode;

                                    $objLr->delSite         = $lr->delSite;
                                    $objLr->saleType        = $lr->saleType;
                                    $objLr->endoCode        = $lr->endoCode;
                                    $objLr->salesProCode    = $lr->salesProCode;
                                    $objLr->stockyCode      = $lr->stockyCode;
                                    $objLr->clientCode      = $lr->clientCode;
                                    $objLr->siteCode        = $lr->siteCode;
                                    $objLr->frgtRate        = $lr->frgtRate;
                                    $objLr->placeCode       = $lr->placeCode;
                                    $objLr->loading         = $lr->loading;
                                    $objLr->tpvPlacecode    = $lr->tpvPlacecode;
                                    $objLr->rem1            = $lr->rem1;
                                    $objLr->transpBillNo    = $lr->transpBillNo;
                                    $objLr->customerno      = $objPushLrDataRequest->customerno;
                                    $objLr->userid          = $objPushLrDataRequest->userid;
                                    $objLr->todaysDate      = $objPushLrDataRequest->todaysDate;
                                    $objLr->isdeleted      = 1;
                                    $objTripManager->insertTripLrMapping($objLr);
                                }
                            }
                        }
                    }
                    }
                    else if(isset($order->CRUD_OPERATION_FLAG) && isset($order->CRUD_OPERATION_FLAG) =='C'){
                          if ($isVehicleTripExists == 0){
                                $isVehicleTripExists = $objTripManager->add_tripdetails((array) $objOrder);
                        }
                     $arrResult['status'] = "0";
                    if ($isVehicleTripExists){
                         $objTripManager    = new Trips($objPushLrDataRequest->customerno, $objPushLrDataRequest->userid);
                         $gettripdetails    = $objTripManager->gettripdetails($objOrder->vehicleid ,$objOrder->triplogno);
                         $tripstatusid      = $gettripdetails['tripstatusid'];
                        /*If Trip status is Reported  then add new trip */
                         if($tripstatusid == '12'){
                            $isVehicleTripExists = $objTripManager->add_tripdetails((array) $objOrder);
                         }
                        $objOrder->tripid    = $isVehicleTripExists;
                        $objOrder->isdeleted = 0;
                        $orderId = $objTripManager->insertTripOrderMapping($objOrder);
                        if($orderId){
                        $arrRes = array();
                        $arrRes['vehicleNo']        = $objOrder->vehicleno;
                        $arrRes['chitthiNo']        = $order->chitthiNo;
                        $arrResult['tripDetails'][] = $arrRes;
                        $arrResult['status']        = "1";
                            if (isset($order->deliveryChallan) && !empty($order->deliveryChallan)) {
                                foreach ($order->deliveryChallan as $lr) {
                                    $lr                 = (object)$lr;
                                    $objLr              = new stdClass();
                                    $objLr->orderId     = $orderId;
                                    $objLr->delNo       = $lr->delNo;
                                    $objLr->delDate     = $lr->delDate;
                                    $objLr->chlNo       = $lr->chlNo;
                                    $objLr->chlDate     = $lr->chlDate;
                                    $objLr->diNo        = $lr->diNo;
                                    $objLr->sapNo       = $lr->sapNo;
                                    $objLr->sapDate     = $lr->sapDate;
                                    $objLr->lrNo        = $lr->lrNo;
                                    $objLr->invNo       = $lr->invNo;
                                    $objLr->delFrom     = $lr->delFrom;
                                    $objLr->qtyType     = $lr->qtyType;
                                    $objLr->delType     = $lr->delType;
                                    $objLr->bCoCode     = $lr->bCoCode;

                                    $objLr->delSite         = $lr->delSite;
                                    $objLr->saleType        = $lr->saleType;
                                    $objLr->endoCode        = $lr->endoCode;
                                    $objLr->salesProCode    = $lr->salesProCode;
                                    $objLr->stockyCode      = $lr->stockyCode;
                                    $objLr->clientCode      = $lr->clientCode;
                                    $objLr->siteCode        = $lr->siteCode;
                                    $objLr->frgtRate        = $lr->frgtRate;
                                    $objLr->placeCode       = $lr->placeCode;
                                    $objLr->loading         = $lr->loading;
                                    $objLr->tpvPlacecode    = $lr->tpvPlacecode;
                                    $objLr->rem1            = $lr->rem1;
                                    $objLr->transpBillNo    = $lr->transpBillNo;
                                    $objLr->customerno      = $objPushLrDataRequest->customerno;
                                    $objLr->userid          = $objPushLrDataRequest->userid;
                                    $objLr->todaysDate      = $objPushLrDataRequest->todaysDate;
                                    $objLr->isdeleted      = 0;
                                      if(isset($lr->CRUD_OPERATION_FLAG) && $lr->CRUD_OPERATION_FLAG != ''){
                                        if($lr->CRUD_OPERATION_FLAG == 'U' || $lr->CRUD_OPERATION_FLAG == 'C'){
                                              $objLr->isdeleted      = 0;
                                        }
                                        else if($lr->CRUD_OPERATION_FLAG == 'D'){
                                             $objLr->isdeleted      = 1;
                                        }
                                        $objTripManager->insertTripLrMapping($objLr);
                                    }
                                }
                            }
                        }
                    }
                    else if($order->CRUD_OPERATION_FLAG == ''){
                       /* if ($isVehicleTripExists == 0){
                           $isVehicleTripExists = $objTripManager->add_tripdetails((array) $objOrder);
                        }
                    $arrResult['status'] = "0";*/
                    if ($isVehicleTripExists){
                        // $objTripManager    = new Trips($objPushLrDataRequest->customerno, $objPushLrDataRequest->userid);
                       /*  $gettripdetails    = $objTripManager->gettripdetails($objOrder->vehicleid ,$objOrder->triplogno);
                         $tripstatusid      = $gettripdetails['tripstatusid'];*/
                        /*If Trip status is Reported  then add new trip */
                         /*if($tripstatusid == '12'){
                            $isVehicleTripExists = $objTripManager->add_tripdetails((array) $objOrder);
                         }*/
                        /*$objOrder->tripid = $isVehicleTripExists;
                        $objOrder->isdeleted = 0;
                        $orderId = $objTripManager->insertTripOrderMapping($objOrder);*/
                        if($orderId){
                        $arrRes = array();
                        $arrRes['vehicleNo']        = $objOrder->vehicleno;
                        $arrRes['chitthiNo']        = $order->chitthiNo;
                        $arrResult['tripDetails'][] = $arrRes;
                        $arrResult['status']        = "1";
                            if (isset($order->deliveryChallan) && !empty($order->deliveryChallan)){
                                foreach ($order->deliveryChallan as $lr){
                                    $lr                 = (object)$lr;
                                    $objLr              = new stdClass();
                                    $objLr->orderId     = $orderId;
                                    $objLr->delNo       = $lr->delNo;
                                    $objLr->delDate     = $lr->delDate;
                                    $objLr->chlNo       = $lr->chlNo;
                                    $objLr->chlDate     = $lr->chlDate;
                                    $objLr->diNo        = $lr->diNo;
                                    $objLr->sapNo       = $lr->sapNo;
                                    $objLr->sapDate     = $lr->sapDate;
                                    $objLr->lrNo        = $lr->lrNo;
                                    $objLr->invNo       = $lr->invNo;
                                    $objLr->delFrom     = $lr->delFrom;
                                    $objLr->qtyType     = $lr->qtyType;
                                    $objLr->delType     = $lr->delType;
                                    $objLr->bCoCode     = $lr->bCoCode;

                                    $objLr->delSite         = $lr->delSite;
                                    $objLr->saleType        = $lr->saleType;
                                    $objLr->endoCode        = $lr->endoCode;
                                    $objLr->salesProCode    = $lr->salesProCode;
                                    $objLr->stockyCode      = $lr->stockyCode;
                                    $objLr->clientCode      = $lr->clientCode;
                                    $objLr->siteCode        = $lr->siteCode;
                                    $objLr->frgtRate        = $lr->frgtRate;
                                    $objLr->placeCode       = $lr->placeCode;
                                    $objLr->loading         = $lr->loading;
                                    $objLr->tpvPlacecode    = $lr->tpvPlacecode;
                                    $objLr->rem1            = $lr->rem1;
                                    $objLr->transpBillNo    = $lr->transpBillNo;
                                    $objLr->customerno      = $objPushLrDataRequest->customerno;
                                    $objLr->userid          = $objPushLrDataRequest->userid;
                                    $objLr->todaysDate      = $objPushLrDataRequest->todaysDate;
                                    $objLr->isdeleted      = 0;
                                    if(isset($lr->CRUD_OPERATION_FLAG) && $lr->CRUD_OPERATION_FLAG != ''){
                                        if($lr->CRUD_OPERATION_FLAG == 'U' || $lr->CRUD_OPERATION_FLAG == 'C'){
                                            $objLr->isdeleted      = 0;
                                        }
                                        else if($lr->CRUD_OPERATION_FLAG == 'D'){
                                            $objLr->isdeleted      = 1;
                                        }
                                        $objTripManager->insertTripLrMapping($objLr);
                                    }
                                }
                            }
                        }
                    }
                }
                    }
                }
            }
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

    // </editor-fold>
}
?>
