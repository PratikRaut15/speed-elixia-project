<?php
    include_once 'travelSettings_functions.php';
    if (isset($_GET['delnid']) && $_GET['delnid']) {
    delNomens($_GET['delnid']);
}
    $travelSettingsArray = getTravelSettingList();

?>
<ul id="tabnav">
    <li><a href="travelSettings.php?id=1">Add Travel Settings</a></li> 
    <li><a class="selected" href="travelSettings.php?id=2">View Travel Settings</a></li> 
</ul>
<table class="table table-bordered table-striped dTableR dataTable" style=" width:70%;margin-top: 20px;">
    <thead>
        <tr>
            <th>Sr.No.</th>
            <th>Night Drive Start Time </th>
            <th>Night Drive End Time </th>
            <th>Threshold Distance</th>
            <th>Actions</th>
        </tr>
    </thead>
    <?php 
    if(isset($travelSettingsArray) && !empty($travelSettingsArray)){
        foreach($travelSettingsArray as $travelSettingKey=>$travelSettingVal){ ?>  
            <tr>
                <td><?php echo ($travelSettingKey+1);?></td>
                <td><?php echo $travelSettingVal['start_time'];?></td>
                <td><?php echo $travelSettingVal['end_time'];?></td>
                <td><?php echo $travelSettingVal['threshold_distance'];?></td>
                <td>
                    <a href="travelSettings.php?id=3&travelsettingid=<?php echo $travelSettingVal['nightDriveDetId'];?>&starttime=<?php echo $travelSettingVal['start_time']?>&endtime=<?php echo $travelSettingVal['end_time'];?>&threshold=<?php echo $travelSettingVal['threshold_distance'];?>">
                        <i class="icon-pencil"></i>
                    </a>
                </td>
            </tr>
        <?php }
    }?>
</table>