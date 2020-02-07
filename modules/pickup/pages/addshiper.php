<form enctype="multipart/form-data" method="POST" id="addshiper" class="form-horizontal well "  style="width:70%;" >

    <?php include 'panels/addshiper.php'; ?>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Shipper Name <span class="mandatory">*</span></span> <input type="text" name="shipername" id="shipername" placeholder="Name" autofocus>

            </div>
        </div>
               
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Phone No <span class="mandatory">*</span></span>   <input type="text" name="phoneno" id="phoneno" placeholder="Phone No">

        </div>
        </div>
        
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Email <span class="mandatory">*</span></span>   <input type="text" name="email" id="email" placeholder="Email">

        </div>
        </div>
        
        
                
    </fieldset>
    

    <fieldset>
        <div class="control-group">
            <input type="button" value="Add Shiper" id="adddealerbtn" class="btn btn-primary" onclick="addshiper();">
        </div>    
    </fieldset>
</form>