// JavaScript Document
var toggle_counter = 2;
var previousid = 0;
var globalid = 0;
var fuel_balance = 0;
var eviction_list = [];
var periodic = true;
var callid = 0;
var timeouts = [];
var hideIconCount = 0;
var Minutes = 60 * 1;
var display = jQuery('#time');
var interval;

if (customerrefreshfrq == 267) {
    var periodictime = 9000;
    var refreshtime = 10000;
} else {
    var periodictime = (Minutes * 1000) - 1000;
    var refreshtime = Minutes * 1000;
}
var styles = [
    { "featureType": "road", "elementType": "geometry.stroke", "stylers": [{ "visibility": "off" }] }, { "featureType": "poi.park", "stylers": [{ "visibility": "simplified" }, { "lightness": 46 }] }, { "featureType": "poi", "elementType": "labels", "stylers": [{ "visibility": "on" }] }, { "featureType": "road.highway", "elementType": "labels", "stylers": [{ "visibility": "off" }] }, { "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{ "color": "#9e9e9f" }] }, { "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{ "color": "#bfbfbf" }] }, { "featureType": "road.local", "elementType": "geometry.fill", "stylers": [{ "color": "#e0e0e0" }] }, { "featureType": "poi.park", "stylers": [{ "lightness": 38 }] }, { "stylers": [{ "saturation": -54 }] }
];
jQuery(document).ready(function() {
    //jQuery('body').chardinJs('start');
    //loadrefresh();
    //close_map();
    var userRole = jQuery('#loginUserRole').val();
    if(userRole != 'elixir'){
        periodicupdateloadrefresh();    
    }
    
    jQuery('.helper').tipsy({
        gravity: 'se'
    });
    jQuery('.always_show').tipsy({
        gravity: 'se'
    });
    jQuery('.tooltip-top').tipsy({
        gravity: 'se'
    });
    jQuery('.tooltip-right').tipsy({
        gravity: 'w'
    });
    startTimer(Minutes, display);
});

function close_map() {
    jQuery(".filter").focus(function() {
        jQuery("#floatmap").hide();
        jQuery('.iv_all').attr("src", "../../images/show.png");
    });
}

function update_vehicle_status() {
    var sel_status = jQuery('#sel_status').val();
    if (sel_status == 3 || sel_status == 4) {
        jQuery('#sel_stoppage').show();
    } else {
        jQuery('#sel_stoppage').hide();
    }
    var data = "sel_status=" + sel_status;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(html) {
            //window.location.reload();
        }
    });
}

function update_vehicle_group() {
    var groupid = jQuery('#groupid').val();
    var data = "groupid=" + groupid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/realtimedata/rttabs.php",
        data: data,
        cache: false,
        success: function(html) {
            //window.location.reload();
        }
    });
}

function update_vehicle_status_onchange() {
    var statusid = jQUery('#statusid').val();
    if (statusid == 3 || statusid == 4) {
        jQuery('#sel_stoppage').show();
    } else {
        jQuery('#sel_stoppage').hide();
    }
    var data = "statusid=" + statusid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(html) {
            window.location.reload();
        }
    });
}

function update_stoppage_time() {
    var sel_stoppage = jQuery('#sel_stoppage').val();
    var data = "sel_stoppage=" + sel_stoppage;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(html) {
            //window.location.reload();
        }
    });
}

function add_fulecon(vehicleid, average, fueltank, fuelbalance) {
    jQuery("#vehicleid").val(vehicleid);
    //jQuery("#vehilceno").val(vehicleno);
    jQuery("#average").val(average);
    jQuery("#fuelcapacity").val(fueltank);
    jQuery("#fuelbalance").val(fuelbalance);
    jQuery("#fueltank").val(fueltank);
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#fuelpost').modal('show');
            jQuery("#header-1").html('Add Fuel For Vehicle No.- ' + json);
        }
    });
}

function add_driver(vehicleid, unitno, driverid) {
    jQuery("#vehicle_driver_id").val(vehicleid);
    jQuery("#unitnodriver").val(unitno);
    jQuery("#driverid").val(driverid);
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#Driver').modal('show');
            jQuery("#header-4").html('Allocate Driver For Vehicle No.- ' + json);
        }
    });
}

function messageToDriver(vehicleid, drivername, driverno) {
    jQuery('#vehicle_id').val(vehicleid);
    jQuery('#driver_name').val(drivername);
    jQuery('#driver_no').val(driverno);
    jQuery('#MessageToDriver').modal('show');
    jQuery("#header-6").html('Send Message To Driver.- ' + drivername);
}

function view_vehicle_history(vehicleid) {
    var data = "history_vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/realtimedata/rtd_ajax.php",
        data: data,
        cache: false,
        dataType: "json",
        success: function(json) {
            if (json.length > 0) {
                var count = 0;
                var trHTML = '<table id="dataTable" border="1"><tr><th>Sr No</th><th>Task</th><th>Old Unit No</th><th>New Unit No</th><th>Field Engineer</th><th>Status</th><th>Remark</th><th>Date</th></tr>';
                $.each(json, function(i, item) {
                    count++;
                    trHTML += '<tr><td>' + count +
                        '</td><td>' + item.task +
                        '</td><td>' + item.oldunitid +
                        '</td><td>' + item.newunitid +
                        '</td><td>' + item.name +
                        '</td><td>' + item.status +
                        '</td><td>' + item.remark +
                        '</td><td>' + item.createdon +
                        '</td></tr>';
                });
                $('#historyTable').html(trHTML);
                $('#dataTable th').css({ 'background': 'white', 'border-color': 'black', 'font-weight': 'bold' });
                $('#dataTable td').css({ 'border-color': 'black' });
            } else {
                $('#historyTable').html('History not available');
            }
        }
    });
    jQuery('#VehicleHistory').modal('show');
}
jQuery("#messageText").keyup(function() {
    jQuery("#count").text(jQuery(this).val().length);
});

