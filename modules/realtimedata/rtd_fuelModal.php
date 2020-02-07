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
                            <span class="add-on" style="color:#000;">Date</span><input id="SDate" name="SDate" type="text" value="<?php echo date('d-m-Y', $StartDate); ?>" required/>
                            <span class="add-on" style="color:#000;">Time</span><input id="STime" name="STime" type="text" class="input-mini" data-date="<?php echo $stime; ?>" value="<?php echo $stime; ?>"/>
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
                        <input type="hidden" id="customerno" name="customerno"  value="<?php echo $_SESSION['customerno']; ?>">
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
