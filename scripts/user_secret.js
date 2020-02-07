var data1 = 0;

function onKey(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    if ((key == 13)){
        checkUserAuth();
    }
}

/*Date: 21st oct 14, ak edited, cleared bug*/
function genNewPass() {
    var uname = jQuery("#uname").val();
    jQuery.ajax({
        type: "POST",
        url: "modules/user/route_ajax.php",
        cache: false,
        data: {uname: uname},
        success: function (statuscheck) {
            if (statuscheck == "ok") {
                jQuery("#forgot_message").show();
                jQuery("#forgot_message").fadeOut(5000);
                jQuery("#uname").val('');
            }
            else if (statuscheck == "notok") {
                jQuery("#wuser").show();
                jQuery("#wuser").fadeOut(5000);
            }
            else if (statuscheck == "noemail") {
                jQuery("#noemail").show();
                jQuery("#noemail").fadeOut(5000);
            }
            else if (statuscheck == "unableemail") {
                jQuery("#unableemail").show();
                jQuery("#unableemail").fadeOut(5000);
            }
        }
    });
}

function checkUserAuth() {

    var username = jQuery("#username").val();
    var password = jQuery("#password").val();
    var isOtpSent = jQuery("#isOtpSent").val();
    var authType = jQuery("#authType").val();
    if(authType == 1 && isOtpSent == 1) {
        login();
    } else {
        var hash_password = CryptoJS.SHA1(password);
        hash_password = hash_password.toString();
        jQuery.ajax({
            type: "POST",
            url: "modules/user/route_ajax.php",
            cache: false,
            data: {username: username, password: hash_password},
            success: function (data) {
                var info = JSON.parse(data);
                if(info.status == "notok") {
                    jQuery("#incorrect").show();
                    jQuery("#incorrect").fadeOut(5000);
                } else if (info.status == "forgot_pass") {
                    jQuery('#myModal').modal('hide');
                    jQuery('#myForgotPassModal').modal('show');
                    jQuery(".modal-body #user_name").val(username);
                } else if (info.status == "ok" && info.authType == 1 && info.otpSent == "Yes"){
                    jQuery("#inputOtp").show();
                    jQuery("#isOtpSent").val(1);
                    jQuery("#authType").val(info.authType);
                    jQuery("#otpSent").show();
                    jQuery("#otpSent").fadeOut(5000);
                    //login();
                } else if (info.status == "ok" && info.authType == 1 && info.otpSent == "No"){
                    jQuery("#otpNotSent").show();
                    jQuery("#otpNotSent").fadeOut(5000);
                } else if (info.status == "ok" && info.authType == 1 && info.otpSent == "phoneNotAvailable"){
                    jQuery("#nophone").show();
                    jQuery("#nophone").fadeOut(5000);
                } else if (info.status == "ok" && info.authType == 1 && info.otpSent == "limitExceeded"){
                    jQuery("#limitExceeded").show();
                    jQuery("#limitExceeded").fadeOut(5000);
                } else if (info.status == "ok" && info.authType == 1 && info.otpSent == "userLocked"){
                    jQuery("#userLocked").show();
                    jQuery("#userLocked").fadeOut(10000);
                } else if (info.status == "ok" && info.authType == 1 && info.otpSent == "noSmsBalance"){
                    jQuery("#noSmsBalance").show();
                    jQuery("#noSmsBalance").fadeOut(10000);
                } else if (info.status == "ok" && info.authType == 0){
                    login();
                } else {
                    alert("Login Failed");
                }
            }
        });
    }
}

function login() {

    var username = jQuery("#username").val();
    var password = jQuery("#password").val();
    var authType = jQuery("#authType").val();
    var otp = '';
    if(authType == 1) {
        otp = jQuery("#confirmOtp").val();
    }
    if(authType == 1 && otp == '') {
        jQuery("#sendOtp").show();
        jQuery("#sendOtp").fadeOut(5000);
    } else {
        var hash_password = CryptoJS.SHA1(password);
        hash_password = hash_password.toString();
        jQuery.ajax({
            type: "POST",
            url: "modules/user/route_secret.php",
            cache: false,
            data: {username: username, password: hash_password, authType:authType, otp:otp},
            success: function (data) {
                if(data == 'notOk') {
                    jQuery('#loginFailed').show();
                } else {
                    window.location = data;
                }
            }
        });
    }
}

