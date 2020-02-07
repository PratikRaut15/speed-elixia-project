<?php
if (isset($_POST['search'])) {
    $data = getFilterTyreData($_POST['vehid']);
} else {
    $data = getTyreData();
}
?>
<form action="" method="POST" >
    <table  style="border: none">
        <tr><td style="border: none">
                Vehicle No
            </td>
            <td style="border: none">
                <input  type="text" name="vehicleno" id="vehicleno" size="18" value="<?php
                if (isset($_POST['vehicleno'])) {
                    echo $_POST['vehicleno'];
                }
                ?>" placeholder="Enter Vehicle No" autocomplete="off" required/>
                <input type="hidden" name="vehid" id="vehid" size="20" value=""/>
                <div id="display" class="listvehicle"></div>
            </td>
            <td style="border: none">
                <input type="submit" class=" btn btn-primary" name="search" id="search" value="Search" />
            </td>
        </tr>
    </table>
</form>
<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:75%">
    <thead>
    <th>Sr. No.</th>
    <th>Vehicle No.</th>
    <th>Right Front</th>
    <th>Left Front</th>
    <th>Right Back Out</th>
    <th>Left Back Out</th>
    <th>Stepney</th>
    <th>Right Back In</th>
    <th>Left Back In</th>
    <th>Options</th>
</thead>
<tbody>
    <?php
    if (!empty($data)) {
        $x = 0;
        foreach ($data as $datas) {
            $x++;
            echo "<tr>";
            echo "<td>$x</td>";
            echo "<td>$datas->vehicleno</td>";
            foreach ($datas->mappedtyres as $mapped) {
                $updatedon = empty($mapped->updatedon) ? "" : "(" . $mapped->updatedon . ")";
                echo "<td>$mapped->serialno<br/>$updatedon</td>";
            }
            echo "<td><a href='tyre_srno.php?id=4&vehid=$datas->vehicleid' ><i class='icon-pencil'></i></a></td></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>
            <td colspan=100% style='text-align:center;'>No Tyre Serial Nos. Added</td>
        </tr>";
    }
    ?>
</tbody>
</table>
<script type="text/javascript">

    jQuery("#vehicleno").autoSuggest({
        ajaxFilePath: "tyresrno_ajax.php",
        ajaxParams: "dummydata=vehicleno",
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
</script>
