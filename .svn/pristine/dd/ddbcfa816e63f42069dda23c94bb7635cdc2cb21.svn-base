jQuery(function () {
    jQuery('#ajaxBstatus').html('');
    jQuery('#editInvBuble').css({"visibility": "hidden"});
    jQuery('body').click(function () {
        jQuery('#ajaxBstatus').hide();
    });

    jQuery("input[name=cdob]").datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery("input[name=cannivrsry]").datepicker({format: "dd-mm-yyyy", autoclose: true});
    //jQuery("input[name=stockdate]").datepicker({format: "dd-mm-yyyy",autoclose:true});
    jQuery('#STime').val('00:00');
    jQuery('#srcode').change(function () {
        var srcd = jQuery(this).val();
        popultateDist(srcd);
    });

    jQuery('#srcodeentry').change(function (){
        var srcd = jQuery(this).val();
        popultateShopbysr(srcd);
    });

    jQuery('#distid').change(function () {
        var distid = jQuery(this).val();
        popultateArea(distid);
    });

    jQuery('#areaid').change(function () {
        var areaid = jQuery(this).val();
        popultateShop(areaid);
    });

    jQuery('#catid').change(function () {
        var catid = jQuery(this).val();
        popultateStyle(catid);
    });

    jQuery('#categoryid').change(function () {
        var catid = jQuery(this).val();
        popultateSku(catid);
    });

    jQuery('#distid1').change(function () {
        var distid = jQuery(this).val();
        popultateArea(distid);
        popultateDisttoShop(distid);
    });

});

function showBerror(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'red');
    jQuery("#ajaxBstatus").show();
}
function showBsuccess(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
}

function popultateStyle(catid) {
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getstyleid&catid=' + catid,
        success: function (result) {
            jQuery('#styleid').html(result);
        },
    });
}

function popultateSku(catid) {
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getstyleid&catid=' + catid,
        success: function (result) {
            jQuery('#skuid').html(result);
        },
    });
}

function popultateDist(srcd) {
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getdistid&srcd=' + srcd,
        success: function (result) {
            jQuery('#distid').html(result);
        },
    });
}

function popultateArea(distid) {
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getaid&distid=' + distid,
        success: function (result) {
            jQuery('#areaid').html(result);
        },
    });
}

function popultateShop(areaid) {
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getshopid&areaid=' + areaid,
        success: function (result) {
            jQuery('#shopid').html(result);
        },
    });
}

function popultateShopbysr(srid) {
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getshops&srcode=' + srid,
        success: function (result) {
            jQuery('#shopid').html(result);
        },
    });

}

function popultateDisttoShop(distid) {
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=distidbyshopdata&distid=' + distid,
        success: function (result) {
            jQuery('#shopid1').html(result);
        },
    });
}

