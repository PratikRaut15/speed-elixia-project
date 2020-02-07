<div id="Driver" class="modal hide in" style="width:700px; height:490px; display: none;">
    <?php
        $dm = new DriverManager($_SESSION['customerno']);
        $drivers = $dm->get_all_drivers_allocated();
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
                        <span style="color: #000;">Existing <?php echo $driverLabel; ?>s</span>
                        <span>
                            <select name="drivers" id="vdriver">
                                <option value="0">Select <?php echo $driverLabel; ?></option>
                                <?php
                                    if (isset($drivers)) {
                                        foreach ($drivers as $driver) {
                                            echo "<option value='" . $driver->driverid . "'>" . $driver->drivername . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </span>
                        <br/><span id="drivererr" style="display: none;">Please Select <?php echo $driverLabel; ?></span>
                    </div>
                    <input type="hidden" id="unitnodriver" name="unitno"  value="">
                    <input type="hidden" id="driverid" name="driverid"  value="">
                    <input type="hidden" id="vehicle_driver_id" name="vehicle_id"  value="">
                    <input type="hidden" id="customerno" name="customerno"  value="<?php echo $_SESSION['customerno']; ?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                    <div class="control-group">
                        <button  onclick="update_driver();" name="updatedriver" id="updatedriver" value="addfuel" data-toggle="modal" class="btn btn-success">Save</button>
                        <button data-dismiss="modal" class="btn btn-success">Cancel</button>
                    </div>
                    <br/>
                    <div class="control-group" style="color: #000;">
                        OR
                    </div>
                    <div class="control-group" style="color: #000;">Enter <?php echo $driverLabel; ?> Details</div>
                    <div class="control-group">
                        <div class="input-prepend">
                            <span class="add-on" style="color: #000;">Name</span>
                            <span><input type="text" name="dname" id="dname" value="" placeholder="<?php echo $driverLabel; ?> Name"/></span>
                            <br/><span id="drivererr1" style="display: none;">Please Enter  <?php echo $driverLabel; ?> Name</span>
                        </div>
                        <br/>
                        <br/>
                        <div class="input-prepend">
                            <span class="add-on" style="color: #000;"><?php echo $_SESSION["licno"]; ?></span>
                            <span><input type="text" name="dlic" id="dlic" value="" placeholder="<?php echo $driverLabel; ?><?php echo $_SESSION["licno"]; ?>"/></span>
                        </div>
                        <br/>
                        <br/>
                        <div class="input-prepend">
                            <span class="add-on" style="color: #000;">Phone No</span>
                            <span><input type="text" name="dphone" id="dphone" value="" placeholder="<?php echo $driverLabel; ?> Phone No"/></span>
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
