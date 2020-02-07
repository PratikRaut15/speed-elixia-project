<div class="container">
    
    <h3>Order Mapped Sequence</h3><br/>
    
    <?php
    $today = date('d-m-Y'); 
    $vehicles = get_vehicles_arr();
    $slots = get_slots();
    ?>
    
    <table>
        <thead>
            <tr>
                <th>Vehicle</th>
                <th>Slot</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select id='vehicleid' >
                    <option value='0'>-All Vehicles-</option>
                    <?php
                    foreach($vehicles as $vid=>$vehdata){
                        echo "<option value='$vid'>{$vehdata['vehno']}</option>";
                    }
                    ?>
                    </select>
                </td>
                <td>
                    <select id="slotid" >
                        <option value='0'>-All Slots-</option>
                    <?php
                    foreach($slots as $sid=>$sname){
                        echo "<option value='$sid'>$sid</option>";
                    }
                    ?>
                    </select>
                </td>
                <td><input type="text" class='search_init' id='entrytime' value='<?php echo $today; ?>' /></td>
                <td>
                    <input id='getSequence' onclick='getSequence();' type="submit" value="Get Data" class="g-button g-button-submit" style='margin-bottom:8px' />
                    <a href='javascript:void(0)' onclick="html2xlsSequence();"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                    <a href='javascript:void(0)' onclick="sequence_print('<?php  echo "Order Sequence"; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <center id='centerDiv'></center>
</div>