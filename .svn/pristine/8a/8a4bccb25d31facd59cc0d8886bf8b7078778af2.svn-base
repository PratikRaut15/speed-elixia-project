<?php
include_once 'session/sessionhelper.php';
include_once("constants/constants.php");
include_once("lib/system/utilities.php");

include_once("lib/components/ajaxpage.inc.php");
$dontredirect=true;// This is set so we don't ask for login details...
$ajaxpage = new ajaxpage();
$customerno = GetCustomerno();
$ids = $ajaxpage->GetParameter("trackeeids");
$idarray = explode(",", $ids);

class jsonop
{
    // Empty class!
}

include_once("lib/bo/TrackeeManager.php");
include_once("lib/bo/DeviceManager.php");

$tm = new TrackeeManager($customerno);
$finaloutput = array();
if(isset($idarray))
{
    foreach($idarray as $thisid)
    {
        $trackee = $tm->get_trackee($thisid);
        if(isset($trackee))
        {
		
            $dm = new DeviceManager($customerno);
            $thisdevice = $dm->getdevicefromtrackee($trackee->id);    
            $output = new jsonop();
            $output->tgeolat = $thisdevice->devicelat;
            $output->tgeolong = $thisdevice->devicelong;
            $geotag = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$thisdevice->devicelat,$thisdevice->devicelong&sensor=false"));                
            $output->geotag = "Near ".$geotag->results[0]->formatted_address;            
            //Today - Last Updated
            $timetoday = strtotime($thisdevice->today);

            $timelastupdated = strtotime($thisdevice->lastupdated);

            if($thisdevice->lastupdated != "0000-00-00 00:00:00")
            {
                $diff = abs($timetoday - $timelastupdated); 

                $years   = floor($diff / (365*60*60*24)); 
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
                $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
                $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
                $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
                if($days > 0)
                {
                    $output->difference = date("d-m-Y",$timelastupdated);
                }
                else
                {
                    if($hours > 0)
                    {
                        $output->difference = $hours." hr ".$minutes." min ".$seconds." sec ago";
                    }
                    elseif($minutes > 0)
                    {
                        $output->difference = $minutes." min ".$seconds." sec ago";                
                    }
                    else
                    {
                        $output->difference = $seconds." sec ago";                                
                    }
                }
            }
            else
            {
                    $output->difference = "Not sending data";                                                
            }    
            $output->ticonimage = $trackee->ticonimage;
            $output->tname = $trackee->tname;    
            $finaloutput[] = $output;
        }
    }
}
$ajaxpage->SetResult($finaloutput);
$ajaxpage->Render();
?>