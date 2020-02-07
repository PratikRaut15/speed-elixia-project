<?php

include_once '../../../lib/system/Log.php';
include_once '../../../lib/system/utilities.php';
include_once '../../../lib/autoload.php';

class SALES {

    function __construct() {
        $this->db = new database_salesengage(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, DB_SALESENGAGE_NEW);
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    public function validate_userkey($userkey) {
        $sp_params = "'" . $userkey . "'"
        ;

        $QUERY = $this->PrepareSP(constants::$SP_SPEED_CHECK_USERKEY, $sp_params);
        $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
        $results = $this->db->fetch_array($queryResult);
        $row_count = $this->db->num_rows($queryResult);
        $queryResult->close();
        $this->db->next_result();

        return $results;
    }

    public function getLoginData($value) {

        extract($value);

        $arr_data = array();

        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $username . "'"
                . ",'" . $password . "'"
                . ",'" . $todaysdate . "'"
                . "," . '@usertype'
                . "," . '@userkeyparam';

        $QUERY = $this->PrepareSP(constants::$SP_AUTHENTICATE_FOR_LOGIN, $sp_params);
        $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
        $this->db->next_result();
        $outputParamsQuery = "SELECT @usertype AS usertype, @userkeyparam AS userkeyparam";
        $outputResult = $this->db->query($outputParamsQuery, __FILE__, __LINE__);
        $outputFetch = $this->db->fetch_array($outputResult);
        $row_count = $this->db->num_rows($outputResult);
        $usertype = $outputFetch['usertype'];
        $userkeyparam = $outputFetch['userkeyparam'];
        $outputResult->close();
        $this->db->next_result();
        if ($userkeyparam != 0) {// check for invalid login credentials
            if ($usertype == 0 && $userkeyparam != 0) {// check for valid user and normal user login
                $arrResult = $this->db->fetch_array($queryResult);
                $queryResult->close();
                $this->db->next_result();
                $customerno = $arrResult['customerno'];
                $userkey = $arrResult['userkey'];
                $arrTemp = array();

//client
                try {
                    $sp_params1 = "" . $customerno . ""
                            . ",''"
                            . ",''"
                    ;

                    $QUERY1 = $this->PrepareSP(constants::SP_GET_CLIENT, $sp_params1);
                    $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);

                    $row_count1 = $this->db->num_rows($queryResult1);

                    if ($row_count1 > 0) {

                        while ($row1 = $this->db->fetch_array($queryResult1)) {
                            $arrTemp['clientid'] = $row1['clientid'];
                            $arrTemp['clientname'] = $row1['name'];
                            $arr_data['clientdata'][] = $arrTemp;
                        }
                    } else {
                        $arr_data['clientdata'] = '';
                    }
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }

                $queryResult1->close();
                $this->db->next_result();
// termsandcontions from chunk
                try {
                    unset($arrTemp);
                    $sp_params5 = "''"
                            . ",'" . $customerno . "'"
                            . ",''"
                    ;
                    $QUERY5 = $this->PrepareSP(constants::SP_GET_TERMSANDCONDITION_CHUNKMAPPING, $sp_params5);
                    $queryResult5 = $this->db->query($QUERY5, __FILE__, __LINE__);

                    $row_count5 = $this->db->num_rows($queryResult5);
                    $this->db->next_result();
                    if ($row_count5 > 0) {
                        while ($row5 = $this->db->fetch_array($queryResult5)) {
                            $arrTemp['chunkid'] = $row5['chunkid'];
                            $sp_params9 = "'" . $customerno . "'"
                                    . ",'" . $row5['chunkid'] . "'"
                            ;
                            $QUERY9 = $this->PrepareSP(constants::SP_GET_CHUNK, $sp_params9);
                            $queryResult9 = $this->db->query($QUERY9, __FILE__, __LINE__);
                            $row9 = $this->db->fetch_array($queryResult9);
                            $arrTemp['chunkname'] = $row9['chunkname'];
                            $arr_data['termsdata'][] = $arrTemp;
                            $queryResult9->close();
                            $this->db->next_result();
                        }
                    } else {
                        $arr_data['termsdata'] = '';
                    }
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $queryResult5->close();
                $this->db->next_result();

                /* chunk
                  try {
                  unset($arrTemp);
                  $arr_data1 = array();
                  $sp_params2 = "" . $customerno . ""
                  . ",'" . '' . "'"
                  ;
                  $QUERY2 = $this->PrepareSP(constants::SP_GET_CHUNK, $sp_params2);
                  $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);

                  $row_count2 = $this->db->num_rows($queryResult2);
                  if ($row_count2 > 0) {
                  while ($row2 = $this->db->fetch_array($queryResult2)) {
                  $arrTemp['chunkid'] = $row2['chunkid'];
                  $arrTemp['chunkname'] = $row2['chunkname'];
                  $arr_data1[] = $arrTemp;
                  }
                  } else {
                  $arr_data['chunkdata'][0]['chunkid'] = '-1';
                  $arr_data['chunkdata'][0]['chunkname'] = 'Company Profile';
                  $arr_data['chunkdata'][1]['chunkid'] = '-2';
                  $arr_data['chunkdata'][1]['chunkname'] = 'Quotation';
                  //$arr_data['chunkdata'][] = $arrTemp;
                  }
                  // removal of terms and condition chunk from chunk data
                  $tmpArray = array();
                  if (!empty($arr_data['termsdata'])) {
                  foreach ($arr_data1 as $data1) {

                  $duplicate = false;
                  foreach ($arr_data['termsdata'] as $data2) {
                  if ($data1['chunkid'] === $data2['chunkid'])
                  $duplicate = true;
                  }

                  if ($duplicate === false)
                  $tmpArray[] = $data1;
                  }

                  $newData = $tmpArray;
                  $arr_data['chunkdata'] = $newData;
                  }else {
                  $arr_data['chunkdata'] = $arr_data1;
                  }
                  // to find if Company Profile or Quotation chunk is present or not in $arr_data['chunkdata'] array
                  if ($row_count2 != 0) {
                  $arr_cnt = count($arr_data['chunkdata']);
                  $cp_bol = 0;
                  $cp = $this->searchForId('Company Profile', $arr_data['chunkdata'], 'chunkname');
                  if ($cp === null) {
                  $cp_cnt = $arr_cnt;
                  $arr_data['chunkdata'][$cp_cnt]['chunkid'] = '-1';
                  $arr_data['chunkdata'][$cp_cnt]['chunkname'] = 'Company Profile';
                  $cp_bol = 1;
                  }

                  $qt = $this->searchForId('Quotation', $arr_data['chunkdata'], 'chunkname');
                  if ($qt === null) {
                  if ($cp_bol == 0) {
                  $qt_cnt = $arr_cnt;
                  } else {
                  $qt_cnt = $arr_cnt + 1;
                  }
                  $arr_data['chunkdata'][$qt_cnt]['chunkid'] = '-2';
                  $arr_data['chunkdata'][$qt_cnt]['chunkname'] = 'Quotation';
                  }
                  }
                  } catch (Exception $ex) {
                  $log = new Log();
                  $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                  $arr_data['status'] = "Exception occured.";
                  }
                  $queryResult2->close();
                  $this->db->next_result();
                 *
                 */

//categoryid & product
                try {
                    unset($arrTemp);
                    $sp_params3 = "" . $customerno . ""
                            . ",''"
                            . ",''"
                    ;
                    $QUERY3 = $this->PrepareSP(constants::SP_GET_PRODUCT_MASTER, $sp_params3);
                    $queryResult3 = $this->db->query($QUERY3, __FILE__, __LINE__);

                    $row_count3 = $this->db->num_rows($queryResult3);
                    if ($row_count3 > 0) {

                        while ($row3 = $this->db->fetch_array($queryResult3)) {
                            $arrTemp['productid'] = $row3['product_masterid'];
                            $arrTemp['productname'] = $row3['productname'];
                            $arrTemp['unit_price'] = $row3['unit_price'];
                            $arrTemp['productcode'] = $row3['productcode'];
                            $arrTemp['categoryid'] = $row3['categoryid'];
//$arrTemp['categoryname'] = $row3['categoryname'];
//$arrTemp['categorycode'] = $row3['categorycode'];
                            $arr_data['productdata'][] = $arrTemp;
                        }
                    } else {
                        $arr_data['productdata'] = '';
                    }
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $queryResult3->close();
                $this->db->next_result();

//tax
                try {
                    unset($arrTemp);

                    $sp_params4 = "" . $customerno . ""
                            . ",'" . '' . "'"
                    ;
                    $QUERY4 = $this->PrepareSP(constants::SP_GET_TAX, $sp_params4);
                    $queryResult4 = $this->db->query($QUERY4, __FILE__, __LINE__);

                    $row_count4 = $this->db->num_rows($queryResult4);
                    if ($row_count4 > 0) {
                        while ($row4 = $this->db->fetch_array($queryResult4)) {
                            $arrTemp['taxid'] = $row4['taxid'];
                            $arrTemp['taxname'] = $row4['taxname'];
                            $arrTemp['taxpercent'] = $row4['taxpercent'];
                            $arr_data['taxdata'][] = $arrTemp;
                        }
                    } else {
                        $arr_data['taxdata'] = '';
                    }
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $queryResult4->close();
                $this->db->next_result();

//stages
                try {
                    unset($arrTemp);
                    $sp_params6 = "" . $customerno . ""
                            . ",'" . '' . "'"
                    ;
                    $QUERY6 = $this->PrepareSP(constants::SP_GET_STAGES, $sp_params6);
                    $queryResult6 = $this->db->query($QUERY6, __FILE__, __LINE__);

                    $row_count6 = $this->db->num_rows($queryResult6);
                    if ($row_count6 > 0) {
                        while ($row6 = $this->db->fetch_array($queryResult6)) {
                            $arrTemp['stageid'] = $row6['stageid'];
                            $arrTemp['stagename'] = $row6['stagename'];
                            $arrTemp['stagedescription'] = $row6['stagedescription'];
                            $arr_data['stagedata'][] = $arrTemp;
                        }
                    } else {
                        $arrTemp['stageid'] = 0;
                        $arrTemp['stagename'] = "please create stages from web";
                        $arrTemp['stagedescription'] = "please create stages from web";
                        $arr_data['stagedata'] = $arrTemp;
                    }
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $queryResult6->close();
                $this->db->next_result();
//category_master data
                try {
                    unset($arrTemp);
                    $sp_params7 = "" . $customerno . ""
                            . ",'" . '' . "'"
                    ;
                    $QUERY7 = $this->PrepareSP(constants::SP_GET_CATEGORY_MASTER, $sp_params7);
                    $queryResult7 = $this->db->query($QUERY7, __FILE__, __LINE__);

                    $row_count7 = $this->db->num_rows($queryResult7);
                    if ($row_count7 > 0) {

                        while ($row7 = $this->db->fetch_array($queryResult7)) {
                            $arrTemp['categoryid'] = $row7['categoryid'];
                            $arrTemp['categoryname'] = $row7['categoryname'];
                            $arrTemp['categorycode'] = $row7['categorycode'];
                            $arr_data['categorydata'][] = $arrTemp;
                        }
                    } else {
                        $arr_data['categorydata'] = '';
                    }
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $queryResult7->close();
                //$this->db->next_result();

                $arr_data['success'] = 1;
                $arr_data['userkey'] = $userkey;
            } else if ($usertype == 1 && $userkeyparam != 0) {//check for forgot password user
                $arr_data['status'] = "forgot_password_success";
                $arr_data['customername'] = null;
                $arr_data['userkey'] = $userkeyparam;
            } else {
                $arr_data['success'] = 0;
                $arr_data['status'] = "Invalid Credentials";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['status'] = "Invalid Credentials";
        }

        echo json_encode($arr_data);
        return $arr_data;
    }

    public function getRecipientData($value) {
        extract($value);

        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];

//client
            try {
                $sp_params = "" . $customerno . ""
                        . "," . $clientid . ""
                        . ",''"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_CLIENTRECIPIENTUSERS, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);

                $row_count = $this->db->num_rows($queryResult);

                if ($row_count > 0) {
                    $arr_data['success'] = 1;
                    while ($row = $this->db->fetch_array($queryResult)) {
                        $arrTemp['recipientid'] = $row['cruid'];
                        $arrTemp['recipientname'] = $row['cruname'];
                        $arrTemp['recipientemail'] = $row['cruemail'];
                        $arrTemp['recipientphone'] = $row['cruphoneno'];
                        $arr_data['recipientdata'][] = $arrTemp;
                    }
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['recipientdata'] = '';
                    $arr_data['errormsg'] = "No Client Recipient Found";
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            $queryResult->close();
//$this->db->next_result();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }

        echo json_encode($arr_data);
        return $arr_data;
    }

    public function InsertClientData($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];
            $today = date('Y-m-d H:i:s');
            if ($dob != '') {
                $formatdob = date("Y-m-d", strtotime($dob));
            } else {
                $formatdob = '';
            }
            if ($email != '' && $mobileno != '' && $city != '' && $country != '' && $customerno != '' && $companyname != '' && $name != '') {

//client
                try {
                    $sp_params1 = "'" . $name . "'"
                            . ",'" . $mobileno . "'"
                            . ",'" . $email . "'"
                            . ",'" . $formatdob . "'"
                            . ",'" . $companyname . "'"
                            . ",'" . $customerno . "'"
                            . ",'" . $userid . "'"
                            . ",'" . $today . "'"
                            . "," . "@clientid"
                    ;

                    $QUERY1 = $this->PrepareSP(constants::SP_INSERT_CLIENT, $sp_params1);
                    $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
                    $outputVars1 = $this->db->query('SELECT @clientid as currentclientid', __FILE__, __LINE__);
                    $row_count1 = $this->db->num_rows($outputVars1);
                    $outparam_client = $this->db->fetch_array($outputVars1);
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $outputVars1->close();
                $this->db->next_result();

                if ($row_count1 > 0 && !empty($outparam_client)) {
                    $clientid = $outparam_client['currentclientid'];
                    //$addressid = $outparam_address['currentaddressid'];

                    try {
//address
                        $sp_params = "'" . $clientid . "'"
                                . ",'" . $line1 . "'"
                                . ",'" . $line2 . "'"
                                . ",'" . $line3 . "'"
                                . ",'" . $pincode . "'"
                                . ",'" . $city . "'"
                                . ",'" . $state . "'"
                                . ",'" . $country . "'"
                                . ",'" . $customerno . "'"
                                . ",'" . $userid . "'"
                                . ",'" . $today . "'"
                                . "," . "@addressid"
                        ;

                        $QUERY = $this->PrepareSP(constants::SP_INSERT_ADDRESS, $sp_params);
                        $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                        $outputVars = $this->db->query('SELECT @addressid as currentaddressid', __FILE__, __LINE__);
                        $row_count = $this->db->num_rows($outputVars);
                        $outparam_address = $this->db->fetch_array($outputVars);
                    } catch (Exception $ex) {
                        $log = new Log();
                        $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                        $arr_data['status'] = "Exception occured.";
                    }

                    $outputVars->close();
                    $this->db->next_result();

//clientrecipientuser
                    try {
                        $sp_params2 = "'" . $clientid . "'"
                                . ",'" . $name . "'"
                                . ",'" . $email . "'"
                                . ",'" . $mobileno . "'"
                                . ",'" . $customerno . "'"
                                . ",'" . $userid . "'"
                                . ",'" . $today . "'"
                                . "," . "@cruid"
                        ;
                        $QUERY2 = $this->PrepareSP(constants::SP_INSERT_CLIENTRECIPIENTUSERS, $sp_params2);
                        $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
                        $outputVars2 = $this->db->query('SELECT @cruid as currentcruid', __FILE__, __LINE__);
                        $row_count2 = $this->db->num_rows($outputVars2);
                        $outparam_recipient = $this->db->fetch_array($outputVars2);
                    } catch (Exception $ex) {
                        $log = new Log();
                        $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                        $arr_data['status'] = "Exception occured.";
                    }
                    $outputVars2->close();
                    $this->db->next_result();
                    $arr_data['success'] = 1;

                    $arr_data['clientid'] = $clientid;
                    $arr_data['clientname'] = $name;
                    $arr_data['message'] = "Client Added Successfully";
                } else {
                    $arr_data['errormsg'] = "Client Not Added";
                }
            } else {
                $arr_data['errormsg'] = "Client Not Added";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }

        echo json_encode($arr_data);
        return $arr_data;
    }

    public function InsertRecipientData($value) {
        extract($value);
        $arr_data = array();
        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];

            $today = date('Y-m-d H:i:s');

            try {
                $sp_params = "'" . $clientid . "'"
                        . ",'" . $name . "'"
                        . ",'" . $email . "'"
                        . ",'" . $phone . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $userid . "'"
                        . ",'" . $today . "'"
                        . "," . "@cruid"
                ;
                $QUERY = $this->PrepareSP(constants::SP_INSERT_CLIENTRECIPIENTUSERS, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $outputVars = $this->db->query('SELECT @cruid as currentcruid', __FILE__, __LINE__);
                $row_count = $this->db->num_rows($outputVars);
                $outparam_recipient = $this->db->fetch_array($outputVars);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            $outputVars->close();

            if ($row_count > 0 && !empty($outparam_recipient)) {
                $arr_data['success'] = 1;
                $arr_data['recipientid'] = $outparam_recipient['currentcruid'];
                $arr_data['recipientname'] = $name;
                $arr_data['message'] = "Recipient Added Successfully";
            } else {
                $arr_data['recipientid'] = 0;
                $arr_data['errormsg'] = "Recipient Not Added";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }

        echo json_encode($arr_data);
        return $arr_data;
    }

    public function getPortfolioData($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];

            $today = date('Y-m-d H:i:s');
            if ($chunkid == '-1') {
                $arr_data['errormsg'] = "Please Create Compamy Profile From Web";
            } elseif ($chunkid == '-2') {
                $arr_data['errormsg'] = "Please Fill The Quotation Details From Web";
            } else {

                foreach ($recipientdata as $data) {
                    try {
                        $sp_params = "'" . $customerno . "'"
                                . ",''"
                                . ",'" . $data['recipientid'] . "'"
                        ;
                        $QUERY = $this->PrepareSP(constants::SP_GET_CLIENTRECIPIENTUSERS, $sp_params);
                        $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);

                        $row_count = $this->db->num_rows($queryResult);

                        $arr_recipientdetails[] = $this->db->fetch_array($queryResult);

                        $queryResult->close();
                        $this->db->next_result();
                    } catch (Exception $ex) {
                        $log = new Log();
                        $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                        $arr_data['status'] = "Exception occured.";
                    }
                }

                if (!empty($arr_recipientdetails)) {
                    try {
                        $sp_params1 = "'" . $customerno . "'"
                                . ",'" . $chunkid . "'"
                        ;

                        $QUERY1 = $this->PrepareSP(constants::SP_GET_CHUNK, $sp_params1);
                        $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
                        $row_count1 = $this->db->num_rows($queryResult1);
                    } catch (Exception $ex) {
                        $log = new Log();
                        $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                        $arr_data['status'] = "Exception occured.";
                    }
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['errormsg'] = "Fail To Send Email";
                }

                if ($row_count1 > 0) {
                    $row = $this->db->fetch_array($queryResult1);

                    $email_data = $row['emailtemplate'];
                    $subject = $row['email_subject'];
                    $attach1 = $row['attach1'];
                    $attach2 = $row['attach2'];
                    $attach3 = $row['attach3'];
                    $queryResult1->close();
                    $this->db->next_result();

                    $message = '';
                    $message = "<html>";
                    $message.="<head>
            <style type='text/css'>
            .formatting{
                font-family:Arial;
                font-size: 15pt;
            }

            </style>
            </head>
            <body class='formatting'>";
                    $message.=$email_data;
                    $message .= "</body></html>";

                    $message = chunk_split(base64_encode($message));

                    foreach ($arr_recipientdetails as $recipientdetail) {
                        $users = '';
                        $users = $recipientdetail['cruemail'];

                        $this->sendMail($users, $subject, $message);
                    }
                    $arr_data['success'] = 1;
                    $arr_data['message'] = "Email Sent Successfully";
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['errormsg'] = "Fail To Send Email";
                }
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function EditClientData($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];
            $today = date('Y-m-d H:i:s');

            $formatdob = date("Y-m-d", strtotime($dob));
            try {
                $sp_params = "'" . $clientid . "'"
                        . ",'" . $name . "'"
                        . ",'" . $mobileno . "'"
                        . ",'" . $email . "'"
                        . ",'" . $formatdob . "'"
                        . ",'" . $companyname . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $userid . "'"
                        . ",'" . $today . "'"

                ;

                $QUERY = $this->PrepareSP(constants::SP_UPDATE_CLIENT, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($addressid != '' && $addressid != 0) {
                try {
                    $sp_params1 = "'" . $addressid . "'"
                            . ",'" . $clientid . "'"
                            . ",'" . $line1 . "'"
                            . ",'" . $line2 . "'"
                            . ",'" . $line3 . "'"
                            . ",'" . $pincode . "'"
                            . ",'" . $city . "'"
                            . ",'" . $state . "'"
                            . ",'" . $country . "'"
                            . ",'" . $customerno . "'"
                            . ",'" . $userid . "'"
                            . ",'" . $today . "'"

                    ;

                    $QUERY1 = $this->PrepareSP(constants::SP_UPDATE_ADDRESS, $sp_params1);
                    $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
            }

            if ($queryResult > 0) {

                $arr_data['success'] = 1;
                $arr_data['message'] = "Client Edited Successfully";
            } else {
                $arr_data['success'] = 0;
                $arr_data['errormsg'] = "Client Not Edited";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function EditRecipientData($value) {
        extract($value);
        $arr_data = array();
        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];

            $today = date('Y-m-d H:i:s');

            try {
                $sp_params = "'" . $recipientid . "'"
                        . ",'" . $clientid . "'"
                        . ",'" . $name . "'"
                        . ",'" . $email . "'"
                        . ",'" . $phone . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $userid . "'"
                        . ",'" . $today . "'"

                ;
                $QUERY = $this->PrepareSP(constants::SP_UPDATE_CLIENTRECIPIENTUSERS, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }

            if ($queryResult > 0) {
                $arr_data['success'] = 1;
                $arr_data['message'] = "Recipient Edited Successfully";
            } else {
                $arr_data['success'] = 0;
                $arr_data['errormsg'] = "Recipient Not Edited";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }

        echo json_encode($arr_data);
        return $arr_data;
    }

    public function InsertOrderData($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        $msg_sms = '';
        $msg_email = '';
        $statusMessage = '';

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];
            $realname = $userdata['realname'];
            $fromemail = $userdata['email'];
            //$stagename = strtolower(trim($stagename));
            $stagename = preg_replace('/[^a-z\s]/', '', strtolower($stagename));
            $stagename = preg_split('/\s+/', $stagename, NULL, PREG_SPLIT_NO_EMPTY);
            $stagename = array_flip($stagename);

            $word = "quotation";

            $today = date('Y-m-d H:i:s');
//check in seed table
//insert in order
            try {
                $sp_params = "''" . ""
                        . ",'" . $clientid . "'"
                        . ",'" . $stageid . "'"
                        . ",''" . ""
                        . ",'" . $subtotalamount . "'"
                        . ",''" . ""
                        . ",''" . ""
                        . ",''" . ""
                        . ",''" . ""
                        . ",'" . $additionalcostname . "'"
                        . ",'" . $additionalcost . "'"
                        . ",'" . $totalamount . "'"
                        . ",'" . $taxid . "'"
                        . ",'" . $taxamount . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $today . "'"
                        . ",'" . $userid . "'"
                        . "," . "@orderid"
                        . "," . "@ordero"
                ;
                $QUERY = $this->PrepareSP(constants::SP_INSERT_ORDERS, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $outputVars = $this->db->query('SELECT @orderid as currentorderid', __FILE__, __LINE__);
                $row_count = $this->db->num_rows($outputVars);
                $outparam_order = $this->db->fetch_array($outputVars);
                $outputVars->close();
                $this->db->next_result();
                $outputVars1 = $this->db->query('SELECT @orderno as currentorderno', __FILE__, __LINE__);
                $row_count1 = $this->db->num_rows($outputVars1);
                $outparam_order1 = $this->db->fetch_array($outputVars1);
                $outputVars1->close();
                $this->db->next_result();
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count > 0 && !empty($outparam_order)) {
                $orderid = $outparam_order['currentorderid'];
                $orderno = $outparam_order1['currentorderno'];
                if (!empty($attachdata)) {
                    // insert into ATTACHMENTSTAGE_MAPPING table
                    foreach ($attachdata as $attachdatas) {
                        try {
                            $sp_params2 = "'" . $attachdatas['attachid'] . "'"
                                    . ",'" . $orderid . "'"
                                    . ",'" . $stageid . "'"
                                    . ",'" . $customerno . "'"
                                    . ",'" . $userid . "'"
                                    . ",'" . $today . "'"
                                    . ",'" . $userid . "'"
                                    . ",'" . $today . "'"
                                    . "," . "@attachmentmapid"
                            ;
                            $QUERY2 = $this->PrepareSP(constants::SP_INSERT_ATTACHMENTSTAGE_MAPPING, $sp_params2);
                            $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
                            $outputVars2 = $this->db->query('SELECT @attachmentmapid as currentattachmentmapid', __FILE__, __LINE__);
                            //$row_count2 = $this->db->num_rows($outputVars2);
                            //$outparam_order = $this->db->fetch_array($outputVars);
                            $outputVars2->close();
                            $this->db->next_result();
                        } catch (Exception $ex) {
                            $log = new Log();
                            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                            $arr_data['status'] = "Exception occured.";
                        }
                    }
                    //check for attachments

                    $validAttachment = $this->CheckForAttachment($customerno, $attachdata);
                }
                if (isset($stagename[$word])) {
//insert in orderproduct_mapping
                    foreach ($productdata as $productdatas) {
                        try {
                            $sp_params1 = "'" . $orderid . "'"
                                    . ",'" . $productdatas['productid'] . "'"
                                    . ",'" . $productdatas['unit_price'] . "'"
                                    . ",'" . $productdatas['quantity'] . "'"
                                    . ",'" . $customerno . "'"
                                    . ",'" . $userid . "'"
                                    . ",'" . $today . "'"
                                    . "," . "@opmid"
                            ;
                            $QUERY1 = $this->PrepareSP(constants::SP_INSERT_ORDERPRODUCT_MAPPING, $sp_params1);
                            $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
                            $outputVars1 = $this->db->query('SELECT @opmid as currentopmid', __FILE__, __LINE__);
                            $row_count1 = $this->db->num_rows($outputVars1);
                            $productinorder[] = $this->db->fetch_array($outputVars1);
                            $outputVars1->close();
                            $this->db->next_result();
                        } catch (Exception $ex) {
                            $log = new Log();
                            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                            $arr_data['status'] = "Exception occured.";
                        }
                    }
                    // insert into attachment mapping 

                    $msg_email = $this->prepareQuotation_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $validAttachment);
                } else {
                    $msg_email = $this->prepareOrder_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $validAttachment);
                }
                // client recipient phone nos for sms.
                unset($arrTemp);
                $data = array();
                foreach ($recipientdata as $recipientdatas) {
                    try {

                        $sp_params10 = "'" . $customerno . "'"
                                . ",''"
                                . ",'" . $recipientdatas['recipientid'] . "'"
                        ;
                        $QUERY10 = $this->PrepareSP(constants::SP_GET_CLIENTRECIPIENTUSERS, $sp_params10);
                        $queryResult10 = $this->db->query($QUERY10, __FILE__, __LINE__);
                        $row_count10 = $this->db->num_rows($queryResult10);
                        if ($row_count10 > 0) {
                            while ($row10 = $this->db->fetch_array($queryResult10)) {
                                $arrTemp['recipientid'] = $row10['cruid'];
                                $arrTemp['recipientname'] = $row10['cruname'];
                                $arrTemp['recipientemail'] = $row10['cruemail'];
                                $arrTemp['recipientphone'] = $row10['cruphoneno'];
                                $data[] = $arrTemp;
                            }
                        }
                    } catch (Exception $ex) {
                        $log = new Log();
                        $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                        $arr_data['status'] = "Exception occured.";
                    }
                    $queryResult10->close();
                    $this->db->next_result();
                }
                if ($requestsms == 1) {
                    $msg_sms = $this->EditOrderSms($value, $data, $customerno);
                }
                if ($msg_sms != '') {
                    $statusMessage = $msg_sms;
                }
                if ($msg_email != '' && $msg_sms != '') {
                    $statusMessage .= ", " . $msg_email;
                }
                if ($msg_email != '' && $msg_sms == '') {
                    $statusMessage .= $msg_email;
                }
                if ($msg_email == '' && $msg_sms == '') {
                    $statusMessage = "No Phone Number OR Email ID Found";
                }

                $arr_data['success'] = 1;
                $arr_data['orderid'] = $orderid;
                $arr_data['orderno'] = $orderno;
                $arr_data['message'] = $statusMessage;
            } else {
                $arr_data['success'] = 0;
                $arr_data['orderid'] = 0;
                $arr_data['errormsg'] = "Order Not Added";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function DeleteClient($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];

            $today = date('Y-m-d H:i:s');
//delete client
            try {
                $sp_params = "'" . $clientid . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $today . "'"
                        . ",'" . $userid . "'"
                ;
                $QUERY = $this->PrepareSP(constants::SP_DELETE_CLIENT, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($queryResult > 0) {
                $arr_data['success'] = 1;
                $arr_data['clientid'] = $clientid;
                $arr_data['message'] = "Client Deleted Successfully";
            } else {
                $arr_data['success'] = 0;
                $arr_data['clientid'] = 0;
                $arr_data['message'] = "Client Not Deleted";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function DeleteRecipient($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];

            $today = date('Y-m-d H:i:s');
//delete client
            try {
                $sp_params = "'" . $recipient . "'"
                        . ",''"
                        . ",'" . $customerno . "'"
                        . ",'" . $today . "'"
                        . ",'" . $userid . "'"
                ;
                $QUERY = $this->PrepareSP(constants::SP_DELETE_CLIENTRECIPIENTUSERS, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($queryResult > 0) {
                $arr_data['success'] = 1;
                $arr_data['recipient'] = $recipient;
                $arr_data['message'] = "Recipient Deleted Successfully";
            } else {
                $arr_data['success'] = 0;
                $arr_data['recipient'] = 0;
                $arr_data['message'] = "Recipient Not Deleted";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function SearchClient($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];

            $arrTemp = array();

//client
            try {
                $sp_params1 = "'" . $customerno . "'"
                        . ",''"
                        . ",'" . $clientname . "'"
                ;

                $QUERY1 = $this->PrepareSP(constants::SP_GET_CLIENT, $sp_params1);
                $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);

                $row_count1 = $this->db->num_rows($queryResult1);

                if ($row_count1 > 0) {
                    $arr_data['success'] = 1;
                    while ($row1 = $this->db->fetch_array($queryResult1)) {
                        $this->db->next_result();
                        $arrTemp['clientid'] = $row1['clientid'];
                        $arrTemp['clientname'] = $row1['name'];
//to find no client recipient user
                        $sp_params2 = "'" . $customerno . "'"
                                . ",'" . $arrTemp['clientid'] . "'"
                                . ",''"

                        ;
                        $QUERY2 = $this->PrepareSP(constants::SP_GET_CLIENTRECIPIENTUSERS, $sp_params2);
                        $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
                        $row_count2 = $this->db->num_rows($queryResult2);
                        if ($row_count2 > 0) {
                            $arrTemp['usercount'] = $row_count2;
                        } else {
                            $arrTemp['usercount'] = 0;
                        }
                        $arr_data['clientdata'][] = $arrTemp;
                    }
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['clientdata'] = '';
                    $arr_data['errormsg'] = 'Client Not Found';
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }

            $queryResult1->close();
            $queryResult2->close();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function GetOneClientData($value) {
        extract($value);

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
//client
            try {
                $sp_params = "" . $customerno . ""
                        . ",'" . $clientid . "'"
                        . ",''"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_CLIENT, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);

                $row_count = $this->db->num_rows($queryResult);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count > 0) {
                $arr_data['success'] = 1;
                $row = $this->db->fetch_array($queryResult);
                $arr_data['clientid'] = $row['clientid'];
                $arr_data['clientname'] = $row['name'];
                $arr_data['clientemail'] = $row['email'];
                $arr_data['clientmobileno'] = $row['mobileno'];

                if ($row['dob'] != '0000-00-00') {
                    $arr_data['clientdob'] = date("d-m-Y", strtotime($row['dob']));
                } else {
                    $arr_data['clientdob'] = '';
                }
                $arr_data['clientcompanyname'] = $row['clientcompanyname'];
                $arr_data['addressid'] = $row['addressid'];
                $arr_data['line1'] = $row['line1'];
                $arr_data['line2'] = $row['line2'];
                $arr_data['line3'] = $row['line3'];
                $arr_data['pincode'] = $row['pincode'];
                $arr_data['city'] = $row['city'];
                $arr_data['state'] = $row['state'];
                $arr_data['country'] = $row['country'];
            } else {
                $arr_data['success'] = 0;
                $arr_data['message'] = 'Client Not Found';
            }

            $queryResult->close();
            $this->db->next_result();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function PaginationClientData($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $arrTemp = array();

//client
            try {
                $sp_params = "'" . $pageindex . "'"
                        . ",'" . $pagesize . "'"
                        . ",'" . $customerno . "'"
                        . "," . "@recordCount"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_CLIENTS_PAGEWISE, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $row_count = $this->db->num_rows($queryResult);

                if ($row_count > 0) {
                    $arr_data['success'] = 1;
                    while ($row = $this->db->fetch_array($queryResult)) {
                        $arrTemp['clientid'] = $row['clientid'];
                        $arrTemp['clientname'] = $row['name'];
                        $arrTemp['totalclientuser'] = $row['userCount'];
                        $arr_data['clientdata'][] = $arrTemp;
                    }
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['clientdata'] = '';
                    $arr_data['errormsg'] = 'Client Not Found';
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }

            $queryResult->close();
            $this->db->next_result();

            $outputVars = $this->db->query('SELECT @recordCount as totalClients', __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($outputVars)) {
                $result = $row['totalClients'];
            }
            $arr_data['totalClients'] = $result;
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function prepareQuotation_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $validAttachment) {
        extract($value);

        $today = date("d-m-Y");
        $arrTemp = array();
        $tnc = array();

        // get template attachment from quotation
        try {
            $sp_params11 = "'" . $customerno . "'"
                    . ",''"
                    . ",'" . $stageid . "'"
            ;
            $QUERY11 = $this->PrepareSP(constants::SP_GET_TEMPLATES, $sp_params11);
            $queryResult11 = $this->db->query($QUERY11, __FILE__, __LINE__);
            $row11 = $this->db->fetch_array($queryResult11);
            $templatedata = $row11['attach_template'];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        $queryResult11->close();
        $this->db->next_result();

        //check and create required folders on server to store attachment
        $dest = "../../../customer";
        $dest1 = "../../../customer/salesengage";
        $dest2 = "../../../customer/$customerno/salesengage/";
        $dest3 = "../../../customer/$customerno/salesengage/clientno/$clientid";
        if (!file_exists($dest1)) {
            mkdir($dest1, 0777, true);
        }
        if (!file_exists($dest2)) {
            mkdir($dest2, 0777, true);
        }
        if (!file_exists($dest3)) {
            mkdir($dest3, 0777, true);
        }
//address of client
        try {
            $sp_params = "'" . $customerno . "'"
                    . ",'" . $clientid . "'"
            ;
            $QUERY = $this->PrepareSP(constants::SP_GET_ADDRESS, $sp_params);
            $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }

        $row = $this->db->fetch_array($queryResult);
        $name = $row['name'];
        $comapanyname = $row['clientcompanyname'];
        $line1 = $row['line1'];
        $line2 = $row['line2'];
        $line3 = $row['line3'];
        $pincode = $row['pincode'];
        $city = $row['city'];

        $queryResult->close();
        $this->db->next_result();
//tax rate
        try {
            $sp_params5 = "'" . $customerno . "'"
                    . ",'" . $taxid . "'"
            ;
            $QUERY5 = $this->PrepareSP(constants::SP_GET_TAX, $sp_params5);
            $queryResult5 = $this->db->query($QUERY5, __FILE__, __LINE__);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }

        $row5 = $this->db->fetch_array($queryResult5);
        $taxrate = $row5['taxpercent'] . '%';
        $queryResult5->close();
        $this->db->next_result();

        $templatedata = str_replace("{{CLIENTCOMPANY}}", "$comapanyname", "$templatedata");
        $templatedata = str_replace("{{CLIENTLINE1}}", "$line1", "$templatedata");
        $templatedata = str_replace("{{CLIENTLINE2}}", "$line2", "$templatedata");
        $templatedata = str_replace("{{CLIENTLINE3}}", "$line3", "$templatedata");
        $templatedata = str_replace("{{CLIENTPINCODE}}", "$pincode", "$templatedata");
        $templatedata = str_replace("{{CLIENTCITY}}", "$city", "$templatedata");
        $templatedata = str_replace("{{CUSTOMERNO}}", "$customerno", "$templatedata");
        $templatedata = str_replace("{{DATE}}", "$today", "$templatedata");
        $templatedata = str_replace("{{ORDER NO}}", "$orderno", "$templatedata");
        $templatedata = str_replace("{{SUB TOTAL}}", "$subtotalamount", "$templatedata");
        $templatedata = str_replace("{{TOTAL}}", "$totalamount", "$templatedata");
        $templatedata = str_replace("{{ADDITION COST}}", "$additionalcost", "$templatedata");
        $templatedata = str_replace("{{TAX AMOUNT}}", "$taxamount", "$templatedata");
        $templatedata = str_replace("{{TAX RATE}}", "$taxrate", "$templatedata");

//tnc chunk mapping and chunk
        $x = 1;
        $description = "";
        foreach ($termsandconditiondata as $termsandconditiondatas) {
            $total = "";

            try {
                $sp_params12 = "'" . $termsandconditiondatas['chunkid'] . "'"
                        . ",'" . $customerno . "'"
                        . ",''"
                ;
                $QUERY12 = $this->PrepareSP(constants::SP_GET_TERMSANDCONDITION_CHUNKMAPPING, $sp_params12);
                $queryResult12 = $this->db->query($QUERY12, __FILE__, __LINE__);


                while ($row12 = $this->db->fetch_array($queryResult12)) {

                    $description .= $x . ")" . $row12['tandc_description'] . "<br>";

                    $x++;
                }

                $queryResult12->close();
                $this->db->next_result();
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
        }

        $templatedata = str_replace("{{TNCDATA}}", "$description", "$templatedata");

        $product_desc = "";
        $quantity = "";
        $unitprice = "";
        $totalamt = "";

        foreach ($productdata as $productdatas) {
            $productname = "";
            $total = "";
            try {

                $sp_params3 = "" . $customerno . ""
                        . ",''"
                        . ",'" . $productdatas['productid'] . "'"
                ;
                $QUERY3 = $this->PrepareSP(constants::SP_GET_PRODUCT_MASTER, $sp_params3);
                $queryResult3 = $this->db->query($QUERY3, __FILE__, __LINE__);

                $row3 = $this->db->fetch_array($queryResult3);

                $productname = $row3['productname'];
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }

            $product_desc.=$productname . "<br>";
            $quantity.=$productdatas['quantity'] . "<br>";
            $unitprice.=$productdatas['unit_price'] . "<br>";
            (float) $total = (float) $productdatas['unit_price'] * (float) $productdatas['quantity'] . "<br>";
            $totalamt.=$total;

            $queryResult3->close();
            $this->db->next_result();
        }
        $templatedata = str_replace("{{DESCRIPTION}}", "$product_desc", "$templatedata");
        $templatedata = str_replace("{{QUANTITY}}", "$quantity", "$templatedata");
        $templatedata = str_replace("{{PERUNITPRICE}}", "$unitprice", "$templatedata");
        $templatedata = str_replace("{{TOTALAMOUNT}}", "$totalamt", "$templatedata");

// CUSTOMER_PROFILE
        try {

            $sp_params4 = "" . $customerno . ""
                    . ",''"

            ;
            $QUERY4 = $this->PrepareSP(constants::SP_GET_CUSTOMER_PROFILE, $sp_params4);
            $queryResult4 = $this->db->query($QUERY4, __FILE__, __LINE__);

            $row4 = $this->db->fetch_array($queryResult4);
            $custcomapanyname = $row4['companyname'];
            $companycontactperson = $row4['companycontactperson'];
            $companytelephoneno = $row4['companytelephoneno'];
            $companymobileno = $row4['companymobileno'];
            $companyemail = $row4['companyemail'];
            $companylogo = $row4['companylogo'];
            $custline1 = $row4['line1'];
            $custline2 = $row4['line2'];
            $custline3 = $row4['line3'];
            $custpincode = $row4['pincode'];
            $custcity = $row4['city'];
            $custstate = $row4['state'];
            $custcountry = $row4['country'];
            $validity = $row4['validity'];
            $approvedby = $row4['approvedby'];
            $bcc = $row4['bcc'];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        $queryResult4->close();
        $this->db->next_result();

        $templatedata = str_replace("{{YOUR COMPANY NAME}}", "$custcomapanyname", "$templatedata");
        $templatedata = str_replace("{{LINE1}}", "$custline1", "$templatedata");
        $templatedata = str_replace("{{LINE2}}", "$custline2", "$templatedata");
        $templatedata = str_replace("{{LINE3}}", "$custline3", "$templatedata");
        $templatedata = str_replace("{{PINCODE}}", "$custpincode", "$templatedata");
        $templatedata = str_replace("{{CITY}}", "$custcity", "$templatedata");
        $templatedata = str_replace("{{STATE}}", "$custstate", "$templatedata");
        $templatedata = str_replace("{{COUNTRY}}", "$custcountry", "$templatedata");
        $templatedata = str_replace("{{PHONE}}", "$companytelephoneno", "$templatedata");
        $templatedata = str_replace("{{EMAIL}}", "$companyemail", "$templatedata");

        $templatedata = str_replace("{{PREPAREBYUSER}}", "$realname", "$templatedata");

        if ($validity == 0) {
            $val = 7; //set default to 7 days
        } else {
            $val = $validity;
        }

        $valdate = date("d-m-Y", strtotime("+$val days"));
        $userapproval = '';
        if ($approvedby == "") {
            $userapproval = $companycontactperson;
        } else {
            $userapproval = $approvedby;
        }
        $templatedata = str_replace("{{APPROVEDBYUSER}}", "$userapproval", "$templatedata");
        $templatedata = str_replace("{{VALIDDATE}}", "$valdate", "$templatedata");

//pdf
        require_once('../../reports/html2pdf.php');
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($templatedata);
//$html2pdf->Output($templatedata . "_" . date("d-m-Y") . "generate.pdf");
            $html2pdf->Output($dest3 . "/quotation_$orderno.pdf", 'F');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }


        //email body
        try {

            $sp_params9 = "'" . $customerno . "'"
                    . ",'" . $stageid . "'"
            ;
            $QUERY9 = $this->PrepareSP(constants::SP_GET_STAGES, $sp_params9);
            $queryResult9 = $this->db->query($QUERY9, __FILE__, __LINE__);
            $row9 = $this->db->fetch_array($queryResult9);
            $messagedata = $row9['emailtemplate'];
            $subject = $row9['email_subject'];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        $queryResult9->close();
        $this->db->next_result();
        $messagedata = str_replace("{{SENDERNAME}}", "$realname", "$messagedata");
        $messagedata = str_replace("{{COMPANYNAME}}", "$custcomapanyname", "$messagedata");
        //send mail to
        $path = $dest3;
        $filename = "quotation_$orderno.pdf";
        $BCC = isset($bcc) ? $bcc : '';
        foreach ($recipientdata as $recipientdatas) {
            try {

                $sp_params10 = "'" . $customerno . "'"
                        . ",''"
                        . ",'" . $recipientdatas['recipientid'] . "'"
                ;
                $QUERY10 = $this->PrepareSP(constants::SP_GET_CLIENTRECIPIENTUSERS, $sp_params10);
                $queryResult10 = $this->db->query($QUERY10, __FILE__, __LINE__);
                $row10 = $this->db->fetch_array($queryResult10);
                $mailto[] = $row10['cruemail'];
                $recname = $row10['cruname'];
                $recphone = $row10['cruphoneno'];
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            $queryResult10->close();
            $this->db->next_result();

            //insert in clinent interaction table
            try {

                $sp_params11 = "'" . $clientid . "'"
                        . ",'" . $recipientdatas['recipientid'] . "'"
                        . ",'" . $stageid . "'"
                        . ",'" . $orderid . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $userid . "'"
                        . ",'" . date("Y-m-d H:i:s") . "'"
                        . "," . "@ciid"
                ;
                $QUERY11 = $this->PrepareSP(constants::SP_INSERT_CLIENT_INTERACTION, $sp_params11);
                $queryResult11 = $this->db->query($QUERY11, __FILE__, __LINE__);
                $outputVars11 = $this->db->query('SELECT @ciid as currentciid', __FILE__, __LINE__);
                $row_count11 = $this->db->num_rows($outputVars11);
                $client_interaction = $this->db->fetch_array($outputVars11);
                $outputVars11->close();
                $this->db->next_result();
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            //send mail function with attachment
        }

        $mail[] = $this->sentMailattachment($customerno, $mailto, $subject, $messagedata, $path, $filename, $fromemail, $realname, $orderid, $BCC, $validAttachment);
        $message = '';
        if (in_array("sent", $mail)) {
            $message = "Email Sent Successfully";
        } else {
            $message = "Email not sent to alteast one Recipient";
        }
        return $message;
        //to remove alphabates from orderno
        //$pos = strpos($orderno, "-");
        //$replace=$pos+1;
        //$d=  substr($orderno,$replace);
    }

    function ViewOrder($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $arrTemp = array();

//client order pagewise
            try {
                $sp_params = "'" . $pageindex . "'"
                        . ",'" . $pagesize . "'"
                        . ",'" . $customerno . "'"
                        . "," . "@recordCount"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_CLIENTS_ORDER_PAGEWISE, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $row_count = $this->db->num_rows($queryResult);

                if ($row_count > 0) {
                    $arr_data['success'] = 1;
                    while ($row = $this->db->fetch_array($queryResult)) {
                        //$arrTemp['rownum'] = $row['rownum'];
                        $arrTemp['name'] = $row['name'];
                        $arrTemp['totalclientuser'] = $row['userCount'];
                        $arrTemp['orderid'] = $row['orderid'];
                        $arrTemp['orderno'] = $row['orderno'];
                        $arrTemp['modifiedon'] = $row['updatedon'];
                        $arrTemp['stageid'] = $row['stageid'];
                        $arrTemp['stagename'] = $row['stagename'];
                        $arrTemp['stagedescription'] = $row['stagedescription'];
                        $arrTemp['totalactivity'] = $row['activityCount'];
                        $arrTemp['activitydata'] = $this->ViewActivity_ByOrder($arrTemp['orderid'], $customerno);
                        $this->db->next_result();
                        $arr_data['orderdata'][] = $arrTemp;
                    }
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['orderdata'] = '';
                    $arr_data['errormsg'] = 'Order Not Found';
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }

            $queryResult->close();


            $outputVars = $this->db->query('SELECT @recordCount as totalorders', __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($outputVars)) {
                $result = $row['totalorders'];
            }
            $arr_data['totalOrders'] = $result;
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function SearchOrder($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $arrTemp = array();
            //client order search
            try {
                $sp_params = "'" . $clientname . "'"
                        . ",'" . $customerno . "'"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_CLIENT_ORDER_SEARCH, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $row_count = $this->db->num_rows($queryResult);

                if ($row_count > 0) {
                    $arr_data['success'] = 1;
                    while ($row = $this->db->fetch_array($queryResult)) {
                        $arrTemp['name'] = $row['name'];
                        $arrTemp['totalclientuser'] = $row['userCount'];
                        $arrTemp['orderid'] = $row['orderid'];
                        $arrTemp['orderno'] = $row['orderno'];
                        $arrTemp['modifiedon'] = $row['updatedon'];
                        $arrTemp['stageid'] = $row['stageid'];
                        $arrTemp['stagename'] = $row['stagename'];
                        $arrTemp['stagedescription'] = $row['stagedescription'];
                        $arrTemp['totalactivity'] = $row['ActivityCount'];
                        $arr_data['searchdata'][] = $arrTemp;
                    }
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['searchdata'] = '';
                    $arr_data['errormsg'] = 'Client Not Found';
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }

            $queryResult->close();
            $this->db->next_result();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function PullOrderDetails($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $arrTemp = array();
            //client interaction
            try {
                $sp_params = "'" . $orderid . "'"
                        . ",'" . $customerno . "'"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_CLIENT_INTERACTION, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $row_count = $this->db->num_rows($queryResult);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count > 0) {
                $arr_data['success'] = 1;
                while ($row = $this->db->fetch_array($queryResult)) {
                    $arrTemp['recipientid'] = $row['cruid'];
                    $arrTemp['recipientname'] = $row['cruname'];
                    $arrTemp['recipientemail'] = $row['cruemail'];
                    $arrTemp['recipientphone'] = $row['cruphoneno'];
                    $arrTemp['clientid'] = $row['clientid'];
                    $arr_data['recipientdata'][] = $arrTemp;
                }
            } else {

                $arr_data['recipientdata'] = '';
            }
            $queryResult->close();
            $this->db->next_result();
            // get order stage
            try {
                $sp_params3 = "'" . $customerno . "'"
                        . ",'" . $orderid . "'"
                ;

                $QUERY3 = $this->PrepareSP(constants::SP_GET_ORDERS, $sp_params3);
                $queryResult3 = $this->db->query($QUERY3, __FILE__, __LINE__);
                $row_count3 = $this->db->num_rows($queryResult3);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count3 > 0) {

                $row = $this->db->fetch_array($queryResult3);
                $stageid = $row['stageid'];
            }
            $queryResult3->close();
            $this->db->next_result();
            // get attachment order/stage mapping data
            unset($arrTemp);
            $arr_attach = array();
            try {
                $sp_params4 = "'" . $customerno . "'"
                        . ",''"
                        . ",''"
                        . ",'" . $orderid . "'"
                ;

                $QUERY4 = $this->PrepareSP(constants::SP_GET_ATTACHMENTSTAGE_MAPPING, $sp_params4);
                $queryResult4 = $this->db->query($QUERY4, __FILE__, __LINE__);
                $row_count4 = $this->db->num_rows($queryResult4);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count4 > 0) {
                while ($row4 = $this->db->fetch_array($queryResult4)) {
                    $arrTemp['attachid'] = $row4['attachid'];
                    $arr_attach[] = $arrTemp;
                }
            }
            $queryResult4->close();
            $this->db->next_result();

            unset($arrTemp);
            try {
                $sp_params2 = "'" . $customerno . "'"
                        . ",''"
                ;

                $QUERY2 = $this->PrepareSP(constants::SP_GET_ATTACHMENT_MASTER, $sp_params2);
                $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
                $row_count2 = $this->db->num_rows($queryResult2);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count2 > 0) {
                while ($row2 = $this->db->fetch_array($queryResult2)) {
                    $arrTemp['attachid'] = $row2['attachid'];
                    $arrTemp['attachname'] = $row2['attachname'];
                    if (empty($arr_attach)) {
                        $arrTemp['set'] = "no";
                    }
                    if (!empty($arr_attach)) {
                        $arrAttach = array();
                        $cnt = count($arr_attach);

                        for ($i = 0; $i < $cnt; $i++) {
                            $arrAttach[$i] = $arr_attach[$i]['attachid'];
                        }
                        if (in_array($arrTemp['attachid'], $arrAttach)) {
                            $arrTemp['set'] = "yes";
                        } else {
                            $arrTemp['set'] = "no";
                        }
                    }
                    $arr_data['attachdata'] [] = $arrTemp;
                }
            }
            $queryResult2->close();
            $this->db->next_result();
            //stages
            unset($arrTemp);
            try {
                $sp_params1 = "'" . $customerno . "'"
                        . ",''"
                ;

                $QUERY1 = $this->PrepareSP(constants::SP_GET_STAGES, $sp_params1);
                $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
                $row_count1 = $this->db->num_rows($queryResult1);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count1 > 0) {
                while ($row1 = $this->db->fetch_array($queryResult1)) {
                    $arrTemp['stageid'] = $row1['stageid'];
                    $arrTemp['stagename'] = $row1['stagename'];
                    $arrTemp['stagedescription'] = $row1['stagedescription'];
                    if ($stageid == $arrTemp['stageid']) {
                        $arrTemp['currentstage'] = "yes";
                    } else {
                        $arrTemp['currentstage'] = "no";
                    }
                    $arr_data['stagedata'][] = $arrTemp;
                }
            } else {
                //$arr_data['success']=0;
                $arr_data['stagedata'] = '';
            }
            $queryResult1->close();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function EditOrderDetails($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];
            $realname = $userdata['realname'];
            $fromemail = $userdata['email'];
            $today = date("Y-m-d H:i:s");
            $statusMessage = '';
            $msg_sms = '';
            $msg_email = '';
            //$stagename = strtolower(trim($stagename));
            $stagename = preg_replace('/[^a-z\s]/', '', strtolower($stagename));
            $stagename = preg_split('/\s+/', $stagename, NULL, PREG_SPLIT_NO_EMPTY);
            $stagename = array_flip($stagename);
            $word = "quotation";
            $arrTemp = array();
            if ($orderid != '' && $orderid != 0) {
                //get client recipient from client interaction
                try {
                    $sp_params = "'" . $orderid . "'"
                            . ",'" . $customerno . "'"
                    ;

                    $QUERY = $this->PrepareSP(constants::SP_GET_CLIENT_INTERACTION, $sp_params);
                    $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                    $row_count = $this->db->num_rows($queryResult);
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $data = array();
                if ($row_count > 0) {
                    while ($row = $this->db->fetch_array($queryResult)) {
                        $arrTemp['recipientid'] = $row['cruid'];
                        $arrTemp['recipientname'] = $row['cruname'];
                        $arrTemp['recipientemail'] = $row['cruemail'];
                        $arrTemp['recipientphone'] = $row['cruphoneno'];
                        $data[] = $arrTemp;
                    }
                }

                $queryResult->close();
                $this->db->next_result();
                //get order no and stage
                unset($arrTemp);
                try {
                    $sp_params1 = "'" . $customerno . "'"
                            . ",'" . $orderid . "'"
                    ;

                    $QUERY1 = $this->PrepareSP(constants::SP_GET_ORDERS, $sp_params1);
                    $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
                    $row_count1 = $this->db->num_rows($queryResult1);
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                if ($row_count1 > 0) {
                    $row1 = $this->db->fetch_array($queryResult1);
                    $stageid_pre = $row1['stageid'];
                    $stagename_pre = $row1['stagename'];
                    $orderno = $row1['orderno'];

                    $queryResult1->close();
                    $this->db->next_result();
                    //update order
                    try {
                        $sp_params = "" . $orderid . ""
                                . ",''"
                                . ",''"
                                . ",'" . $stageid . "'"
                                . ",''" . ""
                                . ",'" . $requestemail . "'"
                                . ",'" . $requestsms . "'"
                                . ",''" . ""
                                . ",''" . ""
                                . ",'" . $subtotalamount . "'"
                                . ",''" . ""
                                . ",''" . ""
                                . ",'" . $additionalcost . "'"
                                . ",'" . $totalamount . "'"
                                . ",''"
                                . ",'" . $customerno . "'"
                                . ",'" . $userid . "'"
                                . ",'" . $today . "'"
                        ;

                        $QUERY2 = $this->PrepareSP(constants::SP_UPDATE_ORDERS, $sp_params);
                        $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
                    } catch (Exception $ex) {
                        $log = new Log();
                        $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                        $arr_data['status'] = "Exception occured.";
                    }
                    //print_r($attachdata);
                    //die();
                    if (!empty($attachdata)) {
                        //get attachdata for order/stage
                        unset($arrTemp);
                        $arr_attach = array();
                        try {
                            $sp_params4 = "'" . $customerno . "'"
                                    . ",''"
                                    . ",''"
                                    . ",'" . $orderid . "'"
                            ;

                            $QUERY4 = $this->PrepareSP(constants::SP_GET_ATTACHMENTSTAGE_MAPPING, $sp_params4);
                            $queryResult4 = $this->db->query($QUERY4, __FILE__, __LINE__);
                            $row_count4 = $this->db->num_rows($queryResult4);
                        } catch (Exception $ex) {
                            $log = new Log();
                            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                            $arr_data['status'] = "Exception occured.";
                        }
                        if ($row_count4 > 0) {
                            while ($row4 = $this->db->fetch_array($queryResult4)) {
                                $arrTemp['attachid'] = $row4['attachid'];
                                $arrTemp['attachname'] = $row4['attachname'];
                                $arr_attach[] = $arrTemp;
                            }
                        }
                        //print_r($arr_attach);
                        $queryResult4->close();
                        $this->db->next_result();
                        $deleteData = $this->GetArrayDiff($arr_attach, $attachdata);
                        //print_r($deleteData);
                        $insertData = $this->GetArrayDiff($attachdata, $arr_attach);
                        //print_r($insertData);die();
                        if (!empty($deleteData)) {
                            foreach ($deleteData as $deleteDatas) {
                                try {
                                    $sp_params5 = "" . $customerno . ""
                                            . ",''"
                                            . "," . $deleteDatas['attachid'] . ""
                                            . "," . $orderid . ""
                                            . "," . $userid . ""
                                            . ",'" . $today . "'"
                                    ;
                                    $QUERY5 = $this->PrepareSP(constants::SP_DELETE_ATTACHMENTSTAGE_MAPPING, $sp_params5);
                                    $queryResult5 = $this->db->query($QUERY5, __FILE__, __LINE__);
                                } catch (Exception $ex) {
                                    $log = new Log();
                                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                                    $arr_data['status'] = "Exception occured.";
                                }
                            }
                        }
                        if (!empty($insertData)) {
                            foreach ($insertData as $insertDatas) {
                                try {
                                    $sp_params6 = "" . $insertDatas['attachid'] . ""
                                            . "," . $orderid . ""
                                            . "," . $stageid . ""
                                            . "," . $customerno . ""
                                            . "," . $userid . ""
                                            . ",'" . $today . "'"
                                            . "," . $userid . ""
                                            . ",'" . $today . "'"
                                            . "," . '@attachmentmapid'
                                    ;
                                    $QUERY6 = $this->PrepareSP(constants::SP_INSERT_ATTACHMENTSTAGE_MAPPING, $sp_params6);
                                    $queryResult6 = $this->db->query($QUERY6, __FILE__, __LINE__);
                                    $outputVars6 = $this->db->query('SELECT @attachmentmapid as currentattachmentmapid', __FILE__, __LINE__);
                                    $outputVars6->close();
                                    $this->db->next_result();
                                } catch (Exception $ex) {
                                    $log = new Log();
                                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                                    $arr_data['status'] = "Exception occured.";
                                }
                            }
                        }
                        $validAttachment = $this->CheckForAttachment($customerno, $attachdata);
                    }
                    if (isset($stagename[$word])) {
                        $msg_email = $this->EditQuotation_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $data, $validAttachment);
                    } else {
                        $msg_email = $this->EditOrder_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $data, $validAttachment);
                    }


                    if ($requestsms == 1) {
                        $msg_sms = $this->EditOrderSms($value, $data, $customerno);
                    }
                    if ($msg_sms != '') {
                        $statusMessage = $msg_sms;
                    }
                    if ($msg_email != '' && $msg_sms != '') {
                        $statusMessage .= ", " . $msg_email;
                    }
                    if ($msg_email != '' && $msg_sms == '') {
                        $statusMessage .= $msg_email;
                    }
                    if ($msg_email == '' && $msg_sms == '') {
                        $statusMessage = "No Phone Number OR Email ID Found";
                    }
                    $arr_data['success'] = 1;
                    $arr_data['message'] = $statusMessage;
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['message'] = "Order Not Found";
                }
            } else {
                $arr_data['success'] = 0;
                $arr_data['message'] = "Please select an order";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['message'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function GetReminder($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $arrTemp = array();
            try {
                $sp_params = "'" . $customerno . "'"
                        . ",''"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_REMINDERS, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $row_count = $this->db->num_rows($queryResult);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count > 0) {
                $arr_data['success'] = 1;
                while ($row = $this->db->fetch_array($queryResult)) {
                    $arrTemp['reminderid'] = $row['reminderid'];
                    $arrTemp['remindername'] = $row['remindername'];
                    $arr_data['reminderdata'][] = $arrTemp;
                }
            } else {
                $arr_data['success'] = 0;
                $arr_data['errormsg'] = "No Reminders Created";
            }
            $queryResult->close();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function ViewActivity_ByOrder($orderid, $customerno) {
        //extract($value);
        $arr_data = array();
        $this->db->next_result();

        //$customerno = $userdata['customerno'];
        $arrTemp = array();
        try {
            $sp_params = "'" . $customerno . "'"
                    . ",'" . $orderid . "'"
                    . ",''"
                    . ",''"
            ;

            $QUERY = $this->PrepareSP(constants::SP_GET_ACTIVITY, $sp_params);
            $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($queryResult);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($queryResult)) {
                $arrTemp['activityid'] = $row['activityid'];
                //$arrTemp['orderid'] = $row['orderid'];
                //$arrTemp['orderno'] = $row['orderno'];
                $arrTemp['reminderid'] = $row['reminderid'];
                $arrTemp['remindername'] = $row['remindername'];
                //$arrTemp['notes'] = $row['notes'];
                $arrTemp['activitytime'] = date("d-m-Y H:i:s", strtotime($row['activitytime']));
                //$arrTemp['activity_reminder_duration'] = $row['activity_reminder_duration'];
                //$arrTemp['isemailrequested'] = $row['isemailrequested'];
                //$arrTemp['issmsrequested'] = $row['issmsrequested'];
                //$arrTemp['isactivitydone'] = $row['isactivitydone'];
                //$arrTemp['isemailsent'] = $row['isemailsent'];
                //$arrTemp['issmssent'] = $row['issmssent'];
                $arr_data[] = $arrTemp;
            }
        } else {
            $arr_data[] = "No Activity Found For Order";
        }
        $queryResult->close();
        $this->db->next_result();
        //echo json_encode($arr_data);
        return $arr_data;
    }

    function GetActivityDetails($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $arrTemp = array();
            try {
                $sp_params = "'" . $customerno . "'"
                        . ",''"
                        . ",''"
                        . ",'" . $activityid . "'"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_ACTIVITY, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $row_count = $this->db->num_rows($queryResult);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count > 0) {
                $arr_data['success'] = 1;
                while ($row = $this->db->fetch_array($queryResult)) {
                    $arrTemp['activityid'] = $row['activityid'];
                    $arrTemp['orderid'] = $row['orderid'];
                    $arrTemp['orderno'] = $row['orderno'];
                    $arrTemp['reminderid'] = $row['reminderid'];
                    $arrTemp['remindername'] = $row['remindername'];
                    $arrTemp['notes'] = $row['notes'];
                    $arrTemp['activitytime'] = date("d-m-Y H:i:s", strtotime($row['activitytime']));
                    $arrTemp['activity_reminder_duration'] = $row['activity_reminder_duration'];
                    $arrTemp['emailrequested'] = $row['isemailrequested'];
                    $arrTemp['smsrequested'] = $row['issmsrequested'];
                    $arrTemp['isactivitydone'] = $row['isactivitydone'];
                    if ($row['userid'] != 0) {
                        $arrTemp['self'] = 1;
                    } else {
                        $arrTemp['self'] = 0;
                    }
                    $arrTemp['client'] = $row['isclient'];
                    //$arrTemp['isemailsent'] = $row['isemailsent'];
                    //$arrTemp['issmssent'] = $row['issmssent'];
                    $arr_data['activitydata'][] = $arrTemp;
                }
            } else {
                $arr_data['success'] = 0;
                $arr_data['errormsg'] = "No Activity Found";
            }
            $queryResult->close();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function EditActivityDetails($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $today = date("Y-m-d H:i:s");
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];
            $useremail = $userdata['email'];
            $realname = $userdata['realname'];
            $userphone = $userdata['phone'];
            if ($self == 1) {
                $self = $userid;
            }
            $formatactivitytime = date("Y-m-d H:i:s", strtotime($activitytime));
            $arrTemp = array();
            try {
                $sp_params = "'" . $activityid . "'"
                        . ",'" . $orderid . "'"
                        . ",'" . $reminderid . "'"
                        . ",'" . $notes . "'"
                        . ",'" . $formatactivitytime . "'"
                        . ",'" . $reminder_duration . "'"
                        . ",'" . $isactivitydone . "'"
                        . ",'" . $emailrequested . "'"
                        . ",'" . $smsrequested . "'"
                        . ",'" . 0 . "'"
                        . ",'" . 0 . "'"
                        . ",'" . 0.00 . "'"
                        . ",'" . $client . "'"
                        . ",'" . $self . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $userid . "'"
                        . ",'" . $today . "'"

                ;

                $QUERY = $this->PrepareSP(constants::SP_UPDATE_ACTIVITIES, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                //$queryResult->close();
                //$this->db->next_result();
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($queryResult > 0) {
                $arr_data['success'] = 1;

                if ($emailrequested == 1) {

                    $this->sendActivityMail($activityid, $self, $client, $useremail, $customerno, $realname);
                }
                if ($smsrequested == 1) {

                    $this->sendActivitySMS($activityid, $self, $client, $customerno, $userphone);
                }

                //$this->sendActivityMail($activityid, $self, $client, $useremail, $customerno, $realname);

                $arr_data['message'] = "Email Or SMS Sent Successfully";
            } else {
                $arr_data['success'] = 0;
                $arr_data['errormsg'] = "Please Try Again";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function InsertActivityDetails($value) {
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $today = date("Y-m-d H:i:s");
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];
            $useremail = $userdata['email'];
            $realname = $userdata['realname'];
            $userphone = $userdata['phone'];
            if ($self == 1) {
                $self = $userid;
            }
            $formatactivitytime = date("Y-m-d H:i:s", strtotime($activitytime));
            $arrTemp = array();
            try {
                $sp_params = "'" . $orderid . "'"
                        . ",'" . $reminderid . "'"
                        . ",'" . $notes . "'"
                        . ",'" . $formatactivitytime . "'"
                        . ",'" . $reminder_duration . "'"
                        . ",'" . $isactivitydone . "'"
                        . ",'" . $emailrequested . "'"
                        . ",'" . $smsrequested . "'"
                        . ",'" . 0.00 . "'"
                        . ",'" . $client . "'"
                        . ",'" . $self . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $userid . "'"
                        . ",'" . $today . "'"
                        . "," . "@actid"

                ;

                $QUERY = $this->PrepareSP(constants::SP_INSERT_ACTIVITIES, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                $outputVars = $this->db->query('SELECT @actid as currentactid', __FILE__, __LINE__);
                $row_count = $this->db->num_rows($outputVars);
                $activitydata = $this->db->fetch_array($outputVars);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            if ($row_count > 0) {
                $arr_data['success'] = 1;
                $arr_data['activityid'] = $activitydata['currentactid'];

                if ($emailrequested == 1) {

                    $this->sendActivityMail($activityid, $self, $client, $useremail, $customerno, $realname);
                }
                if ($smsrequested == 1) {

                    $this->sendActivitySMS($activityid, $self, $client, $customerno, $userphone);
                }

                $arr_data['message'] = "Email Or SMS Sent Successfully";
            } else {
                $arr_data['success'] = 0;
                $arr_data['activityid'] = 0;
                $arr_data['errormsg'] = "Please Try Again";
            }
            $outputVars->close();
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function sendMail($to, $subject, $content) {
        //$subject = $subject;
// Create email headers
        $headers = '';
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@elixiatech.com\r\n" . "X-Mailer: php";
        //$headers .= "Content-Transfer-Encoding: base64\r\n\r\n";

        if (!@mail($to, $subject, $content, $headers)) {
// message sending failed
            return false;
        }

        return true;
    }

    function sentMailattachment($customerno, $mailto, $subject, $message, $path, $filename, $fromemail, $realname, $orderid, $BCC = null, $validAttachment = null) {
        include_once("../../cron/class.phpmailer.php");
        $file = $path . "/" . $filename;
        $mail = new PHPMailer();

        try {
            $mail->IsMail();
            $mail->ClearAddresses();
            $mail->ClearAllRecipients();
            $mail->ClearAttachments();
            $mail->ClearCustomHeaders();

            if (is_array($mailto)) {
                foreach ($mailto as $mailsto) {
                    $mail->AddAddress($mailsto);
                }
            } else {
                $mail->AddAddress($mailto);
            }
            $mail->From = $fromemail;
            $mail->FromName = $realname;
            $mail->Sender = $fromemail;
            if ($BCC != '' && $BCC != null) {
                $mail->AddCustomHeader("BCC: " . $BCC);
            }
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->IsHtml(true);
            $mail->AddAttachment($file, $filename);
            if (!empty($validAttachment)) {
                foreach ($validAttachment as $validAttachments) {
                    $mail->AddAttachment($validAttachments->filePath, $validAttachments->fileName);
                }
            }

            /* email send process ends */
            //$mail->IsHTML(true);
            //$mail->AddBCC($cc);
            //$mail->AddReplyTo($from,"Elixia Speed");
            //SEND Mail
            if ($mail->Send()) {
                $statusMessage = "sent";
                if (is_array($mailto)) {
                    foreach ($mailto as $mailsto) {
                        $this->insertInemaillog($mailsto, $fromemail, $subject, $message, $orderid, 0, $customerno, $today, 1);
                    }
                } else {
                    $this->insertInemaillog($mailto, $fromemail, $subject, $message, $orderid, 0, $customerno, $today, 1);
                }
            } else {
                $statusMessage = "notsent";
                if (is_array($mailto)) {
                    foreach ($mailto as $mailsto) {
                        $this->insertInemaillog($mailsto, $fromemail, $subject, $message, $orderid, 0, $customerno, "", "-1");
                    }
                } else {
                    $this->insertInemaillog($mailto, $fromemail, $subject, $message, $orderid, 0, $customerno, "", "-1");
                }
            }
        } catch (phpmailerException $e) {
            $statusMessage = "notsent";
            $log = new Log();
            $log->createlog($customerno, $e->errorMessage(), constants::SALES, __FUNCTION__);
        }
        return $statusMessage;
    }

    function sentMailordertemplate($customerno, $mailto, $subject, $message, $fromemail, $realname, $orderid, $validAttachment) {
        include_once("../../cron/class.phpmailer.php");
        $today = date("Y-m-d H:i:s");
        $mail = new PHPMailer();

        try {
            $mail->IsMail();
            $mail->AddAddress($mailto);
            $mail->From = $fromemail;
            $mail->FromName = $realname;
            $mail->Sender = $fromemail;
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->IsHtml(true);
            if (!empty($validAttachment)) {
                foreach ($validAttachment as $validAttachments) {
                    $mail->AddAttachment($validAttachments->filePath, $validAttachments->fileName);
                }
            }

            //SEND Mail
            if ($mail->Send()) {
                $statusMessage = "sent";
                $this->insertInemaillog($mailto, $fromemail, $subject, $message, $orderid, 0, $customerno, $today, 1);
            } else {
                $statusMessage = "notsent";
                $this->insertInemaillog($mailto, $fromemail, $subject, $message, $orderid, 0, $customerno, "", "-1");
            }
        } catch (phpmailerException $e) {
            $statusMessage = "notsent";
            $log = new Log();
            $log->createlog($customerno, $e->errorMessage(), constants::SALES, __FUNCTION__);
        }
        return $statusMessage;
    }

    function sendActivityMail($activityid, $self, $client, $useremail, $customerno, $realname) {
        //get subject ,body
        $this->db->next_result();
        try {
            $sp_params = "'" . $customerno . "'"
                    . ",''"
                    . ",''"
                    . ",'" . $activityid . "'"
            ;

            $QUERY = $this->PrepareSP(constants::SP_GET_ACTIVITY, $sp_params);
            $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($queryResult);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        if ($row_count > 0) {
            $row = $this->db->fetch_array($queryResult);
            $orderid = $row['orderid'];
            $orderno = $row['orderno'];
            $remaindername = $row['remindername'];
            $notes = $row['notes'];
            $activitytime = $row['activitytime'];
            $activity_reminder_duration = $row['activity_reminder_duration'];
            $isactivitydone = $row['isactivitydone'];
        }
        //$this->db->close($queryResult);
        $this->db->next_result();

        $subject = $remaindername . " For Orderno-" . $orderno;
        $message = "<html>";
        $message.="<head>
            </head>
            <body>
            $notes
            <br>
            Regards,
            $realname
        </body></html>";
        $fromemail = $useremail;
        $newtimestamp = strtotime("$activitytime+ $activity_reminder_duration minute");
        $sent_datetime = date('Y-m-d H:i:s', $newtimestamp);
        //check if present in email log
        try {
            $sp_params1 = "'" . $orderid . "'"
                    . ",'" . $activityid . "'"
                    . ",'" . $customerno . "'"
            ;

            $QUERY1 = $this->PrepareSP(constants::SP_GET_EMAILLOG, $sp_params1);
            $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
            $row_count1 = $this->db->num_rows($queryResult1);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        //$this->db->close($queryResult1);
        //$this->db->next_result();

        if ($row_count1 > 0) {
            //if present for given avtivity then delete previous insert new
            $this->db->next_result();
            try {
                $sp_params2 = "'" . $orderid . "'"
                        . ",'" . $activityid . "'"
                        . ",'" . $customerno . "'"
                ;

                $QUERY2 = $this->PrepareSP(constants::SP_DELETE_EMAILLOG, $sp_params2);
                $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
        }
        if ($self != 0 && $client == 0) {
            //insert in emaillog
            $this->insertInemaillog($useremail, $fromemail, $subject, $message, $orderid, $activityid, $customerno, $sent_datetime);
        } elseif ($client != 0 && $self == 0) {
            // get all recipient from client interaction
            $recdata = $this->getRecipientForOrder($orderid, $customerno);
            foreach ($recdata as $recdatas) {
                $this->insertInemaillog($recdatas['cruemail'], $fromemail, $subject, $message, $orderid, $activityid, $customerno, $sent_datetime);
            }
        } else {// sent to all
            $this->insertInemaillog($useremail, $fromemail, $subject, $message, $orderid, $activityid, $customerno, $sent_datetime);

            $alluserdata = $this->getRecipientForOrder($orderid, $customerno);
            foreach ($alluserdata as $alluserdatas) {
                $this->insertInemaillog($alluserdatas['cruemail'], $fromemail, $subject, $message, $orderid, $activityid, $customerno, $sent_datetime);
            }
        }
    }

    function sendActivitySMS($activityid, $self, $client, $customerno, $userphone) {
        $this->db->next_result();
//get subject ,body
        try {
            $sp_params = "'" . $customerno . "'"
                    . ",''"
                    . ",''"
                    . ",'" . $activityid . "'"
            ;

            $QUERY = $this->PrepareSP(constants::SP_GET_ACTIVITY, $sp_params);
            $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($queryResult);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        if ($row_count > 0) {
            $row = $this->db->fetch_array($queryResult);
            $orderid = $row['orderid'];
            $orderno = $row['orderno'];
            $remaindername = $row['remindername'];
            $notes = $row['notes'];
            $activitytime = $row['activitytime'];
            $activity_reminder_duration = $row['activity_reminder_duration'];
            $isactivitydone = $row['isactivitydone'];
        }
        //$this->db->close($queryResult);
        $this->db->next_result();

        $subject = $remaindername . " For Orderno-" . $orderno;
        $message = "";
        $message.=$subject . "\r\n" . $notes . "\r\n";
        $newtimestamp = strtotime("$activitytime+ $activity_reminder_duration minute");
        $sent_datetime = date('Y-m-d H:i:s', $newtimestamp);
        //check if present in email log
        try {
            $sp_params1 = "'" . $orderid . "'"
                    . ",'" . $activityid . "'"
                    . ",'" . $customerno . "'"
            ;

            $QUERY1 = $this->PrepareSP(constants::SP_GET_SMSLOG, $sp_params1);
            $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
            $row_count1 = $this->db->num_rows($queryResult1);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        //$this->db->close($queryResult1);
        //$this->db->next_result();

        if ($row_count1 > 0) {
            //if present for given avtivity then delete previous insert new
            $this->db->next_result();
            try {
                $sp_params2 = "'" . $orderid . "'"
                        . ",'" . $activityid . "'"
                        . ",'" . $customerno . "'"
                ;

                $QUERY2 = $this->PrepareSP(constants::SP_DELETE_SMSLOG, $sp_params2);
                $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
        }
        if ($self != 0 && $client == 0) {
            //insert in smslog
            $this->insertInsmslog($userphone, $message, $orderid, $activityid, $customerno, $sent_datetime);
        } elseif ($client != 0 && $self == 0) {
            // get all recipient from client interaction
            $recdata = $this->getRecipientForOrder($orderid, $customerno);
            foreach ($recdata as $recdatas) {

                $this->insertInsmslog($recdatas['cruphoneno'], $subject, $message, $orderid, $activityid, $customerno, $sent_datetime);
            }
        } else {// sent to all
            $this->insertInsmslog($userphone, $message, $orderid, $activityid, $customerno, $sent_datetime);

            $alluserdata = $this->getRecipientForOrder($orderid, $customerno);
            foreach ($alluserdata as $alluserdatas) {

                $this->insertInsmslog($alluserdatas['cruphoneno'], $message, $orderid, $activityid, $customerno, $sent_datetime);
            }
        }
    }

    function getRecipientForOrder($orderid, $customerno) {
        $this->db->next_result();
        $temp_arr = Array();
        try {
            $sp_params1 = "'" . $orderid . "'"
                    . ",'" . $customerno . "'"
            ;

            $QUERY1 = $this->PrepareSP(constants::SP_GET_CLIENT_INTERACTION, $sp_params1);
            $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
            $row_count1 = $this->db->num_rows($queryResult1);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        if ($row_count1 > 0) {
            while ($row1 = $this->db->fetch_array($queryResult1)) {
                $ci['cruname'] = $row1['cruname'];
                $ci['cruemail'] = $row1['cruemail'];
                $ci['cruphoneno'] = $row1['cruphoneno'];
                $temp_arr[] = $ci;
            }
        }
        //$this->db->close($queryResult1);
        return $temp_arr;
    }

    function get_otp_forgotpwd($value) {
        $customermanager = new CustomerManager();
        $smsStatus=new SmsStatus();
        $todaysdate = date('Y-m-d H:i:s');
        $otpparam = '';
        extract($value);
        $arr_data = array();
        $arr_data['success'] = 0;
        $arr_data['errormsg'] = "Invalid Credentials";

        //Prepare parameters
        $sp_params = "'" . $username . "'"
                . ",'" . $todaysdate . "'"
                . "," . "@userexists" . "";
        $sqlCallSP = $this->PrepareSP(constants::$SP_SPEED_FORGOT_PASSWORD, $sp_params);
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
                $arr_data['success'] = 0;
                $arr_data['errormsg'] = "Your otp request limit exceeded today.";
            } else {
                $isSMSSent = 0;
                $isEmailSent = 0;
                $statusMessage = '';
                $emailMessage = '';
                $smsMessage = '';
                $message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                if (!empty($phone)) {
                    $smsStatus->customerno=$customerno;
                    $smsStatus->userid=$userid;
                    $smsStatus->vehicleid= 0;
                    $smsStatus->mobileno=$phone;
                    $smsStatus->message=$message;
                    $smsStatus->cqid=0;
                    $smscount = $customermanager->getSMSStatus($smsStatus);
                    if ($smscount == 0) {
                        $response = '';
                        $moduleid=7;
                        $isSMSSent = sendSMSUtil($phone, $message, $response);
                        if ($isSMSSent == 1) {
                            $customermanager->sentSmsPostProcess($customerno,$phone,$message,$response,$isSMSSent,$userid,0,$moduleid);
                            $smsMessage = "OTP Number SMS sent successfully. ";
                        } else {
                            $smsMessage = "OTP Number SMS sending failed";
                        }
                    }
                }
                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $body = '';
                    $body = $message;
                    $body .= '<br/>Please login on your ElixiaSpeed Mobile App with your username and mentioned OTP.<br/><br/>';
                    $subject = "ElixiaSpeed Forgot Password OTP";
                    $ToMailId = $email;

                    $isEmailSent = $this->sendMail($ToMailId, $subject, $body);
                    if ($isEmailSent) {
                        $emailMessage = "OTP Number Email sent successfully";
                        //$statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                    } else {
                        $emailMessage = "OTP Number Email sending failed";
                        //$statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                    }
                }
                if ($smsMessage != '') {
                    $statusMessage = $smsMessage;
                }
                if ($emailMessage != '') {
                    $statusMessage .= "," . $emailMessage;
                }
                if ($smsMessage == '' && $emailMessage == '') {
                    $statusMessage = "No Phone Number/Email ID Found";
                }
                $arr_data['success'] = 1;
                $arr_data['errormsg'] = $statusMessage;
            }
        }
        echo json_encode($arr_data);
        return json_encode($arr_data);
    }

    function update_password($value) {
        extract($value);
        $arr_data = array();
        //echo $newpwd ;die("--");
        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $newpwd . "'"
                    . ",'" . $userkey . "'"
                    . ",'" . $todaysdate . "'";

            $queryCallSP = $this->PrepareSP(constants::$SP_UPDATE_NEWFORGOTPASSWORD, $sp_params);
            $result = $this->db->query($queryCallSP, __FILE__, __LINE__);
            if ($result === true) {
                $arr_data['status'] = "successful";
                $arr_data['message'] = "update password successful.";
            } else {
                $arr_data['status'] = "unsuccessful";
                $arr_data['message'] = "update password failed.";
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return json_encode($arr_data);
    }

    function insertInemaillog($toemail, $fromemail, $subject, $message, $orderid, $activityid, $customerno, $sent_datetime, $isemailsent = null) {
        $this->db->next_result();
        if ($isemailsent == null) {
            $isemailsent = 0;
        }
        $today = date("Y-m-d H:i:s");
        try {
            $sp_params1 = "'" . $toemail . "'"
                    . ",'" . $fromemail . "'"
                    . ",'" . $subject . "'"
                    . ",'" . $message . "'"
                    . ",'" . $orderid . "'"
                    . ",'" . $activityid . "'"
                    . ",'" . $customerno . "'"
                    . ",'" . $isemailsent . "'"
                    . ",'" . $sent_datetime . "'"
                    . ",'" . $today . "'"
                    . "," . "@emailid"

            ;

            $QUERY1 = $this->PrepareSP(constants::SP_INSERT_EMAILLOG, $sp_params1);
            $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
            $outVars = $this->db->query('SELECT @emailid as currentemailid', __FILE__, __LINE__);
            $row_count1 = $this->db->num_rows($outVars);
            $outs = $this->db->fetch_array($outVars);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
    }

    function insertInsmslog($mobileno, $message, $orderid, $activityid, $customerno, $sent_datetime) {

        $this->db->next_result();
        $today = date("Y-m-d H:i:s");
        try {
            $sp_params1 = "'" . $mobileno . "'"
                    . ",'" . $message . "'"
                    . ",'" . $orderid . "'"
                    . ",'" . $activityid . "'"
                    . ",'" . $sent_datetime . "'"
                    . ",'" . $customerno . "'"
                    . ",'" . $today . "'"
                    . ",''"
                    . "," . "@smsid"

            ;

            $QUERY1 = $this->PrepareSP(constants::SP_INSERT_SMSLOG, $sp_params1);
            $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
            $outVars = $this->db->query('SELECT @smsid as currentsmsid', __FILE__, __LINE__);
            $row_count1 = $this->db->num_rows($outVars);
            $outs = $this->db->fetch_array($outVars);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
    }

    function searchForId($id, $array, $array_col) {
        foreach ($array as $key => $val) {
            if ($val[$array_col] === $id) {
                return $key;
            }
        }
        return null;
    }

    public function add_supportapi($value) {  //add ticket support api
        extract($value);
        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $today = date("Y-m-d");
            $customerno = $userdata['customerno'];
            $userid = $userdata['userid'];
            $useremail = $userdata['email'];
            $realname = $userdata['realname'];
            $userphone = $userdata['phone'];
            $today1 = date("Y-m-d H:i:s");
            // $today = date("Y-m-d");
            //$ticket_title = $adddata["issuetitle"];
            //$estimateddate = $adddata["estimateddate"];
            $estimateddate1 = date("Y-m-d", strtotime($estimateddate));
            //$timeslot = $adddata["timeslot"];
            //$vehicleid = $adddata["vehicleid"];
            //$tickettype = $adddata["tickettype"];
            //$ticket_details = $adddata["notes_support"];
            //$priority = $adddata["priority"];
            //$customerno = $adddata["customerno"];
            //$companyname = $adddata["companyname"];
            if ($ticket_type != '1' || $ticket_type != '5' || $ticket_type != '6') {
                if ($estimateddate1 <= $today) {
                    $arr_data['success'] = 0;
                    $arr_data['message'] = 'Please select other date';
                }


                if ($estimateddate1 == "01-01-1970") {
                    $estimateddate1 = "";
                }

                $sql = sprintf("select rel_manager,rm.teamid,rm.manager_name,manager_mobile,manager_email from " . SPEEDDB . ".customer cu
                                INNER JOIN " . SPEEDDB . ".relationship_manager  as rm ON  cu.rel_manager = rm.rid
                                where customerno=" . $customerno);

                $result = $this->db->query($sql, __FILE__, __LINE__);
                if ($this->db->num_rows($result) > 0) {
                    $row = $this->db->fetch_array($result);
                    $rel_manager = $row["rel_manager"];
                    $allot_to = $row["teamid"];
                    $crmemail = $row["manager_email"];
                } else {
                    $rel_manager = 0;
                    $allot_to = 0;
                    $crmemail = "support@elixiatech.com";
                }

                $sql1 = sprintf("INSERT INTO " . SPEEDDB . ".sp_ticket(
       `title` ,
       `ticket_type` ,
       `estimateddate`,
       `customerid` ,
        `priority`,
       `create_on_date`,
       `create_by`,
       `created_type`,
       `crmid`,
       `send_mail_to`

       )
       VALUES (
        '%s', %d,'%s',%d,%d,'%s','%d','%d','%d','%s'
       );", $title, $ticket_type, $estimateddate1, $customerno, $priority, $today1, $userid, 1, $rel_manager, $useremail);
                $result1 = $this->db->query($sql1, __FILE__, __LINE__);
                $ticketid1 = $this->db->last_insert_id();


                $sql2 = sprintf("INSERT INTO " . SPEEDDB . ".sp_ticket_details(
    `ticketid` ,
    `description`,
    `allot_to`,
    `status`,
    `create_by`,
    `create_on_time`,
    `created_type`,
    `userid`,
    `is_custupdated`
    )
    VALUES (
     '%d', '%s', '%d', '%d','%d','%s','%d','%d','%d'
    );", $ticketid1, $ticket_description, $allot_to, 0, $userid, $today1, 1, $userid, 1);

                $result2 = $this->db->query($sql2, __FILE__, __LINE__);
                $ticket_detailid = $this->db->last_insert_id();
                if (isset($ticket_detailid) && $ticket_detailid != 0) {
                    $mail_msg = "";
                    //send Mail to CRM
                    $content = "";
                    $to = $crmemail;
                    $subject = "Ticket Raised By Customer -(" . $customerno . "-" . $realname . ")";
                    $content .= "<html>";
                    $content .= "<body>";
                    $content .= "<b>Ticket Id : </b> SUP00" . $ticketid1 . "\n";
                    $content .= "<b>Module : SALES Engage </b>";
                    $content .= "<b>" . $title . "</b>\n";
                    $content .= "<b>Ticket Type :</b>" . $this->gettickettypevalue($ticket_type) . "\n";
                    if ($priority != '' && $priority == 0) {
                        $content .= "<b>Ticket Priority :</b>" . $this->getpriorityvalue($priority) . "\n";
                    }
                    $content .= "</body>";
                    $content .= "</html>";
                    $content .= $ticket_description;
                    $resp = $this->sendMail($to, $subject, $content);
                    if ($resp === true) {
                        $mail_msg = "Email Send";
                    } else {
                        $mail_msg = "Email Not Send";
                    }
                    $ticketid = "SUP00" . $ticketid1;
                    $arr_data['success'] = 1;
                    $arr_data ['ticketid'] = $ticketid;
                    $arr_data ['message'] = "Support Ticket Created And " . $mail_msg;
                } else {
                    $arr_data['success'] = 0;
                    $arr_data ['ticketid'] = 0;
                    $arr_data ['message'] = "Support Ticket Not Created";
                }
            } else {
                $arr_data['success'] = 0;
                $arr_data['message'] = 'Please Select Other Ticket Type';
            }
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    function gettickettypevalue($id) {
        $Query = "SELECT * FROM " . SPEEDDB . ".sp_tickettype where typeid = '" . $id . "' AND isdeleted = 0";
        $typeQuery = sprintf($Query);
        $res = $this->db->query($typeQuery, __FILE__, __LINE__);
        $tickettype = "";
        if ($this->db->num_rows($res) > 0) {
            while ($row = $this->db->fetch_array($res)) {
                $tickettype = $row['tickettype'];
            }
        }
        return $tickettype;
    }

    function getpriorityvalue($id) {
        $Query = "SELECT * FROM " . SPEEDDB . ".sp_priority where prid='" . $id . "' AND isdeleted = 0";
        $priorQuery = sprintf($Query);
        $res = $this->db->query($priorQuery, __FILE__, __LINE__);
        $priority = "";
        if ($this->db->num_rows($res) > 0) {
            while ($row = $this->db->fetch_array($res)) {
                $priority = $row['priority'];
            }
        }
        return $priority;
    }

    function getAlltickettypevalue() {
        $arr_data = array();
        $arr_temp = array();
        $Query = "SELECT * FROM " . SPEEDDB . ".sp_tickettype where isdeleted = 0";
        $typeQuery = sprintf($Query);
        $res = $this->db->query($typeQuery, __FILE__, __LINE__);
        if ($this->db->num_rows($res) > 0) {
            while ($row = $this->db->fetch_array($res)) {
                if ($row['typeid'] == 2 || $row['typeid'] == 3 || $row['typeid'] == 4) {
                    continue;
                }
                $arr_temp['typeid'] = $row['typeid'];
                $arr_temp['tickettype'] = $row['tickettype'];
                $arr_data[] = $arr_temp;
            }
        }
        return $arr_data;
    }

    function getAllpriorityvalue() {
        $arr_data = array();
        $arr_temp = array();
        $Query = "SELECT * FROM " . SPEEDDB . ".sp_priority where isdeleted = 0";
        $priorQuery = sprintf($Query);
        $res = $this->db->query($priorQuery, __FILE__, __LINE__);
        if ($this->db->num_rows($res) > 0) {
            while ($row = $this->db->fetch_array($res)) {
                $arr_temp['prid'] = $row['prid'];
                $arr_temp['priority'] = $row['priority'];
                $arr_data[] = $arr_temp;
            }

            return $arr_data;
        }
    }

    function prepareOrder_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $validAttachment) {
        extract($value);
        $today = date("d-m-Y");
        $arrTemp = array();
        //email body
        if ($requestemail == 1) {
            try {

                $sp_params9 = "'" . $customerno . "'"
                        . ",'" . $stageid . "'"
                ;
                $QUERY9 = $this->PrepareSP(constants::SP_GET_STAGES, $sp_params9);
                $queryResult9 = $this->db->query($QUERY9, __FILE__, __LINE__);
                $row9 = $this->db->fetch_array($queryResult9);
                $messagedata = $row9['emailtemplate'];
                $subject = $row9['email_subject'];
                $attach1 = $row9['attatch1'];
                $attach2 = $row9['attatch2'];
                $attach3 = $row9['attatch3'];
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            $queryResult9->close();
            $this->db->next_result();
            //$subject .="#" . $orderno;
            $messagedata = str_replace("{{SENDERNAME}}", "$realname", "$messagedata");
            //$messagedata = str_replace("{{COMPANYNAME}}", "$custcomapanyname", "$messagedata");
            //send mail to
            //$path = $dest3;
            //$filename = "../../../customer/$customerno/salesengage/company_profile/Company Profile.pdf";
            foreach ($recipientdata as $recipientdatas) {
                try {

                    $sp_params10 = "'" . $customerno . "'"
                            . ",''"
                            . ",'" . $recipientdatas['recipientid'] . "'"
                    ;
                    $QUERY10 = $this->PrepareSP(constants::SP_GET_CLIENTRECIPIENTUSERS, $sp_params10);
                    $queryResult10 = $this->db->query($QUERY10, __FILE__, __LINE__);
                    $row10 = $this->db->fetch_array($queryResult10);
                    $mailto = $row10['cruemail'];
                    $recname = $row10['cruname'];
                    $recphone = $row10['cruphoneno'];
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                $queryResult10->close();
                $this->db->next_result();

                //insert in clinent interaction table
                try {

                    $sp_params11 = "'" . $clientid . "'"
                            . ",'" . $recipientdatas['recipientid'] . "'"
                            . ",'" . $stageid . "'"
                            . ",'" . $orderid . "'"
                            . ",'" . $customerno . "'"
                            . ",'" . $userid . "'"
                            . ",'" . date("Y-m-d H:i:s") . "'"
                            . "," . "@ciid"
                    ;
                    $QUERY11 = $this->PrepareSP(constants::SP_INSERT_CLIENT_INTERACTION, $sp_params11);
                    $queryResult11 = $this->db->query($QUERY11, __FILE__, __LINE__);
                    $outputVars11 = $this->db->query('SELECT @ciid as currentciid', __FILE__, __LINE__);
                    $row_count11 = $this->db->num_rows($outputVars11);
                    $client_interaction = $this->db->fetch_array($outputVars11);
                    $outputVars11->close();
                    $this->db->next_result();
                } catch (Exception $ex) {
                    $log = new Log();
                    $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                    $arr_data['status'] = "Exception occured.";
                }
                //send mail function with attachment
                $mail[] = $this->sentMailordertemplate($customerno, $mailto, $subject, $messagedata, $fromemail, $realname, $orderid, $validAttachment);
            }
        }
        $message = '';
        if (in_array("sent", $mail)) {
            $message = "Email Sent Successfully";
        } else {
            $message = "Email not sent to alteast one Recipient";
        }
        return $message;
    }

    function EditQuotation_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $data, $validAttachment) {
        extract($value);
        $today1 = date("d-m-Y");
        $today = date("Y-m-d H:i:s");
        $arrTemp = array();
        $tnc = array();
        //insert in orderproduct_mapping
        foreach ($productdata as $productdatas) {
            try {
                $sp_params1 = "'" . $orderid . "'"
                        . ",'" . $productdatas['productid'] . "'"
                        . ",'" . $productdatas['unit_price'] . "'"
                        . ",'" . $productdatas['quantity'] . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $userid . "'"
                        . ",'" . $today . "'"
                        . "," . "@opmid"
                ;
                $QUERY1 = $this->PrepareSP(constants::SP_INSERT_ORDERPRODUCT_MAPPING, $sp_params1);
                $queryResult1 = $this->db->query($QUERY1, __FILE__, __LINE__);
                $outputVars1 = $this->db->query('SELECT @opmid as currentopmid', __FILE__, __LINE__);
                $row_count1 = $this->db->num_rows($outputVars1);
                $productinorder[] = $this->db->fetch_array($outputVars1);
                $outputVars1->close();
                $this->db->next_result();
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
        }

        if ($taxid != '' && $taxamount != '' && $taxid != '0' && $taxamount != '0') {
            // insert in ordertax mapping
            $sp_params2 = "" . $orderid . ""
                    . ",'" . $taxid . "'"
                    . ",'" . $taxamount . "'"
                    . ",'" . $customerno . "'"
                    . ",'" . $userid . "'"
                    . ",'" . $today . "'"
                    . "," . '@otmid'
            ;

            $QUERY2 = $this->PrepareSP(constants::SP_INSERT_ORDERTAXMAPPING, $sp_params2);
            $queryResult2 = $this->db->query($QUERY2, __FILE__, __LINE__);
            $outputVars2 = $this->db->query('SELECT @otmid as currentotmid', __FILE__, __LINE__);
            $row_count2 = $this->db->num_rows($outputVars2);
            $outputVars2->close();
            $this->db->next_result();
        }
        if ($additionalcostname != '' && $additionalcost != '' && $additionalcostname != '0' && $additionalcost != '0') {
            // insert in ORDERADDITIONALCOST mapping
            $sp_params3 = "" . $orderid . ""
                    . ",'" . $additionalcostname . "'"
                    . ",'" . $additionalcost . "'"
                    . ",'" . $customerno . "'"
                    . ",'" . $userid . "'"
                    . ",'" . $today . "'"
                    . "," . '@cmid'

            ;
            $QUERY3 = $this->PrepareSP(constants::SP_INSERT_ORDERADDITIONALCOST_MAPPING, $sp_params3);
            $queryResult3 = $this->db->query($QUERY3, __FILE__, __LINE__);
            $outputVars3 = $this->db->query('SELECT @cmid as currentcmid', __FILE__, __LINE__);
            $row_count3 = $this->db->num_rows($outputVars3);
            $outputVars3->close();
            $this->db->next_result();
        }
        // get template attachment from quotation
        try {
            $sp_params11 = "'" . $customerno . "'"
                    . ",''"
                    . ",'" . $stageid . "'"
            ;
            $QUERY11 = $this->PrepareSP(constants::SP_GET_TEMPLATES, $sp_params11);
            $queryResult11 = $this->db->query($QUERY11, __FILE__, __LINE__);
            $row11 = $this->db->fetch_array($queryResult11);
            $templatedata = $row11['attach_template'];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        $queryResult11->close();
        $this->db->next_result();

        //check and create required folders on server to store attachment
        $dest = "../../../customer";
        $dest1 = "../../../customer/salesengage";
        $dest2 = "../../../customer/$customerno/salesengage/";
        $dest3 = "../../../customer/$customerno/salesengage/clientno/$clientid";
        if (!file_exists($dest1)) {
            mkdir($dest1, 0777, true);
        }
        if (!file_exists($dest2)) {
            mkdir($dest2, 0777, true);
        }
        if (!file_exists($dest3)) {
            mkdir($dest3, 0777, true);
        }
//address of client
        try {
            $sp_params = "'" . $customerno . "'"
                    . ",'" . $clientid . "'"
            ;
            $QUERY = $this->PrepareSP(constants::SP_GET_ADDRESS, $sp_params);
            $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }

        $row = $this->db->fetch_array($queryResult);
        $name = $row['name'];
        $comapanyname = $row['clientcompanyname'];
        $line1 = $row['line1'];
        $line2 = $row['line2'];
        $line3 = $row['line3'];
        $pincode = $row['pincode'];
        $city = $row['city'];

        $queryResult->close();
        $this->db->next_result();
//tax rate
        try {
            $sp_params5 = "'" . $customerno . "'"
                    . ",'" . $taxid . "'"
            ;
            $QUERY5 = $this->PrepareSP(constants::SP_GET_TAX, $sp_params5);
            $queryResult5 = $this->db->query($QUERY5, __FILE__, __LINE__);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }

        $row5 = $this->db->fetch_array($queryResult5);
        $taxrate = $row5['taxpercent'] . '%';
        $queryResult5->close();
        $this->db->next_result();

        $templatedata = str_replace("{{CLIENTCOMPANY}}", "$comapanyname", "$templatedata");
        $templatedata = str_replace("{{CLIENTLINE1}}", "$line1", "$templatedata");
        $templatedata = str_replace("{{CLIENTLINE2}}", "$line2", "$templatedata");
        $templatedata = str_replace("{{CLIENTLINE3}}", "$line3", "$templatedata");
        $templatedata = str_replace("{{CLIENTPINCODE}}", "$pincode", "$templatedata");
        $templatedata = str_replace("{{CLIENTCITY}}", "$city", "$templatedata");
        $templatedata = str_replace("{{CUSTOMERNO}}", "$customerno", "$templatedata");
        $templatedata = str_replace("{{DATE}}", "$today1", "$templatedata");
        $templatedata = str_replace("{{ORDER NO}}", "$orderno", "$templatedata");
        $templatedata = str_replace("{{SUB TOTAL}}", "$subtotalamount", "$templatedata");
        $templatedata = str_replace("{{TOTAL}}", "$totalamount", "$templatedata");
        $templatedata = str_replace("{{ADDITION COST}}", "$additionalcost", "$templatedata");
        $templatedata = str_replace("{{TAX AMOUNT}}", "$taxamount", "$templatedata");
        $templatedata = str_replace("{{TAX RATE}}", "$taxrate", "$templatedata");

//tnc chunk mapping and chunk
        $x = 1;
        $description = "";
        foreach ($termsandconditiondata as $termsandconditiondatas) {
            $total = "";

            try {
                $sp_params12 = "'" . $termsandconditiondatas['chunkid'] . "'"
                        . ",'" . $customerno . "'"
                        . ",''"
                ;
                $QUERY12 = $this->PrepareSP(constants::SP_GET_TERMSANDCONDITION_CHUNKMAPPING, $sp_params12);
                $queryResult12 = $this->db->query($QUERY12, __FILE__, __LINE__);


                while ($row12 = $this->db->fetch_array($queryResult12)) {

                    $description .= $x . ")" . $row12['tandc_description'] . "<br>";

                    $x++;
                }

                $queryResult12->close();
                $this->db->next_result();
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
        }

        $templatedata = str_replace("{{TNCDATA}}", "$description", "$templatedata");

        $product_desc = "";
        $quantity = "";
        $unitprice = "";
        $totalamt = "";

        foreach ($productdata as $productdatas) {
            $productname = "";
            $total = "";
            try {

                $sp_params3 = "" . $customerno . ""
                        . ",''"
                        . ",'" . $productdatas['productid'] . "'"
                ;
                $QUERY3 = $this->PrepareSP(constants::SP_GET_PRODUCT_MASTER, $sp_params3);
                $queryResult3 = $this->db->query($QUERY3, __FILE__, __LINE__);

                $row3 = $this->db->fetch_array($queryResult3);

                $productname = $row3['productname'];
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }

            $product_desc.=$productname . "<br>";
            $quantity.=$productdatas['quantity'] . "<br>";
            $unitprice.=$productdatas['unit_price'] . "<br>";
            (float) $total = (float) $productdatas['unit_price'] * (float) $productdatas['quantity'] . "<br>";
            $totalamt.=$total;

            $queryResult3->close();
            $this->db->next_result();
        }
        $templatedata = str_replace("{{DESCRIPTION}}", "$product_desc", "$templatedata");
        $templatedata = str_replace("{{QUANTITY}}", "$quantity", "$templatedata");
        $templatedata = str_replace("{{PERUNITPRICE}}", "$unitprice", "$templatedata");
        $templatedata = str_replace("{{TOTALAMOUNT}}", "$totalamt", "$templatedata");

// CUSTOMER_PROFILE
        try {

            $sp_params4 = "" . $customerno . ""
                    . ",''"

            ;
            $QUERY4 = $this->PrepareSP(constants::SP_GET_CUSTOMER_PROFILE, $sp_params4);
            $queryResult4 = $this->db->query($QUERY4, __FILE__, __LINE__);

            $row4 = $this->db->fetch_array($queryResult4);
            $custcomapanyname = $row4['companyname'];
            $companycontactperson = $row4['companycontactperson'];
            $companytelephoneno = $row4['companytelephoneno'];
            $companymobileno = $row4['companymobileno'];
            $companyemail = $row4['companyemail'];
            $companylogo = $row4['companylogo'];
            $custline1 = $row4['line1'];
            $custline2 = $row4['line2'];
            $custline3 = $row4['line3'];
            $custpincode = $row4['pincode'];
            $custcity = $row4['city'];
            $custstate = $row4['state'];
            $custcountry = $row4['country'];
            $validity = $row4['validity'];
            $approvedby = $row4['approvedby'];
            $bcc = $row4['bcc'];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        $queryResult4->close();
        $this->db->next_result();

        $templatedata = str_replace("{{YOUR COMPANY NAME}}", "$custcomapanyname", "$templatedata");
        $templatedata = str_replace("{{LINE1}}", "$custline1", "$templatedata");
        $templatedata = str_replace("{{LINE2}}", "$custline2", "$templatedata");
        $templatedata = str_replace("{{LINE3}}", "$custline3", "$templatedata");
        $templatedata = str_replace("{{PINCODE}}", "$custpincode", "$templatedata");
        $templatedata = str_replace("{{CITY}}", "$custcity", "$templatedata");
        $templatedata = str_replace("{{STATE}}", "$custstate", "$templatedata");
        $templatedata = str_replace("{{COUNTRY}}", "$custcountry", "$templatedata");
        $templatedata = str_replace("{{PHONE}}", "$companytelephoneno", "$templatedata");
        $templatedata = str_replace("{{EMAIL}}", "$companyemail", "$templatedata");

        $templatedata = str_replace("{{PREPAREBYUSER}}", "$realname", "$templatedata");

        if ($validity == 0) {
            $val = 7; //set default to 7 days
        } else {
            $val = $validity;
        }

        $valdate = date("d-m-Y", strtotime("+$val days"));
        $userapproval = '';
        if ($approvedby == "") {
            $userapproval = $companycontactperson;
        } else {
            $userapproval = $approvedby;
        }
        $templatedata = str_replace("{{APPROVEDBYUSER}}", "$userapproval", "$templatedata");
        $templatedata = str_replace("{{VALIDDATE}}", "$valdate", "$templatedata");

//pdf
        require_once('../../reports/html2pdf.php');
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($templatedata);
//$html2pdf->Output($templatedata . "_" . date("d-m-Y") . "generate.pdf");
            $html2pdf->Output($dest3 . "/quotation_$orderno.pdf", 'F');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }


        //email body
        try {

            $sp_params9 = "'" . $customerno . "'"
                    . ",'" . $stageid . "'"
            ;
            $QUERY9 = $this->PrepareSP(constants::SP_GET_STAGES, $sp_params9);
            $queryResult9 = $this->db->query($QUERY9, __FILE__, __LINE__);
            $row9 = $this->db->fetch_array($queryResult9);
            $messagedata = $row9['emailtemplate'];
            $subject = $row9['email_subject'];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        $queryResult9->close();
        $this->db->next_result();
        $messagedata = str_replace("{{SENDERNAME}}", "$realname", "$messagedata");
        $messagedata = str_replace("{{COMPANYNAME}}", "$custcomapanyname", "$messagedata");
        //send mail to
        $path = $dest3;
        $filename = "quotation_$orderno.pdf";
        $BCC = isset($bcc) ? $bcc : '';
        foreach ($data as $datas) {
            $toemail[] = $datas['recipientemail'];
            //send mail function with attachment
        }

        $mail[] = $this->sentMailattachment($customerno, $toemail, $subject, $messagedata, $path, $filename, $fromemail, $realname, $orderid, $BCC, $validAttachment);

        $message = '';
        if (in_array("sent", $mail)) {
            $message = "Email Sent Successfully";
        } else {
            $message = "Email not sent to alteast one Recipient";
        }
        return $message;
    }

    function EditOrder_template($value, $orderid, $orderno, $realname, $customerno, $userid, $fromemail, $data, $validAttachment) {
        extract($value);
        $today = date("d-m-Y");
        $arrTemp = array();
        if ($requestemail == 1) {
//email body
            try {

                $sp_params9 = "'" . $customerno . "'"
                        . ",'" . $stageid . "'"
                ;
                $QUERY9 = $this->PrepareSP(constants::SP_GET_STAGES, $sp_params9);
                $queryResult9 = $this->db->query($QUERY9, __FILE__, __LINE__);
                $row9 = $this->db->fetch_array($queryResult9);
                $messagedata = $row9['emailtemplate'];
                $subject = $row9['email_subject'];
                $attach1 = $row9['attach1'];
                $attach2 = $row9['attach2'];
                $attach3 = $row9['attach3'];
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            $queryResult9->close();
            $this->db->next_result();
            //$subject .=" #" . $orderno;
            $messagedata = str_replace("{{SENDERNAME}}", "$realname", "$messagedata");
            //$filename = "../../../customer/$customerno/salesengage/company_profile/Company Profile.pdf";
            foreach ($data as $datas) {

                //send mail function with attachment
                $mail[] = $this->sentMailordertemplate($customerno, $datas['recipientemail'], $subject, $messagedata, $fromemail, $realname, $orderid, $validAttachment);
            }
            $message = '';
            if (in_array("sent", $mail)) {
                $message = "Email Sent Successfully";
            } else {
                $message = "Email not sent to alteast one Recipient";
            }
        } else {
            $message = "Email Not Requested";
        }
        return $message;
    }

    function EditOrderSms($value, $data, $customerno) {
        $customermanager = new CustomerManager();
        extract($value);
        $today = date("Y-m-d H:i:s");
        $smsMessage = "";
        try {

            $sp_params9 = "'" . $customerno . "'"
                    . ",'" . $stageid . "'"
            ;
            $QUERY9 = $this->PrepareSP(constants::SP_GET_STAGES, $sp_params9);
            $queryResult9 = $this->db->query($QUERY9, __FILE__, __LINE__);
            $row9 = $this->db->fetch_array($queryResult9);
            $messagedata = "" . $row9['smstemplate'] . "";
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
            $arr_data['status'] = "Exception occured.";
        }
        $queryResult9->close();
        $this->db->next_result();
        foreach ($data as $datas)
            if (!empty($datas['recipientphone'])) {
                $smscount = $customermanager->getSMSCount($customerno);
                if ($smscount > 0) {
                    $response = '';
                    $isSMSSent[] = sendSMSUtil($datas['recipientphone'], $messagedata, $response);
                    $customermanager->updateSMSCount($smscount, $messagedata, $customerno);
                    if (in_array("1", $isSMSSent)) {

                        $smsMessage = "SMS sent successfully. ";
                    } else {
                        $smsMessage = "SMS sending failed";
                    }
                } else {
                    $smsMessage = "No SMS Left In Account";
                }
            }
        return $smsMessage;
    }

    function GetTicketData($value) {
        extract($value);
        $arr_data = array();
        $arr_temp = array();
        $userdata = $this->validate_userkey($userkey);
        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];
            $tictype = $this->getAlltickettypevalue();
            $ticprior = $this->getAllpriorityvalue();
            $sql = sprintf("select rel_manager,rm.teamid,rm.manager_name,manager_mobile,manager_email from " . SPEEDDB . ".customer cu
                                INNER JOIN " . SPEEDDB . ".relationship_manager  as rm ON  cu.rel_manager = rm.rid
                                where customerno=" . $customerno);

            $result = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($result) > 0) {
                $row = $this->db->fetch_array($result);
                $arr_temp['crmname'] = $row["manager_name"];
                $arr_temp['crmemail'] = $row["manager_email"];
                $arr_temp['crmphone'] = $row["manager_mobile"];
            } else {
                $arr_temp['crmname'] = "";
                $arr_temp['crmemail'] = "support@elixiatech.com";
                $arr_temp['crmphone'] = "02225137471";
            }
            $arr_data['sucess'] = 1;
            $arr_data['priority_data'] = $ticprior;
            $arr_data['tickettype_data'] = $tictype;
            $arr_data['crm_data'] = $arr_temp;
        } else {
            $arr_data['success'] = 0;
            $arr_data['errormsg'] = "Invalid Credentials";
        }
        echo json_encode($arr_data);
        return $arr_data;
    }

    public function GetAttachmentData($value) {
        extract($value);

        $arr_data = array();

        $userdata = $this->validate_userkey($userkey);

        if (!empty($userdata)) {
            $customerno = $userdata['customerno'];

//attachment master
            try {
                $sp_params = "" . $customerno . ""
                        . ",''"
                ;

                $QUERY = $this->PrepareSP(constants::SP_GET_ATTACHMENT_MASTER, $sp_params);
                $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);

                $row_count = $this->db->num_rows($queryResult);

                if ($row_count > 0) {
                    $arr_data['success'] = 1;
                    while ($row = $this->db->fetch_array($queryResult)) {
                        $arrTemp['attachid'] = $row['attachid'];
                        $arrTemp['attachname'] = $row['attachname'];
                        $arr_data['attachmentdata'][] = $arrTemp;
                    }
                } else {
                    $arr_data['success'] = 0;
                    $arr_data['attachmentdata'] = '';
                    $arr_data['message'] = "No Attachments Found";
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($customerno, $ex, constants::SALES, __FUNCTION__);
                $arr_data['status'] = "Exception occured.";
            }
            $queryResult->close();
//$this->db->next_result();
        } else {
            $arr_data['success'] = 0;
            $arr_data['message'] = "Invalid Credentials";
        }

        echo json_encode($arr_data);
        return $arr_data;
    }

    public function CheckForAttachment($customerno, $attachdata) {
        $validAttachments = array();
        $location = "../../../customer/$customerno/salesengage/all_attachments";
        foreach ($attachdata as $attachdatas) {
            $filename = $attachdatas['attachname'] . ".pdf";
            $filepath = $location . "/" . $filename;
            if (file_exists($filepath)) {
                $attachment = new stdClass;
                $attachment->fileName = $filename;
                $attachment->filePath = $filepath;
                $validAttachments[] = $attachment;
            } else {
                continue;
            }
        }
        return $validAttachments;
    }

    public function GetArrayDiff($newData, $oldData) {
        $tmpArray = array();
        $Data = array();
        foreach ($newData as $data1) {

            $duplicate = false;
            foreach ($oldData as $data2) {
                if ($data1['attachid'] === $data2['attachid'])
                    $duplicate = true;
            }

            if ($duplicate === false) {
                $tmpArray[] = $data1;
            }
        }

        $Data = $tmpArray;
        return $Data;
    }

}
