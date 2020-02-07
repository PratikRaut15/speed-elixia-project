<?php

require_once("global.config.php");
require_once("database.inc.php");
date_default_timezone_set('Asia/Kolkata');

class api {

  const PER_SMS_CHARACTERS = 160;

  static $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
  //static $SMS_TEMPLATE_FOR_QUICK_SHARE = "{{USERNAME}} wants you to track the trip at {{URL}}";
  static $SMS_TEMPLATE_FOR_QUICK_SHARE = "Vehicle No: {{VEHICLENO}}\r\nLocation: {{LOCATION}}\r\nShared by: {{USERNAME}}";
  var $status;
  var $status_time;

  //<editor-fold defaultstate="collapsed" desc="Constructor">
  // construct
  function __construct() {
    $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    $this->tmsdb = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, DB_TMS);
    $this->objDate = new DateTime();
  }

  // </editor-fold>
  //
  //<editor-fold defaultstate="collapsed" desc="API functions">
  // checks for login
  function check_login($username, $password) {
    $retarray['status'] = "failure";
    $retarray['version'] = '';
    $retarray['customername'] = null;
    $retarray['userkey'] = 0;
    $userkeyparam = 0;
    $pdo = $this->db->CreatePDOConn();
    $todaysdate = date("Y-m-d H:i:s");
    $sp_params = "'" . $username . "'"
     . ",'" . $password . "'"
     . ",'" . $todaysdate . "'"
     . "," . '@usertype'
     . "," . '@userkeyparam';

    $queryCallSP = "CALL " . SP_AUTHENTICATE_FOR_LOGIN . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $this->db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @usertype AS usertype, @userkeyparam AS userkeyparam";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    $usertype = $outputResult['usertype'];
    $userkeyparam = $outputResult['userkeyparam'];
    if ($userkeyparam != 0) {
      if ($usertype == 0 && $userkeyparam != 0) {
        $devices = $this->checkforvalidity($arrResult['customerno']);
        $initday = 0;
        if (isset($devices)) {
          foreach ($devices as $thisdevice) {
            $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
            if ($days > 0) {
              $initday = $days;
            }
          }
        }
        if ($initday > 0) {
          $retarray['status'] = "successful";
          $retarray['userkey'] = $arrResult['userkey'];
          $this->update_push_android_chk($arrResult['userkey'], 1);
          $this->update_push_android_chk_main($arrResult['userkey'], 1);
          $retarray['customerno'] = $arrResult['customerno'];
          $retarray['username'] = $arrResult['username'];
          $retarray['customername'] = $arrResult['customercompany'];
          $retarray['version'] = $arrResult['version'];
          $retarray['role'] = $arrResult['role'];

          $today = date("Y-m-d H:i:s");
          $sql = "UPDATE user SET lastlogin_android='" . $today . "' where userkey = '" . $arrResult['userkey'] . "' AND customerno= '" . $arrResult['customerno'] . "' LIMIT 1";
          $this->db->query($sql, __FILE__, __LINE__);
        } else {
          $retarray['status'] = "expired";
          $retarray['version'] = '';
          $retarray['customername'] = null;
          $retarray['userkey'] = 0;
        }
      } else if ($usertype == 1 && $userkeyparam != 0) {
        $retarray['status'] = "forgot_password_success";
        $retarray['version'] = '';
        $retarray['customername'] = null;
        $retarray['userkey'] = $userkeyparam;
      }
    }
    echo json_encode($retarray);
    return $retarray;
  }

  function updateLogin($userkey, $phone, $version) {
    $today = date('Y-m-d H:i:s');
    $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted = 0";
    $record = $this->db->query($sql, __FILE__, __LINE__);
    $row = $this->db->fetch_array($record);
    if ($row['userkey'] != "") {
      $userid = $row['userid'];
      $customerno = $row['customerno'];
      $sqlInsert = "insert into login_history(userid, customerno,type,timestamp,phonetype,version)"
       . "values($userid,$customerno,1,'" . $today . "','$phone','$version')";
      $this->db->query($sqlInsert, __FILE__, __LINE__);
    }
  }

  function get_otp_forgotpwd($username) {
    $todaysdate = date('Y-m-d H:i:s');
    $otpparam = '';
    $arr_p = Array();

    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "Please enter registered username.";
    //Prepare parameters
    $sp_params = "'" . $username . "'"
     . ",'" . $todaysdate . "'"
     . "," . "@userexists" . "";
    $sqlCallSP = "CALL " . SP_SPEED_FORGOT_PASSWORD . "($sp_params)";
    $result = $this->db->query($sqlCallSP, __FILE__, __LINE__);
    $this->db->next_result();
    $outputParamQuery = "SELECT @userexists as isUserExists";
    $outParamResult = $this->db->query($outputParamQuery, __FILE__, __LINE__);
    while ($row = $this->db->fetch_array($outParamResult)) {
      $isUserExists = $row['isUserExists'];
    }
    if ($isUserExists) {
      while ($row = $this->db->fetch_array($result)) {
        $userid = $row['useridparam'];
        $otpparam = $row['otpparam'];
        $validuptodate = $row['otpvalidupto'];
        $email = $row['useremail'];
        $phone = $row['userphone'];
        $customerno = $row['custno'];
      }
      if ($otpparam == -1) {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Your otp request limit exceeded today.";
      } else {
        $isSMSSent = 0;
        $isEmailSent = 0;
        $statusMessage = '';
        $message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
        if (!empty($phone)) {
          $smscount = $this->getSMSCount($customerno);
          if ($smscount > 0) {
            $response = '';
            $isSMSSent = $this->sendSMS($phone, $message, $response);
            $moduleid = 1;
            if ($isSMSSent) {
              $this->updateSMSCount($smscount, $message, $customerno);
              $todaysdate = date("Y-m-d H:i:s");
              $vehicleid = 0;
              if (is_array($phone)) {
                foreach ($phone as $phoneno) {
                  $smsLogId = $this->insertSMSLog($phoneno, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid);
                }
              } else {
                $smsLogId = $this->insertSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid);
              }
              $statusMessage = "OTP Number SMS sent successfully. " . (($smsLogId > 0) ? "SMS logged" : " SMS logging failed.");
            } else {
              $statusMessage = "OTP Number SMS sending failed";
            }
          }
        }
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $body = '';
          $body = $message;
          $body .= '<br/>Please login on your ElixiaSpeed Mobile App with your username and mentioned OTP.<br/><br/>';
          $subject = "ElixiaSpeed Forgot Password OTP";
          $arrToMailIds = array($email);
          $strCCMailIds = '';
          $strBCCMailIds = '';
          $attachmentFilePath = '';
          $attachmentFileName = '';
          $isEmailSent = $this->sendMail($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $body, $attachmentFilePath, $attachmentFileName);
          if ($isEmailSent) {
            $emailMessage = "OTP Number Email sent successfully";
            $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
          } else {
            $emailMessage = "OTP Number Email sending failed";
            $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
          }
        }
        $arr_p['status'] = "successful";
        $arr_p['message'] = $statusMessage;
      }
    }
    echo json_encode($arr_p);
    return json_encode($arr_p);
  }

  function update_password($userkey, $newpwd) {
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "update password failed.";

    $todaysdate = date("Y-m-d H:i:s");
    $sp_params = "'" . $newpwd . "'"
     . ",'" . $userkey . "'"
     . ",'" . $todaysdate . "'";

    $queryCallSP = "CALL " . SP_UPDATE_NEWFORGOTPASSWORD . "($sp_params)";
    $result = $this->db->query($queryCallSP, __FILE__, __LINE__);
    $affectedRows = $this->db->get_affectedRows($result);
    if ($affectedRows > 0) {
      $arr_p['status'] = "successful";
      $arr_p['message'] = "update password successful.";
    }
    echo json_encode($arr_p);
    return json_encode($arr_p);
  }

  function get_proposed_indents($userkey, $startdate, $enddate, $isaccepted, $isautorejected, $pageIndex, $pageSize, $searchstring) {
    $arrResult = null;
    $proposed_indents_sku = array();
    $recordCount = 0;
    $validation = $this->check_userkey($userkey);
    $arr_p = array();
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "";
    $json_p = array();
    if ($validation['status'] == 'successful') {
      $customerno = $validation['customerno'];
      $userid = $validation['userid'];
      $checkTransporter = $this->check_transporter($userid, $customerno);
      if ($checkTransporter['status'] == 'successful') {

        $objStartDate = new DateTime($startdate);
        $objEndDate = new DateTime($enddate);

        $startdate = $objStartDate->format(DATEFORMAT_YMD);
        $enddate = $objEndDate->format(DATEFORMAT_YMD);

        $proposed_transporterid = $checkTransporter['transporterid'];
        $pdo = $this->tmsdb->CreatePDOConn();
        //Prepare parameters
        $sp_params = "'" . $customerno . "'"
         . ",''"
         . ",'" . $proposed_transporterid . "'"
         . ",''"
         . ",'" . $startdate . "'"
         . ",'" . $enddate . "'"
         . ",'" . $isaccepted . "'"
         . ",'" . $isautorejected . "'"
         . ",'" . $pageIndex . "'"
         . ",'" . $pageSize . "'"
         . ",'" . $searchstring . "'"
         . ",@recordCount";
        $queryCallSP = "CALL " . SP_GET_TRANSPORTER_PROPOSED_INDENT . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputVars = $pdo->query('SELECT @recordCount')->fetch(PDO::FETCH_ASSOC);
        $recordCount = $outputVars["@recordCount"];
        $this->tmsdb->ClosePDOConn($pdo);

        if (isset($arrResult) && $arrResult != null) {
          foreach ($arrResult as $data) {
            $proposedIndentarr = array();
            $proposedIndentarr['proposedindentid'] = $data['proposedindentid'];
            $proposedIndentarr['fromto'] = $data['factoryname'] . " To " . $data['depotname'];
            $proposedIndentarr['proposedvehiclecode'] = $data['proposedvehiclecode'] . ' - ' . $data['proposedvehicledescription'];
            $proposedIndentarr['daterequired'] = date('d-m-Y', strtotime($data['date_required']));
            $proposedIndentarr['proposed_transporterid'] = 0;
            $proposedIndentarr['pitmappingid'] = 0;
            if ($data['isAccepted'] == 0 && $data['isAutoRejected'] == 0) {
              //$proposedIndentarr['edit'] = "<a href='tms.php?pg=edit-transporterindent-sku&eid=" . $proposedIndent['proposedindentid'] . "&tid=" . $proposedIndent['proposed_transporterid'] . "&pid=" . $proposedIndent['pitmappingid'] . "' >" . constants::editimage . "</a>";
              $proposedIndentarr['proposed_transporterid'] = $data['proposed_transporterid'];
              $proposedIndentarr['pitmappingid'] = $data['pitmappingid'];
            }
            if ($data['isAccepted'] == 1 && $data['isApproved'] != 1) {
              $proposedIndentarr['status'] = 'Indent Confirmed By Transporter';
            } else if ($data['isAccepted'] == -1 && $data['isAutoRejected'] == 0) {
              $proposedIndentarr['status'] = 'Indent Rejected By Transporter';
            } else if ($data['isAccepted'] == -1 && $data['isAutoRejected'] == 1) {
              $proposedIndentarr['status'] = 'Indent Not Confirmed. Auto Rejected By System';
            } else if ($data['isAccepted'] == 0 && $data['isAutoRejected'] == 1) {
              $proposedIndentarr['status'] = 'Indent Expired';
            } else {
              $proposedIndentarr['status'] = 'Awaiting Response';
            }
            if ($data['isApproved'] == 1 && $data['isAccepted'] == 1) {
              $proposedIndentarr['status'] = 'Indent Confirmed By Transporter';
            } else if ($data['isApproved'] == -1) {
              $proposedIndentarr['status'] = 'Indent Rejected By Transporter';
            }

            $proposed_indents_sku[] = $proposedIndentarr;
          }
          $arr_p['status'] = 'successful';
          $arr_p['totalrecords'] = $recordCount;
          $arr_p['result'] = $proposed_indents_sku;
        } else {
          $arr_p['message'] = 'Data not found.';
        }
      } else {
        $arr_p['message'] = 'User is not transporter.';
      }
    } else {
      $arr_p['message'] = 'Invalid User, Plase Check Userkey.';
    }

    echo json_encode($arr_p);
    return json_encode($arr_p);
  }

  function get_proposed_indents_details($userkey, $indentid, $transporterid, $pitmappingid, $pageIndex, $pageSize, $searchstring) {
    $arrResult = null;
    $arrVehicleTypes = null;
    $proposed_indents_sku = array();
    $vehicles = array();
    $validation = $this->check_userkey($userkey);
    $arr_p = array();
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "";
    $json_p = array();
    if ($validation['status'] == 'successful') {
      $customerno = $validation['customerno'];
      $userid = $validation['userid'];
      $checkTransporter = $this->check_transporter($userid, $customerno);
      if ($checkTransporter['status'] == 'successful') {

        $proposed_transporterid = $transporterid;
        $pdo = $this->tmsdb->CreatePDOConn();
        //Prepare parameters
        $sp_params = "'" . $customerno . "'"
         . ",'" . $indentid . "'"
         . ",'" . $proposed_transporterid . "'"
         . ",'" . $pitmappingid . "'"
         . ",''"
         . ",''"
         . ",''"
         . ",''"
         . ",'" . $pageIndex . "'"
         . ",'" . $pageSize . "'"
         . ",'" . $searchstring . "'"
         . ",@recordCount";
        $queryCallSP = "CALL " . SP_GET_TRANSPORTER_PROPOSED_INDENT . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->tmsdb->ClosePDOConn($pdo);
        $skutypeid = 0;
        if (isset($arrResult) && $arrResult != null) {
          foreach ($arrResult as $data) {
            $proposedIndentarr = array();

            $proposedIndentarr['proposedindentid'] = $data['proposedindentid'];
            $proposedIndentarr['fromto'] = $data['factoryname'] . " To " . $data['depotname'];
            $proposedIndentarr['factoryname'] = $data['factoryname'];
            $proposedIndentarr['factoryid'] = $data['factoryid'];
            $proposedIndentarr['depotname'] = $data['depotname'];
            $proposedIndentarr['depotid'] = $data['depotid'];
            $proposedIndentarr['proposedvehiclecode'] = $data['proposedvehiclecode'] . ' - ' . $data['proposedvehicledescription'];
            $proposedIndentarr['proposed_vehicletypeid'] = $data['proposed_vehicletypeid'];
            $proposedIndentarr['actualvehiclecode'] = '';
            if ($data['actualvehiclecode'] != '') {
              $proposedIndentarr['actualvehiclecode'] = $data['actualvehiclecode'] . ' - ' . $data['actualvehicledescription'];
            }
            $proposedIndentarr['daterequired'] = date('d-m-Y', strtotime($data['date_required']));
            $proposedIndentarr['created_on'] = date(speedConstants::DEFAULT_DATETIME, strtotime($data['created_on']));
            $proposedIndentarr['vehicleno'] = $data['vehicleno'];
            $proposedIndentarr['drivermobileno'] = $data['drivermobileno'];
            $proposedIndentarr['proposed_transporterid'] = 0;
            $proposedIndentarr['pitmappingid'] = 0;
            if ($data['isAccepted'] == 0 && $data['isAutoRejected'] == 0) {
              $proposedIndentarr['proposed_transporterid'] = $data['proposed_transporterid'];
              $proposedIndentarr['pitmappingid'] = $data['pitmappingid'];
            }
            $skutypeid = $data['skutypeid'];
            $proposed_indents_sku[] = $proposedIndentarr;
          }
          if ($skutypeid != 0) {
            $pdo = $this->tmsdb->CreatePDOConn();
            $query = "select * from vehicletype where vehicletype.customerno=%d AND skutypeid = %d AND isdeleted = 0";
            $query .=" order by vehicletypeid DESC ";
            $sql = sprintf($query, $customerno, $skutypeid);
            $arrVehicleTypes = $pdo->query($sql)->fetchall(PDO::FETCH_ASSOC);
            $this->tmsdb->ClosePDOConn($pdo);
            if (isset($arrVehicleTypes) && $arrVehicleTypes != null) {
              foreach ($arrVehicleTypes as $vehicledata) {
                $vehicleArray = array();
                $vehicleArray['vehicletypeid'] = $vehicledata['vehicletypeid'];
                $vehicleArray['vehicle'] = $vehicledata['vehicledescription'] . " - " . $vehicledata['vehiclecode'];
                $vehicles[] = $vehicleArray;
              }
            }
          }

          $arr_p['status'] = 'successful';
          $arr_p['result'] = $proposed_indents_sku;
          $arr_p['vehicletypes'] = $vehicles;
        } else {
          $arr_p['message'] = 'Data not found.';
        }
      } else {
        $arr_p['message'] = 'User is not transporter.';
      }
    } else {
      $arr_p['message'] = 'Invalid User, Plase Check Userkey.';
    }

    echo json_encode($arr_p);
    return json_encode($arr_p);
  }

  function modify_proposed_indent($userkey, $details) {
    //print_r($details);
    $arrResult = null;
    $arrVehicleTypes = null;
    $proposed_indents_sku = array();
    $vehicles = array();
    $validation = $this->check_userkey($userkey);
    $arr_p = array();
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "";
    $json_p = array();
    if ($validation['status'] == 'successful') {
      $customerno = $validation['customerno'];
      $userid = $validation['userid'];
      $checkTransporter = $this->check_transporter($userid, $customerno);
      if ($checkTransporter['status'] == 'successful') {

        $objProposedTransporterIndent = new stdClass();
        $objProposedTransporterIndent->customerno = $customerno;
        $objProposedTransporterIndent->proposedindentid = $details->proposed_indentid;
        $objProposedTransporterIndent->vehicleno = urldecode($details->vehicleno);
        $objProposedTransporterIndent->actual_vehicletypeid = isset($details->actual_vehicletypeid) ? $details->actual_vehicletypeid : 0;
        $objProposedTransporterIndent->proposed_vehicletypeid = $details->proposed_vehicletypeid;
        $objProposedTransporterIndent->proposed_transporterid = $details->proposed_transporterid;
        $objProposedTransporterIndent->drivermobileno = urldecode($details->drivermobileno);
        $objProposedTransporterIndent->pitmappingid = $details->pitmappingid;
        $objProposedTransporterIndent->isAccepted = $details->isaccepted;
        $objProposedTransporterIndent->remarks = urldecode($details->remarks);
        $objProposedTransporterIndent->isAutoRejected = 0;
        $objProposedTransporterIndent->todaydate = date('Y-m-d h:i:s');
        $objProposedTransporterIndent->userid = $userid;

        $objProposedIndent = new stdClass();
        $objProposedIndent->customerno = $customerno;
        $objProposedIndent->proposedindentid = $details->proposed_indentid;
        $objProposedIndent->depotid = $details->depotid;
        $objProposedIndent->factoryid = $details->factoryid;
        $objProposedIndent->total_weight = '';
        $objProposedIndent->total_volume = '';
        $objProposedIndent->hasTransporterAccepted = $details->isaccepted;
        $objProposedIndent->isApproved = '';
        if ($details->isaccepted == "1") {
          $objProposedIndent->isApproved = 1;
        }
        $objProposedIndent->todaydate = date('Y-m-d h:i:s');
        $objProposedIndent->userid = $userid;

        $this->update_proposed_indent($objProposedIndent);
        $this->update_pitmapping($objProposedTransporterIndent);

        if ($details->isaccepted == "-1") {
          $rejection = new stdClass();
          $rejection->proposedindentid = $details->proposed_indentid;
          $rejection->transporterid = $details->proposed_transporterid;
          $rejection->vehicletypeid = $details->proposed_vehicletypeid;
          $rejection->depotid = $details->depotid;
          $rejection->factoryid = $details->factoryid;
          $rejection->customerno = $customerno;
          $rejection->todaydate = date('Y-m-d h:i:s');
          $rejection->userid = $userid;

          $this->reject_proposed_indent($rejection);
        } else if ($details->isaccepted == "1") {

          $objProposedIndent = new stdClass();



          $objProposedIndent->customerno = $customerno;
          $objProposedIndent->proposedindentid = $details->proposed_indentid;
          $objProposedIndent->factoryid = $details->factoryid;
          $objProposedIndent->transporterid = $details->proposed_transporterid;
          $objProposedIndent->date_required = '';
          $objProposedIndent->isApproved = 1;
          $objProposedIndent->startdate = '';
          $objProposedIndent->enddate = '';

          $result = $this->get_proposed_indent($objProposedIndent);


          $objSKUMapping = new stdClass();
          $objSKUMapping->customerno = $customerno;
          $objSKUMapping->proposedindentid = $details->proposed_indentid;
          $skuResult = $this->get_proposed_indent_sku_mapping($objSKUMapping);

          $objIndent = new stdClass();

          $objIndent->customerno = $customerno;
          $objIndent->transporterid = $result[0]['transporterid'];
          $objIndent->proposed_vehicletypeid = $result[0]['proposed_vehicletypeid'];
          $objIndent->actual_vehicletypeid = $result[0]['actual_vehicletypeid'];
          $objIndent->vehicleno = $result[0]['vehicleno'];
          $objIndent->proposedindentid = $result[0]['proposedindentid'];
          $objIndent->totalweight = $result[0]['total_weight'];
          $objIndent->totalvolume = $result[0]['total_volume'];
          $objIndent->date_required = $result[0]['date_required'];
          $objIndent->factoryid = $result[0]['factoryid'];
          $objIndent->depotid = $result[0]['depotid'];
          $objIndent->todaysdate = date('Y-m-d h:i:s');
          $objIndent->userid = $userid;

          $objSKU = new stdClass();
          $insertindet = $this->insert_indent($objIndent);

          if ($insertindet != 0) {
            foreach ($skuResult as $sku) {
              $objSKU->indentid = $insertindet;
              $objSKU->skuid = $sku['skuid'];
              $objSKU->no_of_units = $sku['no_of_units'];
              $objSKU->customerno = $customerno;
              $objSKU->todaysdate = date('Y-m-d h:i:s');
              $objSKU->userid = $userid;
              $this->insert_indent_sku_mapping($objSKU);
            }
          }
        }
        $arr_p['status'] = 'successful';
        $arr_p['message'] = '';
      } else {
        $arr_p['message'] = 'User is not transporter.';
      }
    } else {
      $arr_p['message'] = 'Invalid User, Plase Check Userkey.';
    }

    echo json_encode($arr_p);
    return json_encode($arr_p);
  }

  function get_dashboard($userkey) {
    //print_r($details);
    $arrResult = null;
    $validation = $this->check_userkey($userkey);
    $arr_p = array();
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "";
    $indentCount = array();
    if ($validation['status'] == 'successful') {
      $customerno = $validation['customerno'];
      $userid = $validation['userid'];
      $checkTransporter = $this->check_transporter($userid, $customerno);
      if ($checkTransporter['status'] == 'successful') {

        $startdate = $this->objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
        $enddate = $this->objDate->modify('last day of this month')->format(DATEFORMAT_YMD);

        $proposed_transporterid = $checkTransporter['transporterid'];
        $pdo = $this->tmsdb->CreatePDOConn();
        //Prepare parameters
        $sp_params = "'" . $customerno . "'"
         . ",'" . $proposed_transporterid . "'"
         . ",'" . $startdate . "'"
         . ",'" . $enddate . "'";
        $queryCallSP = "CALL " . SP_GET_TRANSPORTER_INDENT_DASHBOARD . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->tmsdb->ClosePDOConn($pdo);
        if (isset($arrResult) && $arrResult != null) {
          foreach ($arrResult as $data) {
            $proposedIndentarr = array();
            $proposedIndentarr['total_indent'] = $data['totalindent'];
            $proposedIndentarr['awating_indent'] = $data['awaitingindent'];
            $proposedIndentarr['expired_indent'] = $data['expiredindent'];
            $proposedIndentarr['rejected_indent'] = $data['rejectedindent'];
            $proposedIndentarr['accepted_indent'] = $data['acceptedindent'];
            $proposedIndentarr['autorjected_indent'] = $data['autorejectedindent'];

            $indentCount[] = $proposedIndentarr;
          }
          $arr_p['status'] = 'successful';
          $arr_p['result'] = $indentCount;
        } else {
          $arr_p['message'] = 'Data not found.';
        }


        $factoryid = '';
        $depotid = '';
        $transporterid = $checkTransporter['transporterid'];
        $zoneid = '';
        $typeid = '';

        $effObj = new stdClass();
        $effObj->customerno = $customerno;
        $effObj->factoryid = $factoryid;
        $effObj->depotid = $depotid;
        $effObj->transporterid = $transporterid;
        $effObj->zoneid = $zoneid;
        $effObj->typeid = $typeid;
        $effObj->startdate = $startdate;
        $effObj->enddate = $enddate;
        //$transportereff = get_transporter_eff($effObj);
        $pdo = $this->tmsdb->CreatePDOConn();
        $sp_params = "'" . $effObj->customerno . "'"
         . ",'" . $effObj->factoryid . "'"
         . ",'" . $effObj->depotid . "'"
         . ",'" . $effObj->transporterid . "'"
         . ",'" . $effObj->zoneid . "'"
         . ",'" . $effObj->typeid . "'"
         . ",'" . $effObj->startdate . "'"
         . ",'" . $effObj->enddate . "'";
        $queryCallSP = "CALL " . SP_GET_TRANSPORTER_EFF . "($sp_params)";
        $transportereff = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->tmsdb->ClosePDOConn($pdo);
        /* Vendor Wise Placement Efficiency */
        $transporterEffArrayTotal = '';
        if (isset($transportereff)) {
          $totalindent = 0;
          $placedindent = 0;
          $effpercent = 0;
          foreach ($transportereff as $transportereffarr) {
            $totalindent += $transportereffarr['totalindent'];
            $placedindent += $transportereffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $transporterEffArrayTotal = $effpercent;
          }
          $arr_p['efficiency'] = $transporterEffArrayTotal;
          $arr_p['transportername'] = $checkTransporter['transportername'];
        }
      } else {
        $arr_p['message'] = 'User is not transporter.';
      }
    } else {
      $arr_p['message'] = 'Invalid User, Plase Check Userkey.';
    }

    echo json_encode($arr_p);
    return json_encode($arr_p);
  }

  public function update_proposed_indent($objProposedIndent) {
    $noOfRowsAffected = 0;
    $pdo = $this->tmsdb->CreatePDOConn();
    //Prepare parameters

    $sp_params = "" . $objProposedIndent->proposedindentid . ""
     . ",'" . $objProposedIndent->factoryid . "'"
     . ",'" . $objProposedIndent->depotid . "'"
     . ",'" . $objProposedIndent->total_weight . "'"
     . ",'" . $objProposedIndent->total_volume . "'"
     . ",'" . $objProposedIndent->isApproved . "'"
     . ",'" . $objProposedIndent->hasTransporterAccepted . "'"
     . ",'" . $objProposedIndent->todaydate . "'"
     . ",'" . $objProposedIndent->userid . "'";

    $queryCallSP = "CALL " . SP_UPDATE_PROPOSED_INDENT . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP);
    $noOfRowsAffected = $arrResult->rowCount();
    $this->tmsdb->ClosePDOConn($pdo);


    return $noOfRowsAffected;
  }

  public function update_pitmapping($objPITMapping) {

    $noOfRowsAffected = 0;
    $pdo = $this->tmsdb->CreatePDOConn();
    $sp_params = "" . $objPITMapping->pitmappingid . ""
     . ",'" . $objPITMapping->actual_vehicletypeid . "'"
     . ",'" . $objPITMapping->vehicleno . "'"
     . ",'" . $objPITMapping->drivermobileno . "'"
     . ",'" . $objPITMapping->isAccepted . "'"
     . ",'" . $objPITMapping->remarks . "'"
     . ",'" . $objPITMapping->isAutoRejected . "'"
     . ",'" . $objPITMapping->todaydate . "'"
     . ",'" . $objPITMapping->userid . "'";

    $queryCallSP = "CALL " . SP_UPDATE_PIT_MAPPING . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP);
    $noOfRowsAffected = $arrResult->rowCount();
    $this->tmsdb->ClosePDOConn($pdo);

    return $noOfRowsAffected;
  }

  public function reject_proposed_indent($objProposedIndent) {
    $noOfRowsAffected = 0;
    $pdo = $this->tmsdb->CreatePDOConn();
    //Prepare parameters
    $sp_params = "" . $objProposedIndent->proposedindentid . ""
     . ",'" . $objProposedIndent->transporterid . "'"
     . ",'" . $objProposedIndent->vehicletypeid . "'"
     . ",'" . $objProposedIndent->factoryid . "'"
     . ",'" . $objProposedIndent->depotid . "'"
     . ",'" . $objProposedIndent->customerno . "'"
     . ",'" . $objProposedIndent->todaydate . "'"
     . "," . $objProposedIndent->userid . "";

    $queryCallSP = "CALL " . SP_GET_REJECT_PROPOSED_INDENT . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP);
    $noOfRowsAffected = $arrResult->rowCount();
    $this->tmsdb->ClosePDOConn($pdo);

    return $noOfRowsAffected;
  }

  public function get_proposed_indent($objProposedIndent) {
    $arrResult = null;
    $pdo = $this->tmsdb->CreatePDOConn();

    //Prepare parameters
    $sp_params = "'" . $objProposedIndent->customerno . "'"
     . ",'" . $objProposedIndent->proposedindentid . "'"
     . ",'" . $objProposedIndent->factoryid . "'"
     . ",'" . $objProposedIndent->transporterid . "'"
     . ",'" . $objProposedIndent->date_required . "'"
     . ",'" . $objProposedIndent->isApproved . "'"
     . ",'" . $objProposedIndent->startdate . "'"
     . ",'" . $objProposedIndent->enddate . "'";
    $queryCallSP = "CALL " . SP_GET_PROPOSED_INDENT . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    $this->tmsdb->ClosePDOConn($pdo);

    return $arrResult;
  }

  public function get_proposed_indent_sku_mapping($objProposedIndent) {
    $arrResult = null;
    $pdo = $this->tmsdb->CreatePDOConn();
    //Prepare parameters
    $sp_params = "'" . $objProposedIndent->customerno . "'"
     . ",'" . $objProposedIndent->proposedindentid . "'";
    $queryCallSP = "CALL " . SP_GET_PROPOSED_INDENT_SKU_MAPPING . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    $this->tmsdb->ClosePDOConn($pdo);

    return $arrResult;
  }

  public function insert_indent($objIndent) {
    $currentindentid = 0;
    $pdo = $this->tmsdb->CreatePDOConn();
    //Prepare parameters
    $sp_params = "'" . $objIndent->transporterid . "'"
     . ",'" . $objIndent->vehicleno . "'"
     . ",'" . $objIndent->proposedindentid . "'"
     . ",'" . $objIndent->proposed_vehicletypeid . "'"
     . ",'" . $objIndent->actual_vehicletypeid . "'"
     . ",'" . $objIndent->factoryid . "'"
     . ",'" . $objIndent->depotid . "'"
     . ",'" . $objIndent->date_required . "'"
     . ",'" . $objIndent->totalweight . "'"
     . ",'" . $objIndent->totalvolume . "'"
     . ",'" . $objIndent->customerno . "'"
     . ",'" . $objIndent->todaysdate . "'"
     . ",'" . $objIndent->userid . "'"
     . ",@currentindentid";

    $queryCallSP = "CALL " . SP_INSERT_INDENT . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP);
    $outputVars = $pdo->query('SELECT @currentindentid')->fetch(PDO::FETCH_ASSOC);
    $currentindentid = $outputVars["@currentindentid"];
    $this->tmsdb->ClosePDOConn($pdo);

    return $currentindentid;
  }

  public function insert_indent_sku_mapping($objIndentSkuMapping) {
    $current_indent_sku_mappingid = 0;
    $pdo = $this->tmsdb->CreatePDOConn();

    //Prepare parameters
    $sp_params = "'" . $objIndentSkuMapping->indentid . "'"
     . ",'" . $objIndentSkuMapping->skuid . "'"
     . ",'" . $objIndentSkuMapping->no_of_units . "'"
     . ",'" . $objIndentSkuMapping->customerno . "'"
     . ",'" . $objIndentSkuMapping->todaysdate . "'"
     . ",'" . $objIndentSkuMapping->userid . "'"
     . "," . "@current_indent_sku_mappingid";

    $queryCallSP = "CALL " . SP_INSERT_INDENT_SKU_MAPPING . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP);
    $outputVars = $pdo->query('SELECT @current_indent_sku_mappingid')->fetch(PDO::FETCH_ASSOC);
    $current_indent_sku_mappingid = $outputVars["@current_indent_sku_mappingid"];
    $this->tmsdb->ClosePDOConn($pdo);

    return $current_indent_sku_mappingid;
  }

