var bucketDetails = [];
var bucketArray = new Array(30);
var i = 0;
for (i = 0; i < bucketArray.length; i++) {
  bucketArray[i] = 0;
}
  var today = new Date();
    var dd = today.getDate()+1;
    var mm = today.getMonth()+1; 
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd; 

//console.log(bucketArray);
$('#addBucket').click(function() {
    var i;
    for (i = 0; i < bucketArray.length; i++) {
        if (bucketArray[i] == 0) {
            bucketCount = i;
            bucketArray[i] = 1;
            //console.log(bucketArray);
            break;
        }
        if (i == bucketArray.length - 1) {
            alert("Only " + bucketArray.length + " can be created per docket");
            return;
        }
    }


var optn_type = 1;    


var str="\
<div class='bucket' id='bucketForm"+bucketCount+"'>\
<div class='bucket-content' id='bucketContent"+bucketCount+"'>\
<span class='close' id='close"+bucketCount+"' onclick='Bucket_removeModal("+bucketCount+")'>&times;</span>\
<span class='close' id='close"+bucketCount+"' onclick='Bucket_hideModal("+bucketCount+")'>-</span>\
<form id='bucketFormId"+bucketCount+"' class='bucketForm' style=''>\
<div>\
<b>Bucket Type </b>\
<select name='OperationType"+bucketCount+"' id='OperationType"+bucketCount+"' onchange='operation_type("+bucketCount+")' >\
<option value=0>Select</option>\
<option value=1>Installation</option>\
<option value=2>Repair</option>\
<option value=4 >Replacement</option>\
<option value=5 >Reinstall</option>\
<option value=3 >Removal</option>\
</select>\
</div>\
<br>\
<table>\
<tr name='r_vehicle" + bucketCount + "' id='r_vehicle" + bucketCount + "' style='display:none;'>\
<td><label>Vehicle No</label></td>\
<td><input  type='text' name='vehicleno"+bucketCount+"' id='vehicleno"+bucketCount+"' size='20' placeholder='Enter Vehicle No' onkeyup='getVehicle("+bucketCount+");'/></td>\
<td><input name = 'deviceid"+bucketCount+"' id='deviceid"+bucketCount+"' type='hidden'></td>\
<td><input name = 'simcardid"+bucketCount+"' id='simcardid"+bucketCount+"' type='hidden'></td>\
<input type='hidden' id='BucketId" + bucketCount + "' name='BucketId" + bucketCount + "' value=''>\
</tr>\
<tr>\
<td><label>Appointment Date</label></td>\
<td> <input name='sapt_date"+bucketCount+"' id='sapt_date"+bucketCount+"' type='text' value="+today+"  onclick='Bucketdate("+bucketCount+")'/>\
</td>\
<td><label>Priority</label></td>\
<td><select name='spriority"+bucketCount+"' id='spriority"+bucketCount+"'>\
<option value=1>High</option>\
<option value=2>Medium</option>\
<option value=3>Low</option>\
</select>\
</td>\
</tr>\
<tr name='i_bucket" + bucketCount + "' id='i_bucket" + bucketCount + "' style='display:none;'>\
<td><label>No. of Installations</label></td>\
<td><select name='no_inst"+bucketCount+"' id='no_inst"+bucketCount+"' onchange='no_inst_check("+bucketCount+")'>\
<option value=1 selected>1</option>\
<option value=2 >2</option>\
<option value=3 >3</option>\
<option value=4 >4</option>\
<option value=5 >5</option>\
<option value=6 >6</option>\
<option value=7 >7</option>\
<option value=8 >8</option>\
<option value=9 >9</option>\
<option value=10 >10</option>\
</select>\
</td>\
<td><label>New Vehicle No.</label></td>\
<td><input name = 'vehno"+bucketCount+"' id='vehno"+bucketCount+"' type='text' placeholder='Enter Vehicle No.'></td>\
</tr>\
<tr>\
<td><label>Location</label></td>\
<td><input name = 'slocation"+bucketCount+"' id='slocation"+bucketCount+"' type='text' placeholder='Enter Location'></td>\
<td><label>Timeslot</label></td>\
<td>\
<select align='right ' class='formControl' id='stimeslot"+bucketCount+"' name='stimeslot"+bucketCount+"'></select>\</td>\
</tr>\
<tr>\
<td><label>Details</label></td>\
<td><input name = 'sdetails"+bucketCount+"' id='sdetails"+bucketCount+"' type='text' placeholder='Enter Details'></td>\
</tr>\
<tr>\
<td><label>Co-ordinator</label></td>\
<td><select align='right ' class='formControl' id='scoordinator"+bucketCount+"' name='scoordinator"+bucketCount+"'>\
<option value='0'>Select a Co-ordinator</option>\
</select>\</td>\
 <td>\
OR<input name = 'newCord"+bucketCount+"' id='newCord"+bucketCount+"' type='checkbox' onchange='coordinator_name("+bucketCount+")'></td\
</td>\
<td>New Co-ordinator Name<input name='coname"+bucketCount+"' id='coname"+bucketCount+"' type='text' placeholder='Enter Name' disabled>\
<br>New Co-ordinator Phone<input name='cophone"+bucketCount+"' id='cophone"+bucketCount+"' type='text' placeholder='Enter Number' disabled>\
</td>\
</tr>\
<tr name='r_email" + bucketCount + "' id='r_email" + bucketCount + "' style='display:none;'>\
<td><label>Send Mail</label></td>\
<td><input id='sendmailsuspect"+bucketCount+"' type='checkbox' value=1 name='sendmailsuspect"+bucketCount+"'></td>\
<td><label>Comments</label></td>\
<td><input name = 'scomments"+bucketCount+"' id='scomments"+bucketCount+"' type='text' placeholder='Enter Comments'></td>\
</tr>\
</div>\
  </table>\
<input type='button' style='margin-left:40%;background-color:#3b5998;color:#ffffff;'  id='saveBucket' onclick='refreshGrid_bucket("+bucketCount+")' value='Save Bucket'>\
  </form>\
  </div>\
  </div>";
    $('#bucketDiv').after(str);
    //details ={};
    loadData_bucket(bucketCount);
    var modal = document.getElementById("bucketForm" + bucketCount + "");
    modal.style.display = "block";
});

