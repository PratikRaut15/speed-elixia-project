<?php
echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js' > </script>";
echo '<link href="' . $_SESSION['subdir'] . '/style/style.css" rel="stylesheet" type="text/css" />';
$probity_cust = explode(",", speedConstants::PROBITY_CUSTNO);
?>

<?php
$batch = "";
$today = date("d-m-Y");
$vehicle = getvehicle($_GET['vid']);
if (in_array($_SESSION['customerno'], $probity_cust)) {
    $batch = getbatch($_GET['vid']);
}
?>
<div id="pageloaddiv" style='display:none;'></div>
<form  class="form-horizontal well " id="deleteprobity"  action="probity.php?id=5&vid=<?php echo $_GET['vid']; ?>" method="POST"  onsubmit="deleteprobity();
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
    if (in_array($_SESSION['customerno'], $probity_cust)) {
        ?>
        <fieldset>
            <?php
            //if (in_array($_SESSION['customerno'], $probity_cust) && $_SESSION['customerno'] != 48) {
            if (in_array($_SESSION['customerno'], $probity_cust)) {
                ?>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Date</span>
                        <input id="SDate" name="SDate" type="text"  value="<?php echo date("d-m-Y"); ?>"/>
                        <br/><br/>
                        <span class="add-on">Start Time</span>
                        <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php echo date("H:i", strtotime($today)); ?>" value="<?php echo date("H:i", strtotime($today)); ?>"/>
                        <span class="add-on">End Time</span>
                        <input id="ETime" name="ETime" type="text" class="input-mini" data-date="<?php echo date("H:i", strtotime($today)); ?>" value="<?php echo date("H:i", strtotime($today)); ?>"/>
                        <br/><br/>
                    </div>
                </div>
            <?php } ?>
        </fieldset>
    <?php } ?>

    <fieldset>
        <div class="control-group pull-right">
            <input type="submit" class="btn  btn-primary" value="Delete Probity">&nbsp;
        </div>
    </fieldset>
    <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
    <input type="hidden" name="action" id="action" value="deleteprobity">

</form>

<script>
    $(document).ready(function () {
        $("#errormsg").hide();
    });

    function deleteprobity() {
        var sdate = jQuery('#SDate').val();
        var edate = jQuery('#EDate').val();
        var vehicleno = jQuery('#vehicleno').val();

        if (sdate != "" && edate != "" && vehicleno != "") {
            var data = jQuery("#deleteprobity").serialize();
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
