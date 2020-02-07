<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/datagrid.php");

$_scripts[] = "../../scripts/jquery.min.js";
//$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

$bucketid = GetSafeValueString(isset($_GET["id"]) ? $_GET["id"] : $_POST["id"], "long");

if (IsHead()) {
    $msg = "<P>You are an authorized user</p>";
} else {
    header("Location: index.php");
    exit;
}

$db = new DatabaseManager();

class testing {
    
}

// See if we need to save a new one.
$message = "";
$db = new DatabaseManager();

if (isset($_POST["save"])) {
    
    $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
    $oldunitid = GetSafeValueString($_POST["oldunitid"], "string");
    $sstatus = GetSafeValueString($_POST["sstatus"], "string");
    $unsuccess_problem = GetSafeValueString($_POST["sproblem"], "string");
    $reschedule_date = GetSafeValueString($_POST["reschedule_date"], "string");
    $reschedule_date = date("Y-m-d", strtotime($reschedule_date));
    $feid = GetSafeValueString($_POST["feid"], "string");
    $customerno = GetSafeValueString($_POST["customerno"], "string");
    $created_by = GetSafeValueString($_POST["createdby"], "string");
    $purposeid = GetSafeValueString($_POST["purposeid"], "string");
    $comment = GetSafeValueString($_POST["comment"], "string");
    $priorityid = GetSafeValueString($_POST["priorityid"], "string");
    $timeslot = GetSafeValueString($_POST["timeslotid"], "string");
    $details = GetSafeValueString($_POST["details"], "string");
    $coordinator = GetSafeValueString($_POST["coordinatorid"], "string");
    $docketid = $_POST['docketid'];

    $pdo = $db->CreatePDOConn();
    if ($purposeid == 1) {
        if (isset($_POST["unitid_reg"])) {
            $unitid_reg = GetSafeValueString($_POST["unitid_reg"], "string");
            $utype_reg = GetSafeValueString($_POST["utype_reg"], "string");
            $sendemail_reg = GetSafeValueString($_POST["sendmail_reg"], "string");
            $simcardid_reg = GetSafeValueString($_POST["simid_reg"], "string");
            $pono_reg = GetSafeValueString($_POST["cpono_reg"], "string");
            $podate_reg = GetSafeValueString($_POST["podate_reg"], "string");
            $expiry_reg = GetSafeValueString($_POST["expiry_reg"], "string");
            $expiry_reg = date("Y-m-d", strtotime($expiry_reg));
            $installdate_reg = GetSafeValueString($_POST["installation_reg"], "string");
            $installdate_reg = date("Y-m-d", strtotime($installdate_reg));
            $end_date  = date('Y-m-t', strtotime($installdate_reg));
            $invoiceno_reg = GetSafeValueString($_POST["invoiceno_reg"], "string");
            $vehicleno_reg = GetSafeValueString($_POST["vehicleno_reg"], "string");
            $kind_reg = GetSafeValueString($_POST["kind_reg"], "string");
            $lease_reg = GetSafeValueString($_POST['lease_reg'], "string");
            $teamid_reg = GetSafeValueString($_POST["teamid_reg"], "string");
            if ($podate_reg != '') {
                $podate_reg = date("Y-m-d", strtotime($podate_reg));
            }

            $sp_params = "'" . $today . "'"
                    . ",'" . $unitid_reg . "'"
                    . ",'" . $utype_reg . "'"
                    . ",'" . $simcardid_reg . "'"
                    . ",'" . $customerno . "'"
                    . ",'" . $pono_reg . "'"
                    . ",'" . $podate_reg . "'"
                    . ",'" . $expiry_reg . "'"
                    . ",'" . $installdate_reg . "'"
                    . ",'" . $end_date . "'"
                    . ",'" . $invoiceno_reg . "'"
                    . ",'" . $vehicleno_reg . "'"
                    . ",'" . $kind_reg . "'"
                    . ",'" . $lease_reg . "'"
                    . ",'" . $teamid_reg . "'"
                    . ",'" . GetLoggedInUserId() . "'"
                    . ",'" . $sstatus . "'"
                    . ",'" . $unsuccess_problem . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $bucketid . "'"
                    . ",'" . $comment . "'"
                    . ",'" . $docketid . "'"
                    . ",@is_executed,@username,@realname,@email,@unitnumber,@simcardno,@elixir,@errormsg";

            $queryCallSP = "CALL " . speedConstants::SP_REGISTER_DEVICE . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP);

            $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                        ,@username AS username
                                        ,@realname AS realname
                                        ,@email AS email
                                        ,@unitnumber AS unitnumber
                                        ,@simcardno AS simcardno
                                        ,@elixir AS elixir
                                        ,@errormsg AS errormsg";

            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            $db->ClosePDOConn($pdo);

            if ($outputResult['is_executed'] == 1) {
                if ($sstatus == 2) {
                    // Create unit directory
                    $relativepath = "../..";
                    if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['unitnumber'])) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['unitnumber'], 0777, true) or die("Could not create directory");
                    }

                    if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['unitnumber'] . '/sqlite')) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['unitnumber'] . '/sqlite', 0777, true) or die("Could not create directory");
                    }
                    $reg_message = "Successfully registered device.";
                    if ($sendemail_reg == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult["realname"];
                        $mail->email = $outputResult["email"];
                        $mail->unitnumber = $outputResult["unitnumber"];
                        $mail->simcardno = $outputResult["simcardno"];
                        $mail->elixir = $outputResult["elixir"];
                        $mail->vehicleno = $vehicleno_reg;
                        $mail->installdate = $installdate_reg;
                        $mail->expirydate = $expiry_reg;
                        $mail->comment = $comment;
                        $status = SendEmail($mail);
                        if ($status == 1) {
                            $reg_message = "Successfully registered device.Mail sent";
                        } else {
                            $reg_message = "Successfully registered device.Mail not sent";
                        }
                    }
                } else {
                    $reg_message = "Bucket updated successfully";
                }
            } else {
                if (!empty($outputResult['errormsg'])) {
                    $reg_message = $outputResult['errormsg'] . "Bucket updation failed.";
                } else {
                    $reg_message = "Bucket updation failed.";
                }
            }
        } else {
            $reg_message = "Unit not entered.Registration failed.";
        }
        header("Location: compliance.php?msg=" . $reg_message);
    } elseif ($purposeid == 2) {
        if (isset($_POST["unitid_repr"]) && !empty($_POST["unitid_repr"])) {
            $unitid_repr = GetSafeValueString($_POST["unitid_repr"], "string");
            $simcardid_repr = GetSafeValueString($_POST["simcardid_repr"], "string");
            $teamid_repr = GetSafeValueString($_POST["teamid_repr"], "string");
            $sendmail_repr = GetSafeValueString($_POST["sendmail_repr"], "string");

            $sp_params = "'" . $today . "'"
                    . ",'" . $unitid_repr . "'"
                    . ",'" . $simcardid_repr . "'"
                    . ",'" . $teamid_repr . "'"
                    . ",'" . GetLoggedInUserId() . "'"
                    . ",'" . $customerno . "'"
                    . ",'" . $sstatus . "'"
                    . ",'" . $unsuccess_problem . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $bucketid . "'"
                    . ",'" . $comment . "'"
                    . ",'" . $docketid . "'"
                    . ",@is_executed,@username,@realname,@email,@vehicleno,@unitnumber,@simcardno,@elixir";

            $queryCallSP = "CALL " . speedConstants::SP_REPAIR . "($sp_params)";
           
            $arrResult = $pdo->query($queryCallSP);

            $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                        ,@username AS username
                                        ,@realname AS realname
                                        ,@email AS email
                                        ,@vehicleno AS vehicleno
                                        ,@unitnumber AS unitnumber
                                        ,@simcardno AS simcardno
                                        ,@elixir AS elixir";

            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            $db->ClosePDOConn($pdo);

            if ($outputResult['is_executed'] == 1) {
                if ($sstatus == 2) {
                    $reg_message = "Successfully repaired device.";
                    if ($sendmail_repr == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult['realname'];
                        $mail->email = $outputResult['email'];
                        $mail->vehicleno = $outputResult['vehicleno'];
                        $mail->unitno = $outputResult['unitnumber'];
                        $mail->simcard = $outputResult['simcardno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $comment;
                        $status = SendEmailRepair($mail);
                        if ($status == 1) {
                            $reg_message = "Successfully repaired device.Mail sent";
                        } else {
                            $reg_message = "Successfully repaired device.Mail not sent";
                        }
                    }
                } else {
                    $reg_message = "Bucket updated successfully.";
                }
            } else {
                $reg_message = "Bucket updation failed.";
            }
        } else {
            $reg_message = "Old unit not present.Repair failed.";
        }
        header("Location: compliance.php?msg=" . $reg_message);
    } elseif ($purposeid == 3) {
        if (isset($_POST["unitid_rem"]) && !empty($_POST["unitid_rem"])) {
            $unitid_rem = GetSafeValueString($_POST["unitid_rem"], "string");
            $teamid_rem = GetSafeValueString($_POST["teamid_rem"], "string");
            $sendmail_rem = GetSafeValueString($_POST["sendmail_rem"], "string");

            $sp_params = "'" . $today . "'"
                    . ",'" . $customerno . "'"
                    . ",'" . $unitid_rem . "'"
                    . ",'" . $teamid_rem . "'"
                    . ",'" . GetLoggedInUserId() . "'"
                    . ",'" . $sstatus . "'"
                    . ",'" . $unsuccess_problem . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $bucketid . "'"
                    . ",'" . $comment . "'"
                    . ",'" . $docketid . "'"
                    . ",@is_executed,@username,@realname,@email,@vehicleno,@unitno,@simno,@elixir";

            $queryCallSP = "CALL " . speedConstants::SP_REMOVE_BOTH . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $db->ClosePDOConn($pdo);

            $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                        ,@username AS username
                                        ,@realname AS realname
                                        ,@email AS email
                                        ,@vehicleno AS vehicleno
                                        ,@unitno AS unitno
                                        ,@simno AS simno
                                        ,@elixir AS elixir";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

            if ($outputResult['is_executed'] == 1) {
                if ($sstatus == 2) {
                    $reg_message = "Successfully removed device.";
                    // Create unit directory
                    $oldunitno = $outputResult['unitno'];
                    $relativepath = "../..";
                    if (!is_dir($relativepath . '/customer/1/unitno/' . $oldunitno)) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $oldunitno, 0777, true) or die("Could not create directory");
                    }

                    if (!is_dir($relativepath . '/customer/1/unitno/' . $oldunitno . '/sqlite')) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $oldunitno . '/sqlite', 0777, true) or die("Could not create directory");
                    }

                    if ($sendmail_rem == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult['realname'];
                        $mail->email = $outputResult['email'];
                        $mail->vehicleno = $outputResult['vehicleno'];
                        $mail->unit = $outputResult['unitno'];
                        $mail->sim = $outputResult['simno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $comment;
                        $status = SendEmailRemoveBad($mail);
                        if ($status == 1) {
                            $reg_message = "Successfully removed device.Mail sent";
                        } else {
                            $reg_message = "Successfully removed device.Mail not sent";
                        }
                    }
                } else {
                    $reg_message = "Bucket updated successfully";
                }
            } else {
                $reg_message = "Bucket updation failed";
            }
        } else {
            $reg_message = "Old unit not present.Remove failed";
        }
        header("Location: compliance.php?msg=" . $reg_message);
    } elseif ($purposeid == 4) {
        if (isset($_POST["unitid_repl"]) && !empty($_POST["unitid_repl"])) {
            $teamid_repl = GetSafeValueString($_POST["teamid_repl"], "string");
            $sendmail_repl = GetSafeValueString($_POST["sendmail_repl"], "string");
            if (!empty($_POST["unitid_replace_to"]) && empty($_POST["simcardid_replace_to"])) {
                $oldunitid_repl = GetSafeValueString($_POST["unitid_repl"], "string");
                $newunitid_repl = GetSafeValueString($_POST["unitid_replace_to"], "string");

                $sp_params = "'" . $today . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $vehicleid . "'"
                        . ",'" . $oldunitid_repl . "'"
                        . ",'" . $teamid_repl . "'"
                        . ",'" . $newunitid_repl . "'"
                        . ",'" . GetLoggedInUserId() . "'"
                        . ",'" . $bucketid . "'"
                        . ",'" . $comment . "'"
                        . ",@is_executed,@username,@realname,@email,@vehicleno,@oldunitno,@newunitno,@simcardno,@elixir,@errormsgOut";

                $queryCallSP = "CALL " . speedConstants::SP_REPLACE_DEVICE . "($sp_params)";
                $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
                $db->ClosePDOConn($pdo);
                $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                                ,@username AS username
                                                ,@realname AS realname
                                                ,@email AS email
                                                ,@vehicleno AS vehicleno
                                                ,@oldunitno AS oldunitno
                                                ,@newunitno AS newunitno
                                                ,@simcardno AS simcardno
                                                ,@elixir AS elixir
                                                ,@errormsgOut AS errormsgOut";
                $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
                if ($outputResult['is_executed'] == 1) {
                    $reg_message = "Successfully replaced unit.";
                    // Create unit directory
                    $relativepath = "../..";
                    if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'])) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'], 0777, true) or die("Could not create directory");
                    }

                    if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite')) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                    }
                    // Create unit directory
                    $relativepath = "../..";
                    if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'])) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'], 0777, true) or die("Could not create directory");
                    }

                    if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite')) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                    }

                    if ($sendmail_repl == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult['realname'];
                        $mail->email = $outputResult['email'];
                        $mail->vehicleno = $outputResult['vehicleno'];
                        $mail->oldunitno = $outputResult['oldunitno'];
                        $mail->newunitno = $outputResult['newunitno'];
                        $mail->simcard = $outputResult['simcardno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $comment;
                        $mail->subject = 'Unit Replace Details';
                        $status = SendEmailReplaceDevice($mail);
                        if ($status == 1) {
                            $reg_message = "Successfully replace unit.Mail sent";
                        } else {
                            $reg_message = "Successfully replace unit.Mail not sent";
                        }
                    }
                } else {
                    if (!empty($outputResult['errormsgOut'])) {
                        $reg_message = $outputResult['errormsgOut'] . "Replace unit failed.";
                    } else {
                        $reg_message = "Replace unit failed.";
                    }
                }
                header("Location: compliance.php?msg=" . $reg_message);
            } elseif (empty($_POST["unitid_replace_to"]) && !empty($_POST["simcardid_replace_to"])) {
                $unitid_repl = GetSafeValueString($_POST["unitid_repl"], "string");
                $newsimid_repl = GetSafeValueString($_POST["simcardid_replace_to"], "string");

                $todaysdate = date("Y-m-d H:i:s");
                $sp_params = "'" . $today . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $vehicleid . "'"
                        . ",'" . $unitid_repl . "'"
                        . ",'" . $teamid_repl . "'"
                        . ",'" . $newsimid_repl . "'"
                        . ",'" . GetLoggedInUserId() . "'"
                        . ",'" . $bucketid . "'"
                        . ",'" . $comment . "'"
                        . ",@is_executed,@username,@realname,@email,@vehicleno,@oldsimcardno,@newsimcardno,@elixir";

                $queryCallSP = "CALL " . speedConstants::SP_REPLACE_SIM . "($sp_params)";

                $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
                $db->ClosePDOConn($pdo);
                $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                                ,@username AS username
                                                ,@realname AS realname
                                                ,@email AS email
                                                ,@vehicleno AS vehicleno
                                                ,@oldsimcardno AS oldsimcardno
                                                ,@newsimcardno AS newsimcardno
                                                ,@elixir AS elixir";
                $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

                if ($outputResult['is_executed'] == 1) {
                    $reg_message = "Successfully replaced simcard.";
                    if ($sendmailreplacesimcard == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult['realname'];
                        $mail->email = $outputResult['email'];
                        $mail->vehicleno = $outputResult['vehicleno'];
                        $mail->oldsim = $outputResult['oldsimcardno'];
                        $mail->newsim = $outputResult['newsimcardno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $comment;
                        $mail->subject = 'Simcard Replace Details';
                        $status = SendEmailReplaceSimcard($mail);
                        if ($status == 1) {
                            $reg_message = "Successfully replace simcard.Mail sent";
                        } else {
                            $reg_message = "Successfully replace simcard.Mail not sent";
                        }
                    }
                } else {
                    $reg_message = "Replace simcard failed.";
                }
                header("Location: compliance.php?msg=" . $reg_message);
            } elseif ((!empty($_POST["unitid_replace_to"]) && !empty($_POST["simcardid_replace_to"])) || (empty($_POST["unitid_replace_to"]) && empty($_POST["simcardid_replace_to"]))) {
                $oldunitid_repl = GetSafeValueString($_POST["unitid_repl"], "string");
                $newunitid_repl = !empty($_POST["unitid_replace_to"]) ? GetSafeValueString($_POST["unitid_replace_to"], "string") : 0;
                $newsimid_repl = !empty($_POST["simcardid_replace_to"]) ? GetSafeValueString($_POST["simcardid_replace_to"], "string") : 0;
                $sp_params = "'" . $today . "'"
                        . ",'" . $customerno . "'"
                        . ",'" . $vehicleid . "'"
                        . ",'" . $oldunitid_repl . "'"
                        . ",'" . $teamid_repl . "'"
                        . ",'" . $newunitid_repl . "'"
                        . ",'" . $newsimid_repl . "'"
                        . ",'" . GetLoggedInUserId() . "'"
                        . ",'" . $sstatus . "'"
                        . ",'" . $unsuccess_problem . "'"
                        . ",'" . $reschedule_date . "'"
                        . ",'" . $reschedule_date . "'"
                        . ",'" . $bucketid . "'"
                        . ",'" . $comment . "'"
                        . ",@is_executed,@username,@realname,@email,@vehicleno,@oldunitno,@oldsimno,@newunitno,@newsimno,@elixir,@errormsg";

                $queryCallSP = "CALL " . speedConstants::SP_REPLACE_BOTH . "($sp_params)";
                $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
                $db->ClosePDOConn($pdo);
                $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                                ,@username AS username
                                                ,@realname AS realname
                                                ,@email AS email
                                                ,@vehicleno AS vehicleno
                                                ,@oldunitno AS oldunitno
                                                ,@oldsimno AS oldsimno
                                                ,@newunitno AS newunitno
                                                ,@newsimno AS newsimno
                                                ,@elixir AS elixir
                                                ,@errormsg AS errormsg";
                $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

                if ($outputResult['is_executed'] == 1) {
                    if ($sstatus == 2) {
                        $reg_message = "Successfully replaced unit and simcard.";
                        // Create unit directory
                        $relativepath = "../..";
                        if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'])) {
                            // Directory doesn't exist.
                            mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'], 0777, true) or die("Could not create directory");
                        }

                        if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite')) {
                            // Directory doesn't exist.
                            mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                        }

                        // Create unit directory
                        $relativepath = "../..";
                        if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'])) {
                            // Directory doesn't exist.
                            mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'], 0777, true) or die("Could not create directory");
                        }

                        if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite')) {
                            // Directory doesn't exist.
                            mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                        }

                        if ($sendmail_repl == 1) {
                            $mail = new stdClass();
                            $mail->username = $outputResult['username'];
                            $mail->realname = $outputResult['realname'];
                            $mail->email = $outputResult['email'];
                            $mail->vehicleno = $outputResult['vehicleno'];
                            $mail->oldunit = $outputResult['oldunitno'];
                            $mail->oldsim = $outputResult['oldsimno'];
                            $mail->newunit = $outputResult['newunitno'];
                            $mail->newsim = $outputResult['newsimno'];
                            $mail->elixir = $outputResult['elixir'];
                            $mail->comments = $comment;
                            $mail->subject = 'Unit & Simcard Replace Details';
                            $status = SendEmailReplaceBoth($mail);
                            if ($status == 1) {
                                $reg_message = "Successfully replace unit and simcard.Mail sent";
                            } else {
                                $reg_message = "Successfully replace unit and simcard.Mail not sent";
                            }
                        }
                    } else {
                        $reg_message = "Bucket updated successfully";
                    }
                } else {
                    if (!empty($outputResult['errormsg'])) {
                        $reg_message = $outputResult['errormsg'] . "Bucket updation failed.";
                    } else {
                        $reg_message = "Bucket updation failed";
                    }
                }
            }
        } else {
            $reg_message = "Old unit not present.Replacement failed.";
        }
        header("Location: compliance.php?msg=" . $reg_message);
    } elseif ($purposeid == 5) {
        if (isset($_POST["unitid_rei"]) && !empty($_POST["unitid_rei"])) {
            $unitid_rei = GetSafeValueString($_POST["unitid_rei"], "string");
            $teamid_rei = GetSafeValueString($_POST["teamid_rei"], "string");
            $newvehicleno_rei = GetSafeValueString($_POST["newvehicleno_rei"], "string");
            $kind_rei = GetSafeValueString($_POST["kind_rei"], "string");
            $sendmail_rei = GetSafeValueString($_POST["sendmail_rei"], "string");

            $sp_params = "'" . $today . "'"
                    . ",'" . $unitid_rei . "'"
                    . ",'" . $teamid_rei . "'"
                    . ",'" . $newvehicleno_rei . "'"
                    . ",'" . $kind_rei . "'"
                    . ",'" . GetLoggedInUserId() . "'"
                    . ",'" . $sstatus . "'"
                    . ",'" . $unsuccess_problem . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $reschedule_date . "'"
                    . ",'" . $bucketid . "'"
                    . ",'" . $comment . "'"
                    . ",'" . $docketid . "'"
                    . ",@is_executed,@newvehicleno,@oldvehicleno,@username,@realname,@email,@elixir,@errormsg";

            $queryCallSP = "CALL " . speedConstants::SP_REINSTALLDEV . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@newvehicleno AS newvehicleno,@oldvehicleno AS oldvehicleno,@username AS username,@realname AS realname,@email AS email,@elixir AS elixir";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult["is_executed"] == 1) {
                $reg_message = "Successfully reinstall";
                if ($sstatus == 2) {
                    if ($sendmail_repl == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult['realname'];
                        $mail->email = $outputResult['email'];
                        $mail->oldvehicleno = $outputResult['oldvehicleno'];
                        $mail->newvehicleno = $outputResult['newvehicleno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $comment;
                        $status = SendEmailReinstall($mail);
                        if ($status == 1) {
                            $reg_message = "Successfully reinstall.Mail sent";
                        } else {
                            $reg_message = "Successfully reinstall.Mail not sent";
                        }
                    }
                } else {
                    $reg_message = "Bucket updated successfully";
                }
            } else {
                if (!empty($outputResult['errormsg'])) {
                    $reg_message = $outputResult['errormsg'] . "Bucket updation failed.";
                } else {
                    $reg_message = "Bucket updation failed.";
                }
            }
        } else {
            $reg_message = "Old unit not present.Reinstallation failed.";
        }
        header("Location: compliance.php?msg=" . $reg_message);
    } else {
        $reg_message = "Wrong purpose.";
        header("Location: compliance.php?msg=" . $reg_message);
    }
}

