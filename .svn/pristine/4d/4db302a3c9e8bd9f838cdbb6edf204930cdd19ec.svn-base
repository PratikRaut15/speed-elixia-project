jQuery(function () {
  jQuery('body').click(function () {
    jQuery('#ajaxstatus').hide();
  });
  jQuery('.showtable').click(function () {
    jQuery('#ajaxBstatus').hide();
  });
});

// For Users
function addnewuser()
{
  var role = jQuery('#role').val();
  var heirid = jQuery('#heirid').val();
  var roleid = $("#role").find('option:selected').attr("rel");
  var getin = false;
  if (jQuery("#role").val() == "0")
  {
    jQuery("#errorrole").show();
    jQuery("#errorrole").fadeOut(3000);
  }
  else if (jQuery("#nameid").val() == "")
  {
    jQuery("#errorname").show();
    jQuery("#errorname").fadeOut(3000);
  }
  else if (role == 'Administrator' || role == 'transporter' || role == 'factoryofficial' || role == 'elixir' || role == 'depotofficial')
  {
    getin = true;
  }
  if (getin == true)
  {
    if (jQuery("#email1").val() == "") {
      jQuery("#erroremail1").show();
      jQuery("#erroremail1").fadeOut(3000);
    }
    else if (jQuery("#email1").val() != "" && jQuery("#email1").val().match(/.+@.+\.(.+){2,}/) == null)
    {
      jQuery("#erroremail").show();
      jQuery("#erroremail").fadeOut(3000);
    }
    else if (jQuery("#password").val() == "")
    {
      jQuery("#errorpassword").show();
      jQuery("#errorpassword").fadeOut(3000);
    }
    else
    {
      var username = jQuery("#email1").val();
      jQuery.ajax({
        type: "POST",
        url: "../account/route_ajax.php",
        data: {
          username: username
        },
        async: true,
        cache: false,
        success: function (cdata1) {
          var statuscheck = cdata1;
          if (statuscheck == "ok")
          {
            if (jQuery("#email1").val() != "")
            {
              var valid_email = jQuery("#email1").val().match(/.+@.+\.(.+){2,}/);
              if (valid_email == null)
              {
                jQuery("#erroremail").show();
                jQuery("#erroremail").fadeOut(3000);
              }
              else if (jQuery("#phoneno").val() != '') {
                var phone = jQuery("#phoneno").val();
                phone = phone.replace(/[^0-9]/g, '');
                if (phone.length != 10) {
                  jQuery("#phoneerror").show();
                  jQuery("#phoneerror").fadeOut(3000);
                }
                else {
                  submituserdata();
                }
              }
              else
              {
                submituserdata();
              }
            }
            else if (jQuery("#phoneno").val() != '') {
              var phone = jQuery("#phoneno").val();
              phone = phone.replace(/[^0-9]/g, '');
              if (phone.length != 10) {
                jQuery("#phoneerror").show();
                jQuery("#phoneerror").fadeOut(3000);
              }
              else {
                submituserdata();
              }
            }
            else
            {
              submituserdata();
            }
          }
          else
          {
            jQuery("#errorusername").show();
            jQuery("#errorusername").fadeOut(3000);
          }
        }
      });
    }
  }
}
function submituserdata()
{
  if (!validate_exception()) {
    return false;
  }
  var role = jQuery('#role').val();
  var roleid = $("#role").find('option:selected').attr("rel");
  var dat = jQuery('#adduser').serialize();
  var data = dat + "&roleid=" + roleid;
  //alert(data);exit;
  jQuery.ajax({
    type: "POST",
    url: "../account/route.php",
    data: data,
    cache: false,
    success: function (html)
    {
      window.location = 'tms.php?pg=view-users';
    }
  });
}
function edituser()
{
  var role = jQuery('#role').val();
  var roleid = jQuery('#' + role).attr('rel');
  var getin = false;
  if (jQuery("#role").val() == "0")
  {
    jQuery("#errorrole").show();
    jQuery("#errorrole").fadeOut(3000);
  }
  else if (jQuery("#nameid").val() == "")
  {
    jQuery("#errorname").show();
    jQuery("#errorname").fadeOut(3000);
  }
  else if (role == 'Administrator' || role == 'elixir' || role == 'transporter' || role == 'factoryofficial' || role == 'depotofficial')
  {
    getin = true;
  }
  if (getin == true)
  {
    if (jQuery("#email1").val() != "")
    {
      var valid_email = jQuery("#email1").val().match(/.+@.+\.(.+){2,}/);
      if (valid_email == null)
      {
        jQuery("#erroremail").show();
        jQuery("#erroremail").fadeOut(3000);
      }
      else if (jQuery("#phoneno").val() != '') {
        var phone = jQuery("#phoneno").val();
        phone = phone.replace(/[^0-9]/g, '');
        if (phone.length != 10) {
          jQuery("#phoneerror").show();
          jQuery("#phoneerror").fadeOut(3000);
        }
        else {
          edituserdata();
        }
      }
      else
      {
        edituserdata();
      }
    }
    else if (jQuery("#phoneno").val() != '') {
      var phone = jQuery("#phoneno").val();
      phone = phone.replace(/[^0-9]/g, '');
      if (phone.length != 10) {
        jQuery("#phoneerror").show();
        jQuery("#phoneerror").fadeOut(3000);
      }
      else {
        edituserdata();
      }
    }
    else
    {
      edituserdata();
    }
  }
}
function edituserdata()
{
  var role = jQuery('#role').val();
  var roleid = jQuery('#tms_' + role).attr('rel');
  var dat = jQuery('#edituser').serialize();
  var data = dat + "&roleid=" + roleid;
  jQuery.ajax({
    type: "POST",
    url: "../account/route.php",
    data: data,
    cache: false,
    success: function (html) {
      window.location = 'tms.php?pg=view-users';
    }
  });
}
function tmsroles() {
  var role = jQuery('#role').val();
  if (role == 'transporter') {
    jQuery("#trans_display").show();
    jQuery("#facts_display").hide();
    jQuery("#depots_display").hide();
  } else if (role == 'factoryofficial') {
    jQuery("#trans_display").hide();
    jQuery("#facts_display").show();
    jQuery("#depots_display").hide();
  } else if (role == 'depotofficial') {
    jQuery("#trans_display").hide();
    jQuery("#facts_display").hide();
    jQuery("#depots_display").show();
  } else {
    jQuery("#trans_display").hide();
    jQuery("#facts_display").hide();
    jQuery("#depots_display").hide();
  }
}
// For TMS
function addvehicle() {
  var vehicleid = jQuery('#vehicletypeid').val();
  if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
    var selected_name = jQuery('#vehicletypeid option:selected').text();
    var div = document.createElement('div');
    var remove_image = document.createElement('img');
    remove_image.src = "../../images/boxdelete.png";
    remove_image.className = 'clickimage';
    remove_image.onclick = function () {
      removeVehicle(vehicleid);
    };
    div.className = 'recipientbox';
    div.id = 'to_vehicle_div_' + vehicleid;
    div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
    jQuery('#vehicle_list').append(div);
    jQuery(div).append(remove_image);
  }
  jQuery('#vehicletypeid').val(0);
}
function removeVehicle(checkpoint_no) {
  jQuery('#to_vehicle_div_' + checkpoint_no).remove();
}
function addallvehicle() {
  jQuery("#vehicletypeid option").each(function (index, element) {
    jQuery("#vehicletypeid").val(jQuery(element).val());
    addvehicle();
  });
}
function chksubmit()
{
  var vehiclearray = new Array();
  jQuery(".recipientbox").each(function () {
    vehiclearray.push(this.id);
  });
  if (vehiclearray == '') {
    jQuery("#vehiclearray").show();
    jQuery("#vehiclearray").fadeOut(4000);
  }
  else
  {
    var data = jQuery('#addtransporter').serialize();
    //alert(data);
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=add-transporter",
      data: data,
      cache: false,
      success: function (html)
      {
        if (jQuery.trim(html) != '') {
          window.location.href = 'tms.php?pg=view-transporter';
        } else {
          jQuery("#error").show();
          jQuery("#error").fadeOut(4000);
        }
      }
    });
  }
}
function chksubmitupdate()
{
  var vehiclearray = new Array();
  jQuery(".recipientbox").each(function () {
    vehiclearray.push(this.id);
  });
  if (vehiclearray == '') {
    jQuery("#vehiclearray").show();
    jQuery("#vehiclearray").fadeOut(4000);
  }
  else
  {
    var data = jQuery('#addorders').serialize();
    //alert(data);
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=edit-transporter",
      data: data,
      cache: false,
      success: function (html)
      {
        if (jQuery.trim(html) != '') {
          window.location.href = 'tms.php?pg=view-transporter';
        } else {
          jQuery("#error").show();
          jQuery("#error").fadeOut(4000);
        }
      }
    });
  }
}