function loadData_bucket(bucketCount, details = {
    
    "scoordinator": "0",
    "apt_date":today
}) {
    
    //console.log(details.apt_date);
    //console.log(today);
    bucketArray[bucketCount]=1;
    
   
      var Timeslot = '';
      jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "getTimeslot=1",
        success: function(data){
            
          var result = JSON.parse(data);
          Timeslot = '';
          $.each(result,function(i,text){
                if (details.timeslot == text.timeslot) {
                    Timeslot = Timeslot + "<option value='" + text.tsid + "' selected>" + text.timeslot + "</option>";
                } else {
                    Timeslot = Timeslot + "<option value='" + text.tsid + "'>" + text.timeslot + "</option>";
                }
            });

          $('#stimeslot'+bucketCount).html(Timeslot);
        }
      });
      
      var Cordinator='';
      var data=$('#customerno').val();
      jQuery.ajax({

        type: "POST",
        url: "Docket_functions.php",
        data: "customerno="+data+"&getCordinator=1",
        success: function(data){
          var result = JSON.parse(data);
          var Cordinator = '';
          Cordinator = Cordinator + '<option value = '+"0"+'>'+"Select Cordinator"+'</option>';
          $.each(result,function(i,text){

            if (details.person_name == text.person_name) {
                    Cordinator = Cordinator + "<option value='" + text.cpdetailid + "' selected>" + text.person_name + "</option>";
                } else {
                    Cordinator = Cordinator + "<option value='" + text.cpdetailid + "'>" + text.person_name + "</option>";
                }
          });
          $('#scoordinator'+bucketCount).html(Cordinator);


              var add_charge_check = details.add_charge;
   
                if(add_charge_check==1)
                {
                  
                   $('#add_charge'+bucketCount).prop('checked', true);
                   $('#charge_desc_div'+bucketCount).show();
                   $('#amt_charge_div'+bucketCount).show();
               }
        }
      });

    var date = details.apt_date;
    date = date.split("-");                                   //This is done because the date is stored 
    date= date[2]+ "-" +date[1]+ "-" +date[0];
    $('#sapt_date'+bucketCount).val(date);  // as yy-mm-dd in db


              
          
}

function Bucket_hideModal(id) {

    //console.log(bucketArray);
    $("#bucketForm" + id).hide();
}

function Bucket_removeModal(id) {
    bucketArray[id] = 0;
    //console.log(bucketArray);
    $("#bucketForm" + id).remove();
}



function editBucket(id,bucketid) {
    //console.log(bucketArray);
    var form=$('#bucketForm' + id);
    form.show();   
}

