<?php
/**
 * View Distributor interface
 */
$catres = get_allcategory($customerno);
//echo"<pre>";
//print_r($catres);
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
                    <th>Category Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <?php
            if (isset($catres)) {
                foreach ($catres as $row) {
                    ?>  
                    <tr>
                        <td style="width:50%;"><?php echo $row['categoryname'];?></td>
                        <td style="width:10%; text-align: center;"> 
                            <?php $catid =  $row['categoryid']; ?>
                            <a href="expense.php?id=3&catid=<?php echo $catid; ?>"><img src="../../images/edit.png"></a>
                        </td>
                        <td style="width:10%; text-align: center;">
                            <a href="#" onclick="deletecategory(<?php echo $catid;?>);"><img src="../../images/delete.png"></a>
                        </td>
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