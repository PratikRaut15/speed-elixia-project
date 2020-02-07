<script type="text/javascript">

    function daydiff(first, second) {
        return ((second - first) / (1000 * 60 * 60 * 24));
    }

    function parseDateN(str) {
    var mdy = str.split('/')
    // alert(mdy[1]-1); return false;
    return new Date(mdy[2], mdy[0], mdy[1] - 1);
    }
    
    function get_excelattendancereport(customerno) {

        var sdate = jQuery("#SDate").val();
        var edate = jQuery("#EDate").val();
        
        var customerno = jQuery("#customerno").val();
        var userid = jQuery("#userid").val();

        var datediff = daydiff(parseDateN(sdate), parseDateN(edate));

        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(4000);
            return false;
        } else if (datediff > 30){
            //alert(datediff);
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(4000);
            return false;
        } else if (datediff <= 30){
            // alert(datediff);
            // alert("yes i m in"); return false; 
            window.open("../reports/savexls.php?report=attendancedetails&userid="+ userid + "&edate=" + edate +"&stdate=" + sdate + "&customerno=" + customerno , "_blank");
    }
}

</script>

<?php
$today = date("m/d/Y");
/**
 * View Distributor interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    //#viewcategory_filter{display: none}
    .dataTables_length{display: none}
</style>    
<br/>

<div class='container' style="width:50%;" >
    <div style="float:right;">
    </div>


    <center>

        <table>
            <thead>    
                <tr>
                    <td>Start Date</td>
                    <td>End Date</td>
                    <td>Report Type</td>
                    
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
                    <td><input id="SDate" name="STdate" type="text" value="<?php echo $today ?>" required/></td>
                    <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today ?>" required/></td>

                    <!-- <td>
                        <select id="report" name="report">
                            <option value="Table">Tabular Report</option>
                            <option value="Graph">Graphical Report</option>
                        </select>
                    </td> -->
                    <td>
                        <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid']; ?>">
                        <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">

                        <!-- <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
                                     -->            
                        <!-- <a href='javascript:void(0)' onclick="get_pdfDistanceReport(<?php  echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['use_maintenance'];?>,<?php echo $_SESSION['use_hierarchy'];?>,<?php echo $_SESSION['groupid'];?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a> -->
                        
                        <a href='javascript:void(0)' onclick="get_excelattendancereport(<?php  echo $_SESSION['customerno']; ?>); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                        
                        <!-- <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a> -->
                    </td>
                </tr>
            </tbody>
        </table>


        <input type='hidden' id='forTable' value='viewAttendance'/>
        <table class='table table-bordered ' id="viewattendance" style="width: 90%" >
            <thead>
                <tr class="filterrow">
<!--            <td><input type="hidden" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>-->
                    <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th data-sortable="true">Name </th>
                    <th data-sortable="true">Role</th>
                    <th data-sortable="true">Start Date</th>
                    <th data-sortable="true">End Date</th>
                    <th data-sortable="true">Status</th>
                    <th data-sortable="true" style="width: 18%">Location</th>
                    <th data-sortable="false">Edit</th>
                    <th data-sortable="false">Delete</th>
                    
                </tr> 
            </thead>
        </table>
    </center>
</div>


