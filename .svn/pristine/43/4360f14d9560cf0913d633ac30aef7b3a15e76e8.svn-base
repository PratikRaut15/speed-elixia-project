<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    define('CHECK_COUNT', "10");
    define('DISTANCE', "40");
    define("DATEFORMAT_YMD", "Y-m-d");
    define("DATEFORMAT_his", "h:i:s");

    define("STABILITY_PERCENT", "3");
    #### DO NOT CHANGE INACCCURACY PERCENT. IN CASE CHANGE IS REQUIRED, CHANGE LISTENER SP ####
    define("INACCURACY_PERCENT_CONSUMPTION", "5");
    define("INACCURACY_PERCENT_FOR_THEFT", "5");
    define("INACCURACY_PERCENT_FOR_REFILL", "10");

    /*
    Fuel alert types
     * 1)  alerttype = 1  --> Thresholdalert
     * 2)  alerttype = 2  --> Refuel alert
     * 3)  alerttype = 3  --> Theft alert
     * */
    // on testing - customerno - 487 --> MH 15 EG 6227 vehicleid= 9380 - remove on for all vehicle
    require "../../lib/system/utilities.php";
    require '../../lib/autoload.php';

    $objUserManager = new UserManager();
    $users = $objUserManager->getUsersForReportFuel();
    $customerUserArray = cronCustomerUsersFuel($users);
    //print_r($customerUserArray);
    $curtime = date('Y-m-d H:i:s');
    $objCustomerManager = new CustomerManager();
    $objUserManager = new UserManager();
    if (isset($customerUserArray) && !empty($customerUserArray)) {
        foreach ($customerUserArray as $customer => $customerDetails) {
            $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
            $vehicleManager = new VehicleManager($customer);
            //print_r($customerDetails);
            foreach ($customerDetails as $userDetails) {
                foreach ($userDetails as $user) {
                    if ($user['email'] != '') {
                        $useremailid = $user['email'];
                        $realname = $user['realname'];

                        $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                        //print_r($userGroups);
                        $arrGroups = array();
                        if (isset($userGroups) && !empty($userGroups)) {
                            foreach ($userGroups as $group) {
                                $arrGroups[] = $group->groupid;
                            }
                        }
                        $vehicleManager = new VehicleManager($customer);
                        $vehicles = $vehicleManager->get_all_vehicles_by_group_fuel($arrGroups);
                        //print_r($vehicles);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $objData = new stdClass();
                                $objData->vehicleno = $vehicle->vehicleno;
                                $objData->vehicleid = $vehicle->vehicleid;
                                $objData->fuelcapacity = $vehicle->fuelcapacity;
                                $objData->fuelvehicleid = isset($vehicle->fuelvehicleid) ? $vehicle->fuelvehicleid : 0;
                                $objData->fuel_balance = $vehicle->fuel_balance;
                                $objData->old_fuel_balance1 = isset($vehicle->old_fuel_balance) ? $vehicle->old_fuel_balance : '';
                                $objData->old_fuel_balance = isset($vehicle->old_fuel_balance) ? $vehicle->old_fuel_balance : '';
                                $objData->old_odometer = isset($vehicle->old_odometer) ? $vehicle->old_odometer : 0;
                                $objData->odometer = isset($vehicle->odometer) ? $vehicle->odometer : 0;
                                $objData->old_fuel_odometer = isset($vehicle->old_fuel_odometer) ? $vehicle->old_fuel_odometer : 0;
                                $objData->fuelreading = $vehicle->fuelreading;
                                $objData->countcheck = $vehicle->countcheck;
                                $objData->deltaSum = $vehicle->deltaSum;
                                $objData->average = $vehicle->average;
                                $objData->isAlert = 0;
                                $objData->isOldBalanceUpdate = 0;
                                $objData->stabilityDiff = 0;
                                $objData->diffInFuel = 0;
                                $objData->userid = $user['userid'];
                                $objData->email = $useremailid;
                                $objData->realname = $realname;
                                $objData->customerno = $user['customerno'];

                                $distance = 0;
                                if ($objData->average > 0) {
                                    if (isset($objData->fuelvehicleid) && $objData->fuelvehicleid != '' && $objData->fuelvehicleid != 0 && $objData->countcheck < CHECK_COUNT) {
                                        $objData->countcheck++;
                                        //echo abs($objData->fuel_balance - $objData->fuelreading);
                                        //echo "<br/>";
                                        //echo "<br/>";
                                        $objData->deltaSum = ($objData->deltaSum + abs($objData->fuel_balance - $objData->fuelreading));

                                        //echo "<br/>";echo "<br/>";
                                        $objData->fuelreading = $objData->fuel_balance;

                                        $objData->old_odometer = $objData->odometer;
                                    } else {
                                        $objData->countcheck = 1;
                                        $objData->fuelreading = $objData->fuel_balance;
                                        $objData->old_fuel_balance = $objData->fuel_balance;
                                        $objData->deltaSum = 0;
                                        $objData->old_odometer = $objData->odometer;
                                    }
                                    $stabilityFactor = round((STABILITY_PERCENT / 100) * $objData->fuelcapacity);
                                    if ($objData->countcheck == CHECK_COUNT && round($objData->deltaSum) < floatval($stabilityFactor)) {
                                        //echo $objData->deltaSum."<br/>";
                                        // floatval(STABILITY_FACTOR)."<br/>";

                                        if ($objData->odometer != 0) {
                                            $distance = round(($objData->odometer - $objData->old_fuel_odometer) / 1000); // Distance In Km
                                        }

                                        $diffInFuel = ($objData->fuelreading - $objData->old_fuel_balance);
                                        $stabilityDiff = round((INACCURACY_PERCENT_CONSUMPTION / 100) * $objData->old_fuel_balance);
                                        $objData->diffInFuel = $diffInFuel;
                                        $objData->stabilityDiff = $stabilityDiff;
                                        $objData->old_fuel_balance = $objData->fuel_balance;
                                        $objData->old_fuel_odometer = $objData->odometer;

                                        $fuelConsumed = round(($distance / $objData->average)); // Ltr Consume
                                        $inaccurateFuelConsumed = round((INACCURACY_PERCENT_FOR_THEFT / 100) * $fuelConsumed);

                                        //$expectedLowerConsumption = round($fuelConsumed - $inaccurateFuelConsumed);
                                        $expectedHigherConsumption = round($fuelConsumed + $inaccurateFuelConsumed);

                                        $inaccurateFuelRefill = round((INACCURACY_PERCENT_FOR_REFILL / 100) * $objData->fuelcapacity);

                                        // print_r($objData);die();
                                        $objData->isOldBalanceUpdate = 1; // First Stable Update On Vehicle Table.
                                        if ($diffInFuel > 0 && abs($diffInFuel) > $stabilityDiff && $diffInFuel >= $inaccurateFuelRefill) {
                                            $objData->isAlert = 2; //Refill
                                            refuel_cron_alert($objData);
                                        } elseif ($diffInFuel < 0 && abs($diffInFuel) > $stabilityDiff && (abs($diffInFuel) >= $expectedHigherConsumption)) {
                                            $objData->isAlert = 3; //Theft
                                            fueltheft_cron_alert($objData);
                                        }
                                    }
                                    //prettyPrint($objData);
                                    if ($objData->fuelvehicleid == 0) {
                                        $vehicleManager->insertFuelAlert($objData);
                                        echo "<br/>" . $objData->vehicleno . " inserted<br/>";
                                    } else {
                                        $vehicleManager->updateFuelAlert($objData);
                                        echo "<br/>" . $objData->vehicleno . " updated";
                                    }

                                    if ($objData->isOldBalanceUpdate == 1) {
                                        $vehicleManager->updateFuelOldBalance($objData);
                                        echo "<br/>" . $objData->vehicleno . " fuel updated";
                                    }
                                }

                                //
                                //break;
                            }
                        }
                    }
                    //break;
                }
            }
        }
    }

    //<editor-fold defaultstate="collapsed" desc="Refuel Alert">
    function refuel_cron_alert($data) {
        $today = date("Y-m-d H:i:s");
        $objCustomerManager = new CustomerManager();
        $status = 1;
        $CCEmail = 'mrudang.vora@elixiatech.com';
        $BCCEmail = '';
        $subject = "Fuel Refuel Alert For " . $data->vehicleno;
        $message = "Dear " . ucfirst($data->realname) . ",<br>";
        $message .= "<br>" . $data->vehicleno . " Refueled Now.";
        $message .= "<br>Vehicleno :" . $data->vehicleno;
        $message .= "<br>Old Fuel Balanced :" . $data->old_fuel_balance1;
        $message .= "<br>Balanced Fuel :" . $data->fuel_balance;
        $message .= "<br>Refuel ltr:" . abs($data->diffInFuel);
        $message .= "<br><br>Disclaimer: We are sending alerts once we find that fuel level is stabilised with 95% accuracy for span of " . CHECK_COUNT . " mins.";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = "";
        //echo $message;
        $to = $objData->email;
        //$to = 'software@elixiatech.com';
        $isMailSent = sendMailUtil(array($to), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 0);
        $objEmail = new stdClass();
        $objEmail->email = $to;
        $objEmail->subject = $subject;
        $objEmail->message = $message;
        $objEmail->vehicleid = $data->vehicleid;
        $objEmail->userid = $data->userid;
        $objEmail->type = 0;
        $objEmail->moduleid = speedConstants::MODULE_VTS;
        $objEmail->customerno = $data->customerno;
        $objEmail->isMailSent = $isMailSent;
        $objEmail->today = $today;
        if ($isMailSent == 1) {
            $emailId = $objCustomerManager->insertCustomerEmailLog($objEmail);
        }
    }

    //<editor-fold defaultstate="collapsed" desc="Fuel Theft Alert">
    function fueltheft_cron_alert($data) {
        $today = date("Y-m-d H:i:s");
        $objCustomerManager = new CustomerManager();
        $status = 1;
        $CCEmail = 'mrudang.vora@elixiatech.com';
        $BCCEmail = '';

        $subject = "Fuel Theft Alert For " . $data->vehicleno;
        $message = "Dear " . ucfirst($data->realname) . ",<br>";
        $message .= "<br><br> Fuel theft from " . $data->vehicleno;
        $message .= "<br>Vehicleno :" . $data->vehicleno;
        $message .= "<br>Old Fuel Balance :" . $data->old_fuel_balance1;
        $message .= "<br>Current Fuel Balanced :" . $data->fuel_balance;
        $message .= "<br>Fuel thefted :" . abs($data->diffInFuel);
        $message .= "<br><br>Disclaimer: We are sending alerts once we find that fuel level is stabilised with 95% accuracy for span of " . CHECK_COUNT . " mins.";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = "";
        $to = $objData->email;
        //echo $message;
        //$to = 'software@elixiatech.com';
        $isMailSent = sendMailUtil(array($to), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 0);
        $objEmail = new stdClass();
        $objEmail->email = $to;
        $objEmail->subject = $subject;
        $objEmail->message = $message;
        $objEmail->vehicleid = $data->vehicleid;
        $objEmail->userid = $data->userid;
        $objEmail->type = 0;
        $objEmail->moduleid = speedConstants::MODULE_VTS;
        $objEmail->customerno = $data->customerno;
        $objEmail->isMailSent = $isMailSent;
        $objEmail->today = $today;
        if ($isMailSent == 1) {
            $emailId = $objCustomerManager->insertCustomerEmailLog($objEmail);
        }
    }

    //</editor-fold>
    /*
    function cronCustomerUsersFuel($users) {
    $array = json_decode(json_encode($users), true);
    $customerUserArray = array_reduce($array, function ($result, $currentItem) {
    if (isset($result[$currentItem['customerno']])) {
    $user = array();
    $user['userid'] = $currentItem['userid'];
    $user['email'] = $currentItem['email'];
    $user['phone'] = $currentItem['phone'];
    $user['realname'] = $currentItem['realname'];
    $user['userkey'] = $currentItem['userkey'];
    $user['userrole'] = $currentItem['userrole'];
    $user['roleid'] = $currentItem['roleid'];
    $user['customerno'] = $currentItem['customerno'];
    $result[$currentItem['customerno']]['users'][] = $user;
    } else {
    $result[$currentItem['customerno']] = array();
    $user = array();
    $user['userid'] = $currentItem['userid'];
    $user['email'] = $currentItem['email'];
    $user['realname'] = $currentItem['realname'];
    $user['phone'] = $currentItem['phone'];
    $user['userkey'] = $currentItem['userkey'];
    $user['userrole'] = $currentItem['userrole'];
    $user['roleid'] = $currentItem['roleid'];
    $user['customerno'] = $currentItem['customerno'];
    $result[$currentItem['customerno']]['users'][] = $user;
    }
    return $result;
    });
    return $customerUserArray;
    }
     */

?>

