<?php
/**
 * View Orders interface
 */
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$getproductlist = $sales->getproductlist();
$getstagedataselect = $sales->getstagedataselect();
$getordersource = $sales->getordersourceselect();
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #vieworder_filter{display: none}
    .dataTables_length{display: none}
    
nav ul{height:auto; min-height: 70px; width:150px;}
nav ul{overflow:hidden; overflow-y:scroll;}
nav ul{list-style-type: none;}

</style>
<br/>
<div class='container'>
    <div style="float:right;">
<!--        <a href="salesengage.php?pg=view-importclient"><img src="../../images/import.png"></a>-->
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addorder();">
            Add New Order <img src="../../images/order-192.png"/>
        </button>
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewOrder'/>
        <table class='display table table-bordered table-striped table-condensed' id="vieworder" >
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td><input type="text" class='search_init' name='clientid' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='stageid' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='expectedordercomplitiondate' autocomplete="off"/></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class='dtblTh'>
                    <th>Order Id</th>
                    <th>Activity</th>
                    <th>Client</th>
                    <th>Stage </th>
                    <th>Stage Completion Date</th>
                    <th>Product List</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
        </table>
    </center>
</div>


<!--Add Orders pop starts----->
<div id='addOrderBuble' class="bubble row" oncontextmenu="return false;" style="position: absolute; ">
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
                <div class="col-xs-12">
                    <h4 style='text-align:center;'>Add New Order</h4>
                    <div id='ajaxBstatus'></div>
                    <table class="table showtable">
                        <tbody>
                            
                            <tr><td>Client <span class="mandatory">*</span>:</td>
                                <td>
                                    <input type="text" id="clientnameauto" name="clientnameauto" value='' required>
                                    <input type='hidden' id='clientid' name='clientid' value="">   
                                    <a href="javascript:void(0);" id="clientaddpop">&nbsp;Add New Client</a>
                                </td>
                            </tr>
                            <tr><td>Stage <span class="mandatory">*</span> :</td>
                                <td>
                                    <select name="stageid" id="stageid">
                                    <option value="">Select</option>
                                    <?php 
                                    if(!empty($getstagedataselect)){
                                    foreach($getstagedataselect as $row){    
                                    ?>
                                    <option value="<?php echo $row['id'];?>" <?php if($row["value"]=="Enquiry"){ echo "selected"; }?>  ><?php echo $row["value"];?></option>
                                    <?php
                                    }
                                    }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <tr><td>Stage Date :</td>
                                <td>
                                    <input type="text" name="eocd" id='eocd' value="<?php echo date("d-m-Y");?>">
                                </td></tr>
                            <tr><td>Order Source <span class="mandatory">*</span> :</td>
                                <td>
                                    <select name="ordersource" id="ordersource">
                                    <option value="">Select</option>
                                    <option value="0">Other</option>
                                    <?php 
                                    if(!empty($getordersource)){
                                    foreach($getordersource as $row){    
                                    ?>
                                        <option value="<?php echo $row['id'];?>"><?php echo $row["value"];?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr><td>Request For :</td>
                                <td>
                                    <input type="checkbox" name="emailchk" id="emailchk" checked="checked" value="1"/> Email 
                                 <input type="checkbox" name="smschk" id="smschk" value="1" checked="checked"/> SMS    
                                </td>
                            </tr>
                            <tr>
                                <td>Additional Cost:</td>
                                <td>
                                    <input type="number" name="additionalcost" id='additionalcost'>
                                </td>
                            </tr>
                             <tr>
                                <td>Total Cost:</td>
                                <td>
                                   <input type="number" name="totalcost" id='totalcost'>
                                </td>
                            </tr>
                            <tr>
                                <td class="frmlblTd">Products</td>
                                <td>
                                    <nav>
                                         <ul>
                                        <?php 
                                         if(isset($getproductlist)){
                                            foreach($getproductlist as $row){
                                                echo "<li><input type='checkbox' value='".$row['id']."' name='productlist'  id='productlist' class='productlist'> ".$row['value']."</li>";
                                            }   
                                         }
                                        ?>     
                                        </ul>
                                   </nav>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
        </div>
        <div class='row'>
             <div class='col-xs-12' style='text-align:right;'>
                 <input type="submit" class="btn btn-primary" value="Submit" id="addclientdata" onclick="addorderdatapop();"/> 
                 <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change status pop ends-->



<!--Add client pop starts----->
<div id='addClientBuble' class="bubble row" style='position: absolute;' oncontextmenu="return false;">
    
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