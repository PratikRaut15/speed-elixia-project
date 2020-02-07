<?php
/**
 * Edit Order master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno, $userid);

$id = $_GET['id'];
if ($id == "" || $id == "0") {
    header('location:salesengage.php?pg=view-order');
}
$getorderdata = $sales->getorderdata_byid($id);
$getproductlist = $sales->getorderproductlist_byid($id);
$getproductlist_old = $sales->getproductlist();
$getlostreasons = $sales->getlostreasons();

if (!empty($getproductlist)) {
    foreach ($getproductlist as $row) {
        $productids[] = $row['productid'];
    }
}
$eocd1 = date("d-m-Y", strtotime($getorderdata[0]['lastdate']));
$getstagedataselect = $sales->getstagedataselect();
$getordersource = $sales->getordersourceselect();
?>
<style>
nav ul{height:auto; min-height: 70px; width:150px;}
nav ul{overflow:hidden; overflow-y:scroll;}
nav ul{list-style-type: none;}
</style>
<br/>
<div class='container'>

    <center>

        <form name="editorderform" id="editorderform" method="POST"  onsubmit="editorderdata();
                return false;">
            <table class='table table-condensed'>
                <thead>
                    <tr><td colspan="100%" class="tdnone">
                            <div>
                                <a href="salesengage.php?pg=view-order" class="backtextstyle"> Back To Order View</a>
                            </div>
                        </td>
                    </tr>
                    <tr><th colspan="100%">Update Order</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr>
                        <td class='frmlblTd'>Client <span class="mandatory">*</span></td>
                        <td>
                            <input type="text" id="clientnameauto1" name="clientnameauto" value='<?php echo $getorderdata[0]['name']; ?>' required>
                            <input type='hidden' id='clientid1' name='clientid' value="<?php echo $getorderdata[0]['clientid']; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Stage <span class="mandatory">*</span></td>
                        <td>
                            <select name="stageid1" id="stageid1">
                                <option value="">Select</option>
                                <?php
                                if (!empty($getstagedataselect)) {
                                    foreach ($getstagedataselect as $row) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>" <?php
                                        if ($getorderdata[0]['stageid'] == $row['id']) {
                                            echo"selected";
                                        }
                                        ?>><?php echo $row["value"]; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>
                    <tr id="lost_category">
                        <td class='frmlblTd'>Lost Reasons</td>
                        <td>
                            <select name="lostreasons" id="lostreasons">
                                <option value="0">Select</option>
                                <?php 
                                if(!empty($getlostreasons)){
                                    foreach ($getlostreasons as $row){
                                    ?>
                                        <option value="<?php echo $row['id'];?>"><?php echo $row['value'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>    
                    </tr>
                    <tr id="lost_notes">
                        <td class='frmlblTd'>Lost Notes</td>
                        <td><textarea name="lostnotes" id="lostnotes"></textarea></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Stage Date</td>
                        <td><input type="text" name="eocd" id='eocd' value="<?php echo $eocd1; ?>"></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Order Source <span class="mandatory">*</span> </td>
                            <td>
                                <select name="ordersource" id="ordersource">
                                <option value="">Select</option>
                                <option value="0">Other</option>
                                <?php 
                                if(!empty($getordersource)){
                                foreach($getordersource as $row){    
                                ?>
                                    <option value="<?php echo $row['id'];?>" <?php if($getorderdata[0]["srcorderid"]==$row['id'] ){ echo"selected";} ?>  ><?php echo $row["value"];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </td>
                    </tr>
                    
                    <tr>
                        <td class="frmlblTd">Request For</td>
                        <td><input type="checkbox" name="emailchk" id="emailchk" value="1" <?php
                            if ($getorderdata[0]['isemailrequested'] == "1") {
                                echo 'checked';
                            }
                            ?> /> Email <input type="checkbox" name="smschk" id="smschk" value="1" <?php
                                   if ($getorderdata[0]['issmsrequested'] == "1") {
                                       echo 'checked';
                                   }
                                   ?> /> SMS </td>
                    </tr>
                    <tr>
                        <td class="frmlblTd">Additional Cost</td>
                        <td><input type="number" name="additionalcost" id='additionalcost' value="<?php echo $getorderdata[0]['additionalcost']; ?>"></td>
                    </tr>
                    <tr>
                        <td class="frmlblTd">Total Cost</td>
                        <td><input type="number" name="totalcost" id='totalcost' value="<?php echo $getorderdata[0]['totalamount']; ?>"></td>
                    </tr>
                    
                    <tr>
                                <td class="frmlblTd">Products</td>
                                <td>
                                    <nav>
                                         <ul>
                                        <?php 
                                         if(isset($getproductlist_old)){
                                            foreach($getproductlist_old as $row){
                                            $selected="";
                                            if (!empty($productids)) {
                                                if (in_array($row['id'], $productids)) {
                                                    $selected =  "checked";
                                                }
                                            }    
                                                
                                            ?>
                                             <li>
                                                 <input type='checkbox' value='<?php echo $row['id']?>' <?php echo $selected ;?> name='productlist1[]'  id='productlist' class='productlist'> <?php echo $row['value']; ?>
                                             </li>
                                            <?php
                                             }   
                                         }
                                        ?>     
                                        </ul>
                                   </nav>
                                    
                                </td>
                            </tr>
                    
                  
                    <tr><td colspan="100%" class='frmlblTd'>
                            <input type="submit" name="stagesubmit" value="Update" class='btn btn-primary'>
        <!--                    <input type="button" class="btn-info" value="View Orders">-->

                        </td></tr>
                </tbody>
            </table>
            <input type="hidden" name="orderid" id="orderid" value="<?php echo $getorderdata[0]['orderid']; ?>">
        </form>
    </center>
</div>
