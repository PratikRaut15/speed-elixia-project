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
var Minutes = 30 ;
var display = jQuery('#time');
var interval;
var currentPage = 1;

var periodictime = (Minutes * 1000) - 1000;
var refreshtime = Minutes * 1000;


jQuery(document).ready(function () {
    loadrefresh();
    periodicupdateloadrefresh();
    startTimer(Minutes, display);
});

function loadrefresh()
{
    clearInterval(interval);
    var ids = "";
    jQuery("tr:not([filtermatch]) input[id^='latlong']").each(function (i) {
        var data;
        data = (jQuery(this).attr('id')).replace("latlong", "");
        ids += data + ",";
    });
    ids = ids.slice(0, -1);
    var count = 0, flag = 0;
    jQuery("input[id^='search_table_filter']").each(function () {
        count++;
        if (jQuery(this).val() == "") {
            flag++;
        }
    });

    var userkey = jQuery("#userkey").val();
    var pageSize = jQuery("#pageSize").val();
    var totalPageSize = jQuery("#totalPageSize").val();
    var currentPageSize = jQuery("#pageIndex").val();
    if(totalPageSize == currentPageSize) {
        currentPage = 1;
    }




    var UrlSpc;

        UrlSpc = "rtd_map_ajax.php?all=1&userkey="+userkey+"&pageSize="+pageSize+"&pageIndex="+currentPage;

    jQuery.ajax({
        type: "POST",
        url: UrlSpc,
        cache: false,
        success: function (data) {
            jQuery("#pageIndex").val(currentPage);
            jQuery("#tblRows").html(data);
            currentPage++;
            var today = new Date();
            //alert(today);
              var h = today.getHours();
              var m = today.getMinutes();
              var s = today.getSeconds();
            jQuery('#currentTimeStamp').text(h + ":" + m);
            startTimer(Minutes, display);
            periodicupdateloadrefresh();
            jQuery('#pageloaddiv').hide();

        }
    });
}
function periodicupdateloadrefresh()
{
    for (var i = 0; i < timeouts.length; i++) {
        clearTimeout(timeouts[i]);
    }
    timeouts.push(setTimeout(function () {
        loadrefresh();
    }, periodictime));

}
function startTimer(duration, display) {
    clearInterval(interval);
    var timer = duration / 1000, minutes, seconds;
    interval = setInterval(function () {
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
