jQuery(function () {
  /** 
   *  TMS search as you type calls
   */
  $("#zonename").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=zonelist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#locationname").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=locationlist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#depot_name").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=depotlist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#vehicletype_list").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=vehicletypelist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#transporter_name").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=transporterlist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#transporter_byZone").keyup(function () {
    var factoryid = $("#factoryid").val();
    var depotid = $("#depotid").val();
    var srh = $("#transporter_byZone").val();
    //alert(typeid);
    jQuery.ajax({
      type: "POST",
      url: "autocomplete.php",
      data: "dummydata=transporterlist_byZone&q=" + srh + "&factoryid=" + factoryid + "&depotid=" + depotid,
      success: function (json) {
        if (json != '')
        {
          jQuery('#transporterdisplay').show();
          jQuery("#transporterdisplay").html(json);
        } else {
          jQuery('#transporterdisplay').show();
          jQuery("#transporterdisplay").html(json);
        }
      }
    });
  });
  $("#factory_name").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=factorylist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#route_name").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=routelist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#type_name").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=typelist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#sku_code").autoSuggest({
    ajaxFilePath: "autocomplete.php",
    ajaxParams: "dummydata=skulist",
    autoFill: false,
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
  });
  $("#vehicletypetrans_list").keyup(function () {
    var transporterid = $("#transporterid").val();
    var typeid = $("#typeid").val();
    var srh = $("#vehicletypetrans_list").val();
    //alert(typeid);
    jQuery.ajax({
      type: "POST",
      url: "autocomplete.php",
      data: "dummydata=transtype&q=" + srh + "&transporterid=" + transporterid + "&typeid=" + typeid,
      success: function (json) {
        if (json != '')
        {
          jQuery('#chkdisplay').show();
          jQuery("#chkdisplay").html(json);
        } else {
          jQuery('#chkdisplay').show();
          jQuery("#chkdisplay").html(json);
        }
      }
    });
  });
  $("#multidepot_name").keyup(function () {
    var transporterid = $("#transporterid").val();
    var srh = $("#multidepot_name").val();
    //alert(typeid);
    jQuery.ajax({
      type: "POST",
      url: "autocomplete.php",
      data: "dummydata=depots&q=" + srh,
      success: function (json) {
        if (json != '')
        {
          jQuery('#chkdisplay').show();
          jQuery("#chkdisplay").html(json);
        } else {
          jQuery('#chkdisplay').show();
          jQuery("#chkdisplay").html(json);
        }
      }
    });
  });
});
function chkkey(listid) {
  var typeid = jQuery("#typeid").val();
  var srh = jQuery("#sku_code" + listid).val();
  jQuery.ajax({
    type: "POST",
    url: "autocomplete.php",
    data: "dummydata=sku&q=" + srh + "&cnt=" + listid + "&typeid=" + typeid,
    success: function (json) {
      if (listid > 0)
      {
        jQuery('#chkdisplay' + listid).show();
        jQuery("#chkdisplay" + listid).html(json);
      } else {
        jQuery('#chkdisplay').show();
        jQuery("#chkdisplay").html(json);
      }
    }
  });
}
function fill(Value, strparam)
{
  jQuery('#zonename').val(strparam);
  jQuery('#zoneid').val(Value);
  jQuery('.ajax_response_3').hide();
}
function fill_location(Value, strparam)
{
  jQuery('#locationname').val(strparam);
  jQuery('#locationid').val(Value);
  jQuery('.ajax_response_4').hide();
}
function fill_vehicletype(Value, strparam)
{
  jQuery('#vehicletype_list').val(strparam);
  jQuery('#vehicletypeid').val(Value);
  jQuery('#display').hide();
}
function fill_transporter(Value, strparam)
{
  jQuery('#transporter_name').val(strparam);
  jQuery('#transporterid').val(Value);
  jQuery('.ajax_response_4').hide();
}
function fill_transporter_byZone(Value, strparam)
{
  jQuery('#transporter_byZone').val(strparam);
  jQuery('#transporterid').val(Value);
  jQuery('#transporterdisplay').hide();
}
function fill_factory(Value, strparam)
{
  jQuery('#factory_name').val(strparam);
  jQuery('#factoryid').val(Value);
  jQuery('.ajax_response_5').hide();
}
function fill_depot(Value, strparam)
{
  jQuery('#depot_name').val(strparam);
  jQuery('#depotid').val(Value);
  jQuery('.ajax_response_6').hide();
}
function fill_route(Value, strparam)
{
  jQuery('#route_name').val(strparam);
  jQuery('#routemasterid').val(Value);
  jQuery('.ajax_response_7').hide();
}
function fill_type(Value, strparam)
{
  jQuery('#type_name').val(strparam);
  jQuery('#typeid').val(Value);
  jQuery('.ajax_response_8').hide();
}
function fill_sku(Value, strparam, strdescription)
{
  jQuery('#sku_code').val(strparam);
  jQuery('#sku_description').val(strdescription);
  jQuery('#skuid').val(Value);
  jQuery('.ajax_response_9').hide();
}

function fillsku(Value, codeparam, descriptionparam, count)
{
  jQuery('#sku_code' + count).val(codeparam);
  jQuery('#sku_description' + count).val(descriptionparam);
  jQuery('#skuid' + count).val(Value);
  jQuery('#chkdisplay' + count).hide();
}
function fillvehicletype(vehicledescription, vehiclecode, vehicletypeid, volume, weight)
{
  //alert("test");
  jQuery('#vehicletypetrans_list').val(vehicledescription + '-' + vehiclecode);
  jQuery('#vehicletypeid').val(vehicletypeid);
  jQuery('#vehiclevolume').val(volume);
  jQuery('#vehicleweight').val(weight);
  jQuery('#chkdisplay').hide();
  jQuery('#vehicle_stat').show();
}
