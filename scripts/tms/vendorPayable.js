var lridstorrage = new Array();
var lridstorragebill = new Array();

jQuery(function () {
  /*Date Input Initialisation*/
  jQuery('#bill_date').datepicker({format: 'dd-mm-yyyy', autoclose: true});
  jQuery('#bill_received_date').datepicker({format: 'dd-mm-yyyy', autoclose: true});
  jQuery('#bill_processed_date').datepicker({format: 'dd-mm-yyyy', autoclose: true});
  jQuery('#bill_sent_date').datepicker({format: 'dd-mm-yyyy', autoclose: true});

  /*Close LR Details Modal*/
  jQuery('.bubbleclose').click(function () {
    jQuery("#ajaxBstatus1").html('');
    //jQuery('#addLrDetails').css({"visibility": "hidden"});
  });
  
  jQuery('.bubbleclose').click(function () {
    jQuery("#ajaxBstatus1").html('');
    //jQuery('#editLrDetails').css({"visibility": "hidden"});
  });

});


/*
 * 
 * Vendor Payable Validation Using Validate Js 
 */
var frmVendor = $('#frmVendorPayable').validate();

var validationRulesVendorPayable = {
  bill_type: {
    required: true,
    min: 1
  },
  invoice_location: {
    required: true
  },
  depot: {
    required: true
  },
  vendor: {
    required: true
  },
  bill_no: {
    required: true,
    maxlength: 30
  },
  bill_date: {
    required: true
  }
};
var validationMessagesVendorPayable = {
  bill_type: {
    required: "Please select bill type",
    min: "Please Select Correct Bill Type"
  },
  invoice_location: {
    required: "Please enter invoice location"
  },
  depot: {
    required: "Please enter depot / warehouse location"
  },
  vendor: {
    required: "Please enter vendor name"
  },
  bill_no: {
    required: "Please enter bill no",
    maxlength: "Bill No Length not more than 30 charecters"
  },
  bill_date: {
    required: "Please enter bill date",
    date: true
  }
};

function validatingVendorPayableForm() {
  frmVendor.resetForm();
  frmVendor.settings.rules = validationRulesVendorPayable;
  frmVendor.settings.messages = validationMessagesVendorPayable;
}

$().ready(function () {
  validatingVendorPayableForm();
  /* Validate Vendor Payable (Bills) Form on " addLrNo " Button - ADD LR */
  $("#addLrNo").click(function () {
    //showLrForm();
    if ($("#frmVendorPayable").valid()) {
      saveTransaction();
    } else {
      return;
    }
  });
  /* Validate Vendor Payable (Bills) Form on " save_bill_payable " Button - Save Draft */
  $("#save_draft").click(function () {
    validatingVendorPayableForm();
    if ($("#frmVendorPayable").valid()) {
      saveDraft();
    } else {
      return;
    }
  });
  
  $("#editLrNo").click(function () {
    showLr();
    if ($("#frmVendorPayable").valid()) {
      var isAddLr = 1;
      saveBill(isAddLr);     
    } else {
      return;
    }
  });
  /* Save Bill And Delete Selected Lr on Save Bill in Bill Payable Edit */
  $("#save_bill").click(function () {
    validatingVendorPayableForm();
    if ($("#frmVendorPayable").valid()) {
      var isAddLr = 0;      
      saveBill(isAddLr);      
    } else {
      return;
    }
  });
  
  /* Add Bill Payable Form Redirect To Bill Tracker */
  $("#cancel_bill_payable").click(function () {
    window.location.href = 'tms.php?pg=view-bills'; 
  });
  
  $("#cancel_bill_payable_draft").click(function () {
    window.location.href = 'tms.php?pg=view-bills'; 
  });
  
  /* Edit Bill Payable Form Redirect To Bill Tracker */
  $("#cancel_bill_payable_edit").click(function () {
    window.location.href = 'tms.php?pg=view-billtracker'; 
  });
  
  
  

});



