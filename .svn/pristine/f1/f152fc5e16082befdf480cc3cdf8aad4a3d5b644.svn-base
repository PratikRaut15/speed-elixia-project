<div  style="float:none; padding-left:30%;">
<table id="floatingpanel">
<form id="alerts" method="POST" action="route.php">
    <thead>
    <tr>
        <th colspan="3" id="formheader">Customize Fields</th>
    </tr>
    <tr>
    <td id="saved" style="display: none" colspan="3">Changes Saved</td>
    <td id="error" style="display: none" colspan="3">Error</td>
   </tr>
    <tr>
        <td></td>
        <td>Use Custom Field?</td>
        <td>Custom Field Name</td>
    </tr>
    </thead>
    <tbody>
        <?php
        
        $customlist = getcustomfields();
        foreach($customlist as $customl){
            $custom = getcustombyid_all($customl->id);
            $usecustom = retval_issetor($custom->usecustom);
            $customname = retval_issetor($custom->customname);
        ?>
        <tr>
            <td><?php echo $customl->name; ?><input type="hidden" class="name" id="<?php echo $customl->name; ?>" name="<?php echo $customl->name; ?>"></td>
            <td><input type="checkbox" id="usecustom_<?php echo $customl->id; ?>" name="usecustom_<?php echo $customl->id; ?>" <?php if($usecustom == 1) echo("checked"); ?>></td>
            <td><input type="text" id="customname_<?php echo $customl->id; ?>" name="customname_<?php echo $customl->id; ?>" value="<?php echo $customname;?>"></td>
        </tr>
        <?php
        }
        ?>

        <tr>
            <td colspan="100%">
                <input type="button" name="EModify" id="EModify" class="btn  btn-primary" value="Modify" onclick="dosave_customize();">
            </td>
        </tr>
    </tbody>
</form>
</table>
</div>