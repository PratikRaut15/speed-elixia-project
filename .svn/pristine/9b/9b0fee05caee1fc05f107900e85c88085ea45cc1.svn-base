<?php
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/datatables/jquery.dataTables_new.css" type="text/css" />';
echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
?>
<script>
    $(function () {
        $("#vehicleno").autoSuggest({
            ajaxFilePath: "autocomplete.php",
            ajaxParams: "dummydata=maintenance",
            autoFill: false,
            iwidth: "auto",
            opacity: "0.9",
            ilimit: "10",
            idHolder: "id-holder",
            match: "contains"
        });
    });

    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#vehicleid').val(Value);
        jQuery('#display').hide();
    }
</script>

<?php
$display_date = date('d-m-Y');
$delaers = get_dealers_by_type(7, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
$sdate1 = date('d-m-Y');
$edate1 = date('d-m-Y');
$vehicleno = isset($_POST["vehicleno"]) ? $_POST["vehicleno"] : '';
$vehicleid = isset($_POST["vehicleid"]) ? $_POST["vehicleid"] : '';
?>

<form action="reports.php?id=37" method="POST" id="FuelRep" name="FuelRep">
    <table>
        <thead>
            <tr>
                <th id="formheader" colspan="100%">Fuel History</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="100%">
                    <span id="error2" name="error2" style="display: none;color: #FF0000;">Please Check Start Date</span>
                </td>
            </tr>

            <tr>
                <td>Vehicle No</td>
                <td>Dealer</td>
                <td>Start Date</td>
                <td>End Date</td>
            </tr>
            <tr>
                <td>
                    <input  type="text" name="vehicleno" id="vehicleno" size="17" value="<?php echo $vehicleno; ?>" autocomplete="off" placeholder="Enter Vehicle No" >
                    <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid; ?>"/>
                    <div id="display" class="listvehicle"></div>
                </td>
                <td>
                    <select name="dealerid" id='dealerid'>
                        <option value="-1">Select Dealer</option>
                        <option value="0">All Dealer</option>
                        <?php
                        if (isset($delaers)) {
                            foreach ($delaers as $dealer) {
                                if ($dealerid == $dealer->dealerid) {
                                    echo "<option value='$dealer->dealerid' selected='' >$dealer->name</option>";
                                } else {
                                    echo "<option value='$dealer->dealerid' >$dealer->name</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </td>
                <td><input id="SDate" name="STdate" type="text" value="<?php echo $sdate1; ?>" required/></td>
                <td><input id="EDate" name="ETdate" type="text" value="<?php echo $edate1; ?>" required/></td>
                <td style="padding-bottom:10px;">
                    <input type="button" class="g-button g-button-submit" value="Get Report" name="Filter" onclick = "FuelReport();"/>&nbsp;
                    <a href='javascript:void(0)' onclick="fuel_hist_maintenance_pdf();"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
                    <a href='javascript:void(0)' onclick="fuel_hist_maintenance(); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                    <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<br>
<div class="fluid" id="fuelRepdiv" style="display: none;">
    <div id="tableheaderdiv">

    </div>
    <style type="text/css">
        #fuel_report_filter{
            margin-right: 5px;
        }

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

    <table class='display table table-bordered table-striped table-condensed' id="fuel_report" style="width:100%">
        <thead>
            <tr class="filterrow">
                <td><input type="text"id="srno_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="vehicleno_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="transactionid_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="seatcapacity_filter"  class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="fuel_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="amount_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="rate_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="refno_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="startkm_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="endkm_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="netkm_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="average_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="dealer_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="date_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="additional_amount_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="notes_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="ofasnumber_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td>
                    <input type="text" id="chequeno_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/>
                </td>
                <td><input type="text" id="chequeamount_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="chequedate_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" id="tdsamount_filter" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            </tr>
            <tr>
                <th data-sortable="true">Sr. No</th>              
                <th data-sortable="true">Vehicle No</th>      
                <th data-sortable="true">Transaction ID</th>             
                <th data-sortable="true">Seat Capacity</th>                 
                <th data-sortable="true">Fuel</th>
                <th data-sortable="true">Amount</th>
                <th data-sortable="true">Rate</th>
                <th data-sortable="true">Ref. No</th>
                <th data-sortable="true">Start Km</th>
                <th data-sortable="true">End Km</th>
                <th data-sortable="true">Net Km</th>
                <th data-sortable="true">Average</th>        
                <th data-sortable="true">Dealer</th>                
                <th data-sortable="true">Date</th>  
                <th data-sortable="true">Additional Amount</th>
                <th data-sortable="true">Notes</th>
                <th data-sortable="true">Slip No</th>
                <th data-sortable="true">Cheque No</th>
                <th data-sortable="true">Cheque Amount</th>
                <th data-sortable="true">Cheque Date</th>
                <th data-sortable="true">TDS Amount</th>
            </tr>
        </thead>
    </table>
</div>
<?php
$mail_function = "fuel_hist_maintenance_mail(" . $_SESSION['customerno'] . ");";
include_once "mail_pop_up.php";
?>
