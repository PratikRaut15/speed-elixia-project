jQuery(document).ready(function () {
    jQuery(".device_tr").hide();
    jQuery(".renewal_tr").hide();
    jQuery("#adv_tr").hide();
    jQuery(".other_tr").hide();
    jQuery("#ghtml").attr('disabled', true);
    jQuery("#tr_creditnote").hide();
    jQuery("#tr_renewalduration").hide();
    jQuery("#sdate_td").hide();
//    jQuery("#invgen_div").show();
//    jQuery("#invconfirm_div").show();
    showInvdetails();
    //getPodetails();
//    showDevice();
//    if (jQuery('input[name=invtype]:checked').val() == '0') {
//        showDevice();
//    } else {
//        getMappedveh();
//    }
    Calendar.setup(
            {
                inputField: "invdate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger1" // ID of the button
            });
    Calendar.setup(
            {
                inputField: "sdate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger2" // ID of the button
            });
    jQuery(".duration_class").click(function () {
        var selected_duration = jQuery(this).val();
        if (selected_duration == '0') {
            setCustomDuration();

        } else {
            getRenewalprice(selected_duration);
            getRenewalSub(selected_duration);
        }
    });

});

function setCustomDuration() {
    var custom = 0;
    jQuery("#duration_custom").keyup(function () {
        custom = jQuery(this).val();
        getRenewalprice(custom);
        getRenewalSub(custom);
    });
}
/*
 function getLedgername()
 {
 var custno = jQuery("#cno").val();
 jQuery.ajax({
 type: "POST",
 url: "invoice_ajax.php",
 cache: false,
 data: {
 work: "getLedgerByCust"
 , cid: custno
 },
 success: function (result) {
 var data = jQuery.parseJSON(result);
 //console.log(data);
 html_ledgerdetails(data);
 }
 });
 jQuery("#custno").val(custno);
 
 
 }
 */
function html_ledgerdetails(data)
{
    var detail = '';
    detail += "<option value=" + 0 + ">Select Ledger</option>";
    jQuery(data).each(function (i, v) {
        detail += "<option value=" + v.ledgerid + ">" + v.ledgerid + "-" + v.ledgername + "</option>";

    });
    jQuery("#ledger").html(detail);
}
function get_pdf()
{

    var cno = jQuery("#cno").val();
    var inid47 = jQuery("#icname").val();
    //alert(inid47);
    window.open('invoice_pdf.php?custno=' + cno + '&invid=' + inid + '_blank');
}
function DisplayAdv() {
    jQuery("#adv_tr").show();
}

function HideAdv() {
    jQuery("#adv_tr").hide();
}
function setInvno()
{
    var invoiceno = jQuery("#invno").val();
    jQuery("#in_no").val(invoiceno);
}
function setInvdate()
{
    var invoicedate = jQuery("#invdate").val();
    jQuery("#in_date").val(invoicedate);
}
function toSubmit() {
    if (confirm("Are You Sure?") == true) {
        //return true;
        save_invoicedata();
    } else {
        return false;
    }
}
function showInvdetails() {
    var ledger = jQuery("#ledger").val();
    var cno = jQuery("#cno").val();
    if (ledger == '0' || ledger == 'null') {
        jQuery("#error_ledger").show();
        jQuery("#error_ledger").fadeOut(3000);
    } else if (cno == '0') {
        jQuery("#error_cust").show();
        jQuery("#error_cust").fadeOut(3000);
    } else {
        jQuery("#invgen_div").show();
        jQuery("#invconfirm_div").show();
        //getMappedveh();
        getPodetails();
        if (jQuery('input[name=invtype]:checked').val() == '0') {
            showDevice();
        }
        //jQuery("#ledgerid").val(ledger);

    }
}

