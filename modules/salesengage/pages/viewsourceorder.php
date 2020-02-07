<?php
/**
 * View Template interface
 */
echo "<script src='" . $_SESSION['subdir'] . "/scripts/tinymce/tinymce.min.js' type='text/javascript'></script>";
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno, $userid);
$reminderlist = $sales->getreminderdataselect();
$stagelist = $sales->getstagedataselect();
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #viewsourceorder_filter{display: none}
    .dataTables_length{display: none}
    .bubbleclosetemp{
        text-align: right;
        cursor:pointer;
    }
</style>
<br/>
<div class='container'>
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addsourceorder();">Add New Order Source <img src="../../images/show.png"></button>
    </div>
    <div style="clear:both;"></div>
    <center>
        <input type='hidden' id='forTable' value='viewSourceorder'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewsourceorder">
            <thead>
                <tr>

                    
                    <td><input type="text" class='search_init' name='source_order' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='date' autocomplete="off"/></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class='dtblTh'>
<!--                    <th>Id</th>-->
                    <th>Order Source</th>
                    <th>Entry Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
        </table>
    </center>
</div>


<!--Add templates pop starts----->
<div id='addSourceorderformBuble'  class="bubble row" style='position: absolute;' >
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h4 style='text-align:center;'>Order Source </h4>
                <div id='ajaxBstatus'></div>
                <table  class="table showtable">
                    <tbody>
                        
                        <tr>
                            <td class="frmlblTd">Order Source</td>
                            <td>
                            <input type="text" name="order_source" id="order_source"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class='row'>
            <div class='col-xs-12' style='text-align:right;'>
                <input type="submit" class="btn btn-primary" value="Submit" id="addreminderdata" onclick="addordersourcepop();"/> 
                <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change template pop ends-->


