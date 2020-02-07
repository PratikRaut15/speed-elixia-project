<script>

    function getcallsummary_report(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var sdate = jQuery("#SDate").val();
        var srid = jQuery("#srlist").val();
          if(srid=='0'){
              jQuery('#error5').show();
              jQuery('#error5').fadeOut(3000); 
              jQuery('#pageloaddiv').hide();
              return false;
          }  
         else if (sdate != "") {
            var data = jQuery("#callsummaryreportForm").serialize();
            jQuery.ajax({
                url: "seccallsummary_ajax.php",
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

    function get_excelcallsummaryreport(customerno){
        

        var srid = jQuery("#srlist").val();
        var sdate = jQuery("#SDate").val();
        var edate = jQuery("#EDate").val();
        var action = jQuery("#action").val();
        var userid = jQuery("#userid").val();
        var customerno = jQuery("#customerno").val();
        var srarr = jQuery("#srarr").val();
        
        if(srid=='0'){
              jQuery('#error5').show();
              jQuery('#error5').fadeOut(3000); 
              jQuery('#pageloaddiv').hide();
              return false;
        }else if (sdate != ""){
            if (srid != 0) {
                window.open("savexls.php?report=salescallsummaryreport&userid=" + userid + "&edate=" + edate + "&stdate=" + sdate + "&customerno=" + customerno + "&srarr=" + srarr + "&srid=" + srid, "_blank");
            }
        }
    }
    
</script>
<script src="../../scripts/highstock/highstock.js" type='text/javascript'></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>
<form action="reports.php?id=60" method="POST" onsubmit="getcallsummary_report(); return false;" id="callsummaryreportForm">
    <?php
    $today = date('d-m-Y');
    $prevdate = date('Y-m-d', strtotime(' -1 day'));
    $showdate = date('d-m-Y', strtotime($prevdate));
    ?>
    <div class="container">
        <?php include_once("panels/seccallsummaryreport.php"); ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>Date</td>
                    <!-- <td>End Date</td> -->
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        $srlist = getsaleslist($_SESSION['userid'], $_SESSION['role_modal']);
                        // $supervisorlist = getsupervisorlist($_SESSION['customerno'], $_SESSION['userid']);
                        ?>
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
                    <!-- <td><input id="EDate" name="EDdate" type="text" value="<?php echo $showdate; ?>" required/></td>
                    <td><input type="checkbox" id="bysupervisor" name="bysupervisor" value="1"/>View Report by Supervisor</td> -->
                    <td>
                        <input type="submit"   class="g-button g-button-submit" value="Generate" name="GetReport">
                        &nbsp;
                        <a href='javascript:void(0)' onclick="get_excelcallsummaryreport(<?php echo $_SESSION['customerno']; ?>);
                                return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                        <!-- <a href='javascript:void(0)' onclick="get_excelsummaryreport(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);
                                return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a> -->
                    </td>
                </tr>
            </tbody>
        </table>
</form>
<br><br>
<div id='centerDiv'></div>
</div>