function getMappedveh()
{
    var selected_cust_grp = jQuery("#cno").val();
    var selected_ledgerid = jQuery("#ledger").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {
            work: "allotedveh",
            cust_no: selected_cust_grp,
            ledgerid: selected_ledgerid
        },
        success: function (res) {
            var map = jQuery.parseJSON(res);
            //console.log(map);
            var renewal_count = map.renewal.length;
            var lease_count = map.lease.length;
            if (jQuery("#vehicle_list").html().length > 0) {
                jQuery("#vehicle_list").html("");
            }
            if (jQuery("#lease_list").html().length > 0) {
                jQuery("#lease_list").html("");
            }
            if (jQuery('input[name=invtype]:checked').val() == '1') {
                printMapvehicle(map.renewal);
                jQuery("#quantity1").val(renewal_count);
            }
            if (jQuery('input[name=invtype]:checked').val() == '5') {
                printLeaseAllotedvehicle(map.lease);
                jQuery("#quantity1").val(lease_count);
            }
        }
    });
}
function printMapvehicle(map)
{
    var print = '';
    jQuery(map).each(function (i, v) {
        if (v.vehicleid > -1 && jQuery('#to_vehicle_div_' + v.vehicleid).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = '../../images/boxdelete.png';
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeVehicle(v.vehicleid);
            };
            div.className = 'recipientbox';
            div.id = 'to_vehicle_div_' + v.vehicleid;
            div.innerHTML = "<span>" + v.vehicleno + "</span><input type='hidden' class='v_list_element' name='to_vehicle_" + v.vehicleid + "' value= '" + v.vehicleid + "'/>";
            jQuery("#vehicle_list").append(div);
            jQuery(div).append(remove_image);
        }
    });
}
function printLeaseAllotedvehicle(map)
{
    var display = "#lease_list";
    jQuery(map).each(function (i, v) {
        if (v.vehicleid > -1 && jQuery('#to_vehicle_divlease_' + v.vehicleid).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = '../../images/boxdelete.png';
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeLeaseVehicle(v.vehicleid);
            };
            div.className = 'recipientbox';
            div.id = 'to_vehicle_divlease_' + v.vehicleid;
            div.innerHTML = "<span>" + v.vehicleno + "</span><input type='hidden' class='v_list_element' name='to_vehicle_" + v.vehicleid + "' value= '" + v.vehicleid + "'/>";
            jQuery('' + display + '').append(div);
            jQuery(div).append(remove_image);
        }
    });
}
function removeVehicle(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
    var totalveh = parseInt(jQuery("#quantity1").val()) - 1;
    jQuery("#quantity1").val(totalveh);
}

function removeLeaseVehicle(vehicleid) {
    jQuery('#to_vehicle_divlease_' + vehicleid).remove();
    var totalveh = parseInt(jQuery("#quantity1").val()) - 1;
    jQuery("#quantity1").val(totalveh);
}

function getPodetails() {
    var selected_cust_no = jQuery("#cno").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "getpo",
            cust_no: selected_cust_no
                    //acc_cust: selected_cust
        },
        success: function (res) {
            var data = jQuery.parseJSON(res);
            printPodetails(data);
        }
    });
}

function printPodetails(data)
{
    var detail = '';
    detail += "<option value=" + 0 + ">Select PO Option</option>";
    jQuery(data).each(function (i, v) {
        detail += "<option value=" + v.poid + ">" + v.pono + "-" + v.description + "</option>";

    });
    jQuery("#po").html(detail);
}

function showDevice() {
    var selected = jQuery("#devicetype").val();
    if (selected == '0') {
        jQuery(".device_tr").show();
        jQuery(".veh_tr").show();
        jQuery(".renewal_tr").hide();
        jQuery(".other_tr").hide();
        jQuery("#tr_creditnote").hide();
        jQuery("#tr_renewalduration").hide();
        jQuery(".tr_lease").hide();
        jQuery("#sdate_td").hide();
    } else {
        jQuery(".device_tr").hide();
    }
    getInvoiceNo(selected);
    getMappedvehnewdevice();

}
function showRenewal() {
    var selected = jQuery("#renewaltype").val();
    if (selected == '1') {
        var vehcount = 0;
        getMappedveh();

        jQuery(".renewal_tr").show();
        jQuery(".veh_tr").show();
        jQuery("#sdate_td").show();
        jQuery(".device_tr").hide();
        jQuery(".other_tr").hide();
        jQuery("#tr_creditnote").hide();
        jQuery("#tr_renewalduration").show();
        jQuery(".tr_lease").hide();
        getRenewalprice(-1);
    } else {
        jQuery(".renewal_tr").hide();
    }
    getInvoiceNo(selected);
    //getRenewalSub(-1);
    getRenewalDuration();
}

