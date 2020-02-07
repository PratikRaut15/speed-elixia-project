<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once '../../deliveryapi/class/config.inc.php';
require_once 'assign_function.php';

$customerno = exit_issetor($_SESSION['customerno'], "Please Login");
$vid = (int)exit_issetor($_REQUEST['vid'], "Vehicle id not found");
$sid = (int)exit_issetor($_REQUEST['slotid'], "Slot id not found");
$date = exit_issetor($_REQUEST['date'], "Date not found");

$dm = new DeliveryManager($customerno);

$get_sequence = $dm->get_order_sequence($vid,$sid,$date);


if($get_sequence){
?>
<table class='table newTable'>
    <thead>
        <tr>
            <th>Delivery Date</th>
            <th>Vehicle No</th>
            <th>Delivery Boy</th>
            <th>Zone</th>
            <th>Slot</th>
            <th>Sequence</th>
            <th>Time(Min)</th>
            <th>Bill No</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $tr_array = array();
        foreach($get_sequence as $gs){
            $vehicleid = $gs['vehicleid']; 
            $delboyname = $dm->get_deliveryboyname($vehicleid);
            $delstr="";
            if(!empty($delboyname)){
               $delstr = $delboyname;
            }
           
            echo "<tr>";
            echo "<td>{$gs['delivery_date']}</td>";
            echo "<td>{$gs['vno']}</td>";
            echo "<td>{$delstr}</td>";
            echo "<td>{$gs['zname']}</td>";
            echo "<td>{$gs['slot']}</td>";
            echo "<td>{$gs['seq']}</td>";
            echo "<td>{$gs['time']}</td>";
            echo "<td>{$gs['oid']}</td>";
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