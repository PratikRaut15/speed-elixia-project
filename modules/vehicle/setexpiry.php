<?php
include 'vehicle_functions.php';
$data = array();
if(isset($_POST['pucset']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $pucexpiry = $_POST['pucexpiry'];
    $pucrem = $_POST['pucrem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setPUCExpiry($customerno,$userid,$vehicleid,$pucexpiry,$pucrem);
    //return $result;
    echo $data = $result;
    
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}
else if(isset($_POST['regset']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $regexpiry = $_POST['regexpiry'];
    $regrem = $_POST['regrem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setREGExpiry($customerno,$userid,$vehicleid,$regexpiry,$regrem);
    //return $result;
    echo $data = $result;
    
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}
else if(isset($_POST['speedset']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $speedexpiry = $_POST['speedexpiry'];
    $speedrem = $_POST['speedrem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setSPEEDExpiry($customerno,$userid,$vehicleid,$speedexpiry,$speedrem);
    //return $result;
    echo $data = $result;
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}
else if(isset($_POST['fireextset']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $fireexpiry = $_POST['fireexpiry'];
    $fireextrem = $_POST['fireextrem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setFIREEXTExpiry($customerno,$userid,$vehicleid,$fireexpiry,$fireextrem);
    //return $result;
    echo $data = $result;
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}
else if(isset($_POST['insset']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $insexpiry = $_POST['insexpiry'];
    $insrem = $_POST['insrem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setINSExpiry($customerno,$userid,$vehicleid,$insexpiry,$insrem);
    //return $result;
    echo $data = $result;
    
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}

else if(isset($_POST['oth1set']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $oth1expiry = $_POST['oth1expiry'];
    $oth1rem = $_POST['oth1rem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setOTH1Expiry($customerno,$userid,$vehicleid,$oth1expiry,$oth1rem);
    //return $result;
    echo $data = $result;
    
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}

else if(isset($_POST['oth2set']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $oth2expiry = $_POST['oth2expiry'];
    $oth2rem = $_POST['oth2rem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setOTH2Expiry($customerno,$userid,$vehicleid,$oth2expiry,$oth2rem);
    //return $result;
    echo $data = $result;
    
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}

else if(isset($_POST['oth3set']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $oth3expiry = $_POST['oth3expiry'];
    $oth3rem = $_POST['oth3rem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setOTH3Expiry($customerno,$userid,$vehicleid,$oth3expiry,$oth3rem);
    //return $result;
    echo $data = $result;
    
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}
else if(isset($_POST['oth4set']))
{
    $error = false;
    $customerno = $_POST['customerno'];
    $userid = $_POST['userid'];
    $oth4expiry = $_POST['oth4expiry'];
    $oth4rem = $_POST['oth4rem'];
    $vehicleid = $_POST['vehicleid'];
    
    $result = setOTH4Expiry($customerno,$userid,$vehicleid,$oth4expiry,$oth4rem);
    //return $result;
    echo $data = $result;
    
    //$data = array('not suc' => 'Form was submitted', 'formData' => $_POST);
}else
{
	$data = array('success' => 'Form was submitted', 'formData' => $_POST);
        echo json_encode($data);        
}


?>
