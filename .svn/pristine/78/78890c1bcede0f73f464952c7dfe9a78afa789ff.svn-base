<?php
$customers = getcustomers();
?>

<form enctype="multipart/form-data" method="POST" id="addvendor" class="form-horizontal well "  style="width:70%;" >

    <?php include 'panels/addvendor.php'; ?>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Name <span class="mandatory">*</span></span> 
                <input type="text" name="vendorname" id="vendorname" placeholder="Name" autofocus>

            </div>
            <div class="input-prepend ">
                <span class="add-on">Company <span class="mandatory">*</span></span> 
                <input type="text" name="vendorcompany" id="vendorcompany" placeholder="comapnyname" >

            </div>
            
        </div>
        
        
               
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Phone No <span class="mandatory">*</span></span>
            <input type="text" name="phoneno" id="phoneno" placeholder="Phone No">

        </div>
            <div class="input-prepend ">
            <span class="add-on">Email <span class="mandatory">*</span></span>
            <input type="text" name="email" id="email" placeholder="Email">

        </div>
        </div>
        
        <div class="control-group">
             <div class="input-prepend ">
            <span class="add-on">Address</span>
            <textarea type="text" name="address" id="address" placeholder="Address"></textarea>

        </div>
            <div class="input-prepend ">
            <span class="add-on">Pincode <span class="mandatory">*</span></span>
            <input type="text" name="pincode" id="pincode" placeholder="Pincode">

        </div>
        </div>
        
        
        
    </fieldset>
    
    <fieldset>
            <?php
            if(isset($customers) && !empty($customers))
            {
                $i=1;
                foreach($customers as $customer){
                   ?>
                   <div class="input-prepend ">
                    <span class="add-on"><?php echo $customer->customername;?> Vendor No <span class="mandatory">*</span></span>
                    <input type="text" class="ven" name="vendor_no_<?php echo $customer->customerid;?>" id="vendor_no_<?php echo $customer->customerid;?>" placeholder="Vendor No">
                    <input type="hidden" class="cus" name="customer_no_<?php echo $i;?>" id="customer_no_<?php echo $i;?>" value="<?php echo $customer->customerid?>"/>
                    </div></br></br>
                   <?php
                   $i++;
                }
            }
            ?>
    </fieldset>
    
    

    <fieldset>
        <div class="control-group">
            <input type="button" value="Add Vendor" id="adddealerbtn" class="btn btn-primary" onclick="addvendor();">
        </div>    
    </fieldset>
</form>