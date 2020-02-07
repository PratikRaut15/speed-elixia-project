<?php
    $tid = $_REQUEST['tid'];
   $isTripEnd = 1;
    $getstatushistory = gettriphistory_status($_SESSION['customerno'], $_SESSION['userid'], $tid,$isTripEnd);
?>
<br>
<h3>Trip History </h3>
<br>
<table id='search_table2'  cellspacing='10'>
    <tr style="background-color:#CCCCCC; font-weight: bold;">
        <td style='text-align: center;'>Vehicle No</td>
        <td style='text-align: center;'>Triplog No</td>
        <td style='text-align: center;'>Trip Status</td>
        <?php
            if($_SESSION['customerno'] == '447' ){ ?>
                <td style='text-align: center;'>Lr Creation Time</td>
                <td style='text-align: center;'>LR Delay Time</td>
                <td style='text-align: center;'>Yard Checkout Time</td>
                <td style='text-align: center;'>Yard Detention Time</td>
                <td style='text-align: center;'>Yard CheckIn Time</td>
                <td style='text-align: center;'>Empty Return Deviation</td>
            <?php                 
            }
            else{ ?>
                <td style='text-align: center;'>Budgeted Kms</td>
                <td style='text-align: center;'>Budgeted Hrs</td>
                <td style='text-align: center;'>Consignor</td>
                <td style='text-align: center;'>Consignee</td>
                <td style='text-align: center;'>Billing Party</td>
          <?php  
              } ?>
        <td style='text-align: center;'>Entry Date</td>
    </tr>
    <?php
    if (isset($getstatushistory)) {
        for ($i = 0; $i < count($getstatushistory); $i++) {
            ?>
            <tr>
                <th><?php echo $getstatushistory[$i]['vehicleno']; ?></th>
                <th><?php echo $getstatushistory[$i]['triplogno']; ?></th>
                <th><?php echo $getstatushistory[$i]['tripstatus']; ?></th>
                <?php
                if($_SESSION['customerno'] == '447' ){ ?>
                    <th><?php echo $getstatushistory[$i]['varLrCreation']; ?></th>
                    <th><?php echo $getstatushistory[$i]['lrdelay']; ?></th>
                    <th><?php echo $getstatushistory[$i]['varYardCheckout']; ?></th>
                    <th><?php echo $getstatushistory[$i]['varYardDetention']; ?></th>
                    <th><?php echo $getstatushistory[$i]['varYardCheckin']; ?></th>                    
                    <th><?php echo $getstatushistory[$i]['varEmptyReturnDeviation']; ?></th>   
                <?php                 
                }
                else{ ?>
                    <th><?php echo $getstatushistory[$i]['budgetedkms']; ?></th>
                    <th><?php echo $getstatushistory[$i]['budgetedhrs']; ?></th>
                    <th><?php echo $getstatushistory[$i]['consignor']; ?></th>
                    <th><?php echo $getstatushistory[$i]['consignee']; ?></th>
                    <th><?php echo $getstatushistory[$i]['billingparty']; ?></th>
              <?php  
              } ?>
                <th><?php echo date(speedConstants::DEFAULT_DATETIME, strtotime($getstatushistory[$i]['statusdate'])); ?></th>
            </tr>
        <?php } ?>
    </table>
    <?php
}
?>
