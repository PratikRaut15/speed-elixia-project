<form enctype="multipart/form-data" method="POST" id="adddealer" class="form-horizontal well "  style="width:70%;" >
    <?php include 'panels/adddealer.php';?>
    <fieldset>
       <div class="control-group">
           <div class="input-prepend ">
               <span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="name" placeholder="Name" autofocus>
           </div>
           <div class="input-prepend ">
               <span class="add-on">Phone No <span class="mandatory">*</span></span><input type="text" name="phoneno" id="phoneno" placeholder="Phone No">
           </div>
           <div class="input-prepend ">
               <span class="add-on">Cell Phone <span class="mandatory">*</span></span><input type="text" name="cellphone" id="cellphone" placeholder="Cell Phone No">
           </div>
       </div>
   </fieldset>
   <fieldset>
       <div class="control-group">
           <div class="input-prepend ">
               <span class="add-on">Type </span>
               <input type="checkbox" name="battery" value="Battery">Battery 
               <input type="checkbox" name="tyre" value="Tyre">Tyre 
               <input type="checkbox" name="service" value="Service">Service 
               <input type="checkbox" name="repair" value="Repair">Repair 
               <input type="checkbox" name="vehicle" value="Vehicle">Vehicle 
               <input type="checkbox" name="accessory" value="Accessory">Accessory                 
               <input type="checkbox" name="fuel" value="Fuel">Fuel                                         
           </div>
       </div>
   </fieldset>
   <fieldset>
       <div class="control-group">
           <div class="input-prepend ">
            <span class="add-on">Code</span> <input type="text" name="code" id="code" placeholder="Code">
        </div>
    </div>
</fieldset>
<fieldset>
   <div class="control-group">
       <div class="input-prepend ">
        <span class="add-on">Notes <span class="mandatory">*</span></span>   <textarea type="text" name="notes" id="notes" placeholder="Notes"></textarea>
    </div>
    <div class="input-prepend ">
        <span class="add-on">Address <span class="mandatory">*</span></span> 
        <textarea type="text" name="address" id="address" placeholder="Address"></textarea>
    </div>
</div>
</fieldset>
<fieldlist>
    <div class="control-group">
        <div class="input-prepend">
           <span>Upload 1 </span> <span class="add-on">Name</span><input type="text" name="other1" id="other1"> <input type="file" name="file1" id="file1">
       </div>
   </div>
</fieldlist>
<fieldlist>
    <div class="control-group">
        <div class="input-prepend">
           <span>Upload 2 </span> <span class="add-on">Name</span><input type="text" name="other2" id="other2"> <input type="file" name="file2" id="file2">
       </div>
   </div>
</fieldlist>
<fieldset>
  <div class="control-group pull-right">
      <input type="button" value="Add Dealer" id="adddealerbtn" class="btn btn-primary" onclick="adddealer();">
  </div>    
</fieldset>
</form>