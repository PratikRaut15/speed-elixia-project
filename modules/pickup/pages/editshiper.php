<?php
$shiperid = $_REQUEST['uid'];
$shiper = getshiper($shiperid);

?>

<form enctype="multipart/form-data" method="POST" id="editshiper" class="form-horizontal well "  style="width:70%;" >

    <?php include 'panels/editshiper.php'; ?>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Shipper Name <span class="mandatory">*</span></span>
                <input type="text" name="shipername" id="shipername" placeholder="Name" value="<?php echo $shiper->sname;?>" autofocus>
                <input type="hidden" name="shiperid" id="shiperid" value="<?php echo $shiper->sid;?>">

            </div>
        </div>
               
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Phone No <span class="mandatory">*</span></span> 
            <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" value="<?php echo $shiper->phone;?>">

        </div>
        </div>
        
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Email <span class="mandatory">*</span></span> 
            <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $shiper->email;?>">

        </div>
        </div>
        
        
                
    </fieldset>
    

    <fieldset>
        <div class="control-group">
            <input type="button" value="Modify Shiper" id="adddealerbtn" class="btn btn-primary" onclick="editshiper();">
        </div>    
    </fieldset>
</form>