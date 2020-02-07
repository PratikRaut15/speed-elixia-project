<?php
session_start();
require_once("../../lib/system/utilities.php");
require_once("../../lib/components/ajaxpage.inc.php");
$dontredirect=true;// This is set so we don't ask for login details...
$ajaxpage = new ajaxpage();
$customerno = $_SESSION['customerno'];

class jsonop
{
    // Empty class!
}

include_once("../../lib/bo/ZoneManager.php");        
        $geomanager = new ZoneManager($_SESSION['customerno']);
		$finaloutput = array(); 
        $zones = $geomanager->getzones_with_bounds();
if(isset($zones))
{
//    print_r($zones); die();
    
    foreach($zones as $zone)
    {
       $output = new stdClass();
        $output->zoneid = $zone['zoneid'];    
        $output->zonename = $zone['zonename']; 
        $output->zone_bound = $zone['zone_bound'];
        $finaloutput[] = $output;
    }
}

$ajaxpage->SetResult($zones);
$ajaxpage->Render();
?>