<?php
    include_once 'freeze_ignitionOn_function.php';
    $title = 'Freeze Ignition On Report';
    $today = date('d-m-Y');
    $reportDate = date("d-m-Y",strtotime('-1 day'));

?>
<form method="POST" id="freezeIgnOnFrm" onsubmit="getfreezeIgnOnReport();return false;">
<?php include 'panels/freezeIgnOnReport.php';?>
<?php if (isset($_SESSION['ecodeid'])) {?>
    <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
    <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
    <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
    <input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo $_SESSION['codecalculateddate']; ?>" />
<?php }?>
  <tr>
    <td>Vehicle No.</td>
    <td>Start Date</td>
    <td>End Date</td>
    <td>Generate Report</td>
    <td>Actions</td>
  </tr>
  <tr>
    <td>
      <input  type="text" name="vehicleno" id="vehicleno" size="18" value="" autocomplete="off" placeholder="Enter Vehicle No" required/>
      <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
    </td>
    <td><input id="SDate" name="STdate" type="text" value="<?php echo $reportDate; ?>" required/></td>
    <td><input id="EDate" name="EDdate" type="text" value="<?php echo $reportDate; ?>" required/></td>
    <td><input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
    <td>
        <a href='javascript:void(0)' onclick="export_freezeIgnOnReport(<?php echo $_SESSION['customerno']; ?>,'<?php echo speedConstants::REPORT_PDF; ?>' );"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
        <a href='javascript:void(0)' onclick="export_freezeIgnOnReport(<?php echo $_SESSION['customerno']; ?>,'<?php echo speedConstants::REPORT_XLS; ?>' );return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
        <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
        <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
    </td>
  </tr>
</tbody>
</table>
</form>
<br/><br/>
<div class="entry"></div>

<?php
$mail_function = "export_freezeIgnOnReport('" . $_SESSION['customerno'] . "','".speedConstants::REPORT_EMAIL."');";
include_once "mail_pop_up.php";
?>
<center id='centerDiv'></center>
<script type="text/javascript">
    /* Auto dropdown for Vehicle No. */
    jQuery(document).ready(function () {
        jQuery("#vehicleno").autocomplete({
            source: "autocomplete.php?action=dummyData",
            minLength: 1,
            select: function (event, ui) {
                jQuery('#vehicleid').val(ui.item.vehicleid);
            }
        });
    });
    function getfreezeIgnOnReport(){
      jQuery('#entry').html('');
      jQuery('#pageloaddiv').show();
      var data = jQuery("#freezeIgnOnFrm").serialize();
      jQuery.ajax({
        url: "freezeIgnOnAjax.php",
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

</script>
