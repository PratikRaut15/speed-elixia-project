<?php
$StartDate = getdate_IST();
$EndDate = $StartDate;
$stime = "00:00";
$etime = "23:59";
if (isset($_POST['STdate'])) {
    $StartDate = strtotime($_POST['STdate']);
}
if (isset($_POST['EDdate'])) {
    $EndDate = strtotime($_POST['EDdate']);
}
if (isset($_POST['STime'])) {
    $stime = $_POST['STime'];
}
if (isset($_POST['ETime'])) {
    $etime = $_POST['ETime'];
}

$chktype = isset($_POST['chktype']) ? $_POST['chktype'] : 1;

$main_checkpoints = get_all_checkpoint();
$checkpointopt = "";
if (isset($main_checkpoints)) {
    foreach ($main_checkpoints as $check) {
        if (isset($_POST['checkpoint_start']) && $check->checkpointid == $_POST['checkpoint_start'] && $chktype == '1') {
            $checkpointopt .= "<option selected='selected' value='$check->checkpointid'>$check->cname</option>";
        } else {
            $checkpointopt .= "<option value='$check->checkpointid'>$check->cname</option>";
        }
    }
}
$checkpointopt_end = "";
if (isset($main_checkpoints)) {
    foreach ($main_checkpoints as $check_end) {
        if (isset($_POST['checkpoint_end']) && $check_end->checkpointid == $_POST['checkpoint_end'] && $chktype == '1') {
            $checkpointopt_end .= "<option selected='selected' value='$check_end->checkpointid'>$check_end->cname</option>";
        } else {
            $checkpointopt_end .= "<option value='$check_end->checkpointid'>$check_end->cname</option>";
        }
    }
}
$checkpointopt_via = "";
if (isset($main_checkpoints)) {
    foreach ($main_checkpoints as $check_via) {
        if (isset($_POST['checkpoint_via']) && $check_via->checkpointid == $_POST['checkpoint_via'] && $chktype == '1') {
            $checkpointopt_via .= "<option selected='selected' value='$check_via->checkpointid'>$check_via->cname</option>";
        } else {
            $checkpointopt_via .= "<option value='$check_via->checkpointid'>$check_via->cname</option>";
        }
    }
}
$main_checkpointtype = get_all_checkpoint_type();
$checkpointtypeopt = "";
if (isset($main_checkpointtype)) {
    foreach ($main_checkpointtype as $checktype) {
        if (isset($_POST['checkpointtype_start']) && $checktype->ctid == $_POST['checkpointtype_start'] && $chktype == '2') {
            $checkpointtypeopt .= "<option selected='selected' value='$checktype->ctid'>$checktype->name</option>";
        } else {
            $checkpointtypeopt .= "<option value='$checktype->ctid'>$checktype->name</option>";
        }
    }
}
$checkpointtypeopt_end = "";
if (isset($main_checkpointtype)) {
    foreach ($main_checkpointtype as $checktype_end) {
        if (isset($_POST['checkpointtype_end']) && $checktype_end->ctid == $_POST['checkpointtype_end'] && $chktype == '2') {
            $checkpointtypeopt_end .= "<option selected='selected' value='$checktype_end->ctid'>$checktype_end->name</option>";
        } else {
            $checkpointtypeopt_end .= "<option value='$checktype_end->ctid'>$checktype_end->name</option>";
        }
    }
}
$checkpointtypeopt_via = "";
if (isset($main_checkpointtype)) {
    foreach ($main_checkpointtype as $checktype_via) {
        if (isset($_POST['checkpointtype_via']) && $checktype_via->ctid == $_POST['checkpointtype_via'] && $chktype == '2') {
            $checkpointtypeopt_via .= "<option selected='selected' value='$checktype_via->ctid'>$checktype_via->name</option>";
        } else {
            $checkpointtypeopt_via .= "<option value='$checktype_via->ctid'>$checktype_via->name</option>";
        }
    }
}
$select = "";
if (isset($_POST['report'])) {
    switch ($_POST['report']) {
        case 'trip':
            $select .= '<option value="trip" selected="selected">Summarize By Trip</option>';
            $select .= '<option value="vehi">Summarize By Vehicle</option>';
            break;
        case 'vehi':
            $select .= '<option value="trip" >Summarize By Trip</option>';
            $select .= '<option value="vehi" selected="selected">Summarize By Vehicle</option>';
    }
} else {
    $select .= '<option value="trip">Summarize By Trip</option>';
    $select .= '<option value="vehi">Summarize By Vehicle</option>';
}
?>

