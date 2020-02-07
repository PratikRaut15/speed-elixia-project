<script>
    function parseDate(str) {
        var mdy = str.split('-');
        return new Date(mdy[2], mdy[1] - 1, mdy[0]);
    }

    function daydiff(first, second) {
        return ((second - first) / (1000 * 60 * 60 * 24));
    }

    function getsummary_report(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var sdate = jQuery("#SDate").val();
        var edate = jQuery("#EDate").val();
        var datediff = daydiff(parseDate(sdate), parseDate(edate));
        var bysupervisor = 0;
        
        var ischecked= jQuery('#bysupervisor').is(':checked');
            var supid = jQuery("#suplist").val();
            if(ischecked==true){
              if(supid=='0' || supid=='-1'){
                  jQuery('#error5').show();
                  jQuery('#error5').fadeOut(3000); 
                  jQuery('#pageloaddiv').hide();
                  return false;
              }else{
                   bysupervisor = jQuery("#bysupervisor").val();
              }  
            }
        
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        } else if (datediff > 30) {
            //alert(datediff);
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
            jQuery('#pageloaddiv').hide();
            return false;
        } else if (datediff <= 30) {
            var data = jQuery("#summaryreportForm").serialize();
            jQuery.ajax({
                url: "secsales_ajax.php",
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
    }

    function get_excelsummaryreport(customerno){
        var srid = jQuery("#srlist").val();
        var supid = jQuery("#suplist").val();
        var prevdate = jQuery("#prevdate").val();
        var sdate = jQuery("#SDate").val();
        var edate = jQuery("#EDate").val();
        var action = jQuery("#action").val();
        var userid = jQuery("#userid").val();
        var customerno = jQuery("#customerno").val();
        var srarr = jQuery("#srarr").val();
        var datediff = daydiff(parseDate(sdate), parseDate(edate));
        
        var bysupervisor = 0;
        
        var ischecked= jQuery('#bysupervisor').is(':checked');
            var supid = jQuery("#suplist").val();
            if(ischecked==true){
              if(supid=='0' || supid=='-1'){
                  jQuery('#error5').show();
                  jQuery('#error5').fadeOut(3000); 
                  jQuery('#pageloaddiv').hide();
                  return false;
              }else{
                   bysupervisor = jQuery("#bysupervisor").val();
              }  
            }
        
        if(datediff < 0){
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
            return false;
        }else if (datediff > 30){
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
            return false;
        }else if (datediff <= 30){
            if (srid != 0) {
                window.open("savexls.php?report=salessummaryreport&userid=" + userid + "&edate=" + edate + "&stdate=" + sdate + "&prevdate=" + prevdate + "&customerno=" + customerno + "&srarr=" + srarr + "&supid="+supid+"&bysupervisor="+bysupervisor+"&srid=" + srid, "_blank");
            }
        }
    }
    jQuery(function () {
        jQuery('#suplist').change(function (){
            var supid = jQuery(this).val();
            popultateSrlist(supid);
        });
        
        jQuery("#bysupervisor").change(function(){
                    var ischecked= jQuery(this).is(':checked');
                    var supid = jQuery("#suplist").val();
                    if(ischecked==true){
                      if(supid=='0' || supid=='-1'){
                          jQuery('#error5').show();
                          jQuery('#error5').fadeOut(3000); 
                          return false;
                      }  
                    }
                }); 
    });
    function popultateSrlist(supid) {
        var userid = jQuery("#userid").val();
        var customerno = jQuery("#customerno").val();
        jQuery.ajax({url: "secsales_ajax.php", type: 'POST', data: 'action=getsrlist&userid='+userid+'&customerno='+customerno+'&supid=' + supid,
            success: function (result) {
                jQuery('#srlist').html(result);
            },
        });
    }


</script>
<script src="../../scripts/highstock/highstock.js" type='text/javascript'></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>
<form action="reports.php?id=57" method="POST" onsubmit="getsummary_report(); return false;" id="summaryreportForm">
    <?php
    $today = date('d-m-Y');
    $prevdate = date('Y-m-d', strtotime(' -1 day'));
    $showdate = date('d-m-Y', strtotime($prevdate));
    ?>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th id="formheader" colspan="100%">Sales Summary Report</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%">
                        <span id="error" name="error" style="display: none;">Data Not Available</span>
                        <span id="error1" name="error1" style="display: none;">Please Check The Date</span>
                        <span id="error2" name="error2" style="display: none;">Please Select Dates With Difference Of Not More Than 30 Days</span>
                        <span id="error5" name="error5" style="display: none;">Please select supervisor</span>
                        <span id="error3" name="error3" style="display: none;">Please Select <?php echo $vehicle; ?></span>
                        <span id="error4" name="error4" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime($_SESSION['startdate'])); ?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate'])); ?></span>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>Start Date</td>
                    <td>End Date</td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        $srlist = getsaleslist($_SESSION['userid'], $_SESSION['role_modal']);
                        $supervisorlist = getsupervisorlist($_SESSION['customerno'], $_SESSION['userid']);
                        ?>
                        <select name="suplist" id="suplist">
                            <option value="0">Select Supervisor</option>
                            <option value="-1">ALL Supervisor</option>
                            <?php
                            foreach ($supervisorlist as $row){
                                $suparray[] = $row->userid;
                                $supid = $row->userid;
                                $supname = $row->realname;
                                echo "<option value='" . $supid . "'>" . $supname . "</option>";
                            }
                            ?>
                        </select>
                        <select name="srlist" id="srlist">
                            <option value="0">Select SR</option>
                            <option value="-1">ALL SR</option>
                            <?php
                            foreach ($srlist as $row){
                                $srarray[] = $row->userid;
                                $srid = $row->userid;
                                $srname = $row->realname;
                                echo "<option value='" . $srid . "'>" . $srname . "</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="prevdate" id="prevdate" value="<?php echo $prevdate; ?>">
                        <input type="hidden" name="action" id="action" value="salessummary">
                        <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid']; ?>">
                        <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
                        <input type="hidden" name="srarr" id="srarr" value="<?php echo implode(',', $srarray); ?>">
                        <input type="hidden" name="suparr" id="suparr" value="<?php echo implode(',', $suparray); ?>">
                    </td>
                    <td><input id="SDate" name="STdate" type="text" value="<?php echo $showdate; ?>" required/></td>
                    <td><input id="EDate" name="EDdate" type="text" value="<?php echo $showdate; ?>" required/></td>
                    <td><input type="checkbox" id="bysupervisor" name="bysupervisor" value="1"/>View Report by Supervisor</td>
                    <td>
                        <input type="submit"   class="g-button g-button-submit" value="Generate" name="GetReport">
                        &nbsp;
                        <a href='javascript:void(0)' onclick="get_excelsummaryreport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);
                                return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                    </td>
                </tr>
            </tbody>
        </table>
</form>
<br><br>
<div id='centerDiv'></div>
</div>
<?php
//$mail_function = "send_humidity_mail(" . $_SESSION['customerno'] . "," . $_SESSION['switch_to'] . ");";
//include_once "mail_pop_up.php";
?>
