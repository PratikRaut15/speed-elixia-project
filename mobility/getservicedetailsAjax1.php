<?php
include_once 'session/sessionhelper.php';
include_once("constants/constants.php");
include_once("lib/system/utilities.php");

include_once("lib/components/ajaxpage.inc.php");
$dontredirect=true;// This is set so we don't ask for login details...
$ajaxpage = new ajaxpage();
$customerno = GetCustomerno();
$clientid = $ajaxpage->GetParameter("cid");

class jsonop
{
    // Empty class!
}

include_once("lib/bo/ClientManager.php");
include_once("lib/bo/DeviceManager.php");

$cm = new ClientManager($customerno);
$client = $cm->getclient($clientid, 1);
$finaloutput = array();
if(isset($client))
{
    $output = new jsonop();
    $output->city = $client->city;
    $output->state = $client->state;
    $output->zip = $client->zip;
    $output->email = $client->email;
    $output->address1 = $client->add1;
    $output->address2 = $client->add2;
    $output->maincontact = $client->maincontact;
    $output->phone = $client->phone;
    $output->extra = $client->extra;
    $output->clientname = $client->clientname;
    $finaloutput[] = $output;
}
$ajaxpage->SetResult($finaloutput);
$ajaxpage->Render();
?>