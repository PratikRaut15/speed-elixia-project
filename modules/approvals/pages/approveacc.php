

<?php
$maintanace_obj = new MaintananceManager($_SESSION['customerno']);
     $maintanaces = $maintanace_obj->get_acc_approval_form_by_vehicle_id($_GET['tid']);
      //var_dump($maintanaces);


?>