function showLrForm() {
  jQuery("#ajaxBstatus1").html('');
  jQuery("#addLrDetails").modal('show');
  /* To Reset LR Details Form */
  jQuery('#frmLrDetails')[0].reset();
  jQuery("#lrid").val();
}

function showLr() {
  jQuery("#ajaxBstatus1").html('');
  jQuery("#editLrDetails").modal('show');
  /* To Reset LR Details Form */
  jQuery('#frmLrDetails')[0].reset();
  jQuery("#lrid").val();
}

function showSearchInput() {
  var srhstring = jQuery('input[name=lrsearch]:checked').val();
  if (srhstring == 'delivery') {
    jQuery("#delivery_no").prop('readonly', false);
    jQuery("#lr_no").prop('readonly', true);
    jQuery("#lr_no").val('');
  } else if (srhstring == 'lr') {
    jQuery("#delivery_no").prop('readonly', true);
    jQuery("#delivery_no").val('');
    jQuery("#lr_no").prop('readonly', false);
    
  }
}

function checkDeliveryDetails(id) {
  var datastring = '';
  var srhstring = jQuery('input[name=lrsearch]:checked').val();
  if (id == 'delivery_no' && srhstring == 'delivery') {
    var deliveryno = jQuery("#delivery_no").val();
    if (deliveryno == '') {
      alert('Please Enter Delivery No');
    } else {
      datastring = "deliveryno=" + deliveryno + "&lrno=0";
    }
  } else if (id == 'lr_no' && srhstring == 'lr') {
    var lrno = jQuery("#lr_no").val();
    if (lrno == '') {
      alert('Please Enter LR No');
    } else {
      datastring = "deliveryno=0&lrno=" + lrno;
    }
  } 
  
  if (datastring !== ''){
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=check-deliveryno",
      data: datastring,
      cache: false,
      datatype: "POST",
      success: function (data)
      {
        //alert(data);
        var info = JSON.parse(data);
        if(info.DeliveryDetails.length > 0){
        var delivery_no = info.DeliveryDetails[0].deliverynoparam;
        var lr_no = info.DeliveryDetails[0].lrnoparam;
        var shipment_no = info.DeliveryDetails[0].shipmentnoparam;
        var cost_doc_no = info.DeliveryDetails[0].costdocnoparam;
        var truck_type = info.DeliveryDetails[0].trucktypeparam;
        var route = info.DeliveryDetails[0].routeparam;
        var vehicleno = info.DeliveryDetails[0].vehicleparam;
        var indentid = info.DeliveryDetails[0].indentidparam;

        jQuery('#delivery_no').val(delivery_no);
        jQuery('#lr_no').val(lr_no);
        jQuery('#shipment_no').val(shipment_no);
        jQuery('#cost_document_no').val(cost_doc_no);
        jQuery('#truck_type').val(truck_type);
        jQuery('#route').val(route);
        jQuery('#vehicle_no').val(vehicleno);
        jQuery('#indentid').val(indentid);
      }
      }
    });
  }
}

function calculateDeliveryAmount() {  
  var cfa_cost = parseInt(jQuery('#cfa_cost').val());
  var shipment_freight_bill = parseInt(jQuery('#shipment_freight_bill').val());
  var loadingcharge = parseInt(jQuery('#loadingcharge').val());
  var unloading = parseInt(jQuery('#unloading').val());
  var loading_charges = parseInt(jQuery('#loading_charges').val());
  var unloading_charges = parseInt(jQuery('#unloading_charges').val());
  var other_charges = parseInt(jQuery('#other_charges').val());
  var multidrop_charges = parseInt(jQuery('#multidrop_charges').val());
  var toll_charges = parseInt(jQuery('#toll_charges').val());
  var permit_charges = parseInt(jQuery('#permit_charges').val());
  var charges_outword = parseInt(jQuery('#charges_outword').val());
  var gprs = parseInt(jQuery('#gprs').val());
  var noentry_charges = parseInt(jQuery('#noentry_charges').val());
  var auto_charges = parseInt(jQuery('#auto_charges').val());
  var lr_charge = parseInt(jQuery('#lr_charges').val());

  var tt_penalty = parseInt(jQuery('#tt_penalty').val());
  var any_deduction = parseInt(jQuery('#any_deduction').val());

  var additionamount = parseInt(cfa_cost + shipment_freight_bill + loadingcharge + unloading + loading_charges + unloading_charges + other_charges + multidrop_charges + toll_charges + permit_charges + charges_outword + gprs + noentry_charges + auto_charges + lr_charge);
  var deductionamount = parseInt(tt_penalty + any_deduction);
  var finaltotal = parseInt(additionamount - deductionamount);

  jQuery('#total_delivery_amount').val(finaltotal);

}

