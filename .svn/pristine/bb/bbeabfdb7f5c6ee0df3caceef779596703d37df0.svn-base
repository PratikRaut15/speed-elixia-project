<script type="text/javascript">
    function getrouteSummary(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#routeSummaryForm").serialize();

        jQuery.ajax({
            url:"tatSummary_ajax.php",
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
<form action="reports.php?id=40" method="POST" id='routeSummaryForm' onsubmit="getrouteSummary();return false;">
    <input type="hidden" name='getReport' value='routeSummaryLive'/>
<?php
$title = "Route wise ETA Report";
$today = date('d-m-Y');
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
            <span id="error6" name="error6" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime( $_SESSION['startdate']));?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate']));?></span>
        </td>
    </tr>
    <tr>
        <!-- <td>Start Date</td>
        <td>End Date</td> -->
        <td>Route</td>
        <td></td>
    </tr>
    <tr>
        <!-- <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td> -->
                <td>
                    <?php
                        $CustomerRoutes = getRouteList($_SESSION['customerno']);
                    ?>
                    <select name="routelist" id="routelist">
                        <option value="0">Select Route</option>
                        <?php
                        if(isset($CustomerRoutes) && !empty($CustomerRoutes)){
                            foreach($CustomerRoutes as $routedata){
                                echo "<option value='".$routedata->routeid."'>".$routedata->routename."</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport">
            <input type="hidden" name="groupid" id="groupid" value="<?php echo $_SESSION['groupid'];?>">
            <!--
            <a href='javascript:void(0)' onclick="get_pdfreportRouteTatSmry();"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            -->
            <!-- hidden temp -->
            <!-- <a href='javascript:void(0)' onclick="html2xlsRouteTatSmry();return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a> -->
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br><br>
<center id='centerDiv'></center>