function multiAuth(userid, customerno) {
    jQuery('#myModal').modal('hide');
    jQuery('#multiAuthOtp').modal('show');
}

function change_forgotpass() {

    var nwpass = jQuery("#newpasswd").val();
    var cmpass = jQuery("#confirm_newpasswd").val();

    if (nwpass == '') {
        jQuery("#newempty").show();
        jQuery("#newempty").fadeOut(3000);
    } else if (cmpass == '') {
        jQuery("#confirmempty").show();
        jQuery("#confirmempty").fadeOut(3000);
    } else if (nwpass != cmpass) {
        jQuery("#unmatch").show();
        jQuery("#unmatch").fadeOut(3000);
    } else {
        jQuery("#forpass").submit();
    }

}

//function checkgroupvalidity(username,password){
//  //var username = jQuery("#username").val();
//  //var password =jQuery("#password").val();
//        var username = username;
//        var password = password;
//
//  jQuery.ajax({
//              type: "POST",
//              url: "modules/user/route_ajax.php",
//              cache: false,
//              data:{usern:username,passwd:password},
//              success: function (statuscheck) {
//                      if (statuscheck == "ok")
//                      jQuery("#auth").submit();
//                      else if (statuscheck == "notok") {
//                      jQuery("#admin").show();
//                      jQuery("#admin").fadeOut(3000);
//                      }
//              }
//          });
//}
/*
function get_km_tracked(param, data1) {
    jQuery.ajax({
        type: "POST",
        url: "modules/user/route_ajax_km.php",
        data: "kmTracked=1&data1=" + data1,
        success: function (data) {
            var data_json = jQuery.parseJSON(data);
            if (param == 'html') {
                jQuery("#kmTracked").html(data_json[0]);
                data1 = data_json[0];
                jQuery("#kmTracked").attr('data-stop', data_json[1]);
            }
            else {
                jQuery('.counter').counter('stop')
                jQuery('.counter').counter({stop: data_json[1]});
                jQuery('.counter').counter('reset');
            }
        }
    });
}

get_km_tracked('html', data1);

setInterval(function () {
    get_km_tracked('abc', data1);
}, 5000);
*/

function elixiacode() {
    if (jQuery("#ecodeid").val() != "") {
        //alert('elixiacode.php?ecodeid='+jQuery("#ecodeid").val());
        //window.location = 'elixiacode.php?ecodeid='+jQuery("#ecodeid").val();
        //alert('Please Enter Cleint Code');
        //jQuery('#eecode').show();
        jQuery('#ecode').submit();
    }
}

function checkout() {
    if (jQuery("#ecodeid").val() != "") {
        evictMarkers();
        initialize();
        //mapvehicles();
        jQuery('#ehide').fadeOut(100);
        //AddItem('All', 'all');
        //AddItem1('All', 'all');
    } else {
        jQuery("#eecode").show();
        jQuery("#eecode").fadeOut(3000);
    }
}

function evictMarkers() {
    // clear all markers
    eviction_list.forEach(function (item) {
        item.setMap(null)
    });
    // reset the eviction array
    eviction_list = [];
}

function submit_contact_form() {
    var name = jQuery('#name').val();
    var email = jQuery('#email').val();
    var subject = jQuery('#subject').val();
    var message = jQuery('#message').val();

    jQuery.ajax({
        type: "POST",
        url: "mail.php",
        cache: false,
        dataType: 'JSON',
        data: {name: name, email: email, subject: subject, message: message},
        success: function (data) {
            if (data.status == true) {
                jQuery('#contact_alert').html('Request sent successfully');
                jQuery('#contact_alert').addClass('alert alert-info');
                jQuery('#contact_alert').removeClass('alert-danger');
            } else {
                jQuery('#contact_alert').html('Request failed, Please enter all fields!');
                jQuery('#contact_alert').removeClass('alert-info');
                jQuery('#contact_alert').addClass('alert alert-danger');
            }
        }
    });
}
