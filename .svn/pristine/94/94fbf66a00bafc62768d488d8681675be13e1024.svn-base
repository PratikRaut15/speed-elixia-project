<?php
$vid = $_REQUEST['vid'];
?>
<div class="container">
    
<table class="table newTable">
    <thead>
    <tr>
        <th colspan="100%" id="formheader"><span style="float: left;"><a href="accinfo.php?id=2#inventory_mgt"><img src="../../images/back.png" /> Back To Inventory Management</a></span>Service History For Vehicle No - <?php echo getVehcileno($vid); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr class="tableSub">
        <td>Sr #</td>
        <td>Transaction #</td>
        <td>Status #</td>
        <td>Simcard #</td>
        <td>Invoice No #</td>
        <td>Expiry</td>
        <td>Date</td>
        <td>Done By</td>
        
        
    </tr>
<?php
$i=1;
$services = service_details($vid,$_SESSION['customerno']);
if(isset($services)){
    foreach($services as $invoice){
        ?>
    <tr>
        <td><?php echo $i++;?></td>
        <td><?php echo $invoice->transaction; ?></td>
        <td><?php echo $invoice->status;?></td>
        <td><?php echo $invoice->simcardno;?></td>
        <td><?php echo $invoice->invoiceno; ?></td>
        <td><?php echo $invoice->expirydate; ?></td>
        <td><?php echo $invoice->trans_time; ?></td>
        <td><?php echo $invoice->name; ?></td>
        
       
        <?php
    }
}
else{
    echo "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr>";
}
?>
    </tbody>
</table>
    
    

</div>
