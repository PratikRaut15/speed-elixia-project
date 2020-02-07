<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once '../../deliveryapi/class/config.inc.php';
require_once 'pickup_functions.php';

$customerno = exit_issetor($_SESSION['customerno'], "Please Login");
$vid = (int)exit_issetor($_REQUEST['vid'], "Vehicle id not found");
$sid = (int)exit_issetor($_REQUEST['slotid'], "Slot id not found");
$date = exit_issetor($_REQUEST['date'], "Date not found");

$dm = new DeliveryManager($customerno);

$get_sequence = $dm->get_order_sequence_pickup($vid,$sid,$date);

if($get_sequence){
?>
<table class='table'>
    <thead>
        <tr>
            <th>Delivery Date</th>
            <th>UserName</th>
            <th>Time(Min)</th>
            <th>Order No</th>
            <th>Vendor</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($get_sequence as $gs){
            echo "<tr>";
            echo "<td>{$gs['pickupdate']}</td>";
            echo "<td>{$gs['username']}</td>";
            echo "<td>{$gs['time']}</td>";
            echo "<td>{$gs['oid']}</td>";
            echo "<td>{$gs['vendorname']}</td>";
            echo "<td>{$gs['address']}</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
        
</table>

<?php
    
}
else{
    echo "No data found";
}


?>