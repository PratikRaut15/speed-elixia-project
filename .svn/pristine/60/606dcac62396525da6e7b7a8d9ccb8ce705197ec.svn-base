<?php 
//date_default_timezone_set("Asia/Calcutta");
include '../panels/header.php';

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/comman_function/reports_func.php';

$checkpointopt = get_checkpoints();
$def_date = date('d-m-Y');
?>
<div id="pageloaddiv" style='display:none;'></div>
<div class="entry" style='min-height:400px;'>
    
    <center>
        <br/>

        <!-- starts, input table -->
        <form method="post" action="exception.php" onsubmit="getException();return false;" id="exceptionForm">
        <table>
            <thead>
                <tr><th colspan="100%" id="formheader">Exception Report</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%">
                        <span style="display: none;" name="error1" id="error">Already in progress. Please Refresh to start again</span>
                        <span style="display: none;" name="error2" id="error2">Data Not Available</span>
                        <span style="display: none;" name="error3" id="error3">Please Check The Dates</span>
                        <span style="display: none;" name="error4" id="error5">Please Select Dates With Difference Of Not More Than 3 Months</span>
                    </td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td>Start Hour</td>
                    <td>End Date</td>
                    <td>End Hour</td>
                    <td>Select Start Checkpoint</td>
                    <td>Select End Checkpoint</td>
                    <td>Report Type</td>
                    <td><input type='hidden' value='get_exception_report' name='to_get'/></td>
                </tr>
                <tr>
                    <td><input size="10" type="text" required="" value="<?php echo $def_date; ?>" name="SDate" id="SDate"></td>
                    <td><input type="text" data-date="00:00" class="input-mini" name="STime" id="STime"></td>
                    <td><input size="10" type="text" required="" value="<?php echo $def_date; ?>" name="EDate" id="EDate"></td>
                    <td><input type="text" data-date2="23:59" class="input-mini" name="ETime" id="ETime"></td>
                    <td><select id="checkpoint_start" name="checkpoint_start" style="width: 150px;"  required><?php echo $checkpointopt;?></select></td>
                    <td><select id="checkpoint_end" name="checkpoint_end" style="width: 150px;"  required><?php echo $checkpointopt;?></select></td>
                    <td>
                        <select id="report_type" name="report_type" onchange='generate_report_type(this.value);' required >
                            <option value="">--Select--</option>
                            <option value="distance">Distance</option>
                            <option value="avg_speed">Avg Speed</option>
                            <option value="idle_time">Idle Time</option>
                            <option value="genset_avg">Genset Average</option>
                        </select>
                        <span id='reportTypeInput'></span>
                    </td>
                    <td><input type="submit" name="GetReport" value="Get Report" class="g-button g-button-submit" id='ExceptionSubmit'></td>
                </tr>
            </tbody>
        </table>
        </form>
        <br/>
    </center>
    <!-- ends, input table -->
    <center id="centerDiv"></center>
    
</div>

        
<!-- footer starts here -->
                    </div>
                </div>
                <div style="clear: both;">&nbsp;</div>
            </div>
        </div>
    </div>
</div>
<div id="footer">
	<p>&copy; 2012-2014 <a href="http://www.elixiatech.com/">Elixiatech.com</a></p>
</div>
<!-- end #footer -->
</body>
<?php include'../panels/forjs.php';?>
<script type="text/javascript">
    function getException(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#exceptionForm").serialize();
        var start = jQuery("#checkpoint_start option:selected").text();
        var end = jQuery("#checkpoint_end option:selected").text();
        jQuery.ajax({
            url:"ajax.php",
            type: 'POST',
            data: data+'&cp_start_name='+start+'&cp_end_name='+end,
            success:function(result){
                jQuery("#centerDiv").html(result);
            },
            complete: function(){
                jQuery('#pageloaddiv').hide();
            }
        });
    }
    function generate_report_type(rep_val){
        var inp = "<input type='text' class='input-mini' name='report_type_input' required/>";
        var selct = "<select name='condition'  required><option value='gt'>></option><option value='lt_eq'><=</option></select> ";
        if(rep_val!==''){
            var unit = '';
            switch(rep_val){
                case 'distance':
                    unit = 'KM';break;
                case 'avg_speed':
                    unit = 'KM/Hour';break;
                case 'idle_time':
                    unit = 'Hour';break;
                case 'genset_avg':
                    unit = 'Mins';break;
            }
            jQuery('#reportTypeInput').html(selct+inp+unit);
        }
        else{
            jQuery('#reportTypeInput').html('');
        }
    }
</script>
<!--<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script src="../../scripts/highcharts/js/modules/exporting.js"></script>-->
</html>        
        
