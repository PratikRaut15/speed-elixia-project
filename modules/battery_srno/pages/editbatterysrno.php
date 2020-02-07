<?php

$data = getsrno_details_byid($_GET['bmid']);

?>
<form action="batterysrno_ajax.php" method="POST">
    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
        <thead>
        <th colspan="100%">Vehicle No. <?php echo $data->vehicleno;?></th>
        </thead>
        <tbody>
            <tr>
                <td>
                    Battery Serial No.
                </td>
                <td>
                    <input type="hidden" name="batt_mapid" id="batt_mapid" value="<?php echo $data->batt_mapid;?>">
                     <input type="hidden" name="vehid" id="vehid" value="<?php echo $data->vehid;?>">
                    <input type="text" name="batt_srno" id="batt_srno" value="<?php echo $data->batt_serialno;?>">
                </td>
            </tr>
            <tr>
                <td>
                    Installation Date
                </td>
                <td>
                    <input type="text" name="ins_date" id="ins_date" value="<?php echo $data->installedon;?>">
                </td>
            </tr>
            <tr>
                <td colspan="100%" style="text-align: center">
                    <input type="submit" class="btn btn-primary" name="edit_srno" id="edit_srno" value="Edit Battery Details">
                </td>
            </tr>
        </tbody>
    </table>
</form>

<script>
    jQuery(document).ready(function(){
        jQuery('#ins_date').datepicker({format: "dd-mm-yyyy",autoclose:true});
    });
</script>

