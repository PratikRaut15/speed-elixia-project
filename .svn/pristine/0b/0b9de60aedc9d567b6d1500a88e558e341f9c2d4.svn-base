var viewtransactionDataTable;
var tableId = 'viewtransaction';
jQuery(document).ready(function () {
    $("#closedsuccess_msg").hide();
    $('#btnApprove').attr('disabled', 'disabled');
// Handler for .ready() called.
    jQuery('.file-inputs').bootstrapFileInput();
    jQuery('#acc_Date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#chequedate').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#val_from_Date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#val_to_Date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#getbattery_edit #batt_vehicle_in_date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#getbattery_edit #batt_vehicle_out_date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#getbattery_edit #batt_invoice_date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#vehicle_in_date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#vehicle_out_date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#invoice_date').datepicker({format: "dd-mm-yyyy", autoclose: true});

    var transidparam = GetParameterValues('id');
    if (transidparam == 2) {
        // Handle click on "check_all" control
        jQuery("#check_all").click(function (e) {
            var isCheckAllCtrlChecked = this.checked;
            if (isCheckAllCtrlChecked) {
                viewtransactionDataTable.rows({page: 'current'}).select();
            }
            else {
                viewtransactionDataTable.rows({page: 'current'}).deselect();
            }
            e.stopPropagation();
        });

        // Handle row selection event
        $('#' + tableId + '').on('select.dt deselect.dt', function (e, api, items) {
            if (e.type === 'select') {
                $('tr.selected input[type="checkbox"]', api.table().container()).attr('checked', true);
            }
            else {
                $('tr:not(.selected) input[type="checkbox"]', api.table().container()).attr('checked', false);
            }
            // Update state of "Select all" control
            updateDataTableCheckAllCtrl(viewtransactionDataTable);
        });

        // Handle table draw event so that check all box is reset to not checked
        $('#' + tableId + '').on('draw.dt', function () {
            if (viewtransactionDataTable) {
                // Update state of "Select all" control
                updateDataTableCheckAllCtrl(viewtransactionDataTable);
            }
        });

        $("#btnApprove").click(function () {
            var ids = [];
            var str_array = [];
            var rowcollection = viewtransactionDataTable.$(".call-checkbox:checked", {"page": "all"});
            for (var i = 0; i < rowcollection.length; i++) {
                ids.push($(rowcollection[i]).val());
            }
            //console.log(ids);

            jQuery("#check_approval").val(ids);
            var count_id = ids.length;
            var invoiceamtarr = [];
            var totalinvoice = 0;
            for (var i = 0; i < ids.length; i++) {
                var invoiceamtarr = ids[i].split('-');
                totalinvoice += parseFloat(invoiceamtarr[3]);
            }
            var htmldata = "You are About to close " + count_id + " Transactions<br>Total invoice amount :" + totalinvoice;
            jQuery("#NoOfApproval").html(htmldata);
        });
        jQuery('#pageloaddiv').show();
        jQuery.ajax({
            url: 'route_ajax.php',
            type: 'POST',
            data: "work=viewtransaction",
            success: function (result) {
                var sortColumn = 0;
                var transactionsList = jQuery.parseJSON(result);
                var tableCols = [
                    //{"mData": "srno"} To Do Serial No.
                    {"mData": "chk_box"}
                    , {"mData": "trans"}
                    , {"mData": "meter_reading"}
                    , {"mData": "vehicleno"}
                    , {"mData": "category"}
                    , {"mData": "group"}
                    , {"mData": "dname"}
                    , {"mData": "quote_amount"}
                    , {"mData": "invno"}
                    , {"mData": "invoice_amount"}
                    , {"mData": "invoice_date"}
                    , {"mData": "vehicle_out_date"}
                    , {"mData": "statusname"}
                    , {"mData": "role"}
                    , {"mData": "sender"}
                    , {"mData": "submit_date"}
                    , {"mData": "timestamp"}
                    , {"mData": "edit", "width": "10%"}

                ];
                viewtransactionDataTable = CreateDataTable(transactionsList, tableId, tableCols, 10);
            },
            complete: function () {
                jQuery('#pageloaddiv').hide();
            }
        });
    }

});


function updateDataTableCheckAllCtrl(table) {
    var $table = table.table().container();
    var $chkbox_all = $('tbody input[type="checkbox"]', $table);
    var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[type="checkbox"]', $table).get(0);
    if (chkbox_select_all != undefined)
    {
        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }
            //disable approval button if no checkbox is checked
            $('#btnApprove').attr('disabled', 'disabled');
        }
        else if ($chkbox_checked.length === $chkbox_all.length) {
            // If all of the checkboxes are checked
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }
            //enable approval button on checkbox check
            $('#btnApprove').removeAttr('disabled');
        }
        else {
            // If some of the checkboxes are checked
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
            //enable approval button on checkbox check
            $('#btnApprove').removeAttr('disabled');
        }
    }
}

function push_closed_transaction(action) {
    var approval_array = jQuery("#check_approval").val();
    var notes = jQuery("#note").val();
    var ofasnumber = jQuery("#ofasnumber1").val();
    var chequeno = jQuery("#chequeno").val();
    var chequeamt = jQuery("#chequeamt").val();
    var chequedate = jQuery("#chequedate").val();
    var tdsamt = jQuery("#tdsamt").val();
    if (notes == '') {
        jQuery("#approvalerror_note").show();
        jQuery("#approvalerror_note").fadeOut(3000);
    } else if (approval_array.length == 0) {
        jQuery("#approvalerror_checkbox").show();
        jQuery("#approvalerror_checkbox").fadeOut(3000);
    } else {
        var dataString = "work=multiple_approval&action=" + action + "&approvaldata=" + approval_array + "&note=" + notes + "&ofasnumber=" + ofasnumber + "&chequeno=" + chequeno + "&chequeamt=" + chequeamt + "&chequedate=" + chequedate + "&tdsamt=" + tdsamt;
        jQuery.ajax({
            url: 'route_ajax.php',
            type: 'POST',
            data: dataString,
            success: function () {
                jQuery("#closedsuccess_msg").show();
                jQuery("#closedsuccess_msg").fadeOut(2000);
                window.location.href = "transaction.php?id=2";
            }
        });
    }
}

function getFilteredTransaction() {
    var data = jQuery("#transaction_form").serialize();
    var dataString = "work=viewtransaction&filter=1&" + data;
    jQuery('#pageloaddiv').show();
    jQuery.ajax({
        url: 'route_ajax.php',
        type: 'POST',
        data: dataString,
        success: function (result) {
            var transactionsList = jQuery.parseJSON(result);
            var tableId = 'viewtransaction';
            var tableCols = [
                {"mData": "chk_box"}
                , {"mData": "trans"}
                , {"mData": "meter_reading"}
                , {"mData": "vehicleno"}
                , {"mData": "category"}
                , {"mData": "group"}
                , {"mData": "dname"}
                , {"mData": "quote_amount"}
                , {"mData": "invno"}
                , {"mData": "invoice_amount"}
                , {"mData": "invoice_date"}
                , {"mData": "vehicle_out_date"}
                , {"mData": "statusname"}
                , {"mData": "role"}
                , {"mData": "sender"}
                , {"mData": "submit_date"}
                , {"mData": "timestamp"}
                , {"mData": "edit", "width": "10%"}
            ];
            viewtransactionDataTable = CreateDataTable(transactionsList, tableId, tableCols, 10);
        },
        complete: function () {
            jQuery('#pageloaddiv').hide();
        }
    });
}
var files_invoice_name;
var files;
// Add events
jQuery('#getbattery_edit #batt_file_for_invoice').on('change', prepareUploadInvoice);
jQuery('#file_for_quote').on('change', prepareUpload);
jQuery('#file_for_invoice').on('change', prepareUpload);
function addPart() {
    var pname = "";
    var pname = jQuery('#partnameP').val();
    var pamount = "";
    var pamount = jQuery('#partamountP').val();
    var pdiscount = "0";
    var pdiscount = jQuery('#partdiscountP').val();

    if (pname == "")
    {
        jQuery('#partStatus').html("Please enter name");
        jQuery("#partStatus").show();
        jQuery("#partStatus").fadeOut(3000);
    } else if (pamount == "") {
        jQuery('#partStatus').html("Please enter unit amount");
        jQuery("#partStatus").show();
        jQuery("#partStatus").fadeOut(3000);
    } else if (pamount <= 0) {
        jQuery("#partStatus").html("Unit Amount should be greater than zero");
        jQuery("#partStatus").show();
        jQuery("#partStatus").fadeOut(3000);
        return false;
    } else if (pdiscount == "") {
        jQuery("#partStatus").html("Please enter discount amount ");
        jQuery("#partStatus").show();
        jQuery("#partStatus").fadeOut(3000);
        return false;
    } else if (parseFloat(pdiscount) > parseFloat(pamount)) {
        jQuery("#partStatus").html("Discount amount should not be greater than Unit amount");
        jQuery("#partStatus").show();
        jQuery("#partStatus").fadeOut(3000);
        return false;
    } else {
        var dataurl = "action=addpart&partname=" + escape(pname) + "&partamount=" + pamount + "&partdiscount=" + pdiscount;
        jQuery.ajax({
            url: 'route_ajax.php',
            type: 'POST',
            data: dataurl,
            success: function (res) {
                var data = jQuery.parseJSON(res);
                if (data[0] == 1) {

                    jQuery('.partsSelect').each(function () {
                        jQuery(this).prepend("<option value='" + data[2] + "'>" + pname + "</option>");
                    });
                }
                jQuery('#partStatus').html(data[1]);
                jQuery('#partnameP').val('');
                jQuery('#partamountP').val('');
                jQuery('#partdiscountP').val('');
            }
        });
    }
}

function addTask() {
    var tname = "";
    var tname = jQuery('#tasknameP').val();
    var tamount = "";
    var tamount = jQuery('#taskamountP').val();
    var tdiscount = "";
    var tdiscount = jQuery('#taskdiscountP').val();
    if (tname == "")
    {
        jQuery('#taskStatus').html("Please enter name");
        jQuery("#taskStatus").show();
        jQuery("#taskStatus").fadeOut(3000);
    } else if (tamount == "") {
        jQuery('#taskStatus').html("Please enter unit amount");
        jQuery("#taskStatus").show();
        jQuery("#taskStatus").fadeOut(3000);
    } else if (tamount <= 0) {
        jQuery("#taskStatus").html("Unit Amount should be greater than zero");
        jQuery("#taskStatus").show();
        jQuery("#taskStatus").fadeOut(3000);
        return false;
    } else if (tdiscount == "") {
        jQuery("#taskStatus").html("Please enter discount amount ");
        jQuery("#taskStatus").show();
        jQuery("#taskStatus").fadeOut(3000);
        return false;
    } else if (parseFloat(tdiscount) > parseFloat(tamount)) {
        jQuery("#taskStatus").html("Discount amount should not be greater than Unit amount");
        jQuery("#taskStatus").show();
        jQuery("#taskStatus").fadeOut(3000);
        return false;
    } else {
        var dataurl = "action=addtask&taskname=" + escape(tname) + "&taskamount=" + tamount + "&taskdiscount=" + tdiscount;
        jQuery.ajax({
            url: 'route_ajax.php',
            type: 'POST',
            data: dataurl,
            success: function (res) {
                var data = jQuery.parseJSON(res);
                if (data[0] == 1) {
                    jQuery('.tasksSelect').each(function () {
                        jQuery(this).prepend("<option value='" + data[2] + "'>" + tname + "</option>");
                    });
                }
                jQuery('#taskStatus').html(data[1]);
                jQuery('#tasknameP').val('');
                jQuery('#taskamountP').val('');
                jQuery('#taskdiscountP').val('');
            }
        });
    }
}

