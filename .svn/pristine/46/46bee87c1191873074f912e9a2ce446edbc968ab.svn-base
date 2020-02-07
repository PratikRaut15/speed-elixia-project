
<form action="batterysrno_ajax.php" method="POST" name="myform" id="myform" onsubmit="return Validate();">
    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
        <thead>
        <th colspan="100%">Add Battery Serial No.</th>
        </thead>
        <tbody>
        <tr>
            <td colspan="100%" style="text-align: center;">
        <span id="error_vno" style="display:none;color: #FF0000;">Please Enter Vehicle No.</span>
        <span id="error_srno" style="display:none;color: #FF0000;">Please Enter Battery Serial No.</span>
            </td>
        </tr>
        <tr>
            <td>
                Vehicle No.
            </td>
            <td>
                <input  type="text" name="vehicleno" id="vehicleno" size="18" value="" placeholder="Enter Vehicle No" autocomplete="off" required/>
                <input type="hidden" name="vehid" id="vehid" size="20" value=""/>
                <div id="display" class="listvehicle"></div>
            </td>
        </tr>
        <tr>
            <td>
                Battery Serial No.
            </td>
            <td> 
                <input type="text" name="batt_srno" id="batt_srno" value="" />
            </td>
        </tr>
        <tr>
            <td>
                Installation Date
            </td>
            <td>
                <input type="text" name="ins_date" id="ins_date" value="" />
            </td>
        </tr>
        <tr>
            <td colspan="100%" style="text-align: center">
                <input type="submit" class="btn btn-primary" name="add_srno" id="add_srno" value="Add Battery Details" autocomplete="off">
            </td>
        </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#ins_date').datepicker({format: "dd-mm-yyyy", autoclose: true});
    });
    jQuery("#vehicleno").autoSuggest({
        ajaxFilePath: "batterysrno_ajax.php",
        ajaxParams: "dummydata=battvno",
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
        
        var vehid = jQuery("#vehid").val();
        var srno = jQuery("#batt_srno").val();
        if(vehid == ''){
            jQuery("#error_vno").show();
            jQuery("#error_vno").fadeOut(3000);
            return false;
        }else if(srno == ''){
            jQuery("#error_srno").show(3000);
            jQuery("#error_srno").fadeOut(6000);
            return false;
        }else{
            jQuery("#myform").submit()
        }
    }
</script>
