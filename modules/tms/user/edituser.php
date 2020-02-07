<?php
if (isset($_GET['uid'])) {
    $get_uid = (int) $_GET['uid'];
} else {
    $get_uid = $_SESSION['userid'];
}
$user = getuser($get_uid);
if($user->role == 'transporter' || $user->role == 'factoryofficial' || $user->role == 'depotofficial'){
    $tmsuser = gettmsuser($user->id, $user->role, $user->customerno);
    
}
$objTransporter = new Transporter();
$objTransporter->customerno = $_SESSION["customerno"];
$transporterarray = get_transporter($objTransporter);
$objDepot = new Depot();
$objDepot->customerno = $_SESSION["customerno"];
$objDepot->locationid = '';
$objDepot->zoneid = '';
$depotsarray = get_depots($objDepot);
$objFactoty = new Factory();
$objFactoty->customerno = $_SESSION["customerno"];
$factoryarray = get_factory($objFactoty);

$trans = '';
foreach($transporterarray as $transporter){
   
    
    if($tmsuser->tmsrole == 'transporter' && $tmsuser->tmsid == $transporter['transporterid']){
        $trans .= "<option value='".$transporter['transporterid']."' selected=''>".$transporter['transportername']."</option>";
    }else{
    
    $trans .= "<option value='".$transporter['transporterid']."'>".$transporter['transportername']."</option>";
    }
}

$depots ='';

