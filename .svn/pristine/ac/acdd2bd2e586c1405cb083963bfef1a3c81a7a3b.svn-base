<?php
$customerid = $_REQUEST['uid'];
$customer = getcustomer($customerid);

?>

<form enctype="multipart/form-data" method="POST" id="editcustomer" class="form-horizontal well "  style="width:70%;" >

    <?php include 'panels/editcustomer.php'; ?>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Name <span class="mandatory">*</span></span>
                <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $customer->customername;?>" autofocus>
                <input type="hidden" name="customerid" id="customerid" value="<?php echo $customer->customerid;?>">

            </div>
        </div>
               
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Phone No <span class="mandatory">*</span></span> 
            <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" value="<?php echo $customer->phone;?>">

        </div>
        </div>
        
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Email <span class="mandatory">*</span></span> 
            <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $customer->email;?>">

        </div>
        </div>
        
        <div class="control-group">
            <div class="input-prepend ">
            <span class="add-on">Address</span> 
            <textarea type="text" name="address" id="address" placeholder="Address"><?php echo $customer->address;?>
            </textarea>

        </div>
        </div>
                
    </fieldset>
    

    <fieldset>
        <div class="control-group">
            <input type="button" value="MOdify Customer" id="adddealerbtn" class="btn btn-primary" onclick="editcustomer();">
        </div>    
    </fieldset>
</form>