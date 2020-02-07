<?php
/**
 * Update Feedback master form
 */
require_once "mobility_function.php";
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=add-city');
}
$mob = new Mobility($_SESSION['customerno'], $_SESSION['userid']);
$feedbackq = $mob->getfeedbackdata_byid($id);
$question = $feedbackq[0]['question'];
$fid = $feedbackq[0]['id'];
$optiondata = $mob->getoptiondata($fid);
$optionscount = count($optiondata);
?>
<br/>
<div class='container'>
    <center>
    <form name="editfeedbackform" id="editfeedbackform" method="POST" action="mobility.php?pg=edit-feedback" onsubmit="editfeedback();return false;">
    <table class='table table-condensed' id='feedback'>
        <thead><tr><th colspan="100%" >Update Feedback</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Question <span class="mandatory">*</span></td><td><input type="text" name="question" id='question' value="<?php if(isset($question)){echo $question; } ?>" style='width:100%;' required></td></tr>
            
           <?php
           if($optionscount >0){
           for($i=0; $i< $optionscount; $i++){
            ?>
                <tr><td class='frmlblTd'>Options</td><td><input type="text" name="option[]" id='option' value="<?php echo $optiondata[$i]['options_val']; ?>">
                <input type="hidden" name="optid[]" id="optid" value="<?php echo $optiondata[$i]['id']; ?>"></td></tr>
            <?php
                }
           }else{
            ?>   
                <tr><td class='frmlblTd'>Options</td><td><input type="text" name="option[]" id='option'></td></tr>
           <?php
            }    
           ?>
            
            
            <tr><td class='frmlblTd'>&nbsp;</td><td><a href="javascript:void(0);" onclick='addrow()'>Add Options</a></td>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="feedbacksubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="fid" id="fid" value="<?php echo $fid;?>">
    </form>
    </center>
</div>
<script>
function addrow(){
    var table = document.getElementById("feedback");
    var row = table.insertRow(5);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML = "&nbsp;";
    cell2.innerHTML = "<input type='text' name='option[]' id='option'>";
}

function deleterow() {
    document.getElementById("feedback").deleteRow(0);
}

</script>