foreach($depotsarray as $depot){
    if($tmsuser->tmsrole == 'depotofficial' && $tmsuser->tmsid == $depot['depotid']){
        $depots .= "<option value='".$depot['depotid']."' selected>".$depot['depotname']."</option>";
    }else{
     $depots .= "<option value='".$depot['depotid']."'>".$depot['depotname']."</option>";
    }
}
$facts = '';
foreach($factoryarray as $factory){
    if($tmsuser->tmsrole == 'factoryofficial' && $tmsuser->tmsid == $factory['factoryid']){
        $facts .= "<option value='".$factory['factoryid']."' selected=''>".$factory['factoryname']."</option>";
    }else{
     $facts .= "<option value='".$factory['factoryid']."'>".$factory['factoryname']."</option>";
    }
}
?>
<center>
    <div style="float:right; margin-right: 150px; ">
            <a href="tms.php?pg=view-users"> <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;">View Users </button></a>
        </div>
    <br/>
    <br/>
    <form method="POST" id="edituser" class="form-horizontal well "  style="width:80%;">
        <?php include 'panels/edituser.php'; ?>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Name <span class="mandatory">*</span></span><input type="text" name="name" id="nameid" placeholder="Name" value="<?php echo $user->realname; ?>" autofocus>
                    <input type="hidden" name="userid" id="userid" value="<?php echo $user->id; ?>" autofocus>
                    <input type="hidden" id="heirid" name="heirid" value="<?php echo($_SESSION["heirarchy_id"]); ?>">
                </div>
                <div class="input-prepend ">
                    <span class="add-on">User Name <span class="mandatory">*</span></span><input type="text" name="username" id="username" value="<?php echo $user->username; ?>" placeholder="User Name" readonly="">
                    <input type="hidden" name="hiddenusername" id="hiddenusername" value="<?php echo $user->username; ?>" placeholder="User Name" readonly="">

                </div>
            </div>
        </fieldset>

        <fieldset>
            <div class="control-group">

                <div class="input-prepend ">
                    <span class="add-on">Email <span class="mandatory">*</span></span><input type="email" name="email" onKeyUp="copyText()" id="email1" placeholder="Email" value ="<?php echo $user->email; ?>">

                </div>
                <div class="input-prepend ">
                    <span class="add-on">Password <span class="mandatory">*</span></span><input type="password" name="password" id="password" placeholder="Password">

                </div>
            </div>
        </fieldset>

        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Phone No <span class="mandatory">*</span></span><span class="add-on">+91</span><input type="text" name="phoneno" id="phoneno" placeholder="Phone No" value ="<?php echo $user->phone; ?>">

                </div>
                <div class="input-prepend ">
                    <span class="add-on">Role <span class="mandatory">*</span></span>
                    <select id="role" name="role" readonly="">
                        <?php
                        if ($user->role == "Administrator") {
                            ?>
                            <option id="Administrator" value="Administrator" rel="5" selected>Administrator</option>                            
                            
                            <?php
                            if ($switch_to == '9') {
                                if ($_SESSION['use_tms']) {
                                    echo "<option id='tms_transporter' value='transporter' rel='15'>Transporter</option>";
                                }
                            }
                            ?>
                            <?php
                            if ($switch_to == '9') {
                                if ($_SESSION['use_tms']) {
                                    echo "<option id='tms_factoryofficial' value='factoryofficial' rel='16'>factoryofficial</option>";
                                }
                            }
                            ?>
                            <?php
                            if ($switch_to == '9') {
                                if ($_SESSION['use_tms']) {
                                    echo "<option id='tms_depotofficial' value='depotofficial' rel='17'>depotofficial</option>";
                                }
                            }
                            ?>
                            <?php
                        } elseif ($user->role == "transporter") {
                            ?>
                            <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                            <option id="tms_transporter" value="transporter" rel="15" selected="selected" >Transporter</option>
                            <option id="tms_factoryofficial" value="factoryofficial" rel="16" >Factory Official</option>
                            <option id="tms_depotofficial" value="depotofficial" rel="17" >Depot Official</option>
                            <?php
                        } elseif ($user->role == "factoryofficial") {
                            ?>
                            <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                            <option id="tms_transporter" value="transporter" rel="15" >Transporter</option>
                            <option id="tms_factoryofficial" value="factoryofficial" rel="16" selected="selected" >Factory Official</option>
                            <option id="tms_depotofficial" value="depotofficial" rel="17" >Depot Official</option>
                            <?php
                        } elseif ($user->role == "depotofficial") {
                            ?>
                            <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                            <option id="tms_transporter" value="transporter" rel="15"  >Transporter</option>
                            <option id="tms_factoryofficial" value="factoryofficial" rel="16" >Factory Official</option>
                            <option id="tms_depotofficial" value="depotofficial" rel="17" selected="selected" >Depot Official</option>
                            <?php
                        }
                        ?>

                    </select>

                </div>

            </div>
        </fieldset>
         <fieldset>
            <div class="control-group">

                <div class="input-prepend " id="trans_display"  <?php if($user->role != "transporter") { echo 'style="display: none;"';}?>  >
                    <span class="add-on">Transporter </span>
                    <select id="transporterid" name="transporterid">

                        <option value="0">Select Transporter</option>
                        <?php echo $trans ; ?>
                    </select>

                </div>
                
                <div class="input-prepend " id="facts_display" <?php if($user->role != "factoryofficial") { echo 'style="display: none;"';}?> >
                    <span class="add-on">Factory </span>
                    <select id="factoryid" name="factoryid">

                        <option value="0">Select Factory</option>
                        <?php echo $facts ; ?>
                    </select>

                </div>
                
                <div class="input-prepend " id="depots_display" <?php if($user->role != "depotofficial") { echo 'style="display: none;"';}?> >
                    <span class="add-on">Depot </span>
                    <select id="depotid" name="depotid">

                        <option value="0">Select Depot</option>
                        <?php echo $depots ; ?>
                    </select>

                </div>

            </div>
        </fieldset>
        <fieldset>
            <div class="control-group pull-right">
                <input type="hidden" name="session_roleid" id="session_roleid" value="<?php echo $_SESSION['roleid'] ?>" />
                <input type="button" value="Modify User" class="btn btn-primary" onclick="edituser();">
            </div>      
        </fieldset>

    </form>    
</center>
<script type='text/javascript' src='../../scripts/edituser.js'></script>    
<script type='text/javascript' src='../../scripts/exception.js'></script>    
<script type='text/javascript'>
                var user = jQuery("#username").val();
                var userhidden = jQuery("#hiddenusername").val();
                //alert(userhidden);
                jQuery("#useredit").html(user);
                function copyText() {
                    src = jQuery("#email1").val();
                    jQuery("#useredit").html(src);
                    jQuery("#username").val(src);
                    if (jQuery("#email1").val() == '')
                    {
                        jQuery("#useredit").html(userhidden);
                        jQuery("#username").val(userhidden);
                    }


                }
</script>