// Grab the files and set them to our variable
function prepareUploadInvoice(event)
{
    files = null;
    files = event.target.files;
    uploadFilesInvoice(event);
}

function prepareUpload(event)
{
    files = event.target.files;
}

function uploadFilesInvoice(event)
{
    var category;
    if (jQuery('#getbattery_edit #batt_file_for_invoice').val() != '' || jQuery('#gettyre_edit #tyre_file_for_invoice').val() != '' || jQuery('#getrepair_edit #repair_file_for_invoice').val() != '') {
        var edit_vehicle_id = jQuery('#edit_vehicle_id').val();
        var maintenanceid = jQuery('#maintenance_edit_id').val();
        var data = new FormData();
        jQuery.each(files, function (key, value)
        {
            data.append(key, value);
        });
        files = null;
        category = jQuery('#getbattery_edit #category_id').val();
        jQuery.ajax({
            url: 'upload.php?vehicleid=' + edit_vehicle_id + '&maintenanceid=' + maintenanceid + '&category=' + category + '&invoice',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR)
            {
                if (typeof data.error === 'undefined')
                {
//                                jQuery("#upload_puc").val('Upload Successfully');
//                                jQuery("#upload_puc").attr('disabled', 'disabled');
//                                jQuery("#puc").hide();
                    files_invoice_name = data.files[0];
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }
    else {
        alert('please select a file to upload');
        files_invoice = null;
    }
}

jQuery(".change_val").keypress(function () {
//alert("tets");
    var actual_amount = jQuery('#actual_amount').val();
    var sett_amount = jQuery('#sett_amount').val();
    jQuery('#mahindra_amount').val(actual_amount - sett_amount);
});
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function nospecialchars(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((charCode > 32 && charCode < 44) || charCode == 64)
        return false;
    return true;
}
function nospecialcharsnotes(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 47 && charCode < 123) || charCode == 32 || charCode == 37 || charCode == 42 || charCode == 8 || charCode == 45 || charCode == 35 || charCode == 44 || charCode == 46 || charCode == 40 || charCode == 41 || charCode == 36 || charCode == 47 || charCode == 43 || charCode == 61 || charCode == 45 || charCode == 39 || charCode == 34 || charCode == 124)
        return true;
    return false;
}

function getRate()
{
    var fuel = jQuery("#fuel").val();
    var amount = jQuery("#amount").val();
    if (fuel != '' && amount != '') {
        var rate = parseFloat(amount / fuel).toFixed(2);
        jQuery("#rate").val(rate);
    }
}

function getAmount()
{
    var fuel = jQuery("#fuel").val();
    var rate = jQuery("#rate").val();
    if (fuel != '' && rate != '') {
        var amount = parseFloat(rate * fuel).toFixed(2);
        jQuery("#amount").val(amount);
    }
}

function getAVG()
{
    var fuel = jQuery("#fuel").val();
    var open = jQuery("#openingkm").val();
    var end = jQuery("#endingkm").val();
    if (open != '' && end != '') {
        var rate = parseFloat((end - open) / fuel).toFixed(2);
        jQuery("#avg").val(rate);
    }
}


function push_fuel()
{
    var fuel = jQuery("#fuel").val();
    var amount = jQuery("#amount").val();
    var additional_amount = jQuery("#additional_amount").val();
    var openingkm = jQuery("#openingkm").val();
    var sdate = jQuery("#SDate").val();
    var stime = jQuery("#STime").val();
    var numbers = /^[0-9]{1,3}$/;
    var fuel_floats = /^[0-9]{1,3}(\.[0-9]{1,3})$/;
    var data = jQuery('#fuel_details').serialize();
    data += "&pushfuel=1";
    if (fuel == '') {
        jQuery("#fuel_error").show();
        jQuery("#fuel_error").fadeOut(9000);
    }
    else if (!(fuel.match(fuel_floats) || fuel.match(numbers))) {
        jQuery("#fuel_error1").show();
        jQuery("#fuel_error1").fadeOut(9000);
    }
    else if (sdate == '') {
        jQuery("#date_error").show();
        jQuery("#date_error").fadeOut(9000);
    }
    else if (stime == '') {
        jQuery("#time_error").show();
        jQuery("#time_error").fadeOut(9000);
    }
    else if (amount == '') {
        jQuery("#amt_error").show();
        jQuery("#amt_error").fadeOut(9000);
    }
    else if (openingkm == '') {
        jQuery("#opening_error").show();
        jQuery("#opening_error").fadeOut(9000);
    }
    else {
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck != "") {
                    var r = confirm("Your Transaction Id: FU00" + statuscheck);
                    if (r == true) {
                        window.location.href = "transaction.php?id=2";
                    }
                    else {
                        return false;
                    }
                }
            }
        });
    }
}
function edit_fuel()
{
    var fuel = jQuery("#fuel").val();
    var amount = jQuery("#amount").val();
    var openingkm = jQuery("#openingkm").val();
    var sdate = jQuery("#SDate").val();
    var stime = jQuery("#STime").val();
    var numbers = /^[0-9]{1,3}$/;
    var fuel_floats = /^[0-9]{1,3}(\.[0-9]{1,3})$/;
    if (fuel == '') {
        jQuery("#fuel_error").show();
        jQuery("#fuel_error").fadeOut(6000);
    }
    else if (!(fuel.match(fuel_floats) || fuel.match(numbers))) {
        jQuery("#fuel_error1").show();
        jQuery("#fuel_error1").fadeOut(6000);
    }
    else if (sdate == '') {
        jQuery("#date_error").show();
        jQuery("#date_error").fadeOut(6000);
    }
    else if (stime == '') {
        jQuery("#time_error").show();
        jQuery("#time_error").fadeOut(6000);
    }
    else if (amount == '') {
        jQuery("#amt_error").show();
        jQuery("#amt_error").fadeOut(6000);
    }
    else if (openingkm == '') {
        jQuery("#opening_error").show();
        jQuery("#opening_error").fadeOut(6000);
    }
    else {
        var data = jQuery('#Editfuel_f').serialize();
        data += "&action=editfuel";
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    window.location.href = "transaction.php?id=2";
                }
            }
        });
    }
}

