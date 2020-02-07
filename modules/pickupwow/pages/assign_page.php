<style>
.draggable {cursor: move;}
.activeTd{background-color: #3E78FD;}
.mapT{border:2px #000 solid;}
.clickimage{float:right;margin-top: -10px;margin-right: -10px;}
.clickimage:hover{cursor: pointer;}
.table th, .table td{padding: 5px;}
/*new style*/
#mainScroll { width: 75%; overflow-x:scroll;}
.vehDiv{width:11%;float:left;overflow-y:scroll;max-height:385px;}
.slotH{width:50px;text-align:center;}
#droptableid .displayCell{text-align:center;font-weight:bold;}
</style>

<?php
$def_date = date('Y-m-d');
$allVehicles = get_vehicles_arr();
$preset_slots = get_Mapped_Zone_Slot_pickup();
$allFence = get_zones();
$allSlots = get_slots();
$fenceids = array();
?>
<br/>
<div class="container-fluid">
    
    <h3>Zone-Slot-Pickup Boy Mapping</h3><br/>
    
    <div id='ajxStatus'></div><br/>
    
    <div class='vehDiv' >
    <table class='table mapT' id='vehTable' >
        <thead><tr><th>Pickup Boy.</th></tr></thead>
        <tbody>
        <?php
        
        foreach($allVehicles as $s_vehid=>$singleVeh){
            echo '<tr><td><span class="draggable" id="vi'.$s_vehid.'">'.$singleVeh['username'].'</span></td></tr>';
        }
        ?>
        </tbody>    
    </table>
    </div>
    
    <!--<div style="width:75%; float:right;overflow-x:scroll" >-->
    
    <div id='mainScroll'>  
    <form id="mappingTable" onsubmit='map_all();return false;' >
    <table class="table mapT" id='droptableid' style='table-layout:fixed;' >
        <thead>
        <tr>
            <th class='slotH' style='width:100px;' >Slot</th>
            <?php
            foreach($allFence as $fid=>$fname){
                $fenceids[] = $fid;
                echo "<th id='z$fid' style='width:100px;'>{$fname['zname']}</th>";
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $count_Fence = count($allFence);
        foreach($allSlots as $sid=>$sText){
            echo "<tr>";
            //echo "<td id='slot$sid' >{$sText['timing']}</td>";
            echo "<td id='slot$sid' class='displayCell' >$sid (".$sText['timing'].")</td>";
            $loop = $count_Fence;
            while($loop){
                echo "<td class='droppable' ></td>";
                $loop--;
            }
            echo "</tr>";
        }
        ?>
        <tr>
            <td colspan='100%' style='text-align:left;' id='submitTd'>
                <input type='submit' name='mapall' value='Submit' class='btn-primary' style='margin-left:150px'/>
            </td>
        </tr>
        </tbody>
        
    </table>
    </form>
    </div>
    
</div>

<script>
var vehArr = new Object();
var allZones = <?php echo json_encode($fenceids); ?>;
var allAllSlot = <?php echo json_encode(array_keys($allSlots)); ?>;
var allVehicles = <?php echo json_encode($allVehicles); ?>;
var fillTable = false;
<?php if($preset_slots){ ?>
    vehArr = <?php echo json_encode($preset_slots); ?>;
    fillTable = true;
<?php } ?>
var defDate = '<?php echo $def_date; ?>';
</script>