function saveTransaction() {
  var transactionid = jQuery('#transactionid').val();
  var data = jQuery('#frmVendorPayable').serialize();
  if (transactionid == 0) {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=save-vendor-payable-temp",
      data: data,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {
        jQuery('#transactionid').val(response);
        showLrForm();

      }
    });
  }
  else if (transactionid > 0) {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=update-vendor-payable-temp",
      data: data,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {
        jQuery('#transactionid').val(transactionid);
        showLrForm();
      }
    });
  }
  else {
    alert("Check Transaction");
  }
}

function saveBill(isAddLr) {
  var transactionid = jQuery('#transactionid').val();
  var data = jQuery('#frmVendorPayable').serialize();
  if (transactionid > 0) {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=update-vendor-payable",
      data: data,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {
        jQuery('#transactionid').val(transactionid);
        if(isAddLr === 0){
          if(lridstorragebill.length > 0){
          Final_deleteLr();
          }
          window.location = 'tms.php?pg=view-billtracker';
        }        
      }
    });
  }
  else {
    alert("Check Transaction");
  }
}


function calculateFinalBillAmount() {
  var billamt = 0;
  jQuery('.billamt').each(function () {
    billamt += parseInt(jQuery(this).html());
  });
  jQuery('#final_bill_amt').val(billamt);
}

function saveLrDetails() {
  var lrid = jQuery('#lrid').val();
  var delivery_no = jQuery('#delivery_no').val();
  var lr_no = jQuery('#lr_no').val();
  var total_amount = jQuery('#total_delivery_amount').val();
  var billid = jQuery('#transactionid').val();
  var data = jQuery('#frmLrDetails').serialize();
  var datastring = "billid=" + billid + "&delivery_no=" + delivery_no + "&data=" + data;
  if (delivery_no == '' && lr_no =='') {
    alert("Enter Delivery No / LR No");
  } else if (lrid > 0) {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=update-lrdetails-temp",
      data: datastring,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {

        jQuery("#delivery_no_" + lrid).html(delivery_no);
        jQuery("#total_amount_" + lrid).html(total_amount);

        calculateFinalBillAmount();
        jQuery('#frmLrDetails')[0].reset();
        jQuery("#ajaxBstatus1").html('');
        jQuery('#addLrDetails').modal('hide');
      }
    });
  } else {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=save-lrdetails-temp",
      data: datastring,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {

        jQuery('#lrDetails').show();
        //var htmlTxt = '<div style="float: left;width: 40%">456465465</div><div class="billamt" style="float: left;width: 40%">10</div><div style="clear: both;"></div>'; 
        var edit_image = document.createElement('img');
        edit_image.src = "../../images/edit_black.png";
        edit_image.className = 'editimage';
        edit_image.onclick = function () {
          editLrDetails(response);
        };

        var delete_image = document.createElement('img');
        delete_image.src = "../../images/boxdelete.png";
        delete_image.className = 'deleteimage';
        delete_image.onclick = function () {
          deleteLrDetails(response);
        };
        if(lr_no == ''){
          lr_no = "N/A";
        }
        if(delivery_no == ''){
          delivery_no = "N/A";
        }

        var htmlTxt = '<div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;" id="delivery_no_' + response + '">' + delivery_no + '</div>'
                +'<div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;" id="lr_no_' + response + '">' + lr_no + '</div>'
                + '<div style="float: left;width: 25%;border: 1px solid #ccc;padding: 5px;" class="billamt" id="total_amount_' + response + '">' + total_amount + '</div>'
                + '<div style="float: left;width: 10%;border: 1px solid #ccc;padding: 2px;" class="edit_id" id="edit_' + response + '"></div>'
                + '<div style="float: left;width: 10%;border: 1px solid #ccc;padding: 1px;" class="delete_id" id="delete_' + response + '"></div>'
                + '<div style="clear: both;"></div>';
        jQuery('#lrDetails').append(htmlTxt);
        jQuery("#edit_" + response).append(edit_image);
        jQuery("#delete_" + response).append(delete_image);

        calculateFinalBillAmount();
        jQuery('#frmLrDetails')[0].reset();
        jQuery("#ajaxBstatus1").html('');
        jQuery('#addLrDetails').modal('hide');
      }
    });
  }
}