function sendMessage() {
    var message = "Message:";
    message += jQuery("#messageText").val();
    var mobileno = jQuery("#driver_no").val();
    jQuery('#pageloaddiv').show();
    var match = mobileno.match(/8888888888/g);
    if (match != null || mobileno.length <= 10) {
        jQuery("#drivermsgerr1").text("Please provide Mobile number!");
        jQuery("#drivermsgerr1").show();
        jQuery("#drivermsgerr1").fadeOut(4000);
    } else if (message == "") {
        jQuery("#drivermsgerr1").text("Please Enter Message!");
        jQuery("#drivermsgerr1").show();
        jQuery("#drivermsgerr1").fadeOut(4000);
    } else {
        mobileno = mobileno.replace(" ", "");
        var vehicleid = jQuery("#vehicle_id").val();
        var data = "driverno=" + mobileno + "&driversms=" + message + "&vehicleid1=" + vehicleid;
        jQuery.ajax({
            type: "POST",
            url: "../../modules/realtimedata/rtd_ajax.php",
            data: data,
            cache: false,
            success: function(json) {
                jQuery('#pageloaddiv').hide();
                if (json == 0) {
                    jQuery("#drivermsgerr1").text("Successfully Sent");
                    jQuery("#drivermsgerr1").show();
                    jQuery("#drivermsgerr1").fadeOut(8000);
                } else if (json == 1) {
                    jQuery("#drivermsgerr1").text("SMS sending FAILED to " + jQuery('#driver_name').val());
                    jQuery("#drivermsgerr1").show();
                    jQuery("#drivermsgerr1").fadeOut(8000);
                } else if (json == -1) {
                    jQuery("#drivermsgerr1").text("You have exceeded specified SMS limit for this vehicle");
                    jQuery("#drivermsgerr1").show();
                    jQuery("#drivermsgerr1").fadeOut(8000);
                } else if (json == -2) {
                    jQuery("#drivermsgerr1").text("This user have exceeded specified SMS limit");
                    jQuery("#drivermsgerr1").show();
                    jQuery("#drivermsgerr1").fadeOut(8000);
                } else if (json == -3) {
                    jQuery("#drivermsgerr1").text("Unable to send SMS to " + jQuery('#driver_name').val() + " due to insufficient SMS balance.");
                    jQuery("#drivermsgerr1").show();
                    jQuery("#drivermsgerr1").fadeOut(8000);
                } else if (json == -4) {
                    jQuery("#drivermsgerr1").text("Message already sent once.Plese try after some time");
                    jQuery("#drivermsgerr1").show();
                    jQuery("#drivermsgerr1").fadeOut(8000);
                }
            }
        });
    }
}

function click_buzzer(vehicleid, unitno) {
    //alert(vehicleid);
    jQuery("#vehicle_id").val(vehicleid);
    jQuery("#unitno").val(unitno);
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#Buzzer').modal('show');
            jQuery("#header-2").html('Buzzer For Vehicle No.- ' + json);
        }
    });
}

function click_buzzer1(vehicleid, unitno) {
    jQuery("#vehicle_id").val(vehicleid);
    jQuery("#unitno").val(unitno);
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#BuzzerNot').modal('show');
            jQuery("#header-10").html('Buzzer For Vehicle No.- ' + json);
        }
    });
}

function click_immobiliser(vehicleid, unitno, mobiliser, status) {
    jQuery("#vehicle_id").val(vehicleid);
    jQuery("#unitno").val(unitno);
    jQuery("#statuscommand").val(status);
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#Immobiliser').modal('show');
            jQuery("#header-5").html('Immobilizer For Vehicle No.- ' + json);
            if (mobiliser == 0) {
                jQuery("#text-immobilise").html('Immobilizer Not Installed In Your Vehicle.<br/> * Note: For further information please contact an elixir.');
                jQuery("#save_mobiliser").hide();
                jQuery("#no_mobiliser").hide();
                jQuery("#ok_mobiliser").show();
            } else {
                if (status == 1) {
                    jQuery("#lock").show();
                    jQuery("#start").hide();
                    jQuery("#text-immobilise").html('Would You Wish To Immobilize The Vehicle ?');
                } else {
                    jQuery("#start").show();
                    jQuery("#lock").hide()
                    jQuery("#text-immobilise").html('Would You Wish To Start The Vehicle ?');
                }
                jQuery("#save_mobiliser").show();
                jQuery("#no_mobiliser").show();
                jQuery("#ok_mobiliser").hide();
            }
        }
    });
}

function click_unfreeze(vehicleid, unitno, freeze, status) {
    jQuery("#vehicle_id").val(vehicleid);
    jQuery("#unitno").val(unitno);
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#UNfreeze').modal('show');
            jQuery("#header-12").html('Freeze For Vehicle No.- ' + json);
        }
    });
}

function click_freeze(vehicleid, unitno, freeze, status) {
    jQuery("#vehicle_id").val(vehicleid);
    jQuery("#unitno").val(unitno);
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#freeze').modal('show');
            jQuery("#header-13").html('Freeze For Vehicle No.- ' + json);
        }
    });
}

function showfuelmodal(vehicleno) {
    jQuery('#fuelpost').modal('show');
    jQuery("#header-1").html('Add Fuel For');
}

function update_driver() {
    var customerno = jQuery("#customerno").val();
    var unitno = jQuery("#unitnodriver").val();
    var userid = jQuery("#userid").val();
    var vehicleid = jQuery("#vehicle_driver_id").val();
    var driverid = jQuery("#driverid").val();
    var driver = jQuery("#vdriver").val();
    var datastring = "vehicleid=" + vehicleid + "&customerno=" + customerno + "&userid=" + userid + "&driver=" + driver + "&driverid=" + driverid + "&updatedriver=1"
    // alert(datastring);
    if (driver == '' || driver == '0') {
        jQuery("#drivererr").show();
        jQuery("#drivererr").fadeOut(4000);
    } else {
        jQuery("#loader").show();
        jQuery("#save").hide();
        jQuery.ajax({
            type: "POST",
            url: "updatedriver.php",
            data: datastring,
            cache: false,
            success: function(html) {
                window.location.reload();
            }
        });
    }
}

