<form action="reports.php?id=14" method="POST" id="overspeedreportForm" onsubmit="getOverspeedReport();
    return false;">
        <?php
        $title = 'Speed Report';
        $today = date('d-m-Y');
        include 'panels/overspeedreport.php';
        if(isset($_SESSION['ecodeid'])){
        ?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
        <input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo $_SESSION['codecalculateddate']; ?>" />
    <?php } ?>
  <tr>
    <td>Vehicle No.</td>
    <td>Start Date</td>
    <td>Start Hour</td>
    <td>End Date</td>
    <td>End Hour</td>
  </tr>
  <tr>
    <td>
      <input  type="text" name="vehicleno" id="vehicleno" size="18" value="" autocomplete="off" placeholder="Enter Vehicle No" required/>
      <input type="hidden" name="deviceid" id="deviceid" size="20" value=""/>
      <div id="display" class="listvehicle"></div>
    </td>
    <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
    <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
    <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
    <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59" /></td>
    <td><input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
    <td>
      <a href='javascript:void(0)' onclick="get_pdfreportOverspeed(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
      <a href='javascript:void(0)' onclick="html2xlsoverspeed(<?php echo $_SESSION['customerno']; ?>);
          return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
      <a href='javascript:void(0)' onclick="get_speed_print('<?php echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
      <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("")';><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
    </td>
  </tr>
</tbody>
</table>
</form>
<br><br>
<center id='centerDiv'></center>
  <?php
  $mail_function = "send_overspeed_mail(" . $_SESSION['customerno'] . ");";
  include_once "mail_pop_up.php";
  ?>
<script type="text/javascript" src="createcheck.js"></script>
<script>
        function fill(Value, strparam){
          jQuery('#vehicleno').val(strparam);
          jQuery('#deviceid').val(Value);
          jQuery('#display').hide();
        }

        function getOverspeedReport(){
          jQuery('#centerDiv').html('');
          jQuery('#pageloaddiv').show();
          var data = jQuery("#overspeedreportForm").serialize();
          jQuery.ajax({
            url: "overspeedreport_ajax.php",
            type: 'POST',
            data: data,
            success: function (result) {
              jQuery("#centerDiv").html(result);
            },
            complete: function () {
              jQuery('#pageloaddiv').hide();
            }
          });
        }
        $(function (){
          var SDate = GetParameterValues('sdate');
          var STime = GetParameterValues('stime');
          var EDate = GetParameterValues('edate');
          var ETime = GetParameterValues('etime');
          var deviceid = GetParameterValues('deviceid');
          var vehicleno = decodeURI(GetParameterValues('vehicleno'));
          var userkey = GetParameterValues('userkey');
            if (userkey != '' && typeof userkey !== "undefined"){
                $('#divheader').hide();
                $("#footer").hide();
            }

          if (SDate != undefined) {
            jQuery('#SDate').val(SDate);
            jQuery('#STime').val(STime);
            jQuery('#EDate').val(EDate);
            jQuery('#ETime').val(ETime);
            jQuery('#deviceid').val(deviceid);
            jQuery('#vehicleno').val(vehicleno);
            getOverspeedReport();
          }
          $("#vehicleno").autoSuggest({
            ajaxFilePath: "autocomplete.php",
            ajaxParams: "dummydata=location",
            autoFill: false,
            iwidth: "auto",
            opacity: "0.9",
            ilimit: "10",
            idHolder: "id-holder",
            match: "contains"
          });
        });


</script>
<script src="../../scripts/highstock/highstock.js" type='text/javascript'></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>