<form action="reports.php?id=30" method="POST" onsubmit="getTripReport();return false;" id="frmGetTripReport">
    <?php
    include 'panels/tripreport.php';

    if (isset($_SESSION['ecodeid'])) {
        ?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
        <?php
    }
    ?>
    <tr>
        <td>Vehicle No
            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" >
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
        </td>
        <td>Start Date
            <input id="SDate" name="STdate" size="10" type="text" value="<?php echo date('d-m-Y', $StartDate); ?> " required/>
        </td>
        <td>Start Hour<br/>
            <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php echo $stime; ?>" />
        </td>
        <td>End Date<br/>
            <input id="EDate" name="EDdate" size="10" type="text" value="<?php echo date('d-m-Y', $EndDate); ?>" required/>
        </td>
        <td>End Hour<br/>
            <input id="ETime" name="ETime" type="text" class="input-mini" data-date2="<?php echo $etime; ?>"/></td>
        </td>
        <td>
            <select id="report" name="report" style="width: 150px;"  required>
                <?php echo $select; ?>
            </select></td>
        <td>
            <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
        </td>
        <td><a href='javascript:void(0)' onclick="get_pdfTrip_Report(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a></td>
        <td><a href='javascript:void(0)' onclick="html2xlsTrip_Report(<?php echo $_SESSION['customerno']; ?>); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a></td>
    </tr>
    <tr><td></td>
        <td>
            <select id="chktype" name="chktype" onchange="typeOnChange(this)">
                <option value="1">Checkpoint</option>
                <option value="2">Checkpoint Type</option>
            </select>
        </td>
        <td>
            <select id="checkpoint_start" name="checkpoint_start" style="width: 150px;" required>
                <option value=''>Select Start Checkpoint</option>
                <?php echo $checkpointopt; ?>
            </select>
            <select id="checkpointtype_start" name="checkpointtype_start" style="width: 150px;display: none;">
                <option value=''>Select Start Checkpoint Type</option>
                <?php echo $checkpointtypeopt; ?>
            </select>
        </td>
        <td>
            <select id="checkpoint_via" name="checkpoint_via" style="width:150px;">
                <option value=''>Select Via Checkpoint</option>
                <?php echo $checkpointopt_via; ?>
            </select>
            <select id="checkpointtype_via" name="checkpointtype_via" style="width:150px;display: none;">
                <option value=''>Select Via Checkpoint Type</option>
                <?php echo $checkpointtypeopt_via; ?>
            </select>
        </td>
        <td>
            <select id="checkpoint_end" name="checkpoint_end" style="width:150px;" required>
                <option value=''>Select End Checkpoint</option>
                <?php echo $checkpointopt_end; ?>
            </select>
            <select id="checkpointtype_end" name="checkpointtype_end" style="width:150px;display:none;">
                <option value=''>Select End Checkpoint Type</option>
                <?php echo $checkpointtypeopt_end; ?>
            </select>
        </td>
        <td colspan="4"></td>
    </tr>

    <tr>
    </tr>
    <tr id="Temperature" class="tr" style="display: none;"></tr>
</tbody>
</table>
</form>
<br>
<span id="error4" style="display: none;"> Date selection range not more from 30 days</span>
<span id="error5" style="display: none;">
    <img src="../../images/RTD/Fuel/gauge.png" alt="Inactive"/>
</span>
<br/><br>
<div id="centerDiv"></div>
<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    ?>
    <input type="button"  id="print" style="display: none;" value="Print Graph" onclick="PrintElem('#graph_div')" />
<?php }
?>
<script type="text/javascript">

    $(function () {
        $("#vehicleno").autoSuggest({
            ajaxFilePath: "autocomplete.php",
            ajaxParams: "dummydata=dummyData",
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
    function getTripReport() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#frmGetTripReport").serialize();
        jQuery.ajax({
            url: "tripreport_ajax.php",
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

    function typeOnChange(data) {
        if (data.value == 1) {
            jQuery('#checkpoint_start').show();
            jQuery('#checkpoint_via').show();
            jQuery('#checkpoint_end').show();
            jQuery('#checkpoint_start').prop('required', true);
            jQuery('#checkpoint_end').prop('required', true);
            jQuery('#checkpointtype_start').hide();
            jQuery('#checkpointtype_via').hide();
            jQuery('#checkpointtype_end').hide();
            jQuery('#checkpointtype_start').prop('required', false);
            jQuery('#checkpointtype_end').prop('required', false);
        } else if (data.value == 2) {
            jQuery('#checkpoint_start').hide();
            jQuery('#checkpoint_via').hide();
            jQuery('#checkpoint_end').hide();
            jQuery('#checkpoint_start').prop('required', false);
            jQuery('#checkpoint_end').prop('required', false);
            jQuery('#checkpointtype_start').show();
            jQuery('#checkpointtype_via').show();
            jQuery('#checkpointtype_end').show();
            jQuery('#checkpointtype_start').prop('required', true);
            jQuery('#checkpointtype_end').prop('required', true);
        }


    }

</script>