function add_newdriver() {
    var customerno = jQuery("#customerno").val();
    var unitno = jQuery("#unitnodriver").val();
    var userid = jQuery("#userid").val();
    var vehicleid = jQuery("#vehicle_driver_id").val();
    var driverid = jQuery("#driverid").val();
    var dname = jQuery("#dname").val();
    var dlic = jQuery("#dlic").val();
    var dphone = jQuery("#dphone").val();
    var datastring = "vehicleid=" + vehicleid + "&customerno=" + customerno + "&userid=" + userid + "&dname=" + dname + "&dlic=" + dlic + "&dphone=" + dphone + "&driverid=" + driverid + "&adddriver=1"
    if (dname == '') {
        jQuery("#drivererr1").show();
        jQuery("#drivererr1").fadeOut(4000);
    } else {
        jQuery("#loader").show();
        jQuery("#save").hide();
        jQuery.ajax({
            type: "POST",
            url: "updatedriver.php",
            data: datastring,
            cache: false,
            dataType: "json",
            success: function(json) {
                window.location.reload();
            }
        });
    }
}

function add_fuel() {
    var fuelstorrage = jQuery('#fuelstorrage').val();
    var average = jQuery('#average').val();
    var customerno = jQuery('#customerno').val();
    var userid = jQuery('#userid').val();
    var vehicleid = jQuery('#vehicleid').val();
    var sdate = jQuery('#SDate').val();
    var stime = jQuery('#STime').val();
    var fuelbalance = jQuery('#fuelbalance').val();
    var fueltank = jQuery('#fuelcapacity').val();
    var setfuel = parseInt(fuelbalance) + parseInt(fuelstorrage);
    var data = "vehicleid=" + vehicleid + "&fuelstorrage=" + fuelstorrage + "&customerno=" + customerno + "&userid=" + userid + "&average=" + average + "&sdate=" + sdate + "&stime=" + stime + "&fueltank=" + fueltank;
    var numbers = /^[0-9]{1,3}$/;
    var fuel = /^[0-9]{1,3}$/;
    var floats = /^[0-9]{1,2}(\.[0-9]{1,2})$/;
    var fuel_floats = /^[0-9]{1,3}(\.[0-9]{1,2})$/;
    if (fuelstorrage == '') {
        jQuery("#fuelerr").show();
        jQuery("#fuelerr").fadeOut(4000);
    } else if (!(fuelstorrage.match(fuel_floats) || fuelstorrage.match(numbers))) {
        jQuery("#fuelerr1").show();
        jQuery("#fuelerr1").fadeOut(8000);
    } else if (average == '') {
        jQuery("#averageerr").show();
        jQuery("#averageerr").fadeOut(4000);
    } else if (!(average.match(floats) || average.match(numbers))) {
        jQuery("#averageerr1").show();
        jQuery("#averageerr1").fadeOut(4000);
    } else if (sdate == '') {
        jQuery("#dateerr").show();
        jQuery("#dateerr").fadeOut(4000);
    } else if (stime == '') {
        jQuery("#timeerr").show();
        jQuery("#timeerr").fadeOut(4000);
    } else if (fueltank == '') {
        jQuery("#tankerr").show();
        jQuery("#tankerr").fadeOut(4000);
    } else if (!fueltank.match(fuel)) {
        jQuery("#tankerr1").show();
        jQuery("#tankerr1").fadeOut(4000);
    } else if (fuelstorrage == 0 || average == 0 || fueltank == 0) {
        jQuery("#ZeroError").show();
        jQuery("#ZeroError").fadeOut(4000);
    } else if (setfuel > fueltank) {
        jQuery("#capasityerr").show();
        jQuery("#capasityerr").fadeOut(4000);
    } else {
        jQuery("#loader").show();
        jQuery("#save").hide();
        jQuery.ajax({
            type: "POST",
            url: "addfuel.php",
            data: data,
            cache: false,
            success: function(html) {
                window.location.reload();
            }
        });
    }
}

function add_buzzer() {
    var unitno = jQuery("#unitno").val();
    var customerno = jQuery('#customerno').val();
    var userid = jQuery('#userid').val();
    var data = "userid=" + userid + "&unitno=" + unitno + "&customerno=" + customerno;
    jQuery("#loader").show();
    jQuery("#save").hide();
    jQuery.ajax({
        type: "POST",
        url: "add_buzzer.php",
        data: data,
        cache: false,
        success: function(html) {
            jQuery('#Buzzer').modal('hide');
            // location.reload();
        }
    });
}

function unfreezed_vehicle() {
    var unitno = jQuery("#unitno").val();
    var customerno = jQuery('#customerno').val();
    var userid = jQuery("#userid").val();
    var data = "userid=" + userid + "&unitno=" + unitno + "&customerno=" + customerno + "&action=unfreeze";
    jQuery("#loader").show();
    jQuery("#save").hide();
    jQuery.ajax({
        type: "POST",
        url: "freeze.php",
        data: data,
        cache: false,
        success: function(html) {
            jQuery('#UNfreeze').modal('hide');
            location.reload();
        }
    });
}

function freeze_vehicle() {
    var unitno = jQuery("#unitno").val();
    var customerno = jQuery('#customerno').val();
    var userid = jQuery("#userid").val();
    var data = "userid=" + userid + "&unitno=" + unitno + "&customerno=" + customerno + "&action=freezed";
    jQuery("#loader").show();
    jQuery("#save").hide();
    jQuery.ajax({
        type: "POST",
        url: "freeze.php",
        data: data,
        cache: false,
        success: function(html) {
            jQuery('#UNfreeze').modal('hide');
            location.reload();
        }
    });
}

function add_immobiliser() {
    var unitno = jQuery("#unitno").val();
    var customerno = jQuery('#customerno').val();
    var statuscommand = jQuery('#statuscommand').val();
    var userid = jQuery("#userid").val();
    var data = "userid=" + userid + "&unitno=" + unitno + "&customerno=" + customerno + "&status=" + statuscommand;
    jQuery("#loader").show();
    jQuery("#save").hide();
    jQuery.ajax({
        type: "POST",
        url: "add_immobiliser.php",
        data: data,
        cache: false,
        success: function(html) {
            //jQuery('#Immobiliser').modal('hide');
            location.reload();
        }
    });
}

function vehiclehistory_leftdiv(vehicleid) {
    jQuery("#alerthistorydata").empty();
    jQuery.ajax({
        type: "POST",
        url: "vehicle_histdata_ajax.php?vehicleid=" + vehicleid,
        cache: false,
        success: function(result) {
            jQuery("#alerthistorydata").html(result);
        }
    });
}

