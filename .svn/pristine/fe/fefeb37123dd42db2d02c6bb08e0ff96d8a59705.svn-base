<div>
<form name="chkExpForm" id="chkExpForm">
    <table >
    <tr>
        <th id="formheader" colspan="6">Checkpoint Exception</th>
    </tr>

        <tr>
            <td>Exception Name</td>
            <td>Select Checkpoint</td>
            <td>Select VehicleNo</td>
            <td>Start Time</td>
            <td>End Time</td>
            <td>Exception</td>
        </tr>
        <tr>
            <td><input type="text" name="exceptionName" id="exceptionName" value="" placeholder="Enter Exception Name" ></td>
            <td><input type="text" name="checkpoint" id="checkpoint" value="" autocomplete="off" placeholder="Enter Checkpoint" ></td>
            <td><input type="text" name="vehicleno" id="vehicleno" value="" autocomplete="off" placeholder="Enter Vehicle No" ></td>
            <td><input type="text" name="startTime" id="STime"   class="input-mini" data-date="00:00" /></td>
            <td><input type="text" name="endTime" id="ETime"   class="input-mini" data-date2="23:59"/></td>
            <td>
                <select id="exceptionType" name="exceptionType">
                    <option value="-1">Select</option>
                    <option value="1">In</option>
                    <option value="2">Out</option>
                </select>
            </td>
        </tr>
        <tr style="display: none;" id="checkpointRow">
            <td colspan="6" class="checkpointList"></td>
        </tr>
        <tr style="display: none;" id="vehicleRow">
            <td colspan="6" class="vehicleList"></td>
        </tr>
        <tr>
            <td colspan="6">
            <input type="hidden" id="vehicleId" name="vehicleId" value="">
            <input type="hidden" id="checkpointId" name="checkpointId" value="">
            <input type="hidden" id="todaysDate" name="todaysDate" value="<?php echo date('Y-m-d');?>">
            <input type="submit" value="Set Exception" class="g-button g-button-submit" name="setException" onclick="createException();return false;">
            </td>
        </tr>
    <table>
<form>
</div>
