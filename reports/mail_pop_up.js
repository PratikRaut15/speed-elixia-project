function refreshFun(data){
    jQuery("#emailText").autocomplete({
        source: "route_ajax.php?work=getmail&customerno="+data,
        select: function (event, ui) {
            jQuery('#emailText1').val(ui.item.eid);
            insertEmailDiv(ui.item.value, ui.item.eid);
            /*clear selected value */
            jQuery(this).val("");
            return false;
        }
    });
} 
function insertMailId(data){
    var emailid1;
    var emailText1=document.getElementById("emailText").value;
    var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;               
    
    if(!testEmail.test(jQuery("#emailText").val())){
        alert("Enter Valid Mail Id");
        return false;
    }
    else{   
    $.ajax({
            url:'route_ajax.php?work=insertmail&dataTest='+emailText1+'&customerno1='+data,
            type: 'post',
            success: function(data) {
                $("#getid").val(data);
                emailid1=$("#getid").val();        
                insertEmailDiv(emailText1,emailid1);
            }
        });
        jQuery("#emailText").val("");
    }    
}
function insertEmailDiv(selected_name, vehicleid) {
        $("#sentoEmail").val(function(i,val){ 
            if(!val.includes(selected_name)){
                return val + (!val ? '' : ',') + selected_name;
            }
            else{
                return val;
            }
        });
        
        if (vehicleid!="" && jQuery('#em_vehicle_div_' + vehicleid).val() == null) {
            var div = document.createElement('div');
            div.id="contain";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            
            remove_image.onclick = function () {
                removeEmailDiv(selected_name,vehicleid);
            };
            
            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + vehicleid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
            jQuery("#listvehicle1").append(div);
            jQuery(div).append(remove_image);
        }
    }
function removeEmailDiv(name,vehicleid) {
    var rep=","+name;
    $("#sentoEmail").val($("#sentoEmail").val().replace(rep,""));
    $("#sentoEmail").val($("#sentoEmail").val().replace(name,""));
    $('#em_vehicle_div_'+vehicleid).remove();
    console.log($("#sentoEmail").val());
}