function call_row(selid) {
    // if unassigned
    if (previousid == 0 || previousid != selid) {
        if (previousid == 0) {
            previousid = selid;
            jQuery('#iv_' + selid).attr("src", "../../images/show.png");
        }
        if (previousid != selid) {
            jQuery('.iv_all').attr("src", "../../images/show.png");
            jQuery('#iv_' + selid).attr("src", "../../images/hide.gif");
            periodic = true;
        }
    }
    if (previousid == selid) {
        jQuery('.iv_all').attr("src", "../../images/show.png");
        jQuery('#iv_' + selid).attr("src", "../../images/hide.gif");
        jQuery("#floatmap").remove();
    } else {
        if (toggle_counter % 2 !== 0) {
            toggle_counter++;
        }
    }
    if (toggle_counter % 2 === 0) {
        if (jQuery("#floatmap").length) {
            jQuery("#floatmap").remove();
        }
        callid = selid;
        var wwidth = jQuery("#wwidth").val();
        //jQuery("#" + selid).toggle();
        if (wwidth == 75) {
            jQuery("#" + selid).after("<tr id='floatmap'><td colspan='100%'><div id='map_container'><div id=load></div><div id='alerthistorydata' style='height:330px; float:left; width:22%;'></div> <div id='map'  style='height:330px; float:left; width:55%;'></div><div class='pull-right' id='v_des' style='width:22%;height:330px;'></div></div></td></tr>").slideDown('slow');
        } else {
            jQuery("#" + selid).after("<tr id='floatmap'><td colspan='100%' ><div id='map_container'><div id=load></div><div id='map' style='height:330px; float:left; width:100%;' ></div></div></td></tr>").slideDown('slow');
        }
        //slideDown('slow');
        //jQuery('#map_container').animate({height:"330px",speed:1000000,easing:'swing'});
        jQuery('#map_container').animate({ duration: 5000, easing: 'easeInSine' }).animate({ height: 330 });
        if (selid) {
            jQuery.ajax({
                type: "POST",
                url: "rtd_ajax.php?vehicleid=" + selid,
                success: function(data) {
                    jQuery("#v_des").html(data);
                    var get_str = "#latlong" + selid;
                    var str = jQuery(get_str).val();
                    var vehiclename = jQuery("#vehicle" + selid).val();
                    var vehicleimage = jQuery("#vehicleimage" + selid).val();
                    var partsOfStr = str.split(',');
                    var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
                    jQuery.bind(data, refreshdata);
                    var styledMap = new google.maps.StyledMapType(styles, {
                        name: "Styled Map"
                    });
                    var myOptions = {
                        zoom: 15,
                        center: latlng,
                        panControl: true,
                        streetViewControl: false,
                        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
                        zoomControl: true,
                        zoomControlOptions: {
                            style: google.maps.ZoomControlStyle.SMALL
                        },
                        styles: styles
                    };
                    map = new google.maps.Map(document.getElementById('map'), myOptions);
                    map.mapTypes.set('map_style', styledMap);
                    map.setMapTypeId('map_style');
                    var trafficLayer = new google.maps.TrafficLayer();
                    trafficLayer.setMap(map);
                    var image = new google.maps.MarkerImage(vehicleimage,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));
                    var marker = new MarkerWithLabel({
                        position: latlng,
                        map: map,
                        icon: image,
                        labelContent: vehiclename,
                        labelAnchor: new google.maps.Point(9, 45),
                        labelClass: "mapslabels" // the CSS class for the label
                    });
                    eviction_list.push(marker);
                    jQuery('.chk_latlong').each(function() {
                        var circle;
                        var rad;
                        var chklatlng;
                        chklatlng = jQuery(this).val();
                        //alert(chklatlng);
                        var partsOfStr = chklatlng.split(',');
                        var chkrad = partsOfStr[2];
                        var chkname = partsOfStr[3];
                        var chklatlngp = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
                        var chkmarker = new MarkerWithLabel({
                            position: chklatlngp,
                            map: map,
                            labelContent: chkname,
                            labelAnchor: new google.maps.Point(45, 50),
                            labelClass: "mapslabels_chkp" // the CSS class for the label
                        });
                        rad = chkrad * 1000;
                        circle = new google.maps.Circle({
                            map: map,
                            radius: rad,
                            fillColor: '#000000',
                            strokeColor: '#000000',
                            strokeweight: 1
                        });
                        circle.bindTo('center', chkmarker, 'position');
                    });
                    // fence mapping on map
                    jQuery('.fence_latlong').each(function() {});
                    //Edit Vehicle checkpoint and fences
                    jQuery(".editbox").hide();
                    jQuery(".edit_chkname").dblclick(function() {
                        var ID = jQuery(this).attr('rel');
                        var chkid = jQuery(this).attr('id');
                        jQuery("#chkname_" + ID + "_" + chkid).hide();
                        jQuery("#chkname_input_" + ID + "_" + chkid).show();
                        jQuery("#chkname_input_" + ID + "_" + chkid).focus();
                        jQuery("#chkrad_" + ID + "_" + chkid).hide();
                        jQuery("#chkrad_input_" + ID + "_" + chkid).show();
                    }).change(function() {
                        var ID = jQuery(this).attr('rel');
                        var chkid = jQuery(this).attr('id');
                        var chkname = jQuery("#chkname_input_" + ID + "_" + chkid).val();
                        var chkrad = jQuery("#chkrad_input_" + ID + "_" + chkid).val();
                        var dataString = 'chkid=' + chkid + '&chkname=' + chkname + '&chkrad=' + chkrad;
                        jQuery("#chkname_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
                        jQuery("#chkrad_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
                        if (chkname && chkrad) {
                            if (chkname.length > 0 && chkrad.length > 0) {
                                jQuery.ajax({
                                    type: "POST",
                                    url: "../checkpoint/route_ajax.php",
                                    data: dataString,
                                    cache: false,
                                    success: function(html) {
                                        jQuery("#chkname_" + ID + "_" + chkid).html(chkname);
                                        jQuery("#chkrad_" + ID + "_" + chkid).html(chkrad);
                                        call_row_load(ID);
                                    }
                                });
                            } else {
                                alert('Enter something.');
                            }
                        }
                    });
                    //edit fence
                    jQuery(".edit_fence").dblclick(function() {
                        var ID = jQuery(this).attr('rel');
                        var fenceid = jQuery(this).attr('id');
                        jQuery("#fencename_" + ID + "_" + fenceid).hide();
                        jQuery("#fencename_input_" + ID + "_" + fenceid).show();
                        jQuery("#fencename_input_" + ID + "_" + fenceid).focus();
                    }).change(function() {
                        jQuery(".tipsy").each(function() {
                            jQuery(this).hide();
                        })
                        var ID = jQuery(this).attr('rel');
                        var fenceid = jQuery(this).attr('id');
                        var fencename = jQuery("#fencename_input_" + ID + "_" + fenceid).val();
                        var dataString = 'fenceid=' + fenceid + '&fencename=' + fencename;
                        jQuery("#fencename_" + ID + "_" + fenceid).html('<img src="load.gif" />'); // Loading image
                        if (fencename.length > 0) {
                            jQuery.ajax({
                                type: "POST",
                                url: "../fencing/route_ajax.php",
                                data: dataString,
                                cache: false,
                                success: function(html) {
                                    jQuery("#fencename_" + ID + "_" + fenceid).html(fencename);
                                    call_row_load(ID);
                                }
                            });
                        } else {
                            alert('Enter something.');
                        }
                    });
                    // Edit input box click action
                    jQuery(".editbox").mouseup(function() {
                        return false
                    });
                    // Outside click action
                    jQuery(document).mouseup(function() {
                        jQuery(".editbox").hide();
                        jQuery(".text").show();
                    });
                    jQuery('.editbox').keypress(function(e) {
                        if (e.which == 13) {
                            //alert('The enter key was pressed!');
                            jQuery(".editbox").hide();
                            jQuery(".text").show();
                            jQuery(".tipsy").each(function() {
                                jQuery(this).hide();
                            })
                        }
                    });
                    // To Generate the Fuel Gauge
                    var capacity = jQuery('#capacity_' + selid).val();
                    var limit = jQuery('#alert_' + selid).val();
                    var balance = jQuery('#balance_' + selid).val();
                    draw_fuel_gauge(capacity, limit, balance, selid);
                    periodic = true;
                    // Periodic Update
                    globalid = selid;
                    periodicupdate();
                }
            });
        }
        jQuery("#load").animate({
            width: ['toggle', 'swing']
        }, 2000, function() {
            // Animation complete.
        });
        var str = jQuery("#latlong" + selid).val();
        var vehiclename = jQuery("#vehicle" + selid).val();
        if (str != '') {
            var partsOfStr = str.split(',');
            //init_map_realtime(partsOfStr[0],partsOfStr[1],vehiclename);
        }
        vehiclehistory_leftdiv(selid);
        toggle_counter++;
    } else {
        periodic = false;
        toggle_counter++;
        jQuery('#iv_' + selid).attr("src", "../../images/show.png");
    }
    previousid = selid;
    //    console.log(callid);
}

function periodicupdate() {
    if (periodic == true) {
        setTimeout(function() {
            refreshdata();
        }, refreshtime);
    }
}

function refreshdata() {
    if (periodic == true && globalid != 0) {
        jQuery.ajax({
            type: "POST",
            url: "rtd_map_ajax.php",
            cache: false,
            data: { vehicleids: globalid },
            success: function(data) {
                var cdata1 = jQuery.parseJSON(data);
                var results = cdata1.result;
                evictmarkers();
                // Marker Clustering
                jQuery.each(results, function(i, device) {
                    var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));
                    var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                    var marker = new MarkerWithLabel({
                        'position': latLng,
                        map: map,
                        icon: image,
                        labelContent: device.cname,
                        labelAnchor: new google.maps.Point(9, 45),
                        labelClass: "mapslabels" // the CSS class for the label
                    });
                    map.setCenter(latLng);
                    eviction_list.push(marker);
                    jQuery('#map_table .ebcl_' + globalid).html(device.extbatt);
                    jQuery('#map_table .ibcl_' + globalid).html(device.inbatt);
                    jQuery('#map_table .gsmcl_' + globalid).attr("src", device.gsmimg);
                    jQuery('#map_table .gsmcl_' + globalid).attr("title", device.network);
                    jQuery('#' + globalid + ' .imgcl').attr("src", device.idleimage);
                    jQuery('#' + globalid + ' .gimg').attr("src", device.iimage);
                    jQuery('#' + globalid + ' .speedcl').html(device.cspeed);
                });
                periodicupdate();
            }
        });
    }
}

