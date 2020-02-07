<?php
$result = getvehiclelist_seq();
?>
<link rel="stylesheet" type="text/css" href="../../scripts/jquery-ui/jquery-ui.css">
<script src="../../scripts/jquery-ui/jquery-ui.js"></script>
<style>

    #sortable1, #sortable2 {
        border: 2px solid #65686D;
        width: 142px;
        min-height: 20px;
        list-style-type: none;
        margin: 3px;
        padding: 5px ;
        float: left;
        cursor:pointer
    }
    #sortable1 li, #sortable2 li {
        margin: 0 5px 5px 5px;
        padding: 5px;
        font-size: 1.2em;
        width: 120px;
    }
    .sucmsg{
        color:green;
        font-size: 10px;
    }


</style>
<script>

    $(function () {
        $('body').click(function () {
            $('#msg').css('display', 'none');
        });

        $("#sortable1, #sortable2").sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    });
    function submit() {
        var default_idsInOrder = [];
        var idsInOrder = [];
        $("ul#sortable2 li").each(function () {
            var list = $(this).attr('id');
            if (list != 0) {
                idsInOrder.push(list)
            }
        });
        var seq = idsInOrder.join(',');

        $("ul#sortable1 li").each(function () {
            var deflist = $(this).attr('id');
            if (deflist != 0) {
                default_idsInOrder.push(deflist)
            }
        });
        var defaultseq = default_idsInOrder.join(',');

        var data = "seq=" + escape(seq) + "&defaultseq=" + escape(defaultseq) + "&action=sequenceupdate";
        jQuery.ajax({
            url: "vehicleseq_ajax.php",
            type: 'POST',
            data: data,
            success: function (result) {
                if (result == 'ok') {
                    $('#msg').css('display', 'table-row');
                    $(".sucmsg").html('Vehicle sequence Sucessfully updated.');
                }
            }
        });
    }
</script>
<h4 style="margin:5px;">Sorting sequence of <?php echo $vehicle_ses; ?> for realtimedata page</td></h4>
<table>
    <thead>
        
        <tr id="msg"><td colspan="2" class="sucmsg" style="text-align: center;"></td></tr>
        <tr>
            <th id="formheader">Default order <?php echo $vehicle_ses; ?> list</th>
            <th id="formheader">Customize order <?php echo $vehicle_ses; ?> list</th>
        </tr>
    </thead>
    <tr>
        <td>
            <ul id="sortable1" class="connectedSortable ">
                <?php
                if (isset($result)) {
                    foreach ($result as $vehicle) {
                        if ($vehicle->sequenceno == 0) {
                            echo "<li class='ui-state-default' id='" . $vehicle->vehicleid . "'>" . $vehicle->vehicleno . "</li>";
                        }
                    }
                }
                ?> 
            </ul>
        </td>    
        <td>

            <ul id="sortable2" class="connectedSortable ">
                <?php
                if (isset($result)) {
                    foreach ($result as $vehicle) {
                        if ($vehicle->sequenceno != 0) {
                            echo "<li class='ui-state-default' id='" . $vehicle->vehicleid . "'>" . $vehicle->vehicleno . "</li>";
                        }
                    }
                }
                ?>

            </ul>
        </td> 
    </tr> 
    <tr>
        <td colspan="5">  
            <input type="submit" class='btn-primary' style="margin: 5px;" value="Update" onclick="submit()">
        </td>
    </tr>
</table>
<!--<div style="alignment-adjust: central; padding: 5px; font-size:12px;"><span style="color:red; font-weight: bold;">NOTE </span>: Default list contents list of vehicle sorted by lastupdated time in descending order and Sortable list is your customize list.</div>-->