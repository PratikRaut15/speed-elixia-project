<?php
/**
 * Add Order master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$getproductlist = $sales->getproductlist();
$getstagedataselect = $sales->getstagedataselect();
?>
<br/>
<div class='container'>
    <center>
    <form name="orderform" id="orderform" method="POST"  onsubmit="addorderdataa();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Add Order</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr>
                <td class='frmlblTd'>Client Name <span class="mandatory">*</span></td>
                <td>
                    <input type="text" id="clientnameauto" name="clientnameauto" value='' required>
                    <input type='hidden' id='clientid' name='clientid' value="">
                    
                    <a href="javascript:void(0);" id="clientaddpop">&nbsp;Add New Client</a>
                </td>
            </tr>
            <tr>
                <td class='frmlblTd'>Stage Name <span class="mandatory">*</span></td>
                <td>
                    <select name="stageid" id="stageid">
                        <option value="">Select</option>
                        <?php 
                        if(!empty($getstagedataselect)){
                        foreach($getstagedataselect as $row){    
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row["value"];?></option>
                        <?php
                        }
                        }
                        ?>
                    </select>
                    
                </td>
            </tr>
            <tr>
                <td class='frmlblTd'>Stage Date</td>
                <td><input type="text" name="eocd" id='eocd'></td>
            </tr>
            <tr>
                <td class="frmlblTd">Request For</td>
                <td><input type="checkbox" name="emailchk" id="emailchk" value="1"/> Email <input type="checkbox" name="smschk" id="smschk" value="1"/> SMS </td>
            </tr>
             <tr>
                <td class="frmlblTd">Additional Cost</td>
                <td><input type="number" name="additionalcost" id='additionalcost'></td>
            </tr>
            <tr>
                <td class="frmlblTd">Total Cost</td>
                <td><input type="number" name="totalcost" id='totalcost'></td>
            </tr>
            <tr><th colspan="100%"> Product Map</th></tr>
            <tr>
                <td class="frmlblTd">Products</td>
                <td>
                    <select name="productlist[]" id="productlist" multiple style="width:200px;">
                    <?php
                    if(isset($getproductlist)){
                        foreach($getproductlist as $row){
                    ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['value'];?></option>
                    <?php    
                        }
                    }
                    ?>    
                    </select>
                </td>
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="stagesubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
<!--addclientBuble-->

<!--Add client pop starts----->
<div id='addClientBuble' class="bubble row" oncontextmenu="return false;">
    
    <div class="col-xs-12" >
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
                            <tr><td>email <span class="mandatory">*</span>:</td><td><input type="text" name="cemail" id="cemail"></td></tr>
                            <tr><td>Mobile <span class="mandatory">*</span> :</td><td><input type="text" name="cmobile" id="cmobile"></td></tr>
                            <tr><td>Birth Date:</td><td><input type="text" name="cbirthdate" id="cbirthdate"></td></tr>
                        </tbody>
                    </table>

                </div>
        </div>
        <div class='row'>
             <div class='col-xs-12' style='text-align:right;'>
                 <input type="submit" class="btn" value="Submit" id="addclientdata" onclick="addclientdatapop();"/> 
                 <input type="submit" class="btn btn-primary bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change status pop ends-->


<!--change status pop ends-->
