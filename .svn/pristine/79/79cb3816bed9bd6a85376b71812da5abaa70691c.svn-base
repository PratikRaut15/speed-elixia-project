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

include_once("../../lib/bo/GeofenceManager.php");        
        $geomanager = new GeofenceManager($_SESSION['customerno']);
		$finaloutput = array(); 
        $fences = $geomanager->getfences_with_bounds();
if(isset($fences))
{
    foreach($fences as $fence)
    {
        $output = new jsonop();

       /*  $output->fenceid = $fence->fenceid;    
        $output->fencename = $fence->fencename; 
        $output->fence_bound = $fence->fence_bound; */

        $output->fenceid = $fence['fencid'];    
        $output->fencename = $fence['fencename']; 
        $output->fence_bound = $fence['fence_bound'];
        $finaloutput[] = $output; 

       /*  echo"<br>Fence id is: ".$fence['fencid'];
        echo"<br>Fence Name is: ".$fence['fencename'];
        echo"<br>Fence bound is: ".$fence['fence_bound']; */
    }
}
echo json_encode($finaloutput);
/* $ajaxpage->SetResult($fences);
$ajaxpage->Render(); */
?>