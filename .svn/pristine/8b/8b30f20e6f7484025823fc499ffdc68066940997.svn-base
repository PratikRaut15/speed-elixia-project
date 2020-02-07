<div id="ehide" class="hide">
<li>
    <div  style="margin-bottom:5px;" >User Login</div>
    <form method="POST" action="modules/user/route.php" id="auth">
    <ul>
        <li><input type="text" name="username" id="username" placeholder="Username" autofocus OnKeyDown="onKey(event);" style="width:130px;"></li>
        <li><input type="password" name="password" id="password" placeholder="Password" OnKeyDown="onKey(event);" style="width:130px;"></li>
        <!--<li><input type="checkbox" name="rememberme" id="rememberme"  checked="1"  /> &nbsp;Remember me</li>-->
        <li id="incorrect" class="off" style="display: none;">Incorrect Username/Password</li>
        <li id="admin" class="off" style="display: none;">Your Group has been deleted</li>
        <li id="correct" class="off" style="display: none;">Getting You Inside</li>
        <li><input type = "button" class="btn  btn-primary" value = "Sign In" onclick="login();" ></li>
    </ul>
</li>
<li>
    
	<div  style="margin-bottom:5px;" >Lost Password</div>
    <ul>
        <li><input type = "text" name = "uname" id="uname" placeholder = "Username" style="width:130px;"></li>
		        <li id="wuser" name="wuser" style="display: none;"> Invalid username </li>
        <li id="message" name="message" style="display: none;"> You will receive a new password </li>
        <li id="noemail" name="noemail" style="display: none;"> We don't have your email please contact an elixir </li>
    
        <li><input type = "button" class="btn  btn-primary" value = "Retrieve" onclick="genNewPass();"></li>

	</ul>
</li>
<li>
   
	<div  style="margin-bottom:5px;" >Client Code</div>
    <ul>
        <li><input type = "text" name = "ecodeid" id = "ecodeid" placeholder ="Enter Client Code"  style="width:130px;" value="<?php echo $_REQUEST['ecodeid']; ?>" ></li>
        <li id="eecode" name="eecode" style="display: none;" class="notok">Invalid / Expired Code</li>
        <li><input class="btn btn-primary" type = "button"  value = "CheckOut" onclick="elixiacode();"></li>
    </ul>
</li>
</div>
<?php 
if($page == 'index.php' ||$page == 'elixiacode.php')
{?>
    <div id="eshow" class="hide">
    <li>
        <h2>Track Vehicle</h2>
<!--        <ul>
            <li>
                <select id="vehicleid" onchange="getvehicle()">
                    <option>Select Vehicle</option>
                </select>
            </li>
        </ul>-->
                <ul>
                <div class="scrollheader">
                        <span class="tw_b">Vehicles 
                        </span>
                        <div class="scroll_head_container" >
                        <label class="all_select scroll_lable tc_blue " data-type="vehicles" title="Click here to show all" >All </label> 
                        <label  class="scroll_lable  ">|</label>
                         <label class="all_clear scroll_lable tc_blue " data-type="vehicles" title="Click here to clear all" >Clear</label>

                        </div>
                        </div>
                    
                <div class="scrollablediv">
                    <?php
//                    include_once("../../lib/bo/DeviceManager.php");
//        $devicemanager = new DeviceManager(0);
//          $devices = $devicemanager->devicesformappingwithecode($ecodeid);
//                   foreach($devices as $thisdevice) { } ?>
        
                 </div>
            </ul>
    </li>
    <li>
        <ul>
            <li><input type="button" class="btn btn-primary" value="Refresh" onclick="mapvehicles();">&nbsp;<input type="button" class="btn btn-inverse" value="logout" onclick="getout();"></li>
        </ul>
    </li>
    </div>
<?php }?>