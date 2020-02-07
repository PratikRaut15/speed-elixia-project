<script type="text/javascript">
 function getInactiveReport() {
   jQuery('#centerDiv').html('');
   jQuery('#pageloaddiv').show();
   var data = jQuery("#InactiveForm").serialize();
   jQuery.ajax({
     url: "inactive_device_ajax.php",
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
    $title = "Inactive Device Report";
    $date = date("d-m-Y", strtotime("-1 days"));
?>
<br/>
<form action="" method="POST" id='InactiveForm' onsubmit='getInactiveReport();return false;'>
    <table>
        <thead>
            <tr><th id="formheader" colspan="100%"><?php echo $title; ?></th></tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3">
                    <span id="error1" name="error1" style="display: none;">Enter Start Date.</span>
                    <span id="error2" name="error2" style="display: none;">Enter End Date.</span>
                </td>
            </tr>
            <tr>
                <td>From Date</td>
                <td>End Date</td>
                <td></td>
            </tr>
            <tr>
                <td><input id="SDate" name="SDate" type="text" value="<?php echo $date; ?>" required/></td>
                <td><input id="EDate" name="EDate" type="text" value="<?php echo $date; ?>" required/></td>
                <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
                <input type="hidden" id='customerno' value="<?php echo $_SESSION['customerno']; ?>" name="customerno">
                <input type="hidden" id='userid' value="<?php echo $_SESSION['userid']; ?>" name="userid">
                <td>
                  <a onclick="get_inactivepdfreport(<?php echo $_SESSION['customerno']; ?>);" href="javascript:void(0)">
                    <img title="Export to PDF" class="exportIcons" alt="Export to PDF" src="../../images/pdf_icon.png">
                  </a>

                  <a href="javascript:void(0)" onclick="get_inactivexlsreport(<?php echo $_SESSION['customerno']; ?>);return false;">
                    <img title="Export to Excel" class="exportIcons" alt="Export to Excel" src="../../images/xls.gif">
                  </a>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<br/><br/>
<center id='centerDiv'></center>
