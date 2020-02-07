<?php
/**
 * Deadstock Add Master form
 */
$mob = new Sales($customerno, $userid);

if( $_SESSION['role_modal']=="ASM"){
    $salesdata = get_asm_salespersons($_SESSION['userid'],$_SESSION['customerno']);
    $userid = array();   
    foreach($salesdata as $row){
           $userid[] = $row->userid;
       }
      $result = $mob->getdistid($userid); 
}else if($_SESSION['role_modal']=="sales_representative"){
    $result = $mob->getdistid($userid);
}else{
$result = $mob->getdistid();
}
$skulist = $mob->get_styleview();
$catlist = $mob->get_catview();

?>
<br/>
<div class='container'>
    <center>
        <form name="primarysalesform" id="primarysalesform" method="POST" action="sales.php?pg=prisales" onsubmit="addprimarysales();
                return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Primary Sales </th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <?php
                    $usermanager = new UserManager();
                    $getuser = $usermanager->get_user($_SESSION['customerno'], $_SESSION['userid']);
                    $asmid = $getuser->heirarchy_id;
                    $salesid = $getuser->id;

                    if ($_SESSION['role_modal']!=="sales_representative") {
                        ?>    
                        <tr><td class='frmlblTd'> Sales Person<span class="mandatory">*</span></td><td>
                                <select name="srcode" id="srcode" style="width:250px;">
                                    <?php 
                                    foreach($salesdata as $row){
                                    ?>
                                    <option value="<?php echo $row->userid;?>"><?php echo $row->realname;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr>

                    </tr>
                <td class='frmlblTd'> Category <span class="mandatory">*</span> </td><td>
                    <select name="catid" id="categoryid">
                        <option value="">select</option>    
                        <?php
                        if (isset($catlist)) {
                            foreach ($catlist as $cat) {
                                ?>  
                                <option value="<?php echo $cat->categoryid; ?>"><?php echo $cat->categoryname; ?></option>
                                <?php
                            }
                        }
                        ?>  

                    </select>


                <tr>
                    <td class='frmlblTd'> SKU <span class="mandatory">*</span> </td><td>
                        <select name="skuid" id="skuid" style="width:250px;">
                            <option value="">select</option>    
                            <?php
                            if (isset($skulist)) {
                                foreach ($skulist as $sku) {
                                    ?>  
                                    <option value="<?php echo $sku->styleid; ?>"><?php echo $sku->styleno; ?></option>
                                    <?php
                                }
                            }
                            ?>  

                        </select>
                    </td>
                </tr>


                <tr>
                    <td class='frmlblTd'> Distributor <span class="mandatory">*</span> </td><td>
                        <select name="distid" id="distid" style="width:250px;">
                            <option value="">select</option>    
                            <?php
                            if (isset($result)) {
                                $i = 0;
                                foreach ($result as $row) {
                                    ?>  
                                    <option value="<?php echo $row->distid; ?>"><?php echo $row->distname; ?></option>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>  
                        </select>
                    </td>
                </tr>
                <tr><td class='frmlblTd'> No. Of Cartons </td><td><input type="number" name='cartons' id='cartons'/></td></tr>  
                <tr>
                    <td class='frmlblTd'> Expected Delivery Date </td>
                    <td>
                        <input type="text" name="STdate" id="SDate" />
                        <input id="STime" class="input-mini" type="text" data-date="00:00" name="STime" />
                    </td>
                </tr>
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Send For Approval" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
            <?php 
            if( $_SESSION['role_modal']=="sales_representative"){
            ?>
            <input type="hidden" id="srcode" name="srcode" value="<?php echo $salesid; ?>">
            <input type="hidden" id="asmid" name="asmid" value="<?php echo $asmid; ?>">
            <?php }?>
        </form>
    </center>
</div>