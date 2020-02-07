<?php
include_once '../../lib/comman_function/reports_func.php';

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
    $trans .= "<option value='".$transporter['transporterid']."'>".$transporter['transportername']."</option>";
}
$depots ='';
foreach($depotsarray as $depot){
     $depots .= "<option value='".$depot['depotid']."'>".$depot['depotname']."</option>";
}
$facts = '';
foreach($factoryarray as $factory){
     $facts .= "<option value='".$factory['factoryid']."'>".$factory['factoryname']."</option>";
}
?>
<center>
    <div style="float:right; margin-right: 150px; ">
            <a href="tms.php?pg=view-users"> <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;">View Users </button></a>
        </div>
    <br/>
    <br/>
    
    <div id="container">
        
    <form method="POST" id="adduser" class="form-horizontal well "  style="width:50%;">
        <?php include 'panels/adduser.php'; ?>


        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="nameid" placeholder="Name" autofocus>
                    <input type="hidden" id="heirid" name="heirid" value="<?php echo($_SESSION["heirarchy_id"]); ?>">
                </div>
                <div class="input-prepend ">
                    <span class="add-on">Username <span class="mandatory">*</span></span> <input type="text" name="username" id="username" placeholder="Émail ID is your username" readonly="">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Email <span class="mandatory">*</span></span><input type="email" name="email" onKeyUp="copyText()" id="email1" placeholder="Email">

                </div>
                <div class="input-prepend ">
                    <span class="add-on">Password <span class="mandatory">*</span></span>  <input type="password" name="password" id="password" placeholder="Password">

                </div>

            </div>
        </fieldset>

        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Phone No <span class="mandatory">*</span></span><span class="add-on">+91</span> <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" />

                </div>
                <div class="input-prepend ">
                    <span class="add-on">Role </span>
                    <select id="role" name="role" onchange="tmsroles();">

                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <?php
                        if ($switch_to == '9') {
                            if ($_SESSION['use_tms']) {
                                echo "<option id='tms_transporter' value='transporter' rel='15'>transporter</option>";
                                echo "<option id='tms_factoryofficial' value='factoryofficial' rel='16'>factoryofficial</option>";
                                echo "<option id='tms_depotofficial' value='depotofficial' rel='17'>depotofficial</option>";
                            }
                        }
                        ?>
                    </select>

                </div>

            </div>
        </fieldset>

        <fieldset>
            <div class="control-group">

                <div class="input-prepend " id="trans_display" style="display: none;">
                    <span class="add-on">Transporter </span>
                    <select id="transporterid" name="transporterid">

                        <option value="0">Select Transporter</option>
                        <?php echo $trans ; ?>
                    </select>

                </div>
                
                <div class="input-prepend " id="facts_display" style="display: none;">
                    <span class="add-on">Factory </span>
                    <select id="factoryid" name="factoryid">

                        <option value="0">Select Factory</option>
                        <?php echo $facts ; ?>
                    </select>

                </div>
                
                <div class="input-prepend " id="depots_display" style="display: none;">
                    <span class="add-on">Depot </span>
                    <select id="depotid" name="depotid">

                        <option value="0">Select Depot</option>
                        <?php echo $depots ; ?>
                    </select>

                </div>

            </div>
        </fieldset>

        <style>
            /*ak added*/
            .table td {
                text-align: center;   
            }
        </style>
        <fieldset>
            <div class="control-group pull-right"><input type="button" value="Create User" class="btn btn-primary" onclick="addnewuser();"></div>    
        </fieldset>

    </form>
    </div>
</center>    
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script>
                function copyText() {
                    var emailsrc = jQuery("#email1").val();
                    jQuery("#username").val(emailsrc);

                }
</script>