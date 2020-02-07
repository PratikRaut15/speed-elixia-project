<?php
$userid = $_REQUEST['uid'];
$user = getpickupboy($userid);
//print_r($user); 
/*
 $pins = getpinno($userid);
 
$rtt = '';
if (isset($pins) && !empty($pins)) {
    $rt = Array();
    foreach ($pins as $pin) {
        $rt[] = $pin->pincode;
    }
    $rtt = implode(',', $rt);
}
 * 
 */
?>

<form method="POST" id="edituser" name="edituser" class="form-horizontal well "  style="width:70%;" >
    <?php include 'panels/addpickup.php'; ?>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Name <span class="mandatory">*</span></span>
                <input type="text" name="pickupname" id="pickupname" placeholder="Name" autofocus value="<?php echo $user->name; ?>">
                <input type="hidden" name="pickupuser" id="pickupuser" value="<?php echo $user->pid; ?>">

            </div>
            <div class="input-prepend ">
                <span class="add-on">Email </span> 
                <input type="text" name="email" id="email" placeholder="Émail ID " value="<?php echo $user->email; ?>" >
            </div>
        </div>
    </fieldset>

    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Username <span class="mandatory">*</span></span>
                <input type="text" name="username" id="username" placeholder="UserName" autofocus value="<?php echo $user->username; ?>">


            </div>
            <div class="input-prepend ">
                <span class="add-on">Password <span class="mandatory">*</span></span> 
                <input type="password" name="password" id="password" placeholder="Password" value="" >
            </div>
        </div>
    </fieldset>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Phone No <span class="mandatory">*</span></span><span class="add-on">+91</span> 
                <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" value="<?php echo $user->phone; ?>" />

            </div>
<!--            <div class="input-prepend ">
                <span class="add-on">Pincode </span>
                <textarea id="pins" name="pins"><?php echo $rtt; ?></textarea>

            </div>-->

        </div>
    </fieldset>
    <!--
    <fieldset>
        <div class="control-group">
            <span class="add-on">Pincode Alloted - <span class="mandatory">*</span></span>


        </div>

    </fieldset>
    -->
    <fieldset>
        <div class="control-group">
            <span class="add-on">Pickupboy photo </span>
            <?php 
            
            if (!empty($user->pickupboyimg)){ 
                $imagerep = str_replace("data:image/jpeg;base64,",'',$user->pickupboyimg);    
                
         $image = base64_decode($imagerep);
          $target_path = "../../customer/" . $_SESSION['customerno']. "/pickupboy/";
         
         file_put_contents($target_path. $userid . ".jpg", $image);
          chmod($target_path, 0777); 
            echo "<img src= '".$subdir."/customer/".$_SESSION['customerno']."/pickupboy/".$userid.".jpg' style='width:100px; height:120px;'/>";
            } else {
                echo"No image";
            }
            
            ?>
        </div>

    </fieldset>

    <fieldset>
        <div class="control-group pull-right">
            <input type="submit" value="Modify User" name='create_user' class="btn btn-primary" onclick="editPickup();
                    return false;" ></div>    
    </fieldset>

</form>