function addProposedIndent() {
  var factoryid = jQuery('#factoryid').val();
  var depotid = jQuery('#depotid').val();
  var typeid = jQuery('#typeid').val();
  var transporterid = jQuery('#transporterid').val();
  var vehicletypeid = jQuery('#vehicletypeid').val();
  var daterequired = jQuery('#SDate').val();
  if (factoryid == '') {
    jQuery("#error1").show();
    jQuery("#error1").fadeOut(3000);
  } else if (depotid == '') {
    jQuery("#error2").show();
    jQuery("#error2").fadeOut(3000);
  } else if (typeid == '') {
    jQuery("#error3").show();
    jQuery("#error3").fadeOut(3000);
  } else if (transporterid == '') {
    jQuery("#error4").show();
    jQuery("#error4").fadeOut(3000);
  } else if (vehicletypeid == '') {
    jQuery("#error5").show();
    jQuery("#error5").fadeOut(3000);
  } else if (daterequired == '') {
    jQuery("#error6").show();
    jQuery("#error6").fadeOut(3000);
  } else {
    getVehicleOccupancy();
  }
}
function getVehicleOccupancy() {
  var skuids = new Array();
  var weights = new Array();
  var skucodes = new Array();
  jQuery('.skuid').each(function () {
    skuids.push(jQuery(this).val());
  })
  jQuery('.skucode').each(function () {
    skucodes.push(jQuery(this).val());
  })
  jQuery('.weight').each(function () {
    weights.push(jQuery(this).val());
  })
  var vehicleweight = jQuery('#vehicleweight').val();
  var vehiclevolume = jQuery('#vehiclevolume').val();
  var datastring = "skuids=" + skuids + "&skucodes=" + skucodes + "&weights=" + weights;
  jQuery.ajax({
    type: "POST",
    url: "action.php?action=Calculate-Proposed-Indent",
    data: datastring,
    async: false,
    cache: false,
    datatype: "json",
    success: function (response)
    {
      var data = JSON.parse(response);
      //data.TotalWeight
      var weightoccupy = ((data.TotalWeight / vehicleweight) * 100).toFixed(2);
      var volumeoccupy = ((data.TotalVolume / vehiclevolume) * 100).toFixed(2);
      jQuery('#volumeoccupancy').val(volumeoccupy);
      jQuery('#weightoccupancy').val(weightoccupy);
      jQuery('#vehicle_occupancy').show();
      jQuery("#saved").show();
      jQuery("#saved").fadeOut(3000);
      if (volumeoccupy <= 100 && weightoccupy <= 100) {
        jQuery("#btnSaveProposeIndent").prop('disabled', false);
      } else {
        jQuery("#btnSaveProposeIndent").prop('disabled', true);
      }
      //window.location.href = "enh_route.php?id=2";
    }
  });
}
function createProposedIndent() {
  var tbl = jQuery('#addedRows').children().length;
  if (tbl == '0') {
    jQuery("#error8").show();
    jQuery("#error8").fadeOut(3000);
  }
  var p01 = document.getElementById('addedRows');
  var str = p01.firstChild.id;
  var res = str.substring(10, 8);
  var skuids = new Array();
  var weights = new Array();
  var skucodes = new Array();
  var factoryid = jQuery('#factoryid').val();
  var depotid = jQuery('#depotid').val();
  var typeid = jQuery('#typeid').val();
  var transporterid = jQuery('#transporterid').val();
  var vehicletypeid = jQuery('#vehicletypeid').val();
  var daterequired = jQuery('#SDate').val();
  var remark = jQuery('#remark').val();
  var vehicleweight = jQuery('#vehicleweight').val();
  var vehiclevolume = jQuery('#vehiclevolume').val();
  var weightoccupancy = jQuery('#weightoccupancy').val();
  var volumeoccupancy = jQuery('#volumeoccupancy').val();
  var sku = jQuery("#sku_code" + res).val();
  var wt = jQuery("input[name=weight" + res + "]").val();
  jQuery('.skuid').each(function () {
    skuids.push(jQuery(this).val());
  })
  jQuery('.skucode').each(function () {
    skucodes.push(jQuery(this).val());
  })
  jQuery('.weight').each(function () {
    weights.push(jQuery(this).val());
  })
  if (factoryid == '') {
    jQuery("#error1").show();
    jQuery("#error1").fadeOut(3000);
  } else if (depotid == '') {
    jQuery("#error2").show();
    jQuery("#error2").fadeOut(3000);
  } else if (typeid == '') {
    jQuery("#error3").show();
    jQuery("#error3").fadeOut(3000);
  } else if (transporterid == '') {
    jQuery("#error4").show();
    jQuery("#error4").fadeOut(3000);
  } else if (vehicletypeid == '') {
    jQuery("#error5").show();
    jQuery("#error5").fadeOut(3000);
  } else if (daterequired == '') {
    jQuery("#error6").show();
    jQuery("#error6").fadeOut(3000);
  } else if (remark == '') {
    jQuery("#error7").show();
    jQuery("#error7").fadeOut(3000);
  } else if (sku == '') {
    jQuery("#error9").show();
    jQuery("#error9").fadeOut(3000);
  } else if (wt == '') {
    jQuery("#error10").show();
    jQuery("#error10").fadeOut(3000);
  }
  else {
    var data = jQuery('#addorders').serialize();
    //alert(data);
    var datastring = "skuids=" + skuids + "&skucodes=" + skucodes + "&weights=" + weights + "&data=" + data;
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=Calculate-Proposed-Indent",
      data: datastring,
      async: false,
      cache: false,
      datatype: "json",
      success: function (response)
      {
        var data = JSON.parse(response);
        //data.TotalWeight
        var weightoccupy = ((data.TotalWeight / vehicleweight) * 100).toFixed(2);
        var volumeoccupy = ((data.TotalVolume / vehiclevolume) * 100).toFixed(2);
        jQuery('#volumeoccupancy').val(volumeoccupy);
        jQuery('#weightoccupancy').val(weightoccupy);
        jQuery('#vehicle_occupancy').show();
        jQuery("#saved").show();
        jQuery("#saved").fadeOut(3000);
        if (volumeoccupy <= 100 && weightoccupy <= 100) {
          jQuery("#btnSaveProposeIndent").prop('disabled', false);
          jQuery.ajax({
            type: "POST",
            url: "action.php?action=Create-Proposed-Indent",
            data: datastring,
            cache: false,
            datatype: "json",
            success: function (response)
            {
              jQuery("#saved").show();
              jQuery("#saved").fadeOut(5000);
              window.location.href = "tms.php?pg=view-proposed-indent";
            }
          });
        } else {
          jQuery("#btnSaveProposeIndent").prop('disabled', true);
        }
        //window.location.href = "enh_route.php?id=2";
      }
    });
  }
}
function ValidateProposedIndent() {
  var vehicletype = jQuery("#vehicletype_list").val();
  var vehicletypeid = jQuery("#vehicletypeid").val();
  var vehicleno = jQuery("#vehicleno").val();
  var drivermobileno = jQuery("#drivermobileno").val();
  var status = jQuery("input[type='radio'][name='isaccepted']:checked").val();
  if (status === '1' && vehicletype == '' && vehicletypeid == '') {
    alert("Please Enter Actual Vehicle Code");
    return false;
  } else if (status === '1' && (vehicletype == '' || vehicletypeid == '')) {
    alert("Please Enter Actual Vehicle Code");
    return false;
  } else if (status === '1' && vehicleno == '') {
    alert("Please Enter Vehicle No");
    return false;
  } else if (status === '1' && (drivermobileno == '' || drivermobileno == 'Null')) {
    alert("Please Enter Driver Mobile No");
    return false;
  } else if (status === '1' && (drivermobileno != '' && drivermobileno.length < 10)) {
    alert("Please Enter Correct Mobile No");
    return false;
  } else if (status === '1' && (drivermobileno != '' && drivermobileno.length > 10)) {
    alert("Please Enter Correct Mobile No");
    return false;
  } else {
    jQuery("#btnSubmitAcceptReject").prop('disabled', true);
    jQuery("#addorders").submit();
  }
}
function getmultidrop() {
  var status = jQuery("input[type='radio'][name='multidrop']:checked").val();
  if (status == 1) {
    jQuery('#multidepot_name').show();
    jQuery('#factory_name').show();
    jQuery('#factoryspan').show();
    jQuery('#chkdisplay').show();
  } else if (status == 0) {
    jQuery('#multidepot_name').hide();
    jQuery('#factory_name').hide();
    jQuery('#factoryspan').hide();
    jQuery('#chkdisplay').hide();
  } else {
    jQuery('#multidepot_name').hide();
    jQuery('#factory_name').hide();
    jQuery('#factoryspan').hide();
    jQuery('#chkdisplay').hide();
  }
}
function addmultidropdepots(depotid, selected_name) {
  if (jQuery('#to_multidepot_div_0').val() == null) {
    if (depotid > -1 && jQuery('#to_multidepot_div_' + depotid).val() == null) {
      if (depotid == 0)
        removeallgroup();
      //var selected_name = jQuery('#multidepot option[value=' + depotid + ']').text();
      var div = document.createElement('div');
      var remove_image = document.createElement('img');
      remove_image.src = "../../images/boxdelete.png";
      remove_image.className = 'clickimage';
      remove_image.onclick = function () {
        removegroup(depotid);
      };
      div.className = 'recipientbox';
      div.id = 'to_multidepot_div_' + depotid;
      div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="g_list_element" name="to_multidepot_' + depotid + '" value="' + depotid + '"/>';
      jQuery('#multidepot_list').append(div);
      jQuery(div).append(remove_image);
    }
  }
  else {
    if (depotid > -1 && jQuery('#to_multidepot_div_' + depotid).val() == null) {
      removegroup(0);
      var div = document.createElement('div');
      var remove_image = document.createElement('img');
      remove_image.src = "../../images/boxdelete.png";
      remove_image.className = 'clickimage';
      remove_image.onclick = function () {
        removegroup(depotid);
      };
      div.className = 'recipientbox';
      div.id = 'to_multidepot_div_' + depotid;
      div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="g_list_element" name="to_multidepot_' + depotid + '" value="' + depotid + '"/>';
      jQuery('#multidepot_list').append(div);
      jQuery(div).append(remove_image);
    }
  }
  jQuery('#multidepot').selectedIndex = 0;
  jQuery('.checkpointlist').hide();
  jQuery('#multidepot_name').val('');
}
function addeddepots() {
  var depotid = 0;
  var selected_name = jQuery('#multidepot option[value=' + depotid + ']').text();
  if (depotid > -1 && jQuery('#to_multidepot_div_' + depotid) == null) {
    var div = document.createElement('div');
    var remove_image = document.createElement('img');
    remove_image.src = "../../images/boxdelete.png";
    remove_image.className = 'clickimage';
    remove_image.onclick = function () {
      removegroup(depotid);
    };
    div.className = 'recipientbox';
    div.id = 'to_multidepot_div_' + depotid;
    div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_multidepot_' + depotid + '" value="' + depotid + '"/>';
    jQuery('#multidepot_list').append(div);
    jQuery(div).append(remove_image);
  }
  jQuery('#multidepot').selectedIndex = 0;
}
function removegroup(depotid) {
  if (depotid > -1 && jQuery('#to_multidepot_div_' + depotid).val() != null) {
    jQuery('#to_multidepot_div_' + depotid).remove();
  }
}
function removeallgroup() {
  //var select_box = jQuery('#group');
  jQuery("#multidepot option").each(function (index, element) {
    var depotid = jQuery(this).val();
    removegroup(depotid);
  });
}
function mappedgroups() {
  jQuery('.mappedgroups').each(function () {
    var depotid = jQuery(this).attr('rel');
    var depotname = jQuery(this).val();
    ldgroup(depotname, depotid);
  });
}
function ldgroup(depotname, depotid)
{
  var selected_name = depotname;
  if (depotid > -1 && jQuery('#to_multidepot_div_' + depotid).val() == null)
  {
    var div = document.createElement('div');
    var remove_image = document.createElement('img');
    remove_image.src = "../../images/boxdelete.png";
    remove_image.className = 'clickimage';
    remove_image.onclick = function () {
      removegroup(depotid);
    };
    div.className = 'recipientbox';
    div.id = 'to_multidepot_div_' + depotid;
    div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_multidepot_' + depotid + '" value="' + depotid + '"/>';
    jQuery('#multidepot_list').append(div);
    jQuery(div).append(remove_image);
  }
}
function indentEdit() {
  var indentid = jQuery('#indentid').val();
  var loadstatus = jQuery('#loadstatus').val();
  var shipmentno = jQuery('#shipmentno').val();
  var remark = jQuery('#remark').val();
  var datastring = "indentid=" + indentid + "&loadstatus=" + loadstatus + "&shipmentno=" + shipmentno + "&remark=" + remark;
  if (loadstatus == 0) {
    alert("Please Select Loading Status");
  } else if (loadstatus == 1 && shipmentno == '') {
    alert("Please Enter Shippment No");
  } else if (loadstatus == 1 && shipmentno != '' && shipmentno.length < 10) {
    alert("Please Enter Correct Shippment No");
  } else if (loadstatus == 1 && shipmentno != '' && shipmentno.length > 10) {
    alert("Please Enter Correct Shippment No");
  } else if (loadstatus == 2 && remark == '') {
    alert("Please Enter Remark");
  } else if (loadstatus == 3 && remark == '') {
    alert("Please Enter Remark");
  } else {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=edit-indent",
      data: datastring,
      cache: false,
      datatype: "POST",
      success: function (response)
      {
        window.location.href = "tms.php?pg=view-indent";
      }
    });
  }
}
function parseDate(str) {
  var mdy = str.split('-')
  return new Date(mdy[2], mdy[1], mdy[0] - 1);
}
function daydiff(first, second) {
  return (second - first) / (1000 * 60 * 60 * 24)
}
function validateFactoryDelivery() {
  var todaysdate = jQuery('#todaysdate').val();
  var sdate = jQuery('#SDate').val();
  var weight = jQuery('#weight').val();
  var datediff = daydiff(parseDate(todaysdate), parseDate(sdate));
  //alert(d2);
  if (sdate == '') {
    alert("Please Select Date Required");
  } else if (datediff < 0) {
    alert("Please Select Correct Date");
  } else if (weight == '') {
    alert("Please Enter Net Weight");
  } else if (weight > 200) {
    alert("Net Weight Not More Than 200 Tons");
  } else {
    jQuery("#factory_delivery").submit();
  }
}
function validateIndentAlgo() {
  var todaysdate = jQuery('#todaysdate').val();
  var sdate = jQuery('#SDate').val();
  var datediff = daydiff(parseDate(todaysdate), parseDate(sdate));
  if (datediff < 0) {
    alert("Uh Oh!! You can't create indent for past days.");
    return false;
  } else {
    jQuery("#frmIndentAlgo").submit();
  }
}
function ValidateVendorEfficiency() {
  var startdate = jQuery('#SDate').val();
  var enddate = jQuery('#EDate').val();

  var datediff = daydiff(parseDate(startdate), parseDate(enddate));
  if (startdate != '' && enddate == '') {
    alert("Plaese select both dates");
    return false;
  } else if (startdate == '' && enddate != '') {
    alert("Plaese select both dates");
    return false;
  } else if (datediff < 0) {
    alert("Uh Oh!! Please select correct dates.");
    return false;
  } else {
    jQuery("#frmVendorEfficiency").submit();
  }
}
function addVehicelType() {
  jQuery("#addActivityformBuble").css({"visibility": "visible"});
}
function Export_Proposed_Indent(customerno, factoryid, transporterid) {
  window.open('savexls.php?action=PlacementTracker&customerno=' + customerno + "&factoryid=" + factoryid + "&transporterid=" + transporterid, '_blank');
}
mappedgroups();