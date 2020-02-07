<?php

include_once("session.php");
include_once("loginorelse.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/utilities.php");
//ini_set("memory_limit",-1);
set_time_limit(0);
if (isset($_POST['work']) && $_POST['work'] == 'addLedger') {
    $ledgername = GetSafeValueString($_POST['ledgername'], "string");
    $add1 = GetSQLValueString($_POST['add1'], "string");
    $add2 = GetSafeValueString($_POST['add2'], "string");
    $add3 = GetSafeValueString($_POST['add3'], "string");
    $state = GetSafeValueString($_POST['state'], "string");
    $panno = GetSafeValueString($_POST['panno'], "string");
    $gstno = GetSafeValueString($_POST['gstno'], "string");
    $cstno = GetSafeValueString($_POST['cstno'], "string");
    $vatno = GetSafeValueString($_POST['vatno'], "string");
    $stno = GetSafeValueString($_POST['stno'], "string");
    $phno = GetSafeValueString($_POST['phno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");

    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();

    try {
        $sp_params2 = "''"
                . ",'" . trim($ledgername) . "'"
        ;
        $QUERY1 = $db->PrepareSP('get_ledger', $sp_params2);
        $ledgerList = $pdo->query($QUERY1);
        if ($ledgerList->rowCount() > 0) {
            $status = "1";
            $db->ClosePDOConn($pdo);
        } else {
            $pdo1 = $db->CreatePDOConn();
            $status = "Added Successfully";
            $sp_params1 = "'" . $ledgername . "'"
                    . ",'" . $add1 . "'"
                    . ",'" . $add2 . "'"
                    . ",'" . $add3 . "'"
                    . ",'" . $state . "'"
                    . ",'" . $email . "'"
                    . ",'" . $phno . "'"
                    . ",'" . $panno . "'"
                    . ",'" . $gstno . "'"
                    . ",'" . $loginid . "'"
                    . ",'" . $today . "'"
                    . ",'" . $loginid . "'"
                    . ",'" . $today . "'"
                    . "," . "@is_executed"
                    . "," . "@last_ledgerid";
            $pdo = $db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_LEDGER . "($sp_params1)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@last_ledgerid AS last_ledgerid";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult["is_executed"] == 1) {
                $issent = CreateEmailBody($outputResult['last_ledgerid'], 1);
                if ($issent == 1) {
                    $status.=" And Email Sent Successfully";
                } else {
                    $status.=" And Email Sending Fail";
                }
            } else {
                $status = "Ledger insertion failed";
            }
        }
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} 
//elseif (isset($_POST['work']) && $_POST['work'] == 'getLedgerDetails') {
//     $db = new DatabaseManager();
//     $pdo = $db->CreatePDOConn();
//     $ledger = array();
//     try {
//         $sp_params1 = "''"
//                 . ",''"
//         ;
//         $QUERY1 = $db->PrepareSP('get_ledger', $sp_params1);
//         $ledgerList = $pdo->query($QUERY1);
//         if ($ledgerList->rowCount() > 0) {
//             $x = 1;
//             while ($row = $ledgerList->fetch(PDO::FETCH_ASSOC)) {
//                 $data = new stdClass();
//                 $data->x = $x++;
//                 $data->ledgerid = utf8_encode($row['ledgerid']);
//                 $data->ledgername = utf8_encode($row['ledgername']);
//                 $data->add1 = utf8_encode($row['address1']);
//                 $data->add2 = utf8_encode($row['address2']);
//                 $data->add3 = utf8_encode($row['address3']);
//                 $data->pan = utf8_encode($row['pan_no']);
//                 $data->gst = utf8_encode($row['gst_no']);
//                 $data->state = utf8_encode($row['state']);
//                 $data->cst = utf8_encode($row['cst_no']);
//                 $data->vat = utf8_encode($row['vat_no']);
//                 $data->st = utf8_encode($row['st_no']);
//                 $data->phone = utf8_encode($row['phone']);
//                 $data->email = utf8_encode($row['email']);
//                 $ledger[] = $data;
//             }
//         }
//         $db->ClosePDOConn($pdo);
//     } catch (Exception $e) {
//         $status = "Caught exception: " . $e->getMessage();
//     }
//     echo safe_json_encode($ledger);
// }
 elseif (isset($_POST['work']) && $_POST['work'] == 'editLedger') {
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "string");
    $ledgername = GetSafeValueString($_POST['ledgername'], "string");
    $add1 = GetSQLValueString($_POST['add1'], "string");
    $add2 = GetSafeValueString($_POST['add2'], "string");
    $add3 = GetSafeValueString($_POST['add3'], "string");
    $state = GetSafeValueString($_POST['state'], "string");
    $panno = GetSafeValueString($_POST['panno'], "string");
    $gstno = GetSafeValueString($_POST['gstno'], "string");
    $phno = GetSafeValueString($_POST['phno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");

    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    try {

        $status = "Updated Successfully";
        $sp_params1 = "'" . $ledgerid . "'"
                . ",'" . $ledgername . "'"
                . ",'" . $add1 . "'"
                . ",'" . $add2 . "'"
                . ",'" . $add3 . "'"
                . ",'" . $state . "'"
                . ",'" . $email . "'"
                . ",'" . $phno . "'"
                . ",'" . $panno . "'"
                . ",'" . $gstno . "'"
                . ",'" . $loginid . "'"
                . ",'" . $today . "'"
                . ",@is_executed";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_LEDGER . "($sp_params1)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @is_executed AS is_executed";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if ($outputResult["is_executed"] == 1) {
            $issent = CreateEmailBody($ledgerid, 0);
            if ($issent == 1) {
                $status.=" And Email Sent Successfully";
            } else {
                $status.=" And Email Sending Fail";
            }
        } else {
            $status = "Ledger updation failed.";
        }
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} elseif (isset($_GET['work']) && $_GET['work'] == 'deleteLedger') {
    $ledgerid = GetSafeValueString($_GET['delledid'], "string");

    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    try {
        $sp_params1 = "'" . $ledgerid . "'"
                . ",'" . $loginid . "'"
                . ",'" . $today . "'"

        ;
        $QUERY1 = $db->PrepareSP('delete_ledger', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    header("Location: ledger.php");
} elseif (isset($_POST['work']) && $_POST['work'] == 'getMappedLedger') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $custno = GetSQLValueString($_POST['custno'], "string");
    $ledger = array();
    try {
        $sp_params1 = "''"
                . ",'" . $custno . "'"
                . ",''"
        ;
       $QUERY1 = $db->PrepareSP('get_ledger_cust_mapping', $sp_params1);
        $ledgerList = $pdo->query($QUERY1);
        if ($ledgerList->rowCount() > 0) {
            $x = 1;
            while ($row = $ledgerList->fetch(PDO::FETCH_ASSOC)) {
                $data = new stdClass();
                $data->x = $x++;
                $data->ledgerid = $row['ledgerid'];
                $data->ledgername = $row['ledgername'];
                $data->add1 = $row['address1'];
                $data->add2 = $row['address2'];
                $data->add3 = $row['address3'];
                $data->pan = $row['pan_no'];
                $data->gstno =$row['gst_no'];
                $data->phone = $row['phone'];
                $data->email = $row['email'];
                $data->customerno = $row['customerno'];
                $ledger[] = $data;
            }
        }
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo json_encode($ledger);
} elseif (isset($_POST['work']) && $_POST['work'] == 'mapLedger') {
    $ledgername = GetSafeValueString($_POST['ledgername'], "string");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "string");
    $custno = GetSafeValueString($_POST['cust_ledger'], "string");
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    try {
        $status = "Allotted Successfully";
        $sp_params1 = "'" . $ledgerid . "'"
                . ",'" . $custno . "'"
                . ",'" . $loginid . "'"
                . ",'" . $today . "'"
                . ",'" . $loginid . "'"
                . ",'" . $today . "'"
        ;
        $QUERY1 = $db->PrepareSP('insert_ledger_cust_mapping', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} elseif (isset($_GET['work']) && $_GET['work'] == 'deleteMappedLedger') {
    $ledgerid = GetSafeValueString($_GET['mapledid'], "string");
    $custno = GetSafeValueString($_GET['custno'], "string");
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    try {
        $sp_params1 = "'" . $ledgerid . "'"
                . ",'" . $loginid . "'"
                . ",'" . $today . "'"

        ;
        $QUERY1 = $db->PrepareSP('delete_ledger_cust_mapping', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    header("Location: modifycustomer.php?cid=$custno");
}

function CreateEmailBody($last_ledgerid, $isinsert) {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $sp_params2 = "'" . $last_ledgerid . "'"
            . ",''"
    ;
    $QUERY1 = $db->PrepareSP('get_ledger', $sp_params2);
    $ledgerList = $pdo->query($QUERY1);
    if ($ledgerList->rowCount() > 0) {
        $row = $ledgerList->fetch(PDO::FETCH_ASSOC);
        $data = new stdClass();
        $data->ledgerid = $row['ledgerid'];
        $data->ledgername = $row['ledgername'];
        $data->add1 = $row['address1'];
        $data->add2 = $row['address2'];
        $data->add3 = $row['address3'];
        $data->pan = $row['pan_no'];
        $data->gstno = $row['gst_no'];
        $data->phone = $row['phone'];
        $data->email = $row['email'];
    }
    $db->ClosePDOConn($pdo);
    $msg = 'Please find the Ledger Details Below';
    $msg.= '<table border="1" width="100%">
            <tr>
                <th>Ledger ID</th>
                <th>Ledger Name</th>
                <th>Address Line1</th>
                <th>Address Line2</th>
                <th>Address Line3</th>
                <th>PAN No.</th>
                <th>GST No.</th>
                <th>Phone</th>
                <th>Email</th>

            </tr>
            <tbody style="text-align: center;">
            <tr>
            <td>' . $data->ledgerid . '</td>
            <td>' . $data->ledgername . '</td>
            <td>' . $data->add1 . '</td>
            <td>' . $data->add2 . '</td>
            <td>' . $data->add3 . '</td>
            <td>' . $data->pan . '</td>
            <td>' . $data->gstno . '</td>
            <td>' . $data->phone . '</td>
            <td>' . $data->email . '</td>
            </tr>
            </tbody>
        </table>';

    $arrToMailIds = array('sanketsheth1@gmail.com', 'mukundsheth2@gmail.com');
    $strCCMailIds = array('accounts@elixiatech.com');
    if ($isinsert == 1) {
        $subject = 'New Ledger #' . $data->ledgerid . '-' . $data->ledgername;
    } else {
        $subject = 'Updated Ledger #' . $data->ledgerid . '-' . $data->ledgername;
    }
    $strBCCMailIds = array();
    $attachmentFilePath = '';
    $attachmentFileName = '';
    $issent = sendMailUtil($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $msg, $attachmentFilePath, $attachmentFileName);
    return $issent;
}

function sendMail() {
    include_once("../../lib/system/class.phpmailer.php");
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
?>