function addentrydata(){
    var formdata = jQuery("#entryform").serialize();
    var srcode = jQuery("#srcodeentry").val();
    var shopid = jQuery("#shopid").val();
    if ((srcode == "" || srcode == '0') || (shopid == "" || shopid == '0')) {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=entry", 'entryform');
}

function addattendancedata(){
    var formdata = jQuery("#attendanceform").serialize();
    var srcode = jQuery("#srcodeentry").val();
    if ((srcode == "" || srcode == '0')) {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=addattendance", 'attendanceform');
}

function editattendancedata(){
    var formdata = jQuery("#editattendanceform").serialize();
    var srcode = jQuery("#srcode").val();
    if ((srcode == "" || srcode == '0')) {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=editattendance", 'editattendanceform');
}

function addorderdata() {
    var formdata = jQuery("#orderform").serialize();
    var srcode = jQuery("#srcode").val();
    var distid = jQuery("#distid").val();
    var areaid = jQuery("#areaid").val();
    var shopid = jQuery("#shopid").val();
    if ((srcode == "" || srcode == '0') || (distid == "" || distid == '0') || (areaid == "" || areaid == '0') || (shopid == "" || shopid == '0')) {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=addorder", 'orderform');
}

function editorder() {
    var formdata = jQuery("#editorderform").serialize();
    var srcode = jQuery("#srcode").val();
    var distid = jQuery("#distid").val();
    var areaid = jQuery("#areaid").val();
    var shopid = jQuery("#shopid").val();
    if ((srcode == "" || srcode == '0') || (distid == "" || distid == '0') || (areaid == "" || areaid == '0') || (shopid == "" || shopid == '0')) {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=editorder", 'editorderform');
}


function addstockdata() {
    var formdata = jQuery("#stockform").serialize();
    var srcode = jQuery("#srcode").val();
    var distid = jQuery("#distid").val();
    if ((srcode == "" || srcode == '0') || (distid == "" || distid == '0')) {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=stock", 'stockform');
}

function updatestockdata() {
    var formdata = jQuery("#stockeditform").serialize();
    var srcode = jQuery("#srcode").val();
    var distid = jQuery("#distid").val();
    if ((srcode == "" || srcode == '0') || (distid == "" || distid == '0')) {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=stockedit", 'ee');
}

function addprimarysales() {
    var formdata = jQuery("#primarysalesform").serialize();
    var srcode = jQuery("#srcode").val();
    var skuid = jQuery("#skuid").val();
    var distid = jQuery("#distid").val();
    var cartons = jQuery("#cartons").val();
    var categoryid = jQuery("#categoryid").val();
    if ((distid == "" || distid == '0') || cartons == "") {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=addprimary", 'primarysalesform');
}


function editprimarysales() {
    var formdata = jQuery("#editprimarysalesform").serialize();
    var srcode = jQuery("#srcode").val();
    var skuid = jQuery("#skuid").val();
    var distid = jQuery("#distid").val();
    var cartons = jQuery("#cartons").val();
    var categoryid = jQuery("#categoryid").val();
    if ((distid == "" || distid == '0') || cartons == "") {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=editprimary", 'primarysalesform');
}

function adddeadstock() {
    var formdata = jQuery("#deadstockform").serialize();
    var srcode = jQuery("#srcode").val();
    var distid = jQuery("#distid").val();
    var areaid = jQuery("#areaid").val();
    var shopid = jQuery("#shopid").val();
    if (distid == "" || distid == '0' || areaid == "" || shopid == "") {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=adddeadstock", 'deadstockform');
}

function editdeadstock() {
    var formdata = jQuery("#editdeadstockform").serialize();
    var srcode = jQuery("#srcode").val();
    var distid = jQuery("#distid").val();
    var areaid = jQuery("#areaid").val();
    var shopid = jQuery("#shopid").val();
    if ((distid == "" || distid == '0') || areaid == "" || shopid == "") {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=editdeadstock", 'editdeadstockform');
}




function addcategorydata() {
    var formdata = jQuery("#categoryform").serialize();
    if (is_empty('catname')) {
        show_error('Please enter Category Name.');
        return false;
    }
    ajax_request(formdata + "&action=category", 'categoryform');
}

function addshoptypedata() {
    var formdata = jQuery("#stypeform").serialize();
    if (is_empty('stypename')) {
        show_error('Please enter shop type.');
        return false;
    }
    ajax_request(formdata + "&action=stypeadd", 'stypeform');
}

function updatecategorydata() {
    var formdata = jQuery("#categoryeditform").serialize();
    if (is_empty('catname') || is_empty('catid')) {
        show_error('Please enter Category Name.');
        return false;
    }
    ajax_request(formdata + "&action=editcategory", 'ee');
}

function updateshdata() {
    var formdata = jQuery("#stypeeditform").serialize();
    if (is_empty('stypename') || is_empty('shid')) {
        show_error('Please enter Shop type.');
        return false;
    }
    ajax_request(formdata + "&action=editshtype", 'stypeeditform');
}

function addstyledata1(){
    var formdata1 = jQuery("#styleform").serialize();
    if (is_empty('styleno') || is_empty('category') || is_empty('mrp')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata1 + "&action=style", 'styleform');
}


function addstyledata(event){
    var formdata1 = jQuery("#styleform").serialize();
    if (is_empty('styleno') || is_empty('category') || is_empty('mrp')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    //ajax_request(formdata1 + "&action=style", 'styleform');
    var data = formdata1 +"&action=style";
    var fid = 'styleform';
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                var skuid = obj.skuid;
                
                if (jQuery('#imgupload').val() != '')
                {
                    uploadsku(event,skuid);
                }
                show_success(obj.Msg, fid);
                //show_success(obj.Msg, fid);
            } else {
                show_error(obj.Error);
            }
        },
        complete: function () {
            jQuery('#pageloaddiv').hide();
        }
    });
    
    
}

// Variable to store your files
var fileupload1;
jQuery('#imgupload').on('change', prepareUpload);
function prepareUpload(event)
{
    fileupload1 = event.target.files;
}


function uploadsku(event,id){
    if (jQuery('#imgupload').val() != '')
    {
        var file1 = jQuery('#imgupload').val();
        var datafile = new FormData();
        jQuery.each(fileupload1, function (key, value)
        {
            datafile.append(key, value);
        });
        fileupload1 = null;
        //fileupload1 = null;
        jQuery.ajax({
            url: 'upload.php?skuid=' + id + '&filename=sku'+id+ "&skuimage",
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
                   // jQuery("#upload_puc").val('Upload Successful');
                  //  jQuery("#upload_puc").attr('disabled', 'disabled');
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


function updatestyledata1() {
    var formdata = jQuery("#styleeditform").serialize();
    if (is_empty('styleid') || is_empty('styleno') || is_empty('category') || is_empty('mrp')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=styleedit", 'styleeditform');
}

function updatestyledata(event){
    var formdata = jQuery("#styleeditform").serialize();
    if (is_empty('styleid') || is_empty('styleno') || is_empty('category') || is_empty('mrp')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    //ajax_request(formdata + "&action=styleedit", 'styleeditform');
    var data = formdata +"&action=styleedit";
    var fid = 'styleeditform';
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success"){
                var skuid = obj.skuid;
                if (jQuery('#imgupload').val() != '')
                {
                    //var charCode = (evt.which) ? evt.which : evt.keyCode;
                    uploadsku(event,skuid);
                }
                show_success(obj.Msg, fid);
                //show_success(obj.Msg, fid);
            } else {
                show_error(obj.Error);
            }
        },
        complete: function () {
            jQuery('#pageloaddiv').hide();
        }
    });
}


function addstatedata() {
    var formdata = jQuery("#stateform").serialize();
    if (is_empty('statename')) {
        show_error('Please enter State Name.');
        return false;
    }
    ajax_request(formdata + "&action=state", 'stateform');
}

function updatestatedata() {
    var formdata = jQuery("#stateeditform").serialize();
    if (is_empty('statename') || is_empty('stateid')) {
        show_error('Please enter State Name.');
        return false;
    }
    ajax_request(formdata + "&action=editstate", 'ee');
}


function addasmdata() {
    var formdata = jQuery("#asmform").serialize();
    if (is_empty('statename')) {
        show_error('Please enter State Name.');
        return false;
    }
    ajax_request(formdata + "&action=asm", 'asmform');
}
//$asmid
function updateasmdata() {
    var formdata = jQuery("#asmeditform").serialize();
    if (is_empty('asmid') || is_empty('statename')) {
        show_error('Please enter State Name.');
        return false;
    }
    ajax_request(formdata + "&action=editasm", 'ee');
}


function addsalesdata() {
    var formdata = jQuery("#salesform").serialize();
    if (is_empty('srcode') || is_empty('srname') || is_empty('srphoneno')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=sales", 'salesform');
}

function updatesalesdata() {
    var formdata = jQuery("#saleseditform").serialize();
    if (is_empty('saleid') || is_empty('srcode') || is_empty('srname') || is_empty('srphoneno')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=editsales", 'ee');
}



function adddistdata() {
    var formdata = jQuery("#distributor").serialize();
    if (is_empty('saleid') || is_empty('distcode') || is_empty('distname')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=dist", 'distributor');
}


function updatedistdata() {
    var formdata = jQuery("#distributoredit").serialize();
    if (is_empty('distid') || is_empty('saleid') || is_empty('distcode') || is_empty('distname')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=editdist", 'ee');
}

function addareadata() {
    var formdata = jQuery("#areaform").serialize();
    if (is_empty('distid') || is_empty('areaname')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=area", 'areaform');
}

function updateareadata() {
    var formdata = jQuery("#areaeditform").serialize();
    if (is_empty('areaid') || is_empty('distid') || is_empty('areaname')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=editarea", 'ee');
}

function addshopdata() {
    var formdata = jQuery("#shopform").serialize();
    if (is_empty('distid') || is_empty('saleid') || is_empty('areaid') || is_empty('shopname')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=shop", 'shopform');
}

function updateshopdata() {
    var formdata = jQuery("#shopeditform").serialize();
    if (is_empty('sid') || is_empty('distid') || is_empty('saleid') || is_empty('areaid') || is_empty('shopname')) {
        show_error('Please fill mandatory fields.');
        return false;
    }
    ajax_request(formdata + "&action=editshop", 'ee');
}

function deletecategory(a) {
    var res = confirm("Are you sure you want delete this Category.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delcateogry", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deleteshoptype(a) {
    var res = confirm("Are you sure you want delete this Shop type.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delshtype", 'ff');
        location.reload();
    } else {
        return false;
    }
}


function deleteorder(a) {
    var res = confirm("Are you sure you want delete this Order.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delorder", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deletestyle(a) {
    var res = confirm("Are you sure you want delete this Style.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delstyle", 'ff');
        
        location.reload();
    } else {
        return false;
    }
}

function deletestock(a) {
    var res = confirm("Are you sure you want delete this Stock.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delstock", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deleteasm(a) {
    var res = confirm("Are you sure you want delete this ASM.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delasm", 'ff');
        location.reload();
    } else {
        return false;
    }
}
function deletestate(a) {
    var res = confirm("Are you sure you want delete this State.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delstate", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function delete_primaryorder(a) {
    var res = confirm("Are you sure you want delete this order.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delsales", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deletedist(a) {
    var res = confirm("Are you sure you want delete this Distributor.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=deldist", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deletearea(a) {
    var res = confirm("Are you sure you want delete this Area.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delarea", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deleteshop(a) {
    var res = confirm("Are you sure you want delete this Shop.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delshop", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function is_empty(name) {
    if (jQuery("input[name=" + name + "]").val() == '') {
        return true;
    }
    return false;
}
function ajax_request(data, fid) {
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                show_success(obj.Msg, fid);
            } else {
                show_error(obj.Error);
            }
        },
        complete: function () {
            jQuery('#pageloaddiv').hide();
        }
    });
}
function show_error(text) {
    jQuery("#ajaxstatus").html(text);
    jQuery("#ajaxstatus").css('color', 'red');
    jQuery("#ajaxstatus").show();
}
function show_success(text, fid) {
    jQuery("#ajaxstatus").html(text);
    jQuery("#ajaxstatus").css('color', 'green');
    jQuery("#ajaxstatus").show();
    jQuery('#' + fid + ' input,textarea').each(function () {
        if (jQuery(this).attr('type') != 'submit') {
            jQuery(this).val('');
        }
    });
}

function hideareas() {
    var typerep = jQuery("#typerep").val();
    if (typerep == 'PJPB') {
        jQuery("#hidetr").css('display', 'none');
    } else {
        jQuery("#hidetr").css('display', 'table-row');
    }
}

function approved_stock(a) {
    var res = confirm("Are you sure you want approve this stock?.");
    if (res == true) {
        jQuery('#pageloaddiv').show();
        ajax_request("&id=" + a + "&action=approve", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function reject_stock(a) {
    var res = confirm("Are you sure you want reject this stock?.");
    if (res == true) {
        jQuery('#pageloaddiv').show();
        ajax_request("&id=" + a + "&action=reject", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function getskubycategory(id) {
    var catid = jQuery('#category' + id).val();
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getstyleid&catid=' + catid,
        success: function (result) {
            jQuery('#sku' + id).html(result);
        },
    });
}

function getinventoryQty(id) {
    var skuid = jQuery('#sku' + id).val();
    var distid = jQuery('#distid').val();
    //alert(skuid+','+distid); return false;
    if (distid == '0') {
        show_error('Please select distributor');
        return false;
    }

    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=getinventorydata&skuid=' + skuid + '&distid=' + distid,
        success: function (result) {
            var data = JSON.parse(result);
            jQuery('#invqty'+id).val('');
            if(!data){
                   jQuery('#invqty'+id).val(0);
            }else{
                jQuery('#invqty'+id).val(data[0]['qty']);
            }
            
        },
    });
}

function addinventory() {
    var formdata = jQuery("#addinventoryform").serialize();
    var distid = jQuery("#distid").val();
    if (distid == "" || distid == '0') {
        show_error('Please select mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=addinventory", 'addinventoryform');
    //location.reload();
}


//edit inventory popup

function reset_inventorypopup() {
    jQuery("#editcategory").val('');
    jQuery("#editsku").val('');
    jQuery("#editqty").val('');
}


jQuery('.bubbleclose').click(function () {
    jQuery('#ajaxBstatus').html('');
    jQuery('#editInvBuble').css({"visibility": "hidden"});
    reset_inventorypopup();
});

jQuery('#addclient').click(function ()
{
    reset_inventorypopup();
    jQuery('#editInvBuble').css({"visibility": "visible"});
});

function editinvpopup(a) {
    reset_inventorypopup();
    jQuery("#editInvBuble").css({"visibility": "visible"});
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=geteditinventory&invid=' + a,
        success: function (result) {
            var data = JSON.parse(result);
            jQuery('#editcategory').val(data[0]['categoryname']);
            jQuery('#editsku').val(data[0]['sku']);
            jQuery('#editqty').val(data[0]['qty']);
            jQuery('#invid').val(data[0]['invid']);
        },
    });
}

function editinvpopupview() {

    var qty = jQuery('#editqty').val();
    var invid = jQuery('#invid').val();
    if (qty == "") {
        showBerror('Please enter some quantity.');
    }
    jQuery.ajax({url: "sales_ajax.php", type: 'POST', data: 'action=editinvqty&qty=' + qty + '&invid=' + invid,
        success: function (data) {
            var result = JSON.parse(data);
            if (result.Status == "Failure") {
                showBerror(result.Error);
                return false;
            }
            if (result.Status == "Success") {
                showBsuccess(result.Msg);
                reset_inventorypopup();
                location.reload();
            }
        },
    });
}
function deleteattendance(a) {
    var res = confirm("Are you sure you want delete this attendance record.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delattendance", 'ff');
        location.reload();
    } else {
        return false;
    }
}