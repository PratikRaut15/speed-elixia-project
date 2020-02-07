<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'session/sessionhelper.php';
include_once("constants/constants.php");
include_once("lib/system/utilities.php");

include_once("lib/components/ajaxpage.inc.php");

$dontredirect=true;
$ajaxpage = new ajaxpage();
$customerno = GetCustomerno();

$queryphrase = GetSafeValueString($_REQUEST["q"], "string");
$listtype = GetSafeValueString($_REQUEST["t"], "string");
//include_once("classes/CacheManager.php");
//$Cache = new CacheManager( $affiliateid );

//$id = sprintf("%s-%s", $listtype, $queryphrase);

/*if ($data = $Cache->Get($id,$listtype))
{
    // in cache, return it
    $JSON = $data;
}
else
{
*/
if($listtype=="services")
{
    include_once 'lib/bo/ClientManager.php';
    $cm = new ClientManager( $customerno );
    $results = $cm->getclientsfromphrase($queryphrase, 1);
    $servicefields=true;    
}
if($listtype=="servicesextra")
{
    include_once 'lib/bo/ClientManager.php';
    $cm = new ClientManager( $customerno );
    $results = $cm->getclientsfromextraphrase($queryphrase, 1);
    $servicefields=true;    
}
if($listtype=="seruf1")
{
    include_once 'lib/bo/UserFieldManager.php';
    $um = new UserFieldManager( $customerno );
    $results = $um->getuf1fromphrase($queryphrase, 1);
    $servicefields=true;    
}
if($listtype=="seruf2")
{
    include_once 'lib/bo/UserFieldManager.php';
    $um = new UserFieldManager( $customerno );
    $results = $um->getuf2fromphrase($queryphrase, 1);
    $servicefields=true;    
}
if(isset($results))
{
    $JSON = "[";
    $counter=0;
    foreach($results as $thisresult)
    {    
        if($servicefields)
        {
            if($counter>0)
            {
                $JSON .= ',';
            }
            
                $JSON .= '{"id":"'. $thisresult->id . '", "text":"' . trim($thisresult->name).'"}';
                $counter++;
            
        }
    }
    $JSON .= "]";

    }
    else
    {
        $JSON= "{}";
    }

// Wasn't in Cache, Save.
//$Cache->Save($JSON, $id, $listtype);

//}


echo($JSON);

?>
