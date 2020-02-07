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

function pickupaddorder(){
    var orderno = jQuery("#orderid").val();
    var customer = jQuery("#customer").val();
    if(orderno=="" && customer==0){
        jQuery("#fail_error").show();
        jQuery("#fail_error").fadeOut(3000);
    }else{
    var data = jQuery('#addpickuporders').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "pick.php?id=8";
                }else if(json=='exists'){
                    jQuery("#fail_exists").show();
                    jQuery("#fail_exists").fadeOut(3000);
                } else {
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
function addshiper() {
    var name = jQuery("#shipername").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
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
        var data = jQuery('#addshiper').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "shiper.php?id=2";
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
function editshiper() {
    var name = jQuery("#shipername").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
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
        var data = jQuery('#editshiper').serialize();
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
                    window.location.href = "shiper.php?id=2";
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


function addPickup() {
      var name = jQuery("#pickupname").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    var username = jQuery("#username").val();
    var password = jQuery("#password").val();
    var pins = jQuery("#pins").val();
    var base64img = jQuery("#base64img").val();
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
    else if (pins == '') {
        jQuery("#pin_error").show();
        jQuery("#pin_error").fadeOut(3000);
    }else if (base64img == '') {
        jQuery("#photo_error").show();
        jQuery("#photo_error").fadeOut(3000);
    }
    else {
        val = pins.split(',');
        var rt;
        var final = new Array();
        var maini = 0
        var j1 = 0;
        jQuery(val).each(function (index, value) {
            rt = value.split('-');
            if (rt.length > 1) {
                for (j = rt[0]; j <= rt[1]; j++) {
                    //j1=j.trim();
                    j1 = jQuery.trim(j);
                    if (j1.length === 6) {
                        final[maini] = {'value': j1};
                        maini++;
                    }
                }
                //final[i] = {'value':text}; 
            }
            else {
                var ty = jQuery.trim(value);
                if (ty.length === 6) {
                    final[maini] = {'value': ty};
                    maini++;
                }
            }
            //i++;
        });
        final = JSON.stringify(final);
        if(final != ''){
          var data = jQuery('#adduser').serialize() + '&vmap=' + final;
        }else{
        var data = jQuery('#adduser').serialize();
      }
        jQuery.ajax({
            url: "pickup_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (json) {
                if (json == 'ok') {
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
function editPickup() {
    //alert("demo");
    var userid = jQuery("#pickupuser").val();
    var name = jQuery("#pickupname").val();
    var username = jQuery("#username").val();
    var phoneno = jQuery("#phoneno").val();
    var email = jQuery("#email").val();
    var pins = jQuery("#pins").val();
    var base64img = jQuery("#base64img").val();
    var final = new Array();
    
    if (name == '') {
        jQuery("#name_error").show();
        jQuery("#name_error").fadeOut(3000);
    }
    else if (phoneno == '') {
        jQuery("#phoneno_error").show();
        jQuery("#phoneno_error").fadeOut(3000);
    }else if (pins == '') {
        jQuery("#pin_error").show();
        jQuery("#pin_error").fadeOut(3000);
    }else if (base64img == '') {
        jQuery("#photo_error").show();
        jQuery("#photo_error").fadeOut(3000);
    }
    else {
        if (pins != '') {
            val = pins.split(',');
            var rt;
            
            var maini = 0
            var j1 = 0;
            jQuery(val).each(function (index, value) {
                rt = value.split('-');
                if (rt.length > 1) {
                    for (j = rt[0]; j <= rt[1]; j++) {
                        //j1=j.trim();
                        j1 = jQuery.trim(j);
                        if (j1.length === 6) {
                            final[maini] = {'value': j1};
                            maini++;
                        }
                    }
                    //final[i] = {'value':text}; 
                }
                else {
                    var ty = jQuery.trim(value);
                    if (ty.length === 6) {
                        final[maini] = {'value': ty};
                        maini++;
                    }
                }
                //i++;
            });
            final = JSON.stringify(final);
        }else{
          pins ='';
        }
        
        if(final != ''){
          var data = jQuery('#edituser').serialize() + '&vmap=' + final;
        }else{
        var data = jQuery('#edituser').serialize();
      }
        //var data = jQuery('#edituser').serialize() + '&vmap=' + final;
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
