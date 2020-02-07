<?php
$list = getFilterTyreData($_GET['vehid']);
//print_r($list);
?>
<form action="tyresrno_ajax.php" method="POST" name="myform" id="myform" onsubmit="return Validate();">
    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
        <thead>
        <th colspan="100%">Vehicle No. <?php echo $list[0]->vehicleno; ?></th>
        </thead>
        <tbody>
            <tr>
                <td colspan="100%" style="text-align: center;">
                    <span id="error_chk" style="display:none;color: #FF0000;">Please Tick Check Box</span>
                    <span id="error_srno" style="display:none;color: #FF0000;">Please Enter Tyre Serial No.</span>
                </td>
            </tr>
            <tr>
                <td colspan="100%">

                    <table class="table table-bordered">
                        <tr>
                        <span id="rf_error" style="display: none;color: #FF0000">Please Enter New Right Front Sr No.</span>
                        <span id="rbout_error" style="display: none;color: #FF0000;">Please Enter New Right Back Out Sr No.</span>
                        <span id="rbin_error" style="display: none;color: #FF0000;">Please Enter New Right Back In Sr No.</span>
                        <span id="lf_error" style="display: none;color: #FF0000;">Please Enter New Left Front Sr No.</span>
                        <span id="lbout_error" style="display: none;color: #FF0000;">Please Enter New Left Back Out Sr No.</span>
                        <span id="lbin_error" style="display: none;color: #FF0000;">Please Enter New Left Back In Sr No.</span>
                        <span id="st_error" style="display: none;color: #FF0000;">Please Enter New Stepney Sr No.</span>
                        <span id="chk_error" style="display: none;color: #FF0000;">Please Tick Checkbox</span>
            </tr>
        <thead>
        <th colspan="100%" style="background:#CCCCCC;font-weight: bold;">Edit Tyre Serial No. Details</th>
        </thead>
        <tbody>
            <tr>
                <td style="background:#CCCCCC;font-weight: bold;">
                    Tyre Type
                </td>
                <td style="background:#CCCCCC;font-weight: bold;">
                </td>
                <td style="background:#CCCCCC;font-weight: bold;">
                    Tyre Serial No.
                </td>
                <td style="background:#CCCCCC;font-weight: bold;">
                    Installed On
                </td>
            </tr>
            <tr>    
                <td>Right Front
                    <input type="hidden" name="veh" id="veh" value="<?php echo $_GET['vehid']; ?>"/>
                </td>   

                <td><input name="rf" type="checkbox" class="chk" id="rf" value="rf"  onclick="activetextbox();" ></td>
                <td><input name="rf_srno" type="text"  id="rf_srno" value="<?php echo $list[0]->mappedtyres[0]->serialno; ?>"  readonly/></td>
                <td><input name="rf_insdate" type="text" class="txtsrno" id="rf_insdate"  value="<?php echo $list[0]->mappedtyres[0]->insdate; ?>" readonly /></td>

            </tr>
            <tr>    
                <td>Left Front</td>
                <td><input name="lf" type="checkbox" class="chk" id="lf" value="lf"onclick="activetextbox();"></td>
                <td><input name="lf_srno" type="text" id="lf_srno" value="<?php echo $list[0]->mappedtyres[1]->serialno; ?>" readonly=""/></td>
                <td><input name="lf_insdate" type="text" class="txtsrno" id="lf_insdate" value="<?php echo $list[0]->mappedtyres[1]->insdate; ?>"readonly=""/></td>

            </tr>

            <tr>    
                <td>Right Back Out</td>
                <td><input name="rb_out" type="checkbox" class="chk" id="rb_out" value="rb_out" onclick="activetextbox();"></td>
                <td><input name="rb_out_srno" type="text" id="rb_out_srno" value="<?php echo $list[0]->mappedtyres[2]->serialno; ?>" readonly=""/></td>
                <td><input name="rb_out_insdate" type="text" class="txtsrno" id="rb_out_insdate" value="<?php echo $list[0]->mappedtyres[2]->insdate; ?>" readonly=""/></td>

            </tr>
            <tr>   
                <td>Left Back Out</td>
                <td><input name="lb_out" type="checkbox" class="chk" id="lb_out" value="lb_out" onclick="activetextbox();"></td>
                <td><input name="lb_out_srno" type="text" id="lb_out_srno" value="<?php echo $list[0]->mappedtyres[3]->serialno; ?>" readonly=""/></td>
                <td><input name="lb_out_insdate" type="text" class="txtsrno" id="lb_out_insdate" value="<?php echo $list[0]->mappedtyres[3]->insdate; ?>" readonly=""/></td>

            </tr>
            <tr>
                <td>Stepney</td>
                <td><input name="st" type="checkbox" class="chk" id="st" value="st"onclick="activetextbox();"></td>
                <td><input name="st_srno" type="text" id="st_srno" value="<?php echo $list[0]->mappedtyres[4]->serialno; ?>" readonly=""/></td>
                <td><input name="st_insdate" type="text" class="txtsrno" id="st_insdate" value="<?php echo $list[0]->mappedtyres[4]->insdate; ?>" readonly=""/></td>

            </tr>
            <tr>    
                <td>Right Back In</td>                     
                <td><input name="rb_in" type="checkbox" class="chk" id="rb_in" value="rb_in" onclick="activetextbox();"></td>
                <td><input name="rb_in_srno" type="text"  id="rb_in_srno" value="<?php echo $list[0]->mappedtyres[5]->serialno; ?>" readonly=""/></td>
                <td><input name="rb_in_insdate" type="text" class="txtsrno" id="rb_in_insdate" value="<?php echo $list[0]->mappedtyres[5]->insdate; ?>" readonly=""/></td>

            </tr>
            <tr>    
                <td>Left Back In</td>
                <td><input name="lb_in" type="checkbox" class="chk" id="lb_in" value="lb_in" onclick="activetextbox();"></td>
                <td><input name="lb_in_srno" type="text" id="lb_in_srno" value="<?php echo $list[0]->mappedtyres[6]->serialno; ?>" readonly=""/></td>
                <td><input name="lb_in_insdate" type="text" class="txtsrno" id="lb_in_insdate" value="<?php echo $list[0]->mappedtyres[6]->insdate; ?>" readonly=""/></td>

            </tr>

        </tbody>
    </table>
