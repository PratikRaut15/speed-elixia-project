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
                <th>Pickup Boy</th>
                <th>Slot</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select id='vehicleid' >
                    <option value='0'>-All Pickup Boys-</option>
                    <?php
                    foreach($vehicles as $vid=>$vehdata){
                        echo "<option value='$vid'>{$vehdata['username']}</option>";
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
                <td><input id='getSequence' onclick='getSequence();' type="submit" value="Get Data" class="g-button g-button-submit" style='margin-bottom:8px' /></td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <center id='centerDiv'></center>
</div>