function saveLr() {
  var lrid = jQuery('#lrid').val();
  var delivery_no = jQuery('#delivery_no').val();
  var lr_no = jQuery('#lr_no').val();
  var total_amount = jQuery('#total_delivery_amount').val();
  var billid = jQuery('#transactionid').val();
  var data = jQuery('#frmLrDetails').serialize();
  var datastring = "billid=" + billid + "&lrid=" + lrid +"&delivery_no=" + delivery_no + "&data=" + data;
  if (delivery_no == '' && lr_no == '') {
    alert("Enter Delivery No / LR No ");
  } else if (lrid > 0) {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=update-lrdetails",
      data: datastring,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {

        jQuery("#delivery_no_" + lrid).html(delivery_no);
        jQuery("#total_amount_" + lrid).html(total_amount);

        calculateFinalBillAmount();
        jQuery('#frmLrDetails')[0].reset();
        jQuery("#ajaxBstatus1").html('');
        jQuery('#editLrDetails').modal('show');
      }
    });
  } else {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=save-lrdetails",
      data: datastring,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {

        jQuery('#lrDetails').show();
        //var htmlTxt = '<div style="float: left;width: 40%">456465465</div><div class="billamt" style="float: left;width: 40%">10</div><div style="clear: both;"></div>'; 
        var edit_image = document.createElement('img');
        edit_image.src = "../../images/edit_black.png";
        edit_image.className = 'editimage';
        edit_image.onclick = function () {
          editLr(response);
        };

        var delete_image = document.createElement('img');
        delete_image.src = "../../images/boxdelete.png";
        delete_image.className = 'deleteimage';
        delete_image.onclick = function () {
          deleteLr(response);
        };
        if(lr_no == ''){
          lr_no = "N/A";
        }
        if(delivery_no == ''){
          delivery_no = "N/A";
        }

        var htmlTxt = '<div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;" id="delivery_no_' + response + '">' + delivery_no + '</div>'
                +'<div style="float: left;width: 20%;border: 1px solid #ccc;padding: 5px;" id="lr_no_' + response + '">' + lr_no + '</div>'
                + '<div style="float: left;width: 25%;border: 1px solid #ccc;padding: 5px;" class="billamt" id="total_amount_' + response + '">' + total_amount + '</div>'
                + '<div style="float: left;width: 10%;border: 1px solid #ccc;padding: 2px;" class="edit_id" id="edit_' + response + '"></div>'
                + '<div style="float: left;width: 10%;border: 1px solid #ccc;padding: 1px;" class="delete_id" id="delete_' + response + '"></div>'
                + '<div style="clear: both;"></div>';
        jQuery('#lrDetails').append(htmlTxt);
        jQuery("#edit_" + response).append(edit_image);
        jQuery("#delete_" + response).append(delete_image);

        calculateFinalBillAmount();
        jQuery('#frmLrDetails')[0].reset();
        jQuery("#ajaxBstatus1").html('');
        jQuery('#editLrDetails').modal('hide');
      }
    });
  }
}

