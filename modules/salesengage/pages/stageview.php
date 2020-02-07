<?php
/**
 * View Stage interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<style>
    #viewstage_filter{display: none}
    .dataTables_length{display: none}
</style>
<div class='container'>
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addstage();">Add New Stage <img src="../../images/show.png"></button>
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewStage'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewstage">
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='stagename' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                <td></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
                <th>Stage Name</th>
                <th>Entry Date</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
    </table>
    </center>
</div>


<!--Add client pop starts----->
<div id='addStageBuble' class="bubble row" oncontextmenu="return false;">
    
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
                <div class="col-xs-12">
                    <h4 style='text-align:center;'>Add Stage</h4>
                    <div id='ajaxBstatus'></div>
                    <table class="table showtable">
                        <tbody>
                            <tr>
                                <td>Name <span class="mandatory">*</span>:</td>
                                <td><input type="text" name="stagename" id="stagename"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
        </div>
        <div class='row'>
             <div class='col-xs-12' style='text-align:right;'>
                 <input type="submit" class="btn btn-primary" value="Submit" id="addclientdata" onclick="addstagedatapop();"/> 
                 <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change status pop ends-->