function deletefence(fenceid, fencename) {
    var fenceid = jQuery("#del_fence").val();
    var vehicleid = jQuery("#del_fence_vehid").val();
    var dataString = 'fenceid=' + fenceid + '&vehicleid=' + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../fencing/route_ajax.php",
        data: dataString,
        cache: false,
        success: function(html) {
            jQuery("#f_" + fenceid).remove();
            jQuery('#delete_fence').modal('hide');
            call_row_load(vehicleid);
        }
    });
}

function deletechkpoint(chkid, chkname) {
    var chkid = jQuery("#del_chkpoint").val();
    var vehicleid = jQuery("#del_chk_vehid").val();
    var dataString = 'chkid=' + chkid + '&vehicleid=' + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../checkpoint/route_ajax.php",
        data: dataString,
        cache: false,
        success: function(html) {
            jQuery("#c_" + chkid).remove();
            jQuery('#delete_chk').modal('hide');
            call_row_load(vehicleid);
        }
    });
}

function deleteecode(ecodeid, vehid) {
    var ecodeid = jQuery("#del_ecode").val();
    var vehicleid = jQuery("#del_ecode_vehid").val();
    var dataString = 'ecodeid=' + ecodeid + '&vehicleid=' + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../ecode/route_ajax.php",
        data: dataString,
        cache: false,
        success: function(html) {
            jQuery("#e_" + ecodeid).remove();
            jQuery('#delete_ecode1').modal('hide');
            call_row_load(vehicleid);
        }
    });
}

