<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");

class ViewAddress{
    
}
$db = new DatabaseManager();
$x=0;
$address =Array();
if(isset($_POST['cno']))
{
    $cust_no =  GetSafeValueString($_POST['cno'], "string");
    
    $SQL ="SELECT * FROM invoice_customer_address WHERE customerno = $cust_no"; 
    $db->executeQuery($SQL);
    if($db->get_rowCount()>0)
    {
        while($row = $db->get_nextRow())
        {
            $x++;
            $DATA = new ViewAddress();
            $DATA->invid =$row['invcustid'];
            $DATA->invmane =$row['invoicename'];
            $DATA->add1 =$row['address1'];
            $DATA->add2 =$row['address2'];
            $DATA->add3 =$row['address3'];
            $DATA->x =$x;
            $address[] =$DATA;
            
        }
        
       //print_r($address);
    }
    //print_r($address);
    echo json_encode($address);
    
}
?>