var rowCount = 3;
function addMoreRows(frm) {

    rowCount++;
    var recRow = '';
    recRow += '<table style="border:none;" id="rowCount' + rowCount + '" class="rowadded" ><tr>';
    recRow += '<td style="border:none;"><textarea name="description' + rowCount + '" rows="5" cols="30" size="60%" class="description" id="description' + rowCount + '"></textarea></td>';
    recRow += '<td style="border:none;"><input name="quantity' + rowCount + '" id="quantity' + rowCount + '" type="text" value="" size="25%"/></td>'
    recRow += '<td style="border:none;"><input name="price' + rowCount + '" id="price' + rowCount + '" type="text" value="" size="25%"/></td>'
    recRow += '<td style="border:none;"><a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');"><img src="../../images/hide.gif" alt="Delete"/></a><input name="inv_renewal' + rowCount + '" type="hidden" id="inv_renewal' + rowCount + '" class="inv_renewal"/></td></tr></table>';
    jQuery('#addedRows').append(recRow);
    if (rowCount > 4) {
        jQuery("#image_add").hide();
    }
}

function removeRow(removeNum) {
    jQuery('#rowCount' + removeNum).remove();
}

function getRenewalprice(duration) {
    var selected_cust_grp = jQuery("#cno").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "renewal_price",
            cust_grp: selected_cust_grp,
            duration: duration
        },
        success: function (res) {
            jQuery("#price1").val(res);
        }
    });
}

function getInvoiceNo(invtype) {
    var selected_cust_grp = jQuery("#cno").val();
    var ledger = jQuery("#ledger").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "get_invoiceno",
            cust_grp: selected_cust_grp,
            invtype: invtype,
            ledger: ledger
        },
        success: function (res) {
            jQuery("#invno").val(res);
        }
    });
}

function getRenewalSub(duration) {
    var selected_cust_grp = jQuery("#cno").val();
    var Sdate = jQuery("#sdate").val();
    var duration = jQuery('input[name=duration]:checked').val();
    if (duration == '0') {
        duration = jQuery("#duration_custom").val();

    }
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {
            work: "get_renewaltext",
            cust_grp: selected_cust_grp,
            duration: duration,
            sdate: Sdate
        },
        success: function (res) {
            jQuery("#description1").val(res);
        }
    });
}

function showOther() {
    var selected = jQuery("#othertype").val();
    if (selected == '2') {
        jQuery(".other_tr").show();
        jQuery(".device_tr").hide();
        jQuery(".renewal_tr").hide();
        jQuery("#tr_creditnote").hide();
        jQuery("#tr_renewalduration").hide();
        jQuery(".tr_lease").hide();
        jQuery("#sdate_td").hide();
    } else {
        jQuery(".other_tr").hide();
    }
    getInvoiceNo(selected);
}
rowCount = 1;
function addMoreRows_other() {
    rowCount++;
    var recRow = '';
    recRow += '<table style="border:none;" id="rowCountOther' + rowCount + '" class="rowadded" ><tr>';
    recRow += '<td style="border:none;"><textarea name="descriptionOther' + rowCount + '" rows="5" cols="30" size="60%" class="description" id="descriptionOther' + rowCount + '"></textarea></td>';
    recRow += '<td style="border:none;"><input name="quantityOther' + rowCount + '" id="quantityOther' + rowCount + '" type="text" value="" size="25%"/></td>'
    recRow += '<td style="border:none;"><input name="priceOther' + rowCount + '" id="priceOther' + rowCount + '" type="text" value="" size="25%"/></td>'
    recRow += '<td style="border:none;"><a href="javascript:void(0);" onclick="removeRowOther(' + rowCount + ');"><img src="../../images/hide.gif" alt="Delete"/></a><input name="inv_renewalOther' + rowCount + '" type="hidden" id="inv_renewalOther' + rowCount + '" class="inv_renewal"/></td></tr></table>';
    jQuery('#addedRowsOther').append(recRow);
    if (rowCount > 4) {
        jQuery("#image_add_other").hide();
    }
}

function removeRowOther(removeNum) {
    jQuery('#rowCountOther' + removeNum).remove();
}

function showPdf() {
    jQuery("#ghtml").attr('disabled', false);
}

function showCredit() {
    jQuery("#tr_creditnote").show();
    jQuery("#tr_renewalduration").hide();
    jQuery(".tr_lease").hide();
}

