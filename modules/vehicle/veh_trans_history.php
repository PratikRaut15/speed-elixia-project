<?php

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once '../transactions/transaction_functions.php';

$vehicleid = exit_issetor($_REQUEST['vehicleid']);

//0=battery history, 1=tyre history, 2=repair/service history, 5=Accesories history
echo"<div><input type='hidden' name='vehicle_id' id='vehicle_id' value='$vehicleid'/></div>";
$inside = false;



$hist = get_mnt_history($vehicleid, 0);
if ($hist) {
    echo "<table class='table newTable'>";
    echo "<thead><tr><th colspan='100%'>Battery history</th></tr><tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Battery Srno</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
    echo "<th>Modified Date</th><th>Status</th>";
    echo "</tr></thead><tbody>";

    $i = 1;
    foreach ($hist as $record) {
        $mr = date('d-M-Y H:i', strtotime($record->mdate));
        echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td><td>{$record->battery_srno}</td>";
        echo "<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
        echo "</tr>";
        $i++;
    }
    echo "</tbody></table>";
    $inside = true;
}

$hist = get_mnt_history($vehicleid, 1);
if ($hist) {
    echo "<table class='table newTable'>";
    echo "<thead><tr><th colspan='100%'>Tyre history</th></tr><tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
    echo "<th>Modified Date</th><th>Status</th>";
    echo "<th>Tyre Type</th>";
    echo "<th>Tyre Serial No.</th>";
    echo "</tr></thead><tbody>";

    $i = 1;
    foreach ($hist as $record) {
        $mr = date('d-M-Y H:i', strtotime($record->mdate));
        echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
        echo "<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
        echo "<td>{$record->repairtype}</td>";
        echo "<td>{$record->tyre}</td>";
        echo "</tr>";
        $i++;
    }
    echo "</tbody></table>";
    $inside = true;
}

$hist = get_mnt_history($vehicleid, 2);
if ($hist) {

    echo "<table class='table newTable'>";
    echo "<thead><tr><th colspan='100%'>Repair/Service History</th></tr>";
    echo "<tr><td colspan ='100%' style = 'text-align:center;font-weight:bold'>Lables : U - Unit Price , Q - Quantity , T - Total Amount</td></tr>";
    echo "<tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
    echo "<th>Modified Date</th><th>Status</th>";
    echo "<th>Parts</th><th>Tasks</th>";
    echo "</tr></thead><tbody>";

    $i = 1;
    foreach ($hist as $record) {
        //to check the parts and task in maintenance_parts/task table
        $record_parts = getpartsby_maintenanceid($record->mid);
        $record_tasks = gettaskby_maintenanceid($record->mid);
        $mr = date('d-M-Y H:i', strtotime($record->mdate));
        echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
        echo "<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";

        if(!empty($record_parts)){
            echo"<td style='text-align:left;'>";
            $j = 1;
            foreach($record_parts as $parts){
                echo $j.") ";
                echo $parts;
                echo "<br />";
                //echo PHP_EOL;
                $j++;
            }
            echo"</td>";
        }else{
            echo"<td> </td>";
        }
        if(!empty($record_tasks)){
            echo"<td style='text-align:left;'>";
            $k = 1;
            foreach($record_tasks as $tasks){
                 echo $k.") ";
                echo $tasks;
                echo "<br />";
                //echo PHP_EOL;
                $k++;
            }
            echo"</td>";
        }else{
            echo"<td> </td>";
        }
        
        echo "</tr>";
        $i++;
    }
    echo "</tbody></table>";
    $inside = true;
}

$hist = get_mnt_history($vehicleid, 5);
if ($hist) {
    echo "<table class='table newTable'>";
    echo "<thead><tr><th colspan='100%'>Accesories History</th></tr><tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
    echo "<th>Modified Date</th><th>Status</th>";
    echo "<th>Accessories</th>";
    echo "</tr></thead><tbody>";

    $i = 1;
    foreach ($hist as $record) {
        $mr = date('d-M-Y H:i', strtotime($record->mdate));
        echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
        echo "<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
        echo "<td>{$record->access}</td>";
        echo "</tr>";
        $i++;
    }
    echo "</tbody></table>";
    $inside = true;
}

$hist = get_mnt_accident_history($vehicleid);
if ($hist) { //Accident claim history
    echo "<table class='table newTable'>";
    echo "<thead><tr><th colspan='100%'>Accident Claim History</th><tr><th>#</th><th>Transaction ID</th><th>Accident Date</th><th>Location</th><th>Injury/Damage</th><th>Accident Description</th>";
    echo "<th>Driver Name</th><th>License Validity</th><th>License Type</th><th>Workshop Location</th>";
    echo "<th>Loss amount</th><th>Settlement Amount</th><th>Repair Amount</th><th>Amount Spent</th>";
    echo "</tr></thead><tbody>";

    $i = 1;
    foreach ($hist as $record) {
        $adate = date('d-M-Y H:i', strtotime($record->accident_datetime));
        $lv = date('d-M-Y', strtotime($record->lvfrom)) . ' to ' . date('d-M-Y', strtotime($record->lvto));
        echo "<tr><td>$i</td><td>{$record->transid}</td><td>$adate</td><td>{$record->accident_location}</td><td>{$record->tpi_pd}</td><td>{$record->description}</td>";
        echo "<td>{$record->drivername}</td><td>$lv</td>";
        echo "<td>{$record->licence_type}</td>";
        echo "<td>{$record->workshop_location}</td>";
        echo "<td>{$record->loss_amount}</td>";
        echo "<td>{$record->sett_amount}</td>";
        echo "<td>{$record->actual_amount}</td>";
        echo "<td>{$record->mahindra_amount}</td>";
        echo "</tr>";
        $i++;
    }
    echo "</tbody></table>";
    $inside = true;
}

$hist = getfilteredfuels('', $vehicleid, null);
if ($hist) { //Fuel history
    echo "<table class='table newTable'>";
    echo "<thead><tr><th colspan='100%'>Fuel History</th><tr><th>#</th><th>Transaction ID</th><th>Date & Time</th><th>Fuel (In Lt.)</th><th>Amount</th><th>Rate</th>";
    echo "<th>Ref.No</th><th>Opening Km</th><th>Ending Km</th><th>Average</th><th>Vendor</th>";
    echo "</tr></thead><tbody>";

    $i = 1;
    foreach ($hist as $record) {
        echo "<tr><td>$i</td><td>{$record->trans}</td><td>" . date('d-M-Y H:i', strtotime($record->submit_datetime)) . "</td><td>{$record->fuel}</td><td>{$record->amount}</td><td>{$record->rate}</td>";
        echo "<td>{$record->invno}</td>";
        echo "<td>{$record->openingkm}</td>";
        echo "<td>{$record->endingkm}</td>";
        echo "<td>{$record->average}</td>";
        echo "<td>{$record->dname}</td>";
        echo "</tr>";
        $i++;
        $inside = true;
    }
}

if (!$inside) {
    echo "<h2>No Data found</h2>";
}
?>