$sql = sprintf("SELECT  b.docketid,b.bucketid, b.apt_date, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location
                        , b.fe_id, sp.timeslot, b.purposeid, b.details, cp.person_name, cp.cp_phone1, t.name, b.created_by
                        , b.vehicleid, b.coordinatorid, b.timeslotid, te.name as installer, u.unitno, s.simcardno
                        , b.vehicleno as vehno, b.vehicleid , v.uid , d.simcardid
                FROM `bucket` b
                INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN " . DB_PARENT . ".sp_timeslot sp ON sp.tsid = b.timeslotid    
                LEFT OUTER JOIN " . DB_PARENT . ".contactperson_details cp ON cp.cpdetailid = b.coordinatorid    
                LEFT OUTER JOIN " . DB_PARENT . ".team t ON t.teamid = b.created_by    
                LEFT OUTER JOIN " . DB_PARENT . ".team te ON te.teamid = b.fe_id        
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN unit u ON u.uid = v.uid
                LEFT OUTER JOIN devices d ON d.uid = u.uid
                LEFT OUTER JOIN simcard s ON s.id = d.simcardid                
                where b.bucketid='%d'", $bucketid);
$db->executeQuery($sql);

if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $bucketid = $row["bucketid"];
        $apt_date = $row["apt_date"];
        $fe_id = $row["fe_id"];
        $customerno = $row["customerno"];
        $company = $row["customercompany"];
        $createdby = $row["created_by"];
        if ($row['priority'] == 1) {
            $priority = "High";
        }
        if ($row['priority'] == 2) {
            $priority = "Medium";
        }
        if ($row['priority'] == 3) {
            $priority = "Low";
        }
        $priorityid = $row['priority'];
        if ($row['vehicleid'] == 0) {
            $vehicleno = $row['vehno'];
        } else {
            $vehicleno = $row['vehicleno'];
        }
        $location = $row["location"];
        $timeslot = $row["timeslot"];
        $timeslotid = $row["timeslotid"];
        if ($row['purposeid'] == 1) {
            $purpose = "New Installation";
        }
        if ($row['purposeid'] == 2) {
            $purpose = "Repair";
        }
        if ($row['purposeid'] == 3) {
            $purpose = "Removal";
        }
        if ($row['purposeid'] == 4) {
            $purpose = "Replacement";
        }
        if ($row['purposeid'] == 5) {
            $purpose = "Reinstall";
        }
        $purposeid = $row['purposeid'];
        $details = $row["details"];
        $coordinatorid = $row['coordinatorid'];
        $person_name = $row['person_name'];
        $person_phone = $row['cp_phone1'];
        $created_by = $row["name"];
        $vehicleid = $row['vehicleid'];
        $installer = $row['installer'];
        $unitno = $row['unitno'];
        $simcardno = $row['simcardno'];
        if ($row["uid"] == null) {
            $unitid = 0;
        } else {
            $unitid = $row["uid"];
        }
        $simcardid = $row["simcardid"];
        $docketid = $row["docketid"];
    }
} else {
    header("Location: compliance.php");
    exit;
}

