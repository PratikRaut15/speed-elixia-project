<?php

/**
 * Ajax page of Salesengage-module
 */
require_once 'salesengage_function.php';

$customerno = exit_issetor($_SESSION['customerno'], 'Please login');
$userid = exit_issetor($_SESSION['userid'], 'Please login');
$action = ri($_REQUEST['action']);


if ($action == 'addclient') {
    $clname = ri($_REQUEST['clname']);
    $caddress = ri($_REQUEST['caddress']);
    $cemails = ri($_REQUEST['cemail']);
    $cmobile = ri($_REQUEST['cmobile']);
    $cbirthdate = ri($_REQUEST['cbirthdate']);

    $cemail = explode(',', $cemails);
    if (count($cemail) > 1) {
        $cemails = $cemail;
    } else {
        $cemails = $cemails;
    }

    if ($clname == "" || $cemail == "" || $cmobile == "") {
        echo failure('Please enter mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);

        if (count($cemail) > 1) {
            foreach ($cemail as $key => $value) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    echo failure("Invalid email id.");
                    exit;
                }
            }
        } else {
            if (!filter_var($cemails, FILTER_VALIDATE_EMAIL)) {
                echo failure("Invalid email id");
                exit;
            }

            if ($sales->is_client_email_exists($cemails)) {
                echo failure($cemails . " already exists");
                exit;
            }
        }
        $sales->insert_clientdata($clname, $caddress, $cemail, $cmobile, $cbirthdate);
        echo success('Client added sucessfully');
        exit;
    }
} else if ($action == 'editclient') {
    $clientid = ri($_REQUEST['clientid']);
    $clname = ri($_REQUEST['clname']);
    $caddress = ri($_REQUEST['caddress']);
    $cemails = ri($_REQUEST['cemail']);
    $cmobile = ri($_REQUEST['cmobile']);
    $cbirthdate = ri($_REQUEST['cbirthdate']);

    $cemail = explode(',', $cemails);
    if (count($cemail) > 1) {
        $cemails = $cemail;
    } else {
        $cemails = $cemails;
    }

    if ($clname == "" || $cemail == "" || $cmobile == "") {
        echo failure('Please enter mandatory fields');
        exit;
    } else {

        if (count($cemail) > 1) {
            foreach ($cemail as $key => $value) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    echo failure("Invalid email id.");
                    exit;
                }
            }
        } else {
            if (!filter_var($cemails, FILTER_VALIDATE_EMAIL)) {
                echo failure("Invalid email id");
                exit;
            }
        }
        $sales = new Saleseng($customerno, $userid);
        $sales->update_clientdata($clientid, $clname, $caddress, $cemail, $cmobile, $cbirthdate);
        echo success('Client updated sucessfully');
        exit;
    }
} else if ($action == 'delsrcorder') {
    $srcordid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_srcorderdata($srcordid);
    echo success('Source of order deleted sucessfully');
    exit;
} else if ($action == 'delclient') {
    $clientid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_clientdata($clientid);
    echo success('Client deleted sucessfully');
    exit;
} else if ($action == 'addproduct') {
    $pname = ri($_REQUEST['pname']);
    $unitprice = ri($_REQUEST['unitprice']);
    if ($pname == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($sales->is_product_exists($pname)) {
            echo failure('Product already exists');
            exit;
        }
        $sales->insert_productdata($pname, $unitprice);
        echo success('Product added sucessfully');
        exit;
    }
} else if ($action == 'editproduct') {
    $pname = ri($_REQUEST['pname']);
    $prid = ri($_REQUEST['prid']);
    $unitprice = ri($_REQUEST['unitprice']);
    if ($pname == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->update_productdata($prid, $pname, $unitprice);
        echo success('Product Updated sucessfully');
        exit;
    }
} else if ($action == 'delproduct') {
    $productid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_productdata($productid);
    echo success('Product deleted sucessfully');
    exit;
} else if ($action == 'addlost') {
    $lostreason = ri($_REQUEST['lostreason']);
    if ($lostreason == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($sales->is_lost_exists($lostreason)) {
            echo failure('Lost reason already exists');
            exit;
        }
        $sales->insert_addlostdata($lostreason);
        echo success('Lost reason added sucessfully');
        exit;
    }
} else if ($action == 'addstage') {
    $stagename = ri($_REQUEST['stagename']);
    if ($stagename == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($sales->is_stage_exists($stagename)) {
            echo failure('Stage already exists');
            exit;
        }
        $sales->insert_addstagedata($stagename);
        echo success('Stage added sucessfully');
        exit;
    }
} else if ($action == 'addordersource') {
    $ordersource = ri($_REQUEST['ordersource']);
    if ($ordersource == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($sales->is_sourceorder_exists($ordersource)) {
            echo failure('Order Source type already exists');
            exit;
        }
        $sales->insert_addsourceorderdata($ordersource);
        echo success('Order Source type added sucessfully');
        exit;
    }
} else if ($action == 'editordersource') {
    $srcorder = ri($_REQUEST['order_source']);
    $srcid = ri($_REQUEST['srcordid']);
    if ($srcorder == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->update_srcorderdata($srcid, $srcorder);
        echo success('Order Source updated sucessfully');
        exit;
    }
} else if ($action == 'editstage') {
    $stagename = ri($_REQUEST['stagename']);
    $stageid = ri($_REQUEST['stageid']);
    if ($stagename == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->update_stagedata($stageid, $stagename);
        echo success('Stage updated sucessfully');
        exit;
    }
} else if ($action == 'editlost') {
    $lostreason = ri($_REQUEST['lostreason']);
    $lostreasonid = ri($_REQUEST['lostreasonid']);
    if ($lostreason == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->update_lostdata($lostreasonid, $lostreason);
        echo success('Lostreason updated sucessfully');
        exit;
    }
} else if ($action == 'delstage') {
    $stageid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_stage($stageid);
    echo success('Stage deleted sucessfully');
    exit;
} else if ($action == 'dellost') {
    $lostid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_lost($lostid);
    echo success('Lost reason deleted sucessfully');
    exit;
} else if ($action == 'addreminder') {
    $remindername = ri($_REQUEST['rname']);
    if ($remindername == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($sales->is_reminder_exists($remindername)) {
            echo failure('Remindername already exists');
            exit;
        }
        $sales->insert_reminderdata($remindername);
        echo success('Reminder added sucessfully');
        exit;
    }
} else if ($action == 'editreminder') {
    $rid = (int) ri($_REQUEST['reminderid']);
    $remindername = ri($_REQUEST['rname']);
    if ($remindername == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->update_reminderdata($rid, $remindername);
        echo success('Reminder Updated sucessfully');
        exit;
    }
} else if ($action == 'delreminder') {
    $reminderid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_reminder($reminderid);
    echo success('Reminder deleted sucessfully');
    exit;
} else if ($action == 'clientauto') {
    $q = ri($_REQUEST['term']);
    $sales = new Saleseng($customerno, $userid);
    $result = $sales->getclientdata($q);
    echo json_encode($result);
    exit;
} else if ($action == 'stageauto') {
    $q = ri($_REQUEST['term']);
    $sales = new Saleseng($customerno, $userid);
    $result = $sales->getstageautodata($q);
    echo json_encode($result);
    exit;
} else if ($action == "addorder") {
    $clientid = ri($_REQUEST['clientid']);
    $ordersource = ri($_REQUEST["ordersource"]);
    $stageid = ri($_REQUEST['stageid']);
    $eocd = ri($_REQUEST['eocd']);
    $emailchk = ri($_REQUEST['emailchk']);
    $smschk = ri($_REQUEST['smschk']);
    $additionalcost = ri($_REQUEST['additionalcost']);
    $totalcost = ri($_REQUEST['totalcost']);
    $pid = ri($_REQUEST['productlist']);
    $pid1 = explode(",", $pid);

    if ($stageid == "" || $clientid == "" || $ordersource == "") {
        echo failure('Please fill the mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($sales->is_stagetemplate_exists($stageid) == 0) {
            echo failure('Email/Sms template not exists');
            exit;
        }
        if (!empty($pid1)) {
            $productslist = $sales->getproducts_email($pid1);
        }
        $clientdata = $sales->getclientdata_byid($clientid);
        $toemail = $clientdata[0]['cemail'];
        $mobileno = $clientdata[0]['mobileno'];
        $clientname = $clientdata[0]['clientname'];
        $emailsend = 0;
        $smssend = 0;
        $response = "";
        if (!empty($stageid)) {
            $templatedata = $sales->gettemplateby_stageid($stageid);
            if ($emailchk == '1') {
                if (!empty($toemail)) {
                    $subject = $templatedata[0]['email_subject'];
                    $total_price = array();
                    $content = $templatedata[0]['emailtemplate'];
                    $content .= "<table border='1'><tr><th>Product List</th></tr>";
                    foreach ($productslist as $row) {
                        $content .="<tr><td>" . $row['productname'] . "</td></tr>";
                    }
                    $content .= "</table>";

                    $content1 = str_replace("{{CLIENTNAME}}", ucfirst($clientname), $content);
                    $content2 = str_replace("{{DATE}}",ucfirst($eocd),$content1);
                    $toemails = explode(",", $toemail);
                    if(count($toemails)>1){
                        foreach ($toemails as $key => $value) {
                            sendMailWebSales($value, $subject, $content2);
                        }
                         $emailsend = 1;
                    }else{
                        sendMailWebSales($toemail, $subject, $content2);
                        $emailsend = 1;
                    }
                }
            }

            if ($smschk == '1') {
                if (!empty($mobileno)) {
                    $message = $templatedata[0]['smstemplate'];
                    $message1 = str_replace("{{CLIENTNAME}}", ucfirst($clientname), $message);
                    $message2 = str_replace("{{DATE}}", ucfirst($eocd), $message1);
                    
                    $sales = new Saleseng($customerno, $userid);
                    $sms = $sales->pullsmsdetails($customerno);
                    if ($sms[0]['smsleft'] > 0) {
                        if ($sms[0]['smsleft'] == 20) {
                            $message = "Your SMS pack is too low. Please contact an Elixir. Powered by Elixia Tech.";
                            if (sendSMSWebSales($mobileno, $message, $response)) {
                                $smssend = 1;
                            }
                        }
                        if (sendSMSWebSales($mobileno, $message2, $response)) {
                            $smssend = 1;
                        }
                        $smsleft = $sms[0]['smsleft'] - 1;
                        $sales = new Saleseng($customerno, $userid);
                        $sales->updatesmsforsalesengage($smsleft, $customerno);
                    }
                }
            }
        }
        $lastid = $sales->insert_orderdata($pid1, $ordersource, $clientid, $stageid, $eocd, $emailchk, $smschk, $emailsend, $smssend, $additionalcost, $totalcost);

        $sales->check_seed_exists($lastid, $clientid);

        $sales = new Saleseng($customerno, $userid);
        if ($emailchk == 1) {
            $toemails2 = explode(",", $toemail);
            if(count($toemails2)>1){
            foreach ($toemails2 as $key => $value) {
                $sales->insertemaillog($value, $subject, $content2, $lastid, '0', $emailsend);
            }
           }else{
               $sales->insertemaillog($toemail, $subject, $content2, $lastid, '0', $emailsend);
           }
        }
        if ($smschk == 1) {
            $sales->insertsmslog($mobileno, $message2, $lastid, '0', $smssend, $response);
        }
        echo success('Order Added sucessfully');
        exit;
    }
} else if ($action == "editorder") {
    $lostreason = ri($_REQUEST['lostreasons']);
    $orderid = ri($_REQUEST['orderid']);
    $clientid = ri($_REQUEST['clientid']);
    $stageid = ri($_REQUEST['stageid1']);
    $eocd = ri($_REQUEST['eocd']);
    $emailchk = ri($_REQUEST['emailchk']);
    $smschk = ri($_REQUEST['smschk']);
    $additionalcost = ri($_REQUEST['additionalcost']);
    $totalcost = ri($_REQUEST['totalcost']);
    $pid1 = $_REQUEST['productlist1'];
    $lostnotes = $_REQUEST['lostnotes'];
    $ordersource = $_REQUEST['ordersource'];

    if ($stageid == "" || $clientid == "" || $ordersource == "") {
        echo failure('Please fill the mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $clientdata = $sales->getclientdata_byid($clientid);

        if ($sales->is_stagetemplate_exists($stageid) == 0) {
            echo failure('Email/Sms template not exists');
            exit;
        }
        $toemail = $clientdata[0]['cemail'];
        $mobileno = $clientdata[0]['mobileno'];
        $clientname = $clientdata[0]['clientname'];

        $st = $sales->chk_orderstage_ifchange($orderid, $stageid);  //if stage change then reset emailsend /sms send
        if (empty($emailchk)) {
            $emailchk = 0;
            $emailsend = 0;
        } else {
            $emailchk = 1;
            $emailsend = 1;
        }
        if (empty($smschk)) {
            $smschk = 0;
            $smssend = 0;
        } else {
            $smschk = 1;
            $smssend = 1;
        }

        if ($st == '1') {
            if (!empty($pid1)) {
                $productslist = $sales->getproducts_email($pid1);
            }

            $emailsend = 0;
            $smssend = 0;
            $subject = "";
            $content = "";
            if (!empty($stageid)) {
                $templatedata = $sales->gettemplateby_stageid($stageid);
                if ($emailchk == '1') {
                    if (!empty($toemail)) {
                        $subject = $templatedata[0]['email_subject'];
                        $total_price = array();
                        $content = $templatedata[0]['emailtemplate'];
                        $content .= "<table border='1'><tr><th>Product List</th></tr>";
                        foreach ($productslist as $row) {
                            $content .="<tr><td>" . $row['productname'] . "</td></tr>";
                            $total_price[] = $row['unit_price'];
                        }
                        $content .= "</table>";
                        $content1 = str_replace("{{CLIENTNAME}}", ucfirst($clientname), $content);
                        sendMailWebSales($toemail, $subject, $content1);
                        $emailsend = 1;
                    }
                }

                if ($smschk == '1') {
                    if (!empty($mobileno)) {
                        $message = $templatedata[0]['smstemplate'];
                        $message1 = str_replace("{{CLIENTNAME}}", ucfirst($clientname), $message);

                        $sales = new Saleseng($customerno, $userid);
                        $sms = $sales->pullsmsdetails($customerno);
                        if ($sms[0]['smsleft'] > 0) {
                            if ($sms[0]['smsleft'] == 20) {
                                $message = "Your SMS pack is too low. Please contact an Elixir. Powered by Elixia Tech.";
                                if (sendSMSWebSales($mobileno, $message, $response)) {
                                    $smssend = 1;
                                }
                            }
                            if (sendSMSWebSales($mobileno, $message1, $response)) {
                                $smssend = 1;
                            }
                            $smsleft = $sms[0]['smsleft'] - 1;
                            $sales = new Saleseng($customerno, $userid);
                            $sales->updatesmsforsalesengage($smsleft, $customerno);
                        }
                    }
                }
            }
        }

        $sales->update_orderdata($lostreason, $lostnotes, $ordersource, $pid1, $orderid, $clientid, $stageid, $eocd, $emailchk, $smschk, $emailsend, $smssend, $additionalcost, $totalcost);
        $sales = new Saleseng($customerno, $userid);
        if ($emailchk == 1) {
            $sales->insertemaillog($toemail, $subject, $content1, $orderid, '0', $emailsend);
        }
        if ($smschk == 1) {
            $sales->insertsmslog($mobileno, $message1, $orderid, '0', $smssend, $response);
        }
        echo success('Order Updated sucessfully');
        exit;
    }
} else if ($action == 'delorder') {
    $orderid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_order($orderid);
    echo success('Order deleted sucessfully');
    exit;
} else if ($action == 'reminderauto') {
    $q = ri($_REQUEST['term']);
    $sales = new Saleseng($customerno, $userid);
    $result = $sales->getreminderdata($q);
    echo json_encode($result);
    exit;
} else if ($action == 'addtemplate') {
    $templatetype = ri($_REQUEST['templatetype']);
    $reminderid = ri($_REQUEST['remid']);
    $stageid = ri($_REQUEST['stageid']);
    $emailsubject = ri($_REQUEST['emailsubject']);
    $emailtemp = mysql_real_escape_string(ri($_REQUEST['emailtemplate']));
    $smstemp = mysql_real_escape_string(ri($_REQUEST['smstemplate']));
    $rtype = ri($_REQUEST['rtype']);
    $emailtemp = str_replace(',','&#44;',$emailtemp);
    $smstemp = str_replace(',','&#44;',$smstemp);
    
    if ($templatetype == "") {
        echo failure('Please select template type');
        exit;
    } else if ($reminderid == "" && $stageid == "") {
        echo failure('Please select options');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($sales->is_stageorreminder_exists($reminderid, $stageid)) {
            echo failure('template already exists');
            exit;
        }
        $sales->insert_tempalate($templatetype, $reminderid, $stageid, $emailsubject, $emailtemp, $smstemp, $rtype);
        echo success('Template Added sucessfully');
        exit;
    }
} else if ($action == 'edittemplate') {
    $templateid = ri($_REQUEST['templateid']);
    $templatetype = ri($_REQUEST['templatetype1']);
    $reminderid = ri($_REQUEST['remid']);
    $stageid = ri($_REQUEST['stageid']);
    $emailsubject = ri($_REQUEST['emailsubject']);
    $emailtemp = mysql_real_escape_string(ri($_REQUEST['emailtemplate']));
    $smstemp = mysql_real_escape_string(ri($_REQUEST['smstemplate']));
    $emailtemp = str_replace(',','&#44;',$emailtemp);
    $smstemp = str_replace(',','&#44;',$smstemp);
    $rtype = ri($_REQUEST['rtype']);


    if ($templatetype == "") {
        echo failure('Please select template type');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->update_tempalate($templateid, $templatetype, $reminderid, $stageid, $emailsubject, $emailtemp, $smstemp, $rtype);
        echo success('Template Updated sucessfully');
        exit;
    }
} else if ($action == 'deletetemplate') {

    $tempid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_template($tempid);
    echo success('Template deleted sucessfully');
    exit;
} else if ($action == 'addactivity') {
    $orderid = ri($_REQUEST['orderid']);
    $rmid = ri($_REQUEST['remid']);
    $notes = ri($_REQUEST['notes']);
    $activitytime = ri($_REQUEST['activitytime']);
    $stime = ri($_REQUEST['STime']);
    $activityrduration = ri($_REQUEST['activityrduration']);
//$activitystatus = ri($_REQUEST['activitystatus']);
    $emailreq = ri($_REQUEST['emailreq']);
    $smsreq = ri($_REQUEST['smsreq']);
    $paymentamt = ri($_REQUEST['paymentamt']);
    $activitytype = ri($_REQUEST['activitytype']);

    if ($rmid == "" || $activitytype == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        if ($activitytype == '2') {  //for elixir
            $data = $sales->getorderdata_byid($orderid);
            $userid = $data[0]['elixirid'];
        } else {
            $userid = 0;
        }
        $sdate = date('Y-m-d', strtotime($activitytime));
        $stime = substr($stime, 0, 5);
        $activitystatus = 0;
        $sales->insert_activitydata($orderid, $rmid, $notes, $sdate, $stime, $activityrduration, $activitystatus, $emailreq, $smsreq, $paymentamt, $activitytype, $userid);
        echo success('Activity Added sucessfully');
        exit;
    }
} else if ($action == 'editactivity') {
    $orderid = ri($_REQUEST['orderid']);
    $activityid = ri($_REQUEST['activityid']);
    $rmid = ri($_REQUEST['remid']);
    $notes = ri($_REQUEST['notes']);
    $activitytime = ri($_REQUEST['activitytime']);
    $stime = ri($_REQUEST['STime']);
    $activityrduration = ri($_REQUEST['activityrduration']);
//$activitystatus = ri($_REQUEST['activitystatus']);
    $emailreq = ri($_REQUEST['emailreq']);
    $smsreq = ri($_REQUEST['smsreq']);
    $paymentamt = ri($_REQUEST['paymentamt']);
    $activitytype = ri($_REQUEST['activitytype']);

    if ($rmid == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sdate = date('Y-m-d', strtotime($activitytime));
        $stime = substr($stime, 0, 5);

        if ($activitytype == '2') {  //for elixir
            $data = $sales->getorderdata_byid($orderid);
            $userid = $data[0]['elixirid'];
        } else {
            $userid = 0;
        }
        $activitystatus = 0;
        $sales->update_activitydata($activityid, $orderid, $rmid, $notes, $sdate, $stime, $activityrduration, $activitystatus, $emailreq, $smsreq, $paymentamt, $activitytype, $userid);
        echo success('Activity Updated sucessfully');
        exit;
    }
} else if ($action == 'deleteactivity') {
    $actid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_activity($actid);
    echo success('Activity deleted sucessfully');
    exit;
} else if ($action == 'productauto') {
    $q = ri($_REQUEST['term']);
    if (preg_match('/,/', $q)) {
        $test1 = explode(',', $q);
        $q = trim(end($test1));
    }
    $sales = new Saleseng($customerno, $userid);
    $result = $sales->getproductautodata($q);
    echo json_encode($result);
    exit;
} else if ($action == 'addsales') {
    $srname = ri($_REQUEST['srname']);
    $sremail = ri($_REQUEST['sremail']);
    $srphone = ri($_REQUEST['srphone']);

    if ($srname == "" || $sremail == "" || $srphone == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->insert_salesdata($srname, $sremail, $srphone);
        echo success('Sales added sucessfully');
        exit;
    }
} else if ($action == 'editsales') {
    $srid = ri($_REQUEST['srid']);
    $srname = ri($_REQUEST['srname']);
    $sremail = ri($_REQUEST['sremail']);
    $srphone = ri($_REQUEST['srphone']);

    if ($srid == "" || $srname == "" || $sremail == "" || $srphone == "") {
        echo failure('Please filled mandatory fields');
        exit;
    } else {
        $sales = new Saleseng($customerno, $userid);
        $sales->update_salesdata($srid, $srname, $sremail, $srphone);
        echo success('Sales updated sucessfully');
        exit;
    }
} else if ($action == 'deletesales') {
    $srid = (int) ri($_REQUEST['id']);
    $sales = new Saleseng($customerno, $userid);
    $sales->delete_salesdata($srid);
    echo success('Sales deleted sucessfully');
    exit;
} else {
    echo failure('No action found');
    exit;
}
?>
