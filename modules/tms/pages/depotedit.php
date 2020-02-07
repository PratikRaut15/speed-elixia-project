<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #ajaxstatus{text-align:center;font-weight:bold;display:none}
    .mandatory{color:red;font-weight:bold;}
    #addorders table{width:50%;}
    #addorders .frmlblTd{text-align:center}    
    .ajax_response_3{

    }
    .ajax_response_4{

    }
</style>
<br/>
<div class='container' >
    <center>    
        <form id="addorders" method="POST" action="action.php?action=edit-depot">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Depot</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'>Depot Code<span class="mandatory">*</span></td>
                        <td><input type="text" name="depot_code" value="<?php echo $depots[0]['depotcode'] ?>" required maxlength="20"></td></tr>
                    <tr><td class='frmlblTd'>Depot Name<span class="mandatory">*</span></td>
                        <td><input type="text" name="depot_name" value="<?php echo $depots[0]['depotname'] ?>" required maxlength="50"></td></tr>
                    <tr><td class='frmlblTd'>Zone<span class="mandatory">*</span></td>
                        <td><input type="text" name="zonename" id="zonename"  value="<?php echo $depots[0]['zonename'] ?>" required maxlength="10" autocomplete="off"></td>
                <div id="display" class="listzone"></div>
                </tr>
                <input type="hidden" name="zoneid" id="zoneid" value="<?php echo $depots[0]['zoneid'] ?>" maxlength="50"/>
                <input type='hidden' id='locationid' name='depotid' value="<?php echo $depots[0]['depotid'] ?>" >
                <tr>
                    <td class='frmlblTd'>MultiDrop</td>
                    <td>
                        <?php if (isset($mappeddepots) && !empty($mappeddepots)) { ?>
                            <input type="radio" name="multidrop" id="multidrop" value="1" onclick="getmultidrop();" checked=""/> Yes
                            <input type="radio" name="multidrop" id="multidrop" value="0" onclick="getmultidrop();" /> NO
                        <?php } else {
                            ?>
                            <input type="radio" name="multidrop" id="multidrop" value="1" onclick="getmultidrop();"/> Yes
                            <input type="radio" name="multidrop" id="multidrop" value="0" onclick="getmultidrop();" checked=""/> No
                        <?php }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php if (isset($mappeddepots) && !empty($mappeddepots)) { ?>
                            <input type="text" style="width: 170px;" name="multidepot_name" id="multidepot_name" value="" maxlength="50" autocomplete="off" placeholder="Enter Depot"/>
                        <?php } else { ?>
                            <input type="text" style="width: 170px; display: none;" name="multidepot_name" id="multidepot_name" value="" maxlength="50" autocomplete="off" placeholder="Enter Depot"/>
                        <?php } ?>
                        <div id="chkdisplay" class="checkpointlist"></div></td>
                </tr>
                <tr>
                    <td colspan="100%">
                        <div id="multidepot_list">
                            <?php
                            if (isset($mappeddepots)) {
                                foreach ($mappeddepots as $depot) {
                                    ?>
                                    <input type="hidden" class="mappedgroups" id="hid_g<?php $depot[depotid]; ?>" rel="<?php echo($depot[depotid]); ?>" value="<?php echo $depot[depotname]; ?>"/>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <?php if (isset($mappeddepots) && !empty($mappeddepots)) { ?>
                <tr>
                    <td class='frmlblTd'><span id="factoryspan">Factory</span></td>
                    <td><input type="text" name="factory_name" id="factory_name" value="<?php echo $mappeddepots[0]['factoryname'] ?>" required maxlength="50" autocomplete="off" placeholder="Enter Factory"></td>
                    <input type="hidden" name="factoryid" id="factoryid" value="<?php echo $mappeddepots[0]['factoryid'] ?>"/>
                </tr>
                <?php } else { ?>
                <td class='frmlblTd'><span id="factoryspan" style="display: none;">Factory</span></td>
                <td><input type="text" style="display: none;" name="factory_name" id="factory_name" value="" required maxlength="50" autocomplete="off" placeholder="Enter Factory"></td>
                    <input type="hidden" name="factoryid" id="factoryid" value="<"/>
                <?php } ?>
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>