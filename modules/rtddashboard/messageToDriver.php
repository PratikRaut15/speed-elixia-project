<div id="MessageToDriver" class="modal hide in" style="width:550px; height:300px; display: none;">
                <center>
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="modal-header" >
                                <button class="close" data-dismiss="modal">×</button>
                                <h4 id="header-6" style="color:#0679c0"></h4>
                            </div>
                            <div class="modal-body">
                                <div class="control-group">
                                    <span style="color: #000;">Enter Message </span><textarea id="messageText" cols="40"></textarea><div style="font-size: 12px;color: black;text-align: right;margin-right: 100px;">Characters:&nbsp;<span id="count"></span></div>
                                    <br/><span id="drivermsgerr1" style="display: none;">Please Enter Driver Number</span>
                                </div>
                            </div>
                            <div  class="control-group">
                                <input type="hidden" id="vehicle_id" value="">
                                <input type="hidden" id="driver_name" value="">
                                <input type="hidden" id="driver_no" value="">
                                <div id="pageloaddiv" style='display:none;'></div>
                                <button  onclick="sendMessage()" name="smsToDriver" id="smsToDriver" value="Send" data-toggle="modal" class="btn btn-success">Send</button>
                                <button data-dismiss="modal" class="btn btn-success">Cancel</button>
                            </div>
                            <div>
                                <span>Note :</span><span style="color: #000;"> If message exceeds <span style="color: red;">160 characters</span> will considered as extra message.</span>
                            </div>
                        </fieldset>
                    </form>
                </center>
            </div>
