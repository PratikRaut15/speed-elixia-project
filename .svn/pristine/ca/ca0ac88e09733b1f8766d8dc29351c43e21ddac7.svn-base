//function onKey(evt) {
//	var theEvent = evt || window.event;
//	var key = theEvent.keyCode || theEvent.which;
//	if ((key == 13))
//		login();
//}
function getstate()
{
    var nation_id = jQuery("#nationid").val();
    if (jQuery("#nationid").val() == "")
    {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    }
    else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {nation_id: nation_id},
            dataType: 'html',
            success: function (html) {
                jQuery("#stateid").empty();
                jQuery("#stateid").append(html);
            }
        });
        return false;
    }
}
function getdistrict()
{
    var state_id = jQuery("#stateid").val();
    if (jQuery("#stateid").val() == "")
    {
        jQuery("#state_error").show();
        jQuery("#state_error").fadeOut(3000);
    }
    else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {state_id: state_id},
            dataType: 'html',
            success: function (html) {
                jQuery("#districtid").empty();
                jQuery("#districtid").append(html);
            }
        });
        return false;
    }
}
function getcity()
{
    var district_id = jQuery("#districtid").val();
    if (jQuery("#districtid").val() == "")
    {
        jQuery("#district_error").show();
        jQuery("#district_error").fadeOut(3000);
    }
    else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {district_id: district_id},
            dataType: 'html',
            success: function (html) {
                jQuery("#cityid").empty();
                jQuery("#cityid").append(html);
            }
        });
        return false;
    }
}
function getbranch()
{
    var city_id = jQuery("#cityid").val();
    if (jQuery("#cityid").val() == "")
    {
        jQuery("#city_error").show();
        jQuery("#city_error").fadeOut(3000);
    }
    else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {city_id: city_id},
            dataType: 'html',
            success: function (html) {
                jQuery("#branchid").empty();
                jQuery("#branchid").append(html);
            }
        });
        return false;
    }
}
function addnation() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#addnation').serialize();
        jQuery.ajax({
            url: "nation_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#nation_success").show();
                    jQuery("#nation_success").fadeOut(3000);
                    window.location.href = "nation.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
                }
            }
        });
        return false;
    }
}
function editnation() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#editnation').serialize();
        jQuery.ajax({
            url: "nation_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#nation_success").show();
                    jQuery("#nation_success").fadeOut(3000);
                    window.location.href = "nation.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
                }
            }
        });
        return false;
    }
}
function addstate() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    var nationid = jQuery("#nationid").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else if (nationid == '') {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#addstate').serialize();
        jQuery.ajax({
            url: "state_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#state_success").show();
                    jQuery("#state_success").fadeOut(3000);
                    window.location.href = "state.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
                }
            }
        });
        return false;
    }
}
function editstate() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    var nationid = jQuery("#nationid").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else if (nationid == '') {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#editstate').serialize();
        jQuery.ajax({
            url: "state_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#state_edit_success").show();
                    jQuery("#state_edit_success").fadeOut(3000);
                    window.location.href = "state.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
                }
            }
        });
        return false;
    }
}
function adddistrict() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    var stateid = jQuery("#stateid").val();
    var nationid = jQuery("#nationid").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else if (stateid == '') {
        jQuery("#state_error").show();
        jQuery("#state_error").fadeOut(3000);
    }
    else if (nationid == '') {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#adddistrict').serialize();
        jQuery.ajax({
            url: "district_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#district_success").show();
                    jQuery("#district_success").fadeOut(3000);
                    window.location.href = "district.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
                }
            }
        });
        return false;
    }
}
function editdistrict() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    var stateid = jQuery("#stateid").val();
    var nationid = jQuery("#nationid").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else if (stateid == '') {
        jQuery("#state_error").show();
        jQuery("#state_error").fadeOut(3000);
    }
    else if (nationid == '') {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#editdistrict').serialize();
        jQuery.ajax({
            url: "district_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#district_edit_success").show();
                    jQuery("#district_edit_success").fadeOut(3000);
                    window.location.href = "district.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
                }
            }
        });
        return false;
    }
}
function addcity() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    var districtid = jQuery("#districtid").val();
    var stateid = jQuery("#stateid").val();
    var nationid = jQuery("#nationid").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else if (districtid == '') {
        jQuery("#district_error").show();
        jQuery("#district_error").fadeOut(3000);
    }
    else if (stateid == '') {
        jQuery("#state_error").show();
        jQuery("#state_error").fadeOut(3000);
    }
    else if (nationid == '') {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#addcity').serialize();
        jQuery.ajax({
            url: "city_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#city_success").show();
                    jQuery("#city_success").fadeOut(3000);
                    window.location.href = "city.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
                }
            }
        });
        return false;
    }
}
function editcity() {
    var name = jQuery("#name").val();
    var code = jQuery("#code").val();
    var address = jQuery("#address").val();
    var districtid = jQuery("#districtid").val();
    var stateid = jQuery("#stateid").val();
    var nationid = jQuery("#nationid").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (code == '') {
        jQuery("#code_error").show();
        jQuery("#code_error").fadeOut(3000);
    }
    else if (address == '') {
        jQuery("#address_error").show();
        jQuery("#address_error").fadeOut(3000);
    }
    else if (districtid == '') {
        jQuery("#district_error").show();
        jQuery("#district_error").fadeOut(3000);
    }
    else if (stateid == '') {
        jQuery("#state_error").show();
        jQuery("#state_error").fadeOut(3000);
    }
    else if (nationid == '') {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#editcity').serialize();
        jQuery.ajax({
            url: "city_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#city_edit_success").show();
                    jQuery("#city_edit_success").fadeOut(3000);
                    window.location.href = "city.php?id=2";
                } else if (statuscheck == "notok") {
                } else if (statuscheck == "noemail") {
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
function readImage(input) {
    if (input.files && input.files[0]) {
        var FR = new FileReader();
        FR.onload = function (e) {
            jQuery('#base64img').val(e.target.result);
        };
        FR.readAsDataURL(input.files[0]);
    }
}
jQuery('#pickupboyphoto').on('change', function () {
    readImage(this);
});
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
    var name = jQuery("#name").val();
    var phoneno = jQuery("#phoneno").val();
    var cellphone = jQuery("#cellphone").val();
    var notes = jQuery("#notes").val();
    var address = jQuery("#address").val();
    var other1 = jQuery("#other1").val();
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
            url: "dealer_ajax.php",
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
                    //jQuery("#adddealerbtn").val('Dealer Added Successful');
                    //jQuery("#adddealerbtn").attr('disabled', 'disabled');
                    window.location.href = "dealer.php?id=2";
                }
                else
                {
                    // jQuery("#adddealerbtn").val('Dealer Added Successful');
                    //jQuery("#adddealerbtn").attr('disabled', 'disabled');  
                    window.location.href = "dealer.php?id=2";
                }
            }
        });
        return false;
    }
}
function editdealer(event) {
    var name = jQuery("#name").val();
    var phoneno = jQuery("#phoneno").val();
    var cellphone = jQuery("#cellphone").val();
    var notes = jQuery("#notes").val();
    var address = jQuery("#address").val();
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
        var data = jQuery('#editdealer').serialize();
        jQuery.ajax({
            url: "dealer_ajax.php",
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
                    //jQuery("#editdealerbtn").val('Dealer Edited Successful');
                    //jQuery("#editdealerbtn").attr('disabled', 'disabled');
                    window.location.href = "dealer.php?id=2";
                }
                else
                {
                    //jQuery("#editdealerbtn").val('Dealer Edited Successful');
                    //jQuery("#editdealerbtn").attr('disabled', 'disabled'); 
                    window.location.href = "dealer.php?id=2";
                }
            }
        });
        return false;
    }
}
function addcustomer() {
    var name = jQuery("#name").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    var address = jQuery("#address").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    /**
     else if (phoneno == '') {
     jQuery("#phoneno_error").show();
     jQuery("#phoneno_error").fadeOut(3000);
     }
     else if (email == '') {
     jQuery("#email_error").show();
     jQuery("#email_error").fadeOut(3000);
     }
     */
     else {
        var data = jQuery('#addcustomer').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "customer.php?id=2";
                }
                else {
                    jQuery("#fail_error").show();
                    jQuery("#fail_error").fadeOut(3000);
                }
            }
        });
        return false;
    }
}
function changepickup() {
    var id = jQuery("#pickupboyid").val();
    if (id == '' || id == '00') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    } else {
        var data = jQuery('#addorders').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "pick.php?id=3";
                } else {
                    jQuery("#fail_error").show();
                    jQuery("#fail_error").fadeOut(3000);
                }
            }
        });
        return false;
    }
}
function editcustomer() {
    var name = jQuery("#name").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    var address = jQuery("#address").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    /**
     else if (phoneno == '') {
     jQuery("#phoneno_error").show();
     jQuery("#phoneno_error").fadeOut(3000);
     }
     else if (email == '') {
     jQuery("#email_error").show();
     jQuery("#email_error").fadeOut(3000);
     }
     */
     else {
        var data = jQuery('#editcustomer').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "customer.php?id=2";
                }
                else {
                    jQuery("#fail_error").show();
                    jQuery("#fail_error").fadeOut(3000);
                }
            }
        });
        return false;
    }
}
function addvendor() {
    var name = jQuery("#vendorname").val();
    var company = jQuery("#vendorcompany").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    var address = jQuery("#address").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (company == '') {
        jQuery("#comp_error").show();
        jQuery("#comp_error").fadeOut(3000);
    }
    /**
     else if (phoneno == '') {
     jQuery("#phoneno_error").show();
     jQuery("#phoneno_error").fadeOut(3000);
     }
     
     else if (email == '') {
     jQuery("#email_error").show();
     jQuery("#email_error").fadeOut(3000);
     }
     */
     else {
        var final = new Array();
        var i = 0;
        jQuery('.ven').each(function (index, value) {
            var fid = this.id;
            var m = fid.replace('vendor_no_', '');
            final[i] = {'custno': m, 'val': this.value};
            i++;
        });
        final = JSON.stringify(final);
        var data = jQuery('#addvendor').serialize() + '&vmap=' + final;
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "vendor.php?id=2";
                }
                else {
                    jQuery("#fail_error").show();
                    jQuery("#fail_error").fadeOut(3000);
                }
            }
        });
        return false;
    }
}
function editvendor() {
    var name = jQuery("#vendorname").val();
    var company = jQuery("#vendorcompany").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    var address = jQuery("#address").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (company == '') {
        jQuery("#comp_error").show();
        jQuery("#comp_error").fadeOut(3000);
    }
    /**
     else if (phoneno == '') {
     jQuery("#phoneno_error").show();
     jQuery("#phoneno_error").fadeOut(3000);
     }
     else if (email == '') {
     jQuery("#email_error").show();
     jQuery("#email_error").fadeOut(3000);
     }
     */
     else {
        var final = new Array();
        var i = 0;
        jQuery('.ven').each(function (index, value) {
            var fid = this.id;
            var m = fid.replace('vendor_no_', '');
            final[i] = {'custno': m, 'val': this.value};
            i++;
        });
        final = JSON.stringify(final);
        var data = jQuery('#editvendor').serialize() + '&vmap=' + final;
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "vendor.php?id=2";
                }
                else {
                    jQuery("#fail_error").show();
                    jQuery("#fail_error").fadeOut(3000);
                }
            }
        });
        return false;
    }
}
function upload_file_pickupboy(id)
{
    if (jQuery('#other1').val() != '')
    {
        var datafile = new FormData();
        jQuery.each(fileupload1, function (key, value)
        {
            datafile.append(key, value);
        });
        fileupload1 = null;
        jQuery.ajax({
            url: 'uploadpickupboyimg.php?pickupid=' + id + "&pickupboyfile=1",
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
function addPickup() {
    var name = jQuery("#pickupname").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    var username = jQuery("#username").val();
    var password = jQuery("#password").val();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (username == '') {
        jQuery("#username_error").show();
        jQuery("#username_error").fadeOut(3000);
    }
    else if (password == '') {
        jQuery("#password_error").show();
        jQuery("#password_error").fadeOut(3000);
    }
    else if (phoneno == '') {
        jQuery("#phoneno_error").show();
        jQuery("#phoneno_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#adduser').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                var obj = jQuery.parseJSON(json);
                if (obj.sucess == 'ok') {
                    window.location.href = "pickup.php?id=2";
                } else {
                    jQuery("#fail_error").show();
                    jQuery("#fail_error").fadeOut(3000);
                }
            }
        });
        return false;
    }
}
function editPickup() {
    //alert("demo");
    var userid = jQuery("#pickupuser").val();
    var name = jQuery("#pickupname").val();
    var username = jQuery("#username").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    //var pins = jQuery("#pins").val();
    var final = new Array();
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (phoneno == '') {
        jQuery("#phoneno_error").show();
        jQuery("#phoneno_error").fadeOut(3000);
    }
    else {
        var data = jQuery('#edituser').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (jQuery.trim(json) == 'ok') {
                    window.location.href = "pickup.php?id=2";
                }
                else {
                    jQuery("#fail_error").show();
                    jQuery("#fail_error").fadeOut(3000);
                }
            }
        });
        return false;
    }
}
