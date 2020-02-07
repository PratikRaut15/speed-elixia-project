<style>
    .column {
        width: 49%;
        margin-right: .5%;
        height: 500px;
        background: #fff;
        float: left;
        overflow-y: scroll;
    }

    #column2 {
        background-image: url(../../images/drop.png);
        background-position: center;
        background-repeat: no-repeat;
    }

    .heading {
        width: 49%;
        margin-right: .5%;
        min-height: 21px;
        background: #cfc;
        float: left;
    }

    .column .dragbox {
        margin: 5px 2px 20px;
        background: #fff;
        position: "relative";
        border: 1px solid #946553;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        width: inherit;
    }

    .column .dragbox h2 {
        margin: 0;
        font-size: 12px;
        background: #946553;
        color: #fff;
        border-bottom: 1px solid #946553;
        font-family: Verdana;
        cursor: move;
        padding: 5px;
    }

    .dragbox-content {
        background: #fff;
        min-height: 100px;
        margin: 5px;
        font-family: 'Lucida Grande', Verdana;
        font-size: 0.8em;
        line-height: 1.5em;
    }

    .column .placeholder {
        background: #EED5B7;
        border: 1px dashed #946553;
    }

    .alert-info {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #3a87ad;
        cursor: move;
    }

    /*.clor{
border: 1px solid #d3d3d3;
background: #e6e6e6 url(http://code.jquery.com/ui/1.10.4/themes/smoothness/images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x;
font-weight: normal;
color: #555555;
	cursor:move;
}*/
</style>
<div style="width: 67%;">
    <div class="heading" id="head1">Checkpoint</div>
    <div class="heading" id="head1">Route List</div>
    <div id="column1" class="column">
        <?php
        $checkpoints = getchks();
        if (isset($checkpoints)) {
            foreach ($checkpoints as $checkpoint) {
                echo '<div class="alert-info" id=' . $checkpoint->checkpointid . ' rel="' . $checkpoint->cname . '">
    					<h2 style="font-size: 14px;font-weight: normal; margin: 2px;"><span>::</span>   ' . $checkpoint->cname . '</h2>
    				</div>';
                //echo "<li class='dragbox' id='recordsArray_$checkpoint->checkpointid'><span>::</span>   $checkpoint->cname</li>";
            }
        }
        ?>
    </div>
    <div class="column" id="column2"></div>
</div>
<?php include 'panels/addroute.php'; ?>
<tr>
    <td>Route Name</td>
    <td><input type="text" name="routename" id="routename" placeholder="Route Name" maxlength="20" style="width:102px;"></td>
    <td>Route TAT(In Hrs)</td>
    <td><input type="text" name="routeTat" id="routeTat" placeholder="Route TAT" maxlength="20" style="width:102px;"></td>
    <td>Select Vehicles</td>
    <td>
        <input type="text" name="vehicleno" id="vehicleno" value="" autocomplete="off" placeholder="Enter Vehicle No">
        <input type="hidden" name="vehicleid" id="vehicleid" size="20" />
    </td>
    <?php if (isset($_SESSION['customerno']) && ($_SESSION['customerno'] == speedConstants::CUSTNO_APTINFRA || $_SESSION['customerno'] == speedConstants::CUSTNO_DELEX)) {
        $routeTypes = getRouteTypesByCustomer();
        ?>
        <td>Route Type</td>
        <td>
            <select name="routeType" id="routeType">
                <?php foreach ($routeTypes as $types) { ?>
                    <option value="<?php $types->routeTypeId; ?>"><?php echo $types->routeTypeName ?></option>
                <?php } ?>
            </select>
        </td>
    <?php } ?>
    <td>
        <input type="button" value="Add All" onclick="addallvehicleForRoute()" class="g-button g-button-submit">
    </td>
</tr>
<tr>
    <td colspan="100%">
        <div id="vehicle_list_route"></div>
    </td>
</tr>
<tr id="chkEtaDetails" style="display: none;">
    <td colspan="100%">
        <table id="chkEtaTable">
            <tr>
                <td>Checkpoint</td>
                <td>ETA</td>
                <td>ETD</td>
                <td>Return ETA</td>
                <td>Return ETD</td>
                <td>Distance From Last Checkpoint(in KM)</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="7" align="center"><input type="button" value="Save" onclick="checkroute();"></td>
</tr>
</tbody>
</table>