function activetextbox()
{
    var tyretype = jQuery("#tyrerepair").val();
    if (tyretype == 2 || tyretype == 3) {
        jQuery(".txtsrno").css("display", "none");
        if (jQuery("#rf").prop("checked"))
        {
            jQuery("#oright_front").attr("readonly", false);
        }
        else {
            jQuery("#oright_front").attr("readonly", true);
        }

        if (jQuery("#lf").prop("checked"))
        {
            jQuery("#oleft_front").attr("readonly", false);
        }
        else {
            jQuery("#oleft_front").attr("readonly", true);
        }

        if (jQuery("#rb_out").prop("checked"))
        {
            jQuery("#oright_back_out").attr("readonly", false);
        }
        else {
            jQuery("#oright_back_out").attr("readonly", true);
        }

        if (jQuery("#lb_out").prop("checked"))
        {
            jQuery("#oleft_back_out").attr("readonly", false);
        }
        else {
            jQuery("#oleft_back_out").attr("readonly", true);
        }

        if (jQuery("#rb_in").prop("checked"))
        {
            jQuery("#oright_back_in").attr("readonly", false);
        }
        else {
            jQuery("#oright_back_in").attr("readonly", true);
        }
        if (jQuery("#lb_in").prop("checked"))
        {
            jQuery("#oleft_back_in").attr("readonly", false);
        }
        else {
            jQuery("#oleft_back_in").attr("readonly", true);
        }

        if (jQuery("#st").prop("checked"))
        {
            jQuery("#ostepney").attr("readonly", false);
        }
        else {
            jQuery("#ostepney").attr("readonly", true);
        }
    }
    else {
        if (jQuery("#rf").attr("checked"))
        {
            jQuery("#nright_front").css("display", "block");
            jQuery('#nright_front').removeAttr("readonly");
            jQuery("#oright_front").attr("readonly", true);
        } else {
            jQuery("#nright_front").css("display", "none");
        }

        if (jQuery("#lf").attr("checked"))
        {
            jQuery("#nleft_front").css("display", "block");
            jQuery('#nleft_front').removeAttr("readonly");
            jQuery("#oleft_front").attr("readonly", true);
        }
        else {
            jQuery("#nleft_front").css("display", "none");
        }



        if (jQuery("#rb_out").attr("checked"))
        {
            jQuery("#nright_back_out").css("display", "block");
            jQuery('#nright_back_out').removeAttr("readonly");
            jQuery("#oright_back_out").attr("readonly", true);
        }
        else {
            jQuery("#nright_back_out").css("display", "none");
        }

        if (jQuery("#lb_out").attr("checked"))
        {
            jQuery("#nleft_back_out").css("display", "block");
            jQuery('#nleft_back_out').removeAttr("readonly");
            jQuery("#oleft_back_out").attr("readonly", true);
        }
        else {
            jQuery("#nleft_back_out").css("display", "none");
        }
        
        if (jQuery("#rb_in").attr("checked"))
        {
            jQuery("#nright_back_in").css("display", "block");
            jQuery('#nright_back_in').removeAttr("readonly");
            jQuery("#oright_back_in").attr("readonly", true);
        }
        else {
            jQuery("#nright_back_in").css("display", "none");
        }



        if (jQuery("#lb_in").attr("checked"))
        {
            jQuery("#nleft_back_in").css("display", "block");
            jQuery('#nleft_back_in').removeAttr("readonly");
            jQuery("#oleft_back_in").attr("readonly", true);
        }
        else {
            jQuery("#nleft_back_in").css("display", "none");
        }
        if (jQuery("#st").attr("checked"))
        {
            jQuery("#nstepney").css("display", "block");
            jQuery('#nstepney').removeAttr("readonly");
            jQuery("#ostepney").attr("readonly", true);
        }
        else {
            jQuery("#nstepney").css("display", "none");
        }
        if (jQuery("#newbatt_chkbox").attr("checked")) {
            jQuery("#new_battsrno").css("display", "block");
            jQuery('#new_battsrno').removeAttr("readonly");
        }
        else {
            jQuery("#new_battsrno").css("display", "none");
        }
    }
}
function push_transaction_by_category() {
    var iserror = 0;
    var behalfid = 0;
    var meter_reading = jQuery("#meter_reading").val();
    var customerno = jQuery("#customerno").val();
    var roleid = jQuery("#roleid").val();
    var dealerid = jQuery("#dealerid option:selected").val();
    var note_batt = jQuery("#note_batt").val();
    var file_for_quote = jQuery("#file_for_quote").val();
    var vehicle_id = jQuery("#vehicle_id").val();
    var amount_qt = jQuery("#amount_quote").val();
    var amount_quote = parseFloat(amount_qt).toFixed(2);
    var new_batt = jQuery("#new_battsrno").val();
    var old_batt = jQuery("#old_battsrno").val();
    var old_rf = jQuery("#oright_front").val();
    var new_rf = jQuery("#nright_front").val();
    var new_rb_out = jQuery("#nright_back_out").val();
    var old_rb_out = jQuery("#oright_back_out").val();
    var new_rb_in = jQuery("#nright_back_in").val();
    var old_rb_in = jQuery("#oright_back_in").val();
    var new_lb_out = jQuery("#nleft_back_out").val();
    var old_lb_out = jQuery("#oleft_back_out").val();
    var new_lf = jQuery("#nleft_front").val();
    var old_lf = jQuery("#oleft_front").val();
    var new_lb_in = jQuery("#nleft_back_in").val();
    var old_lb_in = jQuery("#oleft_back_in").val();
    var new_st = jQuery("#nstepney").val();
    var old_st = jQuery("#ostepney").val();
    var tyre_repair = jQuery("#tyrerepair").val();
    var tax = jQuery("#p_tax").val();
    var statusid = '';
    var selected = [];
    //var i = 0;

    if (customerno == 64 && (roleid == '1' || roleid == '33' || roleid == '35')) {
        if (jQuery('input[name="behalfradiobtn"]:checked').length === 0) {
            alert('Please select one option');
            return false;
        } else {
            var zonemaster = jQuery("#zonemaster").val();
            var regionalmaster = jQuery("#regionalmaster").val();
            var branchmaster = jQuery("#branchmaster").val();

            if (zonemaster == 0 && regionalmaster == 0 && branchmaster == 0) {
                alert('Please select manager');
                return false;
            } else {
                var behalfvalue = jQuery('input[name=behalfradiobtn]:checked').val();
                if (behalfvalue == 1) {
                    if (zonemaster == 0) {
                        alert('Please select Zone Manager');
                        return false;
                    } else {
                        behalfid = zonemaster;
                    }

                } else if (behalfvalue == 2) {
                    if (regionalmaster == 0) {
                        alert('Please select Regional Manager');
                        return false;
                    } else {
                        behalfid = regionalmaster;
                    }
                } else if (behalfvalue == 3) {
                    if (branchmaster == 0) {
                        alert('Please select Branch Manager');
                        return false;
                    } else {
                        behalfid = branchmaster;
                    }
                }
            }
        }
    }

    if (jQuery("#category_type").val() == 3)
    {
        var category_id = 3;
    } else {
        var category_id = jQuery("#category_id").val();
    }
    var customerno = jQuery("#customerno").val();
    if (category_id == 1 && customerno == 118)
    {
        jQuery(".chk").each(function () {
            var chkbox = jQuery(this);
            if (chkbox.attr('checked')) {

                selected.push(jQuery(this).attr('name'));
                //i++;
            }
        });
    }

    var tax = parseFloat(jQuery("#p_tax").val()) || 0;
    tax = parseFloat(tax.toFixed(2));
    if (category_id === 2 || category_id === 3)
    {
        var category_type = jQuery("#category_type").val();
    }
    var tyre_list = [];
    jQuery('.tyre_type_array').each(function (id, obj) {
        tyre_list.push(obj.value);
    });
    var parts_list = [];
    jQuery('.parts_select_array').each(function (id, obj) {
        parts_list.push(obj.value);
    });
    var task_select_array = [];
    jQuery('.task_select_array').each(function (id, obj) {
        task_select_array.push(obj.value);
    });
    if (meter_reading == "")
    {
        iserror = 1;
        jQuery("#mr_error").show();
        jQuery("#mr_error").fadeOut(6000);
    }
    else if (dealerid == '-1')
    {
        iserror = 1;
        jQuery("#dl_error").show();
        jQuery("#dl_error").fadeOut(6000);
    }
    else if (note_batt == "")
    {
        iserror = 1;
        jQuery("#notes_error").show();
        jQuery("#notes_error").fadeOut(6000);
    }
    else if (amount_quote == "")
    {
        iserror = 1;
        jQuery("#quote_error").show();
        jQuery("#quote_error").fadeOut(6000);
    }
    else if (category_id == 1 && tyre_list.length == 0 && customerno != 118)
    {
        iserror = 1;
        jQuery("#tyre_type_error").show();
        jQuery("#tyre_type_error").fadeOut(6000);
    }
    else if (tyre_repair == 0 && category_id == 1 && customerno == 118) {
        iserror = 1;
        jQuery("#trepair_error").show();
        jQuery("#trepair_error").fadeOut(6000);
    }
    else if (selected.length === 0 && category_id == 1 && customerno == 118) {
        iserror = 1;
        jQuery("#chk_error").show();
        jQuery("#chk_error").fadeOut(6000);
    }

    else if (category_type == -1 && (category_id == 2 || category_id == 3))
    {
        iserror = 1;
        jQuery("#parts_type_error").show();
        jQuery("#parts_type_error").fadeOut(6000);
    }
    else if ((category_id == 2 || category_id == 3)) {
        for (var i = 1; i <= 50; i++) {
            if (jQuery("#parts_select_" + i + "").val() != '-1' && (jQuery("#parts_qty" + i + "").val() == '' || jQuery("#parts_amount" + i + "").val() == '' || jQuery("#parts_qty" + i + "").val() == '0') || jQuery("#parts_amount" + i + "").val() == '0') {
                iserror = 1;
                jQuery("#parts_error").show();
                jQuery("#parts_error").fadeOut(9000);
            }
            if (jQuery("#tasks_select_" + i + "").val() != '-1' && (jQuery("#tasks_qty" + i + "").val() == '' || jQuery("#tasks_amount" + i + "").val() == '' || jQuery("#tasks_qty" + i + "").val() == '0') || jQuery("#tasks_amount" + i + "").val() == '0') {
                iserror = 1;
                jQuery("#tasks_error").show();
                jQuery("#tasks_error").fadeOut(9000);
            }
        }
    }

    else if (category_id == 5 && amount_quote > 22500 && customerno == 64)
    {
        iserror = 1;
        jQuery("#quote_exceed_error").show();
        jQuery("#quote_exceed_error").fadeOut(6000);
    }
    if (jQuery('#sent_pay').is(":checked"))
    {
        statusid = 10;
    }
    else {
        statusid = 7;
    }
    if (typeof iserror !== 'undefined' && iserror != '1')
    {
        var get_string = "route_ajax.php?";
        get_string += "meter_reading=" + meter_reading + " ";
        get_string += "&behalfid=" + behalfid + " ";
        get_string += "&dealerid=" + dealerid + "";
        get_string += "&note_batt=" + note_batt + "";
        get_string += "&work=transaction";
        get_string += "&vehicle_id=" + vehicle_id + "";
        get_string += "&amount_quote=" + amount_quote + "";
        get_string += "&category_id=" + category_id + "";
        get_string += "&status=" + statusid + "";
        if (statusid == 10) {
            var vehindate = jQuery("#vehicle_in_date").val();
            var vehoutdate = jQuery("#vehicle_out_date").val();
            var invoiceno = jQuery("#invoice_no").val();
            var invoiceamt = jQuery("#amount_invoice").val();
            var invoicedate = jQuery("#invoice_date").val();
            get_string += "&vehindate=" + vehindate + "";
            get_string += "&vehoutdate=" + vehoutdate + "";
            get_string += "&invoiceno=" + invoiceno + "";
            get_string += "&invoiceamt=" + invoiceamt + "";
            get_string += "&invoicedate=" + invoicedate + "";
        }
        switch (category_id.toString()) {
            case '0':
                get_string += "&old_battno=" + old_batt + "";
                get_string += "&new_battno=" + new_batt + "";
                get_string += "&tax=" + tax + "";
                break;
            case '1':
                if (customerno == 118) {
                    get_string += "&tyre_repair=" + tyre_repair + "";
                    get_string += "&tax=" + tax + "";
                    var oldtyresrno_list = [];
                    var newtyresrno_list = [];
                    var newtyre_tyreid_srno = [];

                    if (jQuery("#rf").attr("checked"))
                    {
                        oldtyresrno_list.push("Right Front-" + old_rf);
                        newtyresrno_list.push("Right Front-" + new_rf);
                        newtyre_tyreid_srno.push("1$" + new_rf);
                    }
                    if (jQuery("#rb_out").attr("checked")) {
                        oldtyresrno_list.push("Right Back Out-" + old_rb_out);
                        newtyresrno_list.push("Right Back Out-" + new_rb_out);
                        newtyre_tyreid_srno.push("3$" + new_rb_out);
                    }
                    if (jQuery("#rb_in").attr("checked")) {
                        oldtyresrno_list.push("Right Back In-" + old_rb_in);
                        newtyresrno_list.push("Right Back In-" + new_rb_in);
                        newtyre_tyreid_srno.push("6$" + new_rb_in);
                    }
                    if (jQuery("#lf").attr("checked")) {
                        oldtyresrno_list.push("Left Front-" + old_lf);
                        newtyresrno_list.push("Left Front-" + new_lf);
                        newtyre_tyreid_srno.push("2$" + new_lf);
                    }
                    if (jQuery("#lb_out").attr("checked")) {
                        oldtyresrno_list.push("Left Back Out-" + old_lb_out);
                        newtyresrno_list.push("Left Back Out-" + new_lb_out);
                        newtyre_tyreid_srno.push("4$" + new_lb_out);
                    }
                    if (jQuery("#lb_in").attr("checked")) {
                        oldtyresrno_list.push("Left Back In-" + old_lb_in);
                        newtyresrno_list.push("Left Back In-" + new_lb_in);
                        newtyre_tyreid_srno.push("7$" + new_lb_in);
                    }
                    if (jQuery("#st").attr("checked")) {
                        oldtyresrno_list.push("Stepney-" + old_st);
                        newtyresrno_list.push("Stepney-" + new_st);
                        newtyre_tyreid_srno.push("5$" + new_st);
                    }
                    get_string += "&oldtyre_list=" + oldtyresrno_list.toString() + "";
                    get_string += "&newtyre_list=" + newtyresrno_list.toString() + "";
                    get_string += "&newtyre_tyreid_srno=" + newtyre_tyreid_srno.toString() + "";
                }
                else {
                    // does nott need any more agruments
                    var tyre_list = [];
                    jQuery('.tyre_type_array').each(function (id, obj) {
                        tyre_list.push(obj.value);
                    });
                    // convering array into strin and assigning to the getstring url
                    get_string += "&tyre_list=" + tyre_list.toString() + "&tax=" + tax + "";
                }
                break;
            case '2':
                var parts_list = [];
                jQuery('.parts_select_array').each(function (id, obj) {
                    parts_list.push(obj.value);
                });
                // convering array into strin and assigning to the getstring url
                get_string += "&parts_list=" + parts_list.toString() + "";
                var task_select_array = [];
                jQuery('.task_select_array').each(function (id, obj) {
                    task_select_array.push(obj.value);
                });
                // convering array into strin and assigning to the getstring url
                get_string += "&task_select_array=" + task_select_array.toString() + "";
                //Converting Array into strin and assigning to the getstring url
                var parts_list1 = [];
                var parts_total = 0;
                var tasks_total = 0;
                var parts_tax_amt = 0;  // add total tax amount 
                var parts_disc_amt = 0; // parts discount amount
                var tasks_tax_total = 0;//add total amount (qty*amt-discount)
                var tasks_disc_amt = 0; // tasks discount amount

                for (i = 1; i <= 50; i++) {
                    if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
                    {
                        parts_list1.push(jQuery("#parts_select_" + i).val() + "-" + jQuery("#parts_amount" + i).val() + "-" + jQuery("#parts_qty" + i).val() + "-" + jQuery("#parts_discs" + i).val() + "-" + jQuery("#parts_tot" + i).val());
                        parts_total += jQuery("#parts_amount" + i).val() * jQuery("#parts_qty" + i).val();
                        parts_disc_amt += parseFloat(jQuery("#parts_discs" + i).val()) * jQuery("#parts_qty" + i).val();
                        parts_tax_amt += parseFloat(jQuery("#parts_tot" + i).val());
                    }
                }
                get_string += "&parts_select_array1=" + parts_list1.toString() + "";

                var tasks_list1 = [];
                for (i = 1; i <= 50; i++) {
                    if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
                    {
                        tasks_list1.push(jQuery("#tasks_select_" + i).val() + "-" + jQuery("#tasks_amount" + i).val() + "-" + jQuery("#tasks_qty" + i).val() + "-" + jQuery("#tasks_discs" + i).val() + "-" + jQuery("#tasks_tot" + i).val());
                        tasks_total += jQuery("#tasks_amount" + i).val() * jQuery("#tasks_qty" + i).val();
                        tasks_disc_amt += parseFloat(jQuery("#tasks_discs" + i).val()) * jQuery("#tasks_qty" + i).val();
                        tasks_tax_total += parseFloat(jQuery("#tasks_tot" + i).val());
                    }
                }
                get_string += "&tasks_select_array1=" + tasks_list1.toString() + "";
                var totalamt = parts_total - parts_disc_amt;
                var tasksamt = tasks_total - tasks_disc_amt;
                var tot = (parseFloat(totalamt) || 0) + (parseFloat(tasksamt) || 0);
                tot = parseFloat(tot).toFixed(2);
                get_string += "&totalinv=" + tot + "&tax=" + tax + "";
                break;
            case '3':
                //Converting Array into strin and assigning to the getstring url
                var parts_list1 = [];
                var parts_total = 0;
                var parts_tax_amt = 0;  // add total tax amount 
                var parts_disc_amt = 0;  // parts discount amount
                var tasks_tax_total = 0;  //add total task tax amount 
                var tasks_disc_amt = 0;  // tasks discount amount
                for (i = 1; i <= 50; i++) {
                    if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
                    {
                        parts_list1.push(jQuery("#parts_select_" + i).val() + "-" + jQuery("#parts_amount" + i).val() + "-" + jQuery("#parts_qty" + i).val() + "-" + jQuery("#parts_qty" + i).val() + "-" + jQuery("#parts_discs" + i).val() + "-" + jQuery("#parts_tot" + i).val());
                        parts_total += jQuery("#parts_amount" + i).val() * jQuery("#parts_qty" + i).val();
                        parts_disc_amt += jQuery("#parts_discs" + i).val();
                        parts_tax_amt += jQuery("#parts_tot" + i).val();
                    }
                }

                get_string += "&parts_select_array1=" + parts_list1.toString() + "";
                var tasks_list1 = [];
                var tasks_total = 0;
                for (i = 1; i <= 50; i++) {
                    if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
                    {
                        tasks_list1.push(jQuery("#tasks_select_" + i).val() + "-" + jQuery("#tasks_amount" + i).val() + "-" + jQuery("#tasks_qty" + i).val() + "-" + jQuery("#tasks_discs" + i).val() + "-" + jQuery("#tasks_tot" + i).val());
                        tasks_total += jQuery("#tasks_amount" + i).val() * jQuery("#tasks_qty" + i).val();
                        tasks_disc_amt = jQuery("#tasks_discs" + i).val();
                        tasks_tax_total = jQuery("#tasks_tot" + i).val();
                    }
                }
                get_string += "&tasks_select_array1=" + tasks_list1.toString() + "";

                var totalamt = parts_total - parts_disc_amt;
                var tasksamt = tasks_total - tasks_disc_amt;
                var tot = (parseFloat(totalamt) || 0) + (parseFloat(tasksamt) || 0);
                tot = parseFloat(tot).toFixed(2);
                get_string += "&totalinv=" + tot + "&tax=" + tax + "";
                break;
            case '5':
                var accessory_list = [];
                if (jQuery("#accessory_select_1").val() != "-1")
                {
                    accessory_list.push(jQuery("#accessory_select_1").val() + "-" + jQuery("#amount1").val() + "-" + jQuery("#max_amount_hid_1").val());
                }
                if (jQuery("#accessory_select_2").val() != "-1")
                {
                    accessory_list.push(jQuery("#accessory_select_2").val() + "-" + jQuery("#amount2").val() + "-" + jQuery("#max_amount_hid_2").val());
                }
                if (jQuery("#accessory_select_3").val() != "-1")
                {
                    accessory_list.push(jQuery("#accessory_select_3").val() + "-" + jQuery("#amount3").val() + "-" + jQuery("#max_amount_hid_3").val());
                }
                if (jQuery("#accessory_select_4").val() != "-1")
                {
                    accessory_list.push(jQuery("#accessory_select_4").val() + "-" + jQuery("#amount4").val() + "-" + jQuery("#max_amount_hid_4").val());
                }
                if (jQuery("#accessory_select_5").val() != "-1")
                {
                    accessory_list.push(jQuery("#accessory_select_5").val() + "-" + jQuery("#amount5").val() + "-" + jQuery("#max_amount_hid_5").val());
                }
                if (jQuery("#accessory_select_6").val() != "-1")
                {
                    accessory_list.push(jQuery("#accessory_select_6").val() + "-" + jQuery("#amount6").val() + "-" + jQuery("#max_amount_hid_6").val());
                }
                if (jQuery("#accessory_select_7").val() != "-1")
                {
                    accessory_list.push(jQuery("#accessory_select_7").val() + "-" + jQuery("#amount7").val() + "-" + jQuery("#max_amount_hid_7").val());
                }
                get_string += "&accessory_list=" + accessory_list.toString() + "";
                break;
            default :
                alert("not valid data");
                break;
        }
        var data = new FormData();
        if (files)
        {
            jQuery.each(files, function (key, value)
            {
                data.append(key, value);
            });
        }

        if (parseFloat(amount_quote) < parseFloat(tot) && (category_id == 2 || category_id == 3)) {
            jQuery("#quote_exceed_totalinv").show();
            jQuery("#quote_exceed_totalinv").fadeOut(6000);
            //alert("Parts & Tasks Amount Not Greater Than Quotation Amount");
        }
        else {
            jQuery.ajax({
                type: "POST",
                url: get_string,
                contentType: true,
                data: data,
                dataType: "JSON",
                processData: false, // Don't process the files
                contentType: false,
                        success: function (data) {
                            if (data.status == true) {
                                var transid = data.mainid;
                                var prefix = '';
                                if (category_id == '2') { //repair and service
                                    prefix = 'R00';
                                }
                                else if (category_id == '3') { //
                                    prefix = 'AC00';
                                }
                                else if (category_id == '1') {
                                    prefix = 'T00';
                                }
                                else if (category_id == '5') {
                                    prefix = 'A00';
                                }
                                else if (category_id == '0') {
                                    prefix = 'B00';
                                }

                                if (transid != "") {
                                    var r = confirm("Your Added Sucessfully. Transaction Id:" + prefix + "" + transid);
                                    if (r == true) {
                                        jQuery("#getbattery_approval")[0].reset();
                                        jQuery(".file-input-name").html("");
                                        jQuery('#addview_approval').modal('hide');
                                        window.location.href = "transaction.php?id=2";
                                    }
                                }
                            } else {
                                jQuery("#getbattery_approval")[0].reset();
                                jQuery(".file-input-name").html("");
                                jQuery("#transaction_msg").html(data.status_msg);
                                jQuery("#transaction_msg").addClass("alert alert-danger");
                                jQuery("#transaction_msg").removeClass("alert alert-info");
                                jQuery("#transaction_msg").show();
                            }
                        }
            });
        }
    }
}

