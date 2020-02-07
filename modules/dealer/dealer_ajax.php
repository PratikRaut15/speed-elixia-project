<?php
include 'dealer_functions.php';
if(isset($_POST['name']) && !isset($_POST['dealerid']))
{
    $name = GetSafeValueString($_POST['name'],"string");
    $phoneno = GetSafeValueString($_POST['phoneno'],"string");
    $cellphone = GetSafeValueString($_POST['cellphone'],"string");
    $notes = GetSafeValueString($_POST['notes'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $other1 = GetSafeValueString($_POST['other1'],"string");
    $other2 = GetSafeValueString($_POST['other2'],"string");
    
    $vendor = '';
    if($_POST['battery'] == 'Battery'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['tyre'] == 'Tyre'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['service'] == 'Service'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['repair'] == 'Repair'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['vehicle'] == 'Vehicle'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['accessory'] == 'Accessory'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }    
    if($_POST['fuel'] == 'Fuel'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }        
    $response = adddealer($name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $other1, $other2);
    echo $response;
}
else if(isset($_POST['dealerid']))
{
    $dealerid = GetSafeValueString($_POST['dealerid'],"string");
    $name = GetSafeValueString($_POST['name'],"string");
    $phoneno = GetSafeValueString($_POST['phoneno'],"string");
    $cellphone = GetSafeValueString($_POST['cellphone'],"string");
    $notes = GetSafeValueString($_POST['notes'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
     $other1 = GetSafeValueString($_POST['other1'],"string");
    $other2 = GetSafeValueString($_POST['other2'],"string");
    
    $vendor = '';
    if($_POST['battery'] == 'Battery'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['tyre'] == 'Tyre'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['service'] == 'Service'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['repair'] == 'Repair'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['vehicle'] == 'Vehicle'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    if($_POST['accessory'] == 'Accessory'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    } 
    if($_POST['fuel'] == 'Fuel'){
        $vendor .= '1';
    }
    else{
        $vendor .= '0';
    }
    $response = editdealer($dealerid, $name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $other1, $other2);
    echo $response;
}
?>
