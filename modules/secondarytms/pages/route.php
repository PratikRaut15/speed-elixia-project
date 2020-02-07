<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<link rel="stylesheet" type="text/css" href="../../scripts/autocomplete/jquery-ui.min.css">
<script type="text/javascript" src="../../scripts/autocomplete/jquery-ui.min.js"></script>
<style>
    #vehcietypemaster_filter{display: none}
    .dataTables_length{display: none}
    .ajax_response_8{
        margin-left: 710px;
    }
</style>
<br/>
<div class='container' >
    <center>

        <form style='display:inline;width: 90%;' method="post" action="action.php?action=add-vehicle-type" >
            <div class="input-prepend ">
                <span class="add-on" style="text-align: left; width: 110px; ">Route Name</span>
                <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="25" placeholder="Enter Route" />
                <span class="add-on" style="text-align: left;width: 110px;">Drop Point</span>
                <select name="droppoint" id="droppoint" multiple size="4" style="width: 150px;">
                    <option>Mumbai</option>
                    <option>Navi Mumbai</option>
                    <option>Nagpur</option>
                    <option>Aurangabad</option>
                    <option>Bhiwandi</option>
                    <option>Bhopal</option>
                    <option>Indore</option>
                    <option>Surat</option>
                    <option>Pune</option>
                    <option>Nashik</option>
                    <option>Goa</option>
                    <option>Chennai</option>
                </select>
                <input style='display:inline;' type='submit' class="btn  btn-secondary" value='  Add Route  '/>
                <div id="listccmailid"></div>
            </div>
        </form>
    </center>
</div>
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='vehcietypeMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="vehcietypemaster" style="width: 90%;" >
            <thead>
                <tr>

                    <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>

                    <td colspan="2"></td>



                </tr>
                <tr class='dtblTh'>

                    <th >Sr.No </th>
                    <th >Route Name </th>
                    <th >No. Of Drop Points</th>
                    <th >Created By</th>
                    <th >Created On</th>
                    <th >Edit</th>
                    <th >Delete</th>


                    <!--
                    <th></th>
                    -->
                </tr>
            </thead>
        </table>

    </center>
</div>
<script type='text/javascript'>
    var data = <?php
    $transporters = array(
        array('srno' => '1', 'name' => 'Route 1', 'droppoint' => '2', 'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '2','name' => 'Route 3', 'droppoint' => '4',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:16:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '3','name' => 'Route 2', 'droppoint' => '5',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:18:50','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '4','name' => 'Route 4', 'droppoint' => '3',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:21:10','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '5','name' => 'Route 5', 'droppoint' => '2',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:21:47','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '6','name' => 'Ref-Route AE', 'droppoint' => '3',  'createdby' => 'Sachin Jangam', 'createdon' => '2017-05-09 13:21:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '7','name' => 'Ref-Route AF', 'droppoint' => '2',  'createdby' => 'Sachin Jangam', 'createdon' => '2017-05-09 14:31:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '8','name' => 'Route BD', 'droppoint' => '4',  'createdby' => 'Sachin Jangam', 'createdon' => '2017-05-09 14:32:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '9','name' => 'Route BE', 'droppoint' => '6',  'createdby' => 'Sachin Jangam', 'createdon' => '2017-05-09 14:35:00','edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>')
    );

    echo json_encode($transporters); ?>;
    var tableId = 'vehcietypemaster';
    var tableCols = [
        {"mData": "srno"}
        ,{"mData": "name"}
        , {"mData": "droppoint"}
        , {"mData": "createdby"}
        , {"mData": "createdon"}
        , {"mData": "edit"}
        , {"mData": "delete"}

    ];

    var data1 = [{'value': 'Mumbai', 'eid': '1'}, {'value': 'Pune', 'eid': '2'}];

    jQuery("#ccmail").autocomplete({
        source: data1,
        select: function (event, ui) {
            insertCCMailDiv(ui.item.value, ui.item.eid);
            /*clear selected value */
            jQuery(this).val("");
            return false;
        }
    });

    function insertCCMailDiv(selected_name, emailid) {
        if (emailid != "" && jQuery('#em_vehicle_div_' + emailid).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeCCEmailDiv(emailid);
            };
            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + emailid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + emailid + '" value="' + emailid + '"/>';
            jQuery("#listccmailid").append(div);
            jQuery(div).append(remove_image);
        }
    }

    function removeCCEmailDiv(eid) {
        jQuery('#em_vehicle_div_' + eid).remove();
    }

</script>
