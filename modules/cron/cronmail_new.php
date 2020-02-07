<?php
include_once("../../session/sessionhelper.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/utilities.php");
include_once("../../constants/constants.php");
require_once '../../lib/bo/ComQueueManager.php';
include_once("../../lib/bo/CustomerManager.php");
include_once("../../lib/bo/VehicleManager.php");
require_once '../../lib/bo/UserManager.php';
include_once("../../lib/model/VOComQueue.php");
include_once '../../lib/bo/GeoCoder.php';

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

function sendSMS($phone, $message, $customerno)
{
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
    else
    {
        return false;
    }

/*    
    $urloutput=file_get_contents($url);
    echo $urloutput; //should print "N,-1,Cart id must be provided"    
    return true;
 * 
 */
}
if(sendMail('ajaytripathi.10@gmail.com', 'test', 'hello') == true)
{
    echo 'mail sent to ajaytripathi.10@gmail.com';
}
else{
    echo 'mail not sent to ajaytripathi.10@gmail.com';
}
if(sendSMS('9870041322', 'hello test msg', '2') == true)
{
    echo 'sms sent to 9870041322';
}
else{
    echo 'sms not sent to 9870041322';
}
if(sendMail('zatakia.ankit@gmail.com', 'test1', 'hello1') == true)
{
    echo 'mail sent to zatakia.ankit@gmail.com';
}
else{
    echo 'mail not sent to zatakia.ankit@gmail.com';
}
if(sendSMS('9819334888', 'hello test msg1', '2') == true)
{
    echo 'sms sent to 9819334888';
}
else{
    echo 'sms not sent to 9819334888';
}
?>