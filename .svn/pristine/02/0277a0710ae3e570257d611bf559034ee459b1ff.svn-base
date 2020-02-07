<li>
<?php 
include_once("../../lib/bo/GeofenceManager.php");        
$geomanager = new GeofenceManager($_SESSION['customerno']);
$fences = $geomanager->getfences();
		
		
if(!isset($_SESSION['ecodeid']))
{
    if(isset($fences))
    {
?>
    <ul>
        <div class="scrollheader">
            <span class="tw_b">Fences</span>
            <div class="scroll_head_container" >
                <label class="all_select scroll_lable tc_blue" data-type="fences" title="Click here to show all">All</label>
                <label class="scroll_lable">|</label> 
                <label class="all_clear scroll_lable tc_blue" data-type="fences" title="Click here to clear all" >Clear</label>
            </div>
        </div>
        <div class="scrollablediv">
            <?php foreach($fences as $thisfence){ ?>
                <input type="checkbox" class="fence_all" id ="fence_<?php echo $thisfence->fenceid; ?>" onclick="fenceplot(<?php echo $thisfence->fenceid; ?>);"/> 
                <?php echo $thisfence->fencename; ?><br/>
            <?php } ?>
        </div>
    </ul>
<?php
    }
}
?>
</li>