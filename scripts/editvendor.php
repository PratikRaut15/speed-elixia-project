<?php
$vendorid = $_REQUEST['uid'];
$vendor = getvendor($vendorid);
$customers = getcustomers();
?>

<form enctype="multipart/form-data" method="POST" id="editvendor" class="form-horizontal well "  style="width:70%;" >

    <?php include 'panels/addvendor.php'; ?>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Name <span class="mandatory">*</span></span> 
                <input type="text" name="vendorname" id="vendorname" placeholder="Name" value="<?php echo $vendor->vendorname;?>" autofocus>
                <input type="hidden" name="vendorid" id="vendorid" value="<?php echo $vendor->vendorid;?>">

            </div>
            <div class="input-prepend ">
                <span class="add-on">Vendor Company <span class="mandatory">*</span></span> 
                <input type="text" name="vendorcompany" id="vendorcompany" placeholder="comapnyname" value="<?php echo $vendor->company;?>" >

            </div>
        </div>
              
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Phone No <span class="mandatory">*</span></span>
            <input type="text" name="phoneno" id="phoneno"  value="<?php echo $vendor->phone;?>" placeholder="Phone No">

        </div>
            <div class="input-prepend ">
            <span class="add-on">Email <span class="mandatory">*</span></span>
            <input type="text" name="email" id="email"  value="<?php echo $vendor->email;?>" placeholder="Email">

        </div>
        </div>
        
        <div class="control-group">
             <div class="input-prepend ">
            <span class="add-on">Address</span>
            <textarea type="text" name="address" id="address" placeholder="Address"><?php echo $vendor->address;?></textarea>

        </div>
            <div class="input-prepend ">
            <span class="add-on">Pincode <span class="mandatory">*</span></span>
            <input type="text" name="pincode" id="pincode" value="<?php echo $vendor->pincode;?>" placeholder="Pincode">

        </div>
        </div>
        
        
        
    </fieldset>
    <fieldset>
            <?php
            if(isset($customers) && !empty($customers))
            {
                $i=1;
                foreach($customers as $customer){
                    $v_no = getvendor_no($customer->customerid, $vendorid);
                    
                   ?>
                   <div class="input-prepend ">
                    <span class="add-on"><?php echo $customer->customername;?> Vendor No <span class="mandatory">*</span></span>
                    <input type="text" class="ven" name="vendor_no_<?php echo $customer->customerid;?>" id="vendor_no_<?php echo $customer->customerid;?>" value="<?php echo $v_no; ?>" placeholder="Vendor No">
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
            <input type="button" value="Modify Vendor" id="adddealerbtn" class="btn btn-primary" onclick="editvendor();">
        </div>    
    </fieldset>
</form>