<?php
/**
 * View expense
 */
$expense_list = get_expense($_SESSION['customerno']);

?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    //#viewcategory_filter{display: none}
    .dataTables_length{display: none}
</style>    
<br/>

<div class='container' style="width:70%;">
    <div style="float:right;">
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewCategory'/>
        <table class='table table-bordered ' id="viewCategory" style="width:50%;">
            <thead>
                <tr class='dtblTh'>
                    <th>Driver</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Expanse Date</th>
                    <th>Edit</th>
<!--                    <th>Delete</th>-->
                </tr>
            </thead>

            <?php
            if (isset($expense_list)) {
                foreach ($expense_list as $row) {
                    ?>  
                    <tr>
                        <td><?php echo $row['drivername'];?></td>
                        <td><?php echo $row['categoryname'];?></td>
                        <td><?php echo $row['amount'];?></td>
                        <td><?php echo date("d-m-Y",strtotime($row['expence_date'])) ;?></td>
                        
                        <td style="width:10%; text-align: center;"> 
                            <?php $expid =  $row['expid']; ?>
                            <a href="expensemng.php?id=3&expid=<?php echo $expid; ?>"><img src="../../images/edit.png"></a>
                        </td>
<!--                        <td style="width:10%; text-align: center;">
                            <a href="#" onclick="deletecategory(<?php echo $expid;?>);"><img src="../../images/delete.png"></a>
                        </td>-->
                    </tr>
                <?php
                }
            }
            ?>
                    <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'];?>">    
                    <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid'];?>">    
        </table>
    </center>
</div>


<script>
function deletecategory(catid){
   var test  = confirm("Are you sure you want to delete");
   var customerno = jQuery("#customerno").val();
   var userid = jQuery("#userid").val();
   
   if(test==true){
    jQuery.ajax({
           type: "POST",
           url:"../expense/expense_ajax.php",
           data:"userid="+userid+"&customerno="+customerno+"&catid="+catid+"&action=deletecategory",
           success:function(result){
//               var obj = jQuery.parseJSON(result);
//               if(obj.amount!=""){
//                   jQuery("#amount_"+test).html("");
//                   jQuery("#amount_"+test).html(obj.amount);
//               }
    //windows.reload();
            }
        });
    
   }
   return false;
}
</script>