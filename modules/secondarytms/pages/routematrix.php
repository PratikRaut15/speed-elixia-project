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
                <span class="add-on" style="text-align: left; width: 110px; ">Drop Point 1</span>
                 <select name="droppoint" id="droppoint" style="width: 150px;">
                   <option>Select Drop Point</option>
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
                <span class="add-on" style="text-align: left;width: 110px;">Drop Point 2</span>
                 <select name="droppoint" id="droppoint" style="width: 150px;">
                 <option>Select Drop Point</option>
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

                <br><br>
                <span class="add-on" style="text-align: left; width: 110px; ">Distance</span>
                <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="15" size="10"/><span class="add-on" style="text-align: left; width: 30px; ">Km</span>
                <span class="add-on" style="text-align: left; width: 110px; ">Time</span>
                <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="15" size="10"/><span class="add-on" style="text-align: left; width: 30px; ">min</span>
                <br><br>
                <input style='display:inline;' type='submit' class="btn  btn-secondary" value='  Add Route Matrix  '/>
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
                    <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td colspan="2"></td>



                </tr>
                <tr class='dtblTh'>

                    <th >Sr.No</th>
                    <th >Drop Point 1</th>
                    <th >Drop Point 2</th>
                    <th >Distance(In KM)</th>
                    <th >Time (In Min)</th>
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
        array('srno' => '1','droppoint1' => 'Mumbai', 'droppoint2' => 'Pune','distance'=>'149','time'=>'150',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '2','droppoint1' => 'Mumbai', 'droppoint2' => 'Nashik','distance'=>'165','time'=>'180',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '3','droppoint1' => 'Mumbai', 'droppoint2' => 'Goa','distance'=>'609','time'=>'630',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:18:00', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '4','droppoint1' => 'Mumbai', 'droppoint2' => 'Bhopal','distance'=>'780','time'=>'750',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 14:32:00', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '5','droppoint1' => 'Nashik', 'droppoint2' => 'Pune','distance'=>'210','time'=>'300',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:22:00', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '6','droppoint1' => 'Nashik', 'droppoint2' => 'Goa','distance'=>'670','time'=>'750',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:29:10', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '7','droppoint1' => 'Pune', 'droppoint2' => 'Bhopal','distance'=>'790','time'=>'800',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:35:50', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '8','droppoint1' => 'Mumbai', 'droppoint2' => 'Nagpur','distance'=>'812','time'=>'900',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:37:19', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '9','droppoint1' => 'Mumbai', 'droppoint2' => 'Surat','distance'=>'290','time'=>'300',  'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-09 14:51:00', 'edit' => '<a href="#"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>')
    );

    echo json_encode($transporters); ?>;
    var tableId = 'vehcietypemaster';
    var tableCols = [
        {"mData": "srno"}
        , {"mData": "droppoint1"}
        , {"mData": "droppoint2"}
        , {"mData": "distance"}
        , {"mData": "time"}
        , {"mData": "createdby"}
        , {"mData": "createdon"}

        , {"mData": "edit"}
        , {"mData": "delete"}

    ];

    var data1 = [{'value': 'Mumbai', 'eid': '1'}, {'value': 'Pune', 'eid': '2'}];

    jQuery("#ccmail").autocomplete({
        source: data1,
        select: function (event, ui) {
            jQuery(this).val(ui.item.value);
            return false;
        }
    });

    jQuery("#ccmail1").autocomplete({
        source: data1,
        select: function (event, ui) {
            jQuery(this).val(ui.item.value);
            return false;
        }
    });

</script>
