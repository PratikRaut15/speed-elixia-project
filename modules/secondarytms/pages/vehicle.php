<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
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
        <span class="add-on" style="text-align: left; width: 90px; ">Vehicle No</span>
        <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="20"/>

         <span class="add-on" style="text-align: left; width: 90px; ">Vehicle Type</span>
        <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="20"/>
        <br><br>

        <span class="add-on" style="text-align: left;width: 110px;">Group</span>
         <select>
            <option>Select Group</option>
            <option>Mumbai</option>
            <option>Delhi</option>
            <option>Group1</option>
            <option>Group2</option>
        </select>

        <span class="add-on" style="text-align: left; width: 90px; ">Average</span>
        <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="3" size="5"/><span class="add-on" style="text-align: left; width: 40px; ">Km/ltr</span>
        <br><br>

         <input style='display:inline;' type='submit' class="btn  btn-secondary" value='  Add New Vehicle  '/>
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

                <th >Sr.No </th>
                <th >Vehicle No </th>
                <th >Vehicle Type </th>
                <th >Group</th>
                <th >Average (Km/Ltr)</th>
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
  array('srno'=>'1','vehicleno' => 'MH 01 AS 1234','type' => 'C8-Open 22 FT MA','group' => 'Group 1','average' => '10','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 12:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'2','vehicleno' => 'MH 03 D 8451','type' => 'O1-Open truck - 18 feet','group' => 'Group 1','average' => '12','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 12:38:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'3','vehicleno' => 'MH 08 K 9551','type' => 'O1-Open truck - 18 feet','group' => 'Delhi','average' => '15','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 12:39:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'4','vehicleno' => 'MH 03 AT 3615','type' => 'C8-Open 22 FT MA','group' => 'Mumbai','average' => '09','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 12:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'5','vehicleno' => 'MH 02 GD 7425','type' => 'O8-Open truck - 32 feet','group' => 'Group 2','average' => '18','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 12:40:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'6','vehicleno' => 'MH 03 AT 1456','type' => 'C8-Open 22 FT MA','group' => 'Mumbai','average' => '09','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 12:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'7','vehicleno' => 'MH 03 AP 7845','type' => 'R3-Ref truck - 22 feet','group' => 'Mumbai','average' => '07','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 12:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'8','vehicleno' => 'MH 03 CR 1596','type' => 'R3-Ref truck - 22 feet','group' => 'Mumbai','average' => '10','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 12:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'9','vehicleno' => 'MH 03 HP 0965','type' => 'C8-Open 22 FT MA','group' => 'Mumbai','average' => '09','createdby' => 'Dinesh Joil','createdon' => '2017-05-09 13:07:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'10','vehicleno' => 'MH 03 AE 1045','type' => 'X1-Ref 24 FT MA','group' => 'Mumbai','average' => '11','createdby' => 'Dinesh Joil','createdon' => '2017-05-09 13:13:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'11','vehicleno' => 'MH 03 AE 6502','type' => 'X1-Ref 24 FT MA','group' => 'Mumbai','average' => '06','createdby' => 'Dinesh Joil','createdon' => '2017-05-09 13:18:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'12','vehicleno' => 'MH 03 BD 6584','type' => 'X1-Ref 24 FT MA','group' => 'Mumbai','average' => '07','createdby' => 'Dinesh Joil','createdon' => '2017-05-09 13:28:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'13','vehicleno' => 'MH 03 BA 9840','type' => 'C8-Open 22 FT MA','group' => 'Mumbai','average' => '09','createdby' => 'Dinesh Joil','createdon' => '2017-05-09 13:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'14','vehicleno' => 'MH 03 AD 6528','type' => 'C8-Open 22 FT MA','group' => 'Mumbai','average' => '10','createdby' => 'Dinesh Joil','createdon' => '2017-05-10 14:04:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'15','vehicleno' => 'MH 03 AF 7506','type' => 'X1-Ref 24 FT MA','group' => 'Mumbai','average' => '09','createdby' => 'Dinesh Joil','createdon' => '2017-05-10 14:06:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'16','vehicleno' => 'MH 03 AR 6024','type' => 'C8-Open 22 FT MA','group' => 'Mumbai','average' => '08','createdby' => 'Dinesh Joil','createdon' => '2017-05-10 14:08:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'17','vehicleno' => 'MH 03 AT 9460','type' => 'R3-Ref truck - 22 feet','group' => 'Mumbai','average' => '07','createdby' => 'Dinesh Joil','createdon' => '2017-05-10 14:08:58','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
        );
    echo json_encode($transporters); ?>;
    var tableId = 'vehcietypemaster';
    var tableCols = [

        {"mData": "srno"}
        , {"mData": "vehicleno"}
        , {"mData": "type"}
        , {"mData": "group"}
        , {"mData": "average"}
        , {"mData": "createdby"}
        , {"mData": "createdon"}
        , {"mData": "edit"}
        , {"mData": "delete"}
    ];
</script>
