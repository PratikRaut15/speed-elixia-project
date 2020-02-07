<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(300);

if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include 'reports_stoppage_functions.php';
    $sdate = $_POST['STdate'];
    $edate = $_POST['EDdate'];
    $newsdate = date("Y-m-d", strtotime($sdate));
    $newedate = date("Y-m-d", strtotime($edate));
    $datecheck = datediff($sdate, $edate);

    if (isset($_SESSION['ecodeid'])) {
        $startdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d', strtotime(GetSafeValueString($_POST['e_end'], 'string')));
        if (strtotime($sdate) < strtotime($startdate) || strtotime($edate) > strtotime($enddate)) {
            echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
        }
    }

    if ($datecheck == 1) {
        $datediffcheck = date_SDiff($newsdate, $newedate);
        if ($datediffcheck <= 30) {
            $STdate = GetSafeValueString($sdate, 'string');
            $EDdate = GetSafeValueString($edate, 'string');
            $interval = GetSafeValueString($_POST['interval'], 'long');
            //include 'pages/panels/stoppagerepanalysis.php';
            $data = getstoppageanalysis($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $interval, $_SESSION['groupid'], $_SESSION['customerno'], $_SESSION['userid'], speedConstants::REPORT_HTML);

            if (isset($data) && !empty($data)) {
                $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
                $placehoders['{{DATA_TABLE_HEADER}}'] = $data['tableColumns'];
                $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
                $html = file_get_contents('pages/panels/stoppagerepanalysis.php');
                foreach ($placehoders as $key => $val) {
                    $html = str_replace($key, $val, $html);
                }
                echo $html;
            }
            echo "<script>/*date: 25th oct 14, ak added*/
        jQuery('.add_button').click(function(){
            var idval = jQuery(this).attr('href');
            var this_id=idval.replace('#test_','');
            create_map_for_modal_report(this_id);
        });
        /**/</script>";
        } else {
            echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        }
    } elseif ($datecheck == 0) {
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    } else {
        echo "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    }
}
?>
<script>
jQuery('.STime_pop').timepicker({
    minuteStep: 1,
    showInputs: false,
    disableFocus: true,
    showMeridian: false,
    defaultTime: curtime
});

function addStoppageReason(vehicleid, starttime, endtime, lat, lng, timestamp, customerno, userid){
    var reason      = jQuery('#reason_'+timestamp).val();
    var reasontext  = jQuery('#reason_'+timestamp+' option:selected').text();
    var data        = "work=addReason&vehicleid="+vehicleid+"&starttime="+starttime+"&endtime="+endtime+"&lat="+lat+"&lng="+lng+"&reason="+reason+"&customerno="+customerno+"&userid="+userid;

    jQuery.ajax({
        url:"route_ajax.php",
        type: 'POST',
        data: data,
        success:function(result){
            if(result > 0) {
              jQuery('#reasonEdit_'+timestamp).hide();
             reasontextVar   = "<span class='"+sid+"'>"+reasontext+"</span>";
             jQuery('#reason'+timestamp).html(reasontextVar);
            }
        }
    });
}

function updateStoppageReason(customerno, userid,sid) {
   var updatedReasonId  = $("#reasonEdit_"+sid).val();
   var updatedReason    = $("#reasonEdit_"+sid+"  option:selected").text();
   var reason           = jQuery('#'+sid).val();
   var reasontext       = jQuery('#'+sid+' option:selected').text();
   if(updatedReason == "Select Reason"){
        updatedReasonId = 0;
    }
    var data = "work=updateReason&sid="+sid+"&reason="+updatedReasonId+"&customerno="+customerno+"&userid="+userid;
    jQuery.ajax({
        url:"route_ajax.php",
        type: 'POST',
        data: data,
        success:function(result){
            if(result > 0) {
                if(updatedReason == "Select Reason"){
                    jQuery('#reasonEdit_'+sid).show();
                }
                else{
                        jQuery('#reasonEdit_'+sid).css("display", "none");
                        reasontextVar   = "<span class='"+sid+"'>"+reasontext+"</span>";
                        jQuery('.'+sid).html(reasontextVar);
                         jQuery('.'+sid).css("display", "block");
                    }
                }
            }
        });
    }

jQuery(".edit_td").dblclick(function () {
    var ID = jQuery(this).attr('id');
    jQuery("."+ID).hide();
    jQuery("#reasonEdit_" + ID).show();
    var selId = jQuery("#reasonid_" + ID).val();
    jQuery("#reasonEdit_" + ID).val(selId);
    jQuery("#reasonEdit_" + ID).focus();
    jQuery('#'+ID).show();
});

</script>
