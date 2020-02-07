<?PHP
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';;
require_once '../../lib/bo/ComQueueManager.php';
require_once '../../lib/bo/CronManager.php';
include_once("../../lib/model/VOComQueue.php");
include_once '../../lib/bo/GeoCoder.php';


function sendSMS($phone, $message, $customerno)
{
    if($customerno != 0){
        $cm = new CustomerManager();
        $sms = $cm->pullsmsdetails($customerno);
        if($sms->smsleft > 0)
        {
            if($sms->smsleft == 20)
            {
                $cqm = new ComQueueManager();
                $cvo = new VOComQueue();
                $cvo->phone = $phone;
                $cvo->message = "Your SMS pack is too low. Please contact an Elixir. Powered by Elixia Tech.";
                $cvo->subject = "";
                $cvo->email = "";
                $cvo->type = 1;
                $cvo->customerno = $customerno;
                $cqm->InsertQ($cvo);
            }
            $smsleft = $sms->smsleft-1;
            $cm->updatesms($smsleft,$customerno);
    //        $url = "http://india.msg91.com/sendhttp.php?user=sanketsheth&password=271257&mobiles=".urlencode($phone)."&message=".urlencode($message)."&sender=Elixia&route=4";
            $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91".urlencode($phone)."&source=ELIXIA&message=".urlencode($message);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($ch);
            curl_close($ch);
            return true;
        }
    }
    else if($customerno == 0){
     //        $url = "http://india.msg91.com/sendhttp.php?user=sanketsheth&password=271257&mobiles=".urlencode($phone)."&message=".urlencode($message)."&sender=Elixia&route=4";
        $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91".urlencode($phone)."&source=ELIXIA&message=".urlencode($message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return true;
    }
    else
    {
        return false;
    }
}

function sendMail( $to, $subject , $content)
{
    $subject = $subject;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}

function location($lat,$long)
{
    $address = 'Not Found';
    if($lat !=0 && $long!=0)
    {
        if($_SESSION['customerno']==33 || $_SESSION['customerno']==43)
        {
            $API = "http://www.speed.elixiatech.com/location.php?lat=".$lat."&long=".$long."";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near ".$location->results[0]->formatted_address;
			if($location->results[0]->formatted_address==""){
			$GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
            $address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
			}

        }
        else
        {
            $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
            $address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
        }
    }
    return $address;
}

function getduration($EndTime, $StartTime)
{
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    return $hours * 60 + $minutes;
}

$cm = new CronManager();
$simdatas = $cm->getsimdata_cron();
if(isset($simdatas))
{
    foreach($simdatas as $simdata)
    {
        $phoneno = substr($simdata->phoneno, 3);
        $customerno = NULL;
        $phoneexist = $cm->checkphoneno($phoneno);
        if(isset($phoneexist)){
            $customerno = $phoneexist->customer_no;
        }
        if(!isset($customerno)){
        $phoneexistuser = $cm->checkphoneinuser($phoneno);
        if(isset($phoneexistuser)){
                                    $customerno = $phoneexistuser->customerno;
                                  }
        }
        if(isset($customerno)){
            $cvo = new VOComQueue();
            $msg = explode(' ', $simdata->message);
            $command = strtoupper($msg[0]);
            $vehicleno = strtoupper($msg[1]);
            //print_r($customer);
            if($command != 'LOC' && $command != 'IGN'){
                            $smsmessage = "Incorrect Command. Powered by Elixia Tech.";
                            if(sendSMS($phoneno, $smsmessage, $customerno) == true)
                            {
                                $cvo->id = $simdata->id;
                                $cvo->customerno = $customerno;
                                $cvo->msg = $smsmessage;
                                $cm->UpdateSimData($cvo);
                            }
            }
            else{
                    $vehicles = $cm->getvehicles_cron($vehicleno,$customerno);
                    if(isset($vehicles)){
                        foreach($vehicles as $vehicle){
                                if($command == 'LOC'){
                                        $device = $cm->getdevicedata($vehicle->vehicleid,$customerno);
                                        $location = location($device->devicelat, $device->devicelong);
                                        $smsmessage = "Location for $vehicle->vehicleno: $location. Powered by Elixia Tech.";
                    //echo $customerno,$command,$vehicle->vehicleid,$vehicle->vehicleno,$vehicleno,$device->devicelat,$device->devicelong;;exit;
                                        if(sendSMS($phoneno, $smsmessage, $customerno) == true)
                                        {
                                            $cvo = new VOComQueue();
                                            $cvo->id = $simdata->id;
                                            $cvo->lat = $device->devicelat;
                                            $cvo->long = $device->devicelong;
                                            $cvo->msg = $smsmessage;
                                            $cvo->customerno = $customerno;
                                            $cvo->vehicleid = $vehicle->vehicleid;
                                            $cvo->success = 1;
                                            $cm->UpdateSimDataLoc($cvo);
                                        }
                                }
                                else if($command == 'IGN'){
                                        $device = $cm->getdevicedataforignition($vehicle->vehicleid,$customerno);
                                        $diff = getduration($device->ignition_chg_time, $simdata->requesttime);
                                        if($device->status == 1)
                                        {
                                        $smsmessage = "$vehicle->vehicleno is running since $diff mins. Powered by Elixia Tech.";
                                        }
                                        else if($device->status == 0)
                                        {
                                        $smsmessage = "$vehicle->vehicleno is idle since $diff mins. Powered by Elixia Tech.";
                                        }
                                        if(sendSMS($phoneno, $smsmessage, $customerno) == true)
                                        {
                                            $cvo = new VOComQueue();
                                            $cvo->id = $simdata->id;
                                            $cvo->msg = $smsmessage;
                                            $cvo->customerno = $customerno;
                                            $cvo->vehicleid = $vehicle->vehicleid;
                                            $cvo->success = 1;
                                            $cm->SuccessSimData($cvo);
                                        }
                                }
                        }
                    }
                    else{
                            $smsmessage = "$vehicleno:Vehicle not found. Powered by Elixia Tech.";
                            $cvo = new VOComQueue();
                            if(sendSMS($phoneno, $smsmessage, $customerno) == true)
                            {
                                $cvo->id = $simdata->id;
                                $cvo->customerno = $customerno;
                                $cvo->msg = $smsmessage;
                                $cm->UpdateSimData($cvo);
                            }
                    }
                }
        }
        else{
                $emailmessage = "Phone no. $simdata->phoneno, Does not Match. Powered by Elixia Tech.";
                sendMail('sanketsheth21@gmail.com', 'Phone number not match', $emailmessage);
                $smsmessage = "Your phone is not registered. Powered by Elixia Tech.";
                if(sendSMS($phoneno, $smsmessage, 0) == true)
                {
                    $cvo = new VOComQueue();
                    $cvo->id = $simdata->id;
                    $cvo->customerno = 0;
                    $cvo->msg = $smsmessage;
                    $cm->UpdateSimData($cvo);
                }
        }
    }
}
?>

