//
var play = 0;
var map;
var vehicleno;
var SDDate;
var EDDate;
var Shour;
var Ehour;
var Smin;
var Emin;
var marker22;
var markersfordel = [];
var circlesfordel = [];
var marker_car = [];
var results = [];
var data_valid = false;
var infowindow_moving;
var vehiclename;
var i = 0;
var speeds = 3000;
var counter = 1;
//var counterOn = true;
var delay = 200;
var lastRun;
var tempDelay;
var intervalId;
var customerno = 0;
var isMCGMServer = 0;
jQuery.noConflict();
jQuery(document).ready(function () {
    initialize();
    var browserHeight = jQuery(window).height();
    jQuery('#footer').css("margin", '-37px auto 0');
    jQuery('.post').css("margin", 0);
    jQuery('.slider').slider().on('slide', function (ev) {
        if (jQuery(this).val() != '') {
            delay = (100 * (10 - jQuery(this).val()));
        }
    });
    $('.map-print').on('click', printMaps);
    if (isMCGMServer) {
        jQuery('#tdholdtime').hide();
        jQuery('#holdtime').val('600');
        jQuery('#map').css("height", browserHeight - 125);
    }
    else {
        jQuery('#map').css("height", browserHeight - 200);
    }
    customerno = jQuery('#customerno').val();
    var reportview = GetParameterValues('report');
    var userkey = GetParameterValues('userkey');
    if (userkey != '' && typeof userkey !== "undefined") {
        if (reportview != 'view') {
            $('#divheader').hide();
            $("#footer").hide();
        }
    }
    /* Get the parameters from querystring for direct playing of route history */
    var SDate = GetParameterValues('sdate');
    var STime = GetParameterValues('stime');
    var EDate = GetParameterValues('edate');
    var ETime = GetParameterValues('etime');
    var deviceid = GetParameterValues('deviceid');
    var vehicleno = decodeURI(GetParameterValues('vehicleno'));
    var vehicleid = decodeURI(GetParameterValues('vehicleid'));
    var reporttype = 0;
    var overspeed = 50;
    var holdtime = 30;
    if (isMCGMServer) {
        holdtime = 600;
    }
    //alert(vehicleid);
    //alert(SDate);
    //console.log(vehicleid);
    //console.log(vehicleno);
    if(vehicleid == undefined &&  vehicleno == undefined){
    }else if(vehicleid != undefined){
        //alert('1');
        if(typeof vehicleno != undefined && vehicleno == "undefined"){
            console.log(vehicleno);
        }else{
            jQuery('#SDate').val(SDate);
        jQuery('#STime').val(STime);
        jQuery('#EDate').val(EDate);
        jQuery('#ETime').val(ETime);
        jQuery('#reporttype').val(reporttype);
        jQuery('#overspeed').val(overspeed);
        jQuery('#holdtime').val(holdtime);
        changereport();
        }
    }else if (SDate != undefined) {
        //alert('2');
        jQuery('#SDate').val(SDate);
        jQuery('#STime').val(STime);
        jQuery('#EDate').val(EDate);
        jQuery('#ETime').val(ETime);
        jQuery('#vehicleno').val(vehicleno);
        jQuery('#vehicleid').val(vehicleid);
        jQuery('#deviceid').val(deviceid);
        jQuery('#reporttype').val(reporttype);
        jQuery('#overspeed').val(overspeed);
        jQuery('#holdtime').val(holdtime);
        changereport();
        chk_play_history();
        /* Hide route history form for SNB Infra *///alert(vehicleid);
        if (vehicleid != '' && vehicleid != undefined) {
            jQuery('.hideTr').hide();
        }
    }
    /* Play route history automatically if vehicle id is passed from map */
    var hidVehId = jQuery('#getvehicleid').val();
    //alert(hidVehId);
    if (hidVehId != '' && typeof hidVehId !== "undefined") {
        changereport();
        chk_play_history();
    }
    /* Auto dropdown for Vehicle No. */
    jQuery("#vehicleno").autocomplete({
        source: "autocomplete.php?action=dummyData",
        minLength: 1,
        select: function (event, ui) {
            jQuery('#vehicleid').val(ui.item.vehicleid);
            if (ui.item.groupname != '' && ui.item.groupname != null) {
                jQuery('#route_groupname').val(ui.item.groupname);
            }
            else {
                jQuery('#route_groupname').val("Not assigned");
            }
        }
    });
});
var printMaps = function () {
    var popUpAndPrint = function () {
        jQuery('#map').css("width", '100%');
        jQuery('#btnPrint').css("display", 'none');
        jQuery('#sidebar').css("margin", 'auto');
        jQuery('#sidebar').css("width", '700px');
        dataUrl = [];
        jQuery('#map canvas').filter(function () {
            dataUrl.push(this.toDataURL("image/png"));
        });
        var mapContainer = document.getElementById('map');
        var mapContainerClone = jQuery(mapContainer).clone();
        jQuery(mapContainerClone).find('canvas').each(function (i, item) {
            jQuery(item).replaceWith(
                    jQuery('<img>')
                    .attr('src', dataUrl[i]))
                    .css('position', 'absolute')
                    .css('left', '0')
                    .css('top', '0')
                    .css('width', '100%')
                    .css('height', '100%');
        });
        var sideContainer = jQuery('#sidebar');
        var sideContainerClone = jQuery(sideContainer).clone();
        var footerContainer = jQuery("#footer");
        var footerContainerClone = jQuery(footerContainer).clone();
        footerContainerClone.removeAttr("style");
        var printContainer = $('<div>');
        printContainer
                .addClass('print-container')
                .css('position', 'relative')
                .append(sideContainerClone)
                .append(mapContainerClone)
                .append(footerContainerClone);
        jQuery('#map').css("width", '85%');
        jQuery('#sidebar').css("width", '13%');
        jQuery('#btnPrint').show();
        var width = printContainer.clientWidth;
        var height = printContainer.clientHeight;
        var printWindow = window.open('', 'PrintMap', 'width=' + width + ',height=' + height);
        var printstyle = "<head><link rel='stylesheet' type='text/css' href='../../style/style.css'><style type='text/css' media='print'> @page { size: landscape; } </style></head>";
        printWindow.document.writeln("<html>" + printstyle + "<body>" + jQuery(printContainer).html() + "</body></html>");
        printWindow.focus();
        printWindow.print();
        printWindow.document.close();
        printWindow.close();
        printContainer.remove();
    };
    setTimeout(popUpAndPrint, 500);
};
function changereport()
{
    var flag = jQuery("#reporttype").val();
    if (flag == 0)
    {
        jQuery('#overspeed_limit').show();
        jQuery('#temp_limit').hide();
    }
    else if (flag == 1 || flag == 2)
    {
        jQuery('#overspeed_limit').hide();
        jQuery('#temp_limit').show();
    }
    else
    {
        jQuery('#overspeed_limit').hide();
    }
}
function incrementCounter() {
    //$counter.val(counter++);
// console.log("testingause",counter++);
    timeoutId = setTimeout(incrementCounter, delay);
    lastRun = new Date();
    if (results.length > 0) {//console.log("has data");
        addDot();
    }
    // if(results.length>0){console.log("has data");addDot();}else if(check_data_for_validity()==true){console.log("not has data");//sync_start();}else{console.log("data not valid");}
}
incrementCounter();
function play_history() {
    if (results.length > 0) {
        var curTime = new Date();
        lastRun = curTime.valueOf() + tempDelay - delay;
        timeoutId = setTimeout(addDot, tempDelay);
        incrementCounter();
    }
    else {
        if (check_data_for_validity() == true) {
            //console.log("has data");addDot();
            console.log("not has data");
            sync_start();
        }
        else {
        }
    }
}
function pause_history() {
    var curTime = new Date();
    clearTimeout(timeoutId);
    tempDelay = delay - (curTime.valueOf() - lastRun);
}
function addDot() {
    if (results.length > 0) {
        vehiclename = jQuery("#vehicleno").val();
        var templimit = jQuery("#temp").val();
        var color1 = "#00FF00";
        if (i < results.length) {
            var flag = jQuery("#reporttype").val();
            if (flag == 0)
            {
                if (parseInt(results[i].cspeed) < jQuery("#overspeed").val()) {
                    color1 = "#00FF00";
                }
                else {
                    color1 = "#FF0000";
                }
            }
            else {
                if (parseInt(results[i].temp) < templimit) {
                    color1 = "#00FF00";
                }
                else {
                    color1 = "#FF0000";
                }
            }
        }
        if (i < results.length - 1) {
            results[i].cumulative = parseFloat(results[i].cumulative).toFixed(2);
            var polyline = new google.maps.Polyline({
                path: [new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong), new google.maps.LatLng(results[i + 1].cgeolat, results[i + 1].cgeolong)],
                strokeColor: color1,
                geodesic: true,
                strokeWeight: 2
            });
            polyline.setMap(map);
            if (!results[i].temp)
            {
                if (results[i].users_spec == '1')
                {
                    var contentString = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br> </p>';
                }
                else
                {
                    var contentString = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br> Distance Travelled: ' + results[i].cumulative + ' km<br> </p>';
                }
            }
            else {
                if (results[i].users_spec == '1')
                {
                    var contentString = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br>Temperature : ' + results[i].temp + ' &#x2103; </p>';
                }
                else
                {
                    var contentString = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br> Distance Travelled: ' + results[i].cumulative + ' km<br> Temperature : ' + results[i].temp + ' &#x2103;</p>';
                }
            }
        }
        // condition for start point
        if (i == 0) {
            map.setCenter(new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong));
            map.setZoom(13);
            var markers = new google.maps.Marker({
                position: new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong),
                map: map,
                icon: "../../images/Sflag.png"
            });
            var infowindows = new google.maps.InfoWindow({
                content: contentString
            });
            markers.setMap(map);
            if (customerno != 277 && customerno != 293) {
                google.maps.event.addListener(markers, 'click', function () {
                    infowindows.open(map, markers);
                });
                infowindows.open(map, markers); // Start Point Hide For 277
            }
            // moving marker
            marker22 = new google.maps.Marker({
                position: new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong),
                map: map,
                icon: GetVehIcon(results[i].directionchange)
            });
            infowindow_moving = new google.maps.InfoWindow({
                content: contentString,
            });
            infowindow_moving.setPosition(new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong));
            marker22.setMap(map);
            if (customerno != 277 && customerno != 293) {
                google.maps.event.addListener(marker22, 'click', function () {
                    infowindow_moving.open(map, marker22);
                });
                infowindow_moving.open(map, marker22); // Moving Marker Hide For 277
            }
        }
        if (i == (results.length - 1)) {
            infowindow_moving.close(); // Moving Marker Hide
            var marker33 = new google.maps.Marker({
                position: new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong),
                map: map,
                icon: "../../images/Eflag.png"
            });
            console.log('// finish' + i);
            marker33.setMap(map);
            if (!results[i].temp)
            {
                if (results[i].users_spec == '1')
                {
                    var contentString2 = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br> </p>';
                }
                else
                {
                    var contentString2 = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br> Distance Travelled: ' + results[i].cumulative + ' km<br> </p>';
                }
            }
            else {
                if (results[i].users_spec == '1')
                {
                    var contentString2 = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br>Temperature : ' + results[i].temp + ' &#x2103; </p>';
                }
                else
                {
                    var contentString2 = '<p style="width:150px;font-size:11px;"><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                            '' + results[i].cspeed + ' km/hr <br>  ' + results[i].clastupdated + '<br> Distance Travelled: ' + results[i].cumulative + ' km<br> Temperature : ' + results[i].temp + ' &#x2103;</p>';
                }
            }
            var infowindow23 = new google.maps.InfoWindow({
                content: contentString2
            });
            if (customerno != 277 && customerno != 293) {
                google.maps.event.addListener(marker33, 'click', function () {
                    console.log('// finish');
                    infowindow23.open(map, marker33);
                });
                infowindow23.open(map, marker33);
            }
