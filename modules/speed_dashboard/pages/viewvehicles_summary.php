<script type="text/javascript">
 function getSummaryReport() {
   jQuery('#centerDiv').html('');
   jQuery('#pageloaddiv').show();
   var data = jQuery("#SummaryForm").serialize();
   jQuery.ajax({
     url: "viewvehicles_summary_ajax.php",
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
<?php
    $title = "Summary";
    $date = date("d-m-Y", strtotime("-1 days"));
?>
<br/>
<form action="" method="POST" id='SummaryForm' onsubmit='getSummaryReport();return false;'>
    <table>
        <thead>
            <tr><th id="formheader" colspan="100%"><?php echo $title; ?></th></tr>
        </thead>
        <tbody>
            <tr>
                <td>From Date</td>
                <td>End Date</td>
            </tr>
            <tr>
                <td><input id="SDate" name="SDate" type="text" value="<?php echo $date; ?>" required/></td>
                <td><input id="EDate" name="EDate" type="text" value="<?php echo $date; ?>" required/></td>
                <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
                <input type="hidden" id='customerno' value="<?php echo $_SESSION['customerno']; ?>" name="customerno">
                <input type="hidden" id='userid' value="<?php echo $_SESSION['userid']; ?>" name="userid">
                <td>
                  <a onclick="get_analyticpdfreport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,<?php echo $_SESSION['groupid']; ?>);" href="javascript:void(0)">
                    <img title="Export to PDF" class="exportIcons" alt="Export to PDF" src="../../images/pdf_icon.png">
                  </a>
                  <a href="javascript:void(0)" onclick="html2xls_analyticreport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>,<?php echo $_SESSION['groupid']; ?>);return false;">
                    <img title="Export to Excel" class="exportIcons" alt="Export to Excel" src="../../images/xls.gif">
                  </a>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<br/><br/>
<center id='centerDiv'></center>