function editLrDetails(lrid) {
  var data = "lrid=" + lrid;
  if (lrid > 0) {
    jQuery("#ajaxBstatus1").html('');
    jQuery("#addLrDetails").modal('show');
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=get-lrdetails-temp",
      data: data,
      async: true,
      cache: false,
      datatype: "json",
      success: function (data)
      {
        //alert(data);
        var info = JSON.parse(data);
        var lrid = info.LrDetails[0].lrid;
        var delivery_no = info.LrDetails[0].delivery_no;
        var lr_no = info.LrDetails[0].lr_no;
        var shipment_no = info.LrDetails[0].shipment_no;
        var cost_document_no = info.LrDetails[0].cost_document_no;
        var truck_type = info.LrDetails[0].truck_type;
        var route = info.LrDetails[0].route;
        var vehicle_no = info.LrDetails[0].vehicle_no;
        var vehicle_type = info.LrDetails[0].vehicle_type;
        var movement_type = info.LrDetails[0].movement_type;
        var cfa_cost = info.LrDetails[0].cfa_cost;
        var shipment_freight_bill = info.LrDetails[0].shipment_freight_bill;
        var loadingcharge = info.LrDetails[0].loading;
        var unloading = info.LrDetails[0].unloading;
        var loading_charges = info.LrDetails[0].loading_charges;
        var unloading_charges = info.LrDetails[0].unloading_charges;
        var other_charges = info.LrDetails[0].other_charges;
        var multidrop_charges = info.LrDetails[0].multidrop_charges;
        var toll_charges = info.LrDetails[0].toll_charges;
        var permit_charges = info.LrDetails[0].permit_charges;
        var charges_outword = info.LrDetails[0].charges_outword;
        var gprs = info.LrDetails[0].gprs;
        var noentry_charges = info.LrDetails[0].noentry_charges;
        var auto_charges = info.LrDetails[0].auto_charges;
        var lr_charges = info.LrDetails[0].lr_charges;
        var tt_penalty = info.LrDetails[0].tt_penalty;
        var any_deduction = info.LrDetails[0].any_deduction;
        var total_delivery_amount = info.LrDetails[0].total_delivery_amount;

        jQuery('#delivery_no').val(delivery_no);
        jQuery('#lr_no').val(lr_no);
        jQuery('#shipment_no').val(shipment_no);
        jQuery('#cost_document_no').val(cost_document_no);
        jQuery('#truck_type').val(truck_type);
        jQuery('#route').val(route);
        jQuery('#vehicle_no').val(vehicle_no);
        jQuery('#vehicle_type').val(vehicle_type);
        jQuery('#movement_type').val(movement_type);
        jQuery('#cfa_cost').val(cfa_cost);
        jQuery('#shipment_freight_bill').val(shipment_freight_bill);
        jQuery('#loadingcharge').val(loadingcharge);
        jQuery('#unloading').val(unloading);
        jQuery('#loading_charges').val(loading_charges);
        jQuery('#unloading_charges').val(unloading_charges);
        jQuery('#other_charges').val(other_charges);
        jQuery('#multidrop_charges').val(multidrop_charges);
        jQuery('#toll_charges').val(toll_charges);
        jQuery('#permit_charges').val(permit_charges);
        jQuery('#charges_outword').val(charges_outword);
        jQuery('#gprs').val(gprs);
        jQuery('#noentry_charges').val(noentry_charges);
        jQuery('#auto_charges').val(auto_charges);
        jQuery('#lr_charges').val(lr_charges);
        jQuery('#tt_penalty').val(tt_penalty);
        jQuery('#any_deduction').val(any_deduction);
        jQuery('#total_delivery_amount').val(total_delivery_amount);
        jQuery('#lrid').val(lrid);
        
        calculateFinalBillAmount();
      }
    });
  }
}

