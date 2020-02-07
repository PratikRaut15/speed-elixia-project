<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #ajaxstatus{text-align:center;font-weight:bold;display:none}
    .mandatory{color:red;font-weight:bold;}
    #addorders table{width:50%;}
    #addorders .frmlblTd{text-align:center}    
</style>
<br/>
<div class='container' >
    <center>   
        <span id="vehiclearray" name="vehiclearray" style="display: none; color: red;">Please Select Vehicle Type</span>
        <span id="error" name="error" style="display: none;color: red;">Please enter proper details</span>
        <form id="addorders" method="POST" action="action.php?action=edit-transporter">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Transporter</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'>Transporter Code<span class="mandatory">*</span></td>
                        <td><input type="text" name="transporter_code" value="<?php echo $transporter[0]['transportercode'] ?>" required maxlength="20"></td></tr>
                    <tr><td class='frmlblTd'>Transporter Name<span class="mandatory">*</span></td>
                        <td><input type="text" name="transporter_name" value="<?php echo $transporter[0]['transportername'] ?>" required maxlength="50"></td></tr>
                    <tr><td class='frmlblTd'>Email<span class="mandatory">*</span></td>
                        <td><textarea id="email" name="transporter_mail" id="transporter_mail" rows="3"><?php echo $transporter[0]['transportermail'] ?></textarea>
                        </td>

                <div id="display" class="listlocation"></div>
                </tr>

                <tr><td class='frmlblTd'>Mobile<span class="mandatory">*</span></td>
                    <td><textarea id="email" name="transporter_mobile" id="transporter_mobile" rows="3"><?php echo $transporter[0]['transportermobileno'] ?></textarea>
                    </td>
                <div id="display" class="listzone"></div>
                </tr>

                <tr><td class='frmlblTd'>Vehicle Types<span class="mandatory">*</span></td>
                    <td>
                        <select id="vehicletypeid" name="vehicletypeid" onchange="addvehicle()">
                            <option value="-1">Select Vehicle Type</option>
                            <?php
                            echo $vehtypelist;
                            ?>
                        </select>
                        <input type="button" value="Add All" onclick="addallvehicle()" class="g-button-submit">
                        <div id="vehicle_list">
                            <?php
                            if (isset($arrVehicleMapping)) {
                                foreach ($arrVehicleMapping as $vehicletype) {

                                    echo '<div class="recipientbox" id="to_vehicle_div_' . $vehicletype['vehicletypeid'] . '"><span>' . $vehicletype['vehicledescription'] . '-'.$vehicletype['vehiclecode'].'</span>
                        <input type="hidden" value="' . $vehicletype['vehicletypeid'] . '" name="to_vehicle_' . $vehicletype['vehicletypeid'] . '">
                        <img class="clickimage" src="../../images/boxdelete.png" onclick="removeVehicle(' . $vehicletype['vehicletypeid'] . ')" ></div>';
                                }
                            }
                            ?>
                        </div>
                    </td>

                </tr>

                <input type='hidden' id='transporterid' name='transporterid' value="<?php echo $transporter[0]['transporterid'] ?>" >
                <tr><td class='frmlblTd'><input type="button" value="Save" class='btn btn-primary' onclick="chksubmitupdate();"></td>
                    <td class='frmlblTd'><a href="tms.php?pg=view-transporter"><input type="button" value="Cancel" class='btn btn-danger'></a></td>
                </tr>
                </tbody>
            </table>
        </form>
    </center>
</div>    

