<?php 
$dealer = getdealer($_REQUEST['dealerid']);
$dealerid = $_REQUEST['dealerid'];
?>
<form method="POST" id="editdealer" class="form-horizontal well "  style="width:70%;">
    <?php include 'panels/editdealer.php';?>

    <fieldset>
     <div class="control-group">
         <div class="input-prepend ">
             <span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $dealer->name; ?>">

         </div>
         <div class="input-prepend ">
             <span class="add-on">Phone No <span class="mandatory">*</span></span>   <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" value="<?php echo $dealer->phoneno; ?>">

         </div>
         <div class="input-prepend ">
             <span class="add-on">Cell Phone <span class="mandatory">*</span></span>   <input type="text" name="cellphone" id="cellphone" placeholder="Cell Phone No" value="<?php echo $dealer->cellphone; ?>">

         </div>
     </div>
 </fieldset>
    
<fieldset>
 <div class="control-group">
     <div class="input-prepend ">
         <span class="add-on">Type </span>
         <?php 
         $vendor = decbin($dealer->vendor);
         $length = strlen($vendor);
         if($length==1){
             $vendor = '000000'.$vendor; 
         }
         else if($length==2){
             $vendor = '00000'.$vendor; 
         }
         else if($length==3){
             $vendor = '0000'.$vendor; 
         }
         else if($length==4){
             $vendor = '000'.$vendor; 
         }
         else if($length==5){
             $vendor = '00'.$vendor; 
         }                        
         else if($length==6){
             $vendor = '0'.$vendor; 
         }                                                
         ?>
         <input type="checkbox" <?php if($vendor[0]==1) echo 'checked'; ?> name="battery" value="Battery">Battery 
         <input type="checkbox" <?php if($vendor[1]==1) echo 'checked'; ?> name="tyre" value="Tyre">Tyre 
         <input type="checkbox" <?php if($vendor[2]==1) echo 'checked'; ?> name="service" value="Service">Service 
         <input type="checkbox" <?php if($vendor[3]==1) echo 'checked'; ?> name="repair" value="Repair">Repair  
         <input type="checkbox" <?php if($vendor[4]==1) echo 'checked'; ?> name="vehicle" value="Vehicle">Vehicle 
         <input type="checkbox" <?php if($vendor[5]==1) echo 'checked'; ?> name="accessory" value="Accessory">Accessory                 
         <input type="checkbox" <?php if($vendor[6]==1) echo 'checked'; ?> name="fuel" value="Fuel">Fuel                                         
     </div>
 </div>
</fieldset>
<fieldset>
 <div class="control-group">
     <div class="input-prepend ">

        <span class="add-on">Code</span> <input type="text" name="code" id="code" value="<?php echo $dealer->code; ?>" placeholder="Code">
    </select>
</div>
</div>
</fieldset>
<fieldset>
 <div class="control-group">
     <div class="input-prepend ">
         <span class="add-on">Notes <span class="mandatory">*</span></span>   <textarea type="text" name="notes" id="notes" placeholder="Notes"><?php echo $dealer->notes; ?></textarea>

     </div>
     <div class="input-prepend ">
         <span class="add-on">Address <span class="mandatory">*</span></span> <textarea type="text" name="address" id="address" placeholder="Address"><?php echo $dealer->address; ?></textarea>

     </div>
 </div>
</fieldset>
<?php
if(isset($dealer->other1))
{
    ?>
    <fieldlist>
        <div class="control-group">
            <div class="input-prepend">
             <span>Upload 1 </span> <span class="add-on">Name</span><input type="text" name="other1" id="other1" value="<?php echo $dealer->other1; ?>"> 
             <input type="file" name="file1" id="file1">
             <?php
             if(file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".pdf")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".png")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".x-png")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".jpg")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".jpeg")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".pjpeg")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".gif")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other1.".txt")
                )
             {
                ?>
                <a href="download.php?did=<?php echo $dealerid;?>&download_file=<?php echo($dealer->other1); ?>.pdf&customerno=<?php echo $_SESSION['customerno']; ?>">Download</a>
                <?php
            }
            ?>
        </div>
    </div>
</fieldlist>
<?php
}
?>
<?php
if(isset($dealer->other2))
{
    ?>
    <fieldlist>
        <div class="control-group">
            <div class="input-prepend">
             <span>Upload 2 </span> <span class="add-on">Name</span><input type="text" name="other2" id="other2" value="<?php echo $dealer->other2; ?>"> 
             <input type="file" name="file2" id="file2">
             <?php
             if(file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".pdf")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".png")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".x-png")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".jpg")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".jpeg")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".pjpeg")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".gif")
                || file_exists("../../customer/".$_SESSION["customerno"].'/dealer/'.$dealerid.'/'.$dealer->other2.".txt")
                )
             {
                ?>
                <a href="download.php?did=<?php echo $dealerid;?>&download_file=<?php echo($dealer->other2); ?>.pdf&customerno=<?php echo $_SESSION['customerno']; ?>">Download</a>
                <?php
            }
            ?>
        </div>
    </div>
</fieldlist>
<?php
}
?>
<fieldset>
  <div class="control-group pull-right">
      <input type="hidden" value="<?php echo $dealer->dealerid;?>" name="dealerid" id="dealerid">
      <input type="button" value="Edit Dealer" id="editdealerbtn" class="btn btn-primary" onclick="editdealer();">
  </div>    
</fieldset>
</form>