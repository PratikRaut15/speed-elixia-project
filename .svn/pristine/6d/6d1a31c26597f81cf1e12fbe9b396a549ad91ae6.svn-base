<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include 'reports_stoppage_functions.php';
    $STdate = $_POST['STdate'];
    $EDdate = $_POST['EDdate'];
    if (isset($_SESSION['ecodeid'])) {
        /*Client Code Validation */
        $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
        if (isset($validation) && !empty($validation)) {
            if ($validation['isError'] == 1) {
                echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                die();
            } else {
                $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
            }
        }
    }
    /*Date And Date Diff Check*/
    $datecheck = datediff($STdate, $EDdate);
    $datediffcheck = date_SDiff($STdate, $EDdate);
    if ($datecheck == 1) {
        $datediffcheck = date_SDiff($STdate, $EDdate);
        if ($datediffcheck <= 30) {
            $interval = GetSafeValueString($_POST['interval'], 'long');
            $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
            include 'pages/panels/stoppagerep.php';
            getstoppagereport($STdate, $EDdate, $deviceid, $_REQUEST['STime'], $_REQUEST['ETime'], $interval);
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
<script type='text/javascript'>
jQuery('.STime_pop').timepicker({
    minuteStep: 1,
    showInputs: false,
    disableFocus: true,
    showMeridian: false,
    defaultTime: curtime
});
function addStoppageReason(vehicleid, starttime, endtime, lat, lng, timestamp, customerno, userid) {
    var reason      = jQuery('#reason_'+timestamp).val();
    var reasontext  = jQuery('#reason_'+timestamp+' option:selected').text();
    if(reasontext=='Others') // Strict checking for others which considered as remark
    {
      var functionParameters = vehicleid+','+starttime+','+endtime+','+lat+','+lng+','+timestamp+','+customerno+','+userid+','+reason;
      jQuery('#otherReasonSubmitButton').attr('onClick','addOtherReason('+functionParameters+')');
      jQuery("#reasonModal").modal('show');
    }
    else
    {
        var data = "work=addReason&vehicleid="+vehicleid+"&starttime="+starttime+"&endtime="+endtime+"&lat="+lat+"&lng="+lng+"&reason="+reason+"&customerno="+customerno+"&userid="+userid;
          jQuery.ajax({
            url:"route_ajax.php",
            type: 'POST',
            data: data,
            success:function(result){
                if(result > 0) {
                    var sid= result;
                jQuery('#reasonEdit_'+timestamp).hide();
                reasontextVar   = "<span class='"+sid+"'>"+reasontext+"</span>";
                jQuery('#reason'+timestamp).html(reasontextVar);
            // $("input .g-button-submit" ).trigger( "click" );
                }
            }
        });
    }
    //alert(reason);
   /* var data = "work=addReason&vehicleid="+vehicleid+"&starttime="+starttime+"&endtime="+endtime+"&lat="+lat+"&lng="+lng+"&reason="+reason+"&customerno="+customerno+"&userid="+userid;
     jQuery.ajax({
        url:"route_ajax.php",
        type: 'POST',
        data: data,
        success:function(result){
            if(result > 0) {
                var sid= result;
             jQuery('#reasonEdit_'+timestamp).hide();
             reasontextVar   = "<span class='"+sid+"'>"+reasontext+"</span>";
             jQuery('#reason'+timestamp).html(reasontextVar);
           // $("input .g-button-submit" ).trigger( "click" );
            }
        }
    }); */
}
function addOtherReason(vehicleid, starttime, endtime, lat, lng, timestamp, customerno, userid, reason)
{
    var otherReason = jQuery("#otherReasonText").val();
    if(otherReason==null || otherReason=='' || otherReason==undefined)
    {
        jQuery("#otherReasonValidationError").text('Please specify other reason');
        jQuery("#otherReasonValidationError").css('display','block');
    }
    else
    {
        jQuery("#otherReasonValidationError").text('');
        jQuery("#otherReasonValidationError").css('display','none');
        var data = "work=addOtherReason&vehicleid="+vehicleid+"&starttime="+starttime+"&endtime="+endtime+"&lat="+lat+"&lng="+lng+"&reason="+reason+"&customerno="+customerno+"&userid="+userid+"&remarks="+otherReason;
        jQuery.ajax({
            url:"route_ajax.php",
            type: 'POST',
            data: data,
            success:function(result){
                if(result > 0) {
                    var sid= result;
                jQuery('#reasonEdit_'+timestamp).hide();
                reasontextVar   = "<span class='"+sid+"'>"+otherReason+"</span>";
                jQuery('#reason'+timestamp).html(reasontextVar);
            // $("input .g-button-submit" ).trigger( "click" );
                 jQuery("#reasonModal").modal('hide');
                }
            }
        });
    }
    console.log("Other reason is: "+otherReason);
}
function updateStoppageReason(customerno, userid,sid) {
   var updatedReasonId = $(".editbox").val();
   var updatedReason = $(".editbox option:selected").text();
    var reason = jQuery('#'+sid).val();
    var reasontext = jQuery('#'+sid+' option:selected').text();
    if(updatedReason == 'Select Reason'){
        updatedReasonId == 0;
        $('.editbox').show();
    }
   // var selid = sid.replace("reasonEdit_", "");
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
                        jQuery('.'+sid).replaceWith(reasontextVar);
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