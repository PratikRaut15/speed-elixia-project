<?php
include_once "../../lib/bo/ZoneManager.php";
include_once "../../lib/bo/CheckpointManager.php";
$geomanager = new ZoneManager($_SESSION['customerno']);
$groupid = $_SESSION['groupid']; 
$zones = $geomanager->getZones();

$chkmanager = new CheckpointManager($_SESSION['customerno']);
$groupid = $_SESSION['groupid'];
$string='';
$circularzone = $chkmanager->getcheckpointsforcustomercz($groupid,$string);
?>
<style>
    .scrollablediv1{font-size: smaller;
    height: 100px;
    max-width: 230px;
    overflow-y: scroll;
    padding-top: 10px;
    width: 150px;
    }
</style>
<li>
<div>
    <div class="scrollheader">
        <span class="tw_b">Zones</span>
        <div class="scroll_head_container" >
            <label class="all_select scroll_lable tc_blue" data-type="fences" title="Click here to show all">All</label>
            <label class="scroll_lable">|</label>
            <label class="all_clear scroll_lable tc_blue" data-type="fences" title="Click here to clear all" >Clear</label>
        </div>
        <br/>
        <!--<input id="txtFence" class="scroll_lable" style="cursor:auto;" placeholder="Search Zone" type="text" />
        <br />-->
    </div>

    <div class="scrollablediv1" style="height: auto;">
        <?php
        if (isset($zones)) {
            foreach ($zones as $thiszone) {
                ?>
                <div class="searchFences">
                    <input type="checkbox" class="fence_all" id ="fence_<?php echo $thiszone->zoneid; ?>" onclick="zoneplot(<?php echo $thiszone->zoneid; ?>);" value="<?php echo $thiszone->zonename; ?>"/>
                    <?php echo $thiszone->zonename; ?><br/>
                </div>
            <?php }
        } ?>
    </div>

</div>
<br>
<div style="clear: both;"></div>
<div>
    <div class="scrollheader">
                    <span class="tw_b">Circular Zones
                    </span>
                    <div class="scroll_head_container" >
                        <label class="all_select scroll_lable tc_blue" data-type="checkpoints" title="Click here to show all">All</label>
                        <label class="scroll_lable">|</label>
                        <label class="all_clear scroll_lable tc_blue" data-type="checkpoints" title="Click here to clear all" >Clear</label>
                    </div>
                    <br/>
                    <input id="txtCheckpoint" class="scroll_lable" style="cursor:auto;" placeholder="Search Checkpoint" type="text" />
                    <br />
                </div>

    <div class="scrollablediv1" >
        <?php
        if (isset($circularzone)) {
            foreach ($circularzone as $thiszone) {
                ?>
                <div class="searchChkpts">
                            <input type="checkbox" class="chk_all" id ="chk_<?php echo $thiszone->checkpointid; ?>"
                                   onclick="chkplot(<?php echo $thiszone->checkpointid; ?>);"
                                   value="<?php echo $thiszone->cname; ?>"/>
                                   <?php echo $thiszone->cname; ?>
                            <br/>
                </div>
        
    <?php }
} ?>
    </div>

</div>
</li>