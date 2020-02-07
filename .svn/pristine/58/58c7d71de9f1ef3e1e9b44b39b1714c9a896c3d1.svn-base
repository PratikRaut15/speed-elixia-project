<?php
/**
 * Edit template master form
 */
echo "<script src='". $_SESSION['subdir']."/scripts/tinymce/tinymce.min.js' type='text/javascript'></script>";
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);
$reminderlist = $sales->getreminderdataselect(); 
$stagelist = $sales->getstagedataselect(); 

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-template');
}
$gettemplatedata = $sales->gettemplatedata_byid($id);

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
    <form name="edittemplatemasterform" id="edittemplatemasterform" method="POST"  onsubmit="edittemplatedata(); return false;">
    <table class='table table-condensed'>
        <thead>
            <tr>
                <td colspan="100%" class="tdnone">
                    <div>
                        <a href="salesengage.php?pg=view-template" class="backtextstyle"> Back To Templates View </button></a>
                    </div>
                </td>
            </tr>
            <tr><th colspan="100%" >Update Template</th></tr>
        </thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
             <tr>
                <td class='frmlblTd'>Template Type</td>
                <td>
                    <input type="radio" id="templatetype_reminder" name="templatetype1" value='1' <?php if($gettemplatedata[0]['typeid']==1){ echo "checked"; }?>> Reminder
                    <input type="radio" id="templatetype_stage" name="templatetype1" value='2' <?php if($gettemplatedata[0]['typeid']==2){ echo "checked"; }?> > Stages
                </td>
            </tr>

            <tr id="reminder1" <?php if($gettemplatedata[0]['typeid']=='1'){ echo "style='display:table-row'"; }else{ echo "style='display:none'"; } ?>>
                <td class='frmlblTd'>Reminder Name </td>
                <td>
                    <select name="remid" id="remid">
                        <option value="">Select</option>
                        <?php
                        if(isset($reminderlist)){
                            foreach($reminderlist as $row){
                            ?>    
                                <option value="<?php echo $row['id']?>" <?php if($gettemplatedata[0]['reminderid']==$row['id']){ echo "selected"; }?>  ><?php echo $row['value'];?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="stage1" <?php if($gettemplatedata[0]['typeid']=='2'){ echo "style='display:table-row'"; }else{ echo "style='display:none'"; } ?>>
                <td class='frmlblTd'>Stage Name </td>
                <td>
                     <select name="stageid" id="stageid">
                         <option value="">Select</option>
                        <?php
                        if(isset($stagelist)){
                            foreach($stagelist as $row){
                              ?>    
                                    <option value="<?php echo $row['id']?>" <?php if($gettemplatedata[0]['stageid']==$row['id']){ echo "selected"; }?> ><?php echo $row['value'];?></option>
                            <?php
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
                    <textarea name="emailsubject"  class="mceNoEditor" style="resize: none;" cols="37" id="emailsubject"><?php echo ri($gettemplatedata[0]['email_subject']);?></textarea>
                </td>
            </tr>
            <tr>
                <td class="frmlblTd">Email Template</td>
                <td>
                    <textarea name="emailtemplate" id="emailtemplate" class="mceEditor" cols="40"><?php echo ri($gettemplatedata[0]['emailtemplate']);?></textarea>
                </td>
            </tr>
            <tr>
                <td class="frmlblTd">SMS Template</td>
                <td>
                    <textarea name="smstemplate" cols="37" style="resize: none;" class="mceNoEditor" id="smstemplate"><?php echo ri($gettemplatedata[0]['smstemplate']); ?></textarea>
                    <br>
                    <span id="remaining">160 characters remaining</span>
                    <span id="messages">1 message(s)</span>
                </td>
            </tr>
            <tr>
                <td class="frmlblTd">Recipient Type</td>
                <td><input type="radio" name="rtype" value="1" <?php if($gettemplatedata[0]['recipienttype']=='1'){echo"checked";}?> > Client <input type="radio" name="rtype" value="2" <?php if($gettemplatedata[0]['recipienttype']=='2'){echo"checked";}?>  > Self</td>
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="edittemplatesubmit" value="Update" class='btn btn-primary'> 
<!--                    <a href='salesengage.php?pg=view-template'>View template</a>-->
                    <span style="float:right;"> 
                        <a href="javascript:void(0);" class="btn-info" name="editpreview" id="editpreview" style="font-size:12px; font-weight: bold; padding: 3px; text-decoration: none;">Preview Email Template</a>
                    </span></td></tr>
        </tbody>
    </table>
         <input type="hidden" id="templateid" name="templateid" value="<?php echo $gettemplatedata[0]['templateid'];?>">
    </form>
    </center>
</div>


<!--Add template pop starts----->
<div id='edittemplateBuble' class="bubble row" oncontextmenu="return false;">
    
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
