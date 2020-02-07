<?php
require_once '../../lib/bo/CronManager.php';
$cronm = new CronManager();

$teamunits = $cronm->get_sms_details();

if(isset($teamunits))
{
    foreach($teamunits as $thisteamunit)
    {
        $message = "";
        $message = "Closing Stock Units with ".$thisteamunit->name.": ".$thisteamunit->count.":- ";
        $allunits = $cronm->get_sms_unit_details($thisteamunit->teamid);        
        echo $message.=$allunits;
        echo "<br/>";
        if($thisteamunit->phone != "")
        {
          sendSMS($thisteamunit->phone, $message);            
        }
    }
}

$teamsims = $cronm->get_sms_details_sim();

if(isset($teamsims))
{
    foreach($teamsims as $thisteamsim)
    {
        $message = "";        
        $message = "Closing Stock Simcards with ".$thisteamsim->name.": ".$thisteamsim->count.":- ";
        $ammsims = $cronm->get_sms_sim_details($thisteamsim->teamid);
        echo $message.=$ammsims;
        echo "<br/>";
        if($thisteamsim->phone != "")
        {
          sendSMS($thisteamsim->phone, $message);            
        }        
    }
}

$teamunits = $cronm->get_sms_details();

$unitcount = 0;
$message = "";
if(isset($teamunits))
{
    foreach($teamunits as $thisteamunit)
    {
        $unitcount = $unitcount + $thisteamunit->count;
        $message.= $thisteamunit->name.": ".$thisteamunit->count."; ";
    }
}

$finalmessage = "";
$finalmessage.= "Total Units with FE: ".$unitcount.":- ";
echo $finalmessage.= $message;
sendSMS(9619521206,$finalmessage);
sendSMS(9819334888,$finalmessage);
sendSMS(8692089822,$finalmessage);

$teamsims = $cronm->get_sms_details_sim();

$simcount = 0;
$message = "";
if(isset($teamsims))
{
    foreach($teamsims as $thisteamsim)
    {
        $simcount = $simcount + $thisteamsim->count;
        $message.= $thisteamsim->name.": ".$thisteamsim->count."; ";
    }
}
$finalmessage = "";
$finalmessage.= "Total Simcards with FE: ".$simcount.":- ";
echo $finalmessage.= $message;
sendSMS(9619521206,$finalmessage);
sendSMS(9819334888,$finalmessage);
sendSMS(8692089822,$finalmessage);

function sendSMS($phone, $message)
{
        $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91".urlencode($phone)."&source=ELIXIA&message=".urlencode($message);        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);    
        return true;
}

?>
