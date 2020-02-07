<?php
/**
 * Area Master form
 */
$catid = $_GET["catid"];
if(isset($catid)){
    $catdetails = get_editcategory($_SESSION["customerno"],$catid);
}
?>
<style>
    #catform{
        width:50%;
    }
</style>
<br/>
<div class='container'>
    <center>
        <form name="catform" id="catform" method="POST" action="expense.php?id=2" onsubmit="editcategory(); return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Category Edit</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'> Category Name <span class="mandatory">*</span></td><td><input type="text" name="categoryname" id="categoryname" value="<?php echo $catdetails[0]['categoryname'];?>"></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
                    <input type="hidden" name="catid" id="catid" value="<?php echo $catdetails[0]['categoryid'];?>">
                    <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'];?>">
                <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid'];?>">
                </tbody>
            </table>
        </form>
    </center>
</div>


<script>

function editcategory(){
    var catname = jQuery("#categoryname").val();
    var customerno = jQuery("#customerno").val();
    var userid = jQuery("#userid").val();
    var catid = jQuery("#catid").val();
    
    if(catname!=""){
    
    jQuery.ajax({
           type: "POST",
           url:"../expense/expense_ajax.php",
           data:"catname="+catname+"&userid="+userid+"&customerno="+customerno+"&catid="+catid+"&action=editcategory",
           success:function(result){
//               var obj = jQuery.parseJSON(result);
//               if(obj.amount!=""){
//                   jQuery("#amount_"+test).html("");
//                   jQuery("#amount_"+test).html(obj.amount);
//               }
            }
        });
    }else{
        alert("Please enter categoryname");
        return false;
    }
}
</script>
