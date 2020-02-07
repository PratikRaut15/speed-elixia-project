<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #viewclient_filter{display: none}
    .dataTables_length{display: none}
</style>    
<br/>

<div class='container' >
    <div style="float:right;">
        <a href="salesengage.php?pg=view-importclient"><img src="../../images/import.png" alt="Excel Import for client"></a>
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addclient();">Add New Client <img src="../../images/Clients.png"></button>
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewClient'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewclient">
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='name' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='address' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='email' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='mobileno' autocomplete="off"/></td>
                <td></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
                <th>Client Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Mobile No.</th>
<!--                <th>Entry Date</th>-->
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
    </table>
    </center>
</div>


<!--Add client pop starts----->
<div id='addClientBuble' class="bubble row" oncontextmenu="return false;">
    
    <div class="col-xs-12">
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
                <div class="col-xs-12">
                    <h3 style='text-align:center;'>Add Client</h3>
                    <div id='ajaxBstatus'></div>
                    <table class="table showtable">
                        <tbody>
                            <tr><td>Name <span class="mandatory">*</span>:</td><td><input type="text" name="clname" id="clname"></td></tr>
                            <tr><td>Address :</td><td><textarea name="caddress" id="caddress"></textarea></td></tr>
                            <tr><td>email <span class="mandatory">*</span>:</td><td><input type="text" name="cemail" id="cemail"style="width:100%;"><br><span style="font-size: 9px; font-weight: bold; ">You can add multiple email ids with Comma separated.<br/>For eg:- test@gmail.com,test1@gmail.com</span></td></tr>
                            <tr><td>Mobile <span class="mandatory">*</span> :</td><td><input type="text" name="cmobile" id="cmobile"></td></tr>
                            <tr><td>Birth Date:</td><td><input type="text" name="cbirthdate" id="cbirthdate"></td></tr>
                        </tbody>
                    </table>

                </div>
        </div>
        <div class='row'>
             <div class='col-xs-12' style='text-align:right;'>
                 <input type="submit" class="btn btn-primary" value="Submit" id="addclientdata" onclick="addclientdatapop();"/> 
                 <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change status pop ends-->