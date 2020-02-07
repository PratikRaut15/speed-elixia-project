<div id="Mute" class="modal hide in" style="width:600px; height:190px; display:none;">
    <center>
        <form class="form-horizontal" id="mutevehicle" method="post">
            <fieldset>
                <div class="modal-header" >
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 id="header-mute" style="color:#0679c0"></h4>
                </div>
                <div class="modal-body">
                    <div class="control-group"></div>
                    <div class="control-group" id='alertmsg' style="color: #000; height: 3px;"></div>
                    <div class="control-group" id='notemsg' style="color: #000; height: 3px;"></div>
                    <input type="hidden" id="temp" name="temp"  value="">
                    <input type="hidden" id="condition" name="condition"  value="">
                    <input type="hidden" id="vehicle_id" name="vehicle_id"  value="">
                    <input type="hidden" id="cusromerno" name="customerno"  value="<?php echo $_SESSION['customerno']; ?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                </div>
                <div class="modal-footer" style="text-align: center; height: 5px;">
                    <button onclick="updateVehicleMute();" name="addfuel" id="save" value="addfuel" data-toggle="modal" class="btn btn-success">Yes</button>
                    <button data-dismiss="modal" class="btn btn-success">No</button>
                </div>
            </fieldset>
        </form>
    </center>
</div>