function push_transaction_accident() {
    var vehicle_id = jQuery("#vehicle_id1").val();
    //alert(vehicle_id);
    var atpos = jQuery("#send_report").val().indexOf("@");
    var dotpos = jQuery("#send_report").val().lastIndexOf(".");
    var from_date = jQuery("#val_from_Date").val();
    var to_date = jQuery("#val_to_Date").val();
    var datediff = daydiff(parseDate(from_date), parseDate(to_date));
    if (jQuery("#acc_location").val() == "")
    {
        jQuery("#al_error").show();
        jQuery("#al_error").fadeOut(6000);
    }
    else if (jQuery("#acc_desc").val() == "")
    {
        jQuery("#ad_error").show();
        jQuery("#ad_error").fadeOut(6000);
    }
    else if (jQuery("#driver_name").val() == "")
    {
        jQuery("#dn_error").show();
        jQuery("#dn_error").fadeOut(6000);
    }
    else if (jQuery("#send_report").val() != '' && (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= jQuery("#send_report").val().length))
    {
        jQuery("#email_error").show();
        jQuery("#email_error").fadeOut(6000);
    }
    else if (datediff < 0)
    {
        jQuery("#date_conflict_error").show();
        jQuery("#date_conflict_error").fadeOut(6000);
    }
    else if (vehicle_id != '') {
        var data = jQuery('#getaccident_approval').serialize() + '&accident_vehicleid=' + vehicle_id;
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "notok") {
                    document.getElementById("getaccident_approval").reset();
                    jQuery(".file-input-name").html("");
                }
                else if (statuscheck != "notok") {
                    var r = confirm("Your Transaction Id:AC00" + statuscheck);
                    if (r == true) {
                        document.getElementById("getaccident_approval").reset();
                        jQuery(".file-input-name").html("");
                        jQuery('#accidentview_approval').modal('hide');
                        window.location.href = "transaction.php?id=2";
                    }
                    else {
                        return false;
                    }
                }
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }
}

function add_items_container(target, type) {
    var itemsel = jQuery("#" + type + " option:selected").val();
    var itemtext = jQuery("#" + type + " option:selected").text();
    if (itemsel != "" && itemsel != "-1") {
        if (jQuery('#' + type + '_' + itemsel).val() == null)
        {
            jQuery(target).append("<p class='btn btn-primary'  id='" + type + "_" + itemsel + "'>" + itemtext + "<input type='hidden' class='" + type + "_array' name='tyre_type[]' value='" + itemsel + "'/> <span onclick='jQuery(this).parent().remove();'>x</span></p> ");
        }
        else
        {
            // Do Nothing
        }
    }
    else {
        alert("select a type first");
    }
}

function category_selector() {
    var category_type = jQuery("#category_type option:selected").val();
    jQuery("#category_handler").html("");
    if (category_type == 2) {
        jQuery("#category_handler").html(jQuery("#parts_task").html());
    }
    else {
        jQuery("#category_handler").html("");
    }
    if (category_type == 2 || category_type == 3) {
        jQuery("#category_id").val(category_type);
    }

}

