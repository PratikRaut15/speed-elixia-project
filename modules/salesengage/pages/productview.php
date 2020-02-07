<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #viewproduct_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addproduct();">Add New Product <img src="../../images/show.png"></button>
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewProduct'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewproduct">
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='productname' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='unit_price' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                <td></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
                <th>Product Name</th>
                <th>Unit Price</th>
                <th>Entry Date</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
    </table>
    </center>
</div>


<!--Add product pop starts----->
<div id='addProductBuble' class="bubble row" oncontextmenu="return false;">
    
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
                <div class="col-xs-12">
                    <h4 style='text-align:center;'>Add Product</h4>
                    <div id='ajaxBstatus'></div>
                    <table class="table showtable">
                        <tbody>
                            <tr>
                                <td>Product Name <span class="mandatory">*</span>:</td>
                                <td><input type="text" name="pname" id="pname"></td>
                            </tr>
                            <tr>
                                <td>Unit Price </td>
                                <td><input id="unitprice" type="number" name="unitprice"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
        </div>
        <div class='row'>
             <div class='col-xs-12' style='text-align:right;'>
                 <input type="submit" class="btn btn-primary" value="Submit" id="addproductdata" onclick="addproductdatapop();"/> 
                 <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change product status pop ends-->