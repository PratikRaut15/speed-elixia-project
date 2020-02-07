<?php
function diff($Today,$lastupdated)
{
    
    $diff = abs($Today - $lastupdated); 

    $years   = floor($diff / (365*60*60*24)); 
    $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
    $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
    $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
    $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
    if($days > 0)
    {
        $difference = date("D d-M-Y H:i",$lastupdated);
    }
    else
    {
        if($hours > 0)
        {
            $difference = $hours." hr ".$minutes." min ago";
        }
        elseif($minutes > 0)
        {
            $difference = $minutes." min ago";                
        }
        else
        {
            $difference = $seconds." sec ago";                                
        }
    }
    return $difference;
}

function diffdate($Today,$lastupdated)
{
    
    $diff = abs($Today - $lastupdated); 

    $years   = floor($diff / (365*60*60*24)); 
    $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
    $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
    $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
    $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
    $difference = date("D d-M-Y H:i",$lastupdated);
    return $difference;
}

function vehicleimage($device)
{

    $basedir = "/elixiaspeed_test/images/vehicles/";
    $directionfile = round($device->directionchange/10);
    if($device->type=='Car' || $device->type=='Cab')
    {
        $device->type='Car';
        if ($device->ignition=='0')
        {
            $image = $device->type."/Idle/".$device->type.$directionfile.".png";
        }
        else
        {
            if($device->status == 'B' || $device->status == 'D')
            {
                $image = $device->type."/Overspeed/".$device->type.$directionfile.".png";
            }
            else
            {
                $image = $device->type."/Normal/".$device->type.$directionfile.".png";
            }
        }    
    }
    else if($device->type=='Bus')
    {
        if ($device->ignition=='0')
        {
            $image = $device->type."/Idle/".$device->type.$directionfile.".png";
        }
        else
        {
            if($device->status == 'B' || $device->status == 'D')
            {
                $image = $device->type."/Overspeed/".$device->type.$directionfile.".png";
            }
            else
            {
                $image = $device->type."/Normal/".$device->type.$directionfile.".png";
            }
        }    
    }
    else
    {
        if ($device->ignition=='0')
        {
            $image = $device->type."/".$device->type."I.png";
        }
        else
        {
            if($device->status == 'B' || $device->status == 'D')
            {
                $image = $device->type."/".$device->type."O.png";
            }
            else
            {
                $image = $device->type."/".$device->type."N.png";
            }
        }  
    }
    $image = $basedir.$image;
    return $image;
}
?>