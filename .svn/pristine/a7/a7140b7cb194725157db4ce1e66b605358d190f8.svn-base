jQuery(document).ready(function (){
    jQuery("#vehicleno").autocomplete({
        source: "autocomplete.php?action=vehicleList",
        minLength: 1,
            select: function (event, ui) {
               jQuery('#vehicleId').val(ui.item.vehicleid);
               displayVehicleDiv(ui.item.vehicleid, ui.item.value);
                ui.item.value = '';

            }
    });
    jQuery("#checkpoint").autocomplete({
        source: "autocomplete.php?action=checkpointList",
        minLength: 1,
            select: function (event, ui) {
               jQuery('#checkpointId').val(ui.item.checkpointid);
               displayCheckpointDiv(ui.item.checkpointid, ui.item.value);
                ui.item.value = '';
            }
    });
});
function displayVehicleDiv(vehicleid,selected_name) {
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        jQuery("#vehicleRow").show();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeVehicle(vehicleid);
        };
        div.className = 'recipientbox vehList';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery(".vehicleList").append(div);
        jQuery(div).append(remove_image);
    }
    jQuery("#vehicleno").val('');
}
function removeVehicle(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
}
function displayCheckpointDiv(checkpointId,selected_name) {
    if (checkpointId > -1 && jQuery('#to_checkpoint_div_' + checkpointId).val() == null) {
        jQuery("#checkpointRow").show();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeCheckpoint(checkpointId);
        };
        div.className = 'recipientbox chkList';
        div.id = 'to_checkpoint_div_' + checkpointId;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_checkpoint_' + checkpointId + '" value="' + checkpointId + '"/>';
        jQuery(".checkpointList").append(div);
        jQuery(div).append(remove_image);

    }
    jQuery("#checkpoint").val('');
}
function removeCheckpoint(checkpointId) {
    jQuery('#to_checkpoint_div_' + checkpointId).remove();
}
function createException(){
    var vehiclearray = new Array();
    var checkpointarray = new Array();
    jQuery(".vehList").each(function() {
       vehiclearray.push(this.id);
    });
    jQuery(".chkList").each(function() {
       checkpointarray.push(this.id);
    });
    var dateParam = jQuery('#todaysDate').val();
    var startTime = jQuery('#STime').val();
    var endTime = jQuery('#ETime').val();
    var diff = new Date(dateParam+' '+endTime) - new Date(dateParam+' '+startTime);
    var diff_time = diff/(60*1000);
    if(jQuery('#exceptionName').val() == ''){
        alert("Please Enter Checkpoint Exception Name");
    }else if(checkpointarray == ''){
        alert("Please Select Checkpoint");
    }else if(vehiclearray == ''){
        alert("Please Select Vehicle");
    }else if(diff_time < 0) {
        alert("Start Time Should Be Less Than End Time");
    }else if(diff_time < 15) {
        alert("Time Range Not Less Than 15 Min.");
    }else if(jQuery("#exceptionType").val()== -1) {
        alert("Please Select Exception Type");
    } else{
        var data = jQuery("#chkExpForm").serialize();
        var action = "chkException=1";
        var dataString = action+"&" + data;
        jQuery.ajax({
            url: 'route_ajax.php',
            type: 'POST',
            data: dataString,
            success: function (result) {
                if(result == 1){
                    window.location = 'checkpointException.php?id=2';
                }
            }
        });
    }

}
