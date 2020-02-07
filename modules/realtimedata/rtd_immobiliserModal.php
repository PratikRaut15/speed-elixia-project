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
                <input type="hidden" id="cusromerno" name="customerno"  value="<?php echo $_SESSION['customerno']; ?>">
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
