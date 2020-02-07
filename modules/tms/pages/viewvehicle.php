<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #vehiclemaster_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
    <!--
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addVehicelType();">Add Vehicle <img src="../../images/show.png"></button>
    </div>
    -->
    <center>
    <form style='display:inline;width: 70%;' >
        <div class="input-prepend ">
        <span class="add-on" style="text-align: left;">Vehicle NO</span>
        <input type="text"  name="transportercode" id="transportercode" value="" maxlength="50"/>
        <span class="add-on" style="text-align: left;">Vehicle Type</span>
        <input type="text"  name="transportername" id="transportername" value="" maxlength="11"/>
       
        <span class="add-on" style="text-align: ">Transporter</span>
        <select id="zoneid" name="transporterid" >
            <option value="0">Select Transporter</option>
            
        </select>
        <input style='display:inline;' type='button' class="btn  btn-primary" value='  Add Vehicle  '/>
        </div>
     </form>
        </center>
    
</div>    
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='vehicleMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="vehiclemaster" style="width: 90%;" >
            <thead>
                <tr>
                    <td><input type="text" class='search_init' style="width:50%;" name='plant_id'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                    <td colspan="2"></td>



                </tr>
                <tr class='dtblTh'>
                    <th width="10%">Sr. No</th>
                    <th >Vehicle</th>
                    <th >Type </th>
                    <th >Transporter </th>
                    <th colspan="2">Action</th>

                    <!--
                    <th></th>
                    -->
                </tr>
            </thead>
        </table>


        <!--       
               <div id='addActivityformBuble'  class="bubble_tms row" style='position: absolute;' >
           <div class="col-xs-12" >
               <div class='row'>
                   <div class="col-xs-12 bubbleclose" >X</div>
               </div>
               <div class='row'>
                   <div class="col-xs-12">
                       <h4 style='text-align:center;'>Add Vehicle</h4>
                       <div id='ajaxBstatus'></div>
                       <table  class="table showtable" style="max-width: 90%">
                           <tbody>
                               <tr>
                                   <td class='frmlblTd'>Remind me for <span class="mandatory">*</span></td>
                                   <td>
                                       <select name="remid" id="remid">
                                           <option value="">Select</option>
        <?php
        if (!empty($getreminder)) {
            ?>
                                                   <option value="<?php echo $getreminder[0]["id"]; ?>"><?php echo $getreminder[0]["value"]; ?></option>
            <?php
        }
        ?>
                                       </select>
                                   </td>
                               </tr>
                               <tr><td class='frmlblTd'>Notes</td><td><textarea name="notes" id="notes"></textarea></td></tr>
                               <tr><td class='frmlblTd'>Remind me at</td><td>
                                       <input style='display: inline-block; width:135px;' type='text' name='activitytime' id='activitytime' placeholder='Date'/><input type="date" style='display: inline-block;' data-date="00:00" class="input-mini" name="STime" id="STime">
                                   </td></tr>
                               <tr><td class='frmlblTd'>Remind me before in Minutes</td><td><input type="number" name="activityrduration" id="activityrduration"></td></tr>
                               <tr><td class='frmlblTd'>Request For </td><td><input type="checkbox" name="emailreq" id="emailreq" value="1" checked/> Email <input type="checkbox" name="smsreq" id="smsreq" value="1" checked> SMS </td></tr>
                               <tr><td class='frmlblTd'>Payment Amount </td><td><input type="text" name="paymentamt" id="paymentamt"></td></tr>
                               <tr><td class='frmlblTd'>Activity Type <span class="mandatory">*</span></td><td><input type="radio" name="activitytype" checked value="1"> Client <input type="radio" name="activitytype" value="2"> Self</td></tr>
                           </tbody>
                       </table>
                   </div>
               </div>
               <div class='row'>
                   <div class='col-xs-12' style='text-align:right;'>
                       <input type='hidden' id='orderid' name='orderid' value='<?php echo $id; ?>'>
                       <input type="submit" class="btn" value="Submit" id="addactivitydata" onclick="addactivitydatapop();"/>
                       <input type="submit" class="btn btn-primary bubbleclose" value="Close" /></div>
               </div><br/>
           </div>
       </div>
               
               
               
        -->      











    </center>
</div>
<!--Add Activity pop starts----->

<!--change template pop ends-->​
