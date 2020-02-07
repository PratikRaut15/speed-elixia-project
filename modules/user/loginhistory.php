<?php
include_once '../panels/header.php';
include_once 'user_functions.php';
?>
<br/>
<center>
    <form action="loginhistory.php" method="POST" id='LoginHistoryForm' onsubmit="getloginReport();
            return false;">
              <?php
              $title = 'Login History';
              $date = date('d-m-Y');
              ?>
        <table>
            <thead>
                <tr>
                    <th id="formheader" colspan="100%"><?php echo $title; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%">
                        <span id="error" name="error" style="display: none;">Data Not Available</span>
                        <span id="error1" name="error1" style="display: none;">Please Check The Date</span>
                        <span id="error2" name="error2" style="display: none;">Please Select Dates With Difference Of Not More Than 30 Days</span>
                        <span id="error3" name="error3" style="display: none;">Please Select Vehicle</span>
                        <span id="error6" name="error6" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime($_SESSION['startdate'])); ?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate'])); ?></span>
                    </td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td>Start Hour</td>
                    <td>End Date</td>
                    <td>End Hour</td>
                    <td colspan='3'></td>
                </tr>
                <tr>
                    <td><input id="SDate" name="STdate" type="text" value="<?php echo $date; ?>" required/></td>
                    <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
                    <td><input id="EDate" name="EDdate" type="text" value="<?php echo $date; ?>" required/></td>
                    <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59" /></td>
                    <td>
                        <input type="submit" class="g-button g-button-submit" value="Get History" name="GetReport">
                        <a href='javascript:void(0)' onclick="get_pdfreportLoginhistory(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
                        <a href='javascript:void(0)' onclick="html2xlsloginhistory(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>);
                                return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
<!--                        <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
                        <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>-->
                        <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</center>
<br/><br/>
<center id='centerDiv'></center>
<!--<script type="text/javascript" src="createcheck.js"></script>-->
<?php
//$mail_function = "send_location_historydetails_mail(" . $_SESSION['customerno'] . ");";
//include_once "mail_pop_up.php";
?>
<script>
    function getloginReport() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#LoginHistoryForm").serialize();
        jQuery.ajax({
            url: "loginreport_ajax.php",
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
include_once '../panels/footer.php';
?>