function bucket_createModal(details) {
  console.log(details);
    var i;
    for (i = 0; i < bucketArray.length; i++) {
        if (bucketArray[i] == 0) {
            bucketCount = i;
            bucketArray[i] = 1;
            break;
        }
        if (i == bucketArray.length - 1) {
            alert("Only " + bucketArray.length + " can be created per docket");
            return;
        }
    }
    
    

var optn_type = 0;    

    
    bucketDetails[bucketCount] = details;
  var str="\
<div class='bucket' id='bucketForm"+bucketCount+"'>\
<div class='bucket-content' id='bucketContent"+bucketCount+"'>\
<span class='close' id='close"+bucketCount+"' onclick='Bucket_hideModal("+bucketCount+")'>-</span>\
<form id='bucketFormId"+bucketCount+"' class='bucketForm' style=''>\
<div>\
<b>Bucket Type </b>\
<select name='OperationType"+bucketCount+"' id='OperationType"+bucketCount+"' onchange='operation_type("+bucketCount+")' value='" + details.purposeid + "'>\
<option value=1>Installation</option>\
<option value=2>Repair</option>\
<option value=4 >Replacement</option>\
<option value=5 >Reinstall</option>\
<option value=3 >Removal</option>\
</select>\
</div>\
<br>\
<table>\
<tr name='r_vehicle" + bucketCount + "' id='r_vehicle" + bucketCount + "' style='display:none;' >\
<td>Vehicle No</td>\
<input type='hidden' id='BucketId" + bucketCount + "' name='BucketId" + bucketCount + "' value='" + details.bucketid + "'>\
<td><input  type='text' name='vehicleno"+bucketCount+"' id='vehicleno"+bucketCount+"' size='20' value='" + details.vehicleno + "' readonly placeholder='Enter Vehicle No' onkeyup='getVehicle("+bucketCount+");'/></td>\
<input name = 'deviceid"+bucketCount+"' id='deviceid"+bucketCount+"' type='hidden' value='" + details.unitid + "'>\
<input name = 'simcardid"+bucketCount+"' id='simcardid"+bucketCount+"' type='hidden' value='" + details.simcardid + "'>\
<input type='hidden' name='vehicleid"+bucketCount+"' id='vehicleid"+bucketCount+"' value='" + details.vehicleid + "'/>\
<tr>\
<td>Appointment Date </td>\
<td> <input name='sapt_date"+bucketCount+"' id='sapt_date"+bucketCount+"' type='text' value='"+details.apt_date+"'  onclick='Bucketdate("+bucketCount+")' readonly/>\
</td>\
<td>Priority</td>\
<td><select name='spriority"+bucketCount+"' id='spriority"+bucketCount+"'>\
<option value=1>High</option>\
<option value=2>Medium</option>\
<option value=3>Low</option>\
</select>\
</td>\
</tr>\
<tr name='i_vehicle"+bucketCount+"' id='i_vehicle"+bucketCount+"' style='display:none;'>\
<td>Vehicle No</td>\
<td><input name = 'vehno"+bucketCount+"' id='vehno"+bucketCount+"' type='text' value='"+details.vehno+"'></td>\
<td>Location</td>\
<td><input name = 'slocation"+bucketCount+"' id='slocation"+bucketCount+"' type='text' value='" + details.location + "'></td>\
</tr>\
<tr>\
<td>Timeslot </td>\
<td>\
<select align='right ' class='formControl' id='stimeslot"+bucketCount+"' name='stimeslot"+bucketCount+"'></select>\
</td>\
<td>Details</td>\
<td><input name = 'sdetails"+bucketCount+"' id='sdetails"+bucketCount+"' type='text' value='" + details.details + "'></td>\
</tr>\
<tr>\
<td>Co-ordinator</td>\
<td><select align='right ' class='formControl' id='scoordinator"+bucketCount+"' name='scoordinator"+bucketCount+"'>\
<option value='0'>Select a Co-ordinator</option>\
</select>\</td>\
 <td>\
 OR<input name='newCord"+bucketCount+"' id='newCord"+bucketCount+"' type='checkbox' onchange='coordinator_name("+bucketCount+")'></td\
</td>\
<td>Co-ordinator Name<input name='coname"+bucketCount+"' id='coname"+bucketCount+"' type='text' disabled>\
<br> Co-ordinator Phone<input name='cophone"+bucketCount+"' id='cophone"+bucketCount+"' type='text' disabled>\
</td>\
</tr>\
<tr>\
 <td>Status</td>\
<td>\
<select name='sstatus"+bucketCount+"' id='sstatus"+bucketCount+"' onchange='changeFunc("+bucketCount+")'>\
<option value=0 selected>Modify</option>\
<option value=1>Reschedule</option>\
<option value=5>Cancel</option>\
</select>\
</td>\
</tr>\
<tr name='res_date"+bucketCount+"' id='res_date"+bucketCount+"' style='display:none;'>\
<td>Reschedule Date </td>\
<td><input name = 'reschedule_date"+bucketCount+"' id='reschedule_date"+bucketCount+"' value='' type='text' onclick='Bucketdate("+bucketCount+")'></td>\
</td>\
</tr>\
<tr name='c_reason"+bucketCount+"' id='c_reason"+bucketCount+"' style='display:none;'>\
<td>Cancellation Reason </td>\
<td><input name='creason"+bucketCount+"' id='creason"+bucketCount+"' type='text'></td>\
</td>\
</tr>\
<tr name='additional_charge"+bucketCount+"' id='additional_charge"+bucketCount+"'>\
<td>Additional Charge </td>\
<td><input name='add_charge"+bucketCount+"' value=1 id='add_charge"+bucketCount+"' type='checkbox' onchange='chargeFunc("+bucketCount+")'>\
</td\
</td>\
<td style='display:none;' id='charge_desc_div"+bucketCount+"'>Additional Charge Description\
<textarea name='charge_desc"+bucketCount+"' id='charge_desc"+bucketCount+"'  placeholder='Description' required></textarea>\
</td>\
<td style='display:none;' id='amt_charge_div"+bucketCount+"'>Additional Charge Amount\
<input name='amt_charge"+bucketCount+"' id='amt_charge"+bucketCount+"' type='text' placeholder='Amount' required>\
</td>\
</tr>\
</div>\
<tr><td colspan=2 style='text-align:center'><input type='button' id='saveBucket' style='background-color:#3b5998;color:#ffffff;margin-left:auto;margin-right:auto;' onclick='refreshGrid_bucket(" + bucketCount + ");' value='Save Bucket'></td></tr>\
  </table>\
  </form>\
  </div>\
  </div>";
    loadData_bucket(bucketCount, details);
    $('#bucketDiv').after(str);
    $('#OperationType'+bucketCount).val(details.purposeid);
    operation_type(bucketCount);
    if($('#OperationType'+bucketCount).val()==1){
          $('#OperationType'+bucketCount).attr('disabled',true);
    }
    
    if(details.description!=null)
    {
      var charge_description = details.description;             //null is checked for buckets where
                                                                //no amount is charged
      $('#charge_desc'+bucketCount).text(charge_description);
    }
    else
    {
      $('#charge_desc'+bucketCount).text();
    }

    if(details.amount!=null)
    {
      var charge_amount = details.amount;
      $('#amt_charge'+bucketCount).val(charge_amount);
    }
    else
    {
      $('#amt_charge'+bucketCount).val('');
    }

}

