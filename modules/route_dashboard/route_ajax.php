<?php
include_once 'approval_functions.php';

extract($_REQUEST);

if($work=='pull_history'){
   get_approval_form_by_vehicle_id($main_id);
}

if($work=='push_status'){
   pushstatus($main_id,$status,$note);
}

if($work=='approved'){
   $status = approved($vehicleid, $notes);
   echo $status;
}

if($work=='reject'){
   $status = reject($vehicleid, $notes);
   echo $status;
}

?>
