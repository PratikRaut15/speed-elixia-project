<?php 
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//date_default_timezone_set("Asia/Calcutta");
include '../panels/header.php';
?>
<style type="text/css">
#pageloaddiv {
position: fixed;
left: 0px;
top: -80px;
width: 100%;
height: 100%;
z-index: 1000;
background: url('../../images/progressbar.gif') no-repeat center center;
}
</style>
<div id="pageloaddiv" style='display:none;'></div>
<div class="entry" style='min-height:400px;'>
    
    <center>
        <br/>

        <!-- starts, input table -->
        <?php
        $s_date = $e_date = date('d-m-Y');
        ?>
        <form method="post" action="informatics.php" onsubmit="getInformatics();return false;" id="infoForm">
        <table>
            <thead>
                <tr><th colspan="100%" id="formheader">Informatics Report</th></tr>
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
                    <td>End Date</td>
                    <td><input type='hidden' value='get_informatics_report' name='to_get'/></td>
                </tr>
                <tr>
                    <td><input type="text" required="" value="<?php echo $s_date; ?>" name="SDate" id="SDate"></td>
                    <td><input type="text" required="" value="<?php echo $e_date; ?>" name="EDate" id="EDate"></td>
                    <td><input type="submit" name="GetReport" value="Get Report" class="g-button g-button-submit" id='informaticsSubmit'></td>
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
    function getInformatics(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#infoForm").serialize();
        jQuery.ajax({
            url:"informatics_ajax.php",
            type: 'POST',
            data: data,
            success:function(result){
                jQuery("#centerDiv").html(result);
            },
            complete: function(){
                jQuery('#pageloaddiv').hide();
            }
        });
    }
</script>
<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script src="../../scripts/highcharts/js/modules/exporting.js"></script>
</html>        
        
