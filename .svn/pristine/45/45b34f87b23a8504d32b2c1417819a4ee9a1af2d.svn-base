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
        <span class="add-on" style="text-align: left; width: 110px; ">Vehicle Type</span>
        <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="15"/>
        <span class="add-on" style="text-align: left;width: 110px;">Capacity In Kg</span>
        <input type="text" name="volume_kg" id="volume_kg" value="" maxlength="10"/>


        <span class="add-on" style="text-align: left;width: 90px;">Vol In M3</span>
        <input type="text" name="volume_m" id="volume_m" value="" maxlength="10"/>
         <input style='display:inline;' type='submit' class="btn  btn-secondary" value='  Add Type  '/>
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

                <td><input type="text" class='search_init' name='plant_location' style="width:80%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td colspan="2"></td>



            </tr>
            <tr class='dtblTh'>

                <th >Sr.No</th>
                <th >Vehicle Type </th>
                <th >Volume In M3 </th>
                <th >Weight In Kg</th>
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
  array('srno'=>'1','vehType' => 'C8-Open 22 FT MA','volume' => '30.530','weight' => '11000','createdby' => 'Dinesh Joil','createdon' => '2017-05-01 10:20:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'2','vehType' => 'C1-Open 24 FT MA','volume' => '35.000','weight' => '10000','createdby' => 'Dinesh Joil','createdon' => '2017-05-01 10:21:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'3','vehType' => 'C2-Open 26 FT MA','volume' => '40.620','weight' => '15000','createdby' => 'Dinesh Joil','createdon' => '2017-05-01 10:26:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'4','vehType' => 'C3-Open 28 FT MA','volume' => '46.720','weight' => '14500','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 14:20:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'5','vehType' => 'C4-Open 30 FT MA','volume' => '50.120','weight' => '14300','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 14:20:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'6','vehType' => 'C5-Open 32 FT MA','volume' => '65.240','weight' => '14100','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 14:20:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'7','vehType' => 'C7-Open 36 FT MA','volume' => '72.380','weight' => '18500','createdby' => 'Sachion Jangam','createdon' => '2017-05-08 10:20:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'8','vehType' => 'O1-Open truck - 18 feet','volume' => '53744','weight' => '6600','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 10:58:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'9','vehType' => 'O2-Open truck - 20 feet','volume' => '31540','weight' => '8000','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 10:20:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'10','vehType' => 'O3-Open truck - 22 feet','volume' => '34720','weight' => '8500','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 11:59:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'11','vehType' => 'O4-Open truck - 24 feet','volume' => '37910','weight' => '8500','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 11:45:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'12','vehType' => 'O8-Open truck - 32 feet','volume' => '54030','weight' => '5800','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 10:46:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'13','vehType' => 'R2-Ref truck - 20 feet','volume' => '25820','weight' => '7725','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 13:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'14','vehType' => 'R3-Ref truck - 22 feet','volume' => '28550','weight' => '8000','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 13:35:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'15','vehType' => 'R4-Ref truck - 24 feet','volume' => '31280','weight' => '8000','createdby' => 'Dinesh Joil','createdon' => '2017-05-08 10:38:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'16','vehType' => 'R8-Ref truck - 32 feet','volume' => '47600','weight' => '7400','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 17:39:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'17','vehType' => 'X1-Ref 24 FT MA','volume' => '32680','weight' => '11800','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 17:38:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>'),
  array('srno'=>'18','vehType' => 'X3-Ref 28 FT MA','volume' => '45130','weight' => '13800','createdby' => 'Sachin Jangam','createdon' => '2017-05-08 17:40:00','edit'=>'<a href="#"><i class="icon-pencil"></i></a>','delete'=>'<a href="#"><i class="icon-trash"></i></a>')
);

    echo json_encode($transporters); ?>;
    var tableId = 'vehcietypemaster';
    var tableCols = [
         {"mData": "srno"}
        , {"mData": "vehType"}
        , {"mData": "volume"}
        , {"mData": "weight"}
        , {"mData": "createdby"}
        , {"mData": "createdon"}
        , {"mData": "edit"}
        , {"mData": "delete"}

    ];
</script>
