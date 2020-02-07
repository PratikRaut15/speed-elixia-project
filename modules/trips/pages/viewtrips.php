<script src="../../scripts/trips/viewtrips.js"></script>
<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
$customerno = $_SESSION['customerno'];
$gettripstatus = get_tripstatus($customerno, $_SESSION['userid']);
?>
<div class='container-fluid'>
    <center><h3>Trip Details</h3></center><br/>
    <div style="margin-top:0px;">
        <span>
            Page Size
        </span>
        <span>
            <select id="page-size">
                <option value="10" selected>10</option>
                <option value="100">100</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
            </select>
        </span>
        <span>
            <button id="exportExcelDataBttn">Export to Excel</button>
            <button id="exportCSVDataBttn">Export to Csv</button>
        </span>
        <span>
            <input type="text" id="filter-text-box" placeholder="Filter..."/>
        </span>
        <?php
if (isset($customerno)) {
    $addTripFilePath = $RELATIVE_PATH_DOTS . 'modules/trips/pages/customer/' . $customerno . '/addTripModal.php';
    $editTripFilePath = $RELATIVE_PATH_DOTS . 'modules/trips/pages/customer/' . $customerno . '/editTripModal.php';
    if (file_exists($addTripFilePath)) {
        $includeAddTripFilePath = $addTripFilePath;
        $includeEditTripFilePath = $editTripFilePath;
        $editModalId = "" . $customerno . "_editTripModal";
        if ($customerno != 447) {
            ?>
                    <button class="btn btn-default" onclick="openAddTripForm('<?php echo $customerno; ?>_addTripModal')"><i class="fa fa-plus fa-lg"></i> Add New Trip</button>
                <?php
}
    } else {
        $includeAddTripFilePath = 'addTripModal.php';
        $includeEditTripFilePath = 'editTripModal.php';
        $editModalId = 'editTripModal';
        if ($customerno != 795) {
            ?>
                    <button class="btn btn-default" onclick="openAddTripForm('addTripModal')"><i class="fa fa-plus fa-lg"></i> Add New Trip</button>
                <?php
}
    }
}
?>
    </div>
    <div id="myGrid" style="height:450px;text-align: left;margin-top:0px" class="ag-theme-balham"></div>
</div>
<!-- Modal -->
<?php
include_once $includeAddTripFilePath;
include_once $includeEditTripFilePath;
?>

<script type="text/javascript">
    var data            = <?php echo json_encode($gettriprecords); ?>;
    var gridDiv         = document.getElementById('myGrid');
    var editModalId     = "<?php echo $editModalId; ?>";
    var customerno      = "<?php echo $customerno; ?>";
    jQuery(document).ready(function (){
        makeGridView(data,gridDiv,editModalId,customerno);
        jQuery('#etarrivaldate').datepicker({format: "dd-mm-yyyy",autoclose:true});
    })
    jQuery(function (){
        jQuery("#consignor").autocomplete({
            source: "trip_ajax.php?action=consignorauto", minLength: 1,
            select: function (event, ui) {
                jQuery('#consignorid').val(ui.item.id);
            }
        });
        jQuery("#consignee").autocomplete({
            source: "trip_ajax.php?action=consigneeauto", minLength: 1,
            select: function (event, ui) {
                jQuery('#consigneeid').val(ui.item.id);
            }
        });
    });

    $(function () {
        $("#edittripstatus").change(function () {
            var statusid = $("#tripstatus").val();
            if (statusid == 10) {
                var tripid = $("#tripid").val();
                setunloading_values(statusid, tripid);
            }
        });
        $("#editvehicleno").autoSuggest({
            ajaxFilePath: "../reports/autocomplete.php",
            ajaxParams: "dummydata=dummyData",
            autoFill: false,
            iwidth: "auto",
            opacity: "0.9",
            ilimit: "10",
            idHolder: "id-holder",
            match: "contains"
        });
    });
    function setunloading_values(statusid, tripid) {
        var sdate = $("#editSDate").val();
        var stime = $("#editSTime").val();
        var data = "statusid=" + statusid + "&tripid=" + tripid + "&sdate=" + sdate + "&stime=" + stime + "&action=getunloadingdata";
        jQuery.ajax({url: "trip_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.actualhrs == -0) {
                    alert("Please select other status date");
                }
                else {
                    $("#actualhrs").val(obj.actualhrs);
                    var budgetedhrs = $("#budgetedhrs").val();
                    var esttime = budgetedhrs - obj.actualhrs;
                    $("#esttime").val(esttime);
                }
            },
            complete: function () {
                //jQuery('#pageloaddiv').hide();
            }
        });
    }
    function fill(Value, strparam) {
        $('#editvehicleno').val(strparam);
        $('#editvehicleid').val(Value);
        $('#display').hide();
    }
    jQuery(document).ready(function (){
                    jQuery('#editetarrivaldate').datepicker({format: "dd-mm-yyyy",autoclose:true});
                     jQuery('#editSDate').datepicker({format: "dd-mm-yyyy", autoclose: true});
        jQuery("#editconsignor").autocomplete({
            source: "trip_ajax.php?action=consignorauto", minLength: 1,
            select: function (event, ui) {
                jQuery('#consignorid').val(ui.item.id);
            }
        });
        jQuery("#editconsignee").autocomplete({
            source: "trip_ajax.php?action=consigneeauto", minLength: 1,
            select: function (event, ui) {
                jQuery('#consigneeid').val(ui.item.id);
            }
        });
    });
    function getUnloadingDates(triplogno) {
        jQuery.ajax({
            type: "POST",
            url: "trip_ajax.php",
            cache: false,
            data: {action: 'getunloadingtime', triplogno: triplogno},
            success: function (jsonResult) {
                var result = jQuery.parseJSON(jsonResult);
                if (result.Status === "Success") {
                    jQuery("#edittripstatus").val(result.data.unloadingendStatusId);
                    jQuery("#editSDate").val(result.data.unloadenddate);
                    jQuery("#editSTime").val(result.data.unloadendtime);
                    jQuery("#lblGetUnloadingDateMsg").hide();
                    jQuery("#lblUnloadingDateMsg").show();
                    jQuery("#lblUnloadingDateMsg").text('Successfully pulled the trip details.');
                    refreshStatusHistory();
                }
                else {
                    jQuery("#lblGetUnloadingDateMsg").hide();
                    jQuery("#lblUnloadingDateMsg").show();
                    jQuery("#lblUnloadingDateMsg").text(result.Error);
                }
            }
        });
    }
    function refreshStatusHistory() {
        var tripid = jQuery('#tripid').val();
        if (tripid !== undefined && tripid !== '') {
            get_historydetails(tripid);
        }
    }
    jQuery(document).ready(function (){
            jQuery('#etarrivaldate').datepicker({format: "dd-mm-yyyy",autoclose:true});
    });
</script>