function call_row_load(id) {
    jQuery.ajax({
        type: "GET",
        url: "rtd_ajax.php?vehicleid=" + id,
        async: false,
        cache: false,
        success: function(data) {
            jQuery("#v_des").html(data);
            var str = jQuery("#latlong" + id).val();
            var vehiclename = jQuery("#vehicle" + id).val();
            var vehicleimage = jQuery("#vehicleimage" + id).val();
            var partsOfStr = str.split(',');
            var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
            var styledMap = new google.maps.StyledMapType(styles, { name: "Styled Map" });
            var myOptions = {
                zoom: 15,
                center: latlng,
                panControl: true,
                streetViewControl: false,
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                },
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                styles: styles
            };
            map = new google.maps.Map(document.getElementById('map'), myOptions);
            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');
            var image = new google.maps.MarkerImage(vehicleimage,
                new google.maps.Size(48, 48),
                new google.maps.Point(0, 0),
                new google.maps.Point(8, 20));
            var imagechk = new google.maps.MarkerImage("http://elixiatech.com/speed/images/marker_icon.png");
            var marker = new MarkerWithLabel({
                position: latlng,
                map: map,
                icon: image,
                labelContent: vehiclename,
                labelAnchor: new google.maps.Point(9, 45),
                labelClass: "mapslabels" // the CSS class for the label
            });
            jQuery('.chk_latlong').each(function() {
                var circle;
                var rad;
                var chklatlng;
                chklatlng = jQuery(this).val();
                var partsOfStr = chklatlng.split(',');
                var chkrad = partsOfStr[2];
                var chkname = partsOfStr[3];
                var chklatlngp = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
                var marker = new MarkerWithLabel({
                    position: chklatlngp,
                    map: map,
                    labelContent: chkname,
                    labelAnchor: new google.maps.Point(45, 50),
                    labelClass: "mapslabels_chkp" // the CSS class for the label
                });
                rad = chkrad * 1000;
                circle = new google.maps.Circle({
                    map: map,
                    radius: rad,
                    fillColor: '#AA0000',
                    strokeColor: '#AA0000',
                    strokeweight: 1
                });
                circle.bindTo('center', marker, 'position');
            });
            //Edit Vehicle checkpoint and fences
            jQuery(".editbox").hide();
            jQuery(".edit_chkname").dblclick(function() {
                var ID = jQuery(this).attr('rel');
                var chkid = jQuery(this).attr('id');
                jQuery("#chkname_" + ID + "_" + chkid).hide();
                jQuery("#chkname_input_" + ID + "_" + chkid).show();
                jQuery("#chkrad_" + ID + "_" + chkid).hide();
                jQuery("#chkrad_input_" + ID + "_" + chkid).show();
            }).change(function() {
                var ID = jQuery(this).attr('rel');
                var chkid = jQuery(this).attr('id');
                var chkname = jQuery("#chkname_input_" + ID + "_" + chkid).val();
                var chkrad = jQuery("#chkrad_input_" + ID + "_" + chkid).val();
                var dataString = 'chkid=' + chkid + '&chkname=' + chkname + '&chkrad=' + chkrad;
                jQuery("#chkname_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
                jQuery("#chkrad_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
                if (chkname && chkrad) {
                    if (chkname.length > 0 && chkrad.length > 0) {
                        jQuery.ajax({
                            type: "POST",
                            url: "../checkpoint/route_ajax.php",
                            data: dataString,
                            cache: false,
                            success: function(html) {
                                jQuery("#chkname_" + ID + "_" + chkid).html(chkname);
                                jQuery("#chkrad_" + ID + "_" + chkid).html(chkrad);
                                call_row_load(ID);
                            }
                        });
                    } else {
                        alert('Enter something.');
                    }
                }
            });
            //edit fence
            jQuery(".edit_fence").dblclick(function() {
                var ID = jQuery(this).attr('rel');
                var fenceid = jQuery(this).attr('id');
                jQuery("#fencename_" + ID + "_" + fenceid).hide();
                jQuery("#fencename_input_" + ID + "_" + fenceid).show();
            }).change(function() {
                var ID = jQuery(this).attr('rel');
                var fenceid = jQuery(this).attr('id');
                var fencename = jQuery("#fencename_input_" + ID + "_" + fenceid).val();
                var dataString = 'fenceid=' + fenceid + '&fencename=' + fencename;
                jQuery("#fencename_" + ID + "_" + fenceid).html('<img src="load.gif" />'); // Loading image
                if (fencename.length > 0) {
                    jQuery.ajax({
                        type: "POST",
                        url: "../fencing/route_ajax.php",
                        data: dataString,
                        cache: false,
                        success: function(html) {
                            jQuery("#fencename_" + ID + "_" + fenceid).html(fencename);
                            call_row_load(ID);
                        }
                    });
                } else {
                    alert('Enter something.');
                }
            });
            // Edit input box click action
            jQuery(".editbox").mouseup(function() {
                return false
            });
            // Outside click action
            jQuery(document).mouseup(function() {
                jQuery(".editbox").hide();
                jQuery(".text").show();
            });
            jQuery('.editbox').keypress(function(e) {
                if (e.which == 13) {
                    //alert('The enter key was pressed!');
                    jQuery(".editbox").hide();
                    jQuery(".text").show();
                }
            });
            //edit chkpoint and fences complete here
        }
    });
    jQuery("#load").animate({
        width: ['toggle', 'swing']
    }, 2000, function() {
        // Animation complete.
    });
    var str = jQuery("#latlong" + id).val();
    var vehiclename = jQuery("#vehicle" + id).val();
    var partsOfStr = str.split(',');
    //init_map_realtime(partsOfStr[0],partsOfStr[1],vehiclename);
    toggle_counter++;
    previousid = id;
}

function loadrefresh() {
    clearInterval(interval);
    var ids = "";
    jQuery("tr:not([filtermatch]) input[id^='latlong']").each(function(i) {
        var data;
        data = (jQuery(this).attr('id')).replace("latlong", "");
        ids += data + ",";
    });
    ids = ids.slice(0, -1);
    var count = 0,
        flag = 0;
    jQuery("input[id^='search_table_filter']").each(function() {
        count++;
        if (jQuery(this).val() == "") {
            flag++;
        }
    });
    var UrlSpc;
    if (count != flag) {
        UrlSpc = "rtd_map_ajax.php?all=" + ids;
    } else {
        UrlSpc = "rtd_map_ajax.php?all=1";
    }
    jQuery.ajax({
        type: "POST",
        url: UrlSpc,
        cache: false,
        success: function(data) {
            if (data != null || data != 'null' || data != '') {
                if (data.length > 0) {
                    var cdata = jQuery.parseJSON(data);
                    var results = cdata.result;
                } else {
                    var results = null;
                }

            } else {
                var results = null;
            }

            //var cdata = jQuery.parseJSON(data);

            if (results != null && results != 'null' && results != '' && results != 'undefined' && results != undefined) {
                jQuery.each(results, function(i, device) {
                    jQuery('#' + device.cvehicleid + ' .status').html('');
                    jQuery('#' + device.cvehicleid + ' .imgcl').attr("src", device.idleimage);
                    jQuery('#' + device.cvehicleid + ' .lupd').html(device.clastupdated);
                    jQuery('#' + device.cvehicleid + ' .status').html(device.status);
                    jQuery('#' + device.cvehicleid + ' .gimg').val(device.iimage);
                    if (device.location != '') {
                        jQuery('#' + device.cvehicleid + ' .loccl').html('<a style="text-decoration:underline;" onclick="call_row(' + device.cvehicleid + ')">' + device.location + '</a><br>[' + device.lat + ',' + device.long + ']');
                    }

                    jQuery('#' + device.cvehicleid + ' .chkpnt').html(device.chkpnt_status);
                    jQuery('#' + device.cvehicleid + ' .routeStatus').html(device.routeDirection);
                    jQuery('#' + device.cvehicleid + ' .speedcl').html(device.cspeed);
                    if (device.overspeedconf != '') {
                        if (device.cspeed != 0) {
                            jQuery('#' + device.cvehicleid + ' .speedcl').addClass('off');
                        }
                    }
                    jQuery('#' + device.cvehicleid + ' .distcl').html(device.totaldist);
                    jQuery('#' + device.cvehicleid + ' .pccl').attr("src", device.pc);
                    jQuery('#' + device.cvehicleid + ' .pccl').attr("title", device.pctitle);
                    jQuery('#' + device.cvehicleid + ' .loadcl').html(device.msgkey);
                    jQuery('#' + device.cvehicleid + ' .accl').html(device.acsensor);
                    jQuery('#' + device.cvehicleid + ' .doorCl').html(device.doorsensor);
                    if (device.temp_sensors == 1) {
                        if (device.temp1on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                            if (device.tempconf1 !== '') {
                                jQuery('#' + device.cvehicleid + ' .tempcl').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                        }
                    } else if (device.temp_sensors == 2) {
                        if (device.temp1on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                            if (device.tempconf1 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempcl').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                        }
                        if (device.temp2on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempc2').html(device.temp2);
                            if (device.tempconf2 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempc2').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempc2').html(device.temp2);
                        }
                    } else if (device.temp_sensors == 3) {
                        if (device.temp1on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                            if (device.tempconf1 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempcl').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                        }
                        if (device.temp2on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempc2').html(device.temp2);
                            if (device.tempconf2 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempc2').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempc2').html(device.temp2);
                        }
                        if (device.temp3on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempc3').html(device.temp3);
                            if (device.tempconf3 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempc3').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempc3').html(device.temp3);
                        }
                    } else if (device.temp_sensors == 4) {
                        if (device.temp1on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                            if (device.tempconf1 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempcl').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempcl').html(device.temp1);
                        }
                        if (device.temp2on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempc2').html(device.temp2);
                            if (device.tempconf2 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempc2').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempc2').html(device.temp2);
                        }
                        if (device.temp3on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempc3').html(device.temp3);
                            if (device.tempconf3 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempc3').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempc3').html(device.temp3);
                        }
                        if (device.temp4on == 1) {
                            jQuery('#' + device.cvehicleid + ' .tempc4').html(device.temp4);
                            if (device.tempconf4 != '') {
                                jQuery('#' + device.cvehicleid + ' .tempc4').addClass('off');
                            }
                        } else {
                            jQuery('#' + device.cvehicleid + ' .tempc4').html(device.temp4);
                        }
                    }
                    if (device.use_extradigital == 1) {
                        //                        jQuery('#' + device.cvehicleid + ' .genset1').html(device.genset1);
                        jQuery('#' + device.cvehicleid + ' .genset2').html(device.genset2);
                    }
                    if (device.use_humidity == 1) {
                        if (device.humidityon == 1) {
                            jQuery('#' + device.cvehicleid + ' .humicl').html(device.humidity);
                        } else {
                            jQuery('#' + device.cvehicleid + ' .humicl').html(device.humidity);
                        }
                    }
                    jQuery('#' + device.cvehicleid + ' .average').html(device.average);
                    jQuery('#overspeed_val').html(device.a);
                    jQuery('#running_val').html(device.b);
                    jQuery('#idle_ign_on').html(device.c);
                    jQuery('#idle_ign_off').html(device.d);
                    jQuery('#inactive_val').html(device.e);
                    jQuery('#inactive').html(device.p);
                    jQuery('#w_on').html(device.r);
                    jQuery('#conflict').html(device.q);
                    jQuery('#seconds').html("00");
                    //callid = device.cvehicleid;

                });
            }

            startTimer(Minutes, display);
            periodicupdateloadrefresh();
        }
    });
}

function periodicupdateloadrefresh() {
    for (var i = 0; i < timeouts.length; i++) {
        clearTimeout(timeouts[i]);
    }
    timeouts.push(setTimeout(function() {
        loadrefresh();
    }, periodictime));
    timeouts.push(setTimeout(function() {
        hideIconCount = 0;
        jQuery(".iv_all").each(function(i) {
            if (jQuery(this).attr("src") === "../../images/hide.gif") {
                hideIconCount++;
            }
        });
        if (callid !== 0 && hideIconCount > 0) {
            call_row(callid);
            refreshdata();
            call_row(callid);
        }
    }, refreshtime));
}

function evictmarkers() {
    // clear all markers
    jQuery.each(eviction_list, function(i, item) {
        item.setMap(null);
    });
    // reset the eviction array
    eviction_list = [];
}

function draw_fuel_gauge(capacity, limit, balance, selid) {
    //alert(balance);
    //document.getElementById('chart_fuel').innerHTML=capacity;
    google.load("visualization", "1.1", { packages: ["corechart"] });
    google.setOnLoadCallback(drawChart(capacity, limit, balance, selid));
}

function drawChart(capacity, limit, balance, selid) {
    var data123 = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['Fuel  (in Lt)', parseFloat(balance)]
    ]);
    var options123 = {
        width: 120,
        height: 120,
        redFrom: 0,
        redTo: limit,
        minorTicks: 10,
        max: capacity
    };
    var chart123 = new google.visualization.Gauge(document.getElementById('chart_fuel_' + selid));
    chart123.draw(data123, options123);
}

function btnAddCheckPoint_click() {
    for (var i = 0; i < timeouts.length; i++) {
        clearTimeout(timeouts[i]);
        //alert(timeouts[i]);
    }
    //quick reset of the timer array you just cleared
    timeouts = [];
    addedcheckpoint();
}
jQuery('#addcheckpoint').live("hide", function() {
    //alert("test");
    loadrefresh();
});
jQuery('#addfence').live("hide", function() {
    //alert("test");
    loadrefresh();
});
jQuery('#getecode').live("hide", function() {
    //alert("test");
    loadrefresh();
});

function muteVehicle(vehicleid, temp) {
    jQuery("#vehicle_id").val(vehicleid);
    jQuery("#temp").val(temp);
    jQuery("#condition").val('Mute');
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#Mute').modal('show');
            jQuery("#header-mute").html('Mute Temperature For Vehicle No.- ' + json);
            jQuery("#alertmsg").html('Do You Like To Mute The Vehicle Temperature?');
            jQuery("#notemsg").html('Note - This will help you not consider the values for temperature compliance.');
        }
    });
}

function unmuteVehicle(vehicleid, temp) {
    jQuery("#vehicle_id").val(vehicleid);
    jQuery("#temp").val(temp);
    jQuery("#condition").val('Unmute');
    var data = "vehicleid=" + vehicleid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function(json) {
            jQuery('#Mute').modal('show');
            jQuery("#header-mute").html('Unmute Temperature For Vehicle No.- ' + json);
            jQuery("#alertmsg").html('Do You Like To Unmute The Vehicle Temperature?');
            jQuery("#notemsg").html('Note - This will help you consider the values for temperature compliance.');
        }
    });
}

