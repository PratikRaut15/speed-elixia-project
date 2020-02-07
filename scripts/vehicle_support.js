
jQuery(document).ready(function () {
// Handler for .ready() called.
    jQuery('.file-inputs').bootstrapFileInput();
    jQuery('#acc_Date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#val_from_Date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery('#val_to_Date').datepicker({format: "dd-mm-yyyy", autoclose: true});
});
var files;

// Add events
jQuery('input[type=file]').on('change', prepareUpload);

// Grab the files and set them to our variable
function prepareUpload(event)
{
    files = event.target.files;

}

jQuery(".change_val").change(function () {

    var actual_amount = jQuery('#actual_amount').val();
    var sett_amount = jQuery('#sett_amount').val();
    jQuery('#mahindra_amount').val(actual_amount - sett_amount);
});



function push_transaction_by_category() {
    //alert("test");
    var meter_reading = jQuery("#meter_reading").val();
    var dealerid = jQuery("#dealerid option:selected").val();
    var note_batt = jQuery("#note_batt").val();
    var file_for_quote = jQuery("#file_for_quote").val();
    var vehicle_id = jQuery("#vehicle_id").val();
    var amount_quote = jQuery("#amount_quote").val();
    var category_id = jQuery("#category_id").val();

    var get_string = "route_ajax.php?";
    get_string += "meter_reading=" + meter_reading + " ";
    get_string += "&dealerid=" + dealerid + "";
    get_string += "&note_batt=" + note_batt + "";
    get_string += "&work=transaction";
    get_string += "&vehicle_id=" + vehicle_id + "";
    get_string += "&amount_quote=" + amount_quote + "";
    get_string += "&category_id=" + category_id + "";


    switch (category_id.toString()) {
        case '0':
            // does nott need any more agruments
            break;
        case '1':
            // does nott need any more agruments
            var tyre_list = [];
            jQuery('.tyre_type_array').each(function (id, obj) {
                tyre_list.push(obj.value);
            });
            // convering array into strin and assigning to the getstring url
            get_string += "&tyre_list=" + tyre_list.toString() + "";

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
            break;
        case '3':
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

                        document.getElementById("getbattery_approval").reset();
                        jQuery(".file-input-name").html("");
                        jQuery("#transaction_msg").html('Sent for approval');
                        jQuery("#transaction_msg").addClass("alert alert-info");
                        jQuery("#transaction_msg").removeClass("alert alert-danger");
                        jQuery("#transaction_msg").show();
                    } else {
                        document.getElementById("getbattery_approval").reset();
                        jQuery(".file-input-name").html("");
                        jQuery(transaction_msg).html(data.status_msg);
                        jQuery("#transaction_msg").addClass("alert alert-danger");
                        jQuery("#transaction_msg").removeClass("alert alert-info");
                        jQuery("#transaction_msg").show();
                    }

                }
    });




}

function push_transaction_accident() {
    var vehicle_id = jQuery("#vehicle_id1").val();
    var category_id = jQuery("#category_id1").val();

    if (vehicle_id != '') {
        var data = jQuery('#getaccident_approval').serialize() + '&accident_vehicleid=' + vehicle_id;
        //alert(data);exit;
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "notok") {
                    document.getElementById("getaccident_approval ").reset();
                    jQuery(".file-input-name").html("");
                    jQuery(transaction_msg).html(data.status_msg);
                    jQuery("#getaccident_approval #transaction_msg").addClass("alert alert-danger");
                    jQuery("#getaccident_approval #transaction_msg").removeClass("alert alert-info");
                    jQuery("#getaccident_approval #transaction_msg").show();
                }
                else if (statuscheck == "ok") {
                    document.getElementById("getaccident_approval").reset();
                    jQuery(".file-input-name").html("");
                    jQuery("#getaccident_approval #transaction_msg").html('Sent for approval');
                    jQuery("#getaccident_approval #transaction_msg").addClass("alert alert-info");
                    jQuery("#getaccident_approval #transaction_msg").removeClass("alert alert-danger");
                    jQuery("#getaccident_approval #transaction_msg").show();
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
        jQuery(target).append("<p class='btn btn-primary'  id='" + type + "_" + itemsel + "'>" + itemtext + "<input type='hidden' class='" + type + "_array' name='tyre_type[]' value='" + itemsel + "'/> <span onclick='jQuery(this).parent().remove();'>x</span></p> ");

    } else {
        alert("sect a type first");
    }


}
function category_selector() {
    var category_type = jQuery("#category_type option:selected").val();
    jQuery("#category_handler").html("");
    if (category_type == 2) {
        jQuery("#category_handler").html(jQuery("#parts_task").html());
    } else {
        jQuery("#category_handler").html("");
    }
    if (category_type == 2 || category_type == 3) {
        jQuery("#category_id").val(category_type);
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

//var acc_files;

// Add events
//jQuery('#file1').on('change', prepareUploadPUC);
//jQuery('#file2').on('change', prepareUploadREG);
//jQuery('#file3').on('change', prepareUploadINS);
//jQuery('#file4').on('change', prepareUploadOTHER);
//jQuery('#file5').on('change', prepareUploadOTHER1);
//jQuery('#file1').on('change', upload(1));
//jQuery('#file2').on('change', upload(2));
//jQuery('#file3').on('change', upload(3));
//jQuery('#file4').on('change', upload(4));
//jQuery('#file5').on('change', upload(5));
//$('form').on('submit', uploadFiles);

// Grab the files and set them to our variable

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
    var edit_vehicle_id = jQuery('#edit_vehicle_id').val();
    if (edit_vehicle_id != "") {

        var edit_vehicle_id = jQuery('#edit_vehicle_id').val();

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

function get_pdfvehicle(customerno, vehicleid) {
    window.open('pdftest.php?report=vehicle&vehicleid=' + vehicleid + '&customerno=' + customerno, '_blank')
}


/* export vehicle wise data  */

function xls_vehicledata(customerno)
{
    var kind = jQuery("#kind").val();
    var dataString = 'customerno=' + customerno + '&kind=' + kind + '&report=vehdataxls';
    window.location = "savexls.php?" + dataString;
}

function viewTransHistory(vid) {
    jQuery('#vehTransHist').modal('show');
    jQuery.ajax({
        url: '../vehicle/veh_trans_history.php?vehicleid=' + vid,
        type: 'POST',
        success: function (data) {
            jQuery('#vehTransHistBody').html(data);
        },
    });
}
function pdf_vehiclehistory(customerno) {
    var vehid = jQuery("#vehicle_id").val();
    window.open('pdftest.php?report=vehiclehistory&vehicleid=' + vehid + '&customerno=' + customerno, '_blank');
}
function xls_vehiclehistory(customerno) {
    var vehid = jQuery("#vehicle_id").val();
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehid + '&report=veh_histxls';
    window.location = "savexls.php?" + dataString;
}

function viewUploads(vehicle_id)
{
    if (vehicle_id != '') {

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {upload_vehicle_id: vehicle_id},
            dataType: 'html',
            success: function (html) {
                jQuery("#upload_body").html('');
                jQuery("#upload_body").append(html);
                jQuery('#viewuploads').modal('show');
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }

}