function SendEmail($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Unit Installation Details";
    $message = file_get_contents('../emailtemplates/registerDevice.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{UNITNO}}", $mail->unitnumber, $message);
    $message = str_replace("{{SIMCARD}}", $mail->simcardno, $message);
    $message = str_replace("{{INSTALLDATE}}", $mail->installdate, $message);
    $message = str_replace("{{EXPIRYDATE}}", $mail->expirydate, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function SendEmailRepair($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Unit Repair Details";
    $message = file_get_contents('../emailtemplates/repairDevice.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{UNITNO}}", $mail->unitnumber, $message);
    $message = str_replace("{{SIMCARD}}", $mail->simcardno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function SendEmailRemoveBad($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Bad Device remove Details";
    $message = file_get_contents('../../modules/emailtemplates/removeUnitSim.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{UNITNO}}", $mail->unitno, $message);
    $message = str_replace("{{SIMNO}}", $mail->simno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function SendEmailReplaceDevice($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Device replace details";
    $message = file_get_contents('../../modules/emailtemplates/replaceDevice.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{OLDUNITNO}}", $mail->oldunitno, $message);
    $message = str_replace("{{NEWUNITNO}}", $mail->newunitno, $message);
    $message = str_replace("{{SIMCARD}}", $mail->simcard, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function SendEmailReplaceSimcard($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Simcard replace details";
    $message = file_get_contents('../../modules/emailtemplates/replaceSim.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{OLDSIMCARD}}", $mail->oldsimcardno, $message);
    $message = str_replace("{{NEWSIMCARD}}", $mail->newsimcardno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function SendEmailReplaceBoth($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Device and simcard replace details";
    $message = file_get_contents('../../modules/emailtemplates/replaceUnitSim.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{OLDUNIT}}", $mail->oldunitno, $message);
    $message = str_replace("{{NEWUNIT}}", $mail->newunitno, $message);
    $message = str_replace("{{OLDSIM}}", $mail->oldsimno, $message);
    $message = str_replace("{{NEWSIM}}", $mail->newsimno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function SendEmailReinstall($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Device reinstall details";
    $message = file_get_contents('../../modules/emailtemplates/replaceUnitSim.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{OLDVEHICLENO}}", $mail->oldvehicleno, $message);
    $message = str_replace("{{NEWVEHICLENO}}", $mail->newvehicleno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Update Bucket List</div>
    <div class="panelcontents">
        <form method="post" id="form1" action="modifycompliance.php">
            <?php echo($message); ?>
            <input type="hidden" name = "id" value="<?php echo($bucketid) ?>"/>
            <input type="hidden" name = "feid" value="<?php echo($fe_id) ?>"/>
            <input type="hidden" name = "customerno" value="<?php echo($customerno) ?>"/>
            <input type="hidden" name = "createdby" value="<?php echo($createdby) ?>"/>
            <input type="hidden" name = "priorityid" value="<?php echo($priorityid) ?>"/>
            <input type="hidden" name = "oldunitid" value="<?php echo($unitid) ?>"/>
            <input type="hidden" name = "vehicleid" value="<?php echo($vehicleid) ?>"/>
            <input type="hidden" name = "location" value="<?php echo($location) ?>"/>
            <input type="hidden" name = "timeslotid" value="<?php echo($timeslotid) ?>"/>
            <input type="hidden" name = "purposeid" value="<?php echo($purposeid) ?>"/>
            <input type="hidden" name = "details" value="<?php echo($details) ?>"/>
            <input type="hidden" name = "coordinatorid" value="<?php echo($coordinatorid) ?>"/>
            <input type="hidden" name = "docketid" value="<?php echo($docketid) ?>"/>
            <table width="60%">
                <tr>
                    <td> Bucket Id: </td>
                    <td><b><?php echo "B00" . $bucketid; ?></b></td>
                </tr>
                <tr>
                    <td> Appointment Date: </td>
                    <td> <?php echo(date("d-m-Y", strtotime($apt_date))); ?> </td>
                </tr>

                <tr>
                    <td> Customer No: </td>
                    <td> <?php echo($customerno); ?> </td>
                </tr>

                <tr>
                    <td> Company: </td>
                    <td> <?php echo($company); ?> </td>
                </tr>

                <tr>
                    <td> Priority: </td>
                    <td> <?php echo($priority); ?> </td>
                </tr>

                <tr>
                    <td> Vehicle No: </td>
                    <td> <?php echo($vehicleno); ?> </td>
                </tr>

                <tr>
                    <td> Location: </td>
                    <td> <?php echo($location); ?> </td>
                </tr>

                <tr>
                    <td> Time Slot: </td>
                    <td> <?php echo($timeslot); ?> </td>
                </tr>

                <tr>
                    <td> Purpose: </td>
                    <td> <b><?php echo($purpose); ?></b> </td>
                </tr>

                <tr>
                    <td> Details: </td>
                    <td> <?php echo($details); ?> </td>
                </tr>

                <tr>
                    <td> Co-ordinator Name: </td>
                    <td> <?php echo($person_name); ?> </td>
                </tr>

                <tr>
                    <td> Co-ordinator Phone: </td>
                    <td> <?php echo($person_phone); ?> </td>
                </tr>

                <tr>
                    <td> Created By: </td>
                    <td> <?php echo($created_by); ?> </td>
                </tr>

                <tr>
                    <td> Installer: </td>
                    <td> <?php echo($installer); ?> </td>
                </tr>

                <tr>
                    <td> Unit No: </td>
                    <td> <?php echo($unitno); ?> </td>
                </tr>

                <tr>
                    <td> Simcard No: </td>
                    <td> <?php echo($simcardno); ?> </td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td><select name="sstatus" id="sstatus" onchange="changeFunc(value,<?php echo $purposeid . "," . $unitid; ?>);">
                            <option value="-1" selected>Select status</option>
                            <option value="2">Successful</option>
                            <option value="3" >Unsuccessful</option>
                            <option value="6">Incomplete</option>
                            <option value="1" >Reschedule</option>        
                            <option value="5" >Cancel</option>                    
                        </select>
                    </td>
                </tr>


                <tr id="c_reschedule" style="display: none;">
                    <?php
                    $apt_date = date('d-m-Y', strtotime("+ 1 day"));
                    ?>    
                    <td>Reschedule Date </td>
                    <td> <input name="reschedule_date" id="reschedule_date" type="text" value="<?php echo $apt_date; ?>"/><button id="trigger10">...</button>
                    </td>
                </tr>       

                <tr id="c_problem" style="display: none;">
                    <td>Primary Problem Created By</td>
                    <td><select name="sproblem" id="sproblem">
                            <option value="1" selected>Elixir</option>
                            <option value="2" >Customer</option>
                        </select>
                    </td>
                </tr>

            </table>
            <br>
            <table id="register_device" name="register_device" style="display: none;">
                <tr><td><span id="registermsg" style="display: none;font-size: 120%;"><?php
                            $reg_message = isset($_GET["msg"]) ? $_GET["msg"] : '';
                            if ($reg_message != '') {
                                echo $reg_message;
                            }
                            ?></span></td><td></td></tr>

                <tr><td>PO No.</td>
                    <td> <input name="cpono_reg" id="cpono_reg" type="text"/>
                    </td></tr>

                <?php
                $podate_reg = date('d-m-Y');
                ?>

                <tr><td>PO Date </td>
                    <td> <input name="podate_reg" id="podate_reg" type="text" value=""/><button id="trigger3">...</button>
                    </td></tr>


                <tr><td>Unit No. <span style="color:red;">*</span><input type="hidden" id="teamid_reg" name="teamid_reg" value="<?php
                        if (isset($fe_id)) {
                            echo $fe_id;
                        }
                        ?>"></td>
                    <td id="uready_td"><input  type="text" name="unitno_reg" id="unitno_reg" size="20" value="" autocomplete="off" placeholder="Enter Unit No" onkeyup="getUnit()"/>
                        ( Allotted Device List with selected Elixir)
                        <input type="hidden" id="unitid_reg" name="unitid_reg">
                    </td></tr>



                <tr><td>Sim Card No. <span style="color:red;">*</span></td>
                    <td id="simready_td"><input  type="text" name="simno" id="simno" size="20" value="" autocomplete="off" placeholder="Enter Simcard No" onkeyup="getSim()"/>
                        ( Allotted Simcard List with selected Elixir)
                        <input type="hidden" id="simid_reg" name="simid_reg">
                    </td></tr>



                <tr><td>Type</td>
                    <td><select name="utype_reg" id="utype_reg">
                            <option value="5" selected>Installed</option>
                            <option value="22">Not Installed</option>
                            <option value="23">Demo</option>
                        </select>
                    </td></tr>


                <?php
                $installation = date('d-m-Y');
                ?>

                <tr><td>Installation Date </td>
                    <td> <input name="installation_reg" id="installation_reg" type="text" value="<?php echo $installation; ?>"/><button id="trigger2">...</button>
                    </td></tr>


                <?php
                $expiry_reg = date('d-m-Y', strtotime('+1 year'));
                ?>
                
                <tr><td>Expiry Date </td>
                    <td> <input name="expiry_reg" id="expiry_reg" type="text" value="<?php echo $expiry_reg; ?>"/><button id="trigger">...</button>
                    </td></tr>

                <tr><td>Invoice No. </td>
                    <td> <input name="invoiceno_reg" id="invoiceno_reg" type="text"/>
                    </td></tr>

                <tr><td>Vehicle No. </td>
                    <td> <input name="vehicleno_reg" id="vehicleno_reg" type="text"/>(Compulsory Field for Service Call Analysis)
                    </td>
                </tr>

                <tr>
                    <td>Kind</td>
                    <td>
                        <select name="kind_reg">
                            <option value="Car">Car</option>
                            <option value="Truck" selected>Truck</option>
                            <option value="Bus">Bus</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Tanker">Tanker</option>
                        </select>
                    </td>
                </tr>

                <tr><td>Unit On Lease</td>
                    <td>
                        <input type="checkbox" name="lease_reg" id="lease_reg" value="1">
                    </td></tr>

                <tr><td>Send Mail</td><td><input type="checkbox" name = "sendmail_reg" id="sendmail_reg" value="1"></td></tr>

            </table>

            <table id="repair" name="repair" style="display: none;">
                <tr><td>
                        <input type="hidden" id="unitid_repr" name="unitid_repr"/>
                        <input type="hidden" name="simcardid_repr" value="<?php echo( $simcardid ); ?>"/>
                        <input type="hidden" name="teamid_repr" value="<?php echo ($fe_id); ?> "/>
                    </td></tr>
                <tr><td><font size="2">Send Email</font></td><td><input type="checkbox" name = "sendmail_repr" id="sendmail_repr" value="1"></td></tr> 
            </table>

            <table id="removal" width="100%" style="display: none;">
                <tr>
                    <td>
                        <input type="hidden" id="unitid_rem" name="unitid_rem"/>
                        <input type="hidden" name="teamid_rem" value="<?php echo( $fe_id ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Send Email</td>
                    <td><input type="checkbox" name = "sendmail_rem" id="sendmail_rem" value="1"></td>
                </tr>
            </table>

            <table id="replacement" style="display: none;" width="100%">    
                <tr>
                    <td>
                        <input type="hidden" id="unitid_repl" name="unitid_repl" value=""/>
                        <input type="hidden" name="teamid_repl" id="teamid_repl" value="<?php echo( $fe_id ); ?>"/>
                    </td>
                    <td>
                    </td>
                </tr>

                <tr>
                    <td>Unit No. <span style='color:red;'>*</span></td>
                    <td id="uready_replace_both_td">
                        <input  type="text" name="unitno_repl" id="unitno_repl" size="20" value="" autocomplete="off" placeholder="Enter Unit No" onkeyup="getUnitReplace()"/>
                        ( Search Devices with selected F.E.)
                        <input type="hidden" id="unitid_replace_to" name="unitid_replace_to">

                    </td>
                </tr>

                <tr>
                    <td>Sim Card No. <span style='color:red;'>*</span></td>
                    <td id="simready_replace_both_td">

                        <input  type="text" name="simno_repl" id="simno_repl" size="20" value="" autocomplete="off" placeholder="Enter Simcard No" onkeyup="getSimReplace()"/>
                        ( Search Simcard List with selected F.E.)
                        <input type="hidden" id="simcardid_replace_to" name="simcardid_replace_to">

                    </td>
                </tr>
                <tr>
                    <td>Send Email</td>
                    <td><input type="checkbox" name = "sendmail_repl" id="sendmail_repl" value="1"></td>
                </tr>
            </table>

            <table id="reinstall" style="display: none;" width="100%">
                <tr>
                    <td><input type="hidden" id="unitid_rei" name="unitid_rei"/>
                        <input type="hidden" name="teamid_rei" value="<?php echo( $fe_id ); ?>"/>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>New Vehicle No<span style='color:red;'>*</span></td>
                    <td><input type="text" name = "newvehicleno_rei" id="newvehicleno_rei"></td>
                </tr>
                <tr>
                    <td>Kind</td>
                    <td>
                        <select name="kind_rei">
                            <option value="Car">Car</option>
                            <option value="Truck" selected>Truck</option>
                            <option value="Bus">Bus</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Tanker">Tanker</option>
                        </select>
                    </td>
                </tr>                
                <tr>
                    <td>Send Email</td>
                    <td><input type="checkbox" name = "sendmail_rei" id="sendmail_rei" value="1"></td>
                </tr>
            </table>

            <table style="display: none;" id="commentTable">
                <tr><td>Comment :</td><td><input id="comment" name="comment" type="text" required/></td></tr>
            </table>
            <input type="submit" id="final_submit" name="save" value="Save Details" onclick="validateCompliance();" />
        </form>
    </div>
</div>
<?php
include("footer.php");
?>
<script>
    Calendar.setup(
            {
                inputField: "reschedule_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger10" // ID of the button
            });

    function changeFunc(i, purpose, unitid) {
        if (i == 1)
        {
            $("#c_reschedule").show();
            $("#c_problem").hide();
            $("#register_device").hide();
            $("#repair").hide();
            $("#removal").hide();
            $("#replacement").hide();
            $("#reinstall").hide();
            $("#commentTable").show();
        }
        else if (i == 5)
        {
            $("#c_reschedule").hide();
            $("#c_problem").hide();
            $("#register_device").hide();
            $("#repair").hide();
            $("#removal").hide();
            $("#replacement").hide();
            $("#reinstall").hide();
            $("#commentTable").show();
        }
        else if (i == 2)
        {
            if (purpose == 1) {
                $("#register_device").show();
                $("#repair").hide();
                $("#removal").hide();
                $("#replacement").hide();
                $("#reinstall").hide();
            } else if (purpose == 2) {
                $("#repair").show();
                $("#register_device").hide();
                $("#removal").hide();
                $("#replacement").hide();
                $("#reinstall").hide();
            } else if (purpose == 3) {
                $("#removal").show();
                $("#register_device").hide();
                $("#repair").hide();
                $("#replacement").hide();
                $("#reinstall").hide();
            } else if (purpose == 4) {
                $("#replacement").show();
                $("#register_device").hide();
                $("#repair").hide();
                $("#removal").hide();
                $("#reinstall").hide();
            } else if (purpose == 5) {
                $("#reinstall").show();
                $("#register_device").hide();
                $("#repair").hide();
                $("#removal").hide();
                $("#replacement").hide();
            }
            $("#c_reschedule").hide();
            $("#c_problem").hide();
            $("#commentTable").show();
        } else if (i == 3) {
            $("#c_problem").show();
            $("#c_reschedule").hide();
            $("#register_device").hide();
            $("#repair").hide();
            $("#removal").hide();
            $("#replacement").hide();
            $("#reinstall").hide();
            $("#commentTable").show();
        } else if (i == 6) {
            $("#c_reschedule").show();
            $("#c_problem").hide();
            $("#register_device").hide();
            $("#repair").hide();
            $("#removal").hide();
            $("#replacement").hide();
            $("#reinstall").hide();
            $("#commentTable").show();
        } else {
            $("#c_reschedule").hide();
            $("#c_problem").hide();
            $("#register_device").hide();
            $("#repair").hide();
            $("#removal").hide();
            $("#replacement").hide();
            $("#reinstall").hide();
            $("#commentTable").hide();
        }

        if (purpose == 1) {
            $("#unitid_reg").val(unitid);
        } else if (purpose == 2) {
            $("#unitid_repr").val(unitid);
        } else if (purpose == 3) {
            $("#unitid_rem").val(unitid);
        } else if (purpose == 4) {
            $("#unitid_repl").val(unitid);
        } else if (purpose == 5) {
            $("#unitid_rei").val(unitid);
        }
    }
    function getUnit() {
        var data = $('#teamid_reg').val();
        jQuery("#unitno_reg").autocomplete({
            source: "route_ajax.php?uteamid_returnall=" + data,
            select: function (event, ui) {

                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#unitid_reg').val(ui.item.uid)
                return false;
            }
        });
    }
    function getSim() {
        var data = $('#teamid_reg').val();
        jQuery("#simno").autocomplete({
            source: "route_ajax.php?steamid_all=" + data,
            select: function (event, ui) {
                //                    insertUnit(ui.item.value, ui.item.uid);
                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#simid_reg').val(ui.item.sid)
                return false;
            }
        });
    }
    function getUnitReplace() {

        var data = $('#teamid_repl').val();

        jQuery("#unitno_repl").autocomplete({
            source: "route_ajax.php?unit_repl=" + data,
            select: function (event, ui) {

                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#unitid_replace_to').val(ui.item.uid)
                return false;
            }
        });
    }
    function getSimReplace() {
        var teamid = $('#teamid_repl').val();
        var unitid = $('#unitid_replace_to').val();

        jQuery("#simno_repl").autocomplete({
            source: "route_ajax.php?simcard_repl=" + teamid + "&unitid=" + unitid,
            select: function (event, ui) {

                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#simcardid_replace_to').val(ui.item.sid);
                return false;
            }
        });
    }

function validateCompliance(){
    var purposeid = <?php echo $purposeid ?>;
    var comment = jQuery("#comment").val();
    if(purposeid==4){
      var simcard_value=jQuery('#simcardid_replace_to').val();
      var unit_no = jQuery('#unitid_replace_to').val();
      var unit_input_box = jQuery("#unitno_repl").val();
      var sim_input_box = jQuery("#simno_repl").val();
      

      
     if(unit_no=='' && unit_input_box!=''){
            alert("Please Select Unit From Dropdown");
            document.getElementById("final_submit").addEventListener("click", function(event){
                event.preventDefault()
            });
      }
      else if(simcard_value=='' && sim_input_box!=''){
            alert("Please Select Simcard From Dropdown");
            document.getElementById("final_submit").addEventListener("click", function(event){
                event.preventDefault()
            });
      }
      else if(comment==''){
            alert("Please Add Comment");
            document.getElementById("final_submit").addEventListener("click", function(event){
                event.preventDefault()
            });
      }
      else{
            jQuery("#form1").submit();
      }
    }
    else if(comment==''){
            alert("Please Add Comment");
            document.getElementById("final_submit").addEventListener("click", function(event){
                event.preventDefault()
            });
        }
    
    else{
        jQuery("#form1").submit();
    }    
      
    

}
</script>