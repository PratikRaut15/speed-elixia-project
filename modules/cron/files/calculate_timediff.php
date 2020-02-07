<?php
function diff($lastupdated,$firstcheck)
{
    
    $diff = abs($lastupdated - $firstcheck); 
    $years   = floor($diff / (365*60*60*24)); 
    $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
    $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
    $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
    
    $time_diff = 0;
    if($hours > 0)
    {
        $time_diff = $hours*60 + $minutes;
    }
    elseif($minutes > 0)
    {
        $time_diff = $minutes;              
    }
    return $time_diff;
}
?>