<div class="modal hide" id="getecode">
<form class="form-horizontal" id="getecode1">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Generate Client Code</h4>
    </div>
    <div class="modal-body">
            <span id="error" name="error" style="display: none;">Please Check The Date</span>
                <span id="vehiclearray" name="vehiclearray" style="display: none;">Please Select Vehicle</span>
                <span id="emailerror" name="emailerror" style="display: none;">Not a valid e-mail address</span>
                <span id="smserror" name="smserror" style="display: none;">Not a valid phone number</span>
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Expiry Date</span>
                <?php 
		function getcurrentdate()
			{
				$currentdate = strtotime(date("Y-m-d H:i:s")); 
				$currentdate = substr($currentdate, '0',11);
				return $currentdate;
			}
		$currentdate = getcurrentdate();?>
            <input id="currentdate" name="currentdate" type="hidden" value="<?php echo date('d-m-Y H:i:s',$currentdate);?>">
            <input id="SDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                    <span class="add-on" style="color:#000000">Hour</span>
                   <input id="STTime" name="STime" type="text" />
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Select Vehicle </span>
                <select id="vehicleid" class='vehicle_<?php //echo $vehicleid; ?>' name="vehicleid"  onChange="addvehicle()">
                <option value="-1">Select Vehicle</option>
                <?php
                $vehicles = getvehicles();
                foreach ($vehicles as $vehicle)
                {
                    echo "<option id='v_$vehicle->vehicleid' value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
                }
                ?>
                </select>
                </div>
                <div class="input-prepend ">
                    <input type="button" value="Add All" onclick="addallvehicle()" class="btn btn-mini btn-primary" >
                </div>
                </div>
	</fieldset>
        <div class="control-group">
            <div id="vehicle_list"></div>
            <?php $ecoderandom = mt_rand(0, 999999); ?>
        </div>
            <table width="100%">
                <tr>
                    <th colspan="100%" style="text-align:center; background-color: #4D90FE; color: white; font-size: 13px;">Share Your Code : <?php echo $ecoderandom; ?><input type="hidden" name="randomecode" id="randomecode" value="<?php echo $ecoderandom; ?>"></input></th>
                </tr>
                <tr>
                    <td style="text-align:center;"><span class="add-on" style="color:#000000">Email</span><input type="text" name="email" id="email"></td>
                    <td style="text-align:center;"><span class="add-on" style="color:#000000">SMS</span><input type="text" name="sms" id="sms"></td>
                </tr>
           </table>
    </div>
</fieldset>
</form>
    <div class="modal-footer">
        <button onclick="chksubmit();" class="btn btn-success">Save</button>
    </div>
</div>

<script>
function chksubmit()
{
    var selecteddate = jQuery("#SDate").val();
    var STime = jQuery('#STTime').val();
    //alert(STime);exit;
    var selectedhour = jQuery("#hour").val();
    var selectedminutes = jQuery("#minutes").val();
    var datetime = selecteddate + " " + STime + ":" + "00";
    var vehiclearray = new Array();
    var email=jQuery("#email").val();
    var phone=jQuery("#sms").val();
    jQuery(".recipientbox").each(function() {
       vehiclearray.push(this.id);
    });
    if(vehiclearray == ''){
                                    jQuery("#vehiclearray").show();
                                    jQuery("#vehiclearray").fadeOut(4000);
    }
    else if(datetime>=jQuery("#currentdate").val())
    {
        if(email != ''){
                        var atpos=email.indexOf("@");
                        var dotpos=email.lastIndexOf(".");
                        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
                        {
                        jQuery("#emailerror").show();
                        jQuery("#emailerror").fadeOut(3000);
                        }
                        else if(phone != ''){
                            phone = phone.replace(/[^0-9]/g, '');
                            if(phone.length != 10) { 
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
                            }
                            else{
                            submitecodedata();
                            }
                        }
                        else{
                        submitecodedata();
                        }
            }
        else if(phone != ''){
            phone = phone.replace(/[^0-9]/g, '');
                        if(phone.length != 10) { 
                        jQuery("#smserror").show();
                        jQuery("#smserror").fadeOut(3000);
                        }
                        else{
                        submitecodedata();
                        }
            }
        else{
        submitecodedata();
        }
    }
    else
    {
         jQuery("#error").show();
        jQuery("#error").fadeOut(3000);
    }
}

function submitecodedata()
{
    var vehicleid = '<?php echo $vehicleid; ?>';
    var data = jQuery('#getecode1').serialize();
    jQuery.ajax({
                type: "POST",
                url: "../../modules/ecode/route.php",
                data: data,
                cache: false,
                success: function(html)
                {    
                //jQuery(".popup").hide();
                //jQuery(".overlay").hide();
                jQuery("#getecode").trigger(":reset");
                jQuery('#getecode').modal('hide');
                call_row(vehicleid);
                call_row(vehicleid);
                }
        });
         
}

/*Calendar.setup(
{
    inputField : "date", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger" // ID of the button
});*/
</script>