function editLr(lrid) {
  var data = "lrid=" + lrid;
  if (lrid > 0) {
    jQuery("#ajaxBstatus1").html('');
    jQuery("#editLrDetails").modal('show');
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=get-lrdetails",
      data: data,
      async: true,
      cache: false,
      datatype: "json",
      success: function (data)
      {
        //alert(data);
        var info = JSON.parse(data);
        var lrid = info.LrDetails[0].lrid;
        var delivery_no = info.LrDetails[0].delivery_no;
        var lr_no = info.LrDetails[0].lr_no;
        var shipment_no = info.LrDetails[0].shipment_no;
        var cost_document_no = info.LrDetails[0].cost_document_no;
        var truck_type = info.LrDetails[0].truck_type;
        var route = info.LrDetails[0].route;
        var vehicle_no = info.LrDetails[0].vehicle_no;
        var vehicle_type = info.LrDetails[0].vehicle_type;
        var movement_type = info.LrDetails[0].movement_type;
        var cfa_cost = info.LrDetails[0].cfa_cost;
        var shipment_freight_bill = info.LrDetails[0].shipment_freight_bill;
        var loadingcharge = info.LrDetails[0].loading;
        var unloading = info.LrDetails[0].unloading;
        var loading_charges = info.LrDetails[0].loading_charges;
        var unloading_charges = info.LrDetails[0].unloading_charges;
        var other_charges = info.LrDetails[0].other_charges;
        var multidrop_charges = info.LrDetails[0].multidrop_charges;
        var toll_charges = info.LrDetails[0].toll_charges;
        var permit_charges = info.LrDetails[0].permit_charges;
        var charges_outword = info.LrDetails[0].charges_outword;
        var gprs = info.LrDetails[0].gprs;
        var noentry_charges = info.LrDetails[0].noentry_charges;
        var auto_charges = info.LrDetails[0].auto_charges;
        var lr_charges = info.LrDetails[0].lr_charges;
        var tt_penalty = info.LrDetails[0].tt_penalty;
        var any_deduction = info.LrDetails[0].any_deduction;
        var total_delivery_amount = info.LrDetails[0].total_delivery_amount;

        jQuery('#delivery_no').val(delivery_no);
        jQuery('#lr_no').val(lr_no);
        jQuery('#shipment_no').val(shipment_no);
        jQuery('#cost_document_no').val(cost_document_no);
        jQuery('#truck_type').val(truck_type);
        jQuery('#route').val(route);
        jQuery('#vehicle_no').val(vehicle_no);
        jQuery('#vehicle_type').val(vehicle_type);
        jQuery('#movement_type').val(movement_type);
        jQuery('#cfa_cost').val(cfa_cost);
        jQuery('#shipment_freight_bill').val(shipment_freight_bill);
        jQuery('#loadingcharge').val(loadingcharge);
        jQuery('#unloading').val(unloading);
        jQuery('#loading_charges').val(loading_charges);
        jQuery('#unloading_charges').val(unloading_charges);
        jQuery('#other_charges').val(other_charges);
        jQuery('#multidrop_charges').val(multidrop_charges);
        jQuery('#toll_charges').val(toll_charges);
        jQuery('#permit_charges').val(permit_charges);
        jQuery('#charges_outword').val(charges_outword);
        jQuery('#gprs').val(gprs);
        jQuery('#noentry_charges').val(noentry_charges);
        jQuery('#auto_charges').val(auto_charges);
        jQuery('#lr_charges').val(lr_charges);
        jQuery('#tt_penalty').val(tt_penalty);
        jQuery('#any_deduction').val(any_deduction);
        jQuery('#total_delivery_amount').val(total_delivery_amount);
        jQuery('#lrid').val(lrid);
      }
    });
  }
}

function deleteLrDetails(lrid) {
  var data = "lrid=" + lrid;
  lridstorrage.push(lrid);
  jQuery('#delivery_no_' + lrid).remove();
  jQuery('#lr_no_' + lrid).remove();
  jQuery('#total_amount_' + lrid).remove();
  jQuery('#edit_' + lrid).remove();
  jQuery('#delete_' + lrid).remove();
  calculateFinalBillAmount();
}

function Final_deleteLrDetails() {
  
  var datastring = "lrids=" + lridstorrage ;
  jQuery.ajax({
    type: "POST",
    url: "action.php?action=delete-lrdetails-temp",
    data: datastring,
    async: true,
    cache: false,
    datatype: "json",
    success: function (response)
    {
      
    }
  });
  calculateFinalBillAmount();
}



