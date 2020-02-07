<?php
include 'whrtd_functions.php';

/*ak added, to resolve undefinedindex issue*/
$cust_ecodeid = isset($_SESSION['ecodeid']) ? $_SESSION['ecodeid'] : '';
$cust_e_id = isset($_SESSION['e_id']) ? $_SESSION['e_id'] : '';
/**/  
 $type = "";
?>
<link href='http://fonts.googleapis.com/css?family=Crimson+Text:400,700italic' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type='text/javascript' src='https://www.google.com/jsapi'></script> 
<style type="text/css">
    .rt{
     opacity: 0.6;
    }
.rate3 {
padding:2px;
border: 2px solid #ccc;
border-radius: 5px;
background: #ffffff; /* Old browsers */
background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* IE10+ */
background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
}

.rate4 {
padding:2px;
border: 2px solid #ccc;
border-radius: 5px;

background: #88bfe8; /* Old browsers */
background: -moz-linear-gradient(top, #88bfe8 0%, #70b0e0 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#88bfe8), color-stop(100%,#70b0e0)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* IE10+ */
background: linear-gradient(to bottom, #88bfe8 0%,#70b0e0 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#88bfe8', endColorstr='#70b0e0',GradientType=0 ); /* IE6-9 */

}

.rate5 {
padding:2px;
border: 2px solid #ccc;
border-radius: 5px;
background: #ffffff; /* Old browsers */
background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* IE10+ */
background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
}

.rate6 {
padding:2px;
border: 2px solid #ccc;
border-radius: 5px;

background: #88bfe8; /* Old browsers */
background: -moz-linear-gradient(top, #88bfe8 0%, #70b0e0 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#88bfe8), color-stop(100%,#70b0e0)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* IE10+ */
background: linear-gradient(to bottom, #88bfe8 0%,#70b0e0 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#88bfe8', endColorstr='#70b0e0',GradientType=0 ); /* IE6-9 */

}


.vote {
height:30px;
width:auto;
margin-bottom:10px;

}
.ek1{
    min-width: 50px;
    max-width: 50px;
}
</style>

<div>
    <?php if($_SESSION['portable'] !='1') { ?>
    <div class="row-fluid">
        <div class="span4" style="margin-top:12px;position: relative;bottom: 15px; left:-15px;">
       <div>
            <?php 
            
            $manager = getManager();
            if(!empty($manager) && !isset($_SESSION['ecodeid']))
            {
                ?>
                
                <div class="stick">
                <ul>
                <li>
                    <a href="#">

                      <p>Your Dedicated Relationship Manager is 
                          <span style="color: red;">  <?php echo $manager->name;?> </span>
                          </br>Call On <span style="color: red;"><?php echo $manager->mobile?> </span>
                          or Mail @ <span style="color: red;"><?php echo $manager->email?></span></p>
                    </a>
                  </li>

                </ul>
                </div>
                <?php
            }
            ?>
            
        </div>
        
        </div>
        
        <div id="loaddata" class="span3">
         
        </div>
        <div  id="loaddata" class="span5"  >
            <?php display_warehouse_status(); ?>
        </div>
       
    </div>
       <?php  } else echo '<div class="row-fluid" style="height:20px;"></div>' ; ?> 
   
</div>

<script type="text/javascript">
        
    </script>
<?php
    if ($_SESSION['Session_UserRole']=='elixir')
    {    
        
?>
<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='warehouse.php?id=1'>Warehouse and Device Data</a></li>";
    else
        echo "<li><a href='warehouse.php?id=1'>Warehouse and Device Data</a></li>";
    if($_GET['id']==3)
        echo"<li><a class='selected' href='realtimedata.php?id=3'>SIM Data</a></li>";
    else
        echo "<li><a href='realtimedata.php?id=3'>SIM Data</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='realtimedata.php?id=4'>Miscellaneous Data</a></li>";
    else
        echo "<li><a href='realtimedata.php?id=4'>Miscellaneous Data</a></li>";
}
else
{
    echo "<li><a class='selected' href='realtimedata.php?id=1'>Warehouse and Device Data</a></li>";
    //echo "<li><a href='realtimedata.php?id=3'>SIM Data</a></li>";
    //echo "<li><a href='realtimedata.php?id=4'>Miscellaneous Data</a></li>";
}
?>
</ul>
<?php
    }
   
if(!isset($_GET['id']) || $_GET['id']==1)
{
?>
    <div id="rec">
        <?php
        if(!isset($_SESSION['ecodeid']))
        {
            if(isset($_POST['Filter']))
            {
                display_filter_vehicledata($_POST['sel_status'],$_POST['sel_stoppage'],$_POST['vehicleid']);
            }
            else
            {
                if($_SESSION['customerno'] == 177){
                    display_vehicledata_fassos();
                }else{
                    display_vehicledata();
                }
            }
            ?>
            <center>
              <div>
                    <?php if(!isset($_SESSION['ecodeid']) || $_SESSION['customerno']!=177){
                    include '../speed_dashboard/route_dashboard_functions.php';
                    include '../speed_dashboard/pages/viewvehicles.php';
                    }
                    ?>
              </div>
            </center> 
            <?php
            
        }
        else
        {
            if(isset($_POST['Filter']))
            {
                display_filter_vehicledata($_POST['sel_status'],$_POST['sel_stoppage'],$_POST['vehicleid']);
                 
            }
            else
            {
                display_vehicledata ();
            }
            ?>
            <center>
              <div>
                    <?php if(!isset($_SESSION['ecodeid']) || $_SESSION['customerno']!=177){
                    include '../speed_dashboard/route_dashboard_functions.php';
                    include '../speed_dashboard/pages/viewvehicles.php';
                    }
                    ?>
              </div>
            </center> 
            <?php
        }
        ?>
        
           
            
    </div>
<?php
} 
else if($_GET['id']==3)
{  display_simdata();}
else if($_GET['id']==4)
{ display_misc();}
//echo $_REQUEST['next']."hhhehhehe";
?>
<?php
if(!isset($_POST['STdate'])) { $StartDate = getdate_IST();} else { $StartDate = strtotime ($_POST['STdate']);}
        if(!isset($_POST['STime'])) { $stime = "00:00"; } else { $stime = $_POST['STime']; }
?>
<div id="fuelpost" class="modal hide in" style="width:500px; height:350px; display:none;">
    <center>
        <form class="form-horizontal" id="addfuelentry" method="post" action="realtimedata.php">
        <fieldset>
        <div class="modal-header" >
        <button class="close" data-dismiss="modal">×</button>
        <h4 id="header-1" style="color:#0679c0"></h4>
        </div>
            <div class="modal-body">
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on" style="color:#000;">Enter Fuel</span>
                        <input type="text" size="3" id="fuelstorrage" name="fuelstorrage" maxlength="6"  value=""><span class="add-on" style="color:#000;">Lt</span>
                      </div>
                    <br/><br/>
                    <div class="input-prepend">
                        <span class="add-on" style="color:#000;">Date</span><input id="SDate" name="SDate" type="text" value="<?php echo date('d-m-Y',$StartDate);?>" required/>
                        <span class="add-on" style="color:#000;">Time</span><input id="STime" name="STime" type="text" class="input-mini" data-date="<?php echo $stime;?>" value="<?php echo $stime;?>"/>
                    </div>
                    <br/><br/>
                    <div class="input-prepend">
                        <span class="add-on" style="color:#000;">Average</span>
                        <input type="text" size="3" id="average" name="average" maxlength="4"  value=""><span class="add-on" style="color:#000;">Km/Lt</span>
                        <span class="add-on" style="color:#000;">Fuel Tank Capacity</span>
                        <input type="text" id="fuelcapacity" name="fuelcapacity"  maxlength="3" size="5"  value="" required/><span class="add-on" style="color:#000;">Lt</span>
                        <br/>
                        <span id="fuelerr" style="display: none;">Enter Fuel..</span>
                        <span id="fuelerr1" style="display: none;">Please Enter Max 5 Digit Values Only (eg. 152.32)..</span>
                        <span id="averageerr" style="display: none;">Enter Average..</span>
                        <span id="averageerr1" style="display: none;">Please Enter Numeric Values Only (eg. 52 or 15.2)..</span>
                        <span id="tankerr" style="display: none;">Please Enter Fuel Tank Capacity.</span>
                        <span id="tankerr1" style="display: none;">Please Enter Numbers Only.</span>
                        <span id="dateerr" style="display: none;">Please Select Date..</span>
                        <span id="timeerr" style="display: none;">Please Select Time..</span>
                        <span id="ZeroError" style="display: none;">Values should be greater than 0</span>
                        <span id="capasityerr" style="display: none;">Fuel is exceeds than fuel tank capacity</span>
                    </div>
                    <input type="hidden" id="fuelbalance" name="fuelbalance" value="">
                    <input type="hidden" id="fueltank" name="fueltank"  value="">
                    <input type="hidden" id="vehicleid" name="vehicleid"  value="">
                    <input type="hidden" id="vehicleno" name="vehicleno"  value="">
                    <input type="hidden" id="cusromerno" name="customerno"  value="<?php echo $_SESSION['customerno'];?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                    <br/>
                    <img src="../../images/progressbar.gif" id="loader" alt="Loading" width="50" height="50" style="display: none;"/>
                    
                </div>
            </div>
            <div class="modal-footer">
                
                <button onclick="add_fuel();" name="addfuel" id="save" value="addfuel" data-toggle="modal" class="btn btn-success">Save</button>
    </div>
        </fieldset>
    </form>
    </center>

</div>

<div id="Buzzer" class="modal hide in" style="width:500px; height:190px; display:none;">
    <center>
        <form class="form-horizontal" id="addfuelentry" method="post" action="realtimedata.php">
        <fieldset>
        <div class="modal-header" >
        <button class="close" data-dismiss="modal">×</button>
        <h4 id="header-2" style="color:#0679c0"></h4>
        </div>
            <div class="modal-body">
                <div class="control-group"><img class = 'buzzer' src = '../../images/buzzer.png' title = 'Buzzer'></div>
                <div class="control-group" style="color: #000; height: 3px;">Do You Like To Alarm The Vehicle ?</div>
              
                    <input type="hidden" id="unitno" name="unitno"  value="">
                    <input type="hidden" id="vehicle_id" name="vehicle_id"  value="">
                    <input type="hidden" id="cusromerno" name="customerno"  value="<?php echo $_SESSION['customerno'];?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                                     
               
            </div>
            <div class="modal-footer" style="text-align: center; height: 5px;">
                
                <button onclick="add_buzzer();" name="addfuel" id="save" value="addfuel" data-toggle="modal" class="btn btn-success">Yes</button>
                <button data-dismiss="modal" class="btn btn-success">No</button>
            </div>
        </fieldset>
    </form>
    </center>

</div>
    
    <div id="BuzzerNot" class="modal hide in" style="width:500px; height:250px; display:none;">
    <center>
        <form class="form-horizontal" id="addfuelentry" method="post" action="realtimedata.php">
        <fieldset>
        <div class="modal-header" >
        <button class="close" data-dismiss="modal">×</button>
        <h4 id="header-10" style="color:#0679c0"></h4>
        </div>
            <div class="modal-body">
                <div class="control-group"><img class = 'buzzer' src = '../../images/buzzer.png' title = 'Buzzer'></div>
                <div class="control-group" style="color: #000; height: 3px;">Buzzer Not Installed For This Vehicle <br/>* Note: For further information please contact an elixir.<br/></div>
            </div>
            <br/>
            <div class="" style="text-align: center; height: 5px;">
               <button data-dismiss="modal" class="btn btn-success">OK</button>
            </div>
        </fieldset>
    </form>
    </center>

</div>
    
<div id="Driver" class="modal hide in" style="width:700px; height:490px; display: none;">
     <?php
     $dm = new DriverManager($_SESSION['customerno']);
     $drivers = $dm->get_all_drivers_allocated();
     $license_text = getcustombyid(14, 'License No');
     ?>
    <center>
        <form class="form-horizontal" id="addfuelentry" method="post" action="realtimedata.php">
        <fieldset>
        <div class="modal-header" >
        <button class="close" data-dismiss="modal">×</button>
        <h4 id="header-4" style="color:#0679c0"></h4>
        </div>
            <div class="modal-body">
                
                
                <div class="control-group">
                    <span style="color: #000;">Existing Drivers</span>
                    <span>
                        <select name="drivers" id="vdriver">
                            <option value="0">Select Driver</option>
                            <?php
                            if(isset($drivers))
                            {
                                foreach($drivers as $driver)
                                {
                                    echo "<option value='".$driver->driverid."'>". $driver->drivername . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </span>
                    <br/><span id="drivererr" style="display: none;">Please Select Driver</span>
                    
                </div>
                
                    <input type="hidden" id="unitnodriver" name="unitno"  value="">
                    <input type="hidden" id="driverid" name="driverid"  value="">
                    <input type="hidden" id="vehicle_driver_id" name="vehicle_id"  value="">
                    <input type="hidden" id="customerno" name="customerno"  value="<?php echo $_SESSION['customerno'];?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                    <div class="control-group">
                        <button  onclick="update_driver();" name="updatedriver" id="updatedriver" value="addfuel" data-toggle="modal" class="btn btn-success">Save</button>
                        <button data-dismiss="modal" class="btn btn-success">Cancel</button>
                    </div>                    
               <br/>
               <div class="control-group" style="color: #000;">
                        OR
               </div>
               <div class="control-group" style="color: #000;">Enter Driver Details</div>
               <div class="control-group">
                   <div class="input-prepend">
                       <span class="add-on" style="color: #000;">Name</span>
                        <span><input type="text" name="dname" id="dname" value="" placeholder="Driver Name"/></span>
                        <br/><span id="drivererr1" style="display: none;">Please Enter Driver Name</span>
                   </div>
                   <br/>
                   <br/>
                   <div class="input-prepend">
                       <span class="add-on" style="color: #000;"><?php echo $license_text; ?></span>
                        <span><input type="text" name="dlic" id="dlic" value="" placeholder="Driver <?php echo $license_text; ?>"/></span>
                        
                   </div>
                   <br/>
                   <br/>
                   <div class="input-prepend">
                       <span class="add-on" style="color: #000;">Phone No</span>
                        <span><input type="text" name="dphone" id="dphone" value="" placeholder="Driver Phone No"/></span>
                   </div>
               </div>
               <div class="control-group">
                        <button  onclick="add_newdriver();" name="addfuel" id="save" value="addnewdriver" data-toggle="modal" class="btn btn-success">Save</button>
                        <button data-dismiss="modal" class="btn btn-success">Cancel</button>
                </div>
            </div>
            
                
                
          
        </fieldset>
    </form>
    </center>

</div>    
    
<div id="Immobiliser" class="modal hide in" style="width:500px; height:240px; display:none;">
    <center>
        <form class="form-horizontal" id="addfuelentry" method="post" action="realtimedata.php">
        <fieldset>
        <div class="modal-header" >
        <button class="close" data-dismiss="modal">×</button>
        <h4 id="header-5" style="color:#0679c0"></h4>
        </div>
            <div class="modal-body">
                <div class="control-group">
                    
                    <img class = 'buzzer' src = '../../images/immobiliser.png' id="lock" title = 'Immobiliser' style="display: none;">
                    <img class = 'buzzer' src = '../../images/immobiliser1.png' id="start" title = 'Immobiliser' style="display: none;"></div>
                
                <div class="control-group" id="text-immobilise" style="color: #000; height: 3px;"></div>
              
                    <input type="hidden" id="unitno" name="unitno"  value="">
                    <input type="hidden" id="vehicle_id" name="vehicle_id"  value="">
                    <input type="hidden" id="cusromerno" name="customerno"  value="<?php echo $_SESSION['customerno'];?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                    <input type="hidden" id="statuscommand" name="statuscommand"  value="">
                                     
               
            </div>
            <br/>
            <br/>
         
            <div style="text-align: center; height: 5px;">
                
                <button onclick="add_immobiliser();" name="savemobilier" id="save_mobiliser" value="addfuel" data-toggle="modal" class="btn btn-success">Yes</button>
                <button data-dismiss="modal" id="no_mobiliser" class="btn btn-success">No</button>
                <button data-dismiss="modal" id="ok_mobiliser" class="btn btn-success" style="display: none;">OK</button>
            </div>
        </fieldset>
    </form>
    </center>

</div>
    
<?php
// For Realtime Slide map width 
if(!isset($_SESSION['ecodeid'])){
    echo '<input type="hidden" name="wwidth" id="wwidth" value="75">';    
    }else{
     echo '<input type="hidden" name="wwidth" id="wwidth" value="100">';    
    }
?>

    
    <script type="text/javascript">
var test = 0+",";
jQuery('.rate4').live('click',function() {
   
        jQuery(this).attr('class', 'b-s-t Ye rate3');
        var ecodeid = '<?php echo $cust_ecodeid;?>';
        var userrole = '<?php echo $_SESSION['Session_UserRole'];?>';
        var e_id = '<?php echo $cust_e_id;?>';
        var grp = '<?php echo $_SESSION['groupid'];?>';
        var buzzer = '<?php echo $_SESSION['buzzer'];?>'; 
        var customerno = jQuery('#customerno').val();
       // var test='';
       // test += this.id+",";
        test = test.replace(this.id+",", "");
       var test1 = test.replace(0+",", "");
        
       //alert(test); 
       jQuery.ajax({
                    type: "GET",
                    url: "searchfilter.php",
                    data: "customer_no="+customerno+"&sel_status="+test1+"&userrole="+userrole+"&ecodeid="+ecodeid+"&eid="+e_id+"&grp="+grp+"&buzzer="+buzzer ,
                    success: function(html){
                        jQuery("#rec").html(html);
                        loadrefresh();
                    }
            });

});

jQuery('.rate3').live('click',function() {

       
        jQuery(this).attr('class', 'b-s-t Ye rate4');
        var ecodeid = '<?php echo $cust_ecodeid; ?>';
        var userrole = '<?php echo $_SESSION['Session_UserRole'];?>';
        var e_id = '<?php echo $cust_e_id;?>';
        var grp = '<?php echo $_SESSION['groupid'];?>';
        var buzzer = '<?php echo $_SESSION['buzzer'];?>';
        var customerno = jQuery('#customerno').val();
         test += this.id+",";
        var test1 = test.replace(0+",", "");
       //alert(test);
       jQuery.ajax({
                    type: "GET",
                    url: "searchfilter.php",
                    data: "customer_no="+customerno+"&sel_status="+test1+"&userrole="+userrole+"&ecodeid="+ecodeid+"&eid="+e_id+"&grp="+grp+"&buzzer="+buzzer ,
                    success: function(html){
                        jQuery("#rec").html(html);
                        loadrefresh();
                    }
            });
        
});


jQuery('.rate6').live('click',function() {
   
        jQuery(this).attr('class', 'b-s-t Ye rate5');
        var ecodeid = '<?php echo $cust_ecodeid;?>';
        var userrole = '<?php echo $_SESSION['Session_UserRole'];?>';
        var e_id = '<?php echo $cust_e_id;?>';
        var grp = '<?php echo $_SESSION['groupid'];?>';
        var buzzer = '<?php echo $_SESSION['buzzer'];?>'; 
        var customerno = jQuery('#customerno').val();
       // var test='';
       // test += this.id+",";
        test = test.replace(this.id+",", "");
       var test1 = test.replace(0+",", "");
        
       //alert(test); 
       jQuery.ajax({
                    type: "GET",
                    url: "searchfilter.php",
                    data: "customer_no="+customerno+"&sel_status="+test1+"&userrole="+userrole+"&ecodeid="+ecodeid+"&eid="+e_id+"&grp="+grp+"&buzzer="+buzzer ,
                    success: function(html){
                        jQuery("#rec").html(html);
                        loadrefresh();
                    }
            });

});

jQuery('.rate5').live('click',function() {

       
        jQuery(this).attr('class', 'b-s-t Ye rate6');
        var ecodeid = '<?php echo $cust_ecodeid; ?>';
        var userrole = '<?php echo $_SESSION['Session_UserRole'];?>';
        var e_id = '<?php echo $cust_e_id;?>';
        var grp = '<?php echo $_SESSION['groupid'];?>';
        var buzzer = '<?php echo $_SESSION['buzzer'];?>';
        var customerno = jQuery('#customerno').val();
         test += this.id+",";
        var test1 = test.replace(0+",", "");
       //alert(test);
       jQuery.ajax({
                    type: "GET",
                    url: "searchfilter.php",
                    data: "customer_no="+customerno+"&sel_status="+test1+"&userrole="+userrole+"&ecodeid="+ecodeid+"&eid="+e_id+"&grp="+grp+"&buzzer="+buzzer ,
                    success: function(html){
                        jQuery("#rec").html(html);
                        loadrefresh();
                    }
            });
        
});


</script>