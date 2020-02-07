<?php
/**
 * Tempate master form
 */
echo "<script src='". $_SESSION['subdir']."/scripts/tinymce/tinymce.min.js' type='text/javascript'></script>";
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);
$reminderlist = $sales->getreminderdataselect(); 
$stagelist = $sales->getstagedataselect(); 
?>
<script type="text/javascript">
tinymce.init({
    mode : "textareas",
    menubar : false,
    editor_selector : "mceEditor",
    editor_deselector : "mceNoEditor",
    elements : "emailtemplate",
    statusbar : false
});

</script>

<br/>   
<div class='container'>
    <center>
    <form name="templatemasterform" id="templatemasterform" method="POST"  onsubmit="addtemplatedata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Add Template</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
             <tr>
                <td class='frmlblTd'>Template Type</td>
                <td>
                    <input type="radio" id="templatetype_reminder" name="templatetype" value='1'> Reminder
                    <input type="radio" id="templatetype_stage" name="templatetype" value='2'> Stages
                </td>
            </tr>

            <tr id="reminder">
                <td class='frmlblTd'>Reminder Name </td>
                <td>
                    <select name="remid" id="remid">
                        <option value="">Select</option>
                        <?php
                        if(isset($reminderlist)){
                            foreach($reminderlist as $row){
                               echo "<option value='".$row['id']."'>".$row['value']."</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="stage">
                <td class='frmlblTd'>Stage Name </td>
                <td>
                     <select name="stageid" id="stageid">
                         <option value="">Select</option>
                        <?php
                        if(isset($stagelist)){
                            foreach($stagelist as $row){
                               echo "<option value='".$row['id']."'>".$row['value']."</option>";
                            }
                        }
                        ?>
                    </select>
                    
                </td>
            </tr>
            
<!--            <tr>
                <td class="frmlblTd">Request For</td>
                <td><input type="checkbox" name="emailchk" id="emailchk" value="1"/> Email <input type="checkbox" name="smschk" id="smschk" value="1"/> SMS </td>
            </tr>-->
            <tr>
                <td class="frmlblTd">Email Subject</td>
                <td>
                    <textarea name="emailsubject"  class="mceNoEditor" cols="40" id="emailsubject"></textarea>
                </td>
            </tr>
            <tr>
                <td class="frmlblTd">Email Template</td>
                <td>
                    <textarea name="emailtemplate" id="emailtemplate" class="mceEditor" cols="40"></textarea>
                </td>
            </tr>
            <tr>
                <td class="frmlblTd">SMS Template</td>
                <td>
                    <textarea name="smstemplate" cols="40" class="mceNoEditor" id="smstemplate"></textarea>
                </td>
            </tr>
            <tr>
                <td class="frmlblTd">Recipient Type</td>
                <td><input type="radio" name="rtype" value="1"> Client <input type="radio" name="rtype" value="2"> Elixir</td> 
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="templatesubmit" value="Add" class='btn btn-primary'><span style="float:right;"> <input type="button" class="btn-info" name="preview" id="preview" value="Preview Template"/></span> </td></tr>
        </tbody>
    </table>
        
    </form>
    </center>
</div>



<!--Add template pop starts----->
<div id='addtemplateBuble' class="bubble row" oncontextmenu="return false;">
    
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <div id="emailsubjectpreview" style="font-style:normal; font-family:sans-serif; font-size:11px; border: 1px solid #EFEFEF; line-height: 20px; margin: 6px; word-wrap:normal; "></div>
                <div style="clear: both;"></div>
                <div id="emailcontent" style="border: 1px solid #EFEFEF; padding:5px; margin:5px; word-wrap:normal;">
                </div>
                
            </div>
        </div>
        <div class='row'>
             <div class='col-xs-12' style='text-align:right;'>
                 <input type="submit" class="btn btn-primary bubbleclose" value="Close" /></div>
        </div><br/>
        
    </div>
</div>
<!--change status pop ends-->
