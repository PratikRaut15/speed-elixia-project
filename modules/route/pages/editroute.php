<?php
    if (isset($_GET['did'])) {
        $routecheckpoints = get_chks_for_route($_GET['did']);
    }
?>
<style>
.column{
	width:49%;
	margin-right:.5%;
	height:500px;
	background:#fff;
	float:left;
    overflow-y: scroll;
}
#column2{
    background-image: url(../../images/drop.png);
    background-position: center;
    background-repeat: no-repeat;
}
.heading{
	width:49%;
	margin-right:.5%;
	min-height:21px;
	background:#cfc;
	float:left;
}
.column .dragbox{
	margin:5px 2px  20px;
	background:#fff;
	position:"relative";
	border:1px solid #946553;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
        width: inherit;
}
.column .dragbox h2{
	margin:0;
	font-size:12px;
	background:#946553;
	color:#fff;
	border-bottom:1px solid #946553;
	font-family:Verdana;
	cursor:move;
	padding:5px;
}

.dragbox-content{
	background:#fff;
	min-height:100px; margin:5px;
	font-family:'Lucida Grande', Verdana; font-size:0.8em; line-height:1.5em;
}
.column  .placeholder{
	background: #EED5B7;
	border:1px dashed #946553;
}
.alert-info {
background-color: #d9edf7;
border-color: #bce8f1;
color: #3a87ad;
cursor:move;
}
</style>

<div style="width: 67%;">
<div class="heading" id="head1">Checkpoint</div>
<div class="heading" id="head1">Route List</div>
<div id="column1" class="column">
<?php
    $checkpoints = getchks();
    if (isset($checkpoints)) {
        if (isset($routecheckpoints)) {
            foreach ($routecheckpoints as $checkpoint) {
                $checkpointid[] = $checkpoint->checkpointid;
            }
        }
        //print_r($checkpointid);
        foreach ($checkpoints as $checkpoint) {
            if ($checkpoint->checkpointid != null) {
                if (in_array($checkpoint->checkpointid, $checkpointid, TRUE)) {
                } else {
                    echo '<div class="alert-info" id=' . $checkpoint->checkpointid . ' rel="' . $checkpoint->cname . '">
                        <h2 style="font-size: 14px;font-weight: normal; margin: 2px;"><span>::</span>   ' . $checkpoint->cname . '</h2>
                    </div>';
                }
            }
        }
    }
?>
	</div>
	<div class="column" id="column2" >
        <?php
            if (isset($routecheckpoints)) {
                foreach ($routecheckpoints as $checkpoint) {
                    echo '<div class="alert-info" id=' . $checkpoint->checkpointid . ' rel="'. $checkpoint->cname .'">
                    <h2 style="font-size: 14px;font-weight: normal; margin: 2px;"><span>::</span>   ' . $checkpoint->cname . '
                    </h2>
                    </div>';
                }
            }
        ?>
	</div>
</div>
    <?php include 'panels/editroute.php';?>
        <tr>
        <td>Route Name</td>
        <td><input type="hidden" name="routeid" id="routeid" value="<?php echo $_GET['did']; ?>">
        <input type="text" name="routename" id="routename" placeholder="Route Name" value="<?php echo $_GET['routename']; ?>"></td>
        <td>Route TAT(In Hrs)</td>
        <td><input type="text" name="routeTat" id="routeTat" placeholder="Route TAT" value="<?php echo $_GET['routeTat']; ?>" style="width:102px;"></td>
        <td>Select Vehicles</td>
        <td style="display: none;">
            <select id="vehicleroute" name="vehicleroute" onChange="VehicleForRoute()">
                <option value=''>Select Vehicle</option>
                <?php
                    $vehicles = getvehicles();
                    if (isset($vehicles)) {
                        foreach ($vehicles as $vehicle) {
                           echo "<option value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
                        }
                   }
                ?>
            </select>
        </td>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="17" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" />
            <div id="display" class="listvehicle"></div>
        </td>

        <?php if(isset($_SESSION['customerno']) && $_SESSION['customerno'] == speedConstants::CUSTNO_APTINFRA) { ?>
        <td>Route Type</td>
        <td>
            <select name="routeType" id="routeType">
                <option value="1">Loaded</option>
                <option value="0">Empty</option>
            </select>
        </td>
        <?php } ?>

        <td>
            <input type="button" value="Add All" onclick="addallvehicleForRoute()" class="g-button g-button-submit">
        </td>
        </tr>
        <tr>
            <td colspan="100%">
                <div id="vehicle_list_route">
                <?php
                $addedvehicles = getaddedvehicles($_GET['did']);
                if (isset($addedvehicles)) {
                    foreach ($addedvehicles as $vehicle) {
                    ?>
                    <input type="hidden" class="mappedvehicles" id="hid_v<?php echo ($vehicle->vehicleid); ?>" rel="<?php echo ($vehicle->vehicleid); ?>" value="<?php echo ($vehicle->vehicleno); ?>">
                    <?php
                    }
                }
                ?>
                </div>
            </td>
        </tr>
        <tr id="chkEtaDetails">
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
                    <?php
                    if(isset($routecheckpoints) && !empty($routecheckpoints)){
                        foreach ($routecheckpoints as $routeKey=>$routeChk) {
                            $readonly = ($routeKey == '0')?'readonly':'';
                            $recordRow ='';
                            $recordRow .='<tr id="chkEta' .$routeChk->checkpointid. '" class="tableRow">';
                            $recordRow .='<td><input type="hidden" class="chkId" name="chkId'.$routeChk->checkpointid.'" id="chkId'.$routeChk->checkpointid.'" value="'.$routeChk->checkpointid.'">';
                            $recordRow .='<input class="chkName" name="chkName'.$routeChk->checkpointid.'" id="chkName'.$routeChk->checkpointid.'" value="'.$routeChk->cname.'"></td>';
                            $recordRow .='<td><input class="eta  elixiaTimePicker input-mini" name="eta'.$routeChk->checkpointid.'" id="eta'.$routeChk->checkpointid.'" value="'.$routeChk->eta.'" ></td>';
                            $recordRow .='<td><input class="etd elixiaTimePicker input-mini" name="etd'.$routeChk->checkpointid.'" id="etd'.$routeChk->checkpointid.'" value="'.$routeChk->etd.'"></td>';
                            $recordRow .='<td><input class="returneta elixiaTimePicker input-mini" name="returneta'.$routeChk->checkpointid.'" id="returneta'.$routeChk->checkpointid.'" value="'.$routeChk->r_eta.'"></td>';
                            $recordRow .='<td><input class="returnetd elixiaTimePicker input-mini" name="returnetd'.$routeChk->checkpointid.'" id="returnetd'.$routeChk->checkpointid.'" value="'.$routeChk->r_etd.'"></td>';
                            $recordRow .='<td><input class="km input-mini" name="km'.$routeChk->checkpointid.'" id="km'.$routeChk->checkpointid.'" value="'.$routeChk->km.'" '.$readonly.'></td>';
                            $recordRow .='</tr>';
                            echo $recordRow;
                        }
                    }
                    ?>
                </table>
            </td>
        </tr>
        <tr>
        <td colspan="5" align="center"><input type="button" value="Save" onclick="modifyroutechk();"></td>
        </tr>
        </tbody>
        </table>