// </editor-fold>
  //
  // <editor-fold defaultstate="collapsed" desc="Helper functions">
  //find location
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

  function check_userkey($userkey) {
    $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted=0";
    $record = $this->db->query($sql, __FILE__, __LINE__);
    $row = $this->db->fetch_array($record);
    $retarray = array();
    if ($row['userkey'] != "") {
      $retarray['status'] = "successful";
      $retarray['customerno'] = $row["customerno"];
      $retarray['userid'] = $row["userid"];
      $retarray['realname'] = $row["realname"];
      $retarray['roleid'] = $row["roleid"];
      $retarray['role'] = $row["role"];
    } else {
      $retarray['status'] = "unsuccessful";
    }
    return $retarray;
  }

  function check_transporter($userid, $customerno) {
    $sql = "select tmsmapping.userid,tmsmapping.tmsid,transporter.transportername from tmsmapping
        inner join transporter on transporter.transporterid = tmsmapping.tmsid
        where tmsmapping.userid='" . $userid . "' AND tmsmapping.role = 'transporter'
        AND tmsmapping.customerno = '" . $customerno . "' AND tmsmapping.isdeleted=0";
    $record = $this->tmsdb->query($sql, __FILE__, __LINE__);
    $row = $this->tmsdb->fetch_array($record);
    $retarray = array();
    if ($row['userid'] != "") {
      $retarray['status'] = "successful";
      $retarray['transporterid'] = $row["tmsid"];
      $retarray['transportername'] = $row["transportername"];
    } else {
      $retarray['status'] = "unsuccessful";
    }
    return $retarray;
  }

  function checkvalidity($expirydate) {
    $today = date('Y-m-d H:i:s');
//        $today = add_hours($today, 0);
    if (strtotime($today) <= strtotime($expirydate)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function update_push_android_chk($userkey, $val) {
    $sql = "UPDATE user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
    $this->db->query($sql, __FILE__, __LINE__);
  }

  function update_push_android_chk_main($userkey, $val) {
    $sql = "UPDATE user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
    $this->db->query($sql, __FILE__, __LINE__);
  }

  function getSMSCount($customerno) {
    $smscount = 0;
    $pdo = $this->db->CreatePDOConn();
    $sqlSmsGet = sprintf("SELECT smsleft FROM ".DB_PARENT.".customer WHERE customerno=%d", $customerno);
    $arrResult = $pdo->query($sqlSmsGet)->fetch(PDO::FETCH_ASSOC);
    $this->db->ClosePDOConn($pdo);
    if (count($arrResult) == 1) {
      $smscount = $arrResult['smsleft'];
    }
    return $smscount;
  }

  function updateSMSCount($existingSMSCount, $smsmessage, $customerno) {
    $smsconsumed = 0;
    $smsleft = 0;
    $smslength = strlen($smsmessage);
    $divide = floor($smslength / api::PER_SMS_CHARACTERS);
    $mod = $smslength % api::PER_SMS_CHARACTERS;
    if ($mod > 0) {
      $smsconsumed = $divide + 1;
    } else if ($mod == 0) {
      $smsconsumed = $divide;
    }
    $smsleft = $existingSMSCount - $smsconsumed;
    $pdo = $this->db->CreatePDOConn();
    $sqlSmsUpdate = sprintf("UPDATE ".DB_PARENT.".customer SET smsleft=%d WHERE customerno=%d", $smsleft, $customerno);
    $pdo->query($sqlSmsUpdate);
    $this->db->ClosePDOConn($pdo);
  }

  function insertSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid) {
    $smsid = 0;
    $pdo = $this->db->CreatePDOConn();
    $sp_params = "'" . $phone . "'"
     . ",'" . $message . "'"
     . ",'" . $response . "'"
     . ",'" . $vehicleid . "'"
     . ",'" . $userid . "'"
     . ",'" . $customerno . "'"
     . ",'" . $isSMSSent . "'"
     . ",'" . $todaysdate . "'"
     . ",'" . $moduleid . "'"
     . "," . '@smsid';

    $queryCallSP = "CALL " . SP_INSERT_SMSLOG . "($sp_params)";
    $pdo->query($queryCallSP);
    $this->db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @smsid AS smsid";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    if (count($outputResult) > 0) {
      $smsid = $outputResult['smsid'];
    }
    return $smsid;
  }

  // </editor-fold>
  //
  // <editor-fold defaultstate="collapsed" desc="Utility functions">
  function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
    include_once("../../../cron/class.phpmailer.php");
    $isEmailSent = 0;
    $completeFilePath = '';
    if ($attachmentFilePath != '' && $attachmentFileName != '') {
      $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
    }

    $mail = new PHPMailer();
    $mail->IsMail();

    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();

    if (!empty($arrToMailIds)) {
      foreach ($arrToMailIds as $mailto) {
        $mail->AddAddress($mailto);
      }
      if (!empty($strCCMailIds)) {
        $mail->AddCustomHeader("CC: " . $strCCMailIds);
      }

      if (!empty($strBCCMailIds)) {
        $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
      }
    }
    $mail->From = "noreply@elixiatech.com";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com";
    //$mail->AddReplyTo($from,"Elixia Speed");
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHtml(true);
    if ($completeFilePath != '' && $attachmentFileName != '') {
      $mail->AddAttachment($completeFilePath, $attachmentFileName);
    }
    //SEND Mail

    if ($mail->Send()) {
      $isEmailSent = 1; // or use booleans here
    }
    return $isEmailSent;
  }

  function sendSMS($phoneArray, $message, &$response) {
    $isSMSSent = 0;
    $countryCode = "91";
    $arrPhone = array();
    if (is_array($phoneArray)) {
      foreach ($phoneArray as $phone) {
        if (preg_match('/^\d{10}$/', $phone)) {
          $arrPhone[] = $countryCode . $phone;
        }
      }
    } else {
      $arrPhone[] = $countryCode . $phoneArray;
    }
    $phone = implode(",", $arrPhone);
    $url = str_replace("{{PHONENO}}", urlencode($phone), SMS_URL);
    $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    if ($response === false) {
      //echo 'Curl error: ' . curl_error($ch);
      $isSMSSent = 0;
    } else {
      $isSMSSent = 1;
    }
    curl_close($ch);
    return $isSMSSent;
  }

  //</editor-fold>
//
}

?>