function updateVehicleMute() {
    var vehicleid = jQuery("#vehicle_id").val();
    var temp = jQuery("#temp").val();
    var condition = jQuery("#condition").val();
    var customerno = jQuery('#customerno').val();
    var userid = jQuery('#userid').val();
    var data = "vehcileid=" + vehicleid + "&temp=" + temp + "&condition=" + condition + "&userid=" + userid + "&customerno=" + customerno;
    jQuery("#loader").show();
    jQuery("#save").hide();
    jQuery.ajax({
        type: "POST",
        url: "muteVehicleTemp.php",
        data: data,
        cache: false,
        success: function(html) {
            jQuery('#Mute').modal('hide');
            loadrefresh();
        }
    });
}

function routehistopen(vehicleid) {
    window.open("../reports/reports.php?vehicleid=" + vehicleid, "_blank");
}

function travelhistopen(vehicleid) {
    window.open("../reports/reports.php?id=2&vehicleid=" + vehicleid, "_blank");
}

function tempreport(vehicleid, sensor, deviceid) {
    window.open("../reports/reports.php?id=13&vehicleid=" + vehicleid + "&tempsen=" + sensor + "&devid=" + deviceid, "_blank");
}

function humreport(vehicleid, deviceid) {
    window.open("../reports/reports.php?id=48&vehicleid=" + vehicleid + "&deviceid=" + deviceid, "_blank");
}

