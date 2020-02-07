function adddriver(event)
{
    if (jQuery("#drivername").val() == "")
    {
        jQuery("#namecomp").show();
        jQuery("#namecomp").fadeOut(3000);
    } else if (jQuery("#drivephoneno").val().replace(/[^a-z]/g, "").length > 0)
    {
        jQuery("#phonecomp").show();
        jQuery("#phonecomp").fadeOut(3000);
    }
    else if (jQuery('#file1').val() != '' && jQuery('#other1').val() == '')
    {
        jQuery("#upload1_error").show();
        jQuery("#upload1_error").fadeOut(3000);
    }
    else if (jQuery("#loc_mob_no").val().replace(/[^a-z]/g, "").length > 0)
    {
        jQuery("#loc_mob_comp").show();
        jQuery("#loc_mob_comp").fadeOut(3000);
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#username").val() == '')
    {

        jQuery("#username_error").show();
        jQuery("#username_error").fadeOut(3000);
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#pwd").val() == '')
    {
        jQuery("#pwd_error").show();
        jQuery("#pwd_error").fadeOut(3000);
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#mail").val() == '')
    {
        jQuery("#mail_error").show();
        jQuery("#mail_error").fadeOut(3000);
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#phno").val() == '')
    {
        jQuery("#phone_error").show();
        jQuery("#phone_error").fadeOut(3000);
    }
    else
    {
        if (jQuery("#drivelicno").val() == "")
        {
            jQuery("#licensecomp").show();
            jQuery("#licensecomp").fadeOut(3000);
        }
        else
        {
            if (jQuery("#drivephoneno").val() == "")
            {
                jQuery("#phonecomp").show();
                jQuery("#phonecomp").fadeOut(3000);
            }
            else
            {

                var drivername = jQuery("#drivername").val();

                jQuery.ajax({
                    type: "POST",
                    url: "route_ajax.php",
                    cache: false,
                    data: {drivername: drivername, action: 'checkdrivername'},
                    success: function (statuscheck) {
                        if (statuscheck == "ok")
                        {
                            var data = jQuery('#createdriver').serialize();
                            adddriver_data(event, data);
                            //jQuery("#createdriver").submit();
                        }
                        else
                        {
                            jQuery("#samename").show();
                            jQuery("#samename").fadeOut(3000);
                        }
                    }
                });
            }
        }
    }
}
var fileupload1;
// Add events
jQuery('#file1').on('change', prepareUpload1);

function prepareUpload1(event)
{
    //fileupload1 = null;
    fileupload1 = event.target.files;
}

function adddriver_data(event, data) {

    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: data,
        success: function (json) {
            if (jQuery('#file1').val() != '')
            {
                upload_file_driver1(json);
            }

            if (jQuery('#file1').val() != '')
            {
                window.location.href = "driver.php?id=2";
            }
            else
            {
                window.location.href = "driver.php?id=2";
            }
        }
    });
    return false;
}


function upload_file_driver1(id)
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
            url: 'upload.php?did=' + id + '&filename=' + file1 + "&driverfile1",
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
    } else {
        alert("else");
        return false;
    }

}

function editdriver()
{
    if (jQuery("#drivelicno").val() == "")
    {
        jQuery("#licensecomp").show();
        jQuery("#licensecomp").fadeOut(3000);
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#username").val() == '')
    {

        jQuery("#username_error").show();
        jQuery("#username_error").fadeOut(3000);
        return false;
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#pwd").val()=='')
    {
        jQuery("#pwd_error").show();
        jQuery("#pwd_error").fadeOut(3000);
        return false;
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#mail").val() == '')
    {
        jQuery("#mail_error").show();
        jQuery("#mail_error").fadeOut(3000);
    }
    else if (jQuery("#rad1").is(":checked") && jQuery("#phno").val() == '')
    {
        jQuery("#phone_error").show();
        jQuery("#phone_error").fadeOut(3000);
    }
    else
    {
        if (jQuery("#drivephoneno").val() == "")
        {
            jQuery("#phonecomp").show();
            jQuery("#phonecomp").fadeOut(3000);
        }
        else
        {
            var data = jQuery('#modifydriver').serialize();
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: data,
                success: function (json) {
                    if (jQuery('#file1').val() != '')
                    {
                        upload_file_driver1(json);
                    }
                    if (jQuery('#file1').val() != '')
                    {
                        var res = confirm("Driver updated sucessfully");
                        if(res==true){ 
                            window.location.href = "driver.php?id=2";
                        }
                    }
                    else
                    {
                        var res = confirm("Driver updated sucessfully");
                        if(res==true){
                            window.location.href = "driver.php?id=2";
                        }
                    }
                }
            });
            return false;
        }
    }
}


function deletedriver(did) {
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php",
        cache: false,
        data: {driverid: did, action: 'deletedriver'},
        success: function (result) {
            window.location.href = "driver.php?id=2";
        }
    });
}

jQuery(document).ready(function () {
    var vehicleid = jQuery("#vehicleid").val();
    if (vehicleid == 0)
    {
        //alert("Please map vehicle for driver");
        jQuery(".edit_dislpay").hide();
        jQuery("#rad2").attr('checked', true);
        jQuery('#rad1').attr('checked', false); // Unchecks it
    }


    jQuery('#BDate').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#LicvalidDate').datepicker({format: "dd-mm-yyyy", autoclose: true});

    jQuery('#file1').on('change', function (e) {
        var filename = jQuery(this).val();
        var fileExtension = filename.split('.').pop();
        jQuery("#extension").val(fileExtension);
    });

    jQuery(".alertcontent").hide();
    jQuery("input:radio[name=driveralert]").click(function () {
        var driver_alert = jQuery(this).val();
        if (driver_alert == "yes") {
            jQuery(".alertcontent").show();
        } else {
            jQuery(".alertcontent").hide();
        }


    });

    jQuery(".dislpay").hide();
    jQuery("input:radio[name=rad]").click(function () {
        var app_alert = jQuery(this).val();
        if (app_alert == "yes") {
            jQuery(".dislpay").show();
        } else {
            jQuery(".dislpay").hide();
        }
    });
//edit driver for driver app
    jQuery('input:radio[name="edit_rad"]').change(
            function () {
                if (jQuery(this).is(':checked') && jQuery(this).val() == 'yes') {
                    jQuery(".edit_dislpay").show();
                    var vehicleid = jQuery("#vehicleid").val();
                    if (vehicleid == 0)
                    {
                        alert("Please map vehicle for driver");
                        jQuery(".edit_dislpay").hide();
                        jQuery("#rad2").attr('checked', true);
                        jQuery('#rad1').attr('checked', false); // Unchecks it
                    }

                } else {
                    jQuery(".edit_dislpay").hide();
                }

            });


    jQuery('#username').change(function () {
        var username = jQuery('#username').val();
        jQuery.ajax({
            type: 'POST',
            url: "route_ajax.php",
            cache: false,
            data: {username: username, action: 'checkdriverusername'},
            success: function (result) {

                if (result == 1) {
                    alert("username already used try another one");
                }
                else {
                    // do something if username doesn't exist
                }
            }
        });
    });
});