function sel_acc(num)
{
    var accessory = jQuery("#accessory_select_" + num).val();
    jQuery('#max_amount_' + num).text(0);
    jQuery('#max_amount_hid_' + num).val(0);
    if (accessory != '-1') {
        jQuery.ajax({
            url: "route_ajax.php",
            dataType: "json",
            type: 'POST',
            cache: false,
            data: {accessory_id: accessory},
            success: function (data) {
                jQuery('#max_amount_' + num).text(data);
                jQuery('#max_amount_hid_' + num).val(data);
            }
        });
        return false;
    }
}

function getnotes(vehicle_id)
{
    if (vehicle_id != '') {

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {notes_vehicle_id: vehicle_id},
            dataType: 'html',
            success: function (html) {
                jQuery("#notes_body").html('');
                jQuery("#notes_body").append(html);
                jQuery('#viewnotes').modal('show');
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }

}

jQuery(".upload").on("change", function () {


    uploadFilesPUC(event, jQuery(this).data('fd'));
});
function upload(id) {
    //  uploadFilesPUC(event,id);
}
function prepareUploadPUC(event)
{
    acc_files = event.target.files;
}

function uploadFilesPUC(event, id)
{
    alert(id);
    var uploadfile;
    //alert(id);
    if (id == '1')
        uploadfile = jQuery('#file1').val();
    else if (id == '2')
        uploadfile = jQuery('#file2').val();
    else if (id == '3')
        uploadfile = jQuery('#file3').val();
    else if (id == '4')
        uploadfile = jQuery('#file4').val();
    else if (id == '5')
        uploadfile = jQuery('#file5').val();
    if (uploadfile != '') {
        //event.stopPropagation(); // Stop stuff happening
        //event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE

        // Create a formdata object and add the files
        var vehicle_id = jQuery('#vehicle_id').val();
        var data = new FormData();
        jQuery.each(files, function (key, value)
        {
            data.append(key, value);
        });
        files = null;
        jQuery.ajax({
            url: 'upload.php?vehicleid=' + vehicle_id + '&acc_file=' + id,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR)
            {
                if (typeof data.error === 'undefined')
                {
                    jQuery("#upload_file" + id).val('Upload Successful');
                    jQuery("#upload_file" + id).attr('disabled', 'disabled');
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }
    else {
        alert('please select a file to upload');
        files = null;
    }
}

jQuery('#file1').on('change', prepareUploadAccident);
jQuery('#file2').on('change', prepareUploadAccident);
jQuery('#file3').on('change', prepareUploadAccident);
jQuery('#file4').on('change', prepareUploadAccident);
jQuery('#file5').on('change', prepareUploadAccident);
function prepareUploadAccident(event)
{
    files_batt = null;
    files_batt = event.target.files;
    uploadFilesACC(event, this.id);
}


function uploadFilesACC(event, id)
{
    var edit_vehicle_id = jQuery('#vehicle_id1').val();
    if (edit_vehicle_id != "") {

        var data = new FormData();
        jQuery.each(files_batt, function (key, value)
        {
            data.append(key, value);
        });
        files = null;
        jQuery.ajax({
            url: 'upload.php?vehicleid=' + edit_vehicle_id + '&acc=true&inp_id=' + id,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR)
            {
                if (typeof data.error === 'undefined')
                {

                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }
    else {
        alert('please select a file to upload');
        files = null;
    }
}


function get_battery(maintenanceid, vehicleid)
{
    if (maintenanceid != '') {

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {battery_maintenanceid: maintenanceid},
            dataType: "json",
            success: function (data) {
                if (data.statusid == 8) {
                    jQuery('#getbattery_edit #invoice_file_div').show();
                    jQuery('#getbattery_edit #invoice_file').show();
                    jQuery('#getbattery_edit #edit_save_battery').show();
                    jQuery('#getbattery_edit #edit_cancel_battery').show();
                    jQuery('#getbattery_edit #edit_close_battery').hide();
                    jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', false);
                    jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', false);
                    jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', false);
                    jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', false);
                    jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', false);
                    jQuery('#getbattery_edit #ofasnumberdiv').hide();
                    jQuery('#getbattery_edit #invoice_file_view').hide();
                    jQuery('#getbattery_edit #payment_approval_date').hide();
                    jQuery('#getbattery_edit #payment_approval_note').hide();
                    jQuery('#getbattery_edit #trans_close_date').hide();
                    jQuery('#getbattery_edit #ofasnumber_view').hide();
                    jQuery('#getbattery_edit #quotation_approval_note').show();
                    if (data.categoryid != 1)
                    {
                        jQuery('#getbattery_edit #show_tyre_type').hide();
                    }
                    else
                    {
                        jQuery('#getbattery_edit #show_tyre_type').show();
                    }
                    if (data.categoryid != 2)
                    {
                        jQuery('#getbattery_edit #show_parts').hide();
                        jQuery('#getbattery_edit #show_tasks').hide();
                    }
                    else
                    {
                        jQuery('#getbattery_edit #show_parts').show();
                        jQuery('#getbattery_edit #show_tasks').show();
                    }
                }
                else if (data.statusid == 13) {
                    if (data.categoryid != 5)
                    {
                        jQuery('#getbattery_edit #invoice_file').show();
                        jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', true);
                        jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', true);
                        jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', true);
                        jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', true);
                        jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', true);
                        jQuery('#getbattery_edit #invoice_file_div').hide();
                        jQuery('#getbattery_edit #invoice_file_view').hide();
                        jQuery('#getbattery_edit #payment_approval_date').hide();
                        jQuery('#getbattery_edit #payment_approval_note').hide();
                        jQuery('#getbattery_edit #trans_close_date').hide();
                        jQuery('#getbattery_edit #ofasnumber_view').hide();
                        jQuery('#getbattery_edit #quotation_approval_note').show();
                    }
                    jQuery('#getbattery_edit #edit_save_battery').hide();
                    jQuery('#getbattery_edit #edit_cancel_battery').hide();
                    jQuery('#getbattery_edit #edit_close_battery').show();
                    jQuery('#getbattery_edit #ofasnumberdiv').show();
                    if (data.categoryid != 1)
                    {
                        jQuery('#getbattery_edit #show_tyre_type').hide();
                    }
                    else
                    {
                        jQuery('#getbattery_edit #show_tyre_type').show();
                    }
                    if (data.categoryid != 2)
                    {
                        jQuery('#getbattery_edit #show_parts').hide();
                        jQuery('#getbattery_edit #show_tasks').hide();
                    }
                    else
                    {
                        jQuery('#getbattery_edit #show_parts').show();
                        jQuery('#getbattery_edit #show_tasks').show();
                    }
                }
                else
                {
                    jQuery('#getbattery_edit #invoice_file').hide();
                    jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', true);
                    jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', true);
                    jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', true);
                    jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', true);
                    jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', true);
                    jQuery('#getbattery_edit #invoice_file_div').hide();
                    jQuery('#getbattery_edit #ofasnumberdiv').hide();
                    jQuery('#getbattery_edit #edit_save_battery').hide();
                    jQuery('#getbattery_edit #edit_cancel_battery').hide();
                    jQuery('#getbattery_edit #edit_close_battery').hide();
                    jQuery('#getbattery_edit #invoice_file_view').hide();
                    jQuery('#getbattery_edit #payment_approval_date').hide();
                    jQuery('#getbattery_edit #payment_approval_note').hide();
                    jQuery('#getbattery_edit #trans_close_date').hide();
                    jQuery('#getbattery_edit #ofasnumber_view').hide();
                    jQuery('#getbattery_edit #quotation_approval_note').show();
                    if (data.categoryid != 1)
                    {
                        jQuery('#getbattery_edit #show_tyre_type').hide();
                    }
                    else
                    {
                        jQuery('#getbattery_edit #show_tyre_type').show();
                    }
                    if (data.categoryid != 2)
                    {
                        jQuery('#getbattery_edit #show_parts').hide();
                        jQuery('#getbattery_edit #show_tasks').hide();
                    }
                    else
                    {
                        jQuery('#getbattery_edit #show_parts').show();
                        jQuery('#getbattery_edit #show_tasks').show();
                    }
                }
                jQuery('#getbattery_edit #quotation_approval_note_val').text(data.approval_notes);
                jQuery('#getbattery_edit #batt_parts').text(data.partsnew);
                jQuery('#getbattery_edit #batt_tasks').text(data.tasksnew);
                jQuery('#getbattery_edit #batt_tyre_type').text(data.tyre_type);
                jQuery('#getbattery_edit #batt_veh_no').text(data.vehicleno);
                jQuery('#getbattery_edit #batt_status').text(data.statusname);
                jQuery('#getbattery_edit #batt_category').text(data.category);
                jQuery('#getbattery_edit #category_id').val(data.categoryid);
                jQuery('#getbattery_edit #batt_transid').text(data.transid);
                jQuery('#getbattery_edit #batt_submission_date').text(data.submission_date);
                jQuery('#getbattery_edit #batt_veh_branch').text(data.groupname);
                jQuery('#getbattery_edit #batt_veh_odometer').text(data.odometer);
                jQuery('#getbattery_edit #batt_meter_reading').text(data.meter_reading);
                jQuery('#getbattery_edit #batt_dealer').text(data.dealername);
                jQuery('#getbattery_edit #batt_vehicle_in_date').val(data.vehicle_in_date);
                jQuery('#getbattery_edit #batt_vehicle_out_date').val(data.vehicle_out_date);
                jQuery('#getbattery_edit #batt_amount_quote').text(data.amount_quote);
                jQuery('#getbattery_edit #batt_invoice_date').val(data.invoice_date);
                jQuery('#getbattery_edit #batt_invoice_no').val(data.batt_invoice_no);
                jQuery('#getbattery_edit #batt_amount_invoice').val(data.invoice_amount);
                jQuery('#getbattery_edit #batt_notes').text(data.notes);
                jQuery('#maintenance_edit_id').val(maintenanceid);
                jQuery('#edit_vehicle_id').val(vehicleid);
                jQuery('#edit_save_battery').attr({onclick: 'editbattery(' + maintenanceid + ',' + data.vehicleid + ',10,' + data.categoryid + ',' + data.amount_quote + ')'});
                jQuery('#edit_cancel_battery').attr({onclick: 'editbattery(' + maintenanceid + ',' + data.vehicleid + ',11,' + data.categoryid + ',' + data.amount_quote + ')'});
                jQuery('#edit_close_battery').attr({onclick: 'editbattery(' + maintenanceid + ',' + data.vehicleid + ',14,' + data.categoryid + ',' + data.amount_quote + ')'});
                if (data.statusid == 8)
                {
                    checkfilename(maintenanceid, 0, 'quote', data.quote_file_name, 'battery', data.vehicleid, data.customerno);
                }
                else
                {
                    checkfilename(maintenanceid, 0, 'quote', data.quote_file_name, 'battery', data.vehicleid, data.customerno);
                    checkfilename(maintenanceid, 0, 'invoice', data.invoice_file_name, 'battery', data.vehicleid, data.customerno);
                }
                jQuery('#editbattery').modal('show');
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }

}

function get_battery_closed(maintenanceid, vehicleid)
{
    if (maintenanceid != '') {

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {battery_maintenanceid: maintenanceid},
            dataType: "json",
            success: function (data) {
                jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', true);
                jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', true);
                jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', true);
                jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', true);
                jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', true);
                jQuery('#getbattery_edit #invoice_file').hide();
                jQuery('#getbattery_edit #invoice_file_view').show();
                jQuery('#getbattery_edit #payment_approval_date').show();
                jQuery('#getbattery_edit #payment_approval_note').show();
                jQuery('#getbattery_edit #trans_close_date').show();
                jQuery('#getbattery_edit #ofasnumberdiv').hide();
                jQuery('#getbattery_edit #ofasnumber_view').show();
                jQuery('#getbattery_edit #edit_save_battery').hide();
                jQuery('#getbattery_edit #edit_cancel_battery').hide();
                jQuery('#getbattery_edit #edit_close_battery').hide();
                jQuery('#getbattery_edit #quotation_approval_note').show();
                if (data.categoryid != 1)
                {
                    jQuery('#getbattery_edit #show_tyre_type').hide();
                }
                else
                {
                    jQuery('#getbattery_edit #show_tyre_type').show();
                }
                if (data.categoryid != 2)
                {
                    jQuery('#getbattery_edit #show_parts').hide();
                    jQuery('#getbattery_edit #show_tasks').hide();
                }
                else
                {
                    jQuery('#getbattery_edit #show_parts').show();
                    jQuery('#getbattery_edit #show_tasks').show();
                }
                jQuery('#getbattery_edit #quotation_approval_note_val').text(data.approval_notes);
                jQuery('#getbattery_edit #batt_parts').text(data.partsnew);
                jQuery('#getbattery_edit #ofasnumber_view_value').text(data.ofasnumber);
                jQuery('#getbattery_edit #payment_approval_date_value').text(data.payment_approval_date);
                jQuery('#getbattery_edit #payment_approval_note_value').text(data.payment_approval_note);
                jQuery('#getbattery_edit #trans_close_date_value').text(data.timestamp);
                jQuery('#getbattery_edit #batt_tasks').text(data.tasksnew);
                jQuery('#getbattery_edit #batt_tyre_type').text(data.tyre_type);
                jQuery('#getbattery_edit #batt_veh_no').text(data.vehicleno);
                jQuery('#getbattery_edit #batt_status').text(data.statusname);
                jQuery('#getbattery_edit #batt_category').text(data.category);
                jQuery('#getbattery_edit #category_id').val(data.categoryid);
                jQuery('#getbattery_edit #batt_transid').text(data.transid);
                jQuery('#getbattery_edit #batt_submission_date').text(data.submission_date);
                jQuery('#getbattery_edit #batt_veh_branch').text(data.groupname);
                jQuery('#getbattery_edit #batt_veh_odometer').text(data.odometer);
                jQuery('#getbattery_edit #batt_meter_reading').text(data.meter_reading);
                jQuery('#getbattery_edit #batt_dealer').text(data.dealername);
                jQuery('#getbattery_edit #batt_vehicle_in_date').val(data.vehicle_in_date);
                jQuery('#getbattery_edit #batt_vehicle_out_date').val(data.vehicle_out_date);
                jQuery('#getbattery_edit #batt_amount_quote').text(data.amount_quote);
                jQuery('#getbattery_edit #batt_invoice_date').val(data.invoice_date);
                jQuery('#getbattery_edit #batt_invoice_no').val(data.batt_invoice_no);
                jQuery('#getbattery_edit #batt_amount_invoice').val(data.invoice_amount);
                jQuery('#getbattery_edit #batt_notes').text(data.notes);
                jQuery('#maintenance_edit_id').val(maintenanceid);
                jQuery('#edit_vehicle_id').val(vehicleid);
                checkfilename(maintenanceid, 0, 'quote', data.quote_file_name, 'battery', data.vehicleid, data.customerno);
                checkfilename(maintenanceid, 0, 'invoice', data.invoice_file_name, 'battery', data.vehicleid, data.customerno);
                jQuery('#editbattery').modal('show');
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }

}

function print_battery_closed(maintenanceid, vehicleid) {
    window.open('pdftest.php?report=transaction&vehicleid=' + vehicleid + '&maintenanceid=' + maintenanceid, '_blank')
}

function print_accident_closed(maintenanceid, vehicleid) {
    window.open('pdftest.php?report=accident&vehicleid=' + vehicleid + '&maintenanceid=' + maintenanceid, '_blank')
}

function get_accident(maintenanceid, vehicleid)
{
    if (maintenanceid != '') {

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {acc_maintenanceid: maintenanceid},
            dataType: "json",
            success: function (data) {
                jQuery('#edit_close_accident').show();
                jQuery('#getaccident_edit #ofasnumberdiv').show();
                jQuery('#getaccident_edit #accident_ofasnumber_closed').hide();
                jQuery('#getaccident_edit #acc_veh_no').text(data.vehicleno);
                jQuery('#getaccident_edit #acc_mahindra_amount').text(data.mahindra_amount);
                jQuery('#getaccident_edit #acc_estimated_loss').text(data.loss_amount);
                jQuery('#getaccident_edit #acc_settlement_amount').text(data.sett_amount);
                jQuery('#getaccident_edit #acc_repair_amount').text(data.actual_amount);
                jQuery('#getaccident_edit #acc_report').text(data.send_report);
                jQuery('#getaccident_edit #acc_workshop').text(data.add_workshop);
                jQuery('#getaccident_edit #acc_description').text(data.acc_desc);
                jQuery('#getaccident_edit #acc_tpi').text(data.tpi);
                jQuery('#getaccident_edit #acc_location').text(data.acc_location);
                jQuery('#getaccident_edit #acc_time').text(data.STime);
                jQuery('#getaccident_edit #acc_date').text(data.acc_Date);
                jQuery('#getaccident_edit #acc_category').text("Accident");
                jQuery('#getaccident_edit #acc_trans_app_date').text(data.approval_date);
                jQuery('#getaccident_edit #acc_transid').text(data.transid);
                jQuery('#getaccident_edit #acc_approval_note').text(data.approval_notes);
                jQuery('#getaccident_edit #acc_veh_branch').text(data.groupname);
                jQuery('#getaccident_edit #acc_veh_odometer').text(data.odometer);
                jQuery('#getaccident_edit #acc_drivername').text(data.drivername);
                jQuery('#getaccident_edit #acc_driver_lic_from').text(data.val_from_Date);
                jQuery('#getaccident_edit #acc_driver_lic_to').text(data.val_to_Date);
                jQuery('#getaccident_edit #acc_license_type').text(data.licence_type);
                jQuery("#accident_files_view").empty();
                jQuery(data.files).each(function (index, obj) {

                    jQuery("#accident_files_view").append('<a href="' + obj.url.toString() + '" target="_blank">' + obj.name.toString() + '</a><br/>');
                });
                jQuery('#edit_close_accident').attr({onclick: 'editaccident(' + maintenanceid + ')'});
                jQuery('#editaccident').modal('show');
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }
}

function get_accident_closed(maintenanceid, vehicleid)
{
    if (maintenanceid != '') {
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {acc_maintenanceid: maintenanceid},
            dataType: "json",
            success: function (data) {
                jQuery('#getaccident_edit #acc_veh_no').text(data.vehicleno);
                jQuery('#getaccident_edit #acc_mahindra_amount').text(data.mahindra_amount);
                jQuery('#getaccident_edit #acc_estimated_loss').text(data.loss_amount);
                jQuery('#getaccident_edit #acc_settlement_amount').text(data.sett_amount);
                jQuery('#getaccident_edit #acc_repair_amount').text(data.actual_amount);
                jQuery('#getaccident_edit #acc_report').text(data.send_report);
                jQuery('#getaccident_edit #acc_workshop').text(data.add_workshop);
                jQuery('#getaccident_edit #acc_description').text(data.acc_desc);
                jQuery('#getaccident_edit #acc_tpi').text(data.tpi);
                jQuery('#getaccident_edit #acc_location').text(data.acc_location);
                jQuery('#getaccident_edit #acc_time').text(data.STime);
                jQuery('#getaccident_edit #acc_date').text(data.acc_Date);
                jQuery('#getaccident_edit #acc_category').text("Accident");
                jQuery('#getaccident_edit #acc_trans_app_date').text(data.approval_date);
                jQuery('#getaccident_edit #acc_approval_note').text(data.approval_notes);
                jQuery('#getaccident_edit #acc_transid').text(data.transid);
                jQuery('#getaccident_edit #acc_veh_branch').text(data.groupname);
                jQuery('#getaccident_edit #acc_veh_odometer').text(data.odometer);
                jQuery('#getaccident_edit #acc_drivername').text(data.drivername);
                jQuery('#getaccident_edit #acc_driver_lic_from').text(data.val_from_Date);
                jQuery('#getaccident_edit #acc_driver_lic_to').text(data.val_to_Date);
                jQuery('#getaccident_edit #acc_license_type').text(data.licence_type);
                jQuery('#getaccident_edit #accident_ofasnumber_closed').show();
                jQuery('#getaccident_edit #accident_ofasnumber_value').text(data.ofasnumber);
                jQuery("#accident_files_view").empty();
                jQuery(data.files).each(function (index, obj) {
                    jQuery("#accident_files_view").append('<a href="' + obj.url.toString() + '" target="_blank">' + obj.name.toString() + '</a><br/>');
                });
                jQuery('#edit_close_accident').hide();
                jQuery('#getaccident_edit #ofasnumberdiv').hide();
                jQuery('#editaccident').modal('show');
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }
}

function checkfilename(maintenanceid, category, type, filename, transaction, vehicleid, customerno) {
    if (filename == "" || filename == '0')
    {
        filename = "undefined";
    }
    var url = '../../customer/' + customerno + '/vehicleid/' + vehicleid + '/' + filename;
    jQuery.ajax({
        type: "HEAD",
        url: url,
        success: function (data) {
            jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("");
            jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("<a href='download.php?download_file=" + filename + "&vid=" + vehicleid + "&customerno=" + customerno + "'>Download " + type + " file here</a>");
        },
        error: function (request, status) {
            jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("");
            jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("No " + type + " file Uploaded");
        }
    });
}

function daydiff(first, second) {
    return (second - first) / (1000 * 60 * 60 * 24)
}
function parseDate(str) {
    var mdy = str.split('-')
    return new Date(mdy[2], mdy[1], mdy[0] - 1);
}

function editbattery(maintenanceid, vehicleid, statusid, categoryid, quote_amount)
{
    var new_batt = '';
    var old_rf = jQuery("#oright_front").val();
    var new_rf = jQuery("#nright_front").val();
    var new_rb_out = jQuery("#nright_back_out").val();
    var old_rb_out = jQuery("#oright_back_out").val();
    var new_rb_in = jQuery("#nright_back_in").val();
    var old_rb_in = jQuery("#oright_back_in").val();
    var new_lb_out = jQuery("#nleft_back_out").val();
    var old_lb_out = jQuery("#oleft_back_out").val();
    var new_lf = jQuery("#nleft_front").val();
    var old_lf = jQuery("#oleft_front").val();
    var new_lb_in = jQuery("#nleft_back_in").val();
    var old_lb_in = jQuery("#oleft_back_in").val();
    var new_st = jQuery("#nstepney").val();
    var old_st = jQuery("#ostepney").val();
    var tyre_repairname = jQuery("#tyre_repair").html();
    var customerno = jQuery("#cno").val();
    var meter_reading = jQuery('#getbattery_edit #batt_meter_reading').text();
    //alert(statusid);
    var get_string = '';
    //alert("test");
    if (files_invoice_name)
    {

    }
    else
    {
        files_invoice_name = '';
    }
    var getin = false;
    if (statusid == 11)
    {
        getin = true;
    }
    if (statusid == 10 || categoryid == 5)
    {
        var from_date = jQuery("#batt_vehicle_in_date").val();
        var to_date = jQuery("#batt_vehicle_out_date").val();
        var batt_invoice_no = jQuery("#batt_invoice_no").val();
        var batt_amount_quote = jQuery("#batt_amount_quote").val();
        //
        var datediff = daydiff(parseDate(from_date), parseDate(to_date));
        if (jQuery("#batt_vehicle_in_date").val() == "")
        {
            jQuery("#vid_error").show();
            jQuery("#vid_error").fadeOut(6000);
            getin = false;
        }
        else if (jQuery("#batt_vehicle_out_date").val() == "")
        {
            jQuery("#vod_error").show();
            jQuery("#vod_error").fadeOut(6000);
            getin = false;
        }
        else if (jQuery("#batt_invoice_date").val() == "")
        {
            jQuery("#id_error").show();
            jQuery("#id_error").fadeOut(6000);
            getin = false;
        }
        else if (jQuery("#batt_invoice_no").val() == "") {
            if (customerno == '118' && statusid == '10') {
                getin = true;
            }
            else {
                jQuery("#in_error").show();
                jQuery("#in_error").fadeOut(6000);
                getin = false;
            }
        }
        else if (jQuery("#batt_amount_invoice").val() == "")
        {
            jQuery("#ia_error").show();
            jQuery("#ia_error").fadeOut(6000);
            getin = false;
        }
        else if (jQuery("#batt_amount_invoice").val() > quote_amount && categoryid != 2 && (statusid == 10 || categoryid == 5))
        {
            alert(jQuery("#batt_amount_invoice").val() + 'quote' + quote_amount);
            return false;
            jQuery("#ia_q_error").show();
            jQuery("#ia_q_error").fadeOut(6000);
            getin = false;
        }
        else if (datediff < 0)
        {
            jQuery("#datediff_error").show();
            jQuery("#datediff_error").fadeOut(3000);
            getin = false;
        } else
        {
            getin = true;
        }
//        else if (jQuery("#ofasnumber").val() == "" && categoryid == 5)
//        {
//            jQuery("#ofas_error").show();
//            jQuery("#ofas_error").fadeOut(6000);
//            getin = false;
//        }

    }
    if (statusid == 14 && categoryid != 5)
    {
        if (customerno == '118' && jQuery("#batt_invoice_no").val() == "") {
            jQuery("#in_error").show();
            jQuery("#in_error").fadeOut(6000);
            getin = false;
        }
        else if (customerno == '118' && jQuery("#batt_amount_invoice").val() == "0.00") {
            jQuery("#ia_error").show();
            jQuery("#ia_error").fadeOut(6000);
            getin = false;
        }
        else if (jQuery("#ofasnumber").val() == "")
        {
            jQuery("#ofas_error").show();
            jQuery("#ofas_error").fadeOut(6000);
            getin = false;
        }
        else
        {
            getin = true;
        }
    }
    if (statusid == 10 && categoryid == 0) {
        new_batt = jQuery("#new_battsrno").val();
        //alert(new_batt);
    }
    if (statusid == 10 && categoryid == 1 && tyre_repairname == 'New') {
        var selected = [];
        jQuery(".chk").each(function () {
            var chkbox = jQuery(this);
            if (chkbox.attr('checked')) {
                selected.push(jQuery(this).attr('name'));
            }
        });
        if (selected.length === 0) {
            jQuery("#chk_error").show();
            jQuery("#chk_error").fadeOut(6000);
            getin == false;
            return false;
        }
        else {
            getin == true;
        }
        var oldtyresrno_list = [];
        var newtyresrno_list = [];
        if (jQuery("#rf").attr("checked"))
        {
            oldtyresrno_list.push("Right Front-" + old_rf);
            newtyresrno_list.push("Right Front-" + new_rf);
        }
        if (jQuery("#rb_out").attr("checked")) {
            oldtyresrno_list.push("Right Back Out-" + old_rb_out);
            newtyresrno_list.push("Right Back Out-" + new_rb_out);
        }
        if (jQuery("#rb_in").attr("checked")) {
            oldtyresrno_list.push("Right Back In-" + old_rb_in);
            newtyresrno_list.push("Right Back In-" + new_rb_in);
        }
        if (jQuery("#lf").attr("checked")) {
            oldtyresrno_list.push("Left Front-" + old_lf);
            newtyresrno_list.push("Left Front-" + new_lf);
        }
        if (jQuery("#lb_out").attr("checked")) {
            oldtyresrno_list.push("Left Back Out-" + old_lb_out);
            newtyresrno_list.push("Left Back Out-" + new_lb_out);
        }
        if (jQuery("#lb_in").attr("checked")) {
            oldtyresrno_list.push("Left Back In-" + old_lb_in);
            newtyresrno_list.push("Left Back In-" + new_lb_in);
        }
        if (jQuery("#st").attr("checked")) {
            oldtyresrno_list.push("Stepney-" + old_st);
            newtyresrno_list.push("Stepney-" + new_st);
        }
        get_string += "&oldtyre_list=" + oldtyresrno_list.toString() + "";
        get_string += "&newtyre_list=" + newtyresrno_list.toString() + "";
    }
    if (getin == true)
    {
        var data = jQuery('#getbattery_edit').serialize() + '&vehicle_meter_reading=' + meter_reading + '&edit_batt_vehicleid=' + vehicleid + '&categoryid=' + categoryid + '&maintenanceid=' + maintenanceid + '&status_maintenance=' + statusid + '&new_battsrno=' + new_batt + '&file_invoice_name=' + files_invoice_name + get_string;

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck.trim() === "notok") {

                }
                else if (statuscheck.trim() === "ok") {
                    window.location.href = "transaction.php?id=2";
                }
            }
        });
        return false;
    }
}

function editaccident(maintenanceid)
{
    if (jQuery("#acc_ofasnumber").val() == "")
    {
        jQuery("#acc_ofas_error").show();
        jQuery("#acc_ofas_error").fadeOut(6000);
    }
    else
    {
        var data = jQuery('#getaccident_edit').serialize() + '&editacc_maintainanceid=' + maintenanceid;
        //alert(maintenanceid);
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "notok") {
                }
                else if (statuscheck == 'ok') {
                    jQuery('#editaccident').modal('hide');
                    //location.reload();   
                    window.location.href = "transaction.php?id=2";
                }
            }
        });
        return false;
    }
}

function clickdriver() {
    jQuery('#Dealer').modal('show');
    jQuery("#header-4").html('Add Dealer');
}


// Variable to store your files
var fileupload1;
var fileupload2;
// Add events
jQuery('#file1').on('change', prepareUpload1);
jQuery('#file2').on('change', prepareUpload2);
// Grab the files and set them to our variable
function prepareUpload1(event)
{
    //fileupload1 = null;
    fileupload1 = event.target.files;
}

function prepareUpload2(event)
{
    //fileupload2 = null;
    fileupload2 = event.target.files;
}

function upload_file_delaer1(event, id)
{

    if (jQuery('#other1').val() != '')
    {

        var file1 = jQuery('#other1').val();
        var datafile = new FormData();
        jQuery.each(fileupload1, function (key, value)
        {
            datafile.append(key, value);
        });
        fileupload1 = null;
        //fileupload1 = null;
        jQuery.ajax({
            url: 'upload.php?dealerid=' + id + '&filename=' + file1 + "&dealerfile1",
            type: 'POST',
            cache: false,
            data: datafile,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false,
            success: function (datafile, textStatus, jqXHR)
            {
                if (typeof datafile.error === 'undefined')
                {
                    jQuery("#upload_puc").val('Upload Successful');
                    jQuery("#upload_puc").attr('disabled', 'disabled');
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + datafile.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }



}


function upload_file_delaer2(event, id)
{

    if (jQuery('#other2').val() != '')
    {
        var file2 = jQuery('#other2').val();
        var datafile2 = new FormData();
        jQuery.each(fileupload2, function (key, value)
        {
            datafile2.append(key, value);
        });
        fileupload2 = null;
        //fileupload1 = null;
        jQuery.ajax({
            url: 'upload.php?dealerid=' + id + '&filename2=' + file2 + "&dealerfile2",
            type: 'POST',
            cache: false,
            data: datafile2,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false,
            success: function (datafile2, textStatus, jqXHR)
            {
                if (typeof datafile2.error === 'undefined')
                {
                    jQuery("#upload_puc").val('Upload Successful');
                    jQuery("#upload_puc").attr('disabled', 'disabled');
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + datafile2.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }



}

function adddealer(event) {
    //alert("test");
    var name = jQuery("#name").val();
    var phoneno = jQuery("#phoneno").val();
    var cellphone = jQuery("#cellphone").val();
    var notes = jQuery("#notes").val();
    var address = jQuery("#address").val();
    var other1 = jQuery("#other1").val();
    if (jQuery("#cheirarchy") == '1')
    {
        var city = jQuery("#cityid").val();
    }
    var vendor = jQuery("#vendor").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (phoneno == '') {
        jQuery("#phoneno_error").show();
        jQuery("#phoneno_error").fadeOut(3000);
    }
    else if (cellphone == '') {
        jQuery("#cellphone_error").show();
        jQuery("#cellphone_error").fadeOut(3000);
    }
    else if (notes == '') {
        jQuery("#note_error").show();
        jQuery("#note_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else if (city == '' && jQuery("#cheirarchy") == '1') {
        jQuery("#city_error").show();
        jQuery("#city_error").fadeOut(3000);
    }
    else if (vendor == '') {
        jQuery("#vendor_error").show();
        jQuery("#vendor_error").fadeOut(3000);
    }
    else if (jQuery("#phoneno").val().replace(/[^a-z]/g, "").length > 0)
    {
        jQuery("#edit_amount_error").show();
        jQuery("#edit_amount_error").fadeOut(3000);
    }
    else if (jQuery("#cellphone").val().replace(/[^a-z]/g, "").length > 0)
    {
        jQuery("#edit_amount_error").show();
        jQuery("#edit_amount_error").fadeOut(3000);
    }
    else if (jQuery('#file1').val() != '' && jQuery('#other1').val() == '')
    {
        jQuery("#upload1_error").show();
        jQuery("#upload1_error").fadeOut(3000);
    }
    else if (jQuery('#file2').val() != '' && jQuery('#other2').val() == '')
    {
        jQuery("#upload2_error").show();
        jQuery("#upload2_error").fadeOut(3000);
    }

    else {




        var data = jQuery('#adddealer').serialize();
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (jQuery('#file1').val() != '')
                {
                    upload_file_delaer1(event, json);
                }
                if (jQuery('#file2').val() != '')
                {
                    upload_file_delaer2(event, json);
                }

                //window.location.href = "dealer.php?id=2";
                if (jQuery('#file1').val() != '' || jQuery('#file2').val() != '')
                {
                    jQuery("#adddealerbtn").val('Dealer Added Successful');
                    jQuery("#adddealerbtn").attr('disabled', 'disabled');
                    //window.location.href = "dealer.php?id=2";
                    if (json != '') {
                        var select = $("#dealerid");
                        select.append($("<option selected>").val(json).text(name));
                        jQuery('#Dealer').modal('hide');
                    }
                }
                else
                {
                    jQuery("#adddealerbtn").val('Dealer Added Successful');
                    jQuery("#adddealerbtn").attr('disabled', 'disabled');
                    if (json != '') {
                        var select = $("#dealerid");
                        select.append($("<option selected>").val(json).text(name));
                        jQuery('#Dealer').modal('hide');
                    }
                }

            }
        });
        return false;
    }
}

// Variable to store your files
var fileupload1;
var fileupload2;
// Add events
jQuery('#file1').on('change', prepareUpload1);
jQuery('#file2').on('change', prepareUpload2);
// Grab the files and set them to our variable
function prepareUpload1(event)
{
    //fileupload1 = null;
    fileupload1 = event.target.files;
}

function prepareUpload2(event)
{
    //fileupload2 = null;
    fileupload2 = event.target.files;
}

function upload_file_delaer1(event, id)
{
    //alert("test");
    if (jQuery('#other1').val() != '')
    {

        var file1 = jQuery('#other1').val();
        var datafile = new FormData();
        jQuery.each(fileupload1, function (key, value)
        {
            datafile.append(key, value);
        });
        fileupload1 = null;
        //fileupload1 = null;
        jQuery.ajax({
            url: 'upload.php?dealerid=' + id + '&filename=' + file1 + "&dealerfile1",
            type: 'POST',
            cache: false,
            data: datafile,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false,
            success: function (datafile, textStatus, jqXHR)
            {
                if (typeof datafile.error === 'undefined')
                {
                    jQuery("#upload_puc").val('Upload Successful');
                    jQuery("#upload_puc").attr('disabled', 'disabled');
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + datafile.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }



}

function upload_file_delaer2(event, id)
{
    //alert("test1");
    if (jQuery('#other2').val() != '')
    {
        var file2 = jQuery('#other2').val();
        var datafile2 = new FormData();
        jQuery.each(fileupload2, function (key, value)
        {
            datafile2.append(key, value);
        });
        fileupload2 = null;
        //fileupload1 = null;
        jQuery.ajax({
            url: 'upload.php?dealerid=' + id + '&filename2=' + file2 + "&dealerfile2",
            type: 'POST',
            cache: false,
            data: datafile2,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false,
            success: function (datafile2, textStatus, jqXHR)
            {
                if (typeof datafile2.error === 'undefined')
                {
                    jQuery("#upload_puc").val('Upload Successful');
                    jQuery("#upload_puc").attr('disabled', 'disabled');
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + datafile2.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }



}

function getRefilldate() {

    var fuel = jQuery("#fuel").val();
    var perday = jQuery("#perday").val();
    var avg = jQuery("#avg").val();
    var avg1 = Math.ceil(avg);
    var SDate = jQuery('#SDate').val();
    var numbers = SDate.match(/\d+/g); // done to convert dd-mm-yyyy to standard date of javascript
    var date = new Date(numbers[2], numbers[1] - 1, numbers[0]); // done to convert dd-mm-yyyy to standard date of javascript
    if (fuel != '' && perday != '' && avg != '')
    {
        var refill = parseInt((avg1 * fuel) / perday);
        var result = new Date(date);
        var newdate = result.setDate(result.getDate() + refill);
        var date1 = new Date(newdate);
        var dd = date1.getDate();
        var mm = date1.getMonth() + 1;
        var y = date1.getFullYear();
        var someFormattedDate = dd + '-' + mm + '-' + y;
        jQuery("#refilldate").val(someFormattedDate);
    }
}

function CreateDataTable(data, tableId, tableCols, pageLength) {

    var oTable = $('#' + tableId + '').DataTable({
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "No records found",
            "info": "Showing page _PAGE_ of _PAGES_ (_TOTAL_ out of _MAX_ transactions)",
            //"infoEmpty": "Showing page _PAGE_ of _PAGES_ of _MAX_ transactions",
            "infoEmpty": "Unable to find transactions",
            //"infoFiltered": "(filtered from _MAX_ total records)"
            "infoFiltered": ""
        },
//        "columnDefs": [
//            {
//                targets: 0,
//                orderable: false,
//                searchable: false,
//                className: 'dt-body-center',
//                render: function (data, type, full, meta) {
//                    return '<input type="checkbox" class="call-checkbox" value="' + $('<div/>').text(data).html() + '">';
//                }
//            }
//        ],
        "select": {
            style: 'multi'
        },
        "dom": '<"top"ip>rt<"bottom"ip<"lengthMenu"l>><"clear">',
        "stateSave": true,
        "responsive": true,
        //"orderCellsTop": true,
        "destroy": true,
        "processing": true,
        "paging": true,
        "data": data,
        "columns": tableCols,
        "order": [],
        "pageLength": pageLength,
        "emptyTable": "No data found",
        "initComplete": function () {
            //TODO: To find replacement of legacy aoPreSearchCols 
            var oSettings = $('#' + tableId + '').dataTable().fnSettings();
            for (var i = 0; i < oSettings.aoPreSearchCols.length; i++) {
                if (oSettings.aoPreSearchCols[i].sSearch.length > 0) {
                    $("thead input")[i].value = oSettings.aoPreSearchCols[i].sSearch;
                    $("thead input")[i].className = "";
                }
            }
        }
    });
    //Add filter columns
    $("thead input").keyup(function () {
        oTable.columns($('#' + tableId + '' + ' thead input').index(this)).search($(this).val()).draw();
    });
    return oTable;
}

function showInvoiceDetails() {
    if (jQuery('#sent_pay').is(":checked"))
    {
        var category_id = '';
        var parts_list = [];
        var tasks_list = [];
        jQuery('#inv_data').show();
        jQuery("#file_for_quote").attr('disabled', true);
        jQuery("#amount_quote").attr('disabled', true);
        var tax = jQuery("#p_tax").val();
        if (jQuery("#category_type").val() == 3)
        {
            category_id = 3;
        }
        else {
            category_id = jQuery("#category_id").val();
        }

        if (category_id == 1 || category_id == 0) {
            var tax = parseFloat(jQuery("#p_tax").val()) || 0;
            tax = parseFloat(tax.toFixed(2));
            jQuery("#amount_invoice").val(tax);

        }
        if ((category_id == 2 || category_id == 3)) {
            var parts_total = 0;
            var tasks_total = 0;
            var inv_amt = 0;
            var parts_disc_amt = 0;
            var parts_tax_amt = 0;
            var tasks_disc_amt = 0;
            var tasks_tax_amt = 0;
            var tax = parseFloat(jQuery("#p_tax").val()) || 0;
            tax = parseFloat(tax.toFixed(2));
            for (var i = 1; i <= 50; i++) {
                if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
                {
                    parts_total += parseFloat(jQuery("#parts_amount" + i).val()) * parseFloat(jQuery("#parts_qty" + i).val());
                    parts_disc_amt += parseFloat(jQuery("#parts_discs" + i).val()) * parseFloat(jQuery("#parts_qty" + i).val());
                }
                if (jQuery("#tasks_select_" + i + "").val() != '-1' && (jQuery("#tasks_qty" + i + "").val() != undefined)) {
                    tasks_total += parseFloat(jQuery("#tasks_amount" + i + "").val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
                    tasks_disc_amt += parseFloat(jQuery("#tasks_discs" + i).val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
                }
            }
            //alert(tasks_total); 
            var tot = (parseFloat(parts_total) || 0) + (parseFloat(tasks_total) || 0);
            var totalamt = parts_total - parts_disc_amt;
            var tasksamt = tasks_total - tasks_disc_amt;
            var tot = (parseFloat(totalamt) || 0) + (parseFloat(tasksamt) || 0);
            inv_amt = tot + tax;
            inv_amt = parseFloat(inv_amt).toFixed(2);

            jQuery("#amount_invoice").val(inv_amt);
            jQuery("#amount_quote").val(inv_amt);
        }
    }
    else {
        jQuery('#inv_data').hide();
        jQuery("#file_for_quote").attr('disabled', false);
        jQuery("#amount_quote").attr('disabled', false);
    }
}

function setQuotation() {
    var amt = jQuery("#amount_invoice").val();
    jQuery("#amount_quote").val(amt);
}

function calculatetotaltax() {
    var taxamt = 0;
    for (i = 1; i <= 50; i++) {
        if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
        {
            taxamt += parseFloat(jQuery("#tasks_tax" + i).val());
        }



        if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
        {
            taxamt += parseFloat(jQuery("#parts_tot" + i).val());
        }
    }
    var tax = parseFloat(taxamt.toFixed(2));

    jQuery("#p_tax").val(tax);
}

function calculatetotal_parts(i) {
    var totamt = 0;
    var disc = 0;
    if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
    {
        var totamount = parseFloat(jQuery("#parts_amount" + i + "").val()) * parseFloat(jQuery("#parts_qty" + i + "").val());
        disc = parseFloat(jQuery("#parts_discs" + i + "").val()) * parseFloat(jQuery("#parts_qty" + i + "").val());
        if (totamount < disc) {
            alert("Discount amount should not be greater than amount");
            return false;
        }

        totamt += totamount - disc;
        var totamount = parseFloat(totamt.toFixed(2));
        jQuery("#parts_tot" + i).val(totamount);
    }
}

function calculatetotal_tasks(i) {
    var totamt = 0;
    var disc = 0;
    if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
    {
        var totamount = parseFloat(jQuery("#tasks_amount" + i + "").val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
        var disc = parseFloat(jQuery("#tasks_discs" + i + "").val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
        if (totamount < disc) {
            alert("Discount amount should not be greater than amount");
            return false;
        }

        totamt += totamount - disc;
        var totamount = parseFloat(totamt.toFixed(2));
        jQuery("#tasks_tot" + i).val(totamount);
    }
}


function transaction_cancelled(id) {
    //alert(id); 
    var result = "";
    result = confirm("Are you sure you want to cancelled this transaction");
    if (result == true) {
        var dataresult = "work=cancelled_transaction&transid=" + id;

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: dataresult,
            success: function (statuscheck) {
                if (statuscheck == "notok") {
                    return false;
                    location.reload();
                }
                else if (statuscheck == 'ok') {
                    location.reload();
                }
            }
        });
        return true;
    } else {
        return false;
    }
}

function transaction_rollback(id) {
    //alert(id); 
    var result = "";
    result = confirm("Are you sure you want to rollback this transaction");
    if (result == true) {
        var dataresult = "work=rollback_transaction&transid=" + id;

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: dataresult,
            success: function (statuscheck) {
                if (statuscheck.trim() == "notok") {
                    return false;
                    location.reload();
                }
                else if (statuscheck.trim() == 'ok') {
                    location.reload();
                }
            }
        });
        return true;
    } else {
        return false;
    }
}





jQuery('#file_for_quote').on('change', prepareUpload);
