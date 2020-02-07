<?php 
    if($device->status=='A' || $device->status=='C')
        echo "Normal Speed";
    else if($device->status=='B' || $device->status=='D')
        echo "Excess Speed";
    else if($device->status=='E' || $device->status=='G')
        echo "Vehicle Idle";
    else if($device->status=='F')
        echo "Vehicle Started";
    else if($device->status=='H')
        echo "GPS Fixed";
    else if($device->status=='N')
        echo "SOS";
    else if($device->status=='S')
        echo "Harsh Break";
    else if($device->status=='J')
        echo "Ignition Status Change";
    else
        echo "OK";
?>