function deleteLr(lrid) {
  var data = "lrid=" + lrid;
  lridstorragebill.push(lrid);
  jQuery('#delivery_no_' + lrid).remove();
  jQuery('#lr_no_' + lrid).remove();
  jQuery('#total_amount_' + lrid).remove();
  jQuery('#edit_' + lrid).remove();
  jQuery('#delete_' + lrid).remove();
  calculateFinalBillAmount();
  
}

function Final_deleteLr() {
  
  var datastring = "lrids=" + lridstorragebill ;
  jQuery.ajax({
    type: "POST",
    url: "action.php?action=delete-lrdetails",
    data: datastring,
    async: true,
    cache: false,
    datatype: "json",
    success: function (response)
    {
      
    }
  });
  calculateFinalBillAmount();
}

function checkBillStatus() {
  var bill_received_date = jQuery('#bill_received_date').val();
  var bill_processed_date = jQuery('#bill_processed_date').val();
  var bill_sent_date = jQuery('#bill_sent_date').val();
  var bill_date = jQuery('#bill_date').val();
  var data = "bill_received_date=" + bill_received_date + "&bill_processed_date=" + bill_processed_date + "&bill_sent_date=" + bill_sent_date + "&bill_date=" + bill_date;
  jQuery.ajax({
    type: "POST",
    url: "action.php?action=check-bill-status",
    data: data,
    async: true,
    cache: false,
    datatype: "json",
    success: function (data)
    {
      var info = JSON.parse(data);
      var Due_Day = info.BillStatus.Due_Day;
      var Billing_Status = info.BillStatus.Billing_Status;
      var Due_Status = info.BillStatus.Due_Status;
      var Days_For_Receiving_Bills = info.BillStatus.Days_For_Receiving_Bills;
      var Processed_Days = info.BillStatus.Processed_Days;
      var Custody = info.BillStatus.Custody;
      var Total_Custody = info.BillStatus.Total_Custody;
      var Payment_Done_Days = info.BillStatus.Payment_Done_Days;
      var Month_Sent = info.BillStatus.Month_Sent;
      var Year_Sent = info.BillStatus.Year_Sent;
      var Payment_Status = info.BillStatus.Payment_Status;

      jQuery('#due_days').val(Due_Day);
      jQuery('#billing_status').val(Billing_Status);
      jQuery('#due_status').val(Due_Status);
      jQuery('#day_for_receiving_bill').val(Days_For_Receiving_Bills);
      jQuery('#process_day').val(Processed_Days);
      jQuery('#custody').val(Custody);
      jQuery('#total_custody').val(Total_Custody);
      jQuery('#payment_done').val(Payment_Done_Days);
      jQuery('#month_sent').val(Month_Sent);
      jQuery('#year_sent').val(Year_Sent);
      jQuery('#payment_bucket').val(Payment_Status);
    }
  });
}

function saveDraft() {
  var transactionid = jQuery('#transactionid').val();
  var data = jQuery('#frmVendorPayable').serialize();
  if (transactionid > 0) {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=update-vendor-payable-temp",
      data: data,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {
        jQuery('#transactionid').val(transactionid);
        jQuery("#save_bill_payable").prop('disabled', false);
        jQuery("#save_draft").prop('disabled', true);
        //jQuery("#cancel_bill_payable").prop('disabled', true);
        //jQuery("#save_bill_payable").prop('disabled', false);
        if (lridstorrage.length > 0) {
        Final_deleteLrDetails();
        }
      }
    });
  } else {
    alert("Please Add LR Details");
  }
}

function saveMainTransaction() {
  saveDraft();
  var transactionid = jQuery('#transactionid').val();
  var data = jQuery('#frmVendorPayable').serialize();

  if (transactionid > 0) {
    jQuery.ajax({
      type: "POST",
      url: "action.php?action=insert-transaction-main",
      data: data,
      async: true,
      cache: false,
      datatype: "json",
      success: function (response)
      {
        window.location.href = 'tms.php?pg=view-billtracker';        
      }
    });
  } else {
    alert("Check Transaction");
  }
}