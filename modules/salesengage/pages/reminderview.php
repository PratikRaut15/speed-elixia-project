<?php
/**
 * Reminder Stage interface
 */
?>
<style>
    #viewreminder_filter{display: none}
    .dataTables_length{display: none}
</style>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<div class='container' >
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addreminder();">Add New Reminder <img src="../../images/mailreminder.png"></button>
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewReminder'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewreminder">
            <thead>
                <tr>
                    <td><input type="text" class='search_init' name='remindername' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class='dtblTh'>
                    <th>Reminder Name</th>
                    <th>Entry Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
        </table>
    </center>
</div>



<!--Add reminder pop starts----->
<div id='addReminderBuble' class="bubble row" oncontextmenu="return false;">
    
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
                <div class="col-xs-12">
                    <h4 style='text-align:center;'>Add Reminder</h4>
                    <div id='ajaxBstatus'></div>
                    <table class="table showtable">
                        <tbody>
                            <tr>
                                <td>Name <span class="mandatory">*</span>:</td>
                                <td><input type="text" name="rname" id="rname"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
        </div>
        <div class='row'>
             <div class='col-xs-12' style='text-align:right;'>
                 <input type="submit" class="btn btn-primary" value="Submit" id="addreminderdata" onclick="addreminderdatapop();"/> 
                 <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change status pop ends-->