//      marker.setMap(null);
        }
        if (i < results.length - 2 && results.length != 0) {
            results[i].cumulative = parseFloat(results[i].cumulative).toFixed(2);
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong),
                map: map,
                icon: "../../images/point.png"
            });
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });
            if (customerno != 277 && customerno != 293) {
                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.open(map, marker);
                });
            }
            marker.setMap(map);
            /// holdtime
            if (results[i].holdtime == 1) {
                console.log("1inside" + results[i].holdtime);
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong),
                    map: map,
                    icon: "../../images/marker_stop.png"
                });
                if (!results[i].temp) {
                    if (results[i].users_spec == '1')
                    {
                        var contentString2 = '<p style="width:150px;font-size:11px;" ><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                                '' + results[i].location + ' <br><b style="color:red;"> Stopped for ' + results[i].total_hold_time + ' min</b> <br> ' + results[i].clastupdated + '<br> </p>';
                    }
                    else
                    {
                        var contentString2 = '<p style="width:150px;font-size:11px;" ><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                                '' + results[i].location + ' <br><b style="color:red;"> Stopped for ' + results[i].total_hold_time + ' min</b> <br> ' + results[i].clastupdated + '<br> Distance Travelled: ' + results[i].cumulative + ' km<br><br><a href="#test_" data-toggle="modal" onclick="assign_values_to_inputs(\'' + results[i].location + '\',\'' + results[i].cgeolat + '\', \'' + results[i].cgeolong + '\');" >Add As Checkpoint</a></p>';
                    }
                }
                else {
                    if (results[i].users_spec == '1')
                    {
                        var contentString2 = '<p style="width:150px;font-size:11px;" ><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                                '' + results[i].location + ' <br><b style="color:red;"> Stopped for ' + results[i].total_hold_time + ' min</b> <br> ' + results[i].clastupdated + '<br>Temperature : ' + results[i].temp + ' &#x2103;</p>';
                    }
                    else
                    {
                        var contentString2 = '<p style="width:150px;font-size:11px;" ><b style="color:#0074CC;font-size:14px;">' + vehiclename + '</b><br>' +
                                '' + results[i].location + ' <br><b style="color:red;"> Stopped for ' + results[i].total_hold_time + ' min</b> <br> ' + results[i].clastupdated + '<br> Distance Travelled: ' + results[i].cumulative + ' km<br>  Temperature : ' + results[i].temp + ' &#x2103;<br/><a href="#test_" data-toggle="modal" onclick="assign_values_to_inputs(\'' + results[i].location + '\',\'' + results[i].cgeolat + '\', \'' + results[i].cgeolong + '\');" >Add As Checkpoint</a></p>';
                    }
                }
                var infowindow = new google.maps.InfoWindow({
                    content: contentString2
                });
                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.open(map, marker);
                    //click_to_open_info_window(new google.maps.LatLng(results[i].cgeolat, results[i].cgeolong));
                });
                marker.setMap(map);
            }
            else {
                //  console.log("2"+results[i].holdtime);
            }
            infowindow_moving.setPosition(new google.maps.LatLng(results[i + 1].cgeolat, results[i + 1].cgeolong));
            infowindow_moving.setContent(contentString),
                    marker22.setPosition(new google.maps.LatLng(results[i + 1].cgeolat, results[i + 1].cgeolong));
            marker22.setIcon(GetVehIcon(results[i + 1].directionchange));
            if ((i != results.length - 1) || (i != 0)) {
                var bounds = map.getBounds()
                if (!bounds.contains(new google.maps.LatLng(results[i + 1].cgeolat, results[i + 1].cgeolong))) {
                    map.panTo(new google.maps.LatLng(results[i + 1].cgeolat, results[i + 1].cgeolong));
                }
            }
        }
        i++;
        if (play == 0) {
            return; //stop the execution of function
        }
        else {
            //keep on going
            addDot();
        }
    }
}
function click_to_open_info_window(latlong, time) {
}
function sync_start() {
    var Startdate = jQuery('#SDate').val() + " " + jQuery('#STime').val() + ":00";
    var Enddate = jQuery('#EDate').val() + " " + jQuery('#ETime').val() + ":59";
    var holdtime = jQuery('#holdtime').val();
    var grpname = jQuery('#route_groupname').val();
    var vehicle = jQuery('#vehicleno').val();
    var toggleTripId = jQuery('#toggleTripId').val();
    console.log("step 4:// pulling data");
    console.log(Startdate, Enddate, vehicleno);
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php?work=routehistory",
        async: true,
        cache: false,
        data: {
            vehicleid: vehicleno,
            SDate: Startdate,
            EDate: Enddate,
            holdtime: holdtime
        },
        success: function (data) {
            var cdata = jQuery.parseJSON(data);
            results = cdata.result;
            //alert(results[0].location);
            var lgn = (results.length) - 1;
            //var te = results[0].clastupdated.slice(16,22);
            //alert(te);//clastupdated
            jQuery('#map').css("width", '85%');
            jQuery('#sidebar').css("width", '13%');
            jQuery('#sidebar').show();
            jQuery("#stlocation").html(results[0].location);
            jQuery("#endlocation").html(results[lgn].location);
            if (customerno == 277 || customerno == 293) {
                jQuery("#stdate").html(jQuery('#SDate').val());
                jQuery("#enddate").html(jQuery('#EDate').val());
                jQuery("#sttime").html(jQuery('#STime').val());
                jQuery("#endtime").html(jQuery('#ETime').val());
            }
            else {
                var EDate = GetParameterValues('edate');
                var ETime = GetParameterValues('etime');
                if (EDate !== undefined) {
                    var match = (EDate + " " + ETime).match(/^(\d+)-(\d+)-(\d+) (\d+)\:(\d+)$/)
                    var endDate = new Date(match[3], match[2] - 1, match[1], match[4], match[5]);
                    var strEndDate = GetDateString(endDate, "D dd-MMM-yy HH:mm");
                    results[lgn].clastupdated = strEndDate;
                }
                jQuery("#stdate").html(results[0].clastupdated.slice(4, 16));
                jQuery("#enddate").html(results[lgn].clastupdated.slice(4, 16));
                jQuery("#sttime").html(results[0].clastupdated.slice(16, 22));
                jQuery("#endtime").html(results[lgn].clastupdated.slice(16, 22));
            }
            jQuery("#route_vehicleview").html(vehicle);
            jQuery("#route_groupnameview").html(grpname);
            jQuery("#route_distance").html(results[lgn].cumulative + " km");
            if (customerno != 617 && customerno != 606) {
                getWeighDetails();
            }
        }
    });
}
function distance(lat1, lon1, lat2, lon2) {
    var R = 6378;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d;
}
//sync_start();
function check_data_for_validity() {
    console.log("step 2 :validate date and then pull the details");
    vehicleno = jQuery('#vehicleid').val();
    var dtDate1 = jQuery('#SDate').val().split('-');
    var dtDate2 = dtDate1[2] + '/' + dtDate1[1] + '/' + dtDate1[0];
    var dtDate3 = jQuery('#EDate').val().split('-');
    var dtDate4 = dtDate3[2] + '/' + dtDate3[1] + '/' + dtDate3[0];
    var nDifference = Math.abs(new Date(dtDate4) - new Date(dtDate2));
    var one_day = 1000 * 60 * 60 * 24;
    var diff = Math.round(nDifference / one_day);
    SDDate = (jQuery('#SDate').val()).split('-');
    SDDate = new Date(SDDate[2], SDDate[1] - 1, SDDate[0]);
    EDDate = (jQuery('#EDate').val()).split('-');
    EDDate = new Date(EDDate[2], EDDate[1] - 1, EDDate[0]);
    if (vehicleno == -1) {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
        return data_valid = false;
    }
    else if (SDDate > EDDate) {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
        return data_valid = false;
    }
    else if (diff > 7) {
        jQuery('#error5').show();
        jQuery('#error5').fadeOut(3000);
        return data_valid = false;
    }
    else {
        console.log("step 3:// data valid ready to pul and sync");
        return data_valid = true;
    }
}
function initialize(){
//    console.log("step 1 map init");
    var styledMap = new google.maps.StyledMapType(styles,
            {name: "Styled Map"});
    var latlng = new google.maps.LatLng(20, 72);
    var mapOptions = {
        zoom: 5,
        center: latlng,
        panControl: true,
        streetViewControl: false,
        fullscreenControl: true,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        styles: styles
    };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
}
function convert(str) {
    var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-");
}
function reversedate(myDate){
    //only for d-m-y
    reversed='';
   if(typeof myDate === "undefined"){
   }else{
    var pieces = myDate.split('-');
    pieces.reverse();
    var reversed = pieces.join('-');
   }
    return reversed;
}
function chk_play_history(){
    vehicleno = jQuery('#vehicleid').val();
    if (jQuery('#vehicleno').val() == '')
    {
        vehicleno = "";
    }
    var SDDate1 = jQuery('#SDate').val();
    var EDDate = jQuery('#EDate').val();
    var StartDate = jQuery('#s_start').val();
    var EndDate = jQuery('#e_end').val();
    var SDDate = reversedate(SDDate1);
    EDDate = reversedate(EDDate);
    StartDate = reversedate(StartDate);
    EndDate = reversedate(EndDate);
    if (vehicleno == '')
    {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
        return false;
    } else if (isVariableDefined(StartDate) && isVariableDefined(EndDate) && ((Date.parse(SDDate) < Date.parse(StartDate)) || (Date.parse(EDDate) > Date.parse(EndDate)))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
        return false;
    }
    var days = jQuery('#days').val();
    if (isVariableDefined(days) && days != '' && days != 0) {
        var now = new Date();
        var now1 = new Date();
        var test1 = now1.setDate(now1.getDate());
        var test = now.setDate(now.getDate() - days); // add -7 days to your date variable
        var caluclated_date = convert(now);
        var todaysdate = convert(now1);
        var testdate = caluclated_date.split("-").reverse().join("-");
        var newSDate = SDDate1.split("-").reverse().join("-");
        if (Date.parse(newSDate) < Date.parse(caluclated_date)) {
            jQuery('#error8').show();
            jQuery('#error8').fadeOut(3000);
            jQuery("#SDate").val(testdate);
        }
        if (Date.parse(EDDate) > Date.parse(todaysdate)) {
            var todaysdate = todaysdate.split("-").reverse().join("-");
            jQuery("#EDate").val(todaysdate);
        }
    }
    if (jQuery("#reporttype").val() != '0' && (jQuery('#temp').val() == '' || jQuery('#temp').val() == '>')) {
        //alert(jQuery('#temp').val());
        jQuery('#error7').show();
        jQuery('#error7').fadeOut(3000);
        return false;
    }
    if ($("#displaycheckpoints").is(':checked')) {
        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php?work=checkpointlist",
            async: true,
            cache: false,
            data: {
                vehicleid: vehicleno
            },
            success: function (data) {
                var cdata = jQuery.parseJSON(data);
//alert(data);
                var results = cdata.result;
                //checkpoint start
                jQuery.each(results, function (i, device) {
                    var circle;
                    var rad;
                    var chklatlng;
                    var chkrad = device.crad;
                    var chkname = device.cname;
                    var chklatlngp = new google.maps.LatLng(device.cgeolat, device.cgeolong);
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
                        fillColor: '#000000',
                        strokeColor: '#000000',
                        strokeweight: 1
                    });
                    circle.bindTo('center', marker, 'position');
                    circlesfordel.push(circle);
                    markersfordel.push(marker);
                });
                //checkpoint end
            }
        });
    }
    if (jQuery("#reporttype").val() != '0' && jQuery('#temp').val() != '' && jQuery('#temp').val() != '>') {
        play_history();
    }
    if (jQuery("#reporttype").val() == '0') {
        play_history();
    }
}
function displaycheckpoints() {
    var vehicleno = jQuery('#vehicleid').val();
    if (jQuery('#vehicleno').val() == '')
    {
        vehicleno = '';
    }
    if (vehicleno == '')
    {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
        return false;
    }
    else {
        if (jQuery("#displaycheckpoints").is(':checked') == true) {
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php?work=checkpointlist",
                async: true,
                cache: false,
                data: {
                    vehicleid: vehicleno
                },
                success: function (data) {
                    var cdata = jQuery.parseJSON(data);
                    //alert(data);
                    var results = cdata.result;
                    //checkpoint start
                    // alert('true'); return false;
                    jQuery.each(results, function (i, device) {
                        var circle;
                        var rad;
                        var chklatlng;
                        var chkrad = device.crad;
                        var chkname = device.cname;
                        var chklatlngp = new google.maps.LatLng(device.cgeolat, device.cgeolong);
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
                            fillColor: '#000000',
                            strokeColor: '#000000',
                            strokeweight: 1
                        });
                        circle.bindTo('center', marker, 'position');
                        circlesfordel.push(circle);
                        markersfordel.push(marker);
                    });
                    //checkpoint end
                }
            });
        }
        else {
            jQuery.each(markersfordel, function (index, value) {
                marker = markersfordel[index];
                marker.setMap(null);
            });
            jQuery.each(circlesfordel, function (index, value) {
                circle = circlesfordel[index];
                circle.setMap(null);
            });
        }
    }
}
function GetVehIcon(Angle)
{
    var base = "../../images/vehicles/Car/Normal";
    var icon = base + "/Car0.png";
    var vtype = "Car";
    icon = base + "/" + vtype + Angle + ".png";
    return icon;
}
function refresh() {
    console.log("refreshed the whole page");
    window.location.href = window.location.href;
}
function getWeighDetails(){
    var toggleTripId = jQuery('#toggleTripId').val();
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php?work=weighBridgeDetails",
        async: false,
        cache: false,
        data: {
            toggleTripId: toggleTripId,
        },
        success: function (data) {
            //console.log(data);
            if(data !=null){
                var cdata = jQuery.parseJSON(data);
                //console.log(cdata);
                if(cdata[0]!=null){
                    jQuery("#route_grossWeight").html(cdata[0].grossWeight);
                    jQuery("#route_netWeight").html(cdata[0].netWeight);
                    jQuery("#route_unladenWeight").html(cdata[0].unladenWeight);
                }
            }
        }
    });
}
var styles = [
    {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "simplified"}, {"lightness": 46}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#9e9e9f"}]}, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#bfbfbf"}]}, {"featureType": "road.local", "elementType": "geometry.fill", "stylers": [{"color": "#e0e0e0"}]}, {"featureType": "poi.park", "stylers": [{"lightness": 38}]}, {"stylers": [{"saturation": -54}]}];
