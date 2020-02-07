<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
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

<form action="reports.php?id=49" method="POST">
    <table>
        <thead>
            <tr>
                <th id="formheader" colspan="100%">Transaction History</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="100%">
                    <span id="error2" name="error2" style="display: none;color: #FF0000;">Please Check Dates</span>
                    <span id="error3" name="error3" style="display: none;color: #FF0000;">Please Select any one option </span>
                </td>
            </tr>
            <?php
            $delaers = getdealers();
            $vehicleno = isset($_POST["vehicleno"]) ? $_POST["vehicleno"] : '';
            if (isset($_POST["Filter"])) {
                $vehicleid = (int) GetSafeValueString($_POST["vehicleid"], "string");
                $categoryid = GetSafeValueString($_POST['categoryid'], "string");
                $dealerid = GetSafeValueString($_POST["dealerid"], "string");
                $statusid = GetSafeValueString($_POST['statusid'], "string");
                $sdate1 = GetSafeValueString($_POST["STdate"], "string");
                $edate1 = GetSafeValueString($_POST["ETdate"], "string");
                $sdate = "";
                $edate = "";
                $makename = "";
                $modelname="";
                if ($sdate1 != "") {
                    $sdate = strtotime($sdate1 . ' 00:00');
                }
                if ($edate1 != "") {
                    $edate = strtotime($edate1 . ' 23:59');
                }
                $categoryname = GetSafeValueString($_POST['categoryname'], "string");
                $dealername = GetSafeValueString($_POST["dealername"], "string");
                $invoicefilter = GetSafeValueString($_POST["invoicefilter"], "string");
                $statusname = GetSafeValueString($_POST['statusname'], "string");
                $customerno = GetSafeValueString($_POST['customerno'], "string");
                
                if($vehicleno == '' && $categoryid == '-1' && $dealerid == '-1' && $statusid == '-1' && $invoicefilter==''){
                    $errorfound = true;
                    echo "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(3000)</script>";
                }elseif(($sdate != "" && $edate != "") && $sdate > $edate){
                    $errorfound = true;
                    echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
                }elseif(($sdate == "" && $edate != "") || ($sdate != "" && $edate == "")){
                    $errorfound = true;
                    echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
                }elseif ($vehicleno == ''){
                    $transdata = get_transaction_details(0, $categoryid, $dealerid, $sdate, $edate, $statusid, $customerno,$invoicefilter);
                }else{
                    $transdata = get_transaction_details($vehicleid, $categoryid, $dealerid, $sdate, $edate, $statusid, $customerno,$invoicefilter);
                    $makemodeldata = get_make_model($vehicleid,$customerno);
                    $makename = $makemodeldata[0]['makename'];
                    $modelname = $makemodeldata[0]['modelname'];
                }
            }
            else {
                $sdate1 = date('d-m-Y');
                $edate1 = date('d-m-Y');
            }
            ?>
            <tr>
                <td>Vehicle No</td>
                <td>Dealer</td>
                <td>Invoice No</td>
                <td>Category</td>
                <td>Status</td>
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
                    <select name="dealerid" id='dealerid' onchange="SetDealer();">
                        <option value="-1">Select Dealer</option>
                        <option value="0">All Dealer</option>
                        <?php
                        if (isset($delaers)){
                            foreach ($delaers as $dealer){
                                if ($dealerid == $dealer->dealerid){
                                    echo "<option value='$dealer->dealerid' selected='' >$dealer->name</option>";
                                }else{
                                    echo "<option value='$dealer->dealerid' >$dealer->name</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <input type="hidden" name="dealername" id="dealername" value=""/>
                </td>
                <td><input type="text" name="invoicefilter" id="invoicefilter"></td>
                <td>
                    <select id="categoryid" name="categoryid" style="width: 200px;" onchange="SetCategory();">
                        <option value="-1">Select Category</option>
                        <option value="-2">All Category</option>
                        <option value = '0' <?php if (isset($categoryid) && $categoryid == 0) { ?>selected<?php } ?> />Battery</option>
                        <option value = '1' <?php if (isset($categoryid) && $categoryid == 1) { ?>selected<?php } ?> />Tyre</option>
                        <option value = '2' <?php if (isset($categoryid) && $categoryid == 2) { ?>selected<?php } ?> />Repair</option>
                        <option value = '3' <?php if (isset($categoryid) && $categoryid == 3) { ?>selected<?php } ?> />Service</option>
                        <option value = '5' <?php if (isset($categoryid) && $categoryid == 5) { ?>selected<?php } ?> />Accessories</option>
                    </select>
                    <input type="hidden" name="categoryname" id="categoryname" value=""/>
                </td>
                <td>
                    <?php
                    $statuses = get_trans_status();
                    $statusopt = "";
                    if (isset($statuses)) {
                        foreach ($statuses as $status) {
                            if (isset($_POST['statusid']) && $status->statusid == $_POST['statusid']) {
                                $statusopt .= "<option value = '$status->statusid' selected = 'selected'>$status->name</option>";
                            }
                            else {
                                $statusopt .= "<option value = '$status->statusid'>$status->name</option>";
                            }
                        }
                    }
                    ?>
                    <select id="statusid" name="statusid" style="width: 200px;" onchange="SetStatus();">
                        <option value="-1">Select Status</option>
                        <option value="00">All status</option>
                        <?php echo $statusopt; ?>
                    </select>
                    <input type="hidden" name="statusname" id="statusname" value=""/>
                </td>
                <td><input id="SDate" name="STdate" type="text" value="<?php echo $sdate1; ?>" /></td>
                <td><input id="EDate" name="ETdate" type="text" value="<?php echo $edate1; ?>" /></td>
                <td style="padding-bottom:10px;">
                    <input type="submit" class="g-button g-button-submit" value="Get Report" name="Filter" />&nbsp;
                    <a href='javascript:void(0)' onclick="trans_hist_maintenance_pdf();"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
                    <a href='javascript:void(0)' onclick="trans_hist_maintenance(); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                    <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
</form>
<br>
<div class="fluid">
    <?php
    if (isset($_POST["Filter"]) && !isset($errorfound)) {
        $total_amt="";
        if (isset($transdata) && !empty($transdata)) {
            foreach ($transdata as $transdatas) {
                $total_amt += $transdatas->invoice_amount;
            }
        }
        
        
        $title = 'Transaction History Report';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $sdate1",
            "End Date: $edate1",
            "Dealer: $dealername",
            "Category: $categoryname",
            "Status: $statusname",
            "Model Name: $modelname",
            "Make Name: $makename",
            "Total Invoice Amount: $total_amt",
        );
        
        
        $columns = array();
        $middlecolumn = "Total Invoice Amount: ".$total_amt;
        $middlecolumn = "<div class='newTableSubHeader' style='width:30%'>".$middlecolumn."</div>";
        echo table_header($title, $subTitle, $columns, false, $middlecolumn);
    }
    ?>
</div>
<?php
if (isset($transdata) && !empty($transdata)) {
    ?>
    <div class='fluid' >
        <table class='newTable' id="search_table2">
            <thead>
                <tr id="formheader">
                    <th>Sr.No</th>
                    <th>Vehicle No</th>
                    <th>Group Name</th>
                    <th>Dealer Name</th>
                    <th>Transaction ID</th>
                    <th>Meter Reading</th>
                    <th>Category</th>
                    <th>Quotation amount</th>
                    <th>Notes</th>
                    <th>Details</th>
                    <th>Invoice No</th>
                    <th>Invoice Amt</th>
                    <th>Invoice Date</th>
                    <th>Vehicle Out Date</th>
                    <th>Status</th>
                    <th>Parts</th>
                    <th>Tasks</th>
                    <th>Accessories</th>
                    <th>Tyre Type</th>
                    
                </tr>
            </thead>
            <tr><td colspan ='100%' style = 'text-align:center;font-weight:bold'>Lables : U - Unit Price , Q - Quantity , T - Total Amount</td></tr>
            <tbody>
                <?php
                $i = 1;
                foreach ($transdata as $transdatas) {

                    $record_parts = getpartsby_maintenanceid($transdatas->mid, $_SESSION["customerno"]);
                    $record_tasks = gettaskby_maintenanceid($transdatas->mid, $_SESSION["customerno"]);
                    ?>
                    <tr>
                        
                        <td> <?php echo $i++; ?> </td>
                        <td> <?php echo $transdatas->vehicleno; ?></td>
                        <td> <?php echo $transdatas->groupname; ?></td>
                        <td> <?php echo $transdatas->dname; ?></td>
                        <td> <?php echo $transdatas->transid; ?></td>
                        <td> <?php echo $transdatas->meter_reading; ?></td>
                        <td> <?php echo $transdatas->cat; ?></td>
                        <td> <?php echo $transdatas->amount_quote; ?> </td>
                        <td style="word-break: break-all; width: 10%;" > <?php echo $transdatas->notes; ?> </td>
                        <td style="word-break: break-all; width: 10%;" > 
                        <?php
                            echo "Slip No : ".$transdatas->slipno;
                            echo "<br>Cheque No : ".$transdatas->chqno;
                            echo "<br>Cheque Amt : ".$transdatas->chequeamt;
                            echo "<br>Tds Amt : ".$transdatas->tdsamt;
                        ?> </td>
                        <td> <?php echo $transdatas->invoice_no; ?> </td>
                        <td> <?php echo $transdatas->invoice_amount; ?> </td>
                        <td> <?php echo $transdatas->invoice_date; ?> </td>
                        <td> <?php echo $transdatas->vehicle_out_date; ?> </td>
                        <td> <?php echo $transdatas->msname; ?> </td>
                        <?php
                        if (!empty($record_parts)) {
                            echo"<td style = 'text-align:left;'>";
                            $j = 1;
                            foreach ($record_parts as $parts) {
                                echo $j . ") ";
                                echo $parts;
                                echo "<br />";
                                $j++;
                            }
                            echo"</td>";
                        }
                        else {
                            echo"<td> N/A </td>";
                        }
                        if (!empty($record_tasks)) {
                            echo"<td style = 'text-align:left;'>";
                            $k = 1;
                            foreach ($record_tasks as $tasks) {
                                echo $k . ") ";
                                echo $tasks;
                                echo "<br />";
                                $k++;
                            }
                            echo"</td>";
                        }
                        else {
                            echo"<td> N/A</td>";
                        }

                        if ($transdatas->access == '') {
                            echo "<td>N/A</td>";
                        }
                        else {
                            echo "<td>$transdatas->access</td>";
                        }
                        ?>
                        <td> <?php echo $transdatas->tyre_type; ?>  </td>
                    </tr>
                    <?php
                }
                ?>
                    
            </tbody>            
        </table>
    </div>
    <?php
}
else if (isset($_POST["Filter"]) && empty($transdata) && !isset($errorfound)) {
    echo "Data Not Available";
}
$mail_function = "trans_hist_maintenance_mail(" . $_SESSION['customerno'] . ");";
include_once "mail_pop_up.php";
?>
<script>
    function SetCategory() {
        var categoryname = jQuery("#categoryid option:selected").text();
        if (categoryname == 'Select Category') {
            categoryname = ''
        }
        jQuery("#categoryname").val(categoryname);
    }

    function SetDealer() {
        var dealername = jQuery("#dealerid option:selected").text();
        if (dealername == 'Select Dealer') {
            dealername = ''
        }
        jQuery("#dealername").val(dealername);
    }

    function SetStatus() {
        var statusname = jQuery("#statusid option:selected").text();
        if (statusname == 'Select Status') {
            statusname = ''
        }
        jQuery("#statusname").val(statusname);
    }
</script>