</td>
</tr>
<td colspan="100%" style="text-align: center">
    <input type="submit" class="btn btn-primary" name="edit_srno" id="edit_srno" value="Edit Tyre Details" >
</td>
</tr>
</tbody>
</table>
</form>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.txtsrno').datepicker({format: "dd-mm-yyyy", autoclose: true});
    });
    jQuery("#vehicleno").autoSuggest({
        ajaxFilePath: "tyresrno_ajax.php",
        ajaxParams: "dummydata=tyrevno",
        autoFill: false,
        iwidth: "auto",
        opacity: "0.9",
        ilimit: "10",
        idHolder: "id-holder",
        match: "contains"
    });

    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#vehid').val(Value);
        jQuery('#display').hide();
    }

    function Validate() {

        if (jQuery("#rf").prop("checked") || jQuery("#rb_out").prop("checked") || jQuery("#rb_in").prop("checked") || jQuery("#st").prop("checked")
                || jQuery("#lb_out").prop("checked") || jQuery("#lf").prop("checked") || jQuery("#lb_in").prop("checked"))
        {
            jQuery("#myform").submit();
            //return false;

        } else {
            jQuery("#error_chk").show();
            jQuery("#error_chk").fadeOut(3000);
            return false;
        }
    }

    function activetextbox()
    {
        var tyretype = jQuery("#tyrerepair").val();
        if (tyretype == 2 || tyretype == 3) {
            jQuery(".txtsrno").css("display", "none");
            if (jQuery("#rf").prop("checked"))
            {
                jQuery("#oright_front").attr("readonly", false);
            } else {
                jQuery("#oright_front").attr("readonly", true);
            }

            if (jQuery("#lf").prop("checked"))
            {
                jQuery("#oleft_front").attr("readonly", false);
            } else {
                jQuery("#oleft_front").attr("readonly", true);
            }

            if (jQuery("#rb_out").prop("checked"))
            {
                jQuery("#oright_back_out").attr("readonly", false);
            } else {
                jQuery("#oright_back_out").attr("readonly", true);
            }

            if (jQuery("#lb_out").prop("checked"))
            {
                jQuery("#oleft_back_out").attr("readonly", false);
            } else {
                jQuery("#oleft_back_out").attr("readonly", true);
            }

            if (jQuery("#rb_in").prop("checked"))
            {
                jQuery("#oright_back_in").attr("readonly", false);
            } else {
                jQuery("#oright_back_in").attr("readonly", true);
            }
            if (jQuery("#lb_in").prop("checked"))
            {
                jQuery("#oleft_back_in").attr("readonly", false);
            } else {
                jQuery("#oleft_back_in").attr("readonly", true);
            }

            if (jQuery("#st").prop("checked"))
            {
                jQuery("#ostepney").attr("readonly", false);
            } else {
                jQuery("#ostepney").attr("readonly", true);
            }
        } else {
            if (jQuery("#rf").prop("checked"))
            {
                jQuery("#rf_srno").attr("readonly", false);
                jQuery("#rf_insdate").attr("readonly", false);
            } else {
                jQuery("#rf_srno").attr("readonly", true);
                jQuery("#rf_insdate").attr("readonly", true);
            }

            if (jQuery("#rb_out").prop("checked"))
            {
                jQuery("#rb_out_srno").attr("readonly", false);
                jQuery("#rb_out_insdate").attr("readonly", false);
            } else {
                jQuery("#rb_out_srno").attr("readonly", true);
                jQuery("#rb_out_insdate").attr("readonly", true);
            }

            if (jQuery("#rb_in").prop("checked"))
            {
                jQuery("#rb_in_srno").attr("readonly", false);
                jQuery("#rb_in_insdate").attr("readonly", false);
            } else {
                jQuery("#rb_in_srno").attr("readonly", true);
                jQuery("#rb_in_insdate").attr("readonly", true);
            }
            if (jQuery("#lb_out").prop("checked"))
            {
                jQuery("#lb_out_srno").attr("readonly", false);
                jQuery("#lb_out_insdate").attr("readonly", false);
            } else {
                jQuery("#lb_out_srno").attr("readonly", true);
                jQuery("#lb_out_insdate").attr("readonly", true);
            }
            if (jQuery("#lf").prop("checked"))
            {
                jQuery("#lf_srno").attr("readonly", false);
                jQuery("#lf_insdate").attr("readonly", false);
            } else {
                jQuery("#lf_srno").attr("readonly", true);
                jQuery("#lf_insdate").attr("readonly", true);
            }
            if (jQuery("#lb_in").prop("checked"))
            {
                jQuery("#lb_in_srno").attr("readonly", false);
                jQuery("#lb_in_insdate").attr("readonly", false);
            } else {
                jQuery("#lb_in_srno").attr("readonly", true);
                jQuery("#lb_in_insdate").attr("readonly", true);
            }
            if (jQuery("#st").prop("checked"))
            {
                jQuery("#st_srno").attr("readonly", false);
                jQuery("#st_insdate").attr("readonly", false);
            } else {
                jQuery("#st_srno").attr("readonly", true);
                jQuery("#st_insdate").attr("readonly", true);
            }
        }
    }
</script>
