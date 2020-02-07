<?php
$submenu_arr = array('city', 'location', 'service', 'client', $rename,'package', 'call','discount','feedback','category');
?>
<ul id="tabnav">
<?php
foreach($submenu_arr as $hdr){
    if(preg_match("/$hdr/", $pg)){
        $chdr = ucfirst($hdr);
        ?>
        <li><a class='<?php if($pg=="add-$hdr"){echo "selected";} ?>' href='mobility.php?pg=add-<?php echo $hdr; ?>'>Add <?php echo $chdr;?></a></li>
        <li><a class='<?php if($pg=="view-$hdr"){echo "selected";} ?>' href='mobility.php?pg=view-<?php echo $hdr; ?>'>View <?php echo $chdr;?></a></li>
        <?php
        if($pg=="edit-$hdr"){
            echo "<li><a class='selected' href='mobility.php?pg=edit-$hdr'>Edit $chdr</a></li>";
        }
    }
}
?>
</ul>


