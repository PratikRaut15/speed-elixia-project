<?php
echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js' > </script>";
echo '<link href="' . $_SESSION['subdir'] . '/style/style.css" rel="stylesheet" type="text/css" />';
?>
<div id="pageloaddiv" style='display:none;'></div>
<form  class="form-horizontal well "  name="addprobityform" id="addprobityform"  action="probity.php?id=1" method="POST" style="width:70%;">
    <span id="errormsg" font-size: 10px;"></span> 
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on"><?php echo $vehicles_ses; ?>
                    <span class="mandatory">*</span>
                </span>
                <input type="text" name="vehicleno" id="vehicleno" placeholder="Enter <?php echo $vehicles_ses; ?>" autofocus maxlength="20">
            </div>
        </div>    
    </fieldset>    
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Unit No  <span class="mandatory">*</span></span>
                <input type="text" name="unitno" id="unitno" placeholder="Enter Unit Number" autofocus maxlength="20">
            </div>
        </div>    
    </fieldset>    
    <fieldset>
        <div class="control-group">
            <div class="input-prepend">
                <span class="add-on">Start Date<span class="mandatory">*</span></span>
                <input id="SDate" name="SDate" type="text" />
                <span class="add-on">Start Time</span>
                <input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" />
                <br/><br/>
                <div class="control-group">
                    <div class="input-prepend ">
                        <span class="add-on">Batch <span class="mandatory">*</span></span><input type="text" name="batch" id="batch"  size="10" placeholder="Batch" maxlength="15">
                    </div>
                    <div class="input-prepend ">
                        <span class="add-on">Work Key <span class="mandatory">*</span></span>
                        <input type="text" name="workkey" id="workkey" placeholder="work Key"  maxlength="4" size="10" />
                    </div>
                </div>
                <span class="add-on">Select Master <span class="mandatory">*</span></span>
                <select id="sel_master" name="sel_master">
                    <option value="0">Select Master</option>
                    <?php
                    $masters = getworkmaster();
                    if (isset($masters)) {
                        foreach ($masters as $group) {
                            ?>
                            <option value='<?php echo $group->pmid ?>' <?php
                            if(isset($batch->pmid)){
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
    </fieldset>
    <fieldset>
        <div class="control-group pull-right">
            <input type="button"  class="btn  btn-primary" value="Add Probity" onclick="addprobity();"return false;>&nbsp;
        </div>      
    </fieldset>
    <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
    <input type="hidden" name="action" id="action" value="addprobity">
</form>

<script>
    $(document).ready(function () {
        $("#errormsg").hide();
    });
    function addprobity() {
        var sdate = jQuery('#SDate').val();
        var vehicleno = jQuery('#vehicleno').val();
        var unitno = jQuery('#unitno').val();
        var sel_master = jQuery('#sel_master').val();
        var batch = jQuery('#batch').val();
        var workkey = jQuery('#workkey').val();

        if (vehicleno == "") {
            errormsg("Please enter vehicleno.");
        }
        if (unitno == "") {
            errormsg("Please enter unitno.");
        }
        if (sdate == "") {
            errormsg("Please select startdate");
        }
        if (workkey == "") {
            errormsg("Please enter workkey");
        }
        if (batch == "") {
            errormsg("Please enter batch");
        }

        if (sdate != "" && vehicleno != "" && sel_master != 0) {
            var data = jQuery("#addprobityform").serialize();
            jQuery('#pageloaddiv').show();
            jQuery.ajax({
                url: "../cron/probity_data_upload.php",
                type: 'POST',
                data: data,
                success: function (result) {
                    var obj = JSON.parse(result);
                    if (obj.status == 'failure') {
                        errormsg(obj.message);
                    } else {
                        $('#errormsg').show();
                        $('#errormsg').html(obj.message);
                        $('#errormsg').css('color', 'green');
                        $('#errormsg').fadeOut(5000);
                    }
                },
                complete: function () {
                    $('#pageloaddiv').hide();
                }
            });
        }
    }

    function errormsg(msg) {
        $('#errormsg').show();
        $('#errormsg').html(msg);
        $('#errormsg').css('color', 'red');
        $('#errormsg').fadeOut(3000);
        return false;
    }
</script>