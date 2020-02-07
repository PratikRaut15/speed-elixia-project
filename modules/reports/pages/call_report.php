<script>
    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#deviceid').val(Value);
        jQuery('#display').hide();
    }

    function getCalls(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var sdate = jQuery("#SDate").val();
        var edate = jQuery("#EDate").val();
        var datediff = daydiff(parseDate(sdate), parseDate(edate));
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        } else if (datediff > 30) {
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        }else if(datediff <= 30){
            var data = jQuery("#callreportForm").serialize();
            jQuery.ajax({
                url: "secsales_ajax.php",
                type: 'POST',
                data: data,
                success: function (result){
                    jQuery("#centerDiv").html(result);
                },
                complete: function(){
                    jQuery('#pageloaddiv').hide();
                }
            });
        }
    }
    function get_pdfcallreport(customerno) {
        var srid = jQuery("#srlist").val();
        var prevdate = jQuery("#prevdate").val();
        var sdate = jQuery("#SDate").val();
        var edate = jQuery("#EDate").val();
        var action = jQuery("#action").val();
        var userid = jQuery("#userid").val();
        var customerno = jQuery("#customerno").val();
        var srarr = jQuery("#srarr").val();
        var datediff = daydiff(parseDate(sdate), parseDate(edate));
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        } else if (datediff > 30) {
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        } else if (datediff <= 30){
            if (srid != 0) {
                window.open("pdftest.php?report=callreport&userid=" + userid + "&prevdate=" + prevdate + "&startdate=" + sdate + "&enddate=" + edate + "&customerno=" + customerno + "&srarr=" + srarr + "&srid=" + srid, "_blank");
            }
        }
    }

    function get_excelcallreport(customerno){
        var srid = jQuery("#srlist").val();
        var prevdate = jQuery("#prevdate").val();
        var action = jQuery("#action").val();
        var sdate = jQuery("#SDate").val();
        var edate = jQuery("#EDate").val();
        var userid = jQuery("#userid").val();
        var customerno = jQuery("#customerno").val();
        var srarr = jQuery("#srarr").val();
        var datediff = daydiff(parseDate(sdate), parseDate(edate));
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        } else if (datediff > 30) {
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        } else if (datediff <= 30) {
            if (srid != 0) {
                window.open("savexls.php?report=callreport&userid=" + userid + "&prevdate=" + prevdate + "&startdate=" + sdate + "&enddate=" + edate + "&customerno=" + customerno + "&srarr=" + srarr + "&srid=" + srid, "_blank");
            }
        }
    }

    function parseDate(str) {
        var mdy = str.split('-');
        return new Date(mdy[2], mdy[1] - 1, mdy[0]);
    }

    function daydiff(first, second) {
        return ((second - first) / (1000 * 60 * 60 * 24));
    }
</script>
<script src="../../scripts/highstock/highstock.js" type='text/javascript'></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>
<form action="reports.php?id=55" method="POST" onsubmit="getCalls();
        return false;" id="callreportForm">
      <?php
      $today = date('d-m-Y');
      $prevdate = date('Y-m-d', strtotime(' -1 day'));
      ?>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th id="formheader" colspan="100%">First & Last Call Report </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%">
                        <span id="error" name="error" style="display: none;">Data Not Available</span>
                        <span id="error1" name="error1" style="display: none;">Please Check The Date</span>
                        <span id="error2" name="error2" style="display: none;">Please Select Dates With Difference Of Not More Than 30 Days</span>
                        <span id="error3" name="error3" style="display: none;">Please Select <?php echo $vehicle; ?></span>
                        <span id="error4" name="error4" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime($_SESSION['startdate'])); ?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate'])); ?></span>
                    </td>
                </tr>

                <tr>
            <!--        <td>Sales Representative</td>-->
                    <td>&nbsp;</td>
                    <td>Start Date</td>
                    <td>End Date</td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        $srlist = getsaleslist($_SESSION['userid'], $_SESSION['role_modal']);
                        ?>
                        <select name="srlist" id="srlist">
                            <option value="0">Select SR</option>
                            <option value="-1">ALL SR</option>
                            <?php
                            foreach ($srlist as $row) {
                                $srarray[] = $row->userid;
                                $srid = $row->userid;
                                $srname = $row->realname;
                                echo "<option value='" . $srid . "'>" . $srname . "</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="prevdate" id="prevdate" value="<?php echo $prevdate; ?>">
                        <input type="hidden" name="action" id="action" value="callreport">
                        <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid']; ?>">
                        <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
                        <input type="hidden" name="srarr" id="srarr" value="<?php echo implode(',', $srarray); ?>">
                    </td>
                    <td><input id="SDate" name="STdate" type="text" value="<?php echo $startdate; ?>" required/></td>
                    <td><input id="EDate" name="EDdate" type="text" value="<?php echo $endate; ?>" required/></td>
                    <td>
                        <input type="submit"   class="g-button g-button-submit" value="Generate" name="GetReport">
                        <a href='javascript:void(0)' onclick="get_pdfcallreport(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
                        <a href='javascript:void(0)' onclick="get_excelcallreport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);
                                return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                    </td>
                </tr>
            </tbody>
        </table>
</form>
<br><br>
<center id='centerDiv'></center>
</div>
<?php
//$mail_function = "send_humidity_mail(" . $_SESSION['customerno'] . "," . $_SESSION['switch_to'] . ");";
//include_once "mail_pop_up.php";
?>