function refreshInterval1(time1, type) {
    if (customerrefreshfrq == 267) {
        periodictime = 9000;
        refreshtime = 10000;
    } else {
        refreshtime = time1 * 60 * 1000;
        periodictime = refreshtime - 1000;
    }
    Minutes = (60 * time1 * 1000);
    var data1 = "refreshTime=" + time1;

    if (type == "rtd") {
        jQuery.ajax({
            type: "POST",
            url: "rtd_ajax.php",
            data: data1,
            cache: false,
            success: function(html1) {}
        });
    } else if (type == "whrtd") {
        jQuery.ajax({
            type: "POST",
            url: "../warehouse/rtd_ajax.php",
            data: data1,
            cache: false,
            success: function(html1) {}
        });
        ajaxurl = "";
    }
    periodicupdateloadrefresh();
}

function exportTo(type) {
    var dataString = "export=" + type + "&all=1";
    window.open("rtd_map_ajax.php?" + dataString, '_blank');
}

function getPosition(str, m, i) {
    return str.split(m, i).join(m).length;
}

function startTimer(duration, display) {
    clearInterval(interval);
    var timer = duration / 1000,
        minutes, seconds;
    interval = setInterval(function() {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(minutes + ":" + seconds);

        if (--timer < 0) {
            //            if (min1 != 0) {
            //                timer = min1;
            //            }
            //            else {
            timer = duration;
            //            }
        }
    }, 1000);
}

function updateVehicleCommonStatus(vehicleid, selectorId) {
    jQuery('#pageloaddiv').show();

    /* debugger; */
    console.log("Here in updateVehicleCommonStatus and vehicleid is: " + vehicleid);
    //  console.log("Selector id is: "+this.val());
    //console.log("Selcted status id: "+selectorId);
    jQuery.ajax({
        type: "POST",
        url: "rtd_map_ajax.php",
        data: { action: 'updateVehicleCommonStatus', vehicleId: vehicleid, vehicleStatusId: selectorId },
        cache: false,
        success: function(data) {
            $("#pageloaddiv").hide();
            console.log(data);
            loadrefresh();
        },
        complete: function() {
            jQuery('#pageloaddiv').hide();
        }

    });
}