<?php
/**
 * Area Master form
 */

?>
<style>
    #catform{
        width:50%;
    }
</style>
<br/>
<div class='container'>
    <center>
        <form name="catform" id="catform" method="POST" action="expense.php?id=2" onsubmit="addcategory();
            return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Category Add</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'> Category Name <span class="mandatory">*</span></td><td><input type="text" id="categoryname" name="categoryname"></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
                <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'];?>">
                <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid'];?>">
                </tbody>
            </table>
        </form>
    </center>
</div>


<script>
function addcategory(){
    var catname = jQuery("#categoryname").val();
    var customerno = jQuery("#customerno").val();
    var userid = jQuery("#userid").val();
    
    if(catname!=""){
    
    jQuery.ajax({
           type: "POST",
           url:"../expense/expense_ajax.php",
           data:"catname="+catname+"&userid="+userid+"&customerno="+customerno+"&action=addcategory",
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
