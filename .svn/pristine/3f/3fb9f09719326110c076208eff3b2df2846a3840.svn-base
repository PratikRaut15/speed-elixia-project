<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/CommunicationQueueManager.php';
include_once 'files/dailyreport.php';

class VODatacap {

}

$cm = new CustomerManager();
$customernos = $cm->getcustomernos_ForMaintenance();

$today = date('Y-m-d');
if (isset($customernos)){
    foreach ($customernos as $thiscustomerno) {
        $vm = new VehicleManager($thiscustomerno);
        $vehicles = $vm->getAlertVehiclesByCustomer();

        $usrman = new UserManager($thiscustomerno);
        $customname = $usrman->store_custom_name($thiscustomerno, 'Group', 13);
        if (isset($vehicles)&& !empty($vehicles)){

            foreach ($vehicles as $vehicle) {
                $pucday = date_SDiff_cmn($vehicle->puc_expiry, $today, 'GMT', 'm');
                $regday = date_SDiff_cmn($vehicle->reg_expiry, $today, 'GMT', 'm');
                $insuranceday = date_SDiff_cmn($vehicle->insurance_expiry, $today, 'GMT', 'm');
                $other1day = date_SDiff_cmn($vehicle->other1_expiry, $today, 'GMT', 'm');
                $other2day = date_SDiff_cmn($vehicle->other2_expiry, $today, 'GMT', 'm');
                $other3day = date_SDiff_cmn($vehicle->other3_expiry, $today, 'GMT', 'm');
                $other4day = date_SDiff_cmn($vehicle->other4_expiry, $today, 'GMT', 'm');
                $other5day = date_SDiff_cmn($vehicle->other5_expiry, $today, 'GMT', 'm');
                $other6day = date_SDiff_cmn($vehicle->other6_expiry, $today, 'GMT', 'm');

                if (strtotime($vehicle->puc_expiry) >= strtotime($today) && $vehicle->puc_expiry != '0000-00-00 00:00:00' && $vehicle->puc_sms_email == $pucday){
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    $customname = $um->store_custom_name($thiscustomerno, 'Group', 13);

                    if (isset($users)) {
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->puc_expiry;
                                $mail->document = "PUC ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "PUC Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "PUC Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->puc_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;

                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->reg_expiry) >= strtotime($today) && $vehicle->reg_expiry != '0000-00-00 00:00:00' && $vehicle->reg_sms_email == $regday) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    if (isset($users)) {
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->reg_expiry;
                                $mail->document = "Registration ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Registration Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Registration Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->reg_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;
                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->insurance_expiry) >= strtotime($today) && $vehicle->insurance_expiry != '0000-00-00 00:00:00' && $vehicle->insurance_sms_email == $insuranceday) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    if (isset($users)) {
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->insurance_expiry;
                                $mail->document = "Insurance ";
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Insurance Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Insurance Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->insurance_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;

                                $check = $um->checkGroupman($user->customerno, $user->userid);

                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->other1_expiry) >= strtotime($today) && $vehicle->other1_expiry != '0000-00-00 00:00:00' && $vehicle->other1_sms_email == $other1day) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    if (isset($users)) {
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other1_expiry;
                                if ($vehicle->other1 == '') {
                                    $mail->document = "Other Document 1 ";
                                } else {
                                    $mail->document = $vehicle->other1;
                                }
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 1 Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Document 1 Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->other3_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;
                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->other2_expiry) >= strtotime($today) && $vehicle->other2_expiry != '0000-00-00 00:00:00' && $vehicle->other2_sms_email == $other2day) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);

                    if (isset($users)) {
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other2_expiry;
                                if ($vehicle->other2 == '') {
                                    $mail->document = "Other Document 2 ";
                                } else {
                                    $mail->document = $vehicle->other2;
                                }
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 2 Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Document 2 Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->other3_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;
                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->other3_expiry) >= strtotime($today) && $vehicle->other3_expiry != '0000-00-00 00:00:00' && $vehicle->other3_sms_email == $other3day) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    if (isset($users)) {
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other3_expiry;
                                if ($vehicle->other3 == '') {
                                    $mail->document = "Other Document 3 ";
                                } else {
                                    $mail->document = $vehicle->other3;
                                }
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 3 Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Document 3 Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->other3_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;
                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->other4_expiry) >= strtotime($today) && $vehicle->other4_expiry != '0000-00-00 00:00:00' && $vehicle->other4_sms_email == $other4day) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    if (isset($users)) {
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other3_expiry;
                                if ($vehicle->other3 == '') {
                                    $mail->document = "Other Document 4 ";
                                } else {
                                    $mail->document = $vehicle->other4;
                                }
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 4 Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Document 4 Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->other4_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;
                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->other5_expiry) >= strtotime($today) && $vehicle->other5_expiry != '0000-00-00 00:00:00' && $vehicle->other5_sms_email == $other5day) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    if (isset($users)){
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other5_expiry;
                                if ($vehicle->other5 == '') {
                                    $mail->document = "Other Document 5 ";
                                } else {
                                    $mail->document = $vehicle->other5;
                                }
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 5 Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Document 5 Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->other5_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;
                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                if (strtotime($vehicle->other6_expiry) >= strtotime($today) && $vehicle->other6_expiry != '0000-00-00 00:00:00' && $vehicle->other6_sms_email == $other6day) {
                    $um = new UserManager($thiscustomerno);
                    $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                    if (isset($users)){
                        foreach ($users as $user) {
                            if ($user->email != '' || $user->phone != '') {
                                $mail = new VODatacap();
                                $mail->vehicleno = $vehicle->vehicleno;
                                $mail->expiry = $vehicle->other6_expiry;
                                if ($vehicle->other6 == '') {
                                    $mail->document = "Other Document 6 ";
                                } else {
                                    $mail->document = $vehicle->other6;
                                }
                                $mail->username = $user->username;
                                $mail->realname = $user->realname;
                                $mail->email = $user->email;
                                $mail->phone = $user->phone;
                                $mail->subject = "Document 6 Expiry Reminder For " . $vehicle->vehicleno;
                                $mail->message = "Document 6 Expire For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($vehicle->other6_expiry)) . " \nPowerd By Elixia Tech";
                                $mail->groupname = $vehicle->groupname;
                                $mail->customname = $customname;
                                $mail->customerno = $user->customerno;
                                $mail->userid = $user->userid;
                                $mail->vehicleid = $vehicle->vehicleid;
                                $check = $um->checkGroupman($user->customerno, $user->userid);
                                if ($check == 1) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                    if ($mail->email != '') {
                                        Send_Email($mail);
                                    }
                                    if ($mail->phone != '') {
                                        sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                    }
                                }
                            }
                        }
                    }
                }

                $taxalert = $vm->getTaxAlert($vehicle->vehicleid);
                if (isset($taxalert) && !empty($taxalert)) {
                    foreach ($taxalert as $talert) {
                        $taxday = date_SDiff_cmn($talert->tax_expiry, $today, 'GMT', 'm');
                        if (strtotime($talert->tax_expiry) >= strtotime($today) && $talert->tax_expiry != '0000-00-00' && $talert->tax_sms_email == $taxday) {

                            $um = new UserManager($thiscustomerno);
                            $users = $um->getusersforcustomerformaintenance($thiscustomerno, $vehicle->vehicleid);
                            if (isset($users)) {
                                foreach ($users as $user) {
                                    if ($user->email != '' || $user->phone != '') {
                                        $mail = new VODatacap();
                                        $mail->vehicleno = $vehicle->vehicleno;
                                        $mail->expiry = $talert->tax_expiry;
                                        $mail->document = "Tax Renewal ";
                                        $mail->username = $user->username;
                                        $mail->realname = $user->realname;
                                        $mail->email = $user->email;
                                        $mail->phone = $user->phone;
                                        $mail->subject = "Tax Renewals For " . $vehicle->vehicleno;
                                        $mail->message = "Tax Renewals For " . $vehicle->vehicleno . " On " . date('d-m-Y', strtotime($talert->tax_expiry)) . " \nPowerd By Elixia Tech";
                                        $mail->groupname = $vehicle->groupname;
                                        $mail->customname = $customname;
                                        $mail->customerno = $user->customerno;
                                        $mail->userid = $user->userid;
                                        $mail->vehicleid = $vehicle->vehicleid;
                                        $check = $um->checkGroupman($user->customerno, $user->userid);
                                        if ($check == 1) {
                                            if ($mail->email != ''){
                                                Send_Email($mail);
                                            }
                                            if ($mail->phone != ''){
                                                sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
                                            }
                                        } elseif (in_array($vehicle->groupid, $check) && $check != 0) {
                                            if ($mail->email != '') {
                                                Send_Email($mail);
                                            }
                                            if ($mail->phone != '') {
                                                sendSMS($mail->phone, $mail->message, $thiscustomerno, $user->userid, $vehicle->vehicleid);
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
    }
}

function Send_Email($mail) {
    $message ='';
    $message .= '<html><body>';
    $message .= 'Dear ' . $mail->realname . ' ,<br>';
    $message .= '<p></p></br>';
    $message .= 'Greetings from Elixia Tech!<br/>';
    $message .= 'Please find the Document detail which are about to expire. <br/></br>';
    $message .= '<table style="border:1px solid #ccc;" cellspacing="0" cellpadding="0">
                                        <tr style="background-color:#ccc;height:25px;">
                                            <td colspan="4" id="formheader" style="text-align:center; border:1px solid #ccc; padding:5px;">Document Expiry Reminder</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Vehicle No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->customname . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Document Name</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Expiry Date</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->vehicleno . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->groupname . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->document . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . date('d-m-Y', strtotime($mail->expiry)) . '</td>
                                        </tr>
                                     </table></br></br></br>';
    $message .='</body></html>';
    sendMail($mail->email, $mail->subject, $message);

    $status = sendMail($mail->email, $mail->subject, $message);
    $cm = new CustomerManager();
    if($status==1){  //error
        $emailsend = 0;
    }else{
        $emailsend = 1;
    }
    $obj = new stdClass();
    $todaydatetime = date('Y-m-d H:i:s');
    $obj->email = $mail->email;
    $obj->isMailSent = $emailsend;
    $obj->subject = $mail->subject;
    $obj->message = $mail->message;
    $obj->vehicleid = $mail->vehicleid;
    $obj->today = $todaydatetime;
    $obj->userid = $mail->userid;
    $obj->type = 0;
    $obj->moduleid = 2;
    $obj->customerno = $mail->customerno;
    $cm->insertCustomerEmailLog($obj);
}

function sendMail($to, $subject, $content){
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    include_once("class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->AddAddress($to);
    $mail->AddCC('sanketsheth@elixiatech.com');
    $mail->From = "noreply@elixiatech.com\r\n";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com\r\n";
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->IsHTML(true);
    $mail->AddReplyTo("From: noreply@elixiatech.com\r\n", "Elixia Speed");
    if (!$mail->Send()) {
       // echo "Error sending: " . $mail->ErrorInfo;
        $content = '';
        $status = '1'; //error
    } else {
        //echo "Mail sent";
        $content = '';
        $status = '0'; //sent
    }
    return $status;
}

function sendSMS($phone, $message, $customerno, $userid, $vehicleid) {
    $cm = new CustomerManager();
    $smsStatus = new SmsStatus();
    $smsStatus->customerno = $customerno;
    $smsStatus->userid = $userid;
    $smsStatus->vehicleid = $vehicleid;
    $smsStatus->mobileno = $phone;
    $smsStatus->message = $message;
    $smsStatus->cqid = 0;
    $smsstat = $cm->getSMSStatus($smsStatus);
    if ($smsstat == 0) {
        $response = '';
        $isSmsSent = sendSMSUtil($phone, $message, $response);
        if ($isSmsSent == 1) {
            $cm->sentSmsPostProcess($customerno, $phone, $message, $response, $isSmsSent, $userid, $vehicleid, 2);
        }
        return true;
    } else {
        return false;
    }
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

function datediff_cmn($STdate, $EDdate) {
    if (strtotime($STdate) > strtotime($EDdate)) {
        return 0;
    } else {
        return 1;
    }
}