function showPro() {
    var selected = jQuery("#proratatype").val();
    if (selected == '4') {
        jQuery(".renewal_tr").show();
        jQuery(".device_tr").hide();
        jQuery(".other_tr").hide();
        jQuery("#tr_creditnote").hide();
        jQuery("#tr_renewalduration").hide();
        jQuery(".tr_lease").hide();
        //getRenewalprice();
        getProRataprice();
    } else {
        jQuery(".renewal_tr").hide();
    }
    getInvoiceNo(selected);
    getRenewalSub(-1);
}

function showCrDevice() {
    var selected = jQuery("#crdevicetype").val();
    if (selected == '0') {
        jQuery(".device_tr").show();
        jQuery(".renewal_tr").hide();
        jQuery(".other_tr").hide();
        jQuery("#sdate_td").hide();
    } else {
        jQuery(".device_tr").hide();
    }
    getInvoiceNo(3);

}
function showCrRenewal() {
    var selected = jQuery("#crrenewaltype").val();
    if (selected == '1') {
        jQuery(".renewal_tr").show();
        jQuery(".device_tr").hide();
        jQuery(".other_tr").hide();
        getRenewalprice();
        jQuery("#sdate_td").hide();
    } else {
        jQuery(".renewal_tr").hide();
    }
    getInvoiceNo(3);
    getRenewalSub();
}

function getProRataprice() {
    var selected_cust_grp = jQuery("#cno").val();
    var inv_date = jQuery("#invdate").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "prorata_price",
            cust_grp: selected_cust_grp,
            invdate: inv_date
        },
        success: function (res) {
            jQuery("#price1").val(res);
        }
    });
}

function showCusttext() {
    jQuery("#duration_custom").show();
}

function getRenewalDuration() {
    var selected_cust_grp = jQuery("#cno").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "renewal_duration",
            cust_grp: selected_cust_grp
        },
        success: function (res) {
            if (res == '12') {
                jQuery("#mon_12").attr('checked', true);
            } else if (res == '6') {
                jQuery("#mon_6").attr('checked', true);
            } else if (res == '3') {
                jQuery("#mon_3").attr('checked', true);
            } else {
                jQuery("#mon_1").attr('checked', true);
            }
        }
    });
}


function save_invoicedata() {
    var inv_data = jQuery("#gform").serialize();
    var data = inv_data + "&work=save_invoice";
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: data,
        success: function (res) {

        }
    });

}

function show_lease() {
    var selected = jQuery("#leasetype").val();
    if (selected == '5') {
        getMappedveh();
        jQuery(".renewal_tr").show();
        jQuery("#sdate_td").show();
        jQuery(".tr_lease").show();
        jQuery(".device_tr").hide();
        jQuery(".other_tr").hide();
        jQuery("#tr_creditnote").hide();
        jQuery("#tr_renewalduration").hide();
        jQuery(".veh_tr").hide();
        getLeaseprice();
    } else {
        jQuery(".renewal_tr").hide();
    }
    getInvoiceNo(selected);
    getLeaseSub();
    //getRenewalDuration();
}

function getLeaseSub() {
    var selected_cust_grp = jQuery("#cno").val();
    var Sdate = jQuery("#sdate").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {
            work: "get_leasetext",
            custno: selected_cust_grp,
            sdate: Sdate
        },
        success: function (res) {
            jQuery("#description1").val(res);
        }
    });
}

function getLeaseprice() {
    var selected_cust_grp = jQuery("#cno").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "getlease_price",
            custno: selected_cust_grp
        },
        success: function (res) {
            jQuery("#price1").val(res);
        }
    });
}

function getDescription() {
    //alert(jQuery('input[name=invtype]:checked').val());
    if (jQuery('input[name=invtype]:checked').val() == 1) {
        getRenewalSub(-1);
    } else if (jQuery('input[name=invtype]:checked').val() == 5) {
        getLeaseSub();
    }
}

function getMappedvehnewdevice()
{
    var selected_cust_grp = jQuery("#cno").val();
    var selected_ledgerid = jQuery("#ledger").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {
            work: "allotedvehnewdevice",
            cust_no: selected_cust_grp,
            ledgerid: selected_ledgerid
        },
        success: function (res) {
            var map = jQuery.parseJSON(res);
            //console.log(map);
            jQuery("#vehicle_list").html("");
            if (map == '0') {
                var status ='no new alloted vehicles';
                jQuery("#vehicle_list").append(status);
            } else {
                printMapvehicle(map);
            }


        }
    });
}