$("#interactionId").change(function() {
    var callValue = $('#interactionId').val()

    var i = callValue;
    if (i == 1) {
        $("#c_type").show();
        $("#in_type").attr('checked', true);
        if ($('#in_type').attr('checked', true) || $('#out_type').attr('checked', true)) {
            $("#c_type1").show();
            $("#in_type_div1").show();
            $("#out_type_div1").show();
        }

        $("#in_type_div").show();
        $("#out_type_div").show();

    } else if (i == 2) {
        $("#c_type").show();
        $("#in_type").attr('checked', true);


        if ($('#in_type').attr('checked', true) || $('#out_type').attr('checked', true)) {
            $("#c_type1").show();
            $("#in_type_div1").show();
            $("#out_type_div1").show();
        }
        $("#in_type_div").show();
        $("#out_type_div").hide();

    } else if (i == 3) {
        $("#c_type").show();
        $("#in_type_div").show();
        $("#in_type").attr('checked', true);
        $("#out_type_div").show();
        $("#c_type1").hide();
        $("#in_type_div1").hide();
        $("#out_type_div1").hide();

    } else if (i == 4) {
        $("#c_type").show();
        $("#in_type_div").hide();
        $("#out_type_div").show();
        $("#out_type").attr('checked', true);
        $("#c_type1").hide();
        $("#in_type_div1").hide();
        $("#out_type_div1").hide();

    } else {
        $("#c_type").hide();
        $("#in_type_div").hide();
        $("#out_type_div").hide();
        $("#c_type1").hide();
        $("#in_type_div1").hide();
        $("#out_type_div1").hide();
    }
});



  function operation_type(id){

    var i = $("#OperationType"+id).val();
    if(i==0){
      $("#i_bucket"+id).hide();
      $("#i_vehicle"+id).hide();
      $("#r_email"+id).hide();
      $("#r_comments"+id).hide();
      $("#r_vehicle"+id).hide();
    }
    else if(i==1){
      $("#i_bucket"+id).show();
      $("#i_vehicle"+id).show();
      $("#r_email"+id).hide();
      $("#r_comments"+id).hide();
      $("#r_vehicle"+id).hide();
    }
    else{
      $("#i_bucket"+id).hide();
      $("#i_vehicle"+id).hide();
      $("#r_email"+id).show();
      $("#r_comments"+id).show();
      $("#r_vehicle"+id).show();
      
    }


  }


  function coordinator_name(id){
   if ($('#newCord'+id).is(":checked")){
      $('#coname'+id).prop('disabled',false);
      $('#cophone'+id).prop('disabled',false);
      $('#scoordinator'+id).prop('disabled',true);
    }
  else{
      $('#coname'+id).prop('disabled',true);
      $('#cophone'+id).prop('disabled',true);
      $('#scoordinator'+id).prop('disabled',false);
    }

}

  function no_inst_check(id){
    if($('#no_inst'+id+ ' option:selected').text()>1){
      $('#vehno'+id).prop('disabled','disabled');
    }
    else{
      $('#vehno'+id).prop('disabled',false);
    }
  }

  function Bucketdate(id){
    jQuery('#sapt_date'+id).datepicker({
      dateFormat: "dd-mm-yy",
      language: 'en',
      autoclose: 1,
      startDate: Date()
    });
  

    jQuery('#reschedule_date'+id).datepicker({
      dateFormat: "dd-mm-yy",
      language: 'en',
      autoclose: 1,
      startDate: Date()
    });
  }

  function chargeFunc(id)
  {
    var i = $('#sstatus'+id).val();   

    if($("#add_charge"+id).is(":checked") && i==0)
    {

      $('#charge_desc_div'+id).show();
      $('#amt_charge_div'+id).show();
    }
    else{

      $('#charge_desc_div'+id).hide();
      $('#amt_charge_div'+id).hide();
    }
  }

    function getVehicle(id) {

        var vehicleno = '';
        var data = $('#customerno').val();
        jQuery("#vehicleno"+id).autocomplete({
            source: "Docket_functions.php?customerno=" + data+"&getVehicle=1",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value); 
                jQuery('#deviceid'+id).val(ui.item.uid);
                jQuery('#simcardid'+id).val(ui.item.simcardid);

            }
        });
    }

     function changeFunc(id) {
        var i = $('#sstatus'+id).val();   
        if (i == 1)
        {   
            $('#res_date'+id).show();
            $('#c_reason'+id).hide();
            $('#reschedule_date'+id).attr('disabled',false);
            $('#creason'+id).attr('disabled',true);
            $('#add_charge').prop('checked', false);
            $('#add_charge'+id).attr('disabled',true);
            $('#charge_desc_div'+id).hide();
            $('#amt_charge_div'+id).hide();
        }
        else if (i == 5)
        {
            $('#c_reason'+id).show();
            $('#res_date'+id).hide();
            $('#reschedule_date'+id).attr('disabled',true);
            $('#creason'+id).attr('disabled',false);
            $('#add_charge'+id).prop('checked',false);
            $('#add_charge'+id).attr('disabled',true);
            $('#charge_desc_div'+id).hide();
            $('#amt_charge_div'+id).hide();
        }
        else
        { 
            $('#res_date'+id).hide();
            $('#c_reason'+id).hide();
            $('#creason'+id).attr('disabled',true);
            $('#add_charge'+id).attr('disabled',false);
        }

    }


function checkForModifications_bucket(check, bucketDetails) {

    return true;
}

function getFormData_bucket($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function submitDocket(docketId) {
    $("#c_type").hide();
    $("#c_type1").hide();
    var data = $("#editDocket").serialize();
    var count = $("[id^='bucketForm']").length;
    var editFlag = false;
    for (i = 0; i < count; i++) {
        if ($('BucketId' + i) != '') {
            var check = getFormData_bucket($('#bucketFormId' + i))
            // ////console.log(check);
            // ////console.log(bucketDetails[i]);
            if (checkForModifications_bucket(check, bucketDetails[i])) {
                data = data + "&" + $('#bucketFormId' + i).serialize();
            }
        }

    }
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: data + "&edit_docket=1&numberOfTickets=" + count + "&docketid=" + docketId + "&bucketArray=" + bucketArray,
        success: function(response) {
            if (response = 'success') {
                alert("Docket generated successfully");
                //$('#editDocket')[0].reset();
            }
        }
    });
}



