<?php
echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js' > </script>";
echo '<link href="' . $_SESSION['subdir'] . '/style/style.css" rel="stylesheet" type="text/css" />';
$probity_cust = explode(",", speedConstants::PROBITY_CUSTNO);
//print_r($probity_cust);
?>

<?php
$batch = "";
$vehicle = getvehicle($_GET['vid']);
//if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 48 || $_SESSION['customerno'] == 277 || $_SESSION['customerno'] == 289 || $_SESSION['customerno'] == 302) {
if (in_array($_SESSION['customerno'], $probity_cust)) {
    $batch = getbatch($_GET['vid']);
}
?>
<div id="pageloaddiv" style='display:none;'></div>
<form  class="form-horizontal well " id="editprobity"  action="probity.php?id=4&vid=<?php echo $_GET['vid']; ?>" method="POST"  onsubmit="editprobity();
        return false;" style="width:70%;">
    <input  type="hidden" name="vehicleid" value="<?php echo $_GET['vid']; ?>"  />

    <span id="errormsg" font-size: 10px;"></span>
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on"><?php echo $vehicles_ses; ?><span class="mandatory">*</span></span><input type="text" name="vehicleno"  value="<?php echo $vehicle->vehicleno; ?>" id="vehicleno" placeholder="Enter <?php echo $vehicles_ses; ?>" autofocus maxlength="20">
            </div>
    </fieldset>
    <?php
    //if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 48 || $_SESSION['customerno'] == 277 || $_SESSION['customerno'] == 289 || $_SESSION['customerno'] == 302) {
    if (in_array($_SESSION['customerno'], $probity_cust)) {
        ?>
        <fieldset>
            <?php
            //if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 277 || $_SESSION['customerno'] == 289 || $_SESSION['customerno'] == 302) {
            //if (in_array($_SESSION['customerno'], $probity_cust) && $_SESSION['customerno']!=48){
            if (in_array($_SESSION['customerno'], $probity_cust) ){
                ?>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Start Date</span>
                        <input id="SDate" name="SDate" type="text"  value="<?php
                        if (isset($batch)) {
                            if ($batch->starttime != '') {
                                echo date("d-m-Y", strtotime($batch->starttime));
                            }
                        }
                        ?>"/>

                        <span class="add-on">Start Time</span>
                        <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php
                        if (isset($batch)) {
                            if ($batch->starttime != '') {
                                echo date("H:i", strtotime($batch->starttime));
                            }
                        }
                        ?>" value="<?php
                               if (isset($batch)) {
                                   if ($batch->starttime != '') {
                                       echo date("H:i", strtotime($batch->starttime));
                                   }
                               }
                               ?>"/>
                        <br/><br/>
                        <span class="add-on">Select Master </span>
                        <select id="sel_master" name="sel_master">
                            <option value="0">Select Master</option>
                            <?php
                            $masters = getworkmaster();
                            if (isset($masters)) {
                                foreach ($masters as $group) {
                                    ?>
                                    <option value='<?php echo $group->pmid ?>' <?php
                                    if (isset($batch)) {
                                        if ($batch->pmid == $group->pmid) {
                                            echo "selected";
                                        }
                                    }
                                    ?> ><?php echo $group->workkey_name ?> - (<?php echo $group->workkey; ?>)</option>
                                            <?php
                                        }
                                    }
                                    ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
        </fieldset>
    <?php } ?>

    <fieldset>
        <div class="control-group pull-right">
            <input type="submit" class="btn  btn-primary" value="Edit Probity">&nbsp;
        </div>
    </fieldset>
    <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
    <input type="hidden" name="action" id="action" value="editaction">

</form>

<script>
    $(document).ready(function () {
        $("#errormsg").hide();
    });

    function editprobity() {
        var sdate = jQuery('#SDate').val();
        var vehicleno = jQuery('#vehicleno').val();
        var sel_master = jQuery('#sel_master').val();

        if (sdate != "" && vehicleno != "" && sel_master != 0) {
            var data = jQuery("#editprobity").serialize();
            jQuery('#pageloaddiv').show();
            jQuery.ajax({
                url: "../cron/probity_data_upload.php",
                type: 'POST',
                data: data,
                success: function (result) {
                    var obj = JSON.parse(result);
                    if (obj.status == 'failure') {
                        $('#errormsg').show();
                        $('#errormsg').html(obj.message);
                        $('#errormsg').css('color', 'red');
                        $('#errormsg').fadeOut(3000);

                    } else {
                        $('#errormsg').html(obj.message);
                        $('#errormsg').css('color', 'green');
                        $('#errormsg').fadeOut(3000);
                    }
                },
                complete: function () {
                    $('#pageloaddiv').hide();
                }
